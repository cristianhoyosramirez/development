function restar_partir_factura(event, cantidad, id_tabla_producto) {
    event.stopPropagation()

    let url = document.getElementById("url").value;


    $.ajax({
        data: {
            id_tabla_producto,
            cantidad
        },
        url: url + "/" + "pedidos/restar_partir_factura",
        type: "POST",
        success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {

                $('#total_pago_parcial').html(resultado.total)
                $("#productos_pago_parcial").html(resultado.productos);



            }
        },
    });

}