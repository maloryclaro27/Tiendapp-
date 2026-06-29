@extends('admin.layouts.app')

@section('title', 'Editar marca')
@section('page_title', 'Editar marca')
@section('page_description', 'Actualiza la informacion principal de la marca seleccionada.')

@section('page_actions')
    <a href="{{ route('admin.brands.index') }}" class="button-brand-outline">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Volver a marcas
    </a>
@endsection

@section('content')
    <section class="card-dark overflow-hidden" style="border: 1px solid #538bfb;">
        <div class="card-header" style="background: #313335; border-bottom: 1px solid rgba(255, 255, 255, 0.16);">
            <div>
                <strong style="color: #ffffff;">{{ $brand->name }}</strong>
                <div class="help" style="color: #d8dee9;">
                    Referencia actual: {{ $brand->reference }} - Productos asociados: {{ number_format($brand->products_count) }}
                </div>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.brands.update', $brand) }}">
                @csrf
                @method('PUT')

                @include('admin.brands._form', [
                    'brand' => $brand,
                    'submitText' => 'Guardar cambios',
                ])
            </form>
        </div>
    </section>
@endsection
