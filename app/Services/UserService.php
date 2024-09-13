<?php

namespace App\Services;

use App\DataTransferObjects\UserFiltersDto;
use App\Models\User;

class UserService
{
    public function getUsers(UserFiltersDto $filters): mixed
    {
        $query = User::query()
            ->when($filters->name, function ($query) use ($filters) {
                $query->where('name', 'like', "%" . $filters->name . "%");
            })
            ->when($filters->email, function ($query) use ($filters) {
                $query->where('email', 'like', "%" . $filters->email . "%");
            });

        if ($filters->sortField && $filters->sortDirection) {
            $query->orderBy($filters->sortField, $filters->sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $results = ($filters->perPage && $filters->currentPage)
            ? $query->paginate(perPage: $filters->perPage, page: $filters->currentPage)
            : $query->get();

        return $results;
    }
}
