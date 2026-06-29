@extends('admin.layouts.app')

@section('title', 'Productos')
@section('page_title', 'Gestion de productos')
@section('page_description', 'Administra el catalogo, inventario, unidades de medida y marcas asociadas.')

@section('page_actions')
    <a href="{{ route('admin.products.create') }}" class="button button-primary">
        Nuevo producto
    </a>
@endsection

@section('content')
    <section class="card">
        <div class="card-header">
            <div>
                <strong>Catalogo de productos</strong>
                <div class="help">Filtra por marca, unidad de medida, disponibilidad o texto libre.</div>
            </div>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}" class="toolbar">
                <div style="flex: 1; min-width: 240px;">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        class="input"
                        placeholder="Buscar producto u observacion..."
                    >
                </div>

                <div style="min-width: 190px;">
                    <select name="brand_id" class="select">
                        <option value="">Todas las marcas</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" @selected($selectedBrandId === $brand->id)>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="min-width: 170px;">
                    <select name="unit_of_measure" class="select">
                        <option value="">Todas las unidades</option>
                        <option value="Unidad" @selected($selectedUnit === 'Unidad')>Unidad</option>
                        <option value="Display" @selected($selectedUnit === 'Display')>Display</option>
                        <option value="Caja" @selected($selectedUnit === 'Caja')>Caja</option>
                    </select>
                </div>

                <div style="min-width: 180px;">
                    <select name="availability" class="select">
                        <option value="">Todos los estados</option>
                        <option value="available" @selected($selectedAvailability === 'available')>Disponible</option>
                        <option value="low_stock" @selected($selectedAvailability === 'low_stock')>Bajo stock</option>
                        <option value="out_of_stock" @selected($selectedAvailability === 'out_of_stock')>Sin stock</option>
                    </select>
                </div>

                <div style="min-width: 180px;">
                    <select name="sort" class="select">
                        <option value="">Recientes primero</option>
                        <option value="name" @selected($selectedSort === 'name')>Nombre A-Z</option>
                        <option value="stock_desc" @selected($selectedSort === 'stock_desc')>Mayor inventario</option>
                        <option value="stock_asc" @selected($selectedSort === 'stock_asc')>Menor inventario</option>
                        <option value="updated_asc" @selected($selectedSort === 'updated_asc')>Actualizacion antigua</option>
                    </select>
                </div>

                <button type="submit" class="button button-primary">
                    Filtrar
                </button>

                @if ($search || $selectedBrandId || $selectedUnit || $selectedAvailability || $selectedSort)
                    <a href="{{ route('admin.products.index') }}" class="button button-secondary">
                        Limpiar
                    </a>
                @endif
            </form>

            @if ($products->isEmpty())
                <div class="empty-state">
                    No hay productos registrados con los criterios actuales.
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Marca</th>
                                <th>Unidad</th>
                                <th>Inventario</th>
                                <th>Estado</th>
                                <th>Actualizacion</th>
                                <th style="width: 220px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                @php
                                    $stockClass = 'badge-success';
                                    $stockText = 'Disponible';

                                    if ($product->quantity_in_inventory === 0) {
                                        $stockClass = 'badge-danger';
                                        $stockText = 'Sin stock';
                                    } elseif ($product->quantity_in_inventory <= 10) {
                                        $stockClass = 'badge-warning';
                                        $stockText = 'Bajo stock';
                                    }
                                @endphp

                                <tr>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        <div class="help">{{ Str::limit($product->observations, 80) }}</div>
                                    </td>
                                    <td>{{ $product->brand?->name }}</td>
                                    <td>
                                        <span class="badge">{{ $product->unit_of_measure }}</span>
                                    </td>
                                    <td>{{ number_format($product->quantity_in_inventory) }}</td>
                                    <td>
                                        <span class="badge {{ $stockClass }}">{{ $stockText }}</span>
                                    </td>
                                    <td>{{ $product->inventory_updated_at?->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            {{-- Botón Editar --}}
                                            <a
                                                href="{{ route('admin.products.edit', $product) }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 hover:text-blue-800 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                                title="Editar producto"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                                Editar
                                            </a>

                                            {{-- Botón Eliminar --}}
                                            <form
                                                method="POST"
                                                action="{{ route('admin.products.destroy', $product) }}"
                                                onsubmit="return confirm('⚠️ ¿Seguro que deseas eliminar el producto \"{{ $product->name }}\"? Esta acción no se puede deshacer.');"
                                                class="inline"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 hover:text-red-800 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                                    title="Eliminar producto"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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

                <div class="pagination">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection