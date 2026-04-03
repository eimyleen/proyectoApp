@extends('layouts.dashboard')

@section('title', 'Mi Expediente - Alumno')
@section('user-role', 'Alumno')
@section('avatar-iniciales', isset($alumno) ? strtoupper(substr($alumno->nombre, 0, 1)) . strtoupper(substr($alumno->apellido, 0, 1)) : 'U')
@section('nombre-completo', isset($alumno) ? $alumno->nombre . ' ' . $alumno->apellido : 'Usuario')
@section('welcome-message', 'Mi Expediente')
@section('subtitle', 'Aquí puedes consultar tu información personal')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_alumno.css') }}">
@endpush

@section('content')
    <div class="contenido-con-botones">
        <!-- Botones laterales -->
        <div class="botones-laterales">
            <button class="btn-expediente active">Expediente</button>
            <button class="btn-calificaciones">Calificaciones</button>
            
            <!-- Logo circular de la carrera -->
            <div class="carrera-logo">
                <div class="logo-circular">
                    @if(isset($carrera->logo))
                        <img src="{{ asset('storage/' . $carrera->logo) }}" alt="{{ $carrera->nombre }}">
                    @else

                    @endif
                </div>
                <span class="logo-texto">Logo de la carrera</span>
            </div>
        </div>

        <!-- Contenido del expediente -->
        <div class="contenido-principal">
            <div class="datos-container">
                <!-- Foto de perfil -->
                <div class="perfil-section">
                    <div class="foto-perfil">
                        <div class="avatar-grande">
                            @if(isset($alumno->foto))
                                <img src="{{ asset('storage/' . $alumno->foto) }}" alt="Foto" class="foto-perfil-img">
                            @else
                                <span class="avatar-iniciales-grande">
                                    {{ isset($alumno) ? strtoupper(substr($alumno->nombre, 0, 1)) . strtoupper(substr($alumno->apellido, 0, 1)) : 'U' }}
                                </span>
                            @endif
                        </div>
                        <button class="btn-subir-foto">Subir foto</button>
                    </div>
                </div>

                <!-- Datos personales -->
                <h3 class="datos-titulo">Expediente Personal</h3>
                <div class="datos-grid">
                    <div class="dato-item">
                        <label>Nombre</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->nombre : '      ' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Apellidos</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->apellido : '      ' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Carrera</label>
                        <span class="dato-valor">{{ isset($alumno->carrera) ? $alumno->carrera->nombre : '      ' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Grupo</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->grupo : '      ' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Matrícula</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->matricula : '      ' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>CURP</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->curp : '      ' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Edad</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->edad : '      ' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Sexo</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->sexo : '      ' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Fecha de nacimiento</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->fecha_nacimiento : '      ' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Correo electrónico</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->email : '      ' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection