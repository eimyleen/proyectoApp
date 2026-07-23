// ============================================================
// SWEETALERTS - VERSIÓN SCRIPTS TRADICIONALES
// ============================================================
// Este archivo usa SweetAlert2 cargado vía CDN con <script>
// No usa import/export, todo es global con window

// ============================================================
// CLASES BASE REUTILIZABLES
// ============================================================
const clasesAlertas = {
    title: 'swal-title',
    htmlContainer: 'swal-text',
    confirmButton: 'swal-btn-confirm',
    cancelButton: 'swal-btn-cancel'
};

// ============================================================
// CONFIGURACIÓN BASE
// ============================================================
const alertaBase = Swal.mixin({
    buttonsStyling: false,

    customClass: {
        popup: 'swal-popup',
        ...clasesAlertas
    },

    allowOutsideClick: false,
    allowEscapeKey: true,
    reverseButtons: true
});

// ============================================================
// ALERTA DE ÉXITO
// ============================================================
function alertaExito(titulo, mensaje) {
    return alertaBase.fire({
        icon: 'success',
        title: titulo,
        text: mensaje,
        confirmButtonText: 'Aceptar',
        customClass: {
            ...clasesAlertas,
            popup: 'swal-popup swal-success'
        }
    });
}

// ============================================================
// ALERTA DE INFORMACIÓN
// ============================================================
function alertaInfo(titulo, mensaje) {
    return alertaBase.fire({
        icon: 'info',
        title: titulo,
        text: mensaje,
        confirmButtonText: 'Aceptar',
        customClass: {
            ...clasesAlertas,
            popup: 'swal-popup swal-info'
        }
    });
}

// ============================================================
// ALERTA DE ERROR
// ============================================================
function alertaError(titulo, mensaje) {
    return alertaBase.fire({
        icon: 'error',
        title: titulo,
        text: mensaje,
        confirmButtonText: 'Aceptar',
        customClass: {
            ...clasesAlertas,
            popup: 'swal-popup swal-error'
        }
    });
}

// ============================================================
// CONFIRMACIÓN DE ELIMINACIÓN (CON PARÁMETROS)
// ============================================================
function confirmarEliminacion(
    titulo = '¿Eliminar registro?',
    mensaje = 'Esta acción no se puede deshacer.'
) {
    return alertaBase.fire({
        icon: 'warning',
        title: titulo,
        text: mensaje,
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            ...clasesAlertas,
            popup: 'swal-popup swal-warning'
        }
    });
}

// ============================================================
// CONFIRMACIÓN PERSONALIZADA (Extra)
// ============================================================
function confirmarAccion(
    titulo = '¿Confirmar acción?',
    mensaje = '¿Estás seguro de realizar esta acción?',
    textoConfirmar = 'Confirmar',
    textoCancelar = 'Cancelar'
) {
    return alertaBase.fire({
        icon: 'question',
        title: titulo,
        text: mensaje,
        showCancelButton: true,
        confirmButtonText: textoConfirmar,
        cancelButtonText: textoCancelar,
        customClass: {
            ...clasesAlertas,
            popup: 'swal-popup swal-info'
        }
    });
}

// ============================================================
// EXPONER FUNCIONES GLOBALMENTE PARA BLADE
// ============================================================
window.alertaExito = alertaExito;
window.alertaInfo = alertaInfo;
window.alertaError = alertaError;
window.confirmarEliminacion = confirmarEliminacion;
window.confirmarAccion = confirmarAccion;