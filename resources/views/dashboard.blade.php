<x-layout>
    <x-slot:titulo>World Travel | Mi Panel de Viajero</x-slot:titulo>

    <x-slot:recursos>
        @vite(['resources/css/styles.css', 'resources/js/main.js'])
        <style>
            .user-dashboard {
                padding-bottom: 5rem;
            }
            .user-hero {
                background: linear-gradient(135deg, #0b2535 0%, #153e56 100%);
                color: #fff;
                padding: 3rem 2rem 3.5rem;
                border-radius: 24px;
                margin-top: 1.5rem;
                position: relative;
                overflow: hidden;
                box-shadow: 0 12px 36px rgba(11, 37, 53, 0.18);
            }
            .user-hero__glow {
                position: absolute;
                right: -60px;
                top: -60px;
                width: 320px;
                height: 320px;
                background: radial-gradient(circle, rgba(22, 131, 243, 0.25) 0%, transparent 70%);
                border-radius: 50%;
                pointer-events: none;
            }
            .user-hero__badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: rgba(255, 255, 255, 0.12);
                backdrop-filter: blur(8px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                color: #93c5fd;
                padding: 6px 14px;
                border-radius: 999px;
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 1px;
                text-transform: uppercase;
                margin-bottom: 1rem;
            }
            .user-hero h1 {
                font-size: clamp(26px, 3.5vw, 38px);
                font-weight: 800;
                line-height: 1.2;
                margin-bottom: 0.75rem;
            }
            .user-hero p {
                color: #cbd5e1;
                font-size: 15px;
                max-width: 620px;
                line-height: 1.5;
                margin-bottom: 1.75rem;
            }
            .user-hero__actions {
                display: flex;
                flex-wrap: wrap;
                gap: 0.85rem;
            }
            .user-hero__btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.75rem 1.4rem;
                border-radius: 12px;
                font-weight: 600;
                font-size: 14px;
                transition: transform 0.2s, background 0.2s;
                text-decoration: none;
            }
            .user-hero__btn--primary {
                background: #fff;
                color: #0b2535;
            }
            .user-hero__btn--primary:hover {
                transform: translateY(-2px);
                background: #f1f5f9;
            }
            .user-hero__btn--outline {
                background: rgba(255, 255, 255, 0.08);
                color: #fff;
                border: 1px solid rgba(255, 255, 255, 0.25);
            }
            .user-hero__btn--outline:hover {
                background: rgba(255, 255, 255, 0.16);
                transform: translateY(-2px);
            }
            .user-hero__btn--logout {
                background: rgba(239, 68, 68, 0.16);
                color: #fca5a5;
                border: 1px solid rgba(239, 68, 68, 0.35);
                cursor: pointer;
            }
            .user-hero__btn--logout:hover {
                background: rgba(239, 68, 68, 0.32);
                color: #fff;
                transform: translateY(-2px);
            }

            /* Métricas Rápidas */
            .user-metrics {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 1.25rem;
                margin-top: -1.75rem;
                position: relative;
                z-index: 10;
            }
            .user-metric-card {
                background: #fff;
                border-radius: 18px;
                padding: 1.35rem 1.5rem;
                box-shadow: 0 8px 24px rgba(12, 27, 38, 0.08);
                border: 1px solid #e2e8f0;
                display: flex;
                align-items: center;
                gap: 1.25rem;
                transition: transform 0.2s;
            }
            .user-metric-card:hover {
                transform: translateY(-3px);
            }
            .user-metric-icon {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            .user-metric-icon--blue { background: #e0f2fe; color: #0284c7; }
            .user-metric-icon--green { background: #dcfce7; color: #16a34a; }
            .user-metric-icon--purple { background: #f3e8ff; color: #9333ea; }
            .user-metric-info strong {
                display: block;
                font-size: 24px;
                font-weight: 800;
                color: #0b2535;
                line-height: 1.1;
            }
            .user-metric-info span {
                font-size: 13px;
                color: #64748b;
                font-weight: 500;
            }

            /* Secciones de contenido */
            .user-section {
                margin-top: 3.25rem;
            }
            .user-section-head {
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
                margin-bottom: 1.35rem;
            }
            .user-section-head h2 {
                font-size: 24px;
                font-weight: 800;
                color: #0b2535;
                margin-bottom: 4px;
            }
            .user-section-head p {
                font-size: 14px;
                color: #64748b;
            }

            /* Tarjetas de Viajes / Reservas */
            .user-trips-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
                gap: 1.5rem;
            }
            .user-trip-card {
                background: #fff;
                border-radius: 18px;
                border: 1px solid #e2e8f0;
                overflow: hidden;
                box-shadow: 0 6px 20px rgba(12, 27, 38, 0.05);
                display: flex;
                flex-direction: column;
                transition: transform 0.2s, box-shadow 0.2s;
            }
            .user-trip-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 12px 32px rgba(12, 27, 38, 0.1);
            }
            .user-trip-card__media {
                height: 180px;
                position: relative;
                background: #0b2535;
            }
            .user-trip-card__media img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .user-trip-card__badge {
                position: absolute;
                top: 14px;
                left: 14px;
                padding: 6px 12px;
                border-radius: 8px;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            .badge--confirmada, .badge--checkin {
                background: #dcfce7;
                color: #166534;
            }
            .badge--pendiente {
                background: #fef3c7;
                color: #92400e;
            }
            .badge--cancelada {
                background: #fee2e2;
                color: #991b1b;
            }
            .user-trip-card__body {
                padding: 1.35rem;
                display: flex;
                flex-direction: column;
                flex: 1;
            }
            .user-trip-card__title {
                font-size: 18px;
                font-weight: 700;
                color: #0b2535;
                margin-bottom: 0.35rem;
            }
            .user-trip-card__date {
                font-size: 13px;
                color: #64748b;
                display: flex;
                align-items: center;
                gap: 6px;
                margin-bottom: 1rem;
            }
            .user-trip-card__footer {
                margin-top: auto;
                padding-top: 1rem;
                border-top: 1px solid #f1f5f9;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 0.75rem;
            }
            .user-trip-card__price {
                display: flex;
                flex-direction: column;
            }
            .user-trip-card__price strong {
                font-size: 18px;
                font-weight: 800;
                color: #0b2535;
            }
            .user-trip-card__price small {
                font-size: 11px;
                color: #64748b;
            }
            .user-trip-card__btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 14px;
                border-radius: 9px;
                font-size: 13px;
                font-weight: 600;
                text-decoration: none;
                transition: background 0.2s, transform 0.2s;
            }
            .user-trip-card__btn:hover {
                transform: translateY(-1px);
            }
            .user-trip-card__btn--dark {
                background: #0b2535;
                color: #fff;
            }
            .user-trip-card__btn--dark:hover {
                background: #1e3a4e;
            }
            .user-trip-card__btn--pay {
                background: #2563eb;
                color: #fff;
            }
            .user-trip-card__btn--pay:hover {
                background: #1d4ed8;
            }

            /* Tabla de tramos y asientos */
            .user-table-card {
                background: #fff;
                border-radius: 18px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 8px 24px rgba(12, 27, 38, 0.05);
                overflow-x: auto;
            }
            .user-table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
                min-width: 650px;
            }
            .user-table th {
                background: #f8fafc;
                padding: 1rem 1.25rem;
                font-size: 12px;
                font-weight: 700;
                color: #475569;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                border-bottom: 1px solid #e2e8f0;
            }
            .user-table td {
                padding: 1.1rem 1.25rem;
                font-size: 14px;
                color: #1e293b;
                border-bottom: 1px solid #f1f5f9;
                vertical-align: middle;
            }
            .user-table tr:last-child td {
                border-bottom: 0;
            }
            .seat-pill {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                background: #e0f2fe;
                color: #0369a1;
                font-size: 12px;
                font-weight: 700;
                padding: 4px 10px;
                border-radius: 6px;
            }
            .seat-unassigned {
                color: #94a3b8;
                font-size: 13px;
                font-style: italic;
            }

            /* Estado vacío */
            .user-empty {
                background: #fff;
                border-radius: 18px;
                border: 1px dashed #cbd5e1;
                padding: 3.5rem 2rem;
                text-align: center;
            }
            .user-empty h3 {
                font-size: 18px;
                font-weight: 700;
                color: #0b2535;
                margin-bottom: 0.5rem;
            }
            .user-empty p {
                font-size: 14px;
                color: #64748b;
                max-width: 440px;
                margin: 0 auto 1.5rem;
            }

            @media (max-width: 900px) {
                .user-metrics {
                    grid-template-columns: 1fr;
                    margin-top: 1rem;
                }
            }
        </style>
    </x-slot:recursos>

    <div class="wrap user-dashboard">

        @if (session('success'))
            <div style="background: #e8f5e9; border: 1px solid #81c784; color: #1b5e20; padding: 14px 20px; border-radius: 12px; margin-top: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 12px rgba(46,125,50,0.1);">
                <svg class="i" style="width: 22px; height: 22px; color: #2e7d32; flex-shrink: 0;"><use href="#i-cal"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Hero de bienvenida --}}
        <section class="user-hero">
            <div class="user-hero__glow"></div>
            <span class="user-hero__badge">
                <svg class="i" style="width: 14px; height: 14px;"><use href="#i-plane"/></svg>
                Panel de Viajero
            </span>
            <h1>¡Hola, {{ $usuario['primer_nombre'] }}!</h1>
            <p>Gestiona tus reservas, consulta tus vuelos y elige tus asientos para tus próximas aventuras nacionales e internacionales.</p>
            <div class="user-hero__actions">
                <a href="{{ route('search') }}" class="user-hero__btn user-hero__btn--primary">
                    <svg class="i" style="width: 16px; height: 16px;"><use href="#i-search"/></svg>
                    <span>Buscar Vuelos</span>
                </a>
                <a href="{{ route('alojamientos') }}" class="user-hero__btn user-hero__btn--outline">
                    <span>Ver Alojamientos</span>
                </a>
                <a href="{{ route('paquetes') }}" class="user-hero__btn user-hero__btn--outline">
                    <span>Paquetes Turísticos</span>
                </a>
                <a href="{{ route('ofertas') }}" class="user-hero__btn user-hero__btn--outline">
                    <span>Ofertas Especiales</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline-flex; margin:0;">
                    @csrf
                    <button type="submit" class="user-hero__btn user-hero__btn--logout" title="Cerrar sesión">
                        <svg class="i" style="width: 15px; height: 15px;"><use href="#i-logout"/></svg>
                        <span>Cerrar sesión</span>
                    </button>
                </form>
            </div>
        </section>

        {{-- Métricas Rápidas --}}
        <section class="user-metrics" aria-label="Resumen de viajes">
            <div class="user-metric-card">
                <div class="user-metric-icon user-metric-icon--blue">
                    <svg class="i" style="width: 24px; height: 24px;"><use href="#i-plane"/></svg>
                </div>
                <div class="user-metric-info">
                    <strong>{{ $metricas[0]['valor'] ?? 0 }}</strong>
                    <span>Vuelos activos próximos</span>
                </div>
            </div>

            <div class="user-metric-card">
                <div class="user-metric-icon user-metric-icon--green">
                    <svg class="i" style="width: 24px; height: 24px;"><use href="#i-cal"/></svg>
                </div>
                <div class="user-metric-info">
                    <strong>{{ $metricas[1]['valor'] ?? 0 }}</strong>
                    <span>Check-in / Asientos listos</span>
                </div>
            </div>

            <div class="user-metric-card">
                <div class="user-metric-icon user-metric-icon--purple">
                    <svg class="i" style="width: 24px; height: 24px;"><use href="#i-user"/></svg>
                </div>
                <div class="user-metric-info">
                    <strong>{{ count($viajes) }}</strong>
                    <span>Total de reservas registradas</span>
                </div>
            </div>
        </section>

        {{-- Mis Viajes y Reservas --}}
        <section class="user-section" id="mis-viajes">
            <div class="user-section-head">
                <div>
                    <h2>Mis Viajes y Reservas</h2>
                    <p>Historial y seguimiento de tus paquetes y vuelos contratados.</p>
                </div>
                <a href="{{ route('paquetes') }}" class="btn-dark" style="padding: 8px 16px; border-radius: 9px; font-size: 13px;">Explorar más viajes</a>
            </div>

            @if (empty($viajes))
                <div class="user-empty">
                    <h3>Aún no tienes viajes registrados</h3>
                    <p>Encuentra las mejores experiencias y paquetes turísticos con vuelos, hospedajes y traslados incluidos.</p>
                    <a href="{{ route('paquetes') }}" class="btn-dark" style="display: inline-block; padding: 10px 20px; border-radius: 10px;">Ver Paquetes Turísticos</a>
                </div>
            @else
                <div class="user-trips-grid">
                    @foreach ($viajes as $viaje)
                        <article class="user-trip-card">
                            <div class="user-trip-card__media">
                                <img src="{{ asset($viaje['imagen']) }}" alt="{{ $viaje['titulo'] }}" loading="lazy">
                                <span class="user-trip-card__badge badge--{{ $viaje['estado'] }}">
                                    {{ $viaje['estado_label'] }}
                                </span>
                            </div>
                            <div class="user-trip-card__body">
                                <h3 class="user-trip-card__title">{{ $viaje['titulo'] }}</h3>
                                <p class="user-trip-card__date">
                                    <svg class="i" style="width: 15px; height: 15px; color: #94a3b8;"><use href="#i-cal"/></svg>
                                    <span>{{ $viaje['fecha'] }}</span>
                                    <span>·</span>
                                    <span>{{ $viaje['nota'] }}</span>
                                </p>
                                <div class="user-trip-card__footer">
                                    <div class="user-trip-card__price">
                                        <small>Monto total</small>
                                        <strong>{{ $viaje['destacado'] }}</strong>
                                        <small style="font-size:11px; color:#64748b; font-weight:500;">aprox. S/ {{ number_format(($viaje['total_num'] ?? 0) * 3.51, 2) }}</small>
                                    </div>
                                    @if ($viaje['estado'] === 'pendiente')
                                        <a href="{{ route('pagos.show', $viaje['id']) }}" class="user-trip-card__btn user-trip-card__btn--pay">
                                            <span>Pagar Reserva</span>
                                            <svg class="i" style="width: 14px; height: 14px;"><use href="#i-right"/></svg>
                                        </a>
                                    @else
                                        <a href="{{ $viaje['checkin_url'] ?? '#' }}" class="user-trip-card__btn user-trip-card__btn--dark">
                                            <svg class="i" style="width: 14px; height: 14px;"><use href="#i-plane"/></svg>
                                            <span>{{ $viaje['estado'] === 'checkin' ? 'Hacer Check-in' : 'Elegir Asientos' }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Tramos de Vuelo y Asientos Asignados --}}
        @if (!empty($historial))
            <section class="user-section" id="tramos">
                <div class="user-section-head">
                    <div>
                        <h2>Tramos de Vuelo y Asientos</h2>
                        <p>Detalle de pasajeros y asignación de asientos por tramo reservado.</p>
                    </div>
                </div>

                <div class="user-table-card">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Pasajero</th>
                                <th>Fecha de Viaje</th>
                                <th>Tipo</th>
                                <th>Ruta</th>
                                <th>Asiento Asignado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($historial as $tramo)
                                <tr>
                                    <td>
                                        <strong>{{ $tramo['pasajero'] }}</strong>
                                    </td>
                                    <td>{{ $tramo['fecha'] }}</td>
                                    <td>{{ $tramo['tipo'] }}</td>
                                    <td>
                                        <span>{{ $tramo['origen_ciudad'] }} ({{ $tramo['origen'] }})</span>
                                        <span style="color: #64748b; margin: 0 4px;">→</span>
                                        <strong>{{ $tramo['destino_ciudad'] }} ({{ $tramo['destino'] }})</strong>
                                    </td>
                                    <td>
                                        @if (!empty($tramo['asiento']))
                                            <span class="seat-pill">
                                                <svg class="i" style="width: 12px; height: 12px;"><use href="#i-check"/></svg>
                                                Asiento {{ $tramo['asiento'] }}
                                            </span>
                                        @else
                                            <span class="seat-unassigned">Pendiente de selección</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ $tramo['accion_url'] ?? '#' }}" class="btn-dark" style="padding: 6px 12px; border-radius: 7px; font-size: 12px;">
                                            {{ $tramo['accion_label'] }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif

        {{-- Soporte y Reclamaciones --}}
        <section class="user-section" style="background: #fff; border-radius: 18px; border: 1px solid #e2e8f0; padding: 1.75rem 2rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1.5rem;">
            <div>
                <h3 style="font-size: 18px; font-weight: 800; color: #0b2535; margin-bottom: 4px;">¿Necesitas ayuda con tu viaje?</h3>
                <p style="font-size: 14px; color: #64748b; margin: 0;">Llámanos a nuestra central de ventas y atención <strong>000-1010001</strong> o ingresa una consulta o reclamo.</p>
            </div>
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <a href="{{ route('ayuda') }}" class="btn-ghost" style="padding: 9px 16px; border-radius: 9px; font-size: 13px; font-weight: 600;">Centro de Ayuda</a>
                <a href="{{ route('reclamaciones') }}" class="btn-dark" style="padding: 9px 16px; border-radius: 9px; font-size: 13px; font-weight: 600;">Libro de Reclamaciones</a>
            </div>
        </section>

    </div>
</x-layout>
