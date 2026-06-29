<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin') | Tiendapp Catalog</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app-shell" x-data="{ isOpen: false }">

        {{-- SIDEBAR MODERNO --}}
        <aside class="sidebar">
            {{-- Overlay para móvil --}}
            <div class="sidebar-overlay" :class="{ 'opacity-100 pointer-events-auto': isOpen, 'opacity-0 pointer-events-none': !isOpen }" @click="isOpen = false"></div>

            {{-- Contenido del Sidebar --}}
            <div class="sidebar-inner" :class="{ 'translate-x-0': isOpen, '-translate-x-full lg:translate-x-0': !isOpen }">
                {{-- Botón cerrar para móvil --}}
                <button class="sidebar-close-btn" @click="isOpen = false">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="brand-box">
                    <div class="brand-logo">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div>
                        <h1 class="brand-title">Tiendapp</h1>
                        <span class="brand-subtitle">Catalog</span>
                    </div>
                </div>

                <nav class="nav">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <span class="nav-link-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        </span>
                        <span>Dashboard</span>
                        @if(request()->routeIs('admin.dashboard'))
                            <span class="nav-link-indicator"></span>
                        @endif
                    </a>

                    <a class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}" href="{{ route('admin.brands.index') }}">
                        <span class="nav-link-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </span>
                        <span>Marcas</span>
                        @if(request()->routeIs('admin.brands.*'))
                            <span class="nav-link-indicator"></span>
                        @endif
                    </a>

                    <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                        <span class="nav-link-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </span>
                        <span>Productos</span>
                        @if(request()->routeIs('admin.products.*'))
                            <span class="nav-link-indicator"></span>
                        @endif
                    </a>
                </nav>
            </div>
        </aside>

        {{-- CONTENIDO PRINCIPAL --}}
        <main class="main-content">
            {{-- TOPBAR --}}
            <header class="topbar">
                <div class="flex items-center gap-4">
                    {{-- Botón para abrir sidebar en móvil --}}
                    <button class="lg:hidden text-slate-400 hover:text-white p-2 -ml-2" @click="isOpen = true">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h2 class="page-title">@yield('page_title')</h2>
                        <p class="page-description">@yield('page_description')</p>
                    </div>
                </div>

                <div class="topbar-actions">
                    @yield('page_actions')
                </div>
            </header>

            {{-- FLASH MESSAGES --}}
            @if (session('success'))
                <div class="alert alert-success" x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.duration.500ms>
                    <span class="alert-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    {{ session('success') }}
                    <button @click="show = false" class="ml-auto text-emerald-700/70 hover:text-emerald-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error" x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.duration.500ms>
                    <span class="alert-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    {{ session('error') }}
                    <button @click="show = false" class="ml-auto text-red-700/70 hover:text-red-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            {{-- CONTENIDO DINÁMICO --}}
            <div class="content-area">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Alpine.js para funcionalidades interactivas --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>