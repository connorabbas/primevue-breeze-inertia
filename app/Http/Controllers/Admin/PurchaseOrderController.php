<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\PurchaseOrderFiltersDto;
use App\Services\PurchaseOrderService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PurchaseOrderController extends Controller
{
    public function __construct(protected PurchaseOrderService $purchaseOrderService) {}

    public function index(Request $request): Response
    {
        $purchaseOrders = $this->purchaseOrderService->getPurchaseOrders(
            PurchaseOrderFiltersDto::fromDataTableRequest($request)
        );

        return Inertia::render('Business/PurchaseOrders/Index', [
            'urlParams' => $request->all(),
            'purchaseOrders' => $purchaseOrders,
        ]);
    }
}
