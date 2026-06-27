<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('brand_id')
                ->constrained('brands')
                ->restrictOnDelete();

            $table->string('name', 200);
            $table->enum('unit_of_measure', ['Unidad', 'Display', 'Caja']);
            $table->text('observations');
            $table->unsignedInteger('quantity_in_inventory')->default(0);
            $table->timestamp('inventory_updated_at')->useCurrent();

            $table->timestamps();
            $table->softDeletes();

            $table->index('brand_id', 'idx_products_brand');
            $table->index('unit_of_measure', 'idx_products_unit');
            $table->index('quantity_in_inventory', 'idx_products_stock');
            $table->index('inventory_updated_at', 'idx_products_inv_updated');

            $table->index(
                ['deleted_at', 'brand_id', 'unit_of_measure', 'name'],
                'idx_products_catalog'
            );

            $table->index(
                ['deleted_at', 'quantity_in_inventory', 'brand_id'],
                'idx_products_available'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};