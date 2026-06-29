@extends('admin.layouts.app')

@section('title', 'Editar marca')
@section('page_title', 'Editar marca')
@section('page_description', 'Actualiza la informacion principal de la marca seleccionada.')

@section('page_actions')
    <a href="{{ route('admin.brands.index') }}" class="button button-secondary">
        Volver a marcas
    </a>
@endsection

@section('content')
    <section class="card">
        <div class="card-header">
            <div>
                <strong>{{ $brand->name }}</strong>
                <div class="help">
                    Referencia actual: {{ $brand->reference }} · Productos asociados: {{ number_format($brand->products_count) }}
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
@endsectionInvoke-WebRequest -UseBasicParsing http://localhost:8080/admin/brands/1/edit