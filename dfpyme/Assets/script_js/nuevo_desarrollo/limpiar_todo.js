function limpiar_todo() {

    var radio_transaccion = document.getElementById("radio_transaccion");
    // Desmarca el elemento
    
    if (radio_transaccion) {
        radio_transaccion.checked = false;
    }

    //var radio_efectivo = document.getElementById("radio_efectivo");
    // Desmarca el elemento
    //radio_efectivo.checked = true;


    $('#nit_cliente').val('222222222222')
    $('#nombre_cliente').val('222222222222 CONSUMIDOR FINAL')
    $('#id_mesa_pedido').val('')
    $('#nota_pedido').val('')


    $('#pedido_mesa').html('Pedido')
    $('#mesa_pedido').html('Mesa')
    $('#mesa_productos').html('')
    $('#sub_total').html(0)
    $('#nombre_mesero').html('Mesero')
    $('#valor_pedido').html('$0')
    $('#val_pedido').html('$0')
    $('#productos_categoria').html('')
    $('#subtotal_pedido').val('$0')


    $('#propina_del_pedido').val(0);
    $('#total_propina').val(0);
    $('#efectivo').val(0);
    $('#propina_pesos').val(0);
    $('#transaccion').val('');
    //$("#documento")[0].selectedIndex = 0;
    $('#pago').html('Valor pago: 0');
    $('#cambio').html('Cambio: 0');
    $('#mesa_pedido').html('');
    $('#valor_pago_error').html('');
    $('#sub_total_pedido').html('Sub total: 0');
    $('#error_producto').html('')
    $('#producto').val('')

    $('#efectivo').select()

}