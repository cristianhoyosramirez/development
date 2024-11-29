function intercambio_mesa() {
    var url = document.getElementById("url").value;
    var id_mesa_origen = document.getElementById("id_mesa_origen").value;
    var id_mesa_destino = document.getElementById("mesa_destino").value;

    $.ajax({
        data: {
            id_mesa_origen,
            id_mesa_destino
        },
        url: url + "/" + "mesas/intercambio_mesa",
        type: "POST",
        success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 0) {


                document.getElementById("id_mesa_pedido").value = resultado.id_mesa;
                $('#mesa_pedido').html(resultado.nombre_mesa)
                $('#mesa_productos').html(resultado.productos_pedido)
                $('#valor_pedido').html(resultado.valor_total)
                sweet_alert('success', 'Cambio de mesas')


            }
            if (resultado.resultado == 1) {

                $('#mesa_pedido').html(resultado.nombre_mesa)
                $('#mesa_productos').html(resultado.productos_pedido)
                $('#valor_pedido').html(resultado.valor_total)
                document.getElementById("id_mesa_pedido").value = resultado.id_mesa;
                sweet_alert('success', 'Cambio de mesas')


            }


        },
    });
}