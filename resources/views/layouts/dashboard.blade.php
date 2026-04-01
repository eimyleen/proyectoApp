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
                

            <div class="profile-actions">
                <span class="role-badge">@yield('user-role', 'Usuario')</span>
                <div class="action-icon">
                    <img src="{{ asset('img/tuerca.png') }}" alt="Configuración" class="icon-img">
                </div>
                <div class="action-icon">
                    <img src="{{ asset('img/puntitos.png') }}" alt="Más opciones" class="icon-img">
                </div>
            </div>

                <h1 class="profile-name">@yield('welcome-message', '¡Bienvenido!')</h1>
                <div class="profile-subtitle">@yield('subtitle', 'Aquí puedes consultar tu información')</div>
            </div>
        </div>

        <!-- Sección de opciones según rol -->
        <div class="role-options">
            <div class="options-container">
                @yield('role-options')
            </div>
        </div>

        <!-- Barra sticky que aparece al hacer scroll -->
        <div class="sticky-bar" id="stickyBar">
            <div class="sticky-content">
                <div class="sticky-left">
                    <div class="sticky-avatar">
                        @yield('sticky-avatar', 'U')
                    </div>
                    <div class="sticky-name">
                        @yield('sticky-name', 'Usuario')
                    </div>
                </div>
                
                <div class="sticky-actions">
                    <div class="sticky-icon">
                        <img src="{{ asset('img/tuerca.png') }}" alt="Configuración" class="icon-img-small">
                    </div>
                    <div class="sticky-icon">
                        <img src="{{ asset('img/puntitos.png') }}" alt="Más opciones" class="icon-img-small">
                    </div>
                </div>

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