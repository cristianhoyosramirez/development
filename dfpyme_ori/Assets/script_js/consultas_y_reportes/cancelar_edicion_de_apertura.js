function cancelar_edicion_de_apertura() {
    $("#edicion_de_apertura_de_caja").modal("hide");

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    Toast.fire({
        icon: 'info',
        title: 'Cancelada la modificación del valor de la apertura de caja'
    })

}
