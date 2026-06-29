<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class DashboardMetricsService
{
    public function metrics(): array
    {
        $totalProducts = Product::query()->count();
        $availableProducts = Product::query()
            ->where('quantity_in_inventory', '>', Product::LOW_STOCK_MAX)
            ->count();
        $lowStockProducts = Product::query()
            ->whereBetween('quantity_in_inventory', [1, Product::LOW_STOCK_MAX])
            ->count();
        $outOfStockProducts = Product::query()
            ->where('quantity_in_inventory', '<=', 0)
            ->count();

        $stockAlerts = $lowStockProducts + $outOfStockProducts;

        return [
            'total_brands' => Brand::query()->count(),
            'total_products' => $totalProducts,
            'total_inventory_units' => (int) Product::query()->sum('quantity_in_inventory'),
            'available_products' => $availableProducts,
            'low_stock_products' => $lowStockProducts,
            'out_of_stock_products' => $outOfStockProducts,
            'stock_alerts' => $stockAlerts,
            'stock_health_percent' => $this->percentage($availableProducts, $totalProducts, 0),
            'available_percent' => $this->percentage($availableProducts, $totalProducts),
            'low_stock_percent' => $this->percentage($lowStockProducts, $totalProducts),
            'out_of_stock_percent' => $this->percentage($outOfStockProducts, $totalProducts),
        ];
    }

    public function recentlyUpdatedProducts(int $limit = 5): Collection
    {
        return Product::query()
            ->with('brand')
            ->latest('updated_at')
            ->limit($limit)
            ->get();
    }

    private function percentage(int $value, int $total, int $precision = 2): float|int
    {
        if ($total === 0) {
            return 0;
        }

        return round(($value / $total) * 100, $precision);
    }
}
