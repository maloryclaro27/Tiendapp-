@extends('admin.layouts.app')

@section('title', 'Nuevo producto')
@section('page_title', 'Crear producto')
@section('page_description', 'Registra un nuevo producto, su marca, unidad de medida e inventario inicial.')

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
                <strong style="color: #ffffff;">Informacion del producto</strong>
                <div class="help" style="color: #d8dee9;">
                    Todos los campos son obligatorios.
                </div>
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
