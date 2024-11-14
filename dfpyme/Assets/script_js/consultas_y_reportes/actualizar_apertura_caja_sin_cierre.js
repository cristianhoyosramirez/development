function actualizar_apertura_caja_sin_cierre(id_apertura) {
    var url = document.getElementById("url").value;
    var valor_apertura = document.getElementById("editar_valor_apertura_sin_cierre").value;
    var apertura = valor_apertura.replace(/[.]/g, "");
    $.ajax({
        data: {
            id_apertura,
            apertura,
        },
        url: url + "/" + "caja/actualizar_apertura_caja_sin_cierre",
        type: "POST",
        success: function (resultado) {
            var resultado = JSON.parse(resultado);
           
            if (resultado.resultado == 1) {
                $("#edicion_de_apertura_de_caja_sin_cierre").modal("hide");
                $("#valor_apertura_sin_cierre").html(resultado.valor_apertura);
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
                    title: 'Actualización de apertura de caja éxitoso'
                })
            }
        },
    });
}