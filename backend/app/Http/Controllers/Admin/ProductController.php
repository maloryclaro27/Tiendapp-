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
                    $query->where('quantity_in_inventory', '>', Product::LOW_STOCK_MAX);
                }

                if ($availability === 'out_of_stock') {
                    $query->where('quantity_in_inventory', '<=', 0);
                }

                if ($availability === 'low_stock') {
                    $query->whereBetween('quantity_in_inventory', [1, Product::LOW_STOCK_MAX]);
                }
            });

        match ($request->string('sort')->toString()) {
            'name' => $products->orderBy('name'),
            'stock_asc' => $products->orderBy('quantity_in_inventory'),
            'stock_desc' => $products->orderByDesc('quantity_in_inventory'),
            'updated_asc' => $products->orderBy('inventory_updated_at'),
            default => $products->orderByDesc('inventory_updated_at'),
        };

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
