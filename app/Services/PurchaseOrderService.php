<?php

namespace App\Services;

use App\DataTransferObjects\PurchaseOrderFiltersDto;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderPart;
use App\DTOs\SupplierAddressesDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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

    public function createPurchaseOrder(array $data): PurchaseOrder
    {
        return DB::transaction(function () use ($data) {
            $purchaseOrder = PurchaseOrder::create([
                'supplier_id' => $data['supplier_id'],
                'addresses' => $data['addresses'],
                'special_instructions' => $data['special_instructions'],
                'tax_rate' => $data['tax_rate'],
                'additional_costs' => $data['additional_costs'],
                'status' => 'draft',
            ]);

            foreach ($data['parts'] as $part) {
                PurchaseOrderPart::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'part_id' => $part['part_id'],
                    'quantity_ordered' => $part['quantity_ordered'],
                    'unit_cost' => $part['unit_cost'],
                    'total_cost' => $part['quantity_ordered'] * $part['unit_cost'],
                ]);
            }

            $purchaseOrder->calculateTotals();
            $purchaseOrder->save();

            return $purchaseOrder;
        });
    }

    public function getPurchaseOrder($id): PurchaseOrder
    {
        return PurchaseOrder::with(['supplier', 'parts.part'])->findOrFail($id);
    }
}
