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
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('observations', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('brand_id'), function ($query) use ($request) {
                $query->where('brand_id', $request->integer('brand_id'));
            })
            ->when($request->filled('unit_of_measure'), function ($query) use ($request) {
                $query->where('unit_of_measure', $request->string('unit_of_measure')->toString());
            })
            ->when($request->filled('availability'), function ($query) use ($request) {
                $availability = $request->string('availability')->toString();

                if ($availability === 'available') {
                    $query->where('quantity_in_inventory', '>', 0);
                }

                if ($availability === 'out_of_stock') {
                    $query->where('quantity_in_inventory', 0);
                }

                if ($availability === 'low_stock') {
                    $query->whereBetween('quantity_in_inventory', [1, 10]);
                }
            });

        match ($request->string('sort')->toString()) {
            'name' => $products->orderBy('name'),
            'stock_asc' => $products->orderBy('quantity_in_inventory'),
            'stock_desc' => $products->orderByDesc('quantity_in_inventory'),
            'updated_asc' => $products->orderBy('inventory_updated_at'),
            default => $products->orderByDesc('inventory_updated_at'),
        };

        $products = $products
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