@extends('admin')

@section('title', 'Nuevo Alojamiento')
@section('active', 'alojamientos')

@push('styles')
<style>
    .wt-form-wrap {
        max-width: 900px;
        margin: 0 auto;
        padding: 35px 25px 70px;
        color: var(--ink, #16354a);
    }
    .wt-form-top {
        margin-bottom: 25px;
    }
    .wt-form-label-tag {
        color: #1377ae;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }
    .wt-form-top h1 {
        font-size: clamp(24px, 4vw, 32px);
        margin: 6px 0;
        font-weight: 700;
    }
    .wt-form-card {
        background: #fff;
        border: 1px solid #e0e9ef;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 8px 30px rgba(20, 50, 75, .05);
    }
    .wt-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .wt-form-group {
        margin-bottom: 20px;
    }
    .wt-form-group--full {
        grid-column: 1 / -1;
    }
    .wt-form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 7px;
        font-size: 14px;
        color: #1e293b;
    }
    .wt-input, .wt-select, .wt-textarea {
        width: 100%;
        padding: 11px 14px;
        border: 1px solid #cfdce5;
        border-radius: 8px;
        font-size: 14px;
        box-sizing: border-box;
        font-family: inherit;
    }
    .wt-textarea {
        min-height: 100px;
        resize: vertical;
    }
    .wt-error {
        color: #b91c1c;
        font-size: 13px;
        margin-top: 5px;
    }
    .wt-checkbox-label {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }
    .wt-btn-primary {
        background: #106d9f;
        color: #fff;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        font-size: 14px;
        transition: background .2s ease;
    }
    .wt-btn-primary:hover {
        background: #084e74;
    }
    .wt-btn-secondary {
        display: inline-block;
        padding: 12px 20px;
        border: 1px solid #cfdce5;
        border-radius: 8px;
        color: #475569;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        margin-left: 10px;
    }
    .wt-btn-secondary:hover {
        background: #f1f5f9;
    }
</style>
@endpush

@section('content')
<div class="wt-form-wrap">
    <div class="wt-form-top">
        <span class="wt-form-label-tag">Panel Administrador</span>
        <h1>Registrar Nuevo Alojamiento</h1>
        <p style="color: #64748b; font-size: 14px; margin: 0;">
            Los datos ingresados se almacenarán directamente en la tabla <code>alojamientos</code> de MySQL.
        </p>
    </div>

    <div class="wt-form-card">
        <form method="POST" action="{{ route('admin.alojamientos.store') }}">
            @csrf

            <div class="wt-form-grid">
                <!-- NOMBRE -->
                <div class="wt-form-group">
                    <label class="wt-form-label" for="nombre">Nombre del alojamiento *</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" class="wt-input" placeholder="Ej: Belmond Hotel Miraflores" required>
                    @error('nombre') <div class="wt-error">{{ $message }}</div> @enderror
                </div>

                <!-- CÓDIGO SLUG -->
                <div class="wt-form-group">
                    <label class="wt-form-label" for="codigo">Código slug (opcional)</label>
                    <input type="text" id="codigo" name="codigo" value="{{ old('codigo') }}" class="wt-input" placeholder="Ej: belmond-miraflores (se autogenera si se deja vacío)">
                    @error('codigo') <div class="wt-error">{{ $message }}</div> @enderror
                </div>

                <!-- DESTINO ASOCIADO -->
                <div class="wt-form-group">
                    <label class="wt-form-label" for="destino_id">Destino (FK)</label>
                    <select id="destino_id" name="destino_id" class="wt-select">
                        <option value="">Selecciona un destino...</option>
                        @foreach($destinos as $dest)
                            <option value="{{ $dest->id }}" {{ old('destino_id') == $dest->id ? 'selected' : '' }}>
                                {{ $dest->nombre }} ({{ $dest->pais }})
                            </option>
                        @endforeach
                    </select>
                    @error('destino_id') <div class="wt-error">{{ $message }}</div> @enderror
                </div>

                <!-- TIPO -->
                <div class="wt-form-group">
                    <label class="wt-form-label" for="tipo">Tipo de alojamiento *</label>
                    <select id="tipo" name="tipo" class="wt-select" required>
                        <option value="hotel" {{ old('tipo') === 'hotel' ? 'selected' : '' }}>Hotel</option>
                        <option value="resort" {{ old('tipo') === 'resort' ? 'selected' : '' }}>Resort</option>
                        <option value="hostal" {{ old('tipo') === 'hostal' ? 'selected' : '' }}>Hostal</option>
                        <option value="boutique" {{ old('tipo') === 'boutique' ? 'selected' : '' }}>Boutique</option>
                    </select>
                    @error('tipo') <div class="wt-error">{{ $message }}</div> @enderror
                </div>

                <!-- PRECIO POR NOCHE -->
                <div class="wt-form-group">
                    <label class="wt-form-label" for="precio_noche">Precio por noche *</label>
                    <input type="number" step="0.01" min="0" id="precio_noche" name="precio_noche" value="{{ old('precio_noche', '150.00') }}" class="wt-input" required>
                    @error('precio_noche') <div class="wt-error">{{ $message }}</div> @enderror
                </div>

                <!-- MONEDA -->
                <div class="wt-form-group">
                    <label class="wt-form-label" for="moneda">Moneda *</label>
                    <select id="moneda" name="moneda" class="wt-select" required>
                        <option value="USD" {{ old('moneda', 'USD') === 'USD' ? 'selected' : '' }}>Dólares (US$)</option>
                        <option value="PEN" {{ old('moneda') === 'PEN' ? 'selected' : '' }}>Soles (S/)</option>
                    </select>
                    @error('moneda') <div class="wt-error">{{ $message }}</div> @enderror
                </div>

                <!-- PRECIO HABITUAL / ANTERIOR -->
                <div class="wt-form-group">
                    <label class="wt-form-label" for="precio_anterior">Precio habitual tachado (opcional)</label>
                    <input type="number" step="0.01" min="0" id="precio_anterior" name="precio_anterior" value="{{ old('precio_anterior') }}" class="wt-input" placeholder="Ej: 190.00">
                    @error('precio_anterior') <div class="wt-error">{{ $message }}</div> @enderror
                </div>

                <!-- ESTRELLAS -->
                <div class="wt-form-group">
                    <label class="wt-form-label" for="estrellas">Estrellas (1 a 5) *</label>
                    <select id="estrellas" name="estrellas" class="wt-select" required>
                        <option value="5" {{ old('estrellas', '5') == 5 ? 'selected' : '' }}>5 estrellas</option>
                        <option value="4" {{ old('estrellas') == 4 ? 'selected' : '' }}>4 estrellas</option>
                        <option value="3" {{ old('estrellas') == 3 ? 'selected' : '' }}>3 estrellas</option>
                        <option value="2" {{ old('estrellas') == 2 ? 'selected' : '' }}>2 estrellas</option>
                        <option value="1" {{ old('estrellas') == 1 ? 'selected' : '' }}>1 estrella</option>
                    </select>
                    @error('estrellas') <div class="wt-error">{{ $message }}</div> @enderror
                </div>

                <!-- PUNTUACIÓN -->
                <div class="wt-form-group">
                    <label class="wt-form-label" for="puntuacion">Puntuación (ej: 4.8)</label>
                    <input type="number" step="0.1" min="1" max="5" id="puntuacion" name="puntuacion" value="{{ old('puntuacion', '4.7') }}" class="wt-input">
                    @error('puntuacion') <div class="wt-error">{{ $message }}</div> @enderror
                </div>

                <!-- TIPO HABITACIÓN -->
                <div class="wt-form-group">
                    <label class="wt-form-label" for="habitacion">Tipo de habitación</label>
                    <input type="text" id="habitacion" name="habitacion" value="{{ old('habitacion', 'Habitación Estándar') }}" class="wt-input" placeholder="Ej: Deluxe King Suite">
                    @error('habitacion') <div class="wt-error">{{ $message }}</div> @enderror
                </div>

                <!-- ESTADÍA REFERENCIAL -->
                <div class="wt-form-group">
                    <label class="wt-form-label" for="estadia">Estadía de referencia</label>
                    <input type="text" id="estadia" name="estadia" value="{{ old('estadia', '1 noche, 2 personas') }}" class="wt-input">
                    @error('estadia') <div class="wt-error">{{ $message }}</div> @enderror
                </div>

                <!-- DIRECCIÓN / UBICACIÓN -->
                <div class="wt-form-group">
                    <label class="wt-form-label" for="direccion">Dirección / Ubicación detallada</label>
                    <input type="text" id="direccion" name="direccion" value="{{ old('direccion') }}" class="wt-input" placeholder="Ej: Av. Larco 1234, Miraflores, Lima">
                    @error('direccion') <div class="wt-error">{{ $message }}</div> @enderror
                </div>

                <!-- IMAGEN -->
                <div class="wt-form-group wt-form-group--full">
                    <label class="wt-form-label" for="imagen">Ruta de imagen o URL web</label>
                    <input type="text" id="imagen" name="imagen" value="{{ old('imagen', 'icons/hotel1.jpg') }}" class="wt-input" placeholder="icons/hotel1.jpg o https://images.unsplash.com/...">
                    <small style="color: #64748b; font-size: 12px; display: block; margin-top: 4px;">
                        Puede ser una ruta local como <code>icons/hotel1.jpg</code> o un enlace web completo (HTTPS).
                    </small>
                    @error('imagen') <div class="wt-error">{{ $message }}</div> @enderror
                </div>

                <!-- DESCRIPCIÓN -->
                <div class="wt-form-group wt-form-group--full">
                    <label class="wt-form-label" for="descripcion">Descripción del alojamiento</label>
                    <textarea id="descripcion" name="descripcion" class="wt-textarea" placeholder="Describe el alojamiento, amenidades y entorno...">{{ old('descripcion') }}</textarea>
                    @error('descripcion') <div class="wt-error">{{ $message }}</div> @enderror
                </div>

                <!-- SERVICIOS INCLUIDOS -->
                <div class="wt-form-group wt-form-group--full">
                    <label class="wt-form-label" for="servicios_texto">Servicios destacados (uno por línea o separados por coma)</label>
                    <textarea id="servicios_texto" name="servicios_texto" class="wt-textarea" style="min-height: 80px;" placeholder="Wi-Fi de alta velocidad&#10;Desayuno buffet incluido&#10;Piscina climatizada&#10;Gimnasio 24/7">{{ old('servicios_texto') }}</textarea>
                    @error('servicios_texto') <div class="wt-error">{{ $message }}</div> @enderror
                </div>

                <!-- ACTIVO -->
                <div class="wt-form-group wt-form-group--full">
                    <label class="wt-checkbox-label">
                        <input type="checkbox" name="activo" value="1" {{ old('activo', '1') ? 'checked' : '' }}>
                        <span>Alojamiento activo (visible para los clientes y en el buscador)</span>
                    </label>
                </div>
            </div>

            <div style="margin-top: 25px; display: flex; align-items: center;">
                <button type="submit" class="wt-btn-primary">Guardar Alojamiento</button>
                <a href="{{ route('admin.alojamientos.index') }}" class="wt-btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
