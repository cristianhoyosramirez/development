function actualizar_producto_cantidad(e, cantidad, id_tabla_producto) {

    if (cantidad != "") {
        var url = document.getElementById("url").value;
        var id_usuario = document.getElementById("id_usuario").value;

        $.ajax({
            data: {
                cantidad,
                id_tabla_producto,
                id_usuario
            },
            url: url + "/" + "producto/actualizacion_cantidades",
            type: "post",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);

                if (resultado.resultado == 1) {
                    $('#productos_pedido').html(resultado.productos);
                    $("#valor_total").html(resultado.total_pedido);
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
                        title: 'Cantidades agregadas'
                    })
                } else if (resultado.resultado == 0) {
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
                        icon: 'error',
                        title: 'Usuario no tiene permiso de eliminacion de pedido '
                    })
                }
            },
        });





    }

}