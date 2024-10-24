<?php

namespace App\Http\Controllers\Admin;

use App\DataTransferObjects\UserFiltersDto;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(public UserService $userService)
    {
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => $this->userService->getUsers(
                UserFiltersDto::fromDataTableRequest($request)
            ),
        ]);
    }
}
