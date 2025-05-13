function actualizar_cantidad_partir_factura(cantidad, id, cantidad_producto) {
    var url = document.getElementById("url").value;
    let id_mesa = document.getElementById("id_mesa_pedido").value;
    let id_tabla = id

    $.ajax({
        data: {
            cantidad,
            id,
            cantidad_producto,
            id_mesa
        },
        url: url +
            "/" +
            "pedidos/partir_factura",
        type: "POST",
        success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 0) {
                Swal.fire({
                    icon: "success",
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#2AA13D",
                    title: "Operacion cancelada",
                });
            } else if (resultado.resultado == 1) {
                /*$("#valor_producto_partir_factura").html(
                  resultado.valor_total_producto
                );*/
                $("#total_factura_mostrar").html(
                    resultado.valor_total_pedido
                );
                $("#items_facturar_partir").html(resultado.productos);
                Swal.fire({
                    icon: "success",
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#2AA13D",
                    title: "Operación exitosa",
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        myModal = new bootstrap.Modal(
                            document.getElementById("items_partir_factura"), {}
                        );
                        myModal.show();
                    }
                });
            }
        },
    });

}