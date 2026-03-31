<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="login-container">

    <!-- Manteniendo tu esencia -->
    <h2>Bienvenido</h2>
    <p>Ingresa tus credenciales</p>

    <!-- Form -->
    <form action="/dashboard" method="GET">

        <div class="form-group">
            <label for="correo">Correo</label>
            <input 
                type="email" 
                id="correo"
                name="correo"
                class="inputCredenciales"
                placeholder="Ingresa tu correo"
                required
            >
        </div>

        <div class="form-group">
            <label for="contrasena">Contraseña</label>
            <input 
                type="password" 
                id="contrasena"
                name="contrasena"
                class="inputCredenciales"
                placeholder="Ingresa tu contraseña"
                required
            >
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" id="showPassword">
                Mostrar contraseña
            </label>
        </div>

        <button type="submit">Iniciar sesión</button>

    </form>

</div>

<!-- JS -->
<script src="{{ asset('js/login.js') }}"></script>

</body>
</html>