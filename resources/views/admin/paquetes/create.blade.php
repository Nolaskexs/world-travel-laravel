@extends('admin')

@section('title', 'Nuevo paquete turístico')
@section('active', 'paquetes')

@push('styles')
<style>
            .wt-form-page {
                max-width: 1100px;
                margin: 0 auto;
                padding: 35px 25px 70px;
                color: var(--ink, #17354a);
            }

            .wt-form-back {
                display: inline-block;
                margin-bottom: 20px;
                color: #1377ae;
                font-weight: 700;
                text-decoration: none;
            }

            .wt-form-page h1 {
                font-size: 32px;
                margin: 0 0 10px;
            }

            .wt-form-intro {
                color: var(--slate, #697d8d);
                margin-bottom: 30px;
            }

            .wt-form-card {
                background: var(--surface, #fff);
                padding: 30px;
                border: 1px solid #e1eaf0;
                border-radius: 16px;
                box-shadow: 0 8px 30px rgba(20, 50, 75, .06);
            }

            .wt-form-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 22px;
            }

            .wt-field {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .wt-field.full {
                grid-column: 1 / -1;
            }

            .wt-field label {
                font-weight: 700;
                font-size: 14px;
            }

            .wt-field input,
            .wt-field select,
            .wt-field textarea {
                width: 100%;
                padding: 13px;
                border: 1px solid #cbdbe6;
                border-radius: 9px;
                background: var(--surface, #fff);
                color: var(--ink, #17354a);
                font: inherit;
                box-sizing: border-box;
            }

            .wt-field input:focus,
            .wt-field select:focus,
            .wt-field textarea:focus {
                outline: 2px solid #77b9e0;
                border-color: #1377ae;
            }

            .wt-field textarea {
                min-height: 110px;
                resize: vertical;
            }

            .wt-help {
                font-size: 12px;
                color: var(--slate, #758a99);
            }

            .wt-error {
                color: #b42318;
                font-size: 12px;
            }

            .wt-options {
                display: flex;
                flex-wrap: wrap;
                gap: 15px 25px;
                margin-top: 12px;
            }

            .wt-options label {
                display: flex;
                align-items: center;
                gap: 8px;
                cursor: pointer;
            }

            .wt-actions {
                display: flex;
                justify-content: flex-end;
                gap: 12px;
                margin-top: 30px;
                flex-wrap: wrap;
            }

            .wt-btn {
                display: inline-block;
                border: none;
                border-radius: 10px;
                padding: 13px 22px;
                font: inherit;
                font-weight: 700;
                text-decoration: none;
                cursor: pointer;
            }

            .wt-btn-primary {
                background: #106d9f;
                color: #fff;
            }

            .wt-btn-secondary {
                background: #eaf1f5;
                color: #23485f;
            }

            .wt-validation {
                padding: 16px;
                margin-bottom: 24px;
                background: #fff1f0;
                color: #a52218;
                border-radius: 10px;
            }
            @media (max-width: 650px) {
                .wt-form-grid {
                    grid-template-columns: 1fr;
                }
                .wt-form-card {
                    padding: 20px;
                }
            }
.wt-options input[type="checkbox"] {
    width: auto;
    flex: 0 0 auto;
    accent-color: #106d9f;
}
</style>
@endpush

@section('content')
<main class="wt-form-page">
        <a href="{{ route('admin.paquetes.index') }}"
           class="wt-form-back">
            ← Volver a paquetes
        </a>
        <h1>Registrar paquete turístico</h1>
        <p class="wt-form-intro">
            Completa la información del nuevo paquete
            que se ofrecerá en World Travel.
        </p>
        @if ($errors->any())
            <div class="wt-validation" role="alert">
                <strong>Revisa los datos ingresados.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST"
              action="{{ route('admin.paquetes.store') }}"
              class="wt-form-card">
            @csrf
            <div class="wt-form-grid">
                <!-- Nombre del paquete -->
                <div class="wt-field full">
                    <label for="titulo">Nombre del paquete *</label>
                    <input
                        id="titulo"
                        type="text"
                        name="titulo"
                        maxlength="150"
                        value="{{ old('titulo') }}"
                        placeholder="Ejemplo: Aventura en París"
                        required
                    >
                    @error('titulo')
                        <span class="wt-error">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Ciudad / Destino (Regla 4.5: Llave foránea con lista desplegable) -->
                <div class="wt-field">
                    <label for="destino">Ciudad o destino turístico *</label>
                    @if (isset($destinos) && $destinos->isNotEmpty())
                        <select id="destino" name="destino" required onchange="const p = this.options[this.selectedIndex].dataset.pais; if (p) document.getElementById('pais').value = p;">
                            <option value="">-- Selecciona un destino registrado --</option>
                            @foreach ($destinos as $dest)
                                <option value="{{ $dest->nombre }}" data-pais="{{ $dest->pais }}" {{ old('destino') === $dest->nombre ? 'selected' : '' }}>
                                    {{ $dest->nombre }} ({{ $dest->pais }})
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input
                            id="destino"
                            type="text"
                            name="destino"
                            maxlength="100"
                            value="{{ old('destino') }}"
                            placeholder="Ejemplo: París"
                            required
                        >
                    @endif
                    @error('destino')
                        <span class="wt-error">{{ $message }}</span>
                    @enderror
                </div>
                <!-- País -->
                <div class="wt-field">
                    <label for="pais">País *</label>
                    <input
                        id="pais"
                        type="text"
                        name="pais"
                        maxlength="100"
                        value="{{ old('pais') }}"
                        placeholder="Ejemplo: Francia"
                        required
                    >
                    @error('pais')
                        <span class="wt-error">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Continente -->
                <div class="wt-field">
                    <label for="continente">Continente *</label>
                    <select id="continente"
                            name="continente"
                            required>
                        <option value="">Selecciona un continente</option>
                        @foreach ([
                            'America' => 'América',
                            'Europa' => 'Europa',
                            'Asia' => 'Asia',
                            'Africa' => 'África',
                            'Oceania' => 'Oceanía'
                        ] as $valor => $nombre)
                            <option value="{{ $valor }}"
                                @selected(old('continente') === $valor)>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('continente')
                        <span class="wt-error">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Duración -->
                <div class="wt-field">
                    <label for="duracion_dias">Duración (días) *</label>
                    <input
                        id="duracion_dias"
                        type="number"
                        name="duracion_dias"
                        min="1"
                        max="255"
                        value="{{ old('duracion_dias') }}"
                        placeholder="Ejemplo: 7"
                        required
                    >
                    @error('duracion_dias')
                        <span class="wt-error">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Precio -->
                <div class="wt-field">
                    <label for="precio">Precio en USD *</label>
                    <input
                        id="precio"
                        type="number"
                        name="precio"
                        min="0"
                        max="99999999.99"
                        step="0.01"
                        value="{{ old('precio') }}"
                        placeholder="Ejemplo: 1500.00"
                        required
                    >
                    <span class="wt-help">
                        Todos los precios se registran en dólares.
                    </span>
                    @error('precio')
                        <span class="wt-error">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Imagen -->
                <div class="wt-field">
                    <label for="imagen_url">URL de la imagen</label>
                    <input
                        id="imagen_url"
                        type="url"
                        name="imagen_url"
                        maxlength="500"
                        value="{{ old('imagen_url') }}"
                        placeholder="https://ejemplo.com/imagen.jpg"
                    >
                    @error('imagen_url')
                        <span class="wt-error">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Descripción -->
                <div class="wt-field full">
                    <label for="descripcion">Descripción *</label>
                    <textarea
                        id="descripcion"
                        name="descripcion"
                        maxlength="5000"
                        placeholder="Describe los atractivos del paquete..."
                        required
                    >{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <span class="wt-error">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Servicios incluidos -->
                <div class="wt-field full">
                    <label>Servicios y características</label>
                    <div class="wt-options">
                        <label>
                            <input type="checkbox"
                                   name="incluye_vuelo"
                                   value="1"
                                   @checked(old('incluye_vuelo', false))>
                            Incluye vuelo
                        </label>
                        <label>
                            <input type="checkbox"
                                   name="incluye_hotel"
                                   value="1"
                                   @checked(old('incluye_hotel', true))>
                            Incluye hotel
                        </label>
                        <label>
                            <input type="checkbox"
                                   name="incluye_tour"
                                   value="1"
                                   @checked(old('incluye_tour', true))>
                            Incluye tours
                        </label>
                        <label>
                            <input type="checkbox"
                                   name="destacado"
                                   value="1"
                                   @checked(old('destacado', false))>
                            Paquete destacado
                        </label>
                        <label>
                            <input type="checkbox"
                                   name="activo"
                                   value="1"
                                   @checked(old('activo', true))>
                            Paquete activo
                        </label>
                    </div>
                    <span class="wt-help">
                        Los paquetes inactivos no deberían mostrarse
                        en el catálogo público.
                    </span>
                </div>
            </div>
            <div class="wt-actions">
                <a href="{{ route('admin.paquetes.index') }}"
                   class="wt-btn wt-btn-secondary">
                    Cancelar
                </a>
                <button type="submit"
                        class="wt-btn wt-btn-primary">
                    Guardar paquete
                </button>
            </div>
        </form>
    </main>
@endsection
