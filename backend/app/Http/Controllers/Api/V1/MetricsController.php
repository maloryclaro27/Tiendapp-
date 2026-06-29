<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\DashboardMetricsService;
use Illuminate\Http\JsonResponse;

class MetricsController extends Controller
{
    public function __invoke(DashboardMetricsService $dashboardMetrics): JsonResponse
    {
        return response()->json([
            'data' => array_merge($dashboardMetrics->metrics(), [
                'recently_updated_products' => $dashboardMetrics->recentlyUpdatedProducts()
                    ->map(fn (Product $product): array => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'brand' => $product->brand?->name,
                        'quantity_in_inventory' => $product->quantity_in_inventory,
                        'inventory_status' => $product->inventory_status,
                        'updated_at' => $product->updated_at?->toISOString(),
                    ]),
            ]),
        ]);
    }
}
