@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard administrativo')
@section('page_description', 'Resumen ejecutivo del catalogo, inventario y productos recientes.')

@section('page_actions')
    <div class="actions">
        <a href="{{ route('admin.brands.create') }}" class="button button-secondary">
            Nueva marca
        </a>
        <a href="{{ route('admin.products.create') }}" class="button button-primary">
            Nuevo producto
        </a>
    </div>
@endsection

@section('content')
    <div class="grid grid-4" style="margin-bottom: 22px;">
        <div class="card metric">
            <div class="metric-label">Marcas registradas</div>
            <div class="metric-value">{{ number_format($metrics['total_brands']) }}</div>
        </div>

        <div class="card metric">
            <div class="metric-label">Productos activos</div>
            <div class="metric-value">{{ number_format($metrics['total_products']) }}</div>
        </div>

        <div class="card metric">
            <div class="metric-label">Unidades en inventario</div>
            <div class="metric-value">{{ number_format($metrics['total_inventory_units']) }}</div>
        </div>

        <div class="card metric">
            <div class="metric-label">Alertas de stock</div>
            <div class="metric-value">
                {{ number_format($metrics['out_of_stock_products'] + $metrics['low_stock_products']) }}
            </div>
        </div>
    </div>

    <div class="grid grid-4" style="margin-bottom: 22px;">
        <div class="card metric" style="grid-column: span 2;">
            <div class="metric-label">Productos sin stock</div>
            <div class="metric-value">{{ number_format($metrics['out_of_stock_products']) }}</div>
        </div>

        <div class="card metric" style="grid-column: span 2;">
            <div class="metric-label">Productos con bajo stock</div>
            <div class="metric-value">{{ number_format($metrics['low_stock_products']) }}</div>
        </div>
    </div>

    <section class="card">
        <div class="card-header">
            <div>
                <strong>Productos actualizados recientemente</strong>
                <div class="help">Ultimos movimientos relevantes del inventario.</div>
            </div>

            <a href="{{ route('admin.products.index') }}" class="button button-secondary">
                Ver catalogo
            </a>
        </div>

        <div class="card-body">
            @if ($recentProducts->isEmpty())
                <div class="empty-state">
                    Aun no hay productos registrados.
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
                                <th>Actualizado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentProducts as $product)
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
                                        <div class="help">{{ Str::limit($product->observations, 70) }}</div>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>
@endsection