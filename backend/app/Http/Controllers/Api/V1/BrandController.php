<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BrandController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min((int) $request->integer('per_page', 15), 50);

        $brands = Brand::query()
            ->withCount('products')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('reference', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return BrandResource::collection($brands);
    }

    public function select(): AnonymousResourceCollection
    {
        $brands = Brand::query()
            ->select(['id', 'name', 'reference', 'created_at', 'updated_at'])
            ->orderBy('name')
            ->get();

        return BrandResource::collection($brands);
    }

    public function show(Brand $brand): BrandResource
    {
        $brand->loadCount('products');

        return new BrandResource($brand);
    }
}