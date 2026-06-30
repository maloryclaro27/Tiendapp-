<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Product;
use Database\Seeders\BrandSeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_available_and_in_stock_filters_return_the_same_products(): void
    {
        $brand = Brand::factory()->create();

        $available = Product::factory()->create([
            'brand_id' => $brand->id,
            'name' => 'Producto disponible',
            'quantity_in_inventory' => 7,
        ]);

        Product::factory()->create([
            'brand_id' => $brand->id,
            'name' => 'Producto sin stock',
            'quantity_in_inventory' => 0,
        ]);

        $availableResponse = $this->getJson('/api/v1/products?availability=available&per_page=50');
        $inStockResponse = $this->getJson('/api/v1/products?availability=in_stock&per_page=50');

        $availableResponse->assertOk();
        $inStockResponse->assertOk();

        $this->assertSame(
            [$available->id],
            collect($availableResponse->json('data'))->pluck('id')->all()
        );

        $this->assertSame(
            collect($availableResponse->json('data'))->pluck('id')->all(),
            collect($inStockResponse->json('data'))->pluck('id')->all()
        );
    }

    public function test_inventory_status_filters_return_correct_counts(): void
    {
        $brand = Brand::factory()->create();

        Product::factory()->create([
            'brand_id' => $brand->id,
            'quantity_in_inventory' => 25,
        ]);

        Product::factory()->create([
            'brand_id' => $brand->id,
            'quantity_in_inventory' => 11,
        ]);

        Product::factory()->create([
            'brand_id' => $brand->id,
            'quantity_in_inventory' => 10,
        ]);

        Product::factory()->create([
            'brand_id' => $brand->id,
            'quantity_in_inventory' => 1,
        ]);

        Product::factory()->create([
            'brand_id' => $brand->id,
            'quantity_in_inventory' => 0,
        ]);

        $healthy = $this->getJson('/api/v1/products?availability=healthy_stock&per_page=50');
        $low = $this->getJson('/api/v1/products?availability=low_stock&per_page=50');
        $out = $this->getJson('/api/v1/products?availability=out_of_stock&per_page=50');

        $healthy->assertOk();
        $low->assertOk();
        $out->assertOk();

        $this->assertCount(2, $healthy->json('data'));
        $this->assertCount(2, $low->json('data'));
        $this->assertCount(1, $out->json('data'));
    }

    public function test_product_resource_exposes_availability_and_inventory_status(): void
    {
        $brand = Brand::factory()->create();

        $healthy = Product::factory()->create([
            'brand_id' => $brand->id,
            'quantity_in_inventory' => 11,
        ]);

        $low = Product::factory()->create([
            'brand_id' => $brand->id,
            'quantity_in_inventory' => 10,
        ]);

        $out = Product::factory()->create([
            'brand_id' => $brand->id,
            'quantity_in_inventory' => 0,
        ]);

        $this->getJson("/api/v1/products/{$healthy->id}")
            ->assertOk()
            ->assertJsonPath('data.is_available', true)
            ->assertJsonPath('data.inventory_status', 'Stock saludable');

        $this->getJson("/api/v1/products/{$low->id}")
            ->assertOk()
            ->assertJsonPath('data.is_available', true)
            ->assertJsonPath('data.inventory_status', 'Bajo stock');

        $this->getJson("/api/v1/products/{$out->id}")
            ->assertOk()
            ->assertJsonPath('data.is_available', false)
            ->assertJsonPath('data.inventory_status', 'Sin stock');
    }

    public function test_metrics_endpoint_returns_totals_percentages_and_alerts(): void
    {
        $brand = Brand::factory()->create();

        Product::factory()->create([
            'brand_id' => $brand->id,
            'quantity_in_inventory' => 25,
        ]);

        Product::factory()->create([
            'brand_id' => $brand->id,
            'quantity_in_inventory' => 11,
        ]);

        Product::factory()->create([
            'brand_id' => $brand->id,
            'quantity_in_inventory' => 10,
        ]);

        Product::factory()->create([
            'brand_id' => $brand->id,
            'quantity_in_inventory' => 0,
        ]);

        $this->getJson('/api/v1/metrics')
            ->assertOk()
            ->assertJsonPath('data.total_brands', 1)
            ->assertJsonPath('data.total_products', 4)
            ->assertJsonPath('data.total_inventory_units', 46)
            ->assertJsonPath('data.available_products', 3)
            ->assertJsonPath('data.healthy_stock_products', 2)
            ->assertJsonPath('data.low_stock_products', 1)
            ->assertJsonPath('data.out_of_stock_products', 1)
            ->assertJsonPath('data.stock_alerts', 2)
            ->assertJsonPath('data.stock_health_percent', 50)
            ->assertJsonPath('data.available_percent', 75)
            ->assertJsonPath('data.healthy_stock_percent', 50)
            ->assertJsonPath('data.low_stock_percent', 25)
            ->assertJsonPath('data.out_of_stock_percent', 25);
    }

    public function test_products_can_be_filtered_by_brand_unit_and_search(): void
    {
        $alpina = Brand::factory()->create(['name' => 'Alpina', 'reference' => 'ALPINA-TEST']);
        $zenu = Brand::factory()->create(['name' => 'Zenu', 'reference' => 'ZENU-TEST']);

        $target = Product::factory()->create([
            'brand_id' => $alpina->id,
            'name' => 'Yogurt Griego Especial',
            'unit_of_measure' => 'Caja',
            'observations' => 'Producto refrigerado para canal institucional.',
            'quantity_in_inventory' => 18,
        ]);

        Product::factory()->create([
            'brand_id' => $zenu->id,
            'name' => 'Salchicha Tradicional',
            'unit_of_measure' => 'Unidad',
            'observations' => 'Producto carnico.',
            'quantity_in_inventory' => 18,
        ]);

        $response = $this->getJson(
            "/api/v1/products?brand_id={$alpina->id}&unit_of_measure=Caja&search=Griego&per_page=50"
        );

        $response->assertOk();

        $this->assertSame(
            [$target->id],
            collect($response->json('data'))->pluck('id')->all()
        );
    }

    public function test_products_can_be_sorted_by_stock_ascending_and_descending(): void
    {
        $brand = Brand::factory()->create();

        $low = Product::factory()->create([
            'brand_id' => $brand->id,
            'name' => 'Stock bajo',
            'quantity_in_inventory' => 3,
        ]);

        $medium = Product::factory()->create([
            'brand_id' => $brand->id,
            'name' => 'Stock medio',
            'quantity_in_inventory' => 15,
        ]);

        $high = Product::factory()->create([
            'brand_id' => $brand->id,
            'name' => 'Stock alto',
            'quantity_in_inventory' => 80,
        ]);

        $asc = $this->getJson('/api/v1/products?sort=stock_asc&per_page=50');
        $desc = $this->getJson('/api/v1/products?sort=stock_desc&per_page=50');

        $asc->assertOk();
        $desc->assertOk();

        $this->assertSame(
            [$low->id, $medium->id, $high->id],
            collect($asc->json('data'))->pluck('id')->all()
        );

        $this->assertSame(
            [$high->id, $medium->id, $low->id],
            collect($desc->json('data'))->pluck('id')->all()
        );
    }

    public function test_product_cannot_be_created_with_soft_deleted_brand(): void
    {
        $brand = Brand::factory()->create();
        $brand->delete();

        $response = $this->from('/admin/products/create')->post('/admin/products', [
            'brand_id' => $brand->id,
            'name' => 'Producto invalido',
            'unit_of_measure' => 'Unidad',
            'observations' => 'No debe crearse porque la marca esta eliminada.',
            'quantity_in_inventory' => 12,
            'inventory_updated_at' => now()->toDateTimeString(),
        ]);

        $response
            ->assertRedirect('/admin/products/create')
            ->assertSessionHasErrors('brand_id');

        $this->assertDatabaseMissing('products', [
            'name' => 'Producto invalido',
        ]);
    }

    public function test_brand_with_active_products_cannot_be_deleted(): void
    {
        $brand = Brand::factory()->create();

        Product::factory()->create([
            'brand_id' => $brand->id,
            'quantity_in_inventory' => 20,
        ]);

        $this->delete("/admin/brands/{$brand->id}")
            ->assertRedirect('/admin/brands')
            ->assertSessionHas('error', 'No se puede eliminar una marca con productos asociados.');

        $this->assertFalse($brand->fresh()->trashed());
    }

    public function test_catalog_seeders_are_idempotent(): void
    {
        $this->seed(BrandSeeder::class);
        $this->seed(ProductSeeder::class);

        $this->assertSame(8, Brand::count());
        $this->assertSame(9, Product::count());

        $this->seed(BrandSeeder::class);
        $this->seed(ProductSeeder::class);

        $this->assertSame(8, Brand::count());
        $this->assertSame(9, Product::count());
        $this->assertSame(205, Product::sum('quantity_in_inventory'));
    }
    public function test_products_api_rejects_invalid_filter_parameters(): void
    {
        $brand = Brand::factory()->create();
        $brand->delete();

        $this->getJson('/api/v1/products?availability=invalid')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('availability');

        $this->getJson('/api/v1/products?sort=invalid')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('sort');

        $this->getJson('/api/v1/products?unit_of_measure=Paquete')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('unit_of_measure');

        $this->getJson("/api/v1/products?brand_id={$brand->id}")
            ->assertUnprocessable()
            ->assertJsonValidationErrors('brand_id');

        $this->getJson('/api/v1/products?per_page=100')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('per_page');
    }

    public function test_brands_api_rejects_invalid_pagination_parameters(): void
    {
        $this->getJson('/api/v1/brands?per_page=100')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('per_page');

        $this->getJson('/api/v1/brands?page=0')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('page');
    }
}
