<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="{{  asset('css/login.css')  }}">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <form>
        @csrf
        <h2>Inicio de sesión</h2>

        <img src="img/imgperfil.svg" alt="Torrin">

        <p>¡Bienvenido, ingresa tus credenciales para acceder!</p>

        <label class="labelCredenciales" for="nombre_usuario">Usuario</label>
        <input class="inputCredenciales" type="text" id="nombre_usuario" name="nombre_usuario" placeholder="" required autofocus>
        
        <label class="labelCredenciales" for="contrasena">Contraseña</label>
        <input class="inputCredenciales" type="password" id="contrasena" name="contrasena" placeholder="" required autofocus>
       
        <div class="checkboxLogin">
            <input type="checkbox" name="showPassword" id="showPassword">
            <label for="showPassword">Mostrar constraseña</label>
        </div>

        <button class="btnLogin" type="submit">Iniciar Sesión</button>
    </form>
    <script>
        // Si la sesión fue exitosa
        @if(session('autenticado'))
            Swal.fire({
                icon: 'success',
                title: 'Inicio de sesión exitoso',
                text: '{{ session('mensaje_bienvenida') }}',
                timer: 2000,
                showConfirmButton: false,
                willClose: () => {
                    window.location.href = '/dashboard';
                }
            });
        @endif

        // Si la sesión fue cerrada
        @if(session('logout'))
            Swal.fire({
                icon: 'info',
                title: 'Sesión cerrada',
                text: '{{ session('logout') }}',
                timer: 2000,
                showConfirmButton: false
            });
        @endif

        // Si hubo un error por las credenciales
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonText: 'Intentar de nuevo'
            });
        @endif
    </script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>