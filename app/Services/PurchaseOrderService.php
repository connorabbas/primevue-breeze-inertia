<?php

namespace App\Services;

use App\DataTransferObjects\PurchaseOrderFiltersDto;
use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Builder;

class PurchaseOrderService
{
    public function getPurchaseOrders(PurchaseOrderFiltersDto $filters): mixed
    {
        $query = PurchaseOrder::query()
            ->with(['supplier', 'user']) // Eager load relationships
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
            // Handle relationship sorting
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
}
