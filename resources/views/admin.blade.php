<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Panel de administración') | World Travel
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite([
        'resources/css/admin.css',
        'resources/js/admin.js'
    ])

    @stack('styles')
</head>

<body>

@php
    $active = trim($__env->yieldContent('active', 'dashboard'));
    $adminName = auth()->user()->name ?? 'Administrador';
    $adminEmail = auth()->user()->email ?? '';

    $badgeReservas = \App\Models\Reserva::where('estado', 'pendiente')->count();
    $badgeReclamaciones = \App\Models\Reclamacion::where('estado', 'pendiente')->count();

    $menuItems = [
        [
            'key' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'home',
            'href' => route('admin.dashboard')
        ],
        [
            'key' => 'reservas',
            'label' => 'Reservas',
            'icon' => 'cal-check',
            'href' => url('/admin/reservas'),
            'badge' => $badgeReservas > 0 ? $badgeReservas : null
        ],
        [
            'key' => 'paquetes',
            'label' => 'Paquetes turísticos',
            'icon' => 'package',
            'href' => route('admin.paquetes.index')
        ],
        [
            'key' => 'destinos',
            'label' => 'Destinos',
            'icon' => 'pin',
            'href' => url('/admin/destinos')
        ],
        [
            'key' => 'clientes',
            'label' => 'Clientes',
            'icon' => 'users',
            'href' => url('/admin/clientes')
        ],
        [
            'key' => 'facturacion',
            'label' => 'Facturación',
            'icon' => 'receipt',
            'href' => url('/admin/facturacion')
        ],
        [
            'key' => 'reclamaciones',
            'label' => 'Reclamaciones',
            'icon' => 'claim',
            'href' => route('admin.reclamaciones.index'),
            'badge' => $badgeReclamaciones > 0 ? $badgeReclamaciones : null
        ],
        [
            'key' => 'ajustes',
            'label' => 'Ajustes',
            'icon' => 'sliders',
            'href' => url('/admin/ajustes')
        ],
    ];
@endphp

<!-- ICONOS SVG DEL PANEL -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="i-home" viewBox="0 0 24 24">
        <path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/>
    </symbol>
    <symbol id="i-dollar" viewBox="0 0 24 24">
        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
    </symbol>
    <symbol id="i-plane" viewBox="0 0 24 24">
        <path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.2.4c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.3c.4-.2.6-.6.5-1.1z"/>
    </symbol>
    <symbol id="i-package" viewBox="0 0 24 24">
        <path d="m7.5 4.3 9 5.2M21 8v8a2 2 0 0 1-1 1.7l-7 4a2 2 0 0 1-2 0l-7-4A2 2 0 0 1 3 16V8a2 2 0 0 1 1-1.7l7-4a2 2 0 0 1 2 0l7 4A2 2 0 0 1 21 8zM3.3 7 12 12l8.7-5M12 22V12"/>
    </symbol>
    <symbol id="i-user-plus" viewBox="0 0 24 24">
        <circle cx="9" cy="8" r="4"/>
        <path d="M2 21a7 7 0 0 1 14 0M19 8v6M16 11h6"/>
    </symbol>
    <symbol id="i-cal-check" viewBox="0 0 24 24">
        <rect x="3" y="4" width="18" height="18" rx="2"/>
        <path d="M16 2v4M8 2v4M3 10h18M9 16l2 2 4-4"/>
    </symbol>
    <symbol id="i-users" viewBox="0 0 24 24">
        <circle cx="9" cy="8" r="4"/>
        <path d="M2 21a7 7 0 0 1 14 0M16 4a4 4 0 0 1 0 8M22 21a7 7 0 0 0-5-6.7"/>
    </symbol>
    <symbol id="i-pin" viewBox="0 0 24 24">
        <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/>
        <circle cx="12" cy="10" r="3"/>
    </symbol>
    <symbol id="i-receipt" viewBox="0 0 24 24">
        <path d="M5 2h14v20l-3-2-2 2-2-2-2 2-2-2-3 2zM9 7h6M9 11h6"/>
    </symbol>
    <symbol id="i-claim" viewBox="0 0 24 24">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
        <line x1="16" y1="13" x2="8" y2="13"/>
        <line x1="16" y1="17" x2="8" y2="17"/>
        <polyline points="10 9 9 9 8 9"/>
    </symbol>
    <symbol id="i-search" viewBox="0 0 24 24">
        <circle cx="11" cy="11" r="8"/>
        <path d="m21 21-4.3-4.3"/>
    </symbol>
    <symbol id="i-sliders" viewBox="0 0 24 24">
        <path d="M4 6h10M18 6h2M4 12h2M10 12h10M4 18h12M20 18h0"/>
        <circle cx="16" cy="6" r="2"/>
        <circle cx="8" cy="12" r="2"/>
        <circle cx="18" cy="18" r="2"/>
    </symbol>
    <symbol id="i-logout" viewBox="0 0 24 24">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
    </symbol>
    <symbol id="i-menu" viewBox="0 0 24 24">
        <path d="M4 6h16M4 12h16M4 18h16"/>
    </symbol>
    <symbol id="i-x" viewBox="0 0 24 24">
        <path d="M18 6 6 18M6 6l12 12"/>
    </symbol>
    <symbol id="i-up" viewBox="0 0 24 24">
        <path d="m6 15 6-6 6 6"/>
    </symbol>
    <symbol id="i-down" viewBox="0 0 24 24">
        <path d="m6 9 6 6 6-6"/>
    </symbol>
    <symbol id="i-dots" viewBox="0 0 24 24">
        <circle cx="5" cy="12" r="1.5"/>
        <circle cx="12" cy="12" r="1.5"/>
        <circle cx="19" cy="12" r="1.5"/>
    </symbol>
