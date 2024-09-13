<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $paginateSize = $request->input('rows', 20);
        $users = User::paginate($paginateSize);

        return Inertia::render('Admin/Users/Index', [
            'urlParams' => $request->all(),
            'users' => $users,
        ]);
    }
}
