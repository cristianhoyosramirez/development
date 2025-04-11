function pago_parcial() {
    let url = document.getElementById("url").value
    let id_mesa = document.getElementById("id_mesa_pedido").value;

    if (id_mesa == "") {
        sweet_alert('warning', 'No hay pedido ')
    } else if (id_mesa != "") {


        $.ajax({
            type: 'post',
            url: url + "/" + "pedidos/productos_pedido", // Cambia esto a tu script PHP para insertar en la base de datos
            data: {
                id_mesa,

            }, // Pasar los datos al script PHP
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    $('#productos_pago_parcial').html(resultado.productos);
                    $('#total_pago_parcial').html(resultado.total);
                    $("#partir_factura").modal("show");

                }

                if (resultado.resultado == 0) {
                   sweet_alert('warning','¡ Debe abrir la caja !')
                }
            },
        });
    }
}