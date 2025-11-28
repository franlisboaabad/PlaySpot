// Script global para manejar mensajes de sesión con SweetAlert
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si hay mensajes de éxito en la sesión
    const successMessages = document.querySelectorAll('.alert-success');
    successMessages.forEach(function(alert) {
        const message = alert.textContent.trim();
        if (message) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
            // Ocultar el alert original
            alert.style.display = 'none';
        }
    });

    // Verificar si hay mensajes de error en la sesión
    const errorMessages = document.querySelectorAll('.alert-danger');
    errorMessages.forEach(function(alert) {
        const message = alert.textContent.trim();
        if (message) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
                confirmButtonText: 'Entendido'
            });
            // Ocultar el alert original
            alert.style.display = 'none';
        }
    });

    // Verificar si hay mensajes de warning en la sesión
    const warningMessages = document.querySelectorAll('.alert-warning');
    warningMessages.forEach(function(alert) {
        const message = alert.textContent.trim();
        if (message && !alert.closest('.disponibilidadArea')) { // Excluir alertas de disponibilidad
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: message,
                confirmButtonText: 'Entendido'
            });
            // Ocultar el alert original
            alert.style.display = 'none';
        }
    });
});

