@extends('layouts.dashboard')

@section('title', 'Panel Maestro')
@section('user-role', 'Maestro')
@section('avatar-iniciales', 'CS')
@section('nombre-completo', 'Carlos Sánchez')
@section('welcome-message', '¡Bienvenido, Carlos!')
@section('subtitle', 'Selecciona una carrera para gestionar sus grupos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_maestro.css') }}">
@endpush

@section('content')
    <!-- Botones superiores -->
    <div class="maestro-buttons">
        <button class="btn-lista-global">
            <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon">
            Descargar lista global
        </button>
    </div>

    <!-- Carreras -->
    <div class="carreras-container">
        <div class="carreras-grid">
            <!-- Ingeniería en Alimentos -->
            <div class="carrera-card">
                <div class="carrera-img">
                    <img src="{{ asset('img/carreras/ing_alimentos.png') }}" alt="Ingeniería en Alimentos">
                </div>
            </div>

            <div class="carrera-card">
                <div class="carrera-img">
                    <img src="{{ asset('img/carreras/ing_civil.png') }}" alt="Ingeniería Civil">
                </div>
            </div>

            <div class="carrera-card">
                <div class="carrera-img">
                    <img src="{{ asset('img/carreras/ing_inte_artificial.png') }}" alt="Ingeniería Artificial">
                </div>
            </div>

            <div class="carrera-card">
                <div class="carrera-img">
                    <img src="{{ asset('img/carreras/ing_logistica.png') }}" alt="Ingeniería Logística">
                </div>
            </div>

            <div class="carrera-card">
                <div class="carrera-img">
                    <img src="{{ asset('img/carreras/ing_mant_industrial.png') }}" alt="Ingeniería Mantenimiento Industrial">
                </div>
            </div>

            <div class="carrera-card">
                <div class="carrera-img">
                    <img src="{{ asset('img/carreras/ing_mecatronica.png') }}" alt="Ingeniería Mecatrónica">
                </div>
            </div>

            <div class="carrera-card">
                <div class="carrera-img">
                    <img src="{{ asset('img/carreras/ing_micro_semic.png') }}" alt="Ingeniería Micro Semiconductores">
                </div>
            </div>

            <div class="carrera-card">
                <div class="carrera-img">
                    <img src="{{ asset('img/carreras/ing_tec_info.png') }}" alt="Ingeniería Tecnologías Información">
                </div>
            </div>

            <div class="carrera-card">
                <div class="carrera-img">
                    <img src="{{ asset('img/carreras/lic_admin.png') }}" alt="Licenciatura Administración">
                </div>
            </div>

            <div class="carrera-card">
                <div class="carrera-img">
                    <img src="{{ asset('img/carreras/lic_gastro.png') }}" alt="Gastronomía">
                </div>
            </div>

            <div class="carrera-card">
                <div class="carrera-img">
                    <img src="{{ asset('img/carreras/lic_merca.png') }}" alt="Licenciatura Mercadotecnia">
                </div>
            </div>

            <div class="carrera-card">
                <div class="carrera-img">
                    <img src="{{ asset('img/carreras/lic_psicologia.png') }}" alt="Psicología">
                </div>
            </div>

            <div class="carrera-card">
                <div class="carrera-img">
                    <img src="{{ asset('img/carreras/lic_seg_publ.png') }}" alt="Seguridad Pública">
                </div>
            </div>

            <div class="carrera-card">
                <div class="carrera-img">
                    <img src="{{ asset('img/carreras/lic_turismo.png') }}" alt="Licenciatura Turismo">
                </div>
            </div>
        </div>
    </div>
@endsection