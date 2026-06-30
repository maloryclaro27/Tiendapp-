<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('brand')
            ->search($request->string('search')->toString())
            ->byBrand($request->filled('brand_id') ? $request->integer('brand_id') : null)
            ->byUnit($request->string('unit_of_measure')->toString())
            ->withAvailability($request->string('availability')->toString())
            ->sorted($request->string('sort')->toString());

        return view('admin.products.index', [
            'products' => $products->paginate(10)->withQueryString(),
            'brands' => Brand::query()->orderBy('name')->get(),
            'search' => $request->string('search')->toString(),
            'selectedBrandId' => $request->integer('brand_id') ?: null,
            'selectedUnit' => $request->string('unit_of_measure')->toString(),
            'selectedAvailability' => $request->string('availability')->toString(),
            'selectedSort' => $request->string('sort')->toString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'brands' => Brand::query()->orderBy('name')->get(),
            'units' => ['Unidad', 'Display', 'Caja'],
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function show(Product $product): RedirectResponse
    {
        return redirect()->route('admin.products.edit', $product);
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'brands' => Brand::query()->orderBy('name')->get(),
            'units' => ['Unidad', 'Display', 'Caja'],
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
