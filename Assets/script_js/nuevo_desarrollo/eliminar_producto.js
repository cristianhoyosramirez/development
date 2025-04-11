function eliminar_producto(event, id_tabla_producto) {
    let url = document.getElementById("url").value
    let id_usuario = document.getElementById("id_usuario").value
    event.stopPropagation();

    Swal.fire({
        title: 'Seguro de eliminar un producto?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#2AA13D',
        cancelButtonColor: '#D63939',
        focusConfirm: true,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'post',
                url: url + "/" + "pedidos/eliminar_producto", // Cambia esto a tu script PHP para insertar en la base de datos
                data: {
                    id_tabla_producto,
                    id_usuario
                }, // Pasar los datos al script PHP
                success: function (resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {
                        sweet_alert('success', resultado.mensaje)
                        $("#mesa_productos").html(resultado.productos);
                        $("#valor_pedido").html(resultado.total_pedido);
                        $("#val_pedido").html(resultado.total_pedido);
                        $("#subtotal_pedido").val(resultado.sub_total);
                        $("#propina_del_pedido").val(resultado.propina);



                    }

                    if (resultado.resultado == 0) {
                        sweet_alert('error', 'El producto ya fue impreso en comanda, esta acción requiere permiso')
                    }
                },
            });
        }
    })
}