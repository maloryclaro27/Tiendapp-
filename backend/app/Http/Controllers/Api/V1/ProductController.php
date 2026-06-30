<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductIndexRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();
        $perPage = (int) ($filters['per_page'] ?? 12);

        $products = Product::query()
            ->with('brand')
            ->search($filters['search'] ?? null)
            ->byBrand($filters['brand_id'] ?? null)
            ->byUnit($filters['unit_of_measure'] ?? null)
            ->withAvailability($filters['availability'] ?? null)
            ->sorted($filters['sort'] ?? null)
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
