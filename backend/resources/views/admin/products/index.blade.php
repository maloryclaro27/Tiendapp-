@extends('admin.layouts.app')

@section('title', 'Productos')
@section('page_title', 'Gestion de productos')
@section('page_description', 'Administra el catalogo, inventario, unidades de medida y marcas asociadas.')

@section('page_actions')
    <a href="{{ route('admin.products.create') }}" class="button-brand-outline">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        </svg>
        Nuevo producto
    </a>
@endsection

@section('content')
    <section class="card-dark overflow-hidden" style="border: 1px solid #538bfb;">
        <div class="card-header" style="background: #313335; border-bottom: 1px solid rgba(255, 255, 255, 0.16);">
            <div>
                <strong style="color: #ffffff;">Catalogo de productos</strong>
                <div class="help" style="color: #d8dee9;">
                    Filtra por marca, unidad de medida, estado de inventario o texto libre.
                </div>
            </div>

            <div class="hidden rounded-2xl border border-white/15 px-4 py-2 text-sm font-bold text-white md:block"
                style="background: #252729;">
                {{ number_format($products->total()) }} productos
            </div>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}"
                class="mb-6 rounded-3xl border border-white/20 p-4"
                style="background: #313335;">
                <div class="grid grid-cols-1 gap-3 lg:grid-cols-2 xl:grid-cols-6">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        class="input w-full xl:col-span-2"
                        placeholder="Buscar producto u observacion..."
                        style="background: #252729; color: #ffffff; border-color: rgba(255, 255, 255, 0.18);"
                    >

                    <select name="brand_id" class="select w-full"
                        style="background: #252729; color: #ffffff; border-color: rgba(255, 255, 255, 0.18);">
                        <option value="">Todas las marcas</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" @selected((int) $selectedBrandId === $brand->id)>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="unit_of_measure" class="select w-full"
                        style="background: #252729; color: #ffffff; border-color: rgba(255, 255, 255, 0.18);">
                        <option value="">Todas las unidades</option>
                        <option value="Unidad" @selected($selectedUnit === 'Unidad')>Unidad</option>
                        <option value="Display" @selected($selectedUnit === 'Display')>Display</option>
                        <option value="Caja" @selected($selectedUnit === 'Caja')>Caja</option>
                    </select>

                    <select name="availability" class="select w-full"
                        style="background: #252729; color: #ffffff; border-color: rgba(255, 255, 255, 0.18);">
                        <option value="">Todos los estados</option>
                        <option value="in_stock" @selected(in_array($selectedAvailability, ['available', 'in_stock'], true))>Con inventario</option>
                        <option value="healthy_stock" @selected($selectedAvailability === 'healthy_stock')>Stock saludable</option>
                        <option value="low_stock" @selected($selectedAvailability === 'low_stock')>Bajo stock</option>
                        <option value="out_of_stock" @selected($selectedAvailability === 'out_of_stock')>Sin stock</option>
                    </select>

                    <select name="sort" class="select w-full"
                        style="background: #252729; color: #ffffff; border-color: rgba(255, 255, 255, 0.18);">
                        <option value="">Recientes primero</option>
                        <option value="name" @selected($selectedSort === 'name')>Nombre A-Z</option>
                        <option value="stock_desc" @selected($selectedSort === 'stock_desc')>Mayor inventario</option>
                        <option value="stock_asc" @selected($selectedSort === 'stock_asc')>Menor inventario</option>
                        <option value="updated_asc" @selected($selectedSort === 'updated_asc')>Actualizacion antigua</option>
                    </select>
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <button type="submit" class="button-brand-outline">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 018 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                        </svg>
                        Filtrar
                    </button>

                    @if ($search || $selectedBrandId || $selectedUnit || $selectedAvailability || $selectedSort)
                        <a href="{{ route('admin.products.index') }}" class="button-brand-outline">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>

            @if ($products->isEmpty())
                <div class="empty-state" style="background: #313335; color: #d8dee9; border: 1px solid rgba(255, 255, 255, 0.18);">
                    No hay productos registrados con los criterios actuales.
                </div>
            @else
                <div class="overflow-hidden rounded-3xl border" style="background: #313335; border-color: #538bfb;">
                    <div class="overflow-x-auto">
                        <table class="w-full" style="background: #313335; border-collapse: separate; border-spacing: 0;">
                            <thead style="background: #313335;">
                                <tr>
                                    <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-widest" style="background: #313335; color: #ffffff; border-bottom: 1px solid rgba(83, 139, 251, 0.45);">Producto</th>
                                    <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-widest" style="background: #313335; color: #ffffff; border-bottom: 1px solid rgba(83, 139, 251, 0.45);">Marca</th>
                                    <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-widest" style="background: #313335; color: #ffffff; border-bottom: 1px solid rgba(83, 139, 251, 0.45);">Unidad</th>
                                    <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-widest" style="background: #313335; color: #ffffff; border-bottom: 1px solid rgba(83, 139, 251, 0.45);">Inventario</th>
                                    <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-widest" style="background: #313335; color: #ffffff; border-bottom: 1px solid rgba(83, 139, 251, 0.45);">Estado</th>
                                    <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-widest" style="background: #313335; color: #ffffff; border-bottom: 1px solid rgba(83, 139, 251, 0.45);">Actualizacion</th>
                                    <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-widest" style="background: #313335; color: #ffffff; border-bottom: 1px solid rgba(83, 139, 251, 0.45);">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($products as $product)
                                    @php
                                        $stockText = 'Stock saludable';
                                        $stockStyle = 'background: rgba(26, 85, 201, 0.16); color: #6ec1e5; border: 1px solid rgba(110, 193, 229, 0.45);';

                                        if ($product->quantity_in_inventory <= 0) {
                                            $stockText = 'Sin stock';
                                            $stockStyle = 'background: rgba(220, 38, 38, 0.10); color: #dc2626; border: 1px solid rgba(220, 38, 38, 0.35);';
                                        } elseif ($product->quantity_in_inventory <= 10) {
                                            $stockText = 'Bajo stock';
                                            $stockStyle = 'background: rgba(255, 163, 34, 0.14); color: #ffa322; border: 1px solid rgba(255, 163, 34, 0.35);';
                                        }
                                    @endphp

                                    <tr class="transition hover:bg-white/5" style="background: #313335; border-top: 1px solid rgba(255, 255, 255, 0.10);">
                                        <td class="px-5 py-5 align-top" style="background: #313335; border-top: 1px solid rgba(255, 255, 255, 0.10);">
                                            <div class="font-bold text-white">{{ $product->name }}</div>
                                            <div class="mt-1 text-sm leading-relaxed" style="color: #9ca8bd;">
                                                {{ Str::limit($product->observations, 80) }}
                                            </div>
                                        </td>

                                        <td class="px-5 py-5 align-top text-sm font-semibold" style="background: #313335; color: #d8dee9; border-top: 1px solid rgba(255, 255, 255, 0.10);">
                                            {{ $product->brand?->name }}
                                        </td>

                                        <td class="px-5 py-5 align-top" style="background: #313335; border-top: 1px solid rgba(255, 255, 255, 0.10);">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-black uppercase tracking-wide"
                                                style="background: rgba(26, 85, 201, 0.16); color: #6ec1e5; border: 1px solid rgba(110, 193, 229, 0.45);">
                                                {{ $product->unit_of_measure }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-5 align-top" style="background: #313335; border-top: 1px solid rgba(255, 255, 255, 0.10);">
                                            <span class="inline-flex min-w-10 justify-center rounded-2xl px-3 py-2 text-sm font-black text-white"
                                                style="background: #252729; border: 1px solid rgba(255, 255, 255, 0.15);">
                                                {{ number_format($product->quantity_in_inventory) }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-5 align-top" style="background: #313335; border-top: 1px solid rgba(255, 255, 255, 0.10);">
                                            <span class="inline-flex min-w-[120px] items-center justify-center rounded-full px-3 py-1 text-center text-xs font-black uppercase tracking-wide"
                                                style="{{ $stockStyle }}">
                                                {{ $stockText }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-5 align-top text-sm font-semibold" style="background: #313335; color: #d8dee9; border-top: 1px solid rgba(255, 255, 255, 0.10);">
                                            {{ $product->inventory_updated_at?->format('d/m/Y') }}
                                        </td>

                                        <td class="px-5 py-5 align-top" style="background: #313335; border-top: 1px solid rgba(255, 255, 255, 0.10);">
                                            <div class="flex flex-col gap-2">
                                                <a
                                                    href="{{ route('admin.products.edit', $product) }}"
                                                    class="button-brand-outline inline-flex min-w-[130px] justify-center"
                                                    title="Editar producto"
                                                >
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                    Editar
                                                </a>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.products.destroy', $product) }}"
                                                    onsubmit="return confirm('Seguro que deseas eliminar el producto {{ $product->name }}? Esta accion no se puede deshacer.');"
                                                    class="inline"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="button-brand-outline inline-flex min-w-[130px] justify-center"
                                                        title="Eliminar producto"
                                                        style="border-color: rgba(220, 38, 38, 0.55); color: #dc2626; background: rgba(220, 38, 38, 0.08);"
                                                    >
                                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pagination mt-6">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
