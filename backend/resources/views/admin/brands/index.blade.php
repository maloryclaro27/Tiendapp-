@extends('admin.layouts.app')

@section('title', 'Marcas')
@section('page_title', 'Gestion de marcas')
@section('page_description', 'Administra las marcas del catalogo y sus referencias unicas.')

@section('page_actions')
    <a href="{{ route('admin.brands.create') }}" class="button-brand-outline">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
        </svg>
        Nueva marca
    </a>
@endsection

@section('content')
    <section class="card-dark overflow-hidden" style="border: 1px solid #538bfb;">
        <div class="card-header" style="background: #313335; border-bottom: 1px solid rgba(255, 255, 255, 0.16);">
            <div>
                <strong style="color: #ffffff;">Marcas registradas</strong>
                <div class="help" style="color: #d8dee9;">
                    Listado con conteo de productos asociados y referencias unicas.
                </div>
            </div>

            <div class="hidden rounded-2xl border border-white/15 px-4 py-2 text-sm font-bold text-white md:block"
                style="background: #252729;">
                {{ number_format($brands->total()) }} marcas
            </div>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.brands.index') }}"
                class="mb-6 rounded-3xl border border-white/20 p-4"
                style="background: #313335;">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
                    <div class="relative flex-1">


                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            class="input w-full px-4"
                            placeholder="Buscar por nombre o referencia..."
                            style="background: #252729; color: #ffffff; border-color: rgba(255, 255, 255, 0.18);"
                        >
                    </div>

                    <button type="submit" class="button-brand-outline">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 018 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                        </svg>
                        Buscar
                    </button>

                    @if ($search)
                        <a href="{{ route('admin.brands.index') }}" class="button-brand-outline">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>

            @if ($brands->isEmpty())
                <div class="empty-state" style="background: #313335; color: #d8dee9; border: 1px solid rgba(255, 255, 255, 0.18);">
                    No hay marcas registradas con los criterios actuales.
                </div>
            @else
                <div class="overflow-hidden rounded-3xl border" style="background: #313335; border-color: #538bfb;">
                    <div class="overflow-x-auto">
                        <table class="w-full" style="background: #313335; border-collapse: separate; border-spacing: 0;">
                            <thead style="background: #313335;">
                                <tr>
                                    <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-widest" style="background: #313335; color: #ffffff; border-bottom: 1px solid rgba(83, 139, 251, 0.45);">Marca</th>
                                    <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-widest" style="background: #313335; color: #ffffff; border-bottom: 1px solid rgba(83, 139, 251, 0.45);">Referencia</th>
                                    <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-widest" style="background: #313335; color: #ffffff; border-bottom: 1px solid rgba(83, 139, 251, 0.45);">Productos asociados</th>
                                    <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-widest" style="background: #313335; color: #ffffff; border-bottom: 1px solid rgba(83, 139, 251, 0.45);">Creacion</th>
                                    <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-widest" style="background: #313335; color: #ffffff; border-bottom: 1px solid rgba(83, 139, 251, 0.45);">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($brands as $brand)
                                    <tr class="transition hover:bg-white/5" style="background: #313335; border-top: 1px solid rgba(255, 255, 255, 0.10);">
                                        <td class="px-5 py-5" style="background: #313335; border-top: 1px solid rgba(255, 255, 255, 0.10);">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-10 w-10 items-center justify-center rounded-2xl"
                                                    style="background: rgba(26, 85, 201, 0.16); color: #6ec1e5;">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                    </svg>
                                                </div>

                                                <div>
                                                    <div class="font-bold text-white">{{ $brand->name }}</div>
                                                    <div class="mt-1 text-xs font-semibold" style="color: #9ca8bd;">
                                                        Marca del catalogo
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-5 py-5" style="background: #313335; border-top: 1px solid rgba(255, 255, 255, 0.10);">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-black uppercase tracking-wide"
                                                style="background: rgba(26, 85, 201, 0.16); color: #6ec1e5; border: 1px solid rgba(110, 193, 229, 0.45);">
                                                {{ $brand->reference }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-5" style="background: #313335; border-top: 1px solid rgba(255, 255, 255, 0.10);">
                                            <span class="inline-flex min-w-10 justify-center rounded-2xl px-3 py-2 text-sm font-black text-white"
                                                style="background: #252729; border: 1px solid rgba(255, 255, 255, 0.15);">
                                                {{ number_format($brand->products_count) }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-5 text-sm font-semibold" style="background: #313335; color: #d8dee9; border-top: 1px solid rgba(255, 255, 255, 0.10);">
                                            {{ $brand->created_at?->format('d/m/Y') }}
                                        </td>

                                        <td class="px-5 py-5" style="background: #313335; border-top: 1px solid rgba(255, 255, 255, 0.10);">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <a
                                                    href="{{ route('admin.brands.edit', $brand) }}"
                                                    class="button-brand-outline"
                                                    title="Editar marca"
                                                >
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                    Editar
                                                </a>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.brands.destroy', $brand) }}"
                                                    onsubmit="return confirm('Seguro que deseas eliminar la marca {{ $brand->name }}? Esta accion no se puede deshacer.');"
                                                    class="inline"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="button-brand-outline"
                                                        title="Eliminar marca"
                                                        style="border-color: rgba(220, 38, 38, 0.55); color: #dc2626; background: rgba(220, 38, 38, 0.08);"
                                                    >
                                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                </div>

                <div class="pagination mt-6">
                    {{ $brands->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
