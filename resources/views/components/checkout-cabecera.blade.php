@props([
    'titulo' => 'Confirma tu reserva',
    'descripcion' => 'Completa tus datos para registrar la reserva en la base de datos de World Travel.',
    'eyebrow' => 'WORLD TRAVEL / PROCESO PRINCIPAL',
    'volver' => null,
])

<a href="{{ $volver ?? url()->previous() }}" class="checkout-volver">
    &larr; Volver
</a>

<div class="checkout-heading">
    <span class="checkout-eyebrow">{{ $eyebrow }}</span>
    <h1>{{ $titulo }}</h1>
    <p>{{ $descripcion }}</p>
</div>

<div class="checkout-pasos">
    <span class="paso-listo">1. Selección</span>
    <span class="paso-linea"></span>
    <span class="paso-listo">2. Cotización</span>
    <span class="paso-linea"></span>
    <span class="paso-actual">3. Confirmación y Registro</span>
</div>
