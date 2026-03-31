// 'DOMContentLoaded' Solo se ejecuta cuando todo el HTML esté cargado
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('showPassword');
    const passwordInput = document.getElementById('contrasena');

    // Por si algun elemento no está en la página por algún motivo, evita que el código falle.
    if (toggle && passwordInput) {
        toggle.addEventListener('change', function () {
            passwordInput.type = this.checked ? 'text' : 'password';
        });
    }
});
