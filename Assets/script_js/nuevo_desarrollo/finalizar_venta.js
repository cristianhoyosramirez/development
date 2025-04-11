function finalizar_venta() {

    let id_mesa = document.getElementById("id_mesa_pedido").value;
    var url = document.getElementById("url").value;
    var id_usuario = document.getElementById("id_usuario").value;

    

    if (id_mesa != "") {
        $.ajax({
            data: {
                id_mesa,id_usuario
            },
            url: url + "/" + "pedidos/valor",
            type: "POST",
            success: function (resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    

                    $('#finalizar_venta').on('shown.bs.modal', function () {
                        $('#efectivo').focus();
                    })


                    $('#sub_total_pedido').html(resultado.sub_total)
                    $('#requiere_factura_electronica').val(resultado.requiere_factura_electronica)

                    var propina_pedido = document.getElementById("propina_del_pedido").value;
                    //var propina_pedido_limpio = propina_pedido.replace(/\./g, "");
                    var propina_pedido_limpio = propina_pedido.replace(/[\.\$]/g, "");

                    //console.log(propina_pedido_limpio)

                    totales = parseInt(resultado.valor_total) + parseInt(propina_pedido_limpio)
                    //totales = parseInt(resultado.valor_total) 
                
                    $('#valor_total_a_pagar').val(totales)

                    $('#total_pedido').html('Valor pago: ' + totales.toLocaleString('es-ES'))
                    $('#pago').html('Valor pago: ' + totales.toLocaleString('es-ES'))
                    $('#total_propina').val(propina_pedido)
                    $('#efectivo').val(totales.toLocaleString('es-ES'))
                    $('#efectivo').select()


                    if (resultado.factura_electronica == 1) {
                        // Obtén una referencia al botón
                        var boton = document.getElementById("btn_pagar");
                        // Deshabilitar el botón
                        boton.disabled = true;
                    }

                    $('#mensaje_factura').html(resultado.factura_electronica);

                    $("#finalizar_venta").modal("show");

                    if (resultado.estado_licencia == "f") {
                        Swal.fire({
                            title: "No cuenta con una licencia activa por lo que su servicio será suspendido.",

                            icon: "warning",
                            showCancelButton: false,
                            confirmButtonColor: "#28a745", // Green color
                            confirmButtonText: "Aceptar" // Accept in Spanish
                        });

                    }
                }
                if (resultado.resultado == 0) {

                    Swal.fire({
                        title: '¡ Debe abrir la caja ! ',
                        showDenyButton: false,
                        showCancelButton: false,
                        confirmButtonText: 'Aceptar',
                        icon: 'warning'
                    })
                }
            },
        });
    }
    if (id_mesa == "") {
        sweet_alert('warning', 'No hay pedido')
    }
}