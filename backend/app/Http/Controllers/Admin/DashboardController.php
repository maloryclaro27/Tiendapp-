<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $metrics = [
            'total_brands' => Brand::query()->count(),
            'total_products' => Product::query()->count(),
            'total_inventory_units' => (int) Product::query()->sum('quantity_in_inventory'),
            'out_of_stock_products' => Product::query()
                ->where('quantity_in_inventory', 0)
                ->count(),
            'low_stock_products' => Product::query()
                ->whereBetween('quantity_in_inventory', [1, 10])
                ->count(),
        ];

        $recentProducts = Product::query()
            ->with('brand')
            ->latest('inventory_updated_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'metrics' => $metrics,
            'recentProducts' => $recentProducts,
        ]);
    }
}