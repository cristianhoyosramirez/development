function pago_efectivo() {
    var tipo_pago = document.getElementById("tipo_pago").value;
    let total_venta = parseFloat(document.getElementById("valor_total_a_pagar").value);
    let propina_parcial = document.getElementById("total_propina").value;
    // Reemplaza todos los puntos en la propina
    let total_propina = propina_parcial.replace(/\./g, '');

    if (tipo_pago == 1) {
        $('#efectivo').val(total_venta.toLocaleString('es-CO'))
        $('#pago').html('Valor pago: ' + total_venta.toLocaleString('es-CO'))
        $('#faltante').html('Faltante: 0')
        $('#cambio').html('Cambio: 0')
        $('#transaccion').val(0)
    }

    if (tipo_pago == 0) {
        // Asegúrate de convertir total_propina a número antes de sumarlo
        //let total = total_venta + parseFloat(total_propina);
        let total = total_venta;
        $('#efectivo').val(total.toLocaleString('es-CO'))
        $('#pago').html('valor pago: ' + total.toLocaleString('es-CO'))
        $('#faltante').html('Faltante: 0')
        $('#cambio').html('Cambio: 0')
        $('#transaccion').val(0)
    }
}