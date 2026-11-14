
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

            /* ACCIONES: EDITAR Y ELIMINAR */
            .wt-admin__actions {
                display: flex;
                align-items: center;
                gap: 12px;
                flex-wrap: nowrap;
            }

            .wt-admin__edit {
                display: inline-block;
                color: #106d9f;
                font-weight: 700;
                text-decoration: none;
                padding: 8px 4px;
            }

            .wt-admin__edit:hover {
                text-decoration: underline;
            }

            .wt-admin__delete-form {
                margin: 0;
            }

            .wt-admin__delete {
                background: #fff1f0;
                color: #b42318;
                border: 1px solid #f3c5c0;
                border-radius: 8px;
                padding: 9px 13px;
                cursor: pointer;
                font: inherit;
                font-size: 13px;
                font-weight: 700;
                transition: background .2s ease;
            }

            .wt-admin__delete:hover {
                background: #ffe0dc;
            }

            .wt-admin__delete:focus-visible {
                outline: 2px solid #b42318;
                outline-offset: 2px;
            }

            /* MENSAJE DE ÉXITO */
            .wt-admin__alert {
                background: #e5f7ed;
                color: #17683f;
                padding: 15px;
                border-radius: 10px;
                margin-bottom: 20px;
                border: 1px solid #b9e3c7;
            }

            /* MENSAJE DE ERROR */
            .wt-admin__alert--error {
                background: #fff1f0;
                color: #b42318;
                border: 1px solid #f3c5c0;
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

        <!-- CABECERA -->
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

            <!-- CREAR PAQUETE -->
            <a
                href="{{ route('admin.paquetes.create') }}"
                class="wt-admin__button"
            >
                + Nuevo paquete
            </a>
        </div>

        <!-- MENSAJE DE REGISTRO, EDICIÓN O ELIMINACIÓN -->
        @if (session('success'))
            <div class="wt-admin__alert" role="status">
                {{ session('success') }}
            </div>
        @endif

        <!-- MENSAJE SI NO SE PUEDE ELIMINAR -->
        @if (session('error'))
            <div
                class="wt-admin__alert wt-admin__alert--error"
                role="alert"
            >
                {{ session('error') }}
            </div>
        @endif

        <!-- TABLA DE PAQUETES -->
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

                            <!-- PAQUETE E IMAGEN -->
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

                            <!-- DURACIÓN -->
                            <td>
                                {{ $paquete->duracion_dias }} días
                            </td>

                            <!-- PRECIO -->
                            <td class="wt-admin__price">
                                US$ {{ number_format(
                                    (float) $paquete->precio,
                                    2,
                                    '.',
                                    ','
                                ) }}
                            </td>

                            <!-- ESTADO -->
                            <td>

                                @if ($paquete->activo)

                                    <span
                                        class="wt-admin__status
                                               wt-admin__status--on"
                                    >
                                        Activo
                                    </span>

                                @else

                                    <span
                                        class="wt-admin__status
                                               wt-admin__status--off"
                                    >
                                        Inactivo
                                    </span>

                                @endif

                            </td>

                            <!-- BOTONES EDITAR Y ELIMINAR -->
                            <td>

                                <div class="wt-admin__actions">

                                    <!-- UPDATE -->
                                    <a
                                        class="wt-admin__edit"
                                        href="{{ route('admin.paquetes.edit', $paquete) }}"
                                    >
                                        Editar
                                    </a>

                                    <!-- DELETE -->
                                    <form
                                        class="wt-admin__delete-form"
                                        action="{{ route('admin.paquetes.destroy', $paquete) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Estás seguro de eliminar el paquete «{{ $paquete->titulo }}»? Esta acción no se puede deshacer.');"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="wt-admin__delete"
                                            aria-label="Eliminar paquete {{ $paquete->titulo }}"
                                        >
                                            Eliminar
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <!-- CUANDO NO HAY PAQUETES -->
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

        <!-- PAGINACIÓN -->
        @if ($paquetes->hasPages())

            <nav
                class="wt-admin__pagination"
                aria-label="Paginación de paquetes"
            >

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
