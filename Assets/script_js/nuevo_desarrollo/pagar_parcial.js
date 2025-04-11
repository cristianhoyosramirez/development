function pagar_parcial() {



    let url = document.getElementById("url").value;
    let id_mesa = document.getElementById("id_mesa_pedido").value;

    $.ajax({
        data: {
            id_mesa,
        },
        url: url + "/" + "pedidos/valor_pago_parcial",
        type: "POST",
        success: function (resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {


                if (resultado.valor_total !== null && resultado.valor_total > 0) {

                    $('#partir_factura').modal('hide');
                    $('#finalizar_venta').modal('show');
                    if (resultado.estado_licencia == "f") {
                        Swal.fire({
                            title: "No cuenta con una licencia activa por lo que su servicio será suspendido",

                            icon: "warning",
                            showCancelButton: false,
                            confirmButtonColor: "#28a745", // Green color
                            confirmButtonText: "Aceptar" // Accept in Spanish
                        });
                    }
                    $('#sub_total_pedido').html("Sub total: " + resultado.total)
                    $('#mensaje_factura').html(resultado.total)
                    $('#mensaje_factura').html(resultado.factura_electronica)
                    $('#total_pedido').html(resultado.total)
                    $('#pago').html('Valor pago: ' + resultado.total)
                    $('#tipo_pago').val(0)
                    $('#valor_total_a_pagar').val(resultado.valor_total)
                    $('#requiere_factura_electronica').val(resultado.requiere_factura_electronica)
                    $('#total_propina').val(0)
                    $('#criterio_propina_final').val(0)
                    let total = resultado.valor_total
                    $('#efectivo').val(total.toLocaleString('es-CO'))
                    $('#efectivo').select()

                } else if (resultado.valor_total === null) {
                    sweet_alert('warning', ' ! No hay productos para efectuar pagos parciales ¡')
                }

            }
        },
    });
}