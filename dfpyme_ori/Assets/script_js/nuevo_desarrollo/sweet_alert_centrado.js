function sweet_alert_centrado(icono, mensaje) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top', // Cambiado a 'top' para usar estilos personalizados
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: icono,
        title: mensaje,
        customClass: {
            popup: 'centered-toast' // Clase personalizada para el estilo
        }
    });
}
