function actualizar_nota() {
    let url = document.getElementById("url").value;
    let id_producto = document.getElementById("id_producto_pedido").value;
    let nota = document.getElementById("nota_producto_pedido").value;
    $.ajax({
        data: {
            id_producto,
            nota,
        },
        url: url + "/" + "pedidos/agregar_nota",
        type: "POST",
        success: function (resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {

                $('#mesa_productos').html(resultado.productos_pedido);
                $("#agregar_nota").modal("hide");
                $("#agregar_nota").val('');

                $('#operaciones').show();

                var nota = document.getElementById("nota");
                nota.style.display = "none";

    
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 500,
                    timerProgressBar: false,

                })

                Toast.fire({
                    icon: 'success',
                    title: 'Nota de producto actualizada'
                })

                /**
                 * Aca llamo a la funcion sweet alert y se le pasan los parametros.
                 */
                sweet_alert('success', 'Nota de producto actualizada ');
            }
        },
    });

}




