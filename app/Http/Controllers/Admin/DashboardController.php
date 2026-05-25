<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrowsingHistory;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        return view('admin.dashboard', [
            'stats' => [
                'users' => User::count(),
                'sales' => Order::sum('total'),
                'products' => Product::count(),
                'events' => BrowsingHistory::count(),
            ],
            'trending' => Product::with('brand')->orderByDesc('views_count')->limit(8)->get(),
            'categories' => Category::withCount('products')->orderByDesc('products_count')->get(),
            'activity' => BrowsingHistory::with('product')->latest()->limit(10)->get(),
        ]);
    }
}
