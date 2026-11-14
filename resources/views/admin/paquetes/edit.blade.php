
<x-layout>
    <x-slot:titulo>
        World Travel | Editar paquete
    </x-slot:titulo>

    <x-slot:recursos>
        <style>
            .wt-edit-page {
                max-width: 1000px;
                margin: 0 auto;
                padding: 45px 22px 80px;
                color: #17354a;
            }

            .wt-edit-back {
                display: inline-block;
                margin-bottom: 20px;
                color: #1377ae;
                font-weight: 700;
                text-decoration: none;
            }

            .wt-edit-page h1 {
                font-size: 32px;
                margin: 0 0 10px;
            }

            .wt-edit-intro {
                color: #697d8d;
                margin-bottom: 30px;
            }

            .wt-edit-card {
                background: #fff;
                padding: 30px;
                border: 1px solid #e1eaf0;
                border-radius: 16px;
                box-shadow: 0 8px 30px rgba(20, 50, 75, .06);
            }

            .wt-edit-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 22px;
            }

            .wt-edit-field {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .wt-edit-field.full {
                grid-column: 1 / -1;
            }

            .wt-edit-field label {
                font-size: 14px;
                font-weight: 700;
            }

            .wt-edit-field input,
            .wt-edit-field select,
            .wt-edit-field textarea {
                width: 100%;
                box-sizing: border-box;
                padding: 13px;
                border: 1px solid #cbdbe6;
                border-radius: 9px;
                background: #fff;
                color: #17354a;
                font: inherit;
            }

            .wt-edit-field input:focus,
            .wt-edit-field select:focus,
            .wt-edit-field textarea:focus {
                outline: 2px solid #77b9e0;
                border-color: #1377ae;
            }

            .wt-edit-field textarea {
                min-height: 120px;
                resize: vertical;
            }

            .wt-edit-error {
                color: #b42318;
                font-size: 12px;
            }

            .wt-edit-options {
                display: flex;
                flex-wrap: wrap;
                gap: 16px 25px;
                margin-top: 10px;
            }

            .wt-edit-options label {
                display: flex;
                align-items: center;
                gap: 8px;
                cursor: pointer;
            }

            .wt-edit-options input {
                width: auto;
            }

            .wt-edit-actions {
                display: flex;
                justify-content: flex-end;
                gap: 12px;
                margin-top: 30px;
                flex-wrap: wrap;
            }

            .wt-edit-btn {
                display: inline-block;
                padding: 13px 22px;
                border: none;
                border-radius: 10px;
                font: inherit;
                font-weight: 700;
                text-decoration: none;
                cursor: pointer;
            }

            .wt-edit-btn-primary {
                background: #106d9f;
                color: white;
            }

            .wt-edit-btn-primary:hover {
                background: #084e74;
            }

            .wt-edit-btn-secondary {
                background: #eaf1f5;
                color: #23485f;
            }

            .wt-edit-alert {
                background: #fff1f0;
                color: #a52218;
                border-radius: 10px;
                padding: 15px;
                margin-bottom: 24px;
            }

            .wt-edit-help {
                font-size: 12px;
                color: #758a99;
            }

            @media (max-width: 650px) {
                .wt-edit-grid {
                    grid-template-columns: 1fr;
                }

                .wt-edit-card {
                    padding: 20px;
                }
            }
        </style>
    </x-slot:recursos>

    <main class="wt-edit-page">

        <a href="{{ route('admin.paquetes.index') }}"
           class="wt-edit-back">
            ← Volver al listado
        </a>

        <h1>Editar paquete turístico</h1>

        <p class="wt-edit-intro">
            Actualiza la información de
            <strong>{{ $paquete->titulo }}</strong>.
            Los cambios se guardarán en MySQL.
        </p>

        @if ($errors->any())
            <div class="wt-edit-alert" role="alert">
                <strong>Corrige los siguientes errores:</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('admin.paquetes.update', $paquete) }}"
            method="POST"
            class="wt-edit-card"
        >
            @csrf
            @method('PUT')

            <div class="wt-edit-grid">

                <!-- Nombre del paquete -->
                <div class="wt-edit-field full">
                    <label for="titulo">
                        Nombre del paquete *
                    </label>

                    <input
                        type="text"
                        id="titulo"
                        name="titulo"
                        maxlength="150"
                        value="{{ old('titulo', $paquete->titulo) }}"
                        required
                    >

                    @error('titulo')
                        <span class="wt-edit-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Destino -->
                <div class="wt-edit-field">
                    <label for="destino">
                        Ciudad o destino *
                    </label>

                    <input
                        type="text"
                        id="destino"
                        name="destino"
                        maxlength="100"
                        value="{{ old('destino', $paquete->destino) }}"
                        required
                    >

                    @error('destino')
                        <span class="wt-edit-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- País -->
                <div class="wt-edit-field">
                    <label for="pais">
                        País *
                    </label>

                    <input
                        type="text"
                        id="pais"
                        name="pais"
                        maxlength="100"
                        value="{{ old('pais', $paquete->pais) }}"
                        required
                    >

                    @error('pais')
                        <span class="wt-edit-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Continente -->
                <div class="wt-edit-field">
                    <label for="continente">
                        Continente *
                    </label>

                    <select
                        id="continente"
                        name="continente"
                        required
                    >
                        @foreach ([
                            'America' => 'América',
                            'Europa' => 'Europa',
                            'Asia' => 'Asia',
                            'Africa' => 'África',
                            'Oceania' => 'Oceanía'
                        ] as $valor => $nombre)

                            <option
                                value="{{ $valor }}"
                                @selected(
                                    old('continente', $paquete->continente)
                                    === $valor
                                )
                            >
                                {{ $nombre }}
                            </option>

                        @endforeach
                    </select>

                    @error('continente')
                        <span class="wt-edit-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Duración -->
                <div class="wt-edit-field">
                    <label for="duracion_dias">
                        Duración en días *
                    </label>

                    <input
                        type="number"
                        id="duracion_dias"
                        name="duracion_dias"
                        min="1"
                        max="255"
                        value="{{ old('duracion_dias', $paquete->duracion_dias) }}"
                        required
                    >

                    @error('duracion_dias')
                        <span class="wt-edit-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Precio -->
                <div class="wt-edit-field">
                    <label for="precio">
                        Precio en USD *
                    </label>

                    <input
                        type="number"
                        id="precio"
                        name="precio"
                        min="0"
                        max="99999999.99"
                        step="0.01"
                        value="{{ old('precio', $paquete->precio) }}"
                        required
                    >

                    <span class="wt-edit-help">
                        La moneda utilizada es USD.
                    </span>

                    @error('precio')
                        <span class="wt-edit-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Imagen -->
                <div class="wt-edit-field">
                    <label for="imagen_url">
                        URL de imagen
                    </label>

                    <input
                        type="url"
                        id="imagen_url"
                        name="imagen_url"
                        maxlength="500"
                        value="{{ old('imagen_url', $paquete->imagen_url) }}"
                        placeholder="https://ejemplo.com/destino.jpg"
                    >

                    @error('imagen_url')
                        <span class="wt-edit-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="wt-edit-field full">
                    <label for="descripcion">
                        Descripción del paquete *
                    </label>

                    <textarea
                        id="descripcion"
                        name="descripcion"
                        maxlength="5000"
                        required
                    >{{ old('descripcion', $paquete->descripcion) }}</textarea>

                    @error('descripcion')
                        <span class="wt-edit-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Servicios y características -->
                <div class="wt-edit-field full">
                    <label>Servicios incluidos</label>

                    <div class="wt-edit-options">

                        <label>
                            <input type="hidden" name="incluye_vuelo" value="0">
                            <input
                                type="checkbox"
                                name="incluye_vuelo"
                                value="1"
                                @checked(old('incluye_vuelo', $paquete->incluye_vuelo))
                            >
                            Incluye vuelo
                        </label>

                        <label>
                            <input type="hidden" name="incluye_hotel" value="0">
                            <input
                                type="checkbox"
                                name="incluye_hotel"
                                value="1"
                                @checked(old('incluye_hotel', $paquete->incluye_hotel))
                            >
                            Incluye hotel
                        </label>

                        <label>
                            <input type="hidden" name="incluye_tour" value="0">
                            <input
                                type="checkbox"
                                name="incluye_tour"
                                value="1"
                                @checked(old('incluye_tour', $paquete->incluye_tour))
                            >
                            Incluye tours
                        </label>

                        <label>
                            <input type="hidden" name="destacado" value="0">
                            <input
                                type="checkbox"
                                name="destacado"
                                value="1"
                                @checked(old('destacado', $paquete->destacado))
                            >
                            Destacado
                        </label>

                        <label>
                            <input type="hidden" name="activo" value="0">
                            <input
                                type="checkbox"
                                name="activo"
                                value="1"
                                @checked(old('activo', $paquete->activo))
                            >
                            Paquete activo
                        </label>

                    </div>

                    <span class="wt-edit-help">
                        Desmarca «Paquete activo» si deseas
                        ocultarlo del catálogo público.
                    </span>
                </div>

            </div>

            <div class="wt-edit-actions">
                <a
                    href="{{ route('admin.paquetes.index') }}"
                    class="wt-edit-btn wt-edit-btn-secondary"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="wt-edit-btn wt-edit-btn-primary"
                >
                    Guardar cambios
                </button>
            </div>

        </form>

    </main>
</x-layout>
