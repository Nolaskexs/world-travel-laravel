<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $titulo ?? 'World Travel' }}</title>
  <link rel="icon" type="image/png" media="(prefers-color-scheme: light)" href="{{ asset('icons/logo-cut-dark.png') }}">
  <link rel="icon" type="image/png" media="(prefers-color-scheme: dark)" href="{{ asset('icons/logo-cut-light.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('icons/logo-cut-light.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  {{-- 1. Cargar el CSS global del header y footer para todas las páginas --}}
  @vite(['resources/css/styles.css'])

  {{-- Usar {!! !!} permite inyectar etiquetas HTML sin escaparlas --}}
  {!! $recursos ?? '' !!}
</head>
<body>
  <!-- partial: sprite de iconos -->
  <svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="i-plane" viewBox="0 0 24 24"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.2.4c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.3c.4-.2.6-.6.5-1.1z"/></symbol>
    <symbol id="i-bed" viewBox="0 0 24 24"><path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"/></symbol>
    <symbol id="i-case" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></symbol>
    <symbol id="i-car" viewBox="0 0 24 24"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2M9 17h6"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></symbol>
    <symbol id="i-flame" viewBox="0 0 24 24"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.4-.5-2-1-3-1.1-2.1-.2-4 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.2.4-2.4 1-3 0 1.5 1 2.5 2.5 2.5z"/></symbol>
    <symbol id="i-headset" viewBox="0 0 24 24"><path d="M3 14v-3a9 9 0 0 1 18 0v3M21 16a2 2 0 0 1-2 2h-1a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h3zM3 16a2 2 0 0 0 2 2h1a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1H3z"/></symbol>
    <symbol id="i-bell" viewBox="0 0 24 24"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9M10.3 21a1.9 1.9 0 0 0 3.4 0"/></symbol>
    <symbol id="i-pin" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></symbol>
    <symbol id="i-cal" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
    <symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></symbol>
    <symbol id="i-swap" viewBox="0 0 24 24"><path d="m17 3 4 4-4 4M21 7H3M7 21l-4-4 4-4M3 17h18"/></symbol>
    <symbol id="i-user" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></symbol>
    <symbol id="i-left" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></symbol>
    <symbol id="i-right" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></symbol>
    <symbol id="i-plus" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></symbol>
    <symbol id="i-minus" viewBox="0 0 24 24"><path d="M5 12h14"/></symbol>
    <symbol id="i-mail" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></symbol>
    <symbol id="i-star" viewBox="0 0 24 24"><path d="m12 2 3.1 6.3 6.9 1-5 4.9 1.2 6.9L12 17.8 5.8 21l1.2-6.9-5-4.9 6.9-1z"/></symbol>
    <symbol id="i-down" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></symbol>
    <symbol id="i-arrow" viewBox="0 0 24 24"><path d="M5 12h14m-6-6 6 6-6 6"/></symbol>
    <symbol id="i-info" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></symbol>
    <symbol id="i-x" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></symbol>
    <symbol id="i-filter" viewBox="0 0 24 24"><path d="M4 6h16M7 12h10M10 18h4"/></symbol>
    <symbol id="i-bag" viewBox="0 0 24 24"><path d="M4 10a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zM9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2M8 14h8"/></symbol>
    <symbol id="i-suit" viewBox="0 0 24 24"><rect x="6" y="6" width="12" height="15" rx="2"/><path d="M9 6V3h6v3M9 10v7M15 10v7"/></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></symbol>
  </svg>

  <!-- partial: header -->
  <header class="hdr">
    <div class="wrap hdr__top">
      <picture class="logo-ph">
        <source media="(prefers-color-scheme: dark)" srcset="{{ asset('icons/logo-cut-dark.png') }}">
        <source media="(prefers-color-scheme: light)" srcset="{{ asset('icons/logo-cut-light.png') }}">
        <a href="{{ route('inicio') }}"><img class="logo ph" src="{{ asset('icons/logo-cut-light.png') }}" alt="World Travel"></a>
      </picture>
      <div class="util">
        <a class="btn-sales" href="tel:0001010001">Para ventas 000-1010001</a>
        <a href="#">Mis viajes</a>
        <span class="sep"></span>
        <a href="#">Ayuda</a>
        <span class="sep"></span>
        <button class="ico" type="button" aria-label="Soporte">
          <svg class="i"><use href="#i-headset"/></svg>
        </button>
        <button class="ico" type="button" aria-label="Notificaciones">
          <svg class="i"><use href="#i-bell"/></svg>
        </button>
        <a class="btn-login" href="{{ route('login') }}">Iniciar sesión</a>
      </div>
    </div>
    <nav class="wrap tabs" role="tablist" aria-label="Servicios">


      <a href="{{ route('inicio') }}"
        class="tab {{ request()->routeIs('inicio') ? 'is-active' : '' }}"
        aria-current="{{ request()->routeIs('inicio') ? 'page' : 'false' }}">
          <svg class="i">
              <use href="#i-plane"/>
          </svg>
          Vuelos
      </a>  


      <a href="{{ route('alojamientos') }}"
          class="tab {{ request()->routeIs('alojamientos') ? 'is-active' : '' }}"
          aria-current="{{ request()->routeIs('alojamientos') ? 'page' : 'false' }}">
            <svg class="i">
                <use href="#i-bed"/>
            </svg>
            Alojamientos
      </a>
      <a href="{{ route('paquetes') }}"
            class="tab {{ request()->routeIs('paquetes*') ? 'is-active' : '' }}"
            aria-current="{{ request()->routeIs('paquetes*') ? 'page' : 'false' }}">
              <svg class="i">
                  <use href="#i-case"/>
            </svg>
            Paquetes
       </a>

      <a href="{{ route('autos') }}"
          class="tab {{ request()->routeIs('autos') ? 'is-active' : '' }}"
          aria-current="{{ request()->routeIs('autos') ? 'page' : 'false' }}">
            <svg class="i">
                <use href="#i-car"/>
            </svg>
            Autos
      </a>
      
      <a href="{{ route('ofertas') }}"
        class="tab {{ request()->routeIs('ofertas') ? 'is-active' : '' }}"
        aria-current="{{ request()->routeIs('ofertas') ? 'page' : 'false' }}">
          <svg class="i">
              <use href="#i-flame"/>
          </svg>
          Ofertas
      </a>
    </nav>
  </header>

  {{-- Contenido único de la página que invoca al layout --}}
  <main>
    {{ $slot }}
  </main>

  <!-- partial: footer -->
  <!-- footer -->
  <footer class="ftr">
    <div class="wrap">
      <div class="ftr__bar"></div>
      <div class="ftr__cols">
        <div>
          <h4>Ayuda</h4>
          <ul>
            <li><a href="#">Contáctenos</a></li>
            <li><a href="#">Recibos y reembolsos</a></li>
            <li><a href="#">Preguntas frecuentes</a></li>
            <li><a href="#">Planes de servicio al cliente</a></li>
            <li><a href="#">Condiciones de transporte</a></li>
          </ul>
        </div>
        <div>
          <h4>Acerca de World Travel</h4>
          <ul>
            <li><a href="#">Acerca de World Travel</a></li>
            <li><a href="#">Centro de noticias</a></li>
            <li><a href="#">Políticas e información legal</a></li>
            <li><a href="#">Accesibilidad del sitio web</a></li>
            <li><a href="#">Compatibilidad del navegador</a></li>
          </ul>
        </div>
        <div>
          <h4>Adicionales</h4>
          <a href="#">Libro de reclamaciones</a>
          <br><img src="{{ asset('icons/libro-de-reclamaciones.jpg') }}" class="ph libro"><br>
        </div>
      </div>
    </div>
  </footer>
</body>
</html>