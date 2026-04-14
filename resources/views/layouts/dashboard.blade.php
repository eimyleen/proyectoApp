<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset("img/IconUTNAY.png") }}" type="image/x-icon">
    <title>@yield('title', 'Dashboard') - UTNay Expedientes</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    @stack('styles')
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-info">
                <div class="profile-top">
                    <div class="profile-left">
                        @hasSection('back-button')
                            <a href="{{ Auth::user()->role == 'admin' ? route('admin.index') : (Auth::user()->role == 'maestro' ? route('maestro.index') : route('alumno.index')) }}" 
                            style="text-decoration: none;">
                                <div class="back-button" id="backButton">
                                    <img src="{{ asset('img/flecha.png') }}" alt="Regresar" class="back-icon">
                                </div>
                            </a>
                        @endif
                        
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
                        <div class="profile-nombre">
                            <span class="nombre-completo">{{ Auth::user()->name }} {{ Auth::user()->apellido }}</span>
                        </div>
                    </div>

                    <div class="profile-actions">
                        <span class="role-badge">{{ ucfirst(Auth::user()->role) }}</span>
                        
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
                                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                    @csrf
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <img src="{{ asset('img/flecha.png') }}" alt="Cerrar" class="dropdown-icon"> Cerrar Sesión
                                    </a>
                                </form>
                            </div>
                        </div>

                        <img src="{{ asset('img/jaguar.png') }}" alt="Jaguar" class="jaguar-img">
                    </div>
                </div>
                <h1 class="profile-name">¡Bienvenido, {{ Auth::user()->name }}!</h1>
                <div class="profile-subtitle">@yield('subtitle', 'Aquí puedes consultar tu información')</div>
            </div>
        </div>

        <div class="main-content">
            @yield('content')
        </div>
    </div>

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
    @stack('scripts')
</body>
</html>