@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Centro de control del catalogo')
@section('page_description', 'Resumen operativo de marcas, productos, inventario y alertas de stock.')

@section('page_actions')
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('admin.brands.create') }}" class="button-brand-outline">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
            Nueva marca
        </a>

        <a href="{{ route('admin.products.create') }}" class="button-brand-outline">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            Nuevo producto
        </a>
    </div>
@endsection

@section('content')
    @php
        $totalBrands = (int) $metrics['total_brands'];
        $totalProducts = (int) $metrics['total_products'];
        $totalInventoryUnits = (int) $metrics['total_inventory_units'];

        $availableProducts = (int) $metrics['available_products'];
        $healthyStockProducts = (int) $metrics['healthy_stock_products'];
        $lowStockProducts = (int) $metrics['low_stock_products'];
        $outOfStockProducts = (int) $metrics['out_of_stock_products'];
        $stockAlerts = (int) $metrics['stock_alerts'];

        $stockHealth = (int) $metrics['stock_health_percent'];
        $availablePercent = (float) $metrics['available_percent'];
        $healthyStockPercent = (float) $metrics['healthy_stock_percent'];
        $lowStockPercent = (float) $metrics['low_stock_percent'];
        $outOfStockPercent = (float) $metrics['out_of_stock_percent'];
    @endphp

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
        {{-- Hero principal --}}
        <section class="relative overflow-hidden rounded-3xl p-7 text-white shadow-xl xl:col-span-5"
            style="background: #1a1c1e; border: 1px solid #538bfb; box-shadow: 0 18px 40px rgba(0, 0, 0, 0.34);">


            <div class="relative">
                <div
                    class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-widest text-blue-50">
                    Catalog intelligence
                </div>

                <h3 class="max-w-md text-3xl font-black leading-tight tracking-tight">
                    Estado general del catalogo
                </h3>

                <p class="mt-3 max-w-md text-sm leading-relaxed text-blue-50/85">
                    Gestiona marcas, productos, unidades de inventario y alertas de stock desde un panel administrativo
                    centralizado.
                </p>

                <div class="mt-8 grid grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-white/40 p-4" style="background: #252729;">
                        <div class="text-3xl font-black">{{ number_format($totalProducts) }}</div>
                        <div class="mt-1 text-xs font-bold uppercase tracking-wider text-blue-100">Productos</div>
                    </div>

                    <div class="rounded-2xl border border-white/40 p-4" style="background: #252729;">
                        <div class="text-3xl font-black">{{ number_format($stockHealth) }}%</div>
                        <div class="mt-1 text-xs font-bold uppercase tracking-wider text-blue-100">Salud stock</div>
                    </div>
                </div>

                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ route('admin.products.index') }}" class="button-brand-outline">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        Ver catalogo
                    </a>

                    <a href="{{ route('admin.products.create') }}" class="button-brand-outline">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Agregar producto
                    </a>
                </div>
            </div>
        </section>

        {{-- Metricas compactas --}}
        <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:col-span-7">
            <article class="card-dark metric">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="metric-label">Marcas registradas</div>
                        <div class="metric-value">{{ number_format($totalBrands) }}</div>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl"
                        style="background: rgba(26, 85, 201, 0.14); color: #1a55c9;">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                </div>
            </article>

            <article class="card-dark metric">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="metric-label">Productos activos</div>
                        <div class="metric-value">{{ number_format($totalProducts) }}</div>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl"
                        style="background: rgba(110, 193, 229, 0.16); color: #6ec1e5;">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
            </article>

            <article class="card-dark metric">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="metric-label">Unidades inventario</div>
                        <div class="metric-value">{{ number_format($totalInventoryUnits) }}</div>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl"
                        style="background: rgba(110, 193, 229, 0.16); color: #6ec1e5;">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                </div>
            </article>

            <article class="card-dark metric">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="metric-label">Alertas de stock</div>
                        <div class="metric-value">{{ number_format($stockAlerts) }}</div>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl"
                        style="background: rgba(17, 63, 154, 0.16); color: #113f9a;">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>
                </div>
            </article>
        </section>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-12 xl:items-start">
        {{-- Salud del inventario --}}
        <section class="card-dark self-start xl:col-span-4" style="border: 1px solid #538bfb;">
            <div class="card-header" style="background: #313335; border-bottom: 1px solid rgba(255, 255, 255, 0.16);">
                <div>
                    <strong style="color: #ffffff;">Salud del inventario</strong>
                    <div class="help" style="color: #ffffff;">
                        Lectura ejecutiva del estado actual del catalogo.
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="rounded-3xl border border-white/40 p-5" style="background: #313335;">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-xl font-bold text-white">Stock saludable</div>
                            <div class="mt-2 text-xl font-bold text-white">
                                {{ number_format($stockHealth) }}%
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-xl font-bold text-white">Productos evaluados</div>
                            <div class="mt-2 text-xl font-bold text-white">
                                {{ number_format($totalProducts) }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 h-2.5 overflow-hidden rounded-full bg-white/15">
                        <div class="h-full rounded-full transition-all duration-700"
                            style="width: {{ $healthyStockPercent }}%; background: linear-gradient(90deg, #1a55c9, #4554eb);">
                        </div>
                    </div>

                    <p class="mt-5 text-sm leading-relaxed" style="color: #d8dee9;">
                        El sistema identifica disponibilidad, bajo stock y productos sin inventario para priorizar acciones
                        administrativas antes de publicar el catalogo en el ecommerce.
                    </p>
                </div>

                <div class="mt-5 space-y-4">
                    <div class="group rounded-2xl border border-white/40 p-4 transition hover:-translate-y-0.5 hover:shadow-lg"
                        style="background: #313335;">
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="h-3 w-3 rounded-full" style="background: #1a55c9;"></span>
                                <span class="text-sm font-bold text-white">
                                    Stock saludable: {{ number_format($healthyStockProducts) }}
                                </span>
                            </div>
                            <div class="text-sm font-bold text-white">
                                {{ number_format($healthyStockPercent, 0) }}%
                            </div>
                        </div>

                        <div class="h-2 overflow-hidden rounded-full bg-white/15">
                            <div class="h-full rounded-full transition-all duration-700 group-hover:opacity-90"
                                style="width: {{ $healthyStockPercent }}%; background: #1a55c9;"></div>
                        </div>
                    </div>

                    <div class="group rounded-2xl border border-white/40 p-4 transition hover:-translate-y-0.5 hover:shadow-lg"
                        style="background: #313335;">
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="h-3 w-3 rounded-full" style="background: #66b1d2;"></span>
                                <span class="text-sm font-bold text-white">
                                    Bajo stock: {{ number_format($lowStockProducts) }}
                                </span>
                            </div>
                            <div class="text-sm font-bold text-white">
                                {{ number_format($lowStockPercent, 0) }}%
                            </div>
                        </div>

                        <div class="h-2 overflow-hidden rounded-full bg-white/15">
                            <div class="h-full rounded-full transition-all duration-700 group-hover:opacity-90"
                                style="width: {{ $lowStockPercent }}%; background: #66b1d2;"></div>
                        </div>
                    </div>

                    <div class="group rounded-2xl border border-white/40 p-4 transition hover:-translate-y-0.5 hover:shadow-lg"
                        style="background: #313335;">
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="h-3 w-3 rounded-full" style="background: #ffa322;"></span>
                                <span class="text-sm font-bold text-white">
                                    Sin stock: {{ number_format($outOfStockProducts) }}
                                </span>
                            </div>
                            <div class="text-sm font-bold text-white">
                                {{ number_format($outOfStockPercent, 0) }}%
                            </div>
                        </div>

                        <div class="h-2 overflow-hidden rounded-full bg-white/15">
                            <div class="h-full rounded-full transition-all duration-700 group-hover:opacity-90"
                                style="width: {{ $outOfStockPercent }}%; background: #ffa322;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Alertas y acciones --}}
        <section class="card-dark self-start xl:col-span-3" style="border: 1px solid #538bfb;">
            <div class="card-header" style="background: #313335; border-bottom: 1px solid rgba(255, 255, 255, 0.16);">
                <div>
                    <strong style="color: #ffffff;">Alertas operativas</strong>
                    <div class="help" style="color: #ffffff;">
                        Acciones sugeridas sobre inventario.
                    </div>
                </div>
            </div>

            <div class="card-body space-y-5">
                <div class="rounded-2xl border p-4 transition hover:-translate-y-0.5 hover:shadow-lg"
                    style="background: #313335; border-color: #66b1d2;">
                    <div class="flex items-start gap-3">
                        <span class="mt-1 h-3 w-3 flex-shrink-0 rounded-full" style="background: #66b1d2;"></span>

                        <div>
                            <div class="text-sm font-bold" style="color: #66b1d2;">
                                {{ number_format($lowStockProducts) }} productos con bajo stock
                            </div>

                            <p class="mt-2 text-sm leading-relaxed" style="color: #d8dee9;">
                                Prioriza reposicion para referencias con inventario menor o igual a 10 unidades.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border p-4 transition hover:-translate-y-0.5 hover:shadow-lg"
                    style="background: #313335; border-color: #ffa322;">
                    <div class="flex items-start gap-3">
                        <span class="mt-1 h-3 w-3 flex-shrink-0 rounded-full" style="background: #ffa322;"></span>

                        <div>
                            <div class="text-sm font-bold" style="color: #ffa322;">
                                {{ number_format($outOfStockProducts) }} productos sin stock
                            </div>

                            <p class="mt-2 text-sm leading-relaxed" style="color: #d8dee9;">
                                Revisa disponibilidad antes de publicar productos en el ecommerce.
                            </p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.products.index', ['availability' => 'low_stock']) }}"
                    class="button-brand-outline w-full">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 018 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Revisar alertas
                </a>
            </div>
        </section>

        {{-- Productos recientes --}}
        <section class="card-dark xl:col-span-5" style="border: 1px solid #538bfb;">
            <div class="card-header" style="background: #313335; border-bottom: 1px solid rgba(255, 255, 255, 0.16);">
                <div>
                    <strong style="color: #ffffff;">Productos actualizados recientemente</strong>
                    <div class="help" style="color: #ffffff;">
                        Ultimos movimientos relevantes del inventario.
                    </div>
                </div>

                <a href="{{ route('admin.products.index') }}" class="button-brand-outline">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    Ver catalogo
                </a>
            </div>

            <div class="card-body">
                @if ($recentProducts->isEmpty())
                    <div class="empty-state">
                        Aun no hay productos registrados.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($recentProducts as $product)
                            @php
                                $stockText = 'Stock saludable';
                                $stockStyle =
                                    'background: rgba(26, 85, 201, 0.16); color: #1a55c9; border: 1px solid rgba(26, 85, 201, 0.45);';

                                if ($product->quantity_in_inventory === 0) {
                                    $stockText = 'Sin stock';
                                    $stockStyle =
                                        'background: rgba(255, 163, 34, 0.16); color: #ffa322; border: 1px solid rgba(255, 163, 34, 0.45);';
                                } elseif ($product->quantity_in_inventory <= 10) {
                                    $stockText = 'Bajo stock';
                                    $stockStyle =
                                        'background: rgba(102, 177, 210, 0.16); color: #66b1d2; border: 1px solid rgba(102, 177, 210, 0.45);';
                                }
                            @endphp

                            <div class="rounded-2xl border border-white/40 p-4 transition hover:-translate-y-0.5 hover:shadow-lg"
                                style="background: #313335;">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="font-bold text-white">
                                            {{ $product->name }}
                                        </div>

                                        <div class="mt-1 text-sm" style="color: #d8dee9;">
                                            {{ $product->brand?->name }} · {{ $product->unit_of_measure }}
                                        </div>
                                    </div>

                                    <span
                                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide"
                                        style="{{ $stockStyle }}">
                                        {{ $stockText }}
                                    </span>
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-3">
                                    <div class="rounded-xl border border-white/15 px-3 py-2" style="background: #252729;">
                                        <div class="text-xs font-bold uppercase tracking-wide" style="color: #9ca8bd;">
                                            Inventario
                                        </div>
                                        <div class="mt-1 text-lg font-bold text-white">
                                            {{ number_format($product->quantity_in_inventory) }}
                                        </div>
                                    </div>

                                    <div class="rounded-xl border border-white/15 px-3 py-2" style="background: #252729;">
                                        <div class="text-xs font-bold uppercase tracking-wide" style="color: #9ca8bd;">
                                            Actualizado
                                        </div>
                                        <div class="mt-1 text-lg font-bold text-white">
                                            {{ $product->inventory_updated_at?->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection
