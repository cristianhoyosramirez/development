
function pagar() {


    let requiere_factura_electronica = document.getElementById("requiere_factura_electronica").value; // Determinar si se requiere factura electronica o no  
    let estado = document.getElementById("documento").value; // Tipo de documento 

    let valor_efectivo = document.getElementById("efectivo").value;
    efectivoFormat = valor_efectivo.replace(/[.]/g, "");
    let valor_e = efectivoFormat;
    // Asigna un valor predeterminado de cero si "valor" está vacío
    let efectivo = valor_e === "" ? 0 : parseInt(valor_e);
    let valor_t = document.getElementById("transaccion").value;
    valor_t_Format = valor_t.replace(/[.]/g, "");
    // Asigna un valor predeterminado de cero si "valor" está vacío
    let transaccion = valor_t_Format === "" ? 0 : parseInt(valor_t_Format); //Pago electronico 
    //let banco = document.getElementById("banco").value; // Banco con el se registra un pago por pagos electronicos 
    let nit_cliente = document.getElementById("nit_cliente").value; // nit cliente  
    let id_mesa = document.getElementById("id_mesa_pedido").value; // id de la mesa 
    let url = document.getElementById("url").value; // url base
    let id_usuario = document.getElementById("id_usuario").value; // id del usuario 
    let pago_total = parseInt(efectivo) + parseInt(transaccion);
    let tipo_pago = document.getElementById("tipo_pago").value; // Tipo de pago 1 = pago completo; 2 pago parcial
    let medio_de_pago = document.getElementById("medio_de_pago").value; // Tipo de pago 1 = pago completo; 2 pago parcial

    let valor_venta = "";

    if (tipo_pago == 1) {
        //var propina = document.getElementById("propina_del_pedido").value;
        var propina = document.getElementById("total_propina").value;
        propina = propina.replace(/[\$,]/g, '');
        let val_venta = document.getElementById("valor_total_a_pagar").value; // El valor de la venta 
        valor_venta = parseInt(val_venta)


    }
    if (tipo_pago == 0) {
        var propina = document.getElementById("total_propina").value;
        var subtotal_venta = document.getElementById("valor_total_a_pagar").value;
        var subtotal_propina = document.getElementById("total_propina").value;
        subtotal_propina = propina.replace(/[.]/g, "");

        //valor_venta = parseInt(subtotal_propina) + parseInt(subtotal_venta)

        //console.log(valor_venta)

        valor_venta = subtotal_venta
    }



    propina_Format = propina.replace(/[.]/g, "");

    if (requiere_factura_electronica == "si") {  // Validacion de si requiere o no factura electronica 

        if (estado == 8 || estado == 11) {    // Validacion de que este seleccionada la factura electronica 

            factura_electronica(id_mesa, estado, nit_cliente, id_usuario, url, pago_total, valor_venta, tipo_pago, efectivo, transaccion, id_usuario, propina_Format, medio_de_pago)
        } else if (estado != 8 || estado != 11) {
            $('#error_documento').html('! Para continuar por favor seleccione Factura electrónica !')
        }

    } else if (requiere_factura_electronica == "no") {



        if (estado == 8) {
            if (pago_total >= parseInt(valor_venta)) {
                factura_electronica(id_mesa, estado, nit_cliente, id_usuario, url, pago_total, valor_venta, tipo_pago, efectivo, transaccion, id_usuario, propina_Format, medio_de_pago)
                if (pago_total < parseInt(valor_venta)) {
                    $('#valor_pago_error').html('¡ Pago insuficiente !')
                }

            }



        } else if (estado == 1 || estado == 7) {
            let button = document.querySelector("#btn_pagar");
            button.disabled = true; // Deshabilitar el botón de pagar



            if (pago_total >= parseInt(valor_venta)) {

                $.ajax({
                    data: {
                        id_mesa,
                        efectivo,
                        transaccion,
                        estado,
                        nit_cliente,
                        valor_venta,
                        id_usuario,
                        propina_Format,
                        tipo_pago
                    },
                    url: url + "/" + "pedidos/cerrar_venta",
                    type: "POST",
                    success: function (resultado) {
                        var resultado = JSON.parse(resultado);
                        if (resultado.resultado == 1) {

                            $("#btn_pagar").prop("disabled", false); // Habilitar el botón de pagar
                            $('#finalizar_venta').modal('hide');
                            $('#todas_las_mesas').html(resultado.mesas);
                            $('#lista_completa_mesas').html(resultado.mesas);
                            $('#efectivo').val(0);
                            $('#transaccion').val(0);
                            $('#propina_pesos_final').val(0);
                            $('#total_propina').val(0);
                            $('#tipo_pago').val(1);
                            $('#documentos_factura').html(resultado.documentos);

                            if (resultado.valor_pedio == '$ 0') {
                                $('#mesa_pedido').html('');
                                $('#pedido_mesa').html('Pedido');
                            }

                            if (resultado.tipo_pago == 1) {
                                limpiar_todo();
                            }

                            if (resultado.tipo_pago == 0) {
                                $('#mesa_productos').html(resultado.productos);
                                $('#valor_pedido').html(resultado.valor_pedio);
                                $('#subtotal_pedido').val(resultado.valor_pedio);
                                $('#productos_categoria').html('');
                                $('#pago').html('Valor pago: 0');
                                $('#cambio').html('Cambio: 0');
                                $('#propina_pesos').val(0);
                                $('#propina_pesos_final').val(0);
                                $('#total_propina').val(0);
                                $("#btn_pagar").prop("disabled", false); // Habilitar el botón de pagar
                            }

                            let mesas = document.getElementById("todas_las_mesas");
                            if (mesas) {
                                mesas.style.display = "block";
                            }
                            //mesas.style.display = "block";

                            header_pedido();

                            Swal.fire({
                                title: 'Resumen',
                                showDenyButton: true,
                                confirmButtonText: 'Imprimir factura',
                                denyButtonText: 'Facturar',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                html: '<hr>' + resultado.mensaje +
                                    '<div class="container">' +
                                    '<div class="row">' +
                                    '<div class="col-md-6 text-right-custom h1">Total :</div>' +
                                    '<div class="col-md-6 text-right-custom h1">' + resultado.total + '</div>' +
                                    '</div>' +
                                    '<hr class="custom-hr">' + // Línea de separación personalizada
                                    '<div class="row">' +
                                    '<div class="col-md-6 text-right-custom h1">Valor pagado :</div>' +
                                    '<div class="col-md-6 text-right-custom h1">' + resultado.valor_pago + '</div>' +
                                    '</div>' +
                                    '<hr class="custom-hr">' + // Línea de separación personalizada
                                    '<div class="row">' +
                                    '<div class="col-md-6 text-right-custom h1">Cambio : </div>' +
                                    '<div class="col-md-6 text-right-custom h1">' + resultado.cambio + '</div>' +
                                    '</div>' +
                                    '<hr class="custom-hr">' + // Línea de separación personalizada
                                    '</div>',
                                confirmButtonColor: '#58C269',
                                denyButtonColor: '#6782EF',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    let categorias = document.getElementById("lista_categorias");
                                    if (categorias) {
                                        categorias.style.display = "block";
                                    }
                                    let numero_de_factura = resultado.id_factura;

                                    $.ajax({
                                        data: {
                                            numero_de_factura,
                                        },
                                        url: url + "/" + "pedidos/imprimir_factura",
                                        type: "POST",
                                        success: function (resultado) {
                                            var resultado = JSON.parse(resultado);
                                            if (resultado.resultado == 1) {
                                                let mesas = document.getElementById("todas_las_mesas");
                                                if (mesas) {
                                                    mesas.style.display = "block";
                                                }
                                                //mesas.style.display = "block";
                                                let lista_categorias = document.getElementById("lista_categorias");
                                                if (lista_categorias) {
                                                    lista_categorias.style.display = "none";
                                                }
                                                sweet_alert('success', 'Impresión de factura correcto');
                                            }
                                        },
                                    });
                                } else if (result.isDenied) {
                                    let id_factura = resultado.id_factura;

                                    $.ajax({
                                        data: {
                                            id_factura,
                                        },
                                        url: url + "/" + "factura_pos/modulo_facturacion",
                                        type: "get",
                                        success: function (resultado) {
                                            var resultado = JSON.parse(resultado);
                                            if (resultado.resultado == 1) {
                                                let mesas = document.getElementById("todas_las_mesas");
                                                //mesas.style.display = "block";
                                                if (mesas) {
                                                    mesas.style.display = "block";
                                                }
                                                let lista_categorias = document.getElementById("lista_categorias");
                                                if (lista_categorias) {
                                                    lista_categorias.style.display = "none";
                                                }
                                                sweet_alert('success', 'Se ha finalizado la venta');
                                            }
                                        },
                                    });
                                }
                            });
                        }
                        if (resultado.resultado == 0) {
                            Swal.fire({
                                title: resultado.mensaje,
                                icon: "warning",
                                confirmButtonColor: "#ff0000", // Color rojo en formato hexadecimal
                                confirmButtonText: "Aceptar" // Texto personalizado para el botón de aceptar
                            });
                        }
                    },
                });
            } else if (pago_total < parseInt(valor_venta)) {
                $('#valor_pago_error').html('¡ Pago insuficiente !');
            }
        }



        if (estado == 2) {


            $.ajax({
                data: {
                    id_mesa,
                    efectivo,
                    transaccion,
                    estado,
                    nit_cliente,
                    valor_venta,
                    id_usuario,
                    propina_Format,
                    tipo_pago

                },
                url: url + "/" + "pedidos/cerrar_venta",
                type: "POST",
                success: function (resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        $('#finalizar_venta').modal('hide');
                        $('#todas_las_mesas').html(resultado.mesas)
                        $('#lista_completa_mesas').html(resultado.mesas)
                        $('#efectivo').val(0)
                        $('#transaccion').val(0)
                        $('#propina_pesos_final').val(0)
                        $('#total_propina').val(0)
                        $('#tipo_pago').val(1)


                        if (resultado.valor_pedio == '$ 0') {
                            $('#mesa_pedido').html('')
                            $('#pedido_mesa').html('Pedido')
                        }

                        if (resultado.tipo_pago == 1) {
                            limpiar_todo();
                            //$('#efectivo').val(0)
                        }
                        if (resultado.tipo_pago == 0) {
                            $('#mesa_productos').html(resultado.productos)
                            //$('#mesa_pedido').html(resultado.nombre_mesa)
                            //$('#pedido_mesa').html('Pedido: ' + resultado.pedido)
                            $('#valor_pedido').html(resultado.valor_pedio)
                            $('#subtotal_pedido').val(resultado.valor_pedio)
                            $('#productos_categoria').html('')
                            $('#pago').html('Valor pago: 0')
                            $('#cambio').html('Cambio: 0')
                            $('#propina_pesos').val(0)
                            $('#propina_pesos_final').val(0)
                            $('#total_propina').val(0)

                        }
                        let mesas = document.getElementById("todas_las_mesas");
                        //mesas.style.display = "block"

                        if (mesas) {
                            mesas.style.display = "block";
                        }



                        Swal.fire({
                            title: 'Resumen',
                            showDenyButton: true,
                            confirmButtonText: 'Imprimir factura',
                            denyButtonText: 'Facturar',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            html: '<hr>' + resultado.mensaje +
                                '<div class="container">' +
                                '<div class="row">' +
                                '<div class="col-md-6 text-right-custom h1">Total :</div>' +
                                '<div class="col-md-6 text-right-custom h1">' + resultado.total + '</div>' +
                                '</div>' +
                                '<hr class="custom-hr">' + // Línea de separación personalizada
                                '<div class="row">' +
                                '<div class="col-md-6 text-right-custom h1">Valor pagado :</div>' +
                                '<div class="col-md-6 text-right-custom h1">' + resultado.valor_pago + '</div>' +
                                '</div>' +
                                '<hr class="custom-hr">' + // Línea de separación personalizada
                                '<div class="row">' +
                                '<div class="col-md-6 text-right-custom h1">Cambio : </div>' +
                                '<div class="col-md-6 text-right-custom h1">' + resultado.cambio + '</div>' +
                                '</div>' +
                                '<hr class="custom-hr">' + // Línea de separación personalizada
                                '</div>',

                            confirmButtonColor: '#58C269',
                            denyButtonColor: '#6782EF',

                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {

                                let categorias = document.getElementById("lista_categorias");
                                categorias.style.display = "block"

                                let numero_de_factura = resultado.id_factura

                                $.ajax({
                                    data: {
                                        numero_de_factura,
                                    },
                                    url: url + "/" + "pedidos/imprimir_factura",
                                    type: "POST",
                                    success: function (resultado) {
                                        var resultado = JSON.parse(resultado);
                                        if (resultado.resultado == 1) {


                                            let mesas = document.getElementById("todas_las_mesas");
                                            //mesas.style.display = "block"

                                            if (mesas) {
                                                mesas.style.display = "block";
                                            }

                                            let = document.getElementById("lista_categorias");
                                            if (lista_categorias) {
                                                lista_categolista_categoriasrias.style.display = "none";
                                            }
                                            /**
                                             * Aca llamo a la funcion sweet alert y se le pasan los parametros.
                                             */
                                            sweet_alert('success', 'Impresión de factura correcto  ');
                                        }
                                    },
                                });




                            } else if (result.isDenied) {
                                let id_factura = resultado.id_factura

                                $.ajax({
                                    data: {
                                        id_factura,
                                    },
                                    url: url + "/" + "factura_pos/modulo_facturacion",
                                    type: "get",
                                    success: function (resultado) {
                                        var resultado = JSON.parse(resultado);
                                        if (resultado.resultado == 1) {

                                            let mesas = document.getElementById("todas_las_mesas");
                                            //mesas.style.display = "block"

                                            if (mesas) {
                                                mesas.style.display = "block";
                                            }

                                            let lista_categorias = document.getElementById("lista_categorias");

                                            if (lista_categorias) {
                                                lista_categorias.style.display = "none";
                                            }
                                            /**
                                             * Aca llamo a la funcion sweet alert y se le pasan los parametros.
                                             */
                                            sweet_alert('success', 'Se ha finalizado la venta ');
                                        }
                                    },
                                });
                            }
                        })
                    }

                    if (resultado.resultado == 0) {
                        Swal.fire({
                            title: resultado.mensaje,
                            icon: "warning",
                            confirmButtonColor: "#ff0000", // Color rojo en formato hexadecimal
                            confirmButtonText: "Aceptar" // Texto personalizado para el botón de aceptar
                        });

                    }
                },
            });
        }


    }
}