</svg>

<!-- PANEL DE ADMINISTRACIÓN CON NAVEGACIÓN SUPERIOR (ARRIBA, COMO EL HOME) -->
<div class="admin">

    <!-- CABECERA DE ADMINISTRACIÓN SUPERIOR -->
    <header class="admin-topbar-nav" aria-label="Navegación del panel de administración">
        
        <!-- Fila Superior: Marca, estado, perfil del admin y accesos directos -->
        <div class="admin-topbar-nav__top">
            <div class="admin-brand-group">
                <a class="admin-brand" href="{{ route('admin.dashboard') }}">
                    <span class="logo">
                        <img src="{{ asset('icons/logo-cut-light.png') }}" alt="World Travel">
                    </span>
                    <span>World Travel</span>
                </a>
                <span class="admin-badge">Admin Mode</span>
            </div>

            <div class="admin-user-group">
                <a href="{{ route('inicio') }}" class="admin-link-site" title="Ver sitio público">
                    <svg class="i"><use href="#i-plane"/></svg>
                    <span>Ver Sitio Web</span>
                </a>

                <div class="admin-user-pill">
                    <span class="img-wrap avatar-sm">
                        <img data-fallback src="{{ asset('images/avatar-admin.png') }}" alt="">
                        <span class="ph-text">{{ Str::upper(Str::substr($adminName, 0, 1)) }}</span>
                    </span>
                    <div class="admin-user-details">
                        <strong class="trunc">{{ $adminName }}</strong>
                        <span class="trunc muted">{{ $adminEmail }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" style="display:inline-block; margin:0;">
                    @csrf
                    <button class="admin-btn-logout" type="submit" title="Cerrar sesión">
                        <svg class="i"><use href="#i-logout"/></svg>
                        <span>Cerrar sesión</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Fila de Opciones / Menú Horizontal Ordenado -->
        <nav class="admin-menu-bar" aria-label="Secciones del panel de administración">
            <div class="admin-menu-bar__scroll">
                <ul class="admin-nav-tabs">
                    @foreach ($menuItems as $it)
                        <li>
                            <a class="admin-tab {{ $active === $it['key'] ? 'is-active' : '' }}" href="{{ $it['href'] }}" @if ($active === $it['key']) aria-current="page" @endif>
                                <svg class="i"><use href="#i-{{ $it['icon'] }}"/></svg>
                                <span>{{ $it['label'] }}</span>
                                @isset($it['badge'])
                                    <span class="admin-tab__badge">{{ $it['badge'] }}</span>
                                @endisset
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="admin-quick-actions">
                    <a href="{{ route('admin.paquetes.create') }}" class="admin-quick-btn">+ Nuevo Paquete</a>
                    <a href="{{ route('admin.destinos.create') }}" class="admin-quick-btn">+ Nuevo Destino</a>
                </div>
            </div>
        </nav>

    </header>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="content">
        @yield('content')
    </div>

</div>

@stack('scripts')

</body>
</html>
