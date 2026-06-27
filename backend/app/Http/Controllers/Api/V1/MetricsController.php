<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class MetricsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'data' => [
                'total_brands' => Brand::query()->count(),
                'total_products' => Product::query()->count(),
                'total_inventory_units' => (int) Product::query()->sum('quantity_in_inventory'),
                'out_of_stock_products' => Product::query()
                    ->where('quantity_in_inventory', 0)
                    ->count(),
                'low_stock_products' => Product::query()
                    ->whereBetween('quantity_in_inventory', [1, 10])
                    ->count(),
                'recently_updated_products' => Product::query()
                    ->with('brand')
                    ->latest('inventory_updated_at')
                    ->limit(5)
                    ->get()
                    ->map(fn (Product $product) => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'brand' => $product->brand?->name,
                        'quantity_in_inventory' => $product->quantity_in_inventory,
                        'inventory_updated_at' => $product->inventory_updated_at?->toISOString(),
                    ]),
            ],
        ]);
    }
}