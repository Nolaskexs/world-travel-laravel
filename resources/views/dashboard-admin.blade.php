@extends('admin')

@section('title', 'Dashboard')
@section('active', 'dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/jsvectormap/jsvectormap.min.css') }}">
<style>
    /* Tarjetas de estadísticas superiores */
    .stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.75rem;
    }

    .stat {
        background: #ffffff !important;
        border: 1px solid #dae5ec !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 18px rgba(12, 27, 38, 0.06) !important;
        padding: 1.5rem 1.25rem !important;
        text-align: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 26px rgba(12, 27, 38, 0.12) !important;
    }

    /* Contenedor del mapa mundial minimalista */
    .geo__map-box {
        position: relative;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: var(--r-lg, 14px);
        min-height: 330px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.02);
    }

    #worldMap {
        width: 100%;
        height: 330px;
        min-height: 320px;
        background: transparent;
    }

    /* Puntos/Marcadores interactivos del mapa */
    .jvm-marker {
        cursor: pointer;
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), stroke 0.2s ease, stroke-width 0.2s ease, r 0.2s ease;
        transform-box: fill-box;
        transform-origin: center;
    }

    .jvm-marker:hover,
    .jvm-marker.is-hovered {
        transform: scale(1.45);
        stroke: #0F172A !important;
        stroke-width: 2.5px !important;
        filter: drop-shadow(0 4px 6px rgba(12, 27, 38, 0.35));
    }

    /* Halo pulsante animado sobre el destino líder (ej. Roma) */
    @keyframes radarPulse {
        0% {
            r: 10px;
            opacity: 0.85;
            stroke-width: 2px;
        }
        70% {
            r: 22px;
            opacity: 0;
            stroke-width: 4px;
        }
        100% {
            r: 24px;
            opacity: 0;
            stroke-width: 0px;
        }
    }

    .pulse-ring {
        animation: radarPulse 2.4s infinite cubic-bezier(0.215, 0.61, 0.355, 1);
        pointer-events: none;
    }

    /* Estilo del tooltip oficial minimalista */
    .jvm-tooltip {
        background: #0F172A !important;
        color: #FFFFFF !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        font-family: 'Inter', -apple-system, sans-serif !important;
        font-size: 12.5px !important;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        pointer-events: none;
        z-index: 100;
        line-height: 1.4 !important;
    }

    /* Lista interactiva sincronizada a la derecha */
    .dest.js-dest-item {
        cursor: pointer;
        border-radius: 8px;
        padding: 0.65rem 0.65rem;
        margin: 0 -0.65rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .dest.js-dest-item:hover,
    .dest.js-dest-item.is-hovered {
        background-color: #F8FAFC;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transform: translateX(4px);
    }

    .dest.js-dest-item .dest__dot {
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease;
    }

    .dest.js-dest-item:hover .dest__dot,
    .dest.js-dest-item.is-hovered .dest__dot {
        transform: scale(1.4);
        box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.12);
    }

    .dest.js-dest-item.is-hovered .trunc {
        font-weight: 700;
        color: #0F172A;
    }

    /* Mini etiqueta sutil al pie del mapa */
    .geo__map-tag {
        position: absolute;
        bottom: 8px;
        left: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 500;
        color: #64748B;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        padding: 3px 8px;
        border-radius: 6px;
        border: 1px solid #E2E8F0;
        pointer-events: none;
    }
</style>
@endpush

@section('content')
@php
    // Datos simulados: si el controlador envía $stats, $chart o $destinos, se usan esos.
    $stats = $stats ?? [
        ['label' => 'Ingresos Totales',   'value' => '$ 128,450', 'delta' => 27, 'up' => true,  'icon' => 'dollar'],
        ['label' => 'Reservas de Vuelos', 'value' => '865',       'delta' => 88, 'up' => true,  'icon' => 'plane'],
        ['label' => 'Paquetes Vendidos',  'value' => '3,502',     'delta' => 38, 'up' => false, 'icon' => 'package'],
        ['label' => 'Nuevos Clientes',    'value' => '78',        'delta' => 78, 'up' => true,  'icon' => 'user-plus'],
    ];
    $chart = $chart ?? [
        'labels'   => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul'],
        'reservas' => [45, 130, 80, 78, 180, 105, 210],
        'visitas'  => [75, 110, 60, 205, 125, 98, 180],
    ];
    $destinos = $destinos ?? [
        ['ciudad' => 'Roma', 'nombre' => 'Roma, Italia', 'reservas' => 3, 'pct' => 38, 'up' => true, 'color' => '#0C1B26', 'coords' => [41.9028, 12.4964], 'radius' => 11.0, 'is_leader' => true],
        ['ciudad' => 'Bali', 'nombre' => 'Bali, Indonesia', 'reservas' => 1, 'pct' => 13, 'up' => true, 'color' => '#3E6E8E', 'coords' => [-8.4095, 115.1889], 'radius' => 7.4, 'is_leader' => false],
        ['ciudad' => 'Cusco', 'nombre' => 'Cusco, Perú', 'reservas' => 1, 'pct' => 13, 'up' => true, 'color' => '#6FA8C7', 'coords' => [-13.5319, -71.9675], 'radius' => 7.4, 'is_leader' => false],
        ['ciudad' => 'Buenos Aires', 'nombre' => 'Buenos Aires, Argentina', 'reservas' => 1, 'pct' => 13, 'up' => true, 'color' => '#9BB8C9', 'coords' => [-34.6037, -58.3816], 'radius' => 7.4, 'is_leader' => false],
        ['ciudad' => 'Cancún', 'nombre' => 'Cancún, México', 'reservas' => 1, 'pct' => 13, 'up' => true, 'color' => '#5B6B78', 'coords' => [21.1619, -86.8515], 'radius' => 7.4, 'is_leader' => false],
        ['ciudad' => 'Tokio', 'nombre' => 'Tokio, Japón', 'reservas' => 1, 'pct' => 13, 'up' => true, 'color' => '#2A4256', 'coords' => [35.6762, 139.6503], 'radius' => 7.4, 'is_leader' => false],
    ];
