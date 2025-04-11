<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title><?= $this->renderSection('title') ?>&nbsp;-&nbsp;DF PYME</title>
    <!-- CSS files -->
    <link href="<?= base_url() ?>/Assets/css/tabler.min.css" rel="stylesheet" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/Assets/img/favicon.png">
    <!-- Jquery-ui -->
    <link href="<?php echo base_url() ?>/Assets/plugin/jquery-ui/jquery-ui.css" rel="stylesheet">
    <!-- Select 2 -->
    <link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
</head>
<?php $session = session(); ?>

<body>
    <div class="wrapper">
        <?= $this->include('layout/header_mesas') ?>
        

        <div class="page-wrapper">
            <div class="container-xl">
            </div>
            <div class="page-body">
                <?= $this->renderSection('content') ?>
                <?= $this->include('ventanas_modal_finalizar_venta/facturar_pedido') ?>
                <?= $this->include('ventanas_modal_editar_eliminar_pedido/editar_cantidad_y_precio_producto_pedidoFactura') ?>


            </div>

            <?= $this->include('layout/footer') ?>
            <?= $this->include('ventanas_modal_cliente/creacion_cliente') ?>
            <?= $this->include('ventanas_modal_finalizar_venta/finalizar_venta') ?>
            <?= $this->include('ventanas_modal_finalizar_venta/finalizar_venta_partir_factura') ?>
            <?= $this->include('ventanas_modal_finalizar_venta/productos_partir_factura') ?>
            <?= $this->include('ventanas_modal_finalizar_venta/fin_de_venta') ?>
            <?= $this->include('ventanas_modal_finalizar_venta/facturar_pedido_partir_factura') ?>
            <?= $this->include('ventanas_modal_editar_eliminar_pedido/editar_cantidad_producto_pedidoFactura') ?>


        </div>

        <!-- Libs JS -->
        <!-- Tabler Core -->
        <script src="<?= base_url() ?>/Assets/js/tabler.min.js"></script>
        <!--jQuery -->
        <script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
        <!-- jQuery-ui -->
        <script src="<?php echo base_url() ?>/Assets/plugin/jquery-ui/jquery-ui.js"></script>
        <!-- Locales -->
        <script src="<?= base_url() ?>/Assets/script_js/facturar_pedido/editar_datos_producto.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/facturar_pedido/editar_precio_cantidad_producto.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/facturar_pedido/eliminar_producto_pedido.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/facturar_pedido/cerrar_venta.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/facturar_pedido/cliente.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/facturar_pedido/departamento_municipios.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/facturar_pedido/agregar_cliente_pedido.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/facturar_pedido/partir_factura.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/facturar_pedido/imprimir_comanda_desde_pedido.js"></script>
        <!-- Sweet alert -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
        <!--select2 -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.js"></script>
        <!--Input mask-->
        <script src="<?php echo base_url(); ?>/Assets/plugin/input_mask/dist/inputmask.js"></script>
        <!--Atajos teclado -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/atajos_teclado/shortcut.js"></script>

        <script>
            $("#agregar_item").autocomplete({
                source: function(request, response) {
                    var url = document.getElementById("url").value;
                    $.ajax({
                        type: "POST",
                        url: url + "/" + "producto/pedido_pos",
                        data: request,
                        success: response,
                        dataType: "json",
                    });
                },
            }, {
                minLength: 1,
            }, {
                select: function(event, ui) {
                    $("#agregar_item").val(ui.item.value);
                    $("#id_item_pedido").val(ui.item.id_producto);
                    $("#valor_unitario_item").val(ui.item.valor_venta_producto);
                    $("#cantidad_factura_pos").focus();
                    document.getElementById("item_cantidad").focus()

                },
            });
        </script>

        <script>
            var input = document.getElementById('agregar_item');

            input.onkeydown = function() {
                const key = event.key;
                if (key === "Backspace") {
                    document.getElementById('id_item_pedido').value = "";
                }
            };
        </script>

        <script>
            function busqueda_por_codigo_de_barras_2(e, codigo) {
                var enterKey = 13;
                if (codigo != '') {
                    if (e.which == enterKey) {

                        var codigo_de_barras = codigo

                        codigo_interno = document.getElementById("id_item_pedido").value


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
                                        $("#id_item_pedido").val(resultado.codigointernoproducto);
                                        $("#valor_unitario_item").val(resultado.valor_venta_producto);
                                        $("#agregar_item").val(resultado.nombre_producto);
                                        //$("#item_nombre_producto").html(resultado.nombre_producto);
                                        $("#item_cantidad").focus();
                                        $('#buscar_producto').autocomplete('close');
                                        $("#mensaje_de_error").empty();
                                    }
                                    if (resultado.resultado == 0) {
                                        $('#buscar_producto').autocomplete('close');
                                        document.getElementById("buscar_producto").value = ''
                                        $("#mensaje_de_error").html('NO HAY CONCIDENCIAS');
                                    }
                                },
                            });

                        }
                    }
                }
            }
        </script>
        <script>
            function busqueda_por_codigo_de_barras(e, codigo) {
                var enterKey = 13;
                if (codigo != '') {
                    if (e.which == enterKey) {

                        var codigo_de_barras = codigo

                        codigo_interno = document.getElementById("id_item_pedido").value
                        lista_precios = document.getElementById("lista_precios").value

                        if (codigo_interno == '') {
                            $.ajax({
                                data: {
                                    codigo_de_barras,
                                    lista_precios
                                },
                                url: '<?php echo base_url(); ?>/producto/buscar_por_codigo_de_barras',
                                type: "POST",
                                success: function(resultado) {
                                    var resultado = JSON.parse(resultado);

                                    if (resultado.resultado == 1) {
                                        //$("#id_item_pedido").val(resultado.codigointernoproducto);
                                        $("#id_item_pedido").val(resultado.codigo_interno_producto);
                                        $("#valor_unitario_item").val(resultado.valor_venta_producto);
                                        $("#agregar_item").val(resultado.nombre_producto);
                                        //$("#item_nombre_producto").html(resultado.nombre_producto);
                                        $("#item_cantidad").focus();
                                        $('#buscar_producto').autocomplete('close');
                                        $("#mensaje_de_error").empty();
                                        $("#select_lista_precios_pedido").html(resultado.lista_precios);
                                        $('#select_lista_precios_pedido').select2('focus')
                                        $('#select_lista_precios_pedido').select2('open')
                                    }
                                    if (resultado.resultado == 0) {
                                        $('#buscar_producto').autocomplete('close');
                                        document.getElementById("buscar_producto").value = ''
                                        $("#mensaje_de_error").html('NO HAY CONCIDENCIAS');
                                    }
                                },
                            });

                        }
                    }
                }
            }
        </script>

        <script>
            function agregar_item_pedido() {

                var codigo_interno_producto = document.getElementById("id_item_pedido").value;
                var cantidad = document.getElementById("item_cantidad").value;
                var numero_pedido = document.getElementById("numero_de_facturacion").value;

                var estado_precios = document.getElementById("lista_precios").value;
                if (estado_precios == 0) {
                    lista_precios = 0
                }

                if (estado_precios == 1) {
                    var lista_precios = document.getElementById("select_lista_precios_pedido").value;
                }


                elemento = document.getElementById("item_pedido_cantidad");
                elemento.blur();

                $.ajax({
                    data: {
                        codigo_interno_producto,
                        cantidad,
                        numero_pedido,
                        lista_precios

                    },
                    url: "<?php echo base_url(); ?>/producto/cargar_item_al_pedido",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {
                            /* document.getElementById("codigointernoproducto").value = ""
                            document.getElementById("nota_producto_pos").value = ""
                            document.getElementById("buscar_producto").value = ""
                            document.getElementById("valor_venta_producto").value = ""
                            document.getElementById("cantidad_factura_pos").value = 1
                            document.getElementById("buscar_producto").focus();
                            $('#productos_de_pedido_pos').empty();
                           
                            document.getElementById("total_pedido_pos").value = resultado.total */


                            //$("#select_lista_precios_pedido").val('').trigger('change') ;


                            $("#select_lista_precios_pedido").empty().trigger('change')

                            $("#productos_pedido_a_facturar").html(resultado.productos);
                            document.getElementById("total_del_pedido").value = resultado.total_pedido
                            document.getElementById("total_del_pedido_sin_formato").value = resultado.total_pedido_sin_punto

                            document.getElementById("resultado_total_del_pedido").value = resultado.total_pedido

                            document.getElementById("id_item_pedido").value = ""
                            document.getElementById("agregar_item").value = ""
                            document.getElementById("valor_unitario_item").value = ""
                            document.getElementById("item_cantidad").value = 1
                            document.getElementById("agregar_item").focus();
                        }
                        if (resultado.resultado == 2) {
                            $('#productos_de_pedido_pos').empty();
                            $("#productos_de_pedido_pos").append(resultado.productos);
                            document.getElementById("total_pedido_pos").value = resultado.total
                            document.getElementById("codigointernoproducto").value = ""
                            document.getElementById("buscar_producto").value = ""
                            document.getElementById("valor_venta_producto").value = ""
                            document.getElementById("cantidad_factura_pos").value = 1
                            document.getElementById("nota_producto_pos").value = ""
                            document.getElementById("buscar_producto").focus();
                            alert('Resultado 2');
                        }
                    },
                });
            }

            /**
             *  En el input cantudad de producto del formulario factura pos solo puede aceptar numeros
             * solo deben ir valores numericos
             * @param {*} e
             * @returns
             */
            function soloNumerosEnPedido(e) {
                var key = window.Event ? e.which : e.keyCode;
                return key >= 48 && key <= 57;
            }
        </script>

        <script>
            function saltar_item_pedido(e, id) {
                // Obtenemos la tecla pulsada

                e.keyCode ? (k = e.keyCode) : (k = e.which);

                // Si la tecla pulsada es enter (codigo ascii 13)

                if (k == 13) {
                    // Si la variable id contiene "submit" enviamos el formulario

                    if (id == "submit") {
                        document.forms[0].submit();
                    } else {
                        // nos posicionamos en el siguiente input

                        document.getElementById(id).focus();
                    }
                }
            }
        </script>

        <script>
            function reset_inputs() {
                document.getElementById("efectivo_format").value = "";
                document.getElementById("cambio").value = "";
                document.getElementById("valor_total_sinPunto").value = "";
                document.getElementById("efectivo").value = "0";
                document.getElementById("transaccion_format").value = "";
                document.getElementById("transaccion").value = "0";
            }
        </script>




        <script>
            $(document).ready(function() {
                //Para mostrar un modal
                shortcut.add("f8", function() {
                    // $('#ventanaModal').modal('show');
                    codeValue = 'false'
                    //var valor_total = document.getElementById("total_del_pedido").value;
                    //document.getElementById("valor_total_a_pagar").value = valor_total;
                    var numero_pedido = document.getElementById("numero_de_facturacion").value;
                    //console.log("el total del valor es ", valor_total);
                    var url = document.getElementById("url").value;
                    $.ajax({
                        data: {
                            numero_pedido,
                        },
                        url: url + "/" + "pedido/total_pedido",
                        type: "POST",
                        success: function(resultado) {
                            var resultado = JSON.parse(resultado);
                            if (resultado.resultado == 1) {
                                shortcut.remove('f8');
                                document.getElementById("base_grabable").value = resultado.base;
                                document.getElementById("ico_grabable").value = resultado.ico;
                                document.getElementById("iva_grabable").value = resultado.iva;
                                $("#impuestos").show();
                                //document.getElementById("valor_total_a_pagar").value =resultado.valor_total;


                                let descuento = document.getElementById("total_descuento%").value;
                                document.getElementById("descuento_factura").value = descuento;

                                let propina = document.getElementById("total_descuento_propina").value;
                                document.getElementById("propina_factura").value = propina;

                                let tot = resultado.valor_total
                                total = tot.replace(/[.]/g, "")
                                gran_total = parseInt(total) - (parseInt(descuento))
                                gran_totalizado = parseInt(gran_total) + parseInt(propina)

                                document.getElementById("valor_total_a_pagar").value = gran_totalizado.toLocaleString();
                                console.log('hola mundo')

                                myModal = new bootstrap.Modal(
                                    document.getElementById("facturar_pedido"), {}
                                );
                                myModal.show();
                            }
                            if (resultado.resultado == 2) {


                                // document.getElementById("valor_total_a_pagar").value = resultado.valor_total;

                                let descuento = document.getElementById("total_descuento%").value;
                                document.getElementById("descuento_factura").value = descuento;



                                let propina = document.getElementById("total_descuento_propina").value;
                                document.getElementById("propina_factura").value = propina;

                                let tot = resultado.valor_total
                                total = tot.replace(/[.]/g, "")
                                gran_total = parseInt(total) - (parseInt(descuento))
                                gran_totalizado = parseInt(gran_total) + parseInt(propina)

                                document.getElementById("valor_total_a_pagar").value = gran_totalizado.toLocaleString();



                                myModal = new bootstrap.Modal(
                                    document.getElementById("facturar_pedido"), {}
                                );
                                myModal.show();
                            }
                        },
                    });

                    /**
                     * Autofoco en el facturar pedido
                     */
                    $(function() {
                        $("#facturar_pedido").on("shown.bs.modal", function(e) {
                            $("#efectivo").focus();
                        });
                    });
                });
            });

            shortcut.add("f2", function() {
                var url = document.getElementById("url").value;
                var numero_pedido = document.getElementById(
                    "numero_pedido_imprimir_comanda"
                ).value;

                $.ajax({
                    data: {
                        numero_pedido,
                    },
                    url: url + "/" + "comanda/imprimir_comanda_desde_pedido",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);
                        if (resultado.resultado == 1) {
                            shortcut.remove('f2');
                            const Toast = Swal.mixin({
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener("mouseenter", Swal.stopTimer);
                                    toast.addEventListener("mouseleave", Swal.resumeTimer);
                                },
                            });

                            Toast.fire({
                                icon: "success",
                                title: "Impresión de comanda",
                            });
                        }
                        if (resultado.resultado == 0) {
                            shortcut.remove('f2');
                            const Toast = Swal.mixin({
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener("mouseenter", Swal.stopTimer);
                                    toast.addEventListener("mouseleave", Swal.resumeTimer);
                                },
                            });

                            Toast.fire({
                                icon: "info",
                                title: "No hay productos para imprimir en comanda",
                            });
                        }
                    },
                });
            })
        </script>

        <script>
            $(document).ready(function() {
                var url = document.getElementById("url").value;
                $('#select_lista_precios_pedido').select2({
                    width: '100%',
                    placeholder: "lista de precios ",
                    language: "es",
                    theme: "bootstrap-5",
                    allowClear: true,
                    minimumResultsForSearch: -1,
                    language: {

                        noResults: function() {
                            return "No hay resultados";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    }

                });

            });

            /*  document.getElementById("lista_precios_pedido").focus()

             function selectPrecio(e) {
                 var precio = e.target.selectedOptions[0].getAttribute("data-precio")
                 //document.getElementById("valor_venta_producto").value = precio;
                 $("#cantidad_factura_pos").focus();
             } */
        </script>


        <script>
            function myFunction() {
                /*  var x = document.getElementById("mySelect").value;
                 document.getElementById("demo").innerHTML = "You selected: " + x; */
                $('#select_lista_precios_pedido').select2('close');
                //$('#item_cantidad').focus()
                //$('item_cantidad').focus();

                elemento = document.getElementById("select_lista_precios_pedido");
                elemento.blur();

            }
        </script>


        <script>
            function descuento_de_factura(porcentaje_descuento) {
                var url = document.getElementById("url").value;
                var descuento = document.getElementById("descuento_porcentaje").value;

                var tot = document.getElementById("total_del_pedido").value;
                total = parseFloat(tot.replace(/[.]/g, ""));

                if (descuento == 1) {
                    if (porcentaje_descuento <= 100) {
                        porcentaje = (total * porcentaje_descuento) / 100
                        document.getElementById("total_descuento%").value = porcentaje.toLocaleString("es-ES")

                        var descuento_temp = document.getElementById("total_descuento_propina").value;

                        total_descuento = parseInt(descuento_temp.replace(/[.]/g, ""))

                        let descontar = (total - porcentaje) + total_descuento


                        document.getElementById("resultado_total_del_pedido").value = descontar.toLocaleString("es-ES")



                    }
                    if (porcentaje_descuento > 100) {
                        $('#error_porcentaje_descuento').html('Error de porcentaje')
                    }
                }
                if (descuento == 2) {
                    descuento = porcentaje_descuento

                    var descuento_temp = document.getElementById("total_descuento_propina").value;

                    total_descuento = parseInt(descuento_temp.replace(/[.]/g, ""))
                    //console.log(total_descuento) esta es la propina 
                    // console.log('El total es '+total) el total de la cuenta 
                    descuento_total = (total - descuento)+total_descuento

                    document.getElementById("total_descuento%").value = descuento.toLocaleString("es-ES")
                    document.getElementById("resultado_total_del_pedido").value = descuento_total.toLocaleString("es-ES")
                }
            }
        </script>

        <script>
            function propina_descuento(propina) {
                var url = document.getElementById("url").value;
                var descuento_propina = document.getElementById("descuento_propina").value;
                var descuento_pesos = document.getElementById("total_descuento%").value;

                var tot = document.getElementById("total_del_pedido").value;

                total = parseFloat(tot.replace(/[.]/g, ""));

                if (descuento_propina == 1) {
                    if (propina <= 100) {
                        porcentaje_propina = (total * propina) / 100
                        document.getElementById("total_descuento_propina").value = porcentaje_propina.toLocaleString()

                        descontar = (total - descuento_pesos) + porcentaje_propina
                        document.getElementById("resultado_total_del_pedido").value = descontar.toLocaleString("es-ES")
                    }
                    if (propina > 100) {
                        $('#error_porcentaje_propina').html('Error de porcentaje')
                    }
                }
                if (descuento_propina == 2) {
                    porcentaje_propina = propina
                    document.getElementById("total_descuento_propina").value = porcentaje_propina.toLocaleString()

                    descuento = (total - descuento_pesos) + parseInt(porcentaje_propina)
                    document.getElementById("resultado_total_del_pedido").value = descuento.toLocaleString("es-ES")
                }
            }
        </script>


        <script>
            function criterio_descuento_pedido() {

                var x = document.getElementById("descuento_porcentaje").value;

                if (x == 1 || x == 2) {
                    document.getElementById("valor_descuento%").disabled = false;
                }


            }
        </script>
        <script>
            function criterio_propina_pedido() {

                var x = document.getElementById("descuento_propina").value;

                if (x == 1 || x == 2) {
                    document.getElementById("valor_descuento_propina").disabled = false;
                }


            }
        </script>

        <script>
            //SOLO NUMEROS
            $(function() {
                $(".validar_descuento").keydown(function(event) {
                    //alert(event.keyCode);
                    if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !== 190 && event.keyCode !== 110 && event.keyCode !== 8 && event.keyCode !== 9) {
                        return false;
                    }
                });
            });
        </script>

        <script>
            var input = document.getElementById('valor_descuento%');

            input.addEventListener('keydown', function(event) {
                const key = event.key; // const {key} = event; ES6+
                if (key === "Backspace") {

                    //alert(key);
                    //return false;
                    $('#error_porcentaje_descuento').html('');
                }
            });
        </script>
        <script>
            var input = document.getElementById('valor_descuento_propina');

            input.addEventListener('keydown', function(event) {
                const key = event.key; // const {key} = event; ES6+
                if (key === "Backspace") {

                    //alert(key);
                    //return false;
                    $('#error_porcentaje_propina').html('');
                }
            });
        </script>

</body>

</html>