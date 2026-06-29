@extends('admin.layouts.app')

@section('title', 'Marcas')
@section('page_title', 'Gestion de marcas')
@section('page_description', 'Administra las marcas del catalogo y sus referencias unicas.')

@section('page_actions')
    <a href="{{ route('admin.brands.create') }}" class="button button-primary">
        Nueva marca
    </a>
@endsection

@section('content')
    <section class="card">
        <div class="card-header">
            <div>
                <strong>Marcas registradas</strong>
                <div class="help">Listado con conteo de productos asociados.</div>
            </div>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.brands.index') }}" class="toolbar">
                <div style="flex: 1; min-width: 260px;">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        class="input"
                        placeholder="Buscar por nombre o referencia..."
                    >
                </div>

                <button type="submit" class="button button-primary">
                    Buscar
                </button>

                @if ($search)
                    <a href="{{ route('admin.brands.index') }}" class="button button-secondary">
                        Limpiar
                    </a>
                @endif
            </form>

            @if ($brands->isEmpty())
                <div class="empty-state">
                    No hay marcas registradas con los criterios actuales.
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Marca</th>
                                <th>Referencia</th>
                                <th>Productos asociados</th>
                                <th>Creacion</th>
                                <th style="width: 220px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($brands as $brand)
                                <tr>
                                    <td>
                                        <strong>{{ $brand->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge">{{ $brand->reference }}</span>
                                    </td>
                                    <td>
                                        {{ number_format($brand->products_count) }}
                                    </td>
                                    <td>
                                        {{ $brand->created_at?->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            {{-- Botón Editar --}}
                                            <a
                                                href="{{ route('admin.brands.edit', $brand) }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 hover:text-blue-800 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                                title="Editar marca"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                                Editar
                                            </a>

                                            {{-- Botón Eliminar --}}
                                            <form
                                                method="POST"
                                                action="{{ route('admin.brands.destroy', $brand) }}"
                                                onsubmit="return confirm('⚠️ ¿Seguro que deseas eliminar la marca \"{{ $brand->name }}\"? Esta acción no se puede deshacer.');"
                                                class="inline"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 hover:text-red-800 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                                    title="Eliminar marca"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    {{ $brands->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection