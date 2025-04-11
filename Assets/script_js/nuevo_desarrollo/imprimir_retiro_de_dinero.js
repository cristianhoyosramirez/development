function imprimir_retiro_de_dinero() {
    // alert('hola mundo ')
    var url = document.getElementById("url").value;
    var id_retiro = document.getElementById("id_retiro").value;
    var id_usuario = document.getElementById("id_usuario").value;
    $.ajax({
        data: {
            id_usuario,
            id_retiro
        },
        url: url + "/" + "devolucion/imprimir_retiro",
        type: "POST",
        success: function(resultado) {
            var resultado = JSON.parse(resultado);
            $("#imprimir_retiro").modal("hide");
            if (resultado.resultado == 1) {
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
                    icon: 'success',
                    title: 'Impresión de comprobante de retiro de dinero '
                })
            }
        },
    });
}