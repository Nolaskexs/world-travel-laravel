
@extends('admin')

@section('title', 'Detalle del cliente')
@section('active', 'clientes')

@push('styles')
<style>
    .client-detail {
        max-width: 850px;
        margin: 0 auto;
        padding: 40px 25px 70px;
        color: var(--ink, #16354a);
    }

    .client-detail h1 {
        font-size: 30px;
        margin-bottom: 10px;
    }

    .client-detail__desc {
        color: var(--slate, #64748b);
        margin-bottom: 25px;
    }

    .client-detail__card {
        padding: 30px;
        background: var(--surface, #ffffff);
        border: 1px solid #dce5eb;
        border-radius: 15px;
    }

    .client-detail__field {
        padding: 17px 0;
        border-bottom: 1px solid #e8eef2;
    }

    .client-detail__field:last-child {
        border-bottom: none;
    }

    .client-detail__field span {
        display: block;
        font-size: 13px;
        color: var(--slate, #64748b);
        margin-bottom: 7px;
    }

    .client-detail__field strong {
        font-size: 16px;
        overflow-wrap: anywhere;
    }

    .client-detail__back {
        display: inline-block;
        background: #106d9f;
        color: #ffffff;
        padding: 12px 18px;
        border-radius: 9px;
        text-decoration: none;
        font-weight: 700;
        margin-top: 25px;
    }
</style>
@endpush

@section('content')

<main class="client-detail">

    <h1>Información del cliente</h1>

    <p class="client-detail__desc">
        Datos de la cuenta registrada en World Travel.
    </p>

    <section class="client-detail__card">

        <div class="client-detail__field">
            <span>ID del cliente</span>
            <strong>#{{ $cliente->id }}</strong>
        </div>

        <div class="client-detail__field">
            <span>Nombre completo</span>
            <strong>{{ $cliente->name }}</strong>
        </div>

        <div class="client-detail__field">
            <span>Correo electrónico</span>
            <strong>{{ $cliente->email }}</strong>
        </div>

        <div class="client-detail__field">
            <span>Tipo de usuario</span>
            <strong>Cliente</strong>
        </div>

        <div class="client-detail__field">
            <span>Fecha de registro</span>
            <strong>
                {{ $cliente->created_at?->format('d/m/Y H:i') ?? 'Sin fecha' }}
            </strong>
        </div>

    </section>

    <a
        href="{{ route('admin.clientes.index') }}"
        class="client-detail__back"
    >
        Volver a clientes
    </a>

</main>

@endsection
