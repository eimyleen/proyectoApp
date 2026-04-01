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
    <div class="dashboard">
        <!-- Sidebar izquierda -->
        <aside class="sidebar">
            <div class="sidebar-logo">
            <div class="logo-icon">
                <span></span>
            </div>
                <h2>Expedientes UTNay</h2>
            </div>
            
            <nav class="sidebar-nav">
                @yield('sidebar-menu')
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-bubble">
                    <img src="{{ asset('images/default-avatar.png') }}" alt="avatar" class="avatar-img">
                    <div class="user-info">
                        <p class="user-name">@yield('user-name', 'Usuario')</p>
                        <span class="user-role">@yield('user-role')</span>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Contenido principal -->
        <main class="main-content">
            <header class="top-header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle">
                        <span>☰</span>
                    </button>
                </div>
                
                <div class="user-bubble-header">
                    <img src="{{ asset('images/default-avatar.png') }}" alt="avatar" class="avatar-img">
                    <div class="user-info">
                        <p class="user-name">@yield('user-name', 'Usuario')</p>
                        <span class="user-role">@yield('user-role')</span>
                    </div>
                </div>
            </header>
            
            <div class="page-content">
                @yield('content')
            </div>
        </main>
    </div>
    
    <script src="{{ asset('js/dashboard.js') }}"></script>
    @stack('scripts')
</body>
</html>