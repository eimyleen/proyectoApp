// 'DOMContentLoaded' Solo se ejecuta cuando todo el HTML esté cargado
document.addEventListener('DOMContentLoaded', () => {
    // CORREGIDO: Ahora usa los IDs correctos
    const toggle = document.getElementById('mostrarPassword');
    const passwordInput = document.getElementById('password');

    // Por si algún elemento no está en la página, evita que el código falle
    if (toggle && passwordInput) {
        toggle.addEventListener('change', function () {
            passwordInput.type = this.checked ? 'text' : 'password';
        });
    }
});
