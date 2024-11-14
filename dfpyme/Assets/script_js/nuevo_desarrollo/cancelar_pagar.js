function cancelar_pagar() {
    $('#finalizar_venta').modal('hide')
    $('#efectivo').val('')
    $('#transaccion').val('')
    $('#total_propina').val(0)
    $('#propina_pesos_fina').val(0)
    $('#tipo_pago').val(1)
    $('#criterio_propina_final').val(1)
    $('#pago').html('Valor pago: 0')
    $('#faltante').html('Faltante: 0')
    $('#cambio').html('Cambio: 0')
    $('#error_documento').html('')
    $("#btn_pagar").prop("disabled", false);

    let id_mesa = document.getElementById("id_mesa_pedido").value;
    let url = document.getElementById("url").value;
    let tipo_pago = document.getElementById("tipo_pago").value;


    
        $.ajax({
            type: 'post',
            url: url + "/" + "pedidos/cancelar_pago_parcial ", // Cambia esto a tu script PHP para insertar en la base de datos
            data: {
                id_mesa,

            }, // Pasar los datos al script PHP
            success: function (resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    $("#partir_factura").modal("hide");



                }
            },
        });
    
}