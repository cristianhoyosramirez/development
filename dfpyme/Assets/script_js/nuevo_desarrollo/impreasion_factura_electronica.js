function impresion_factura_electronica() {

    var url = document.getElementById("url").value;
    var id_factura = document.getElementById("id_de_factura").value;
    $("#barra_progreso").modal("hide");
    $.ajax({
        data: {
            id_factura, // Incluye el número de factura en los datos
        },
        url: url + "/" + "pedidos/impresion_factura_electronica",
        type: "POST",
        success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {


                let mesas = document.getElementById("todas_las_mesas");
                mesas.style.display = "block"

                let lista_categorias = document.getElementById("lista_categorias");
                lista_categorias.style.display = "none";


                sweet_alert('success', 'Impresión de factura correcto  ');
            }
        },
    });
}