
function llenar_select(e, codigo) {
    var enterKey = 13;
    if (codigo != '') {
        if (e.which == enterKey) {

            var lista_precios = document.getElementById("select_lista_precios").value;
            var codigo_interno_producto = document.getElementById("codigointernoproducto").value;
            if (lista_precios == 1) {
                if (codigo_interno_producto == "") {
                    id_producto = codigo

                    var url = document.getElementById("url").value;
                    $.ajax({
                        data: {
                            id_producto,
                        },
                        url: url + "/" + "caja/listado_precios",
                        type: "POST",
                        success: function (resultado) {
                            var resultado = JSON.parse(resultado);
                            //$("#imprimir_retiro").modal("hide");
                            if (resultado.resultado == 1) {
                                $('#buscar_producto').val(resultado.nombre_producto)
                                $('#lista_precios').html(resultado.lista_precios);
                                $('#codigointernoproducto').val(resultado.codigo_interno_producto);
                                $('#lista_precios').select2('focus')
                                $('#lista_precios').select2('open');
                            }
                        },
                    });

                } else if (codigo_interno_producto != "") {
                    var url = document.getElementById("url").value;
                    //var id_producto = document.getElementById("codigointernoproducto").value;
                    var id_producto = codigo_interno_producto;

                    $.ajax({
                        data: {
                            id_producto,
                        },
                        url: url + "/" + "caja/listado_precios",
                        type: "POST",
                        success: function (resultado) {
                            var resultado = JSON.parse(resultado);
                            //$("#imprimir_retiro").modal("hide");
                            if (resultado.resultado == 1) {
                                $('#buscar_producto').val(resultado.nombre_producto)
                                $('#lista_precios').html(resultado.lista_precios);
                                $('#codigointernoproducto').val(resultado.codigo_interno_producto);
                            
                                $('#lista_precios').select2('open');
                            }
                        },
                    });

                }
            }

        }
    }
}
