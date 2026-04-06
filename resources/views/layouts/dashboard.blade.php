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
                            <div class="back-button" id="backButton">
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
                        
                        <!-- Dropdown de idioma (tuerca) -->
                        <div class="dropdown-container">
                            <div class="action-icon" id="btnIdioma">
                                <img src="{{ asset('img/tuerca.png') }}" alt="Configuración" class="icon-img">
                            </div>
                            <div class="dropdown-menu" id="dropdownIdioma">
                                <a href="#" data-idioma="es">
                                    <img src="{{ asset('img/idioma.png') }}" alt="Idioma" class="dropdown-icon"> Español
                                </a>
                                <a href="#" data-idioma="en">
                                    <img src="{{ asset('img/idioma.png') }}" alt="Idioma" class="dropdown-icon"> English
                                </a>
                            </div>
                        </div>

                        <!-- Dropdown de navegación (puntitos) -->
                        <div class="dropdown-container">
                            <div class="action-icon" id="btnMenu">
                                <img src="{{ asset('img/puntitos.png') }}" alt="Más opciones" class="icon-img">
                            </div>
                            <div class="dropdown-menu dropdown-menu-nav" id="dropdownMenu">
                                <a href="#" id="menuInicio">
                                    <img src="{{ asset('img/inicio.png') }}" alt="Inicio" class="dropdown-icon"> Inicio
                                </a>
                                <a href="#" id="menuPerfil">
                                    <img src="{{ asset('img/perfil.png') }}" alt="Perfil" class="dropdown-icon"> Mi Perfil
                                </a>
                            </div>
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Flecha de regreso
            const backButton = document.getElementById('backButton');
            if (backButton) {
                backButton.addEventListener('click', function() {
                    const backUrl = document.querySelector('meta[name="back-url"]')?.getAttribute('content') || '/dashboard';
                    window.location.href = backUrl;
                });
            }

            // Dropdown de idioma (tuerca)
            const btnIdioma = document.getElementById('btnIdioma');
            const dropdownIdioma = document.getElementById('dropdownIdioma');
            
            if (btnIdioma && dropdownIdioma) {
                btnIdioma.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownIdioma.classList.toggle('show');
                });
            }

            // Dropdown de navegación (puntitos)
            const btnMenu = document.getElementById('btnMenu');
            const dropdownMenu = document.getElementById('dropdownMenu');
            
            if (btnMenu && dropdownMenu) {
                btnMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('show');
                });
            }

            // Cerrar dropdowns al hacer clic fuera
            window.addEventListener('click', function() {
                if (dropdownIdioma) dropdownIdioma.classList.remove('show');
                if (dropdownMenu) dropdownMenu.classList.remove('show');
            });

            // Funcionalidad de los enlaces del menú
            const menuInicio = document.getElementById('menuInicio');
            const menuPerfil = document.getElementById('menuPerfil');
            
            // Obtener el rol desde el badge
            const rolBadge = document.querySelector('.role-badge');
            let rol = 'alumno';
            if (rolBadge) {
                const textoRol = rolBadge.textContent.toLowerCase();
                if (textoRol.includes('maestro')) rol = 'maestro';
                if (textoRol.includes('administrador') || textoRol.includes('admin')) rol = 'admin';
            }

            if (menuInicio) {
                menuInicio.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.href = `/dashboard/${rol}`;
                });
            }

            if (menuPerfil) {
                menuPerfil.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.href = `/dashboard/${rol}/perfil`;
                });
            }

            // Cambio de idioma (ejemplo visual)
            document.querySelectorAll('#dropdownIdioma a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const idioma = this.getAttribute('data-idioma');
                    alert(`Idioma cambiado a: ${idioma === 'es' ? 'Español' : 'English'} (solo front end)`);
                    dropdownIdioma.classList.remove('show');
                });
            });
        });
    </script>
</body>
</html>