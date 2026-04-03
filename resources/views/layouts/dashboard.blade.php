<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - UTNay Expedientes</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    @stack('styles')
</head>
<body>
    <div class="profile-container">
        <!-- Header con gradiente azul-verde -->
        <div class="profile-header">
            <div class="profile-info">
                <div class="profile-top">
                    <div class="profile-left">
                        <!-- Botón de regreso (se muestra solo si se define la sección) -->
                        @hasSection('back-button')
                            <div class="back-button">
                                <img src="{{ asset('img/flecha.png') }}" alt="Regresar" class="back-icon">
                            </div>
                        @endif
                        <div class="avatar-circle">
                            <span class="avatar-iniciales">@yield('avatar-iniciales', 'U')</span>
                        </div>
                        <div class="profile-nombre">
                            <span class="nombre-completo">@yield('nombre-completo', 'Usuario')</span>
                        </div>
                    </div>
                    <div class="profile-actions">
                        <span class="role-badge">@yield('user-role', 'Usuario')</span>
                        <div class="action-icon">
                            <img src="{{ asset('img/tuerca.png') }}" alt="Configuración" class="icon-img">
                        </div>
                        <div class="action-icon">
                            <img src="{{ asset('img/puntitos.png') }}" alt="Más opciones" class="icon-img">
                        </div>
                        <img src="{{ asset('img/jaguar.png') }}" alt="Jaguar" class="jaguar-img">
                    </div>
                </div>
                <h1 class="profile-name">@yield('welcome-message', '¡Bienvenido!')</h1>
                <div class="profile-subtitle">@yield('subtitle', 'Aquí puedes consultar tu información')</div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/dashboard.js') }}"></script>
    @stack('scripts')
</body>
</html>