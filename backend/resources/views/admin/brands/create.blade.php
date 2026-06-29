@extends('admin.layouts.app')

@section('title', 'Nueva marca')
@section('page_title', 'Crear marca')
@section('page_description', 'Registra una nueva marca para asociarla posteriormente a productos del catalogo.')

@section('content')
    <section class="card">
        <div class="card-header">
            <div>
                <strong>Informacion de la marca</strong>
                <div class="help">Todos los campos son obligatorios.</div>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.brands.store') }}">
                @csrf

                @include('admin.brands._form', [
                    'brand' => null,
                    'submitText' => 'Crear marca',
                ])
            </form>
        </div>
    </section>
@endsection