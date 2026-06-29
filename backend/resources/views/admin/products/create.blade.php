@extends('admin.layouts.app')

@section('title', 'Nuevo producto')
@section('page_title', 'Crear producto')
@section('page_description', 'Registra un nuevo producto, su marca, unidad de medida e inventario inicial.')

@section('content')
    <section class="card">
        <div class="card-header">
            <div>
                <strong>Informacion del producto</strong>
                <div class="help">Todos los campos son obligatorios.</div>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.store') }}">
                @csrf

                @include('admin.products._form', [
                    'product' => null,
                    'brands' => $brands,
                    'units' => $units,
                    'submitText' => 'Crear producto',
                ])
            </form>
        </div>
    </section>
@endsection