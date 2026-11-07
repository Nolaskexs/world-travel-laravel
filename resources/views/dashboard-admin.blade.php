@extends('admin')

@section('title', 'Dashboard')
@section('active', 'dashboard')

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
        ['nombre' => 'Cusco, Perú',     'reservas' => 862, 'pct' => 35, 'up' => true,  'color' => '#0C1B26'],
        ['nombre' => 'Cancún, México',  'reservas' => 451, 'pct' => 15, 'up' => true,  'color' => '#3E6E8E'],
        ['nombre' => 'Roma, Italia',    'reservas' => 502, 'pct' => 20, 'up' => false, 'color' => '#6FA8C7'],
        ['nombre' => 'Tokio, Japón',    'reservas' => 502, 'pct' => 20, 'up' => false, 'color' => '#9BB8C9'],
        ['nombre' => 'París, Francia',  'reservas' => 237, 'pct' => 10, 'up' => true,  'color' => '#5B6B78'],
        ['nombre' => 'Nueva York, Estados Unidos de América', 'reservas' => 237, 'pct' => 10, 'up' => false, 'color' => '#2A4256'],
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

<section class="stats" aria-label="Resumen">
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
    <div class="img-wrap geo__map">
        <img data-fallback src="{{ asset('images/mapa-mundial.png') }}" alt="Mapa mundial de reservas">
        <span class="ph-text">Mapa<br>placeholder</span>
    </div>
    <div>
        <h2 id="geo-t">Destinos más populares</h2>
        <ul class="dests">
            @foreach ($destinos as $d)
                <li class="dest" style="--dot: {{ $d['color'] }}">
                    <span class="dest__dot" aria-hidden="true"></span>
                    <span class="trunc" title="{{ $d['nombre'] }}">{{ $d['nombre'] }}</span>
                    <span class="dest__n">{{ number_format($d['reservas']) }} reservas</span>
                    <span class="dest__pct"><svg class="i"><use href="#i-{{ $d['up'] ? 'up' : 'down' }}"/></svg>{{ $d['pct'] }}%</span>
                </li>
            @endforeach
        </ul>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const css = getComputedStyle(document.documentElement);
    const ink = css.getPropertyValue('--ink').trim(), slate = css.getPropertyValue('--slate').trim();
    const serie = (label, data, color) => ({
        label, data, borderColor: color, backgroundColor: color, borderWidth: 2.5,
        tension: 0.4, // curva spline
        pointRadius: 4, pointHoverRadius: 6,
    });

    new Chart(document.getElementById('visitsChart'), {
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
});
</script>
@endpush
