function agregar_nota(id_producto, event) {
    event.stopPropagation();
    $('#id_producto_pedido').val(id_producto)
    let url = document.getElementById("url").value;

    if (id_producto == 1) {
        var miDiv = document.getElementById('1');

        // Agregar la clase 'card-active' para activar el div
        miDiv.classList.add('card-active');
    }
    if (id_producto == 2) {
        var miDiv = document.getElementById('2');

        // Agregar la clase 'card-active' para activar el div
        miDiv.classList.add('card-active');
    }

    $.ajax({
        data: {
            id_producto,
        },
        url: url + "/" + "pedidos/consultar_nota",
        type: "POST",
        success: function (resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {

                $("#agregar_nota").modal("show");
                $("#informacion_producto").html(resultado.producto);
                $("#nota_producto_pedido").val(resultado.nota);
                $("#precio_producto").val(resultado.valor_total);
                $("#descuento_manual").val(resultado.valor_total);
                $("#restar_plata").val(resultado.valor_total);
                $("#cambio_manual").val(resultado.valor_total);

            }
        },
    });
}