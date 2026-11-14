@extends('admin')

@section('title', 'Nuevo Destino')
@section('active', 'destinos')

@push('styles')
<style>
    .wt-form-wrap {
        max-width: 800px;
        margin: 0 auto;
        padding: 35px 25px 70px;
        color: var(--ink, #16354a);
    }
    .wt-form-card {
        background: var(--surface, #fff);
        border: 1px solid #e0e9ef;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 8px 30px rgba(20, 50, 75, .05);
    }
    .wt-form-group {
        margin-bottom: 20px;
    }
    .wt-form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 7px;
        font-size: 14px;
    }
    .wt-input, .wt-textarea {
        width: 100%;
        padding: 11px 14px;
        border: 1px solid #cfdce5;
        border-radius: 8px;
        font-size: 14px;
        box-sizing: border-box;
    }
    .wt-textarea {
        min-height: 110px;
        resize: vertical;
    }
    .wt-error {
        color: #b91c1c;
        font-size: 13px;
        margin-top: 4px;
    }
    .wt-btn-primary {
        background: #106d9f;
        color: #fff;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
    }
    .wt-btn-primary:hover {
        background: #084e74;
    }
    .wt-btn-secondary {
        display: inline-block;
        padding: 12px 20px;
        border: 1px solid #cfdce5;
        border-radius: 8px;
        color: var(--ink, #16354a);
        text-decoration: none;
        font-weight: 600;
        margin-left: 10px;
    }
</style>
@endpush

@section('content')
<main class="wt-form-wrap">
    <div style="margin-bottom: 25px;">
        <a href="{{ route('admin.destinos.index') }}" style="color: #106d9f; text-decoration: none; font-size: 13px; font-weight: 600;">← Volver al listado</a>
        <h1 style="font-size: 28px; font-weight: 700; margin: 8px 0;">Registrar Nuevo Destino</h1>
        <p style="color: var(--slate, #64798a); margin: 0;">Ingresa los datos del nuevo destino turístico.</p>
    </div>

    <div class="wt-form-card">
        <form method="POST" action="{{ route('admin.destinos.store') }}">
            @csrf

            <div class="wt-form-group">
                <label class="wt-form-label" for="nombre">Nombre de la ciudad o destino *</label>
                <input class="wt-input" type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Cusco, Cancún, Roma" required>
                @error('nombre')
                    <p class="wt-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="wt-form-group">
                <label class="wt-form-label" for="pais">País *</label>
                <input class="wt-input" type="text" id="pais" name="pais" value="{{ old('pais') }}" placeholder="Ej: Perú, México, Italia" required>
                @error('pais')
                    <p class="wt-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="wt-form-group">
                <label class="wt-form-label" for="descripcion">Descripción</label>
                <textarea class="wt-textarea" id="descripcion" name="descripcion" placeholder="Resumen de los atractivos turísticos principales...">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <p class="wt-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="wt-form-group">
                <label class="wt-form-label" for="imagen">Nombre o URL de la imagen (opcional)</label>
                <input class="wt-input" type="text" id="imagen" name="imagen" value="{{ old('imagen') }}" placeholder="Ej: cusco.jpg o URL externa">
                @error('imagen')
                    <p class="wt-error">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-top: 30px;">
                <button type="submit" class="wt-btn-primary">Guardar Destino</button>
                <a href="{{ route('admin.destinos.index') }}" class="wt-btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</main>
@endsection
