<?php

use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\MetricsController;
use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::get('brands/select', [BrandController::class, 'select'])->name('brands.select');

    Route::apiResource('brands', BrandController::class)
        ->only(['index', 'show']);

    Route::apiResource('products', ProductController::class)
        ->only(['index', 'show']);

    Route::get('metrics', MetricsController::class)->name('metrics');
});