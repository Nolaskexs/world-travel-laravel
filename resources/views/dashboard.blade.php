<x-layout-dashboard title="Dashboard" body-class="page-dashboard">
    <div class="dashboard" data-dashboard>

        {{-- ===================== BARRA MÓVIL (< 1024px) ===================== --}}
        <header class="mobile-bar">
            <button type="button" class="icon-btn" data-drawer-open aria-controls="sidebar" aria-expanded="false">
                <svg class="icon" aria-hidden="true"><use href="#i-menu"/></svg>
                <span class="sr-only">Abrir menú</span>
            </button>
            <a href="{{ url('/dashboard') }}" class="brand brand--compact">
                <span class="brand__logo"><svg class="icon" aria-hidden="true"><use href="#i-globe"/></svg></span>
                <span class="brand__name">World Travel</span>
            </a>
            <div class="mobile-bar__actions">
                <button type="button" class="icon-btn" data-search-focus aria-controls="dashboard-search">
                    <svg class="icon" aria-hidden="true"><use href="#i-search"/></svg>
                    <span class="sr-only">Buscar</span>
                </button>
                <a href="#perfil" class="avatar avatar--sm" data-initials="{{ $usuario['iniciales'] }}">
                    <img src="{{ asset($usuario['avatar']) }}" alt="" data-fallback>
                    <span class="sr-only">Ir a mi perfil</span>
                </a>
            </div>
        </header>

        {{-- ===================== SIDEBAR IZQUIERDO ===================== --}}
        <aside class="sidebar" id="sidebar" aria-label="Navegación principal" data-drawer>
            <div class="sidebar__head">
                <a href="{{ url('/dashboard') }}" class="brand">
                    <img src="{{ asset('icons/icon-extends-white.png') }}" alt="World Travel" class="brand__logo">
                </a>
                <button type="button" class="icon-btn sidebar__close" data-drawer-close>
                    <svg class="icon" aria-hidden="true"><use href="#i-close"/></svg>
                    <span class="sr-only">Cerrar menú</span>
                </button>
            </div>

            <nav class="sidebar__section" aria-labelledby="nav-overview">
                <h2 class="sidebar__label" id="nav-overview">Overview</h2>
                <ul class="nav-list">
                    <li><a href="{{ url('/dashboard') }}" class="nav-link is-active" aria-current="page"><svg class="icon" aria-hidden="true"><use href="#i-home"/></svg><span>Dashboard</span></a></li>
                    <li><a href="#mis-viajes" class="nav-link"><svg class="icon" aria-hidden="true"><use href="#i-plane"/></svg><span>Mis Vuelos</span></a></li>
                    <li><a href="{{ url('/search') }}" class="nav-link"><svg class="icon" aria-hidden="true"><use href="#i-search"/></svg><span>Buscar Vuelos</span></a></li>
                    <li><a href="#historial" class="nav-link"><svg class="icon" aria-hidden="true"><use href="#i-check"/></svg><span>Check-in</span></a></li>
                    <li><a href="#" class="nav-link"><svg class="icon" aria-hidden="true"><use href="#i-list"/></svg><span>Checklist / Tareas</span></a></li>
                    <li><a href="#" class="nav-link"><svg class="icon" aria-hidden="true"><use href="#i-users"/></svg><span>Mi Grupo / Familia</span></a></li>
                </ul>
            </nav>

            <section class="sidebar__section" aria-labelledby="nav-frecuentes">
                <h2 class="sidebar__label" id="nav-frecuentes">Pasajeros frecuentes / Favoritos</h2>
                <ul class="people-list">
                    @forelse ($frecuentes as $pasajero)
                        <li class="person">
                            <span class="avatar" data-initials="{{ $pasajero['iniciales'] }}">
                                <img src="{{ asset($pasajero['avatar']) }}" alt="" loading="lazy" data-fallback>
                            </span>
                            <span class="person__info">
                                <span class="person__name truncate" title="{{ $pasajero['nombre'] }}">{{ $pasajero['nombre'] }}</span>
                                <span class="person__meta truncate">{{ $pasajero['estado'] }}</span>
                            </span>
                        </li>
                    @empty
                        <li class="empty-text">Aún no tienes pasajeros favoritos.</li>
                    @endforelse
                </ul>
            </section>

            <nav class="sidebar__section sidebar__footer" aria-labelledby="nav-config">
                <h2 class="sidebar__label" id="nav-config">Configuración</h2>
                <ul class="nav-list">
                    <li><a href="#" class="nav-link"><svg class="icon" aria-hidden="true"><use href="#i-settings"/></svg><span>Ajustes</span></a></li>
                    <li>
                        <form method="POST" action="{{ url('/logout') }}">
                            @csrf
                            <button type="submit" class="nav-link nav-link--button"><svg class="icon" aria-hidden="true"><use href="#i-logout"/></svg><span>Cerrar sesión</span></button>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>
        <div class="drawer-overlay" data-drawer-overlay hidden></div>

        {{-- ===================== CONTENIDO CENTRAL ===================== --}}
        <main class="main" id="contenido">
            @if (session('success'))
                <div style="background: #e8f5e9; border: 1px solid #81c784; color: #1b5e20; padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 12px rgba(46,125,50,0.1);">
                    <svg class="icon icon--sm" style="fill: #2e7d32; width: 22px; height: 22px; flex-shrink: 0;" aria-hidden="true"><use href="#i-check"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <section class="promo" aria-labelledby="promo-title">
                <p class="promo__eyebrow">¡Explora nuevos destinos!</p>
                <h1 class="promo__title" id="promo-title">Consigue hasta 30% de descuento en tu próximo viaje a Cusco o Cancún</h1>
                <a href="{{ url('/search') }}" class="btn btn--light">
                    Reservar ahora
                    <svg class="icon icon--sm" aria-hidden="true"><use href="#i-arrow-right"/></svg>
                </a>
            </section>

            <section class="metrics" aria-label="Resumen de actividad">
                @foreach ($metricas as $metrica)
                    <article class="metric">
                        <span class="metric__icon metric__icon--{{ $metrica['tono'] }}">
                            <svg class="icon" aria-hidden="true"><use href="#i-{{ $metrica['icono'] }}"/></svg>
                        </span>
                        <h2 class="metric__label">{{ $metrica['label'] }}</h2>
                        <span class="metric__value">({{ $metrica['valor'] }})</span>
                    </article>
                @endforeach
            </section>

            <section class="block" id="mis-viajes" aria-labelledby="viajes-title" data-carousel>
                <div class="block__head">
                    <h2 class="block__title" id="viajes-title">Continuar planificando / Mis Viajes</h2>
                    <div class="carousel-controls">
                        <button type="button" class="round-btn" data-carousel-prev>
                            <svg class="icon icon--sm" aria-hidden="true"><use href="#i-chevron-left"/></svg>
                            <span class="sr-only">Viaje anterior</span>
                        </button>
                        <button type="button" class="round-btn" data-carousel-next>
                            <svg class="icon icon--sm" aria-hidden="true"><use href="#i-chevron-right"/></svg>
                            <span class="sr-only">Viaje siguiente</span>
                        </button>
                    </div>
                </div>

                <ul class="carousel" data-carousel-track>
                    @forelse ($viajes as $viaje)
                        <li class="trip-card" data-searchable data-search-text="{{ $viaje['titulo'] }} {{ $viaje['estado_label'] }}">
                            <div class="trip-card__media">
                                <img src="{{ asset($viaje['imagen']) }}" alt="{{ $viaje['titulo'] }}" loading="lazy" data-fallback>
                                <span class="badge badge--{{ $viaje['estado'] }}">{{ $viaje['estado_label'] }}</span>
                            </div>
                            <div class="trip-card__body">
                                <h3 class="trip-card__title truncate" title="{{ $viaje['titulo'] }}">{{ $viaje['titulo'] }}</h3>
                                <p class="trip-card__date">{{ $viaje['fecha'] }}</p>
                                <div class="trip-card__footer">
                                    <div class="trip-card__summary">
                                        <strong class="trip-card__price truncate">{{ $viaje['destacado'] }}</strong>
                                        @if (!empty($viaje['nota']))
                                            <span class="trip-card__note truncate">{{ $viaje['nota'] }}</span>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn--accent btn--sm" data-action="viaje" data-label="{{ $viaje['titulo'] }}">
                                        Acción
                                        <svg class="icon icon--sm" aria-hidden="true"><use href="#i-arrow-right"/></svg>
                                    </button>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="empty-card">No tienes viajes en planificación. <a href="{{ url('/search') }}">Busca tu próximo destino</a>.</li>
                    @endforelse
                </ul>
            </section>

            <section class="block" id="historial" aria-labelledby="historial-title">
                <div class="block__head">
                    <h2 class="block__title" id="historial-title">Historial de Vuelos / Próximos Tramos</h2>
                </div>

                <div class="table-card">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="col-passenger">Pasajero y fecha</th>
                                <th scope="col" class="col-type">Tipo</th>
                                <th scope="col" class="col-route">Ruta</th>
                                <th scope="col" class="col-actions">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($historial as $tramo)
                                <tr data-searchable data-search-text="{{ $tramo['pasajero'] }} {{ $tramo['tipo'] }} {{ $tramo['origen_ciudad'] }} {{ $tramo['destino_ciudad'] }} {{ $tramo['origen'] }} {{ $tramo['destino'] }}">
                                    <td>
                                        <div class="cell-person">
                                            <span class="avatar avatar--sm" data-initials="{{ $tramo['iniciales'] }}">
                                                <img src="{{ asset($tramo['avatar']) }}" alt="" loading="lazy" data-fallback>
                                            </span>
                                            <span class="cell-stack">
                                                <span class="truncate cell-strong" title="{{ $tramo['pasajero'] }}">{{ $tramo['pasajero'] }}</span>
                                                <span class="truncate cell-muted">{{ $tramo['fecha'] }}</span>
                                            </span>
                                        </div>
                                    </td>
                                    <td><span class="truncate">{{ $tramo['tipo'] }}</span></td>
                                    <td>
                                        <span class="cell-stack">
                                            <span class="route truncate" title="{{ $tramo['origen_ciudad'] }} → {{ $tramo['destino_ciudad'] }}">
                                                {{ $tramo['origen_ciudad'] }} {{ $tramo['origen'] }}
                                                <svg class="icon icon--xs" aria-label="a"><use href="#i-swap"/></svg>
                                                {{ $tramo['destino'] }}
                                            </span>
                                            <span class="truncate cell-muted">{{ $tramo['destino_ciudad'] }}</span>
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn--outline btn--xs" data-action="tramo" data-label="{{ $tramo['accion_label'] }} · {{ $tramo['origen'] }} → {{ $tramo['destino'] }}">
                                            {{ $tramo['accion_label'] }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-text">No hay vuelos registrados todavía.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <p class="empty-text search-empty" data-search-empty hidden>No se encontraron resultados para tu búsqueda.</p>
            </section>
        </main>

        {{-- ===================== PANEL DERECHO ===================== --}}
        <aside class="panel" id="perfil" aria-label="Tu perfil">
            <form class="search" role="search" action="{{ url('/search') }}" method="GET" data-search>
                <label for="dashboard-search" class="sr-only">Buscar vuelos, destinos o reservas</label>
                <svg class="icon search__icon" aria-hidden="true"><use href="#i-search"/></svg>
                <input id="dashboard-search" name="q" type="search" class="search__input" placeholder="Buscar vuelos, destinos o reservas..." autocomplete="off" data-search-input>
                <button type="button" class="icon-btn" data-action="filtros" data-label="Filtros avanzados">
                    <svg class="icon" aria-hidden="true"><use href="#i-filter"/></svg>
                    <span class="sr-only">Filtros</span>
                </button>
            </form>

            <section class="profile" aria-labelledby="perfil-title">
                <div class="panel__head">
                    <h2 class="panel__title" id="perfil-title">Tu Perfil</h2>
                    <button type="button" class="icon-btn" data-action="perfil" data-label="Opciones de perfil">
                        <svg class="icon" aria-hidden="true"><use href="#i-more"/></svg>
                        <span class="sr-only">Más opciones</span>
                    </button>
                </div>

                <div class="profile__ring" data-progress="{{ $usuario['progreso'] }}" role="img" aria-label="Perfil completado al {{ $usuario['progreso'] }}%">
                    <span class="avatar avatar--xl" data-initials="{{ $usuario['iniciales'] }}">
                        <img src="{{ asset($usuario['avatar']) }}" alt="Foto de {{ $usuario['nombre'] }}" data-fallback>
                    </span>
                </div>

                <p class="profile__name truncate-2" title="{{ $usuario['nombre'] }}">{{ $usuario['nombre'] }}</p>
                <p class="profile__greeting">¡Buen viaje, {{ $usuario['primer_nombre'] }}!</p>
                <p class="profile__hint">Revisa tus millas acumuladas y próximos destinos</p>

                <div class="profile__actions">
                    <button type="button" class="round-btn round-btn--dark" data-action="notificaciones" data-label="Notificaciones"><svg class="icon icon--sm" aria-hidden="true"><use href="#i-bell"/></svg><span class="sr-only">Notificaciones</span></button>
                    <button type="button" class="round-btn round-btn--dark" data-action="mensajes" data-label="Mensajes"><svg class="icon icon--sm" aria-hidden="true"><use href="#i-message"/></svg><span class="sr-only">Mensajes</span></button>
                    <button type="button" class="round-btn round-btn--dark" data-action="descargas" data-label="Descargar boletos"><svg class="icon icon--sm" aria-hidden="true"><use href="#i-download"/></svg><span class="sr-only">Descargas</span></button>
                </div>
            </section>

            <section class="miles" aria-labelledby="millas-title">
                <h2 class="panel__title" id="millas-title">Millas Acumuladas</h2>
                <p class="panel__subtitle">Historial de Viajes</p>

                <ul class="chart" data-chart aria-describedby="millas-title">
                    @foreach ($millas as $mes)
                        <li class="chart__col" data-nacional="{{ $mes['nacional'] }}" data-internacional="{{ $mes['internacional'] }}" title="{{ $mes['mes'] }}: {{ number_format($mes['nacional'] + $mes['internacional']) }} millas">
                            <span class="chart__bar">
                                <span class="chart__seg chart__seg--int"></span>
                                <span class="chart__seg chart__seg--nac"></span>
                            </span>
                            <span class="chart__label">{{ $mes['mes'] }}</span>
                            <span class="sr-only">{{ $mes['mes'] }}: {{ number_format($mes['nacional']) }} nacionales y {{ number_format($mes['internacional']) }} internacionales</span>
                        </li>
                    @endforeach
                </ul>
                <div class="chart__legend" aria-hidden="true">
                    <span><i class="dot dot--nac"></i>Nacional</span>
                    <span><i class="dot dot--int"></i>Internacional</span>
                </div>
            </section>

            <section class="crew" aria-labelledby="crew-title">
                <h2 class="panel__title" id="crew-title">Tus Pilotos</h2>
                <p class="panel__subtitle">Tripulación Favorita</p>
                <ul class="crew__list">
                    @forelse ($tripulacion as $miembro)
                        <li class="person person--row">
                            <span class="avatar" data-initials="{{ $miembro['iniciales'] }}">
                                <img src="{{ asset($miembro['avatar']) }}" alt="" loading="lazy" data-fallback>
                            </span>
                            <span class="person__info">
                                <span class="person__name truncate" title="{{ $miembro['nombre'] }}">{{ $miembro['nombre'] }}</span>
                                <span class="person__meta truncate">{{ $miembro['rol'] }}</span>
                            </span>
                            <button type="button" class="btn btn--outline btn--xs" data-action="tripulacion" data-label="{{ $miembro['accion'] }}: {{ $miembro['nombre'] }}">{{ $miembro['accion'] }}</button>
                        </li>
                    @empty
                        <li class="empty-text">Sin tripulación favorita.</li>
                    @endforelse
                </ul>
                <a href="#historial" class="btn btn--accent btn--block">Ver Todo el Historial</a>
            </section>
        </aside>
    </div>
</x-layout-dashboard>
