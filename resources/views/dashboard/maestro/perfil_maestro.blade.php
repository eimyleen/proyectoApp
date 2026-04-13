@extends('layouts.dashboard')

@section('title', 'Mi Perfil - Maestro')
@section('welcome-message', 'Mi Perfil')
@section('subtitle', 'Aquí puedes consultar y editar tu información personal')

@section('back-button')
    <!-- Botón de regresar -->
@endsection

@section('back-url', '/dashboard/maestro')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_maestro.css') }}">
@endpush

@section('content')
    <div class="perfil-container">
        <!-- Foto de perfil -->
        <div class="perfil-section">
            <div class="foto-perfil">
                <div class="avatar-grande">
                    <span class="avatar-iniciales-grande"></span>
                </div>
                <button class="btn-subir-foto">Subir foto</button>
            </div>
        </div>

        <!-- Datos personales -->
        <h3 class="perfil-titulo">
            Datos personales
            <span class="tutor-badge"></span>
        </h3>
        <div class="datos-grid">
            <div class="dato-item">
                <label>Nombre(s)</label>
                <span class="dato-valor">{{ Auth::user()->name }}</span>
            </div>
            <div class="dato-item">
                <label>Apellidos</label>
                <span class="dato-valor">{{ Auth::user()->apellido }}</span>
            </div>
            <div class="dato-item">
                <label>Número de empleado</label>
                <span class="dato-valor">{{ Auth::user()->maestro->num_empleado ?? 'N/A' }}</span>
            </div>
            <div class="dato-item">
                <label>RFC</label>
                <span class="dato-valor">{{ Auth::user()->maestro->rfc ?? 'N/A' }}</span>
            </div>
            <div class="dato-item">
                <label>Edad</label>
                <span class="dato-valor">{{ Auth::user()->maestro->edad ?? 'N/A' }} años</span>
            </div>
            <div class="dato-item">
                <label>Teléfono</label>
                <span class="dato-valor">{{ Auth::user()->maestro->telefono ?? 'Sin registrar' }}</span>
            </div>
            <div class="dato-item">
                <label>Correo institucional</label>
                <span class="dato-valor">{{ Auth::user()->email }}</span>
            </div>
        </div>

        <!-- Carreras que imparte -->
        <h3 class="seccion-titulo">Carreras que imparte</h3>
        <div class="carreras-grid">
            <!-- Las carreras se cargarán dinámicamente desde el backend -->
        </div>

        <!-- Grupo(s) tutorado -->
        <h3 class="seccion-titulo">Grupo(s) tutorado</h3>
        <div class="grupos-grid">
            <!-- Los grupos se cargarán dinámicamente desde el backend -->
        </div>
    </div>
@endsection