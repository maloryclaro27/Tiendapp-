<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_metrics_endpoint_returns_successful_json_response(): void
    {
        $this->getJson('/api/v1/metrics')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'total_brands',
                    'total_products',
                    'total_inventory_units',
                    'available_products',
                    'healthy_stock_products',
                    'low_stock_products',
                    'out_of_stock_products',
                    'stock_alerts',
                    'stock_health_percent',
                    'available_percent',
                    'healthy_stock_percent',
                    'low_stock_percent',
                    'out_of_stock_percent',
                ],
            ]);
    }
}