function buscar_por_codigo_de_barras_devolucion(e, codigo) {
    var enterKey = 13;
    if (codigo != '') {
        if (e.which == enterKey) {

            var codigo_de_barras = codigo

            codigo_interno = document.getElementById("codigo_producto_devolucion").value

            if (codigo_interno == '') {

                $.ajax({
                    data: {
                        codigo_de_barras
                    },
                    url: '<?php echo base_url(); ?>/producto/buscar_por_codigo_de_barras',
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {
                            $("#codigo_producto_devolucion").val(resultado.codigointernoproducto);
                            $("#precio_devolucion").val(resultado.valor_venta_producto);
                            //$("#nombre_producto").val(resultado.valor_venta_producto);
                            $("#buscar_producto").val(resultado.nombre_producto);
                            //$("#cantidad_factura_pos").focus();
                            $('#buscar_producto').autocomplete('close');
                            $("#mensaje_de_error").empty();
                        }
                        if (resultado.resultado == 0) {
                            $('#buscar_producto').autocomplete('close');
                            document.getElementById("codigo_producto_devolucion").value = ''
                            document.getElementById("devolucion_producto").value = ''
                            document.getElementById("devolucion_producto").focus();

                            $("#error_producto_devolucion").html('NO HAY CONCIDENCIAS');
                        }
                    },
                });

            }
        }
    }
}