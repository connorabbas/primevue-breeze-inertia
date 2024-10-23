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

    public function create(): Response
    {
        // Get self supplier (Doner Industries)
        $selfSupplier = Supplier::where('account_number', 'DI')->firstOrFail();
        Log::info('Self supplier found: ', ['supplier' => $selfSupplier->toArray()]);

        // Get all suppliers for selection
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

        // Get available addresses from self supplier
        $availableAddresses = [
            'billTo' => $selfSupplier->getBillToAddresses(),
            'shipTo' => $selfSupplier->getShipToAddresses(),
        ];

        Log::info('Available addresses:', $availableAddresses);

        return Inertia::render('PurchaseOrders/CreatePurchaseOrder', [
            'initialData' => [
                'availableSuppliers' => $suppliers,
                'defaultTaxRate' => config('purchase_orders.default_tax_rate', 8.25),
                'settings' => [
                    'minQuantity' => 0,
                    'defaultLeadDays' => 0,
                    'requireShippingAddress' => true,
                ],
                'defaultAddresses' => $availableAddresses
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

    private function cleanAddressData($addresses)
    {
        $cleaned = [];
        foreach ($addresses as $type => $address) {
            if ($address) {
                // If address has a 'value' property (from select component), use that
                $addressData = isset($address['value']) ? $address['value'] : $address;

                // Clean up the address data to only include valid fields
                $cleanedAddress = array_intersect_key($addressData, array_flip([
                    'street1',
                    'street2',
                    'city',
                    'state',
                    'postal_code',
                    'country',
                    'type',
                    'address1',
                    'address2',
                    'state_prov_code',
                    'zip',
                    'phone_number',
                    'email_address'
                ]));

                // Wrap single address in array for DTO
                $cleaned[$type] = [$cleanedAddress];
            } else {
                $cleaned[$type] = [];
            }
        }

        return $cleaned;
    }

    public function store(StorePurchaseOrderRequest $request)
    {
        try {
            $validatedData = $request->validated();
            Log::info('Validated data:', $validatedData);

            // Clean up address data
            $validatedData['addresses'] = $this->cleanAddressData($validatedData['addresses']);
            Log::info('Cleaned addresses:', $validatedData['addresses']);

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

    public function draft(StorePurchaseOrderRequest $request)
    {
        try {
            $validatedData = $request->validated();
            Log::info('Validated draft data:', $validatedData);

            // Clean up address data
            $validatedData['addresses'] = $this->cleanAddressData($validatedData['addresses']);
            Log::info('Cleaned addresses:', $validatedData['addresses']);

            $validatedData['addresses'] = SupplierAddressesDTO::fromArray($validatedData['addresses']);
            $purchaseOrder = $this->purchaseOrderService->saveDraft($validatedData);

            return redirect()
                ->route('purchase-orders.show', $purchaseOrder->id)
                ->with('success', 'Purchase order draft saved successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to save purchase order draft', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $validatedData ?? null
            ]);

            return back()->withErrors(['error' => 'Failed to save draft']);
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
