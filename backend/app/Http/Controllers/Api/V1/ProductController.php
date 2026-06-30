<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min((int) $request->integer('per_page', 12), 50);

        $products = Product::query()
            ->with('brand')
            ->search($request->string('search')->toString())
            ->byBrand($request->filled('brand_id') ? $request->integer('brand_id') : null)
            ->byUnit($request->string('unit_of_measure')->toString())
            ->withAvailability($request->string('availability')->toString())
            ->sorted($request->string('sort')->toString())
            ->paginate($perPage)
            ->withQueryString();

        return ProductResource::collection($products);
    }

    public function show(Product $product): ProductResource
    {
        $product->load('brand');

        return new ProductResource($product);
    }
}
