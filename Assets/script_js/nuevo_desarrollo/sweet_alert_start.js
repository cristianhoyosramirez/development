function sweet_alert_start(icono, mensaje) {

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-start',
        //position: 'bottom-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    Toast.fire({
        icon: icono,
        title: mensaje
    })

}