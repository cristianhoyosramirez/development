function detalle_f_e(id_factura) {

    let url = document.getElementById("url").value;

    $.ajax({
        data: {
            id_factura,
        },
        url: url + "/" + "pedidos/detalle_f_e",
        type: "POST",
        success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {



                $("#detalle_factura_electronica").modal("show");
                $("#tabla_f_e").html(resultado.f_e);
                $("#total").html(resultado.total);



            }
        },
    });
}