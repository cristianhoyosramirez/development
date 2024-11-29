/* function cambio(valor) { //Se recibe un un valor desde el formulario de pagos 
    var res = 0;
    let pago = 0;

    $('#valor_pago_error').html('')

    efectivoFormat = valor.replace(/[.]/g, ""); //Se quita el punto del valor recibido 

    let valorAsignado = efectivoFormat === "" ? 0 : parseInt(efectivoFormat); // Validamos que si valor esta vacio le asigne un cero 

    var valor_venta = document.getElementById("valor_total_a_pagar").value; // El valor de la venta 
    var efectivo = valorAsignado; // Valor sin punto o en caso de haber llegado vacio cero 


    let transaccion = document.getElementById("transaccion");
    let valor_t = transaccion.value;

    // Asigna un valor predeterminado de cero si "valor" está vacío
    let banco = valor_t === "" ? 0 : parseInt(valor_t);

    sub_total = parseInt(efectivo) + parseInt(banco);
    res = parseInt(sub_total) - parseInt(valor_venta);
    resultado = res.toLocaleString('es-CO');



    if (res <= 0) {
        $('#cambio').html('Cambio: $' + 0)
    }


    if (res > 0) {
        $('#cambio').html('Cambio: $' + resultado)
    }

    $('#pago').html('Valor pago: $' + sub_total.toLocaleString('es-CO'))


    faltante = parseInt(valor_venta) - (parseInt(efectivo) + parseInt(banco));


    if (faltante >= 0 && faltante < parseInt(valor_venta)) {
        $('#faltante').html('Faltante: $' + faltante.toLocaleString('es-CO'))
    }

    if (faltante < 0 ){
        $('#faltante').html('Faltante: $ 0' )
    }

    if (efectivo ==0 ){
        $('#faltante').html('Faltante: $ 0' )
    }

} */


function cambio(valor) {
    // Limpia el mensaje de error
    $('#valor_pago_error').html('');

    // Convierte el valor ingresado a un número entero
    const efectivo = parseInt(valor.replace(/[.]/g, "")) || 0;

    // Obtiene el valor de la venta y la transacción
    const valorVenta = parseInt(document.getElementById("valor_total_a_pagar").value);
    const valorTransaccion = parseInt(document.getElementById("transaccion").value.replace(/[.]/g, "")) || 0;



    // Calcula el subtotal y el cambio
    const subTotal = efectivo + valorTransaccion;
    const cambio = subTotal - valorVenta;

    // Muestra el valor del pago y el cambio
    $('#pago').html(`Valor pago: $${subTotal.toLocaleString('es-CO')}`);

    if (cambio >= 0) {
        $('#cambio').html(`Cambio: $${cambio.toLocaleString('es-CO')}`);
    } else {
        $('#cambio').html('Cambio: $0');
    }

    // Calcula y muestra el faltante
    const faltante = valorVenta - subTotal;
    

    if (faltante>=0){
        $('#faltante').html('Faltante:' + faltante.toLocaleString('es-CO'));
    }
    if (faltante< 0){
        $('#faltante').html('Faltante:' + 0);
    }
}

