@extends('admin')

@section('title', 'Gestión de reservas')
@section('active', 'reservas')

@push('styles')
<style>
    .reservas-page {
        max-width: 1250px;
        margin: 0 auto;
        padding: 35px 25px 70px;
        color: var(--ink, #16354a);
    }

    .reservas-header {
        margin-bottom: 28px;
    }

    .reservas-header h1 {
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .reservas-header p {
        color: var(--slate, #64748b);
        margin: 0;
        line-height: 1.6;
    }

    .reservas-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 25px;
    }

    .reservas-stat {
        background: var(--surface, white);
        border: 1px solid #dae5ec;
        border-radius: 13px;
        padding: 22px;
        box-shadow: 0 4px 15px rgba(20, 50, 75, .03);
    }

    .reservas-stat span {
        display: block;
        font-size: 13px;
        color: var(--slate, #64748b);
        margin-bottom: 10px;
    }

    .reservas-stat strong {
        font-size: 28px;
        font-weight: 800;
    }

    .reservas-services-title {
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 16px;
    }

    .reservas-services {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .reservas-service-card {
        background: var(--surface, white);
        border: 1px solid #dae5ec;
        border-radius: 15px;
        padding: 24px;
        box-shadow: 0 8px 30px rgba(20, 50, 75, .05);
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }

    .reservas-service-card:hover {
        transform: translateY(-2px);
        border-color: #9dc7df;
        box-shadow: 0 12px 34px rgba(20, 50, 75, .09);
    }

    .reservas-service-card__top {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 14px;
    }

    .reservas-service-card__icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #e0f2fe;
        color: #0369a1;
        flex-shrink: 0;
    }

    .reservas-service-card h3 {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
    }

    .reservas-service-card p {
        color: var(--slate, #64748b);
        font-size: 14px;
        line-height: 1.6;
        margin: 0 0 18px;
    }

    .reservas-service-card__footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
    }

    .reservas-service-card__count {
        font-size: 13px;
        font-weight: 700;
        color: #475569;
    }

    .reservas-service-card__count strong {
        color: #106d9f;
        font-size: 20px;
        margin-right: 4px;
    }

    .reservas-service-card__link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #106d9f;
        color: #fff;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        transition: background .2s ease;
    }

    .reservas-service-card__link:hover {
        background: #084e74;
    }

    @media (max-width: 850px) {
        .reservas-stats,
        .reservas-services {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 560px) {
        .reservas-page {
            padding: 25px 15px 50px;
        }

        .reservas-stats,
        .reservas-services {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<main class="reservas-page">

    <header class="reservas-header">
        <h1>Gestión de reservas</h1>
        <p>
            Consulta las reservas por servicio: vuelos, alojamientos,
            paquetes turísticos y ofertas.
        </p>
    </header>

    <section class="reservas-stats" aria-label="Resumen general de reservas">
        <article class="reservas-stat">
            <span>Total de reservas</span>
            <strong>{{ $totalReservas }}</strong>
        </article>

        <article class="reservas-stat">
            <span>Pendientes</span>
            <strong>{{ $pendientes }}</strong>
        </article>

        <article class="reservas-stat">
            <span>Confirmadas</span>
            <strong>{{ $confirmadas }}</strong>
        </article>

        <article class="reservas-stat">
            <span>Canceladas</span>
            <strong>{{ $canceladas }}</strong>
        </article>
    </section>

    <h2 class="reservas-services-title">Selecciona una subsección</h2>

    <section class="reservas-services">

        @foreach ($servicios as $clave => $servicio)
            <article class="reservas-service-card">
                <div class="reservas-service-card__top">
                    <span class="reservas-service-card__icon">
                        <svg class="i" aria-hidden="true">
                            <use href="#i-{{ $servicio['icono'] ?? 'cal-check' }}"/>
                        </svg>
                    </span>
                    <h3>Reservas de {{ $servicio['plural'] }}</h3>
                </div>

                <p>{{ $servicio['descripcion'] }}</p>

                <div class="reservas-service-card__footer">
                    <span class="reservas-service-card__count">
                        <strong>{{ $servicio['total'] }}</strong>
                        {{ $servicio['total'] === 1 ? 'reserva' : 'reservas' }}
                    </span>

                    <a
                        class="reservas-service-card__link"
                        href="{{ $servicio['ruta'] }}"
                    >
                        Ver reservas
                    </a>
                </div>
            </article>
        @endforeach

    </section>

</main>

@endsection
