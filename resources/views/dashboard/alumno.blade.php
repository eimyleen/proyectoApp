@extends('layouts.dashboard')

@section('title', 'Mi Panel - Alumno')

@section('sidebar-menu')
    <ul>
        <li class="active">
            <a href="#">
                Inicio
            </a>
        </li>
        <li>
            <a href="#">
                Mi Expediente
            </a>
        </li>
        <li>
            <a href="#">
                Mis Horarios
            </a>
        </li>
        <li>
            <a href="#">
                Mi Perfil
            </a>
        </li>
    </ul>
@endsection

@section('user-name', 'Carlos Martínez')
@section('user-role', 'Alumno')

@section('content')
    <div class="welcome-card" style="background: linear-gradient(135deg, #06b6d4, #10b981); border-radius: 16px; padding: 32px; margin-bottom: 32px; color: white;">
        <h1 style="font-size: 1.8rem; margin-bottom: 8px;">¡Bienvenido, Carlos!</h1>
        <p style="opacity: 0.9;">Aquí puedes consultar tu información académica</p>
    </div>
    
    <!-- Aquí irá el contenido específico del alumno -->
    <p>Contenido del dashboard alumno...</p>
@endsection