@endphp

<div class="top">
    <div>
        <h1>Dashboard</h1>
        <nav aria-label="Migas de pan">
            <ol class="crumbs">
                <li><a href="{{ url('/admin') }}" aria-label="Inicio"><svg class="i"><use href="#i-home"/></svg></a></li>
                <li aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>
    <form class="search" role="search" action="{{ url()->current() }}" method="get">
        <label class="sr" for="q">Buscar</label>
        <input id="q" type="search" name="q" placeholder="Buscar reservas, clientes, destinos...">
        <svg class="i"><use href="#i-search"/></svg>
    </form>
</div>

<section class="stats">
    @foreach ($stats as $s)
        <article class="card stat">
            <span class="stat__ico"><svg class="i"><use href="#i-{{ $s['icon'] }}"/></svg></span>
            <p class="stat__label">{{ $s['label'] }}</p>
            <p class="stat__v">{{ $s['value'] }}</p>
            <p class="stat__d"><svg class="i"><use href="#i-{{ $s['up'] ? 'up' : 'down' }}"/></svg>{{ $s['delta'] }}% {{ $s['up'] ? 'más' : 'menos' }} que el mes pasado</p>
        </article>
    @endforeach
</section>

<section class="card chart-card" aria-labelledby="chart-t">
    <div class="card__h">
        <h2 id="chart-t"><strong>Estadísticas</strong> de Visitas y Reservas</h2>
        <button class="icon-btn" type="button" aria-label="Opciones del gráfico"><svg class="i"><use href="#i-dots"/></svg></button>
    </div>
    <div class="chart"><canvas id="visitsChart" role="img" aria-label="Gráfico de visitas y reservas por mes"></canvas></div>
</section>

<section class="card geo" aria-labelledby="geo-t">
    {{-- Mapa mundial interactivo y minimalista --}}
    <div class="geo__map-box" id="worldMapWrapper">
        <div id="worldMap"></div>
        <div class="geo__map-tag">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20M2 12h20"/></svg>
            Mapa global de reservas
        </div>
    </div>

    {{-- Lista de destinos sincronizada con el mapa --}}
    <div>
        <h2 id="geo-t">Destinos más populares</h2>
        <ul class="dests" id="destsList">
            @forelse ($destinos as $index => $d)
                <li class="dest js-dest-item" data-index="{{ $index }}" style="--dot: {{ $d['color'] }}">
                    <span class="dest__dot" aria-hidden="true"></span>
                    <span class="trunc" title="{{ $d['nombre'] }}">{{ $d['nombre'] }}</span>
                    <span class="dest__n">{{ number_format($d['reservas']) }} {{ $d['reservas'] === 1 ? 'reserva' : 'reservas' }}</span>
                    <span class="dest__pct"><svg class="i"><use href="#i-{{ $d['up'] ? 'up' : 'down' }}"/></svg>{{ $d['pct'] }}%</span>
                </li>
            @empty
                <li class="dest" style="color: var(--mut); padding: 1rem 0;">No hay destinos con reservas registradas aún.</li>
            @endforelse
        </ul>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="{{ asset('vendor/jsvectormap/jsvectormap.min.js') }}"></script>
<script src="{{ asset('vendor/jsvectormap/world.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicialización de Chart.js (Curva de Visitas y Reservas)
    const css = getComputedStyle(document.documentElement);
    const ink = css.getPropertyValue('--ink').trim() || '#0c1b26';
    const slate = css.getPropertyValue('--slate').trim() || '#64748b';

    const serie = (label, data, color) => ({
        label, data, borderColor: color, backgroundColor: color, borderWidth: 2.5,
        tension: 0.4,
        pointRadius: 4, pointHoverRadius: 6,
    });

    const chartElem = document.getElementById('visitsChart');
    if (chartElem) {
        new Chart(chartElem, {
            type: 'line',
            data: {
                labels: @json($chart['labels']),
                datasets: [
                    serie('Reservas', @json($chart['reservas']), ink),
                    serie('Visitas', @json($chart['visitas']), slate),
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, border: { display: false }, grid: { color: '#EEF0F2' } },
                },
            },
        });
    }

    // 2. Inicialización del Mapa Mundial Interactivo (jsVectorMap)
    const destinosData = @json($destinos);

    // Mapear los destinos a marcadores de jsVectorMap
    const mapMarkers = destinosData.map((d, index) => ({
        name: d.nombre,
        coords: d.coords || [0, 0],
        style: {
            initial: {
                r: d.radius || 7,
                fill: d.color,
                stroke: '#FFFFFF',
                strokeWidth: 2,
                fillOpacity: 1,
                cursor: 'pointer',
            },
            hover: {
                r: (d.radius || 7) + 3,
                fill: d.color,
                stroke: '#0F172A',
                strokeWidth: 2.5,
                cursor: 'pointer',
            }
        }
    }));

    const worldMap = new jsVectorMap({
        selector: '#worldMap',
        map: 'world',
        backgroundColor: 'transparent',
        draggable: true,
        zoomButtons: false,
        zoomOnScroll: false,
        regionStyle: {
            initial: {
                fill: '#F3F4F6',
                stroke: '#E5E7EB',
                strokeWidth: 0.7,
                fillOpacity: 1,
            },
            hover: {
                fill: '#E2E8F0',
                cursor: 'default',
            }
        },
        markers: mapMarkers,
        markerStyle: {
            initial: {
                stroke: '#FFFFFF',
                strokeWidth: 2,
                fillOpacity: 1,
            },
            hover: {
                stroke: '#0F172A',
                strokeWidth: 2.5,
            }
        },
        onMarkerTooltipShow(event, tooltip, index) {
            const d = destinosData[index];
            if (!d) return;

            tooltip.text(`
                <div style="font-family: 'Inter', sans-serif; text-align: left; padding: 2px;">
                    <div style="font-weight: 700; font-size: 13px; color: #FFFFFF; margin-bottom: 3px;">
                        ${d.nombre}
                    </div>
                    <div style="font-size: 11.5px; color: #94A3B8; display: flex; align-items: center; gap: 6px;">
                        <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:${d.color};"></span>
                        <span style="color:#FFFFFF; font-weight:600;">${d.reservas} ${d.reservas === 1 ? 'reserva' : 'reservas'}</span>
                        &bull;
                        <span style="color:#38BDF8; font-weight:700;">${d.pct}% del total</span>
                    </div>
                </div>
            `, true);

            // Sincronizar: hover en marcador resalta la fila en la lista
            highlightListItem(index);
        },
        onLoaded(map) {
            // Agregar halo pulsante animado al destino líder (ej. Roma)
            const leaderIndex = destinosData.findIndex(d => d.is_leader);
            if (leaderIndex !== -1) {
                setTimeout(() => {
                    const leaderMarker = document.querySelector(`.jvm-marker[data-index="${leaderIndex}"]`);
                    if (leaderMarker && leaderMarker.parentNode) {
                        const cx = leaderMarker.getAttribute('cx');
                        const cy = leaderMarker.getAttribute('cy');
                        const ring = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        ring.setAttribute('cx', cx);
                        ring.setAttribute('cy', cy);
                        ring.setAttribute('class', 'pulse-ring');
                        ring.setAttribute('stroke', destinosData[leaderIndex].color);
                        ring.setAttribute('fill', 'none');
                        leaderMarker.parentNode.insertBefore(ring, leaderMarker);
                    }
                }, 300);
            }
        }
    });

    // 3. Sincronización Interactiva Bidireccional
    const destItems = document.querySelectorAll('.js-dest-item');

    function highlightListItem(index) {
        destItems.forEach(item => item.classList.remove('is-hovered'));
        const target = document.querySelector(`.js-dest-item[data-index="${index}"]`);
        if (target) {
            target.classList.add('is-hovered');
        }
    }

    function clearHighlight() {
        destItems.forEach(item => item.classList.remove('is-hovered'));
        document.querySelectorAll('.jvm-marker').forEach(m => m.classList.remove('is-hovered'));
    }

    destItems.forEach(item => {
        const index = item.getAttribute('data-index');

        // Hover sobre un destino en la lista de la derecha
        item.addEventListener('mouseenter', () => {
            highlightListItem(index);
            const marker = document.querySelector(`.jvm-marker[data-index="${index}"]`);
            if (marker) {
                marker.classList.add('is-hovered');
            }
        });

        item.addEventListener('mouseleave', () => {
            clearHighlight();
        });

        // Clic sobre un destino: centrar suavemente el mapa en las coordenadas
        item.addEventListener('click', () => {
            const d = destinosData[index];
            if (d && d.coords && worldMap) {
                worldMap.setFocus({
                    coords: d.coords,
                    scale: 2.2,
                    animate: true
                });
            }
        });
    });

    const mapBox = document.getElementById('worldMap');
    if (mapBox) {
        mapBox.addEventListener('mouseleave', () => {
            clearHighlight();
        });
        // Doble clic en el mapa restablece la vista general
        mapBox.addEventListener('dblclick', () => {
            if (worldMap && worldMap.reset) {
                worldMap.reset();
            }
        });
    }
});
</script>
@endpush
