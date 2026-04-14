@extends('layouts.dashboard')

@section('title', __('messages.title_my_record'))
@section('subtitle', __('messages.subtitle_record'))

@section('title', 'Mi Expediente - Alumno')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_alumno.css') }}">
@endpush

@section('back-button')
    <!-- Botón de regresar -->
@endsection

@section('content')
    <div class="contenido-con-botones">
        <div class="botones-laterales">
            <a href="{{ route('alumno.expediente') }}" style="text-decoration: none;">
                <button class="btn-expediente {{ Request::routeIs('alumno.expediente') ? 'active' : '' }}">
                    {{ __('messages.btn_record') }}
                </button>
            </a>
            <a href="{{ route('alumno.calificaciones') }}" style="text-decoration: none;">
                <button class="btn-calificaciones {{ Request::routeIs('alumno.calificaciones') ? 'active' : '' }}">
                    {{ __('messages.btn_grades') }}
                </button>
            </a>
            
            <div class="logo-circular">
                @if(Auth::user()->alumno->carrera->logo)
                    <img src="{{ asset(Auth::user()->alumno->carrera->logo) }}" 
                        alt="Logo {{ Auth::user()->alumno->carrera->nombre }}"
                        style="width: 100%; height: 100%; object-fit: contain;">
                @else
                    <img src="{{ asset('img/jaguar.png') }}" alt="UTNay">
                @endif
            </div>
        </div>

        <div class="contenido-principal">
            <div class="datos-container">
                <div class="perfil-section">
                    <div class="foto-perfil">
                        <div class="avatar-grande">
                            @if(Auth::user()->foto)
                                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto" class="foto-perfil-img">
                            @else
                                <span class="avatar-iniciales-grande">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->apellido, 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        <form action="{{ route('perfil.foto.update') }}" method="POST" enctype="multipart/form-data" id="fotoForm">
                            @csrf
                            @method('PUT')
                            <input type="file" name="foto" id="inputFoto" style="display: none;" accept="image/*" onchange="document.getElementById('fotoForm').submit();">
                            <button type="button" class="btn-subir-foto" onclick="document.getElementById('inputFoto').click();">
                                Subir Foto
                            </button>
                        </form>
                    </div>
                </div>

                <h3 class="datos-titulo">{{ __('messages.title_personal_record') }}</h3>
                <div class="datos-grid">
                    <div class="dato-item">
                        <label>{{ __('messages.th_nombre') }}</label>
                        <span class="dato-valor">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="dato-item">
                        <label>{{ __('messages.label_apellidos') }}</label>
                        <span class="dato-valor">{{ Auth::user()->apellido }}</span>
                    </div>
                    <div class="dato-item">
                        <label><label>{{ __('messages.label_major') }}</label></label>
                        <span class="dato-valor">{{ Auth::user()->alumno->carrera->nombre ?? 'No asignada' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>{{ __('messages.label_group') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->grupo ?? 'N/A' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>{{ __('messages.label_id_number') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->matricula ?? 'N/A' }}</span>
                    </div>
                    <div class="dato-item">
                       <label>{{ __('messages.label_curp') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->curp ?? 'N/A' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>{{ __('messages.label_age') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->edad ?? 'N/A' }} años</span>
                    </div>
                    <div class="dato-item">
                        <label>{{ __('messages.label_gender') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->sexo ?? 'N/A' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>{{ __('messages.label_birthdate') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->fecha_nacimiento ?? 'N/A' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>{{ __('messages.th_email') }}</label>
                        <span class="dato-valor">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection