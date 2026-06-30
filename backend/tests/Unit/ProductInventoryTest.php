<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestCase;

class ProductInventoryTest extends TestCase
{
    public function test_inventory_status_is_derived_from_quantity_boundaries(): void
    {
        $cases = [
            'negative inventory is out of stock' => [-3, false, 'Sin stock'],
            'zero inventory is out of stock' => [0, false, 'Sin stock'],
            'one unit is low stock' => [1, true, 'Bajo stock'],
            'threshold quantity is low stock' => [Product::LOW_STOCK_MAX, true, 'Bajo stock'],
            'quantity above threshold is healthy stock' => [Product::LOW_STOCK_MAX + 1, true, 'Stock saludable'],
        ];

        foreach ($cases as $case => [$quantity, $expectedAvailability, $expectedStatus]) {
            $product = new Product([
                'quantity_in_inventory' => $quantity,
            ]);

            $this->assertSame(
                $expectedAvailability,
                $product->is_available,
                "Failed asserting availability for case: {$case}."
            );

            $this->assertSame(
                $expectedStatus,
                $product->inventory_status,
                "Failed asserting inventory status for case: {$case}."
            );
        }
    }

    public function test_low_stock_threshold_is_explicit_for_catalog_rules(): void
    {
        $this->assertSame(10, Product::LOW_STOCK_MAX);
    }

    public function test_inventory_accessors_do_not_require_persisted_products(): void
    {
        $product = new Product([
            'quantity_in_inventory' => 5,
        ]);

        $this->assertTrue($product->is_available);
        $this->assertSame('Bajo stock', $product->inventory_status);
        $this->assertNull($product->getKey());
    }
}