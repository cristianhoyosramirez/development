function buscar_por_codigo_de_barras(e, codigo) {
    var url = document.getElementById("url").value;
    var enterKey = 13;
    if (codigo != '') {
        if (e.which == enterKey) {

            var codigo_de_barras = codigo

            codigo_interno = document.getElementById("codigointernoproducto").value
            lista_precios = document.getElementById("lista_precios").value

            if (codigo_interno == '') {

                $.ajax({
                    data: {
                        codigo_de_barras,
                        lista_precios
                    },
                    url: url + "/" + "producto/buscar_por_codigo_de_barras",
                    type: "POST",
                    success: function (resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {
                            $("#codigointernoproducto").val(resultado.codigo_interno_producto);
                            $("#valor_venta_producto").val(resultado.valor_venta_producto);
                            //$("#nombre_producto").val(resultado.valor_venta_producto);
                            $("#buscar_producto").val(resultado.nombre_producto);
                            //$("#cantidad_factura_pos").focus();
                            $('#buscar_producto').autocomplete('close');
                            $("#mensaje_de_error").empty();
                        }
                        if (resultado.resultado == 0) {
                            $('#buscar_producto').autocomplete('close');
                            document.getElementById("buscar_producto").value = ''
                            document.getElementById("buscar_producto").focus();

                            $("#mensaje_de_error").html('NO HAY CONCIDENCIAS');
                        }
                    },
                });

            }
        }
    }
}
