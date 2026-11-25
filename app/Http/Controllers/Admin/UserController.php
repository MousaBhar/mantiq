<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')
            ->latest()
            ->paginate(10);

        $totalUsers = User::count();

        return view('admin.users.index', compact(
            'users',
            'totalUsers'
        ));
    }
}
