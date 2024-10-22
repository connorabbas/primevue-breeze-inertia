<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\PurchaseOrderFiltersDto;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Services\PurchaseOrderService;
use App\DTOs\SupplierAddressesDTO;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class PurchaseOrderController extends Controller
{
    public function __construct(protected PurchaseOrderService $purchaseOrderService) {}

    // PurchaseOrderController.php
    public function create(): Response
    {
        $suppliers = Supplier::query()
            ->with(['parts' => function ($query) {
                $query->select([
                    'id',
                    'supplier_id',
                    'part_number',
                    'description',
                    'replenishment_data'
                ])->whereNotNull('replenishment_data');
            }])
            ->select([
                'id',
                'name',
                'account_number',
                'addresses'
            ])
            ->whereNull('deleted_at')
            ->get();

        // Debug the data before sending to view
        Log::info('Supplier Data:', [
            'count' => $suppliers->count(),
            'first_supplier' => $suppliers->first()?->toArray()
        ]);

        return Inertia::render('PurchaseOrders/CreatePurchaseOrder', [
            'initialData' => [
                'availableSuppliers' => $suppliers,
                'defaultTaxRate' => config('purchase_orders.default_tax_rate', 8.0),
                'debug' => true // Add this for testing
            ]
        ]);
    }

    public function index(Request $request): Response
    {
        try {
            $filters = PurchaseOrderFiltersDto::fromDataTableRequest($request);
            $purchaseOrders = $this->purchaseOrderService->getPurchaseOrders($filters);

            return Inertia::render('PurchaseOrders/Index', [
                'urlParams' => $request->all(),
                'purchaseOrders' => $purchaseOrders,
                'filters' => $filters
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to load purchase orders', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return Inertia::render('PurchaseOrders/Index', [
                'urlParams' => $request->all(),
                'purchaseOrders' => [
                    'data' => [],
                    'total' => 0
                ],
                'error' => 'Failed to load purchase orders'
            ]);
        }
    }

    public function store(StorePurchaseOrderRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['addresses'] = SupplierAddressesDTO::fromArray($validatedData['addresses']);

            $purchaseOrder = $this->purchaseOrderService->createPurchaseOrder($validatedData);

            return redirect()
                ->route('purchase-orders.show', $purchaseOrder->id)
                ->with('success', 'Purchase order created successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to create purchase order', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $validatedData ?? null
            ]);

            return back()->withErrors(['error' => 'Failed to create purchase order']);
        }
    }

    public function show($id): Response
    {
        try {
            $purchaseOrder = $this->purchaseOrderService->getPurchaseOrder($id);

            return Inertia::render('PurchaseOrders/ShowPurchaseOrder', [
                'purchaseOrder' => $purchaseOrder
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to load purchase order', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('purchase-orders.index')
                ->with('error', 'Purchase order not found');
        }
    }
}
