
@extends('admin')

@section('title', 'Detalle de reserva')
@section('active', 'reservas')

@push('styles')
<style>
    .res-detail {
        max-width: 1100px;
        margin: 0 auto;
        padding: 35px 25px 70px;
        color: var(--ink, #16354a);
    }

    .res-detail__header {
        margin-bottom: 28px;
    }

    .res-detail__header h1 {
        font-size: 30px;
        margin-bottom: 8px;
    }

    .res-detail__header p {
        color: var(--slate, #64748b);
    }

    .res-detail__grid {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 20px;
    }

    .res-detail__card {
        background: var(--surface, #fff);
        border: 1px solid #dce5eb;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
    }

    .res-detail__card h2 {
        font-size: 20px;
        margin-bottom: 18px;
    }

    .res-detail__field {
        padding: 13px 0;
        border-bottom: 1px solid #e7edf1;
    }

    .res-detail__field:last-child {
        border-bottom: 0;
    }

    .res-detail__field span {
        display: block;
        color: var(--slate, #64748b);
        font-size: 13px;
        margin-bottom: 7px;
    }

    .res-detail__field strong {
        display: block;
        font-size: 15px;
        overflow-wrap: anywhere;
    }

    .res-detail__select {
        display: block;
        width: 100%;
        padding: 12px;
        margin: 12px 0 17px;
        background: var(--surface, #fff);
        color: var(--ink, #16354a);
        border: 1px solid #cbd8e0;
        border-radius: 9px;
        font: inherit;
    }

    .res-detail__button {
        display: inline-block;
        padding: 12px 19px;
        background: #106d9f;
        color: white;
        border: 0;
        border-radius: 9px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
    }

    .res-detail__button:hover {
        background: #095478;
    }

    .res-detail__back {
        margin-top: 10px;
    }

    .res-detail__alert {
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 22px;
        background: #e5f7ed;
        color: #17683f;
    }

    .res-detail__error {
        color: #b42318;
        margin-bottom: 12px;
    }

    .res-detail__passenger {
        padding: 13px 0;
        border-bottom: 1px solid #e7edf1;
    }

    .res-detail__passenger:last-child {
        border: 0;
    }

    .res-detail__passenger strong {
        display: block;
        margin-bottom: 5px;
    }

    .res-detail__passenger span {
        color: var(--slate, #64748b);
        font-size: 13px;
    }

    .res-detail__note {
        color: var(--slate, #64748b);
        font-size: 13px;
        line-height: 1.6;
        margin-top: 14px;
    }

    @media (max-width: 850px) {
        .res-detail__grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<main class="res-detail">

    <!-- CABECERA -->
    <header class="res-detail__header">

        <h1>Reserva #{{ $reserva->id }}</h1>

        <p>
            Consulta los datos y administra el estado
            de esta reserva.
        </p>

    </header>

    <!-- MENSAJES -->
    @if (session('success'))

        <div class="res-detail__alert" role="status">
            {{ session('success') }}
        </div>

    @endif

    <div class="res-detail__grid">

        <!-- COLUMNA IZQUIERDA -->
        <div>

            <section class="res-detail__card">

                <h2>Información de la reserva</h2>

                <div class="res-detail__field">
                    <span>Número de reserva</span>
                    <strong>#{{ $reserva->id }}</strong>
                </div>

                <div class="res-detail__field">
                    <span>Cliente</span>
                    <strong>{{ $reserva->cliente_nombre }}</strong>
                </div>

                <div class="res-detail__field">
                    <span>Correo electrónico</span>
                    <strong>{{ $reserva->cliente_email }}</strong>
                </div>

                <div class="res-detail__field">
                    <span>Paquete turístico</span>
                    <strong>{{ $reserva->paquete_titulo }}</strong>
                </div>

                <div class="res-detail__field">
                    <span>Destino</span>
                    <strong>
                        {{ $reserva->paquete_destino }},
                        {{ $reserva->paquete_pais }}
                    </strong>
                </div>

                <div class="res-detail__field">
                    <span>Fecha de viaje</span>
                    <strong>
                        {{ \Illuminate\Support\Carbon::parse($reserva->fecha_viaje)->format('d/m/Y') }}
                    </strong>
                </div>

                <div class="res-detail__field">
                    <span>Número de pasajeros</span>
                    <strong>{{ $reserva->num_pasajeros }}</strong>
                </div>

                <div class="res-detail__field">
                    <span>Total de la reserva</span>
                    <strong>
                        US$ {{ number_format((float) $reserva->total, 2) }}
                    </strong>
                </div>

            </section>

            <!-- PASAJEROS -->
            <section class="res-detail__card">

                <h2>Pasajeros registrados</h2>

                @forelse ($pasajeros as $pasajero)

                    <div class="res-detail__passenger">

                        <strong>
                            {{ $pasajero->nombre }}
                        </strong>

                        <span>
                            Documento: {{ $pasajero->documento }}
                        </span>

                    </div>

                @empty

                    <p class="res-detail__note">
                        Esta reserva todavía no tiene
                        datos de pasajeros registrados.
                    </p>

                @endforelse

            </section>

        </div>

        <!-- COLUMNA DERECHA -->
        <div>

            <section class="res-detail__card">

                <h2>Administrar estado</h2>

                <div class="res-detail__field">
                    <span>Estado actual</span>
                    <strong>
                        {{ ucfirst($reserva->estado) }}
                    </strong>
                </div>

                <!-- ACTUALIZAR ESTADO -->
                <form
                    method="POST"
                    action="{{ route('admin.reservas.update', $reserva->id) }}"
                >

                    @csrf
                    @method('PATCH')

                    <label for="estado">
                        <strong>Nuevo estado</strong>
                    </label>

                    <select
                        id="estado"
                        name="estado"
                        class="res-detail__select"
                        required
                    >

                        <option
                            value="pendiente"
                            @selected(old('estado', $reserva->estado) === 'pendiente')
                        >
                            Pendiente
                        </option>

                        <option
                            value="confirmada"
                            @selected(old('estado', $reserva->estado) === 'confirmada')
                        >
                            Confirmada
                        </option>

                        <option
                            value="cancelada"
                            @selected(old('estado', $reserva->estado) === 'cancelada')
                        >
                            Cancelada
                        </option>

                    </select>

                    @error('estado')
                        <p class="res-detail__error">
                            {{ $message }}
                        </p>
                    @enderror

                    <button
                        type="submit"
                        class="res-detail__button"
                    >
                        Guardar estado
                    </button>

                </form>

                <p class="res-detail__note">
                    Este cambio actualiza el estado en
                    MySQL. No procesa pagos ni reembolsos.
                </p>

            </section>

            <a
                class="res-detail__button res-detail__back"
                href="{{ route('admin.reservas.index') }}"
            >
                Volver a reservas
            </a>

        </div>

    </div>

</main>

@endsection
