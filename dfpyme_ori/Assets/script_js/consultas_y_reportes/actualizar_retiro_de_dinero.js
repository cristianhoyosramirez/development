function actualizar_retiro_de_dinero() {
    var url = document.getElementById("url").value;
    var valor_retiro = document.getElementById("edit_retiro_de_dinero").value;
    var id_retiro = document.getElementById("id_edicion_retiro_de_dinero").value;
    var concepto_retiro = document.getElementById("concepto_retiros").value;
    $.ajax({
        data: {
            id_retiro,
            valor_retiro,
            concepto_retiro
        },
        url: url + "/" + "devolucion/actualizar_retiro_de_dinero",
        type: "POST",
        success: function (resultado) {
            var resultado = JSON.parse(resultado);
            $("#imprimir_retiro").modal("hide");
            if (resultado.resultado == 1) {

                $("#crud_apertura").html(resultado.datos);
                $("#edicion_retiro_de_dinero").modal("hide");
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
                    title: 'Información cambiada  '
                })
            }
        },
    });
}