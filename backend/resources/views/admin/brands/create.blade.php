@extends('admin.layouts.app')

@section('title', 'Nueva marca')
@section('page_title', 'Crear marca')
@section('page_description', 'Registra una nueva marca para asociarla posteriormente a productos del catalogo.')

@section('content')
    <section class="card-dark overflow-hidden" style="border: 1px solid #538bfb;">
        <div class="card-header" style="background: #313335; border-bottom: 1px solid rgba(255, 255, 255, 0.16);">
            <div>
                <strong style="color: #ffffff;">Informacion de la marca</strong>
                <div class="help" style="color: #d8dee9;">
                    Todos los campos son obligatorios.
                </div>
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
