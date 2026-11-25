<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StatsService;

class DashboardController extends Controller
{
    public function index(StatsService $statsService)
    {
        $stats = $statsService->getDashboardStats();

        return view('admin.dashboard', $stats);
    }
}
