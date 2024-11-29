function imprimir_electronica(id_factura) {

    var url = document.getElementById("url").value;

    $.ajax({
        data: {
            id_factura, // Incluye el número de factura en los datos
        },
        url: url + "/" + "pedidos/impresion_factura_electronica",
        type: "POST",
        success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {



            }
        },
    });

}