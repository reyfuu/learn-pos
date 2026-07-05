<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $todayRevenue = \App\Models\Transaction::whereDate('created_at', today())->sum('grand_total');
        $totalTransactions = \App\Models\Transaction::count();
        $totalProducts = \App\Models\Product::count();
        $recentTransactions = \App\Models\Transaction::latest()->take(5)->get();

        return view('dashboard', compact('todayRevenue', 'totalTransactions', 'totalProducts', 'recentTransactions'));
    }
}
