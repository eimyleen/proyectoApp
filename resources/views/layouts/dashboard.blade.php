<<<<<<< HEAD
{{-- 
    ============================================================
    DASHBOARD - LAYOUT BASE
    ============================================================
    Este es el archivo principal que todas las vistas del panel
    de control van a usar (alumno, admin, maestro, etc.).

    ¿CÓMO FUNCIONA?
    - Las vistas hijas se conectan con: @extends('layouts.dashboard')
    - Los @yield() son "huecos" que las vistas hijas pueden llenar
    - Los @stack() permiten agregar CSS o JS extra desde vistas hijas
    - @hasSection() verifica si la vista hija definió algo
    ============================================================ 
--}}

=======
>>>>>>> origin/version_inicial
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    {{-- viewport: Hace que la página se vea bien en celulares y tablets --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- 
        CSRF TOKEN
        Es un código de seguridad que Laravel genera para proteger
        los formularios contra ataques. Siempre debe estar presente.
    --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
<<<<<<< HEAD
    
    {{-- 
        FAVICON
        Es el icono que aparece en la pestaña del navegador.
        Está en la carpeta public/img/
    --}}
    <link rel="shortcut icon" href={{ asset("img/IconUTNAY.png") }} type="image/x-icon">
    
    {{-- 
        TÍTULO DINÁMICO
        Las vistas hijas pueden cambiar el título con:
        @section('title', 'Mi título personalizado')
        Si no lo hacen, por defecto se muestra 'Dashboard'
    --}}
    <title>@yield('title', 'Dashboard') - UTNay Expedientes</title>
    
    {{-- 
        CSS PRINCIPAL
        Este es el archivo que contiene todos los estilos del dashboard:
        colores, efectos glass, tablas, botones, etc.
        Está en: public/css/dashboard.css
    --}}
=======
    <link rel="shortcut icon" href="{{ asset("img/IconUTNAY.png") }}" type="image/x-icon">
    <title>@yield('title', 'Dashboard') - UTNay Expedientes</title>
>>>>>>> origin/version_inicial
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    {{-- 
        CSS ADICIONAL (STACK)
        Las vistas hijas pueden agregar sus propios estilos usando:
        @push('styles') ... @endpush
        Esto es útil cuando una vista necesita estilos únicos.
    --}}
    @stack('styles')
</head>
<body>
    {{-- 
        CONTENEDOR PRINCIPAL
        Envuelve todo el contenido y lo organiza en columna
        (header arriba, contenido abajo)
    --}}
    <div class="profile-container">
<<<<<<< HEAD

        {{-- ======================================================
             HEADER CON EFECTO GLASS
             ====================================================== 
             Este es el encabezado que se ve en todas las páginas.
             Tiene:
             - Avatar con iniciales del usuario
             - Nombre del usuario
             - Badge con el rol (Alumno, Admin, Maestro)
             - Dropdown de idioma (tuerca)
             - Dropdown de navegación (puntitos)
             - Logo Jaguar con enlace a la universidad
             - Línea separadora debajo del menú
             - Título y subtítulo de bienvenida
        --}}
=======
>>>>>>> origin/version_inicial
        <div class="profile-header">
            
            {{-- ==================================================
                 FILA SUPERIOR: Avatar, Nombre y Botones
                 ================================================== --}}
            <div class="profile-info">
                <div class="profile-top">
                    
                    {{-- === COLUMNA IZQUIERDA === --}}
                    {{-- Aquí van: avatar, nombre y (opcional) botón de regreso --}}
                    <div class="profile-left">
<<<<<<< HEAD
                        
                        {{-- 
                            BOTÓN DE REGRESO (opcional)
                            Solo aparece si la vista hija define:
                            @section('back-button')
                            Es útil para volver a la página anterior.
                        --}}
=======
>>>>>>> origin/version_inicial
                        @hasSection('back-button')
                            <a href="{{ Auth::user()->role == 'admin' ? route('admin.index') : (Auth::user()->role == 'maestro' ? route('maestro.index') : route('alumno.index')) }}" 
                            style="text-decoration: none;">
                                <div class="back-button" id="backButton">
                                    <img src="{{ asset('img/flecha.png') }}" alt="Regresar" class="back-icon">
                                </div>
                            </a>
                        @endif
<<<<<<< HEAD

                        {{-- 
                            AVATAR CÍRCULAR
                            Muestra las iniciales del usuario.
                            La vista hija puede cambiarlas con:
                            @section('avatar-iniciales', 'CM')
                            Por defecto muestra 'U' (Usuario)
                        --}}
                        <div class="avatar-circle">
                            <span class="avatar-iniciales">@yield('avatar-iniciales', 'U')</span>
                        </div>

                        {{-- 
                            NOMBRE COMPLETO
                            La vista hija puede cambiarlo con:
                            @section('nombre-completo', 'Carlos Martínez')
                            Por defecto muestra 'Usuario'
                        --}}
=======
                        
                        <a href="{{ 
                            Auth::user()->role == 'admin' ? route('admin.perfil') : 
                            (Auth::user()->role == 'maestro' ? route('maestro.perfil') : route('alumno.expediente')) 
                        }}" style="text-decoration: none;">
                            <div class="avatar-circle">
                                <span class="avatar-iniciales">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->apellido, 0, 1)) }}
                                </span>
                            </div>
                        </a>
>>>>>>> origin/version_inicial
                        <div class="profile-nombre">
                            <span class="nombre-completo">{{ Auth::user()->name }} {{ Auth::user()->apellido }}</span>
                        </div>
                    </div>

<<<<<<< HEAD
                    {{-- === COLUMNA DERECHA === --}}
                    {{-- Aquí van: rol, dropdowns y logo --}}
                    <div class="profile-actions">
                        
                        {{-- 
                            ROLE BADGE
                            Muestra el rol del usuario (Alumno, Admin, Maestro).
                            La vista hija lo define con:
                            @section('user-role', 'Alumno')
                        --}}
                        <span class="role-badge">@yield('user-role', 'Usuario')</span>
                        
                        {{-- ==============================================
                             DROPDOWN DE IDIOMA (tuerca)
                             ============================================== 
                             Al hacer clic en el ícono de la tuerca,
                             se abre un menú para cambiar el idioma.
                             (Actualmente es solo una demostración visual)
                        --}}
=======
                    <div class="profile-actions">
                        <span class="role-badge">{{ ucfirst(Auth::user()->role) }}</span>
                        
>>>>>>> origin/version_inicial
                        <div class="dropdown-container">
                            <div class="action-icon" id="btnIdioma">
                                <img src="{{ asset('img/idioma.png') }}" alt="Configuración" class="icon-img">
                            </div>
                            <div class="dropdown-menu" id="dropdownIdioma">
                                <a href="{{ route('set_language', 'es') }}" data-idioma="es">
                                    <img src="{{ asset('img/idioma.png') }}" alt="Idioma" class="dropdown-icon"> Español
                                </a>
                                <a href="{{ route('set_language', 'en') }}" data-idioma="en">
                                    <img src="{{ asset('img/idioma.png') }}" alt="Idioma" class="dropdown-icon"> English
                                </a>
                            </div>
                        </div>
<<<<<<< HEAD

                        {{-- ==============================================
                             DROPDOWN DE NAVEGACIÓN (puntitos)
                             ============================================== 
                             Al hacer clic en los tres puntitos,
                             se abre un menú con opciones:
                             - Inicio (redirige al dashboard del rol)
                             - Mi Perfil (redirige al perfil del usuario)
                             - Cerrar sesión (cierra la sesión)
                        --}}
=======
>>>>>>> origin/version_inicial
                        <div class="dropdown-container">
                            <div class="action-icon" id="btnMenu">
                                <img src="{{ asset('img/puntitos.png') }}" alt="Más opciones" class="icon-img">
                            </div>
                            <div class="dropdown-menu dropdown-menu-nav" id="dropdownMenu">
                                <a href="{{ 
                                    Auth::user()->role == 'admin' ? route('admin.index') : 
                                    (Auth::user()->role == 'maestro' ? route('maestro.index') : route('alumno.index')) 
                                }}" id="menuInicio">
                                    <img src="{{ asset('img/inicio.png') }}" alt="Inicio" class="dropdown-icon"> Inicio
                                </a>
                                
                                <hr>
                                <a href="{{ 
                                    Auth::user()->role == 'admin' ? route('admin.perfil') : 
                                    (Auth::user()->role == 'maestro' ? route('maestro.perfil') : route('alumno.expediente')) 
                                }}" id="menuPerfil">
                                    <img src="{{ asset('img/perfil.png') }}" alt="Perfil" class="dropdown-icon"> Mi Perfil
                                </a>
