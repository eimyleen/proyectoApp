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

<!DOCTYPE html>
<html lang="es">
<head>
    {{-- viewport: Hace que la página se vea bien en celulares y tablets --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- CSRF TOKEN: Necesario para enviar formularios con seguridad --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- FAVICON: Icono que aparece en la pestaña del navegador --}}
    <link rel="shortcut icon" href="{{ asset("img/IconUTNAY.png") }}" type="image/x-icon">
    
    {{-- 
        TÍTULO DINÁMICO
        Las vistas hijas pueden cambiarlo con @section('title', 'Mi título')
    --}}
    <title>@yield('title', 'Dashboard') - UTNay Expedientes</title>
    
    {{-- 
        CSS PRINCIPAL DEL DASHBOARD
        Contiene: efecto glass, puntos de fondo, línea separadora, colores, etc.
    --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    {{-- 
        CSS ADICIONAL (STACK)
        Las vistas hijas pueden agregar sus propios estilos con:
        @push('styles') ... @endpush
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
        
        {{-- ======================================================
             HEADER CON EFECTO GLASS
             ====================================================== 
             Este es el encabezado que se ve en todas las páginas.
             Tiene:
             - Avatar con iniciales del usuario (dinámico)
             - Nombre completo del usuario (dinámico)
             - Badge con el rol (Alumno, Admin, Maestro) (dinámico)
             - Dropdown de idioma (con enlaces reales)
             - Dropdown de navegación (con enlaces por rol)
             - Logo Jaguar
             - Línea separadora debajo del menú
             - Título y subtítulo de bienvenida (dinámicos)
        --}}
        <div class="profile-header">
            
            {{-- FILA SUPERIOR: Avatar, Nombre y Botones (dentro de profile-info) --}}
            <div class="profile-info">
                <div class="profile-top">
                    
                    {{-- === COLUMNA IZQUIERDA: Avatar + Nombre === --}}
                    <div class="profile-left">
                        
                        {{-- 
                            BOTÓN DE REGRESO (opcional)
                            Solo aparece si la vista hija define @section('back-button')
                            Redirige según el rol del usuario autenticado.
                        --}}
                        @hasSection('back-button')
                            <a href="{{ Auth::user()->role == 'admin' ? route('admin.index') : (Auth::user()->role == 'maestro' ? route('maestro.index') : route('alumno.index')) }}" 
                            style="text-decoration: none;">
                                <div class="back-button" id="backButton">
                                    <img src="{{ asset('img/flecha.png') }}" alt="Regresar" class="back-icon">
                                </div>
                            </a>
                        @endif
                        
                        {{-- 
                            AVATAR CÍRCULAR CON INICIALES
                            Toma la primera letra del nombre y apellido del usuario autenticado.
                            Ejemplo: "Carlos Martínez" → "CM"
                            Al hacer clic, redirige al perfil según el rol.
                        --}}
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
                        
                        {{-- NOMBRE COMPLETO DEL USUARIO --}}
                        <div class="profile-nombre">
                            <span class="nombre-completo">{{ Auth::user()->name }} {{ Auth::user()->apellido }}</span>
                        </div>
                    </div>

                    {{-- === COLUMNA DERECHA: Acciones === --}}
                    <div class="profile-actions">
                        
                        {{-- 
                            ROLE BADGE
                            Muestra el rol del usuario (Alumno, Admin, Maestro) en mayúscula inicial.
                        --}}
                        <span class="role-badge">{{ ucfirst(Auth::user()->role) }}</span>
                        
                        {{-- ==============================================
                             DROPDOWN DE IDIOMA
                             ============================================== 
                             Al hacer clic en el ícono, se abre un menú para cambiar
                             el idioma de la aplicación (Español/English).
                             Usa la ruta 'set_language' definida en web.php.
                        --}}
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

                        {{-- ==============================================
                             DROPDOWN DE NAVEGACIÓN
                             ============================================== 
                             Al hacer clic en los puntitos, se abre un menú con:
                             - Inicio (redirige al dashboard según el rol)
                             - Mi Perfil (redirige al perfil según el rol)
                             - Cerrar Sesión (cierra la sesión con formulario POST)
                        --}}
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
                                <hr>
                                {{-- 
                                    FORMULARIO PARA CERRAR SESIÓN
                                    Laravel requiere que el logout se haga con método POST
                                    para proteger contra ataques CSRF.
                                --}}
                                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                    @csrf
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <img src="{{ asset('img/flecha.png') }}" alt="Cerrar" class="dropdown-icon"> Cerrar Sesión
                                    </a>
                                </form>
                            </div>
                        </div>

                        {{-- LOGO JAGUAR (enlace a la universidad) --}}
                        <img src="{{ asset('img/jaguar.png') }}" alt="Jaguar" class="jaguar-img">
                    </div>
                </div>
            </div>

            {{-- ==================================================
                 LÍNEA SEPARADORA (EXTREMO A EXTREMO)
                 ================================================== 
                 Esta línea está FUERA de .profile-info para que ocupe
                 todo el ancho de la pantalla (sin padding a los costados).
            --}}
            <div class="separator-line-wrapper">
                <div class="separator-line"></div>
            </div>

            {{-- TÍTULO Y SUBTÍTULO (dentro de profile-info) --}}
            <div class="profile-info">
                {{-- 
                    MENSAJE DE BIENVENIDA
                    Usa la función __() para traducción y muestra el nombre del usuario.
                --}}
                <h1 class="profile-name">¡{{ __('messages.title_welcome_dashboard') }}, {{ Auth::user()->name }}!</h1>
                
                {{-- 
                    SUBTÍTULO DINÁMICO
                    Las vistas hijas pueden cambiarlo con @section('subtitle')
                --}}
                <div class="profile-subtitle">@yield('subtitle', 'Aquí puedes consultar tu información')</div>
            </div>
        </div>

        {{-- ======================================================
             CONTENIDO PRINCIPAL
             ====================================================== 
             Aquí es donde las vistas hijas inyectan su contenido.
             Cada vista (alumno, admin, maestro) define su propio
             contenido usando: @section('content')
        --}}
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    {{-- ======================================================
         SCRIPTS
         ====================================================== --}}
    <script>
        {{-- 
            FUNCIONALIDAD JAVASCRIPT:
            - Menú desplegable de navegación (puntitos)
            - Dropdown de idioma
            - Cerrar dropdowns al hacer clic fuera
            - Toma el rol directamente de Laravel
        --}}

        document.addEventListener('DOMContentLoaded', function() {
            // Pasamos el rol directamente de Laravel a JS sin redeclarar variables conflictivas
            const userRole = "{{ Auth::user()->role }}";

            // Lógica del menú desplegable (puntitos)
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
            const btnIdioma = document.getElementById('btnIdioma');
            const dropdownIdioma = document.getElementById('dropdownIdioma');
            
            if (btnIdioma && dropdownIdioma) {
                btnIdioma.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownIdioma.classList.toggle('show');
                });
            }
        });
    </script>
    
    {{-- 
        SCRIPTS ADICIONALES (STACK)
        Las vistas hijas pueden agregar JavaScript extra con:
        @push('scripts') ... @endpush
    --}}
    @stack('scripts')
</body>
</html>