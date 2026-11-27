@props([
    'action' => '',
    'numero' => '01',
    'titulo' => 'Información del viajero titular',
    'descripcion' => 'Ingresa los datos del pasajero responsable de la reserva.',
    'boton' => 'Confirmar Reserva',
    'documentoLabel' => 'Documento (DNI / Pasaporte)',
    'user' => null,
])

@php
    $titular = $user ?? auth()->user();
@endphp

<section class="checkout-card">
    <div class="checkout-card-header">
        <span class="checkout-numero">{{ $numero }}</span>
        <div>
            <h2>{{ $titulo }}</h2>
            <p>{{ $descripcion }}</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="checkout-alerta checkout-alerta--error">
            <strong>Por favor corrige los siguientes campos:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="formViajero" method="POST" action="{{ $action }}">
        @csrf
        {{ $hidden ?? '' }}

        <div class="checkout-campo">
            <label for="nombreViajero">Nombre completo *</label>
            <input type="text"
                   name="nombre"
                   id="nombreViajero"
                   placeholder="Ej. Juan Pérez"
                   value="{{ old('nombre', $titular->name ?? '') }}"
                   minlength="3"
                   maxlength="100"
                   autocomplete="name"
                   required>
        </div>

        <div class="checkout-campo">
            <label for="correoViajero">Correo electrónico *</label>
            <input type="email"
                   name="email"
                   id="correoViajero"
                   placeholder="correo@ejemplo.com"
                   value="{{ old('email', $titular->email ?? '') }}"
                   autocomplete="email"
                   required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="checkout-campo">
                <label for="documentoViajero">{{ $documentoLabel }} *</label>
                <input type="text"
                       name="documento"
                       id="documentoViajero"
                       placeholder="Ej. 74859612"
                       value="{{ old('documento', $titular->dni ?? '') }}"
                       minlength="4"
                       maxlength="30"
                       required>
            </div>

            <div class="checkout-campo">
                <label for="telefonoViajero">Teléfono de contacto</label>
                <input type="tel"
                       name="telefono"
                       id="telefonoViajero"
                       placeholder="Ej. 987654321"
                       value="{{ old('telefono', $titular->telefono ?? '') }}"
                       minlength="7"
                       maxlength="20"
                       autocomplete="tel">
            </div>
        </div>

        {{ $extra ?? '' }}

        <div class="checkout-nota">
            {{ $nota ?? 'Al confirmar, los datos se registrarán de forma real en la base de datos de World Travel. No se realizará ningún cobro.' }}
        </div>

        <button type="submit" class="checkout-submit" id="btnConfirmarReserva">
            {{ $boton }}
            <span aria-hidden="true">&rarr;</span>
        </button>
    </form>
</section>
