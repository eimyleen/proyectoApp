@extends('layouts.dashboard')

@section('title', 'Mi Expediente - Alumno')

@section('back-button')
    
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_alumno.css') }}">
@endpush

@section('content')
    <div class="contenido-con-botones">
        <div class="botones-laterales">
            <button class="btn-expediente active">Expediente</button>
            <button class="btn-calificaciones" onclick="window.location.href='/dashboard/alumno/calificaciones'">Calificaciones</button>
            
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
                        <button class="btn-subir-foto">Subir foto</button>
                    </div>
                </div>

                <h3 class="datos-titulo">Expediente Personal</h3>
                <div class="datos-grid">
                    <div class="dato-item">
                        <label>Nombre</label>
                        <span class="dato-valor">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Apellidos</label>
                        <span class="dato-valor">{{ Auth::user()->apellido }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Carrera</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->carrera->nombre ?? 'No asignada' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Grupo</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->grupo ?? 'N/A' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Matrícula</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->matricula ?? 'N/A' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>CURP</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->curp ?? 'N/A' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Edad</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->edad ?? 'N/A' }} años</span>
                    </div>
                    <div class="dato-item">
                        <label>Sexo</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->sexo ?? 'N/A' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Fecha de nacimiento</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->fecha_nacimiento ?? 'N/A' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Correo electrónico</label>
                        <span class="dato-valor">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection