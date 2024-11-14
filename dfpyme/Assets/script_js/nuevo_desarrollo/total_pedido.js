function total_pedido(valor) {
    var sub_total = document.getElementById("subtotal_pedido").value;


    sub_total = sub_total.replace(/\$/g, ''); // Elimina el signo de dólar
    sub_total = sub_total.replace(/\./g, ''); // Elimina el punto

    temp_valor = "";

    if (valor === "") {
        temp_valor = 0
    } else {
        temp_valor = valor;
    }

    if (temp_valor != 0) {
        temp_valor = valor.replace(/\./g, ''); // Elimina el punto
    }

    valor_pedido = parseInt(sub_total) + parseInt(temp_valor)

    $('#valor_pedido').html('$' + valor_pedido.toLocaleString('es-CO'))

    actualizar_propina(valor)

}