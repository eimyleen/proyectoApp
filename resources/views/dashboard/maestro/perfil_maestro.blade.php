@extends('layouts.dashboard')

@section('title', 'Mi Perfil - Maestro')
@section('user-role', 'Maestro')
@section('avatar-iniciales', 'CS')
@section('nombre-completo', 'Carlos Sánchez')
@section('welcome-message', 'Mi Perfil')
@section('subtitle', 'Aquí puedes consultar y editar tu información personal')

@section('back-button')
    <!-- Activa el botón de regreso -->
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
                <label>Nombre</label>
                <span class="dato-valor"></span>
            </div>
            <div class="dato-item">
                <label>Apellidos</label>
                <span class="dato-valor"></span>
            </div>
            <div class="dato-item">
                <label>Número de empleado</label>
                <span class="dato-valor"></span>
            </div>
            <div class="dato-item">
                <label>RFC</label>
                <span class="dato-valor"></span>
            </div>
            <div class="dato-item">
                <label>Edad</label>
                <span class="dato-valor"></span>
            </div>
            <div class="dato-item">
                <label>Sexo</label>
                <span class="dato-valor"></span>
            </div>
            <div class="dato-item">
                <label>Fecha de nacimiento</label>
                <span class="dato-valor"></span>
            </div>
            <div class="dato-item">
                <label>Correo electrónico</label>
                <span class="dato-valor"></span>
            </div>
            <div class="dato-item">
                <label>Teléfono</label>
                <span class="dato-valor"></span>
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