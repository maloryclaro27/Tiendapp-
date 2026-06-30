<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropIndex('idx_brands_name');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_stock');
            $table->dropIndex('idx_products_inv_updated');
            $table->dropIndex('idx_products_unit');

            $table->index(
                ['deleted_at', 'inventory_updated_at'],
                'idx_products_active_inv_updated'
            );

            $table->index(
                ['deleted_at', 'brand_id', 'inventory_updated_at'],
                'idx_products_active_brand_inv_updated'
            );

            $table->index(
                ['deleted_at', 'unit_of_measure', 'inventory_updated_at'],
                'idx_products_active_unit_inv_updated'
            );

            $table->index(
                ['deleted_at', 'brand_id', 'unit_of_measure', 'inventory_updated_at'],
                'idx_products_active_brand_unit_inv_updated'
            );
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_active_brand_unit_inv_updated');
            $table->dropIndex('idx_products_active_unit_inv_updated');
            $table->dropIndex('idx_products_active_brand_inv_updated');
            $table->dropIndex('idx_products_active_inv_updated');

            $table->index('unit_of_measure', 'idx_products_unit');
            $table->index('inventory_updated_at', 'idx_products_inv_updated');
            $table->index('quantity_in_inventory', 'idx_products_stock');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->index('name', 'idx_brands_name');
        });
    }
};
