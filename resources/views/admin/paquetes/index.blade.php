
<x-layout>
    <x-slot:titulo>
        World Travel | Administración de paquetes
    </x-slot:titulo>

    <x-slot:recursos>
        <style>
            .wt-admin {
                max-width: 1200px;
                margin: 0 auto;
                padding: 50px 24px 80px;
                color: #16354a;
            }

            .wt-admin__top {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 20px;
                flex-wrap: wrap;
                margin-bottom: 30px;
            }

            .wt-admin__label {
                color: #1377ae;
                font-size: 12px;
                font-weight: 800;
                letter-spacing: 1.5px;
                text-transform: uppercase;
            }

            .wt-admin h1 {
                font-size: clamp(26px, 4vw, 38px);
                margin: 8px 0;
            }

            .wt-admin__desc {
                color: #64798a;
                margin: 0;
            }

            .wt-admin__button {
                display: inline-block;
                background: #106d9f;
                color: white !important;
                text-decoration: none;
                padding: 13px 20px;
                border-radius: 10px;
                font-weight: 700;
            }

            .wt-admin__button:hover {
                background: #084e74;
            }

            .wt-admin__card {
                background: white;
                border: 1px solid #e0e9ef;
                border-radius: 15px;
                overflow-x: auto;
                box-shadow: 0 8px 30px rgba(20, 50, 75, .05);
            }

            .wt-admin__table {
                width: 100%;
                border-collapse: collapse;
                min-width: 780px;
            }

            .wt-admin__table th {
                background: #f1f6fa;
                padding: 17px;
                text-align: left;
                font-size: 12px;
                color: #536e80;
                text-transform: uppercase;
            }

            .wt-admin__table td {
                padding: 17px;
                border-top: 1px solid #e8eef2;
                vertical-align: middle;
            }

            .wt-admin__package {
                display: flex;
                align-items: center;
                gap: 13px;
            }

            .wt-admin__image {
                width: 72px;
                height: 55px;
                border-radius: 9px;
                object-fit: cover;
                background: #e8f0f6;
            }

            .wt-admin__name {
                font-weight: 700;
            }

            .wt-admin__sub {
                color: #728595;
                font-size: 12px;
                margin-top: 4px;
            }

            .wt-admin__price {
                font-weight: 800;
                white-space: nowrap;
            }

            .wt-admin__status {
                display: inline-block;
                padding: 7px 11px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 700;
            }

            .wt-admin__status--on {
                background: #e5f7ed;
                color: #177344;
            }

            .wt-admin__status--off {
                background: #f2f2f2;
                color: #697782;
            }

            .wt-admin__edit {
                color: #106d9f;
                font-weight: 700;
                text-decoration: none;
            }

            .wt-admin__edit:hover {
                text-decoration: underline;
            }

            .wt-admin__alert {
                background: #e5f7ed;
                color: #17683f;
                padding: 15px;
                border-radius: 10px;
                margin-bottom: 20px;
            }

            .wt-admin__empty {
                padding: 45px 20px;
                text-align: center;
                color: #64798a;
            }

            .wt-admin__pagination {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 20px;
                margin-top: 25px;
            }

            .wt-admin__pagination a {
                color: #106d9f;
                font-weight: 700;
                text-decoration: none;
            }

            .wt-admin__pagination span {
                color: #60798a;
            }
        </style>
    </x-slot:recursos>

    <section class="wt-admin">

        <div class="wt-admin__top">
            <div>
                <div class="wt-admin__label">
                    Panel de administración
                </div>

                <h1>Gestión de paquetes turísticos</h1>

                <p class="wt-admin__desc">
                    Administra los destinos, precios y servicios
                    disponibles en World Travel.
                </p>
            </div>

            <a href="{{ route('admin.paquetes.create') }}"
               class="wt-admin__button">
                + Nuevo paquete
            </a>
        </div>

        @if (session('success'))
            <div class="wt-admin__alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="wt-admin__card">
            <table class="wt-admin__table">

                <thead>
                    <tr>
                        <th>Paquete turístico</th>
                        <th>Duración</th>
                        <th>Precio USD</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($paquetes as $paquete)
                        <tr>
                            <td>
                                <div class="wt-admin__package">

                                    @if ($paquete->imagen_url)
                                        <img
                                            src="{{ $paquete->imagen_url }}"
                                            alt="{{ $paquete->destino }}"
                                            class="wt-admin__image"
                                        >
                                    @endif

                                    <div>
                                        <div class="wt-admin__name">
                                            {{ $paquete->titulo }}
                                        </div>

                                        <div class="wt-admin__sub">
                                            {{ $paquete->destino }},
                                            {{ $paquete->pais }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                {{ $paquete->duracion_dias }} días
                            </td>

                            <td class="wt-admin__price">
                                US$ {{ number_format(
                                    (float) $paquete->precio,
                                    2,
                                    '.',
                                    ','
                                ) }}
                            </td>

                            <td>
                                @if ($paquete->activo)
                                    <span class="wt-admin__status wt-admin__status--on">
                                        Activo
                                    </span>
                                @else
                                    <span class="wt-admin__status wt-admin__status--off">
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            <td>
                                <a
                                    class="wt-admin__edit"
                                    href="{{ route('admin.paquetes.edit', $paquete) }}">
                                    Editar
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="wt-admin__empty">
                                    Todavía no hay paquetes registrados.
                                    Utiliza «Nuevo paquete» para empezar.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        @if ($paquetes->hasPages())
            <nav class="wt-admin__pagination"
                 aria-label="Paginación de paquetes">

                @if ($paquetes->previousPageUrl())
                    <a href="{{ $paquetes->previousPageUrl() }}">
                        Anterior
                    </a>
                @endif

                <span>
                    Página {{ $paquetes->currentPage() }}
                    de {{ $paquetes->lastPage() }}
                </span>

                @if ($paquetes->nextPageUrl())
                    <a href="{{ $paquetes->nextPageUrl() }}">
                        Siguiente
                    </a>
                @endif

            </nav>
        @endif

    </section>
</x-layout>
