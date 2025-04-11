function cambio_transaccion(valor) { //Se recibe un un valor desde el formulario de pagos 
    var res = 0;
    let pago = 0;

    transaccionFormat = valor.replace(/[.]/g, ""); //Se quita el punto del valor recibido 

    let valorAsignado = transaccionFormat === "" ? 0 : parseInt(transaccionFormat); // Validamos que si valor esta vacio le asigne un cero 

    var valor_venta = document.getElementById("valor_total_a_pagar").value; // El valor de la venta 
    var transaccion = valorAsignado; // Valor sin punto o en caso de haber llegado vacio cero 



    let valor_efectivo = document.getElementById("efectivo").value;
    let efectivoFormat = valor_efectivo.replace(/[.]/g, "");
    let valor_e = efectivoFormat;

    // Asigna un valor predeterminado de cero si "valor" está vacío
    let efectivo = valor_e === "" ? 0 : parseInt(valor_e);


    sub_total = parseInt(efectivo) + parseInt(transaccion);
    res = parseInt(sub_total) - parseInt(valor_venta);



    resultado = res.toLocaleString('es-CO');
    if (res > 0) {
        $('#cambio').html('Cambio: $' + resultado)
    }
    if (res < 0) {
        $('#cambio').html('Cambio: $ 0')
        $('#pago').html('Valor pago: $ 0')
    }

    //if (sub_total > valor_venta) {
    $('#pago').html('Valor pago: $' + sub_total.toLocaleString('es-CO'))
    //}


    // Calcula y muestra el faltante
    const faltante = valor_venta - sub_total;


    if (faltante >= 0) {
        $('#faltante').html('Faltante:' + faltante.toLocaleString('es-CO'));
    }
    if (faltante < 0) {
        $('#faltante').html('Faltante:' + 0);
    }

}