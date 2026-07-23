<!-- Login - Módulo de autenticación de usuarios -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/IconUTNAY.png') }}" type="image/x-icon">
</head>
<body>

    <div class="contenedor-general">
        <img src="{{ asset('img/IconUTNAY.png') }}" alt="IconUTNAY" style="width: 10%;">
        <h1 class="titulo-principal">
            Portal de Expedientes de la UTNay
        </h1>

        <div class="login-container">

            <h2>Inicio de Sesión</h2>
            <p>¡Bienvenido, ingresa tus credenciales para acceder!</p>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <!-- Correo -->
                <div class="form-group">
                    <label for="correo">Correo</label>
                    <input type="email" name="correo" id="correo" value="{{ old('correo') }}" class="inputCredenciales" placeholder="Ingresa tu correo" required>
                    @error('correo')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" class="inputCredenciales" placeholder="Ingresa tu contraseña" required>
                </div>

                <!-- Mostrar contraseña -->
                <div class="checkbox">
                    <input type="checkbox" id="mostrarPassword">
                    <label for="mostrarPassword">Mostrar contraseña</label>
                </div>

                <a href="#">¿Olvidaste tu contraseña?</a>
                <!-- Botón -->
                <button type="submit">Iniciar sesión</button>

            </form>

        </div>

    </div>

    <!-- JS -->
    <script src="{{ asset('js/login.js') }}"></script>

</body>
</html>