
function cancelar_pago_parcial() {

    let id_mesa = document.getElementById("id_mesa_pedido").value;
    let url = document.getElementById("url").value;

    $.ajax({
        type: 'post',
        url: url + "/" + "pedidos/cancelar_pago_parcial ", // Cambia esto a tu script PHP para insertar en la base de datos
        data: {
            id_mesa,

        }, // Pasar los datos al script PHP
        success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {

                sweet_alert('warning', 'Cancelación de pagos parciales')
                $("#partir_factura").modal("hide");



            }
        },
    });

}