function eliminar_cantidades(event, id_tabla_producto) {

    let url = document.getElementById("url").value

    let id_usuario = document.getElementById("id_usuario").value

    let id_tabla = id_tabla_producto
    event.stopPropagation();


    $.ajax({
        type: 'post',
        url: url + "/" + "pedidos/restar_producto", // Cambia esto a tu script PHP para insertar en la base de datos
        data: {
            id_tabla,

        }, // Pasar los datos al script PHP
        success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {
                sweet_alert('success', 'Se elimino un producto')

                $("#mesa_productos").html(resultado.productos);
                $("#valor_pedido").html(resultado.total);
                $("#val_pedido").html(resultado.total);
                $("#subtotal_pedido").val(resultado.sub_total);
                $("#propina_del_pedido").val(resultado.propina);

                document.getElementById('input_cantidadAtri' + resultado.id).value = resultado.cantidad
                document.getElementById('totalProducto' + resultado.id).innerHTML = resultado.valorTotal



            }
        },
    });

}