@extends('admin')

@section('title', 'Ajustes del Sistema')
@section('active', 'ajustes')

@push('styles')
<style>
    .wt-admin {
        max-width: 1000px;
        margin: 0 auto;
        padding: 35px 25px 70px;
        color: var(--ink, #16354a);
    }

    .wt-admin__top {
        margin-bottom: 25px;
    }

    .wt-admin__label {
        color: #1377ae;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .wt-admin h1 {
        font-size: clamp(24px, 3.5vw, 32px);
        margin: 6px 0;
        font-weight: 700;
    }

    .wt-admin__desc {
        color: var(--slate, #64798a);
        margin: 0;
        line-height: 1.5;
    }

    .wt-card {
        background: var(--surface, #fff);
        border: 1px solid #e0e9ef;
        border-radius: 15px;
        padding: 28px;
        margin-bottom: 25px;
        box-shadow: 0 8px 30px rgba(20, 50, 75, .04);
    }

    .wt-card__title {
        font-size: 18px;
        font-weight: 700;
        margin: 0 0 18px;
        color: #106d9f;
        border-bottom: 1px solid #eef3f6;
        padding-bottom: 12px;
    }

    .wt-grid-2 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 18px;
    }

    .wt-form-group {
        margin-bottom: 16px;
    }

    .wt-label {
        display: block;
        font-weight: 600;
        font-size: 13px;
        margin-bottom: 6px;
        color: var(--ink, #16354a);
    }

    .wt-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cfdce5;
        border-radius: 8px;
        font-size: 14px;
        box-sizing: border-box;
    }

    .wt-info-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 12px;
    }

    .wt-info-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 12px 14px;
        border-radius: 8px;
    }

    .wt-info-item span {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 700;
    }

    .wt-info-item strong {
        font-size: 14px;
        color: #0f172a;
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

    .wt-alert {
        padding: 12px 18px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
</style>
@endpush

@section('content')
<main class="wt-admin">

    <div class="wt-admin__top">
        <span class="wt-admin__label">Panel Administrativo</span>
        <h1>Ajustes del Sistema</h1>
        <p class="wt-admin__desc">Configuración general de la agencia World Travel y detalles del entorno de ejecución.</p>
    </div>

    @if (session('success'))
        <div class="wt-alert">{{ session('success') }}</div>
    @endif

    <section class="wt-card">
        <h2 class="wt-card__title">Información de la Agencia de Viajes</h2>

        <form method="POST" action="{{ route('admin.ajustes.update') }}">
            @csrf
            @method('PUT')

            <div class="wt-grid-2">
                <div class="wt-form-group">
                    <label class="wt-label" for="nombre">Razón Social / Nombre Comercial *</label>
                    <input class="wt-input" type="text" id="nombre" name="nombre" value="{{ old('nombre', $agencia['nombre']) }}" required>
                </div>

                <div class="wt-form-group">
                    <label class="wt-label" for="ruc">RUC / Identificación Fiscal *</label>
                    <input class="wt-input" type="text" id="ruc" name="ruc" value="{{ old('ruc', $agencia['ruc']) }}" required>
                </div>

                <div class="wt-form-group">
                    <label class="wt-label" for="email_contacto">Correo de Contacto y Notificaciones *</label>
                    <input class="wt-input" type="email" id="email_contacto" name="email_contacto" value="{{ old('email_contacto', $agencia['email_contacto']) }}" required>
                </div>

                <div class="wt-form-group">
                    <label class="wt-label" for="telefono">Teléfono / WhatsApp de Soporte *</label>
                    <input class="wt-input" type="text" id="telefono" name="telefono" value="{{ old('telefono', $agencia['telefono']) }}" required>
                </div>

                <div class="wt-form-group" style="grid-column: 1 / -1;">
                    <label class="wt-label" for="direccion">Dirección Comercial *</label>
                    <input class="wt-input" type="text" id="direccion" name="direccion" value="{{ old('direccion', $agencia['direccion']) }}" required>
                </div>

                <div class="wt-form-group">
                    <label class="wt-label" for="moneda_principal">Moneda del Sistema</label>
                    <input class="wt-input" type="text" id="moneda_principal" value="{{ $agencia['moneda_principal'] }}" disabled style="background: #f1f5f8;">
                </div>

                <div class="wt-form-group">
                    <label class="wt-label" for="tasa_igv">Tasa Impuesto / IGV (%) *</label>
                    <input class="wt-input" type="number" step="0.01" id="tasa_igv" name="tasa_igv" value="{{ old('tasa_igv', $agencia['tasa_igv']) }}" required>
                </div>
            </div>

            <div style="margin-top: 15px;">
                <button type="submit" class="wt-btn-primary">Guardar Ajustes</button>
            </div>
        </form>
    </section>

    <section class="wt-card">
        <h2 class="wt-card__title">Información del Servidor y Entorno</h2>

        <div class="wt-info-list">
            <div class="wt-info-item">
                <span>Versión PHP</span>
                <strong>{{ $sistema['php_version'] }}</strong>
            </div>
            <div class="wt-info-item">
                <span>Laravel Framework</span>
                <strong>{{ $sistema['laravel_version'] }}</strong>
            </div>
            <div class="wt-info-item">
                <span>Entorno (APP_ENV)</span>
                <strong>{{ $sistema['app_env'] }}</strong>
            </div>
            <div class="wt-info-item">
                <span>Base de Datos</span>
                <strong>{{ $sistema['db_connection'] }} ({{ $sistema['db_name'] }})</strong>
            </div>
            <div class="wt-info-item">
                <span>Zona Horaria</span>
                <strong>{{ $sistema['app_timezone'] }}</strong>
            </div>
            <div class="wt-info-item">
                <span>Sesiones</span>
                <strong>{{ $sistema['session_driver'] }}</strong>
            </div>
        </div>
    </section>

    <section class="wt-card">
        <h2 class="wt-card__title">Sesión del Administrador</h2>
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="background: #106d9f; color: #fff; width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 18px;">
                {{ Str::upper(Str::substr($admin->name ?? 'A', 0, 1)) }}
            </div>
            <div>
                <strong>{{ $admin->name ?? 'Administrador' }}</strong>
                <div style="color: #64748b; font-size: 13px;">{{ $admin->email ?? '' }} · Rol: {{ ucfirst($admin->rol ?? 'admin') }}</div>
            </div>
        </div>
    </section>

</main>
@endsection
