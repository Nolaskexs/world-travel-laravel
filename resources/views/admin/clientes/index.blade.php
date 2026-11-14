
@extends('admin')

@section('title', 'Gestión de clientes')
@section('active', 'clientes')

@push('styles')
<style>
    .clients-page {
        padding: 35px 25px 70px;
        max-width: 1200px;
        margin: 0 auto;
        color: var(--ink, #16354a);
    }

    .clients-header {
        margin-bottom: 28px;
    }

    .clients-header h1 {
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .clients-header p {
        color: var(--slate, #64748b);
    }

    .clients-summary {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 23px;
        margin-bottom: 25px;
        border-radius: 14px;
        background: var(--surface, #ffffff);
        border: 1px solid #dce5eb;
    }

    .clients-summary__icon {
        font-size: 27px;
    }

    .clients-summary strong {
        display: block;
        font-size: 28px;
    }

    .clients-summary span {
        color: var(--slate, #64748b);
        font-size: 14px;
    }

    .clients-panel {
        background: var(--surface, #ffffff);
        border: 1px solid #dce5eb;
        border-radius: 15px;
        overflow: hidden;
    }

    .clients-toolbar {
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .clients-toolbar h2 {
        font-size: 19px;
        font-weight: 700;
    }

    .clients-search {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .clients-search input {
        min-width: 230px;
        padding: 11px 13px;
        border: 1px solid #ccd8e0;
        border-radius: 8px;
        background: var(--surface, #ffffff);
        color: var(--ink, #16354a);
    }

    .clients-button {
        padding: 11px 17px;
        background: #106d9f;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
    }

    .clients-button:hover {
        background: #085078;
    }

    .clients-reset {
        align-self: center;
        color: #1377ae;
        text-decoration: none;
    }

    .clients-scroll {
        overflow-x: auto;
    }

    .clients-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 650px;
    }

    .clients-table th {
        text-align: left;
        padding: 15px 20px;
        font-size: 12px;
        text-transform: uppercase;
        color: var(--slate, #64748b);
        background: rgba(128, 155, 172, .08);
    }

    .clients-table td {
        padding: 18px 20px;
        border-top: 1px solid #e8eef2;
    }

    .clients-name {
        font-weight: 700;
    }

    .clients-muted {
        color: var(--slate, #64748b);
        font-size: 13px;
        margin-top: 4px;
    }

    .clients-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        background: #e5f7ed;
        color: #177344;
        font-size: 12px;
        font-weight: 700;
    }

    .clients-link {
        color: #1377ae;
        font-weight: 700;
        text-decoration: none;
    }

    .clients-link:hover {
        text-decoration: underline;
    }

    .clients-empty {
        padding: 40px 20px;
        text-align: center;
        color: var(--slate, #64748b);
    }

    .clients-pages {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        padding: 22px;
    }

    .clients-pages a {
        color: #1377ae;
        text-decoration: none;
        font-weight: 700;
    }

    .clients-pages span {
        color: var(--slate, #64748b);
    }
</style>
@endpush

@section('content')

<main class="clients-page">

    <header class="clients-header">
        <h1>Gestión de clientes</h1>

        <p>
            Consulta los usuarios registrados
            en World Travel.
        </p>
    </header>

    <!-- TOTAL DE CLIENTES -->
    <section class="clients-summary">

        <div>
            <span>Clientes registrados</span>
            <strong>{{ $totalClientes }}</strong>
        </div>

    </section>

    <!-- LISTADO -->
    <section class="clients-panel">

        <div class="clients-toolbar">

            <h2>Lista de clientes</h2>

            <!-- BÚSQUEDA REAL -->
            <form
                class="clients-search"
                method="GET"
                action="{{ route('admin.clientes.index') }}"
            >
                <input
                    type="search"
                    name="q"
                    value="{{ $busqueda }}"
                    placeholder="Buscar nombre o correo"
                    aria-label="Buscar clientes"
                >

                <button
                    class="clients-button"
                    type="submit"
                >
                    Buscar
                </button>

                @if ($busqueda !== '')
                    <a
                        class="clients-reset"
                        href="{{ route('admin.clientes.index') }}"
                    >
                        Limpiar
                    </a>
                @endif
            </form>

        </div>

        <div class="clients-scroll">

            <table class="clients-table">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Correo electrónico</th>
                        <th>Fecha de registro</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($clientes as $cliente)

                        <tr>

                            <td>
                                #{{ $cliente->id }}
                            </td>

                            <td>
                                <div class="clients-name">
                                    {{ $cliente->name }}
                                </div>
                            </td>

                            <td>
                                {{ $cliente->email }}
                            </td>

                            <td>
                                {{ $cliente->created_at?->format('d/m/Y') ?? 'Sin fecha' }}
                            </td>

                            <td>
                                <span class="clients-badge">
                                    Cliente
                                </span>
                            </td>

                            <td>
                                <a
                                    class="clients-link"
                                    href="{{ route('admin.clientes.show', $cliente) }}"
                                >
                                    Ver detalles
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">

                                <div class="clients-empty">

                                    @if ($busqueda !== '')
                                        No se encontraron clientes
                                        con esa búsqueda.
                                    @else
                                        Todavía no hay clientes
                                        registrados.
                                    @endif

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <!-- PAGINACIÓN -->
        @if ($clientes->hasPages())

            <nav
                class="clients-pages"
                aria-label="Paginación de clientes"
            >

                @if ($clientes->previousPageUrl())
                    <a href="{{ $clientes->previousPageUrl() }}">
                        Anterior
                    </a>
                @endif

                <span>
                    Página {{ $clientes->currentPage() }}
                    de {{ $clientes->lastPage() }}
                </span>

                @if ($clientes->nextPageUrl())
                    <a href="{{ $clientes->nextPageUrl() }}">
                        Siguiente
                    </a>
                @endif

            </nav>

        @endif

    </section>

</main>

@endsection
