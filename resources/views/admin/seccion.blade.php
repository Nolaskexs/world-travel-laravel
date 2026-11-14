@extends('admin')

@section('title', $titulo)
@section('active', $clave)

@push('styles')
<style>
    .wt-section {
        padding: 35px;
        max-width: 1100px;
        margin: 0 auto;
    }

    .wt-section__heading {
        margin-bottom: 25px;
    }

    .wt-section__heading h1 {
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .wt-section__heading p {
        color: var(--slate, #64748b);
        line-height: 1.6;
    }

    .wt-section__card {
        background: var(--surface, #fff);
        border: 1px solid #e3e9ee;
        border-radius: 16px;
        padding: 35px;
    }

    .wt-section__card h2 {
        font-size: 21px;
        margin-bottom: 12px;
    }

    .wt-section__card p {
        color: var(--slate, #64748b);
        line-height: 1.7;
        margin-bottom: 25px;
    }

    .wt-section__button {
        display: inline-block;
        background: #1377ae;
        color: #fff;
        padding: 12px 20px;
        border-radius: 9px;
        text-decoration: none;
        font-weight: 700;
    }

    .wt-section__button:hover {
        background: #0b5c88;
    }
</style>
@endpush

@section('content')

<main class="wt-section">

    <div class="wt-section__heading">
        <h1>{{ $titulo }}</h1>
        <p>{{ $descripcion }}</p>
    </div>

    <section class="wt-section__card">

        <h2>Módulo en desarrollo</h2>

        <p>
            Esta sección ya está integrada al panel administrativo
            de World Travel.

            Sus funcionalidades de consulta, registro y
            administración se implementarán en la siguiente etapa.
        </p>

        <a
            href="{{ route('admin.dashboard') }}"
            class="wt-section__button"
        >
            Volver al Dashboard
        </a>

    </section>

</main>

@endsection
