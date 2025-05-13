
function imprimir_duplicado_retiro(id_retiro, id_usuario) {
    // alert('hola mundo ')
    var url = document.getElementById("url").value;
    var id_retiro = id_retiro;
    var id_usuario = id_usuario;
    $.ajax({
        data: {
            id_usuario,
            id_retiro
        },
        url: url + "/" + "devolucion/imprimir_retiro",
        type: "POST",
        success: function (resultado) {
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
                    title: 'Rempresión de comprobante de retiro de dinero '
                })
            }
        },
    });
}
