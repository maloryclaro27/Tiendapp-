<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('reference', 50);
            $table->timestamps();
            $table->softDeletes();

            $table->unique('reference', 'idx_brands_reference');
            $table->index('name', 'idx_brands_name');
            $table->index(['deleted_at', 'name'], 'idx_brands_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};