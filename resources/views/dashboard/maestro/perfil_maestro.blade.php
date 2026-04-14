@extends('layouts.dashboard')

@section('title', __('messages.profile_teacher_title'))
@section('welcome-message', __('messages.profile_welcome'))
@section('subtitle', __('messages.profile_subtitle'))

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
                <button class="btn-subir-foto">{{ __('messages.profile_upload_photo') }}</button>
            </div>
        </div>

        <!-- Datos personales -->
        <h3 class="perfil-titulo">
            {{ __('messages.profile_personal_data') }}
            <span class="tutor-badge"></span>
        </h3>
        <div class="datos-grid">
            <div class="dato-item">
                <label>{{ __('messages.profile_names') }}</label>
                <span class="dato-valor">{{ Auth::user()->name }}</span>
            </div>
            <div class="dato-item">
                <label>{{ __('messages.profile_last_names') }}</label>
                <span class="dato-valor">{{ Auth::user()->apellido }}</span>
            </div>
            <div class="dato-item">
                <label>{{ __('messages.profile_employee_num') }}</label>
                <span class="dato-valor">{{ Auth::user()->maestro->num_empleado ?? 'N/A' }}</span>
            </div>
            <div class="dato-item">
                <label>{{ __('messages.profile_rfc') }}</label>
                <span class="dato-valor">{{ Auth::user()->maestro->rfc ?? 'N/A' }}</span>
            </div>
            <div class="dato-item">
                <label>{{ __('messages.profile_age') }}</label>
                <span class="dato-valor">{{ Auth::user()->maestro->edad ?? 'N/A' }} {{ __('messages.profile_years') }}</span>
            </div>
            <div class="dato-item">
                <label><label>{{ __('messages.profile_phone') }}</label></label>
                <span class="dato-valor">{{ Auth::user()->maestro->telefono ??  __('messages.profile_no_phone') }}</span>
            </div>
            <div class="dato-item">
                <label>{{ __('messages.profile_email') }}</label>
                <span class="dato-valor">{{ Auth::user()->email }}</span>
            </div>
        </div>

        <!-- Carreras que imparte -->
        <h3 class="seccion-titulo">{{ __('messages.profile_teaching_careers') }}</h3>
        <div class="carreras-grid">
            <!-- Las carreras se cargarán dinámicamente desde el backend -->
        </div>

        <!-- Grupo(s) tutorado -->
        <h3 class="seccion-titulo">{{ __('messages.profile_tutored_groups') }}</h3>
        <div class="grupos-grid">
            <!-- Los grupos se cargarán dinámicamente desde el backend -->
        </div>
    </div>
@endsection