@extends('admin.layouts.app')

@section('title', 'Editar producto')
@section('page_title', 'Editar producto')
@section('page_description', 'Actualiza la informacion comercial, marca, unidad de medida e inventario del producto.')

@section('page_actions')
    <a href="{{ route('admin.products.index') }}" class="button button-secondary">
        Volver a productos
    </a>
@endsection

@section('content')
    <section class="card">
        <div class="card-header">
            <div>
                <strong>{{ $product->name }}</strong>
                <div class="help">
                    Marca actual: {{ $product->brand?->name }} · Inventario actual: {{ number_format($product->quantity_in_inventory) }}
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