<<<<<<< HEAD
                                <a href="#" id="menuLogout">Cerrar sesión</a>
=======
                                <hr>
                                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                    @csrf
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <img src="{{ asset('img/flecha.png') }}" alt="Cerrar" class="dropdown-icon"> Cerrar Sesión
                                    </a>
                                </form>
>>>>>>> origin/version_inicial
                            </div>
                        </div>

                        {{-- 
                            LOGO JAGUAR
                            Es el logo de la universidad.
                            Al hacer clic, abre la página de la UTNay en una nueva pestaña.
                        --}}
                        <a href="https://www.utnay.edu.mx/" target="_blank">
                            <img src="{{ asset('img/jaguar.png') }}" alt="Jaguar" class="jaguar-img">
                        </a>
                    </div>
                </div>
<<<<<<< HEAD
            </div>

            {{-- ==================================================
                 LÍNEA SEPARADORA
                 ================================================== 
                 Esta línea gris separa visualmente el menú superior
                 del título de bienvenida. Está diseñada para ocupar
                 todo el ancho de la pantalla (de extremo a extremo).
            --}}
            <div class="separator-line-wrapper">
                <div class="separator-line"></div>
            </div>

            {{-- ==================================================
                 TÍTULO Y SUBTÍTULO DE BIENVENIDA
                 ================================================== --}}
            <div class="profile-info">
                {{-- 
                    MENSAJE DE BIENVENIDA
                    La vista hija puede cambiarlo con:
                    @section('welcome-message', 'Mis Calificaciones')
                    Por defecto muestra '¡Bienvenido!'
                --}}
                <h1 class="profile-name">@yield('welcome-message', '¡Bienvenido!')</h1>
                
                {{-- 
                    SUBTÍTULO
                    La vista hija puede cambiarlo con:
                    @section('subtitle', 'Aquí puedes consultar tu información')
                --}}
=======
                <h1 class="profile-name">¡{{ __('messages.title_welcome_dashboard') }}, {{ Auth::user()->name }}!</h1>
>>>>>>> origin/version_inicial
                <div class="profile-subtitle">@yield('subtitle', 'Aquí puedes consultar tu información')</div>
            </div>
        </div>

<<<<<<< HEAD
        {{-- ======================================================
             CONTENIDO PRINCIPAL
             ====================================================== 
             Aquí es donde las vistas hijas inyectan su contenido.
             Cada vista (alumno, admin, maestro) define su propio
             contenido usando: @section('content')
        --}}
=======
>>>>>>> origin/version_inicial
        <div class="main-content">
            @yield('content')
        </div>
    </div>

<<<<<<< HEAD
    {{-- ======================================================
         SCRIPTS
         ====================================================== --}}
    
    {{-- 
        DASHBOARD.JS
        Contiene funciones complementarias para el dashboard.
        Está en: public/js/dashboard.js
    --}}
    <script src="{{ asset('js/dashboard.js') }}"></script>
    
    {{-- 
        SCRIPTS ADICIONALES (STACK)
        Las vistas hijas pueden agregar JavaScript extra usando:
        @push('scripts') ... @endpush
    --}}
    @stack('scripts')

    {{-- ======================================================
         JAVASCRIPT FUNCIONAL (INCLUIDO AQUÍ)
         ====================================================== 
         Este código maneja:
         1. Botón de regreso (si existe)
         2. Dropdown de idioma (tuerca)
         3. Dropdown de navegación (puntitos)
         4. Cerrar dropdowns al hacer clic fuera
         5. Redirección del menú según el rol
         6. Cambio de idioma 
         7. Cerrar sesión 
    --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ==============================================
            // 1. BOTÓN DE REGRESO
            // ==============================================
            // Busca el botón de regreso. Si existe, al hacer clic
            // redirige a la URL guardada en 'back-url' o a '/dashboard'
            const backButton = document.getElementById('backButton');
            if (backButton) {
                backButton.addEventListener('click', function() {
                    const backUrl = document.querySelector('meta[name="back-url"]')?.getAttribute('content') || '/dashboard';
                    window.location.href = backUrl;
                });
            }

            // ==============================================
            // 2. DROPDOWN DE IDIOMA (tuerca)
            // ==============================================
            // Al hacer clic en la tuerca, se abre/cierra el menú de idiomas
=======
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pasamos el rol directamente de Laravel a JS sin redeclarar variables conflictivas
            const userRole = "{{ Auth::user()->role }}";

            // Lógica del menú desplegable
            const btnMenu = document.getElementById('btnMenu');
            const dropdownMenu = document.getElementById('dropdownMenu');
            
            if (btnMenu && dropdownMenu) {
                btnMenu.addEventListener('click', (e) => {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('show');
                });
            }

            // Cerrar al hacer clic fuera
            window.addEventListener('click', () => {
                if (dropdownMenu) dropdownMenu.classList.remove('show');
            });

            // Dropdown de idioma (tuerca)
>>>>>>> origin/version_inicial
            const btnIdioma = document.getElementById('btnIdioma');
            const dropdownIdioma = document.getElementById('dropdownIdioma');
            if (btnIdioma && dropdownIdioma) {
                btnIdioma.addEventListener('click', function(e) {
                    e.stopPropagation(); // Evita que se cierre inmediatamente
                    dropdownIdioma.classList.toggle('show');
                });
            }
<<<<<<< HEAD

            // ==============================================
            // 3. DROPDOWN DE NAVEGACIÓN (puntitos)
            // ==============================================
            // Al hacer clic en los puntitos, se abre/cierra el menú de navegación
            const btnMenu = document.getElementById('btnMenu');
            const dropdownMenu = document.getElementById('dropdownMenu');
            if (btnMenu && dropdownMenu) {
                btnMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('show');
                });
            }

            // ==============================================
            // 4. CERRAR DROPDOWNS AL HACER CLIC FUERA
            // ==============================================
            // Si el usuario hace clic en cualquier parte fuera de los dropdowns,
            // se cierran automáticamente.
            window.addEventListener('click', function() {
                if (dropdownIdioma) dropdownIdioma.classList.remove('show');
                if (dropdownMenu) dropdownMenu.classList.remove('show');
            });

            // ==============================================
            // 5. REDIRECCIÓN DEL MENÚ SEGÚN EL ROL
            // ==============================================
            // Cuando el usuario hace clic en "Inicio" o "Mi Perfil",
            // se detecta su rol desde el badge y se redirige a la URL correcta.
            const menuInicio = document.getElementById('menuInicio');
            const menuPerfil = document.getElementById('menuPerfil');
            const rolBadge = document.querySelector('.role-badge');
            
            let rol = 'alumno'; // valor por defecto
            if (rolBadge) {
                const textoRol = rolBadge.textContent.toLowerCase();
                if (textoRol.includes('maestro')) rol = 'maestro';
                if (textoRol.includes('administrador') || textoRol.includes('admin')) rol = 'admin';
            }

            // Redirige a /dashboard/rol cuando hace clic en "Inicio"
            if (menuInicio) {
                menuInicio.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.href = `/dashboard/${rol}`;
                });
            }

            // Redirige a /dashboard/rol/perfil cuando hace clic en "Mi Perfil"
            if (menuPerfil) {
                menuPerfil.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.href = `/dashboard/${rol}/perfil`;
                });
            }

            // ==============================================
            // 6. CAMBIO DE IDIOMA 
            // ==============================================
            // Al seleccionar un idioma debería cambiarlo
            document.querySelectorAll('#dropdownIdioma a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const idioma = this.getAttribute('data-idioma');
                    alert(`Idioma cambiado a: ${idioma === 'es' ? 'Español' : 'English'} (solo front end)`);
                    dropdownIdioma.classList.remove('show');
                });
            });

            // ==============================================
            // 7. CERRAR SESIÓN (DEMO)
            // ==============================================
            // Al hacer clic en "Cerrar sesión", muestra una alerta.
            const menuLogout = document.getElementById('menuLogout');
            if (menuLogout) {
                menuLogout.addEventListener('click', function(e) {
                    e.preventDefault();
                    alert('Cerrar sesión');
                    // Cuando exista la ruta de logout, se puede descomentar:
                    // window.location.href = '/logout';
                });
            }

=======
>>>>>>> origin/version_inicial
        });
    </script>
    @stack('scripts')
</body>
</html>