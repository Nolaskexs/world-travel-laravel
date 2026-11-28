@extends('admin')

@section('title', 'Programar Nuevo Vuelo')
@section('active', 'vuelos')

@push('styles')
<style>
    .wt-form-page {
        max-width: 950px;
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
        font-size: 14px;
    }
    .wt-form-page h1 {
        font-size: 30px;
        margin: 0 0 10px;
        font-weight: 700;
    }
    .wt-form-intro {
        color: var(--slate, #697d8d);
        margin-bottom: 25px;
        font-size: 14px;
    }
    .wt-form-card {
        background: #fff;
        padding: 30px;
        border: 1px solid #e1eaf0;
        border-radius: 14px;
        box-shadow: 0 8px 30px rgba(20, 50, 75, .05);
    }
    .wt-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }
    .wt-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .wt-field.full {
        grid-column: 1 / -1;
    }
    .wt-field label {
        font-weight: 700;
        font-size: 13px;
        color: #102331;
    }
    .wt-field input,
    .wt-field select {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #cbdbe6;
        border-radius: 8px;
        background: #fff;
        color: #17354a;
        font: inherit;
        font-size: 14px;
        box-sizing: border-box;
    }
    .wt-field input:focus,
    .wt-field select:focus {
        outline: 2px solid #77b9e0;
        border-color: #1377ae;
    }
    .wt-error {
        color: #b42318;
        font-size: 12px;
        margin-top: 4px;
    }
    .wt-checkboxes-row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-top: 8px;
    }
    .wt-checkbox-label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
    }
    .wt-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #edf2f7;
    }
    .wt-btn-cancel {
        padding: 12px 20px;
        border-radius: 8px;
        border: 1px solid #cbdbe6;
        background: #fff;
        color: #486581;
        font-weight: 700;
        text-decoration: none;
        font-size: 14px;
    }
    .wt-btn-submit {
        padding: 12px 24px;
        border-radius: 8px;
        border: none;
        background: #106d9f;
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: background .2s;
    }
    .wt-btn-submit:hover {
        background: #084e74;
    }
</style>
@endpush

