<?php

namespace App\Services;

use App\DataTransferObjects\PurchaseOrderFiltersDto;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderPart;
use App\Models\Product;
use App\Models\BillOfMaterial;
use App\DTOs\SupplierAddressesDTO;
use App\Enums\PurchaseOrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseOrderService
{
    public function getPurchaseOrders(PurchaseOrderFiltersDto $filters): mixed
    {
        $query = PurchaseOrder::query()
            ->with(['supplier', 'user'])
            ->when($filters->number, function (Builder $query) use ($filters) {
                $query->where('number', 'like', "%{$filters->number}%");
            })
            ->when($filters->supplier, function (Builder $query) use ($filters) {
                $query->whereHas('supplier', function ($q) use ($filters) {
                    $q->where('name', 'like', "%{$filters->supplier}%");
                });
            })
            ->when($filters->status, function (Builder $query) use ($filters) {
                $query->where('status', $filters->status);
            })
            ->when($filters->created_at, function (Builder $query) use ($filters) {
                $query->whereDate('created_at', $filters->created_at);
            })
            ->when($filters->total_cost, function (Builder $query) use ($filters) {
                $query->where('total_cost', 'like', "%{$filters->total_cost}%");
            })
            ->when($filters->user_name, function (Builder $query) use ($filters) {
                $query->whereHas('user', function ($q) use ($filters) {
                    $q->where('name', 'like', "%{$filters->user_name}%");
                });
            });

        if ($filters->sortField && $filters->sortDirection) {
            if ($filters->sortField === 'supplier.name') {
                $query->join('suppliers', 'purchase_orders.supplier_id', '=', 'suppliers.id')
                    ->orderBy('suppliers.name', $filters->sortDirection);
            } elseif ($filters->sortField === 'user.name') {
                $query->join('users', 'purchase_orders.user_id', '=', 'users.id')
                    ->orderBy('users.name', $filters->sortDirection);
            } else {
                $query->orderBy($filters->sortField, $filters->sortDirection);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return ($filters->perPage && $filters->currentPage)
            ? $query->paginate(perPage: $filters->perPage, page: $filters->currentPage)
            : $query->get();
    }

    public function createPurchaseOrder(array $data, bool $isDraft = false): PurchaseOrder
    {
        Log::info('Creating purchase order with data:', $data);

        return DB::transaction(function () use ($data, $isDraft) {
            try {
                // Create the purchase order
                $purchaseOrder = PurchaseOrder::create([
                    'number' => $data['number'],
                    'supplier_id' => $data['supplier_id'],
                    'location_id' => $data['location_id'] ?? 1,
                    'addresses' => $data['addresses'],
                    'special_instructions' => $data['special_instructions'] ?? null,
                    'tax_rate' => $data['tax_rate'] ?? config('purchase_orders.default_tax_rate', 8.25),
                    'additional_costs' => round($data['additional_costs'] ?? 0.00, 2),
                    'total_cost' => round($data['total_cost'], 2),
                    'user_id' => Auth::id(),
                    'status' => $data['status'] ?? ($isDraft ? PurchaseOrderStatus::DRAFT : PurchaseOrderStatus::SUBMITTED),
                ]);

                Log::info('Created purchase order:', ['id' => $purchaseOrder->id]);

                // Create purchase order parts
                foreach ($data['parts'] as $part) {
                    PurchaseOrderPart::create([
                        'purchase_order_id' => $purchaseOrder->id,
                        'part_id' => $part['part_id'],
                        'quantity_ordered' => $part['quantity_ordered'],
                        'unit_cost' => round($part['unit_cost'], 2),
                        'total_cost' => round($part['total_cost'], 2),
                    ]);
                }

                Log::info('Created purchase order parts');

                // Calculate totals including tax and additional costs
                $purchaseOrder->calculateTotals();
                $purchaseOrder->save();

                Log::info('Calculated totals and saved purchase order');

                return $purchaseOrder->load(['supplier', 'location', 'parts.part']);
            } catch (\Exception $e) {
                Log::error('Failed to create purchase order', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data
                ]);
                throw $e;
            }
        });
    }

    public function saveDraft(array $data): PurchaseOrder
    {
        return $this->createPurchaseOrder($data, true);
    }

    public function getPurchaseOrder($id): PurchaseOrder
    {
        return PurchaseOrder::with(['supplier', 'location', 'parts.part'])->findOrFail($id);
    }

    public function calculateOrderTotals(array $parts, float $taxRate, float $additionalCosts): array
    {
        $subtotal = collect($parts)->sum(function ($part) {
            return round($part['quantity_ordered'] * $part['unit_cost'], 2);
        });

        $taxAmount = round($subtotal * ($taxRate / 100), 2);
        $totalCost = round($subtotal + $taxAmount + $additionalCosts, 2);

        return [
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_cost' => $totalCost
        ];
    }

    /**
     * Calculate required parts from a list of products and their quantities
     *
     * @param array|Collection $products Array or Collection of product_id => quantity pairs
     * @return array Array of calculated part requirements
     */
    public function fromProducts(array|Collection $products): array
    {
        // Convert array to collection if needed
        $products = is_array($products) ? collect($products) : $products;

        Log::info('Calculating parts for products:', $products->toArray());

        // Get all products with their bill of materials
        $productModels = Product::with(['billOfMaterials.part.supplier'])
            ->whereIn('id', $products->keys())
            ->get();

        Log::info('Found products:', ['count' => $productModels->count()]);

        // Initialize parts collection to store required quantities
        $requiredParts = collect();

        // Calculate required quantities for each part
        foreach ($productModels as $product) {
            $productQuantity = $products[$product->id];

            Log::info('Processing product:', [
                'product_id' => $product->id,
                'quantity' => $productQuantity,
                'bom_count' => $product->billOfMaterials->count()
            ]);

            foreach ($product->billOfMaterials as $bom) {
                $partId = $bom->part_id;
                $requiredQuantity = $bom->quantity_required * $productQuantity;

                // Add to or update required parts collection
                if ($requiredParts->has($partId)) {
                    $requiredParts[$partId] = [
                        'part' => $bom->part,
                        'quantity' => $requiredParts[$partId]['quantity'] + $requiredQuantity,
                        'products' => $requiredParts[$partId]['products']->push([
                            'product_id' => $product->id,
                            'product_quantity' => $productQuantity,
                            'quantity_per_product' => $bom->quantity_required,
                            'subtotal_quantity' => $requiredQuantity
                        ])
                    ];
                } else {
                    $requiredParts[$partId] = [
                        'part' => $bom->part,
                        'quantity' => $requiredQuantity,
                        'products' => collect([[
                            'product_id' => $product->id,
                            'product_quantity' => $productQuantity,
                            'quantity_per_product' => $bom->quantity_required,
                            'subtotal_quantity' => $requiredQuantity
                        ]])
                    ];
                }
            }
        }

        // Group parts by supplier
        $partsBySupplier = $requiredParts->groupBy(function ($item) {
            return $item['part']->supplier_id;
        });

        // Log the results
        foreach ($partsBySupplier as $supplierId => $parts) {
            $supplierName = $parts->first()['part']->supplier->name ?? 'Unknown Supplier';

            Log::info("Parts needed from supplier: $supplierName", [
                'supplier_id' => $supplierId,
                'parts' => $parts->map(function ($item) {
                    $unitCost = round($item['part']->unit_cost, 2);
                    $totalCost = round($item['quantity'] * $unitCost, 2);

                    return [
                        'part_number' => $item['part']->part_number,
                        'description' => $item['part']->description,
                        'total_quantity' => $item['quantity'],
                        'unit_cost' => $unitCost,
                        'total_cost' => $totalCost,
                        'used_in_products' => $item['products']->map(function ($usage) {
                            return [
                                'product_id' => $usage['product_id'],
                                'quantity' => $usage['product_quantity'],
                                'quantity_per_unit' => $usage['quantity_per_product']
                            ];
                        })->toArray()
                    ];
                })->toArray()
            ]);
        }

        $totalCost = round($requiredParts->sum(function ($item) {
            return $item['quantity'] * $item['part']->unit_cost;
        }), 2);

        return [
            'parts_by_supplier' => $partsBySupplier->toArray(),
            'total_suppliers' => $partsBySupplier->count(),
            'total_parts' => $requiredParts->count(),
            'total_cost' => $totalCost
        ];
    }
}
