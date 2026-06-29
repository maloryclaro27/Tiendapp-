@extends('admin.layouts.app')

@section('title', 'Editar producto')
@section('page_title', 'Editar producto')
@section('page_description', 'Actualiza la informacion comercial, marca, unidad de medida e inventario del producto.')

@section('page_actions')
    <a href="{{ route('admin.products.index') }}" class="button-brand-outline">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Volver a productos
    </a>
@endsection

@section('content')
    <section class="card-dark overflow-hidden" style="border: 1px solid #538bfb;">
        <div class="card-header" style="background: #313335; border-bottom: 1px solid rgba(255, 255, 255, 0.16);">
            <div>
                <strong style="color: #ffffff;">{{ $product->name }}</strong>
                <div class="help" style="color: #d8dee9;">
                    Marca actual: {{ $product->brand?->name }} - Inventario actual: {{ number_format($product->quantity_in_inventory) }}
                </div>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.update', $product) }}">
                @csrf
                @method('PUT')

                @include('admin.products._form', [
                    'product' => $product,
                    'brands' => $brands,
                    'units' => $units,
                    'submitText' => 'Guardar cambios',
                ])
            </form>
        </div>
    </section>
@endsection