@section('content')
<div class="wt-form-page">
    <a href="{{ route('admin.vuelos.index') }}" class="wt-form-back">← Volver al itinerario de vuelos</a>

    <h1>Programar Nuevo Vuelo Comercial</h1>
    <p class="wt-form-intro">Configura una nueva ruta aérea con origen, destino, aerolínea y tarifas desglosadas.</p>

    <div class="wt-form-card">
        <form method="POST" action="{{ route('admin.vuelos.store') }}">
            @csrf

            <div class="wt-form-grid">
                <div class="wt-field">
                    <label for="codigo_vuelo">Código de Vuelo *</label>
                    <input type="text" id="codigo_vuelo" name="codigo_vuelo" value="{{ old('codigo_vuelo') }}" placeholder="Ej: WT-502, LA-810" required>
                    @error('codigo_vuelo')
                        <span class="wt-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="wt-field">
                    <label for="aerolinea">Aerolínea Operadora *</label>
                    <input type="text" id="aerolinea" name="aerolinea" value="{{ old('aerolinea', 'World Travel Airlines') }}" required>
                    @error('aerolinea')
                        <span class="wt-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="wt-field">
                    <label for="origen_id">Ciudad de Origen (Llave Foránea) *</label>
                    <select id="origen_id" name="origen_id" required>
                        <option value="">Selecciona origen</option>
                        @foreach($destinos as $dest)
                            <option value="{{ $dest->id }}" {{ old('origen_id') == $dest->id ? 'selected' : '' }}>
                                {{ $dest->nombre }} ({{ $dest->codigo_iata ?: strtoupper(substr($dest->nombre, 0, 3)) }}) — {{ $dest->pais }}
                            </option>
                        @endforeach
                    </select>
                    @error('origen_id')
                        <span class="wt-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="wt-field">
                    <label for="destino_id">Ciudad de Destino (Llave Foránea) *</label>
                    <select id="destino_id" name="destino_id" required>
                        <option value="">Selecciona destino</option>
                        @foreach($destinos as $dest)
                            <option value="{{ $dest->id }}" {{ old('destino_id') == $dest->id ? 'selected' : '' }}>
                                {{ $dest->nombre }} ({{ $dest->codigo_iata ?: strtoupper(substr($dest->nombre, 0, 3)) }}) — {{ $dest->pais }}
                            </option>
                        @endforeach
                    </select>
                    @error('destino_id')
                        <span class="wt-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="wt-field">
                    <label for="fecha_salida">Fecha y Hora de Salida *</label>
                    <input type="datetime-local" id="fecha_salida" name="fecha_salida" value="{{ old('fecha_salida', now()->addDays(7)->format('Y-m-d\T08:00')) }}" required>
                    @error('fecha_salida')
                        <span class="wt-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="wt-field">
                    <label for="fecha_llegada">Fecha y Hora de Llegada *</label>
                    <input type="datetime-local" id="fecha_llegada" name="fecha_llegada" value="{{ old('fecha_llegada', now()->addDays(7)->format('Y-m-d\T14:30')) }}" required>
                    @error('fecha_llegada')
                        <span class="wt-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="wt-field">
                    <label for="precio_adulto">Tarifa Base Adulto (US$) *</label>
                    <input type="number" step="0.01" id="precio_adulto" name="precio_adulto" value="{{ old('precio_adulto', '250.00') }}" required>
                    @error('precio_adulto')
                        <span class="wt-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="wt-field">
                    <label for="impuestos_tasas">Tasas e Impuestos Aeroportuarios (US$) *</label>
                    <input type="number" step="0.01" id="impuestos_tasas" name="impuestos_tasas" value="{{ old('impuestos_tasas', '55.00') }}" required>
                    @error('impuestos_tasas')
                        <span class="wt-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="wt-field">
                    <label for="asientos_disponibles">Capacidad / Asientos Disponibles *</label>
                    <input type="number" id="asientos_disponibles" name="asientos_disponibles" value="{{ old('asientos_disponibles', 60) }}" min="1" max="500" required>
                    @error('asientos_disponibles')
                        <span class="wt-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="wt-field">
                    <label for="cantidad_escalas">Escalas Técnicas *</label>
                    <select id="cantidad_escalas" name="cantidad_escalas" required>
                        <option value="0" {{ old('cantidad_escalas') == '0' ? 'selected' : '' }}>Directo (0 escalas)</option>
                        <option value="1" {{ old('cantidad_escalas') == '1' ? 'selected' : '' }}>1 Escala</option>
                        <option value="2" {{ old('cantidad_escalas') == '2' ? 'selected' : '' }}>2 o más escalas</option>
                    </select>
                </div>

                <div class="wt-field full">
                    <label for="etiqueta">Etiqueta de Destacado</label>
                    <select id="etiqueta" name="etiqueta">
                        <option value="ninguna">Sin etiqueta especial</option>
                        <option value="el_mas_barato">El más barato</option>
                        <option value="mejor_opcion">Mejor opción</option>
                        <option value="menor_duracion">Menor duración</option>
                    </select>
                </div>

                <div class="wt-field full">
                    <label>Equipaje Incluido y Disponibilidad</label>
                    <div class="wt-checkboxes-row">
                        <label class="wt-checkbox-label">
                            <input type="checkbox" name="incluye_articulo_personal" value="1" checked> Artículo personal
                        </label>
                        <label class="wt-checkbox-label">
                            <input type="checkbox" name="incluye_equipaje_mano" value="1" checked> Equipaje de mano (10kg)
                        </label>
                        <label class="wt-checkbox-label">
                            <input type="checkbox" name="incluye_equipaje_bodega" value="1"> Equipaje en bodega (23kg)
                        </label>
                        <label class="wt-checkbox-label">
                            <input type="checkbox" name="activo" value="1" checked> Vuelo activo para reserva
                        </label>
                    </div>
                </div>
            </div>

            <div class="wt-form-actions">
                <a href="{{ route('admin.vuelos.index') }}" class="wt-btn-cancel">Cancelar</a>
                <button type="submit" class="wt-btn-submit">Programar Vuelo</button>
            </div>
        </form>
    </div>
</div>
@endsection
