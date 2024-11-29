<?php $user_session = session(); ?>
<!doctype html>
<html lang="eS">

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
            <?= $this->include('ventanas_modal_retiro_de_dinero/devolucion') ?>
            <?= $this->include('ventanas_modal_retiro_de_dinero/retiro') ?>
            <?= $this->include('ventanas_modal_retiro_de_dinero/imprimir_retiro') ?>
            <div class="page-body">
                <?= $this->renderSection('content') ?>
                <?= $this->include('ventanas_modal_cliente/creacion_cliente_factura_pos') ?>
                <?= $this->include('ventanas_modal_factura_pos/observacion_general') ?>
                <?= $this->include('ventanas_modal_factura_pos/finalizar_venta') ?>
                <?= $this->include('ventanas_modal_factura_pos/cerrar_venta_con_impuestos') ?>
                <?= $this->include('ventanas_modal_factura_pos/cerrar_venta_sin_impuestos') ?>
                <?= $this->include('ventanas_modal_editar_eliminar_pedido/editar_cantidad_producto_pos') ?>


            </div>
            <?= $this->include('layout/footer') ?>
            <?= $this->include('ventanas_modal_editar_eliminar_pedido/editar_cantidad_producto_factura_pos') ?>

        </div>
        <!-- Libs JS -->
        <!-- Tabler Core -->
        <script src="<?= base_url() ?>/Assets/js/tabler.min.js"></script>
        <!-- JQuery -->
        <script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
        <!-- jQuery-ui -->
        <script src="<?php echo base_url() ?>/Assets/plugin/jquery-ui/jquery-ui.js"></script>
        <!--select2 -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.js"></script>
        <!-- Locales -->
        <script src="<?= base_url() ?>/Assets/script_js/factura_pos/clientes.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/factura_pos/observacion_general.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/factura_pos/finalizar_venta.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/factura_pos/eliminar_producto.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/factura_pos/cliente_autocompletar.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/factura_pos/agregar_cliente_pedido_pos.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/factura_pos/editar_producto_pos.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/factura_pos/devolucion.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/factura_pos/retiro.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/factura_pos/reset_factura.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/factura_pos/impresion_comanda.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/factura_pos/impresion_de_factura.js"></script>

        <script src="<?= base_url() ?>/Assets/script_js/factura_pos/modulo_facturacion.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/factura_pos/imprimir_factura_imp.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/factura_pos/llenar_select.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/factura_pos/imprimir_retiro_de_dinero.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/factura_pos/buscar_por_codigo_de_barras.js"></script>
        <!-- Sweet alert -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>

        <!-- Calendario -->
        <script src="<?= base_url() ?>/Assets/plugin/calendario/jquery-ui-1.12.1.custom/jquery-ui.js"></script>

        <!--Atajos teclado -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/atajos_teclado/shortcut.js"></script>

        <script>
            $("#buscar_producto").autocomplete({
                source: function(request, response) {
                    var url = document.getElementById("url").value;
                    //var lista_precios=document.getElementById("lista_precios").value;
                    //console.log(lista_precios)
                    $.ajax({
                        type: "POST",
                        url: url + "/" + "producto/pedido_pos",
                        data: {
                            term: request.term,
                            extraParams: $('#lista_precios').val()
                        },
                        success: response,
                        dataType: "json",
                    });
                },
            }, {
                minLength: 1,
            }, {
                select: function(event, ui) {
                    $("#buscar_producto").val(ui.item.value);
                    $("#codigointernoproducto").val(ui.item.id_producto);
                    $("#valor_venta_producto").val(ui.item.valor_venta_producto);
                    //$("#cantidad_factura_pos").focus();
                    //document.getElementById("cantidad_factura_pos").focus()
                    //console.log(lista_precios)
                },
            });

            $("#devolucion_producto").autocomplete({
                source: function(request, response) {
                    var url = document.getElementById("url").value;
                    $.ajax({
                        type: "POST",
                        url: url + "/" + "producto/pedido_pos",
                        //data: request,
                        data: {
                            term: request.term,
                            extraParams: $('#lista_precios').val()
                        },
                        success: response,
                        dataType: "json",
                    });
                },
                appendTo: "#devolucion",
            }, {
                minLength: 1,
            }, {
                select: function(event, ui) {
                    $("#devolucion_producto").val(ui.item.value);
                    $("#codigo_producto_devolucion").val(ui.item.id_producto);
                    $("#precio_devolucion").val(ui.item.valor_venta_producto);
                    //$("#cantidad_factura_pos").focus();
                    //document.getElementById("cantidad_factura_pos").focus()

                },
            });
        </script>



        <script>
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
        </script>

        <script>
            function saltar_factura_pos(e, id) {
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
            function cargar_producto_al_pedido() {

                var codigo_interno_producto = document.getElementById("codigointernoproducto").value;
                var cantidad = document.getElementById("cantidad_factura_pos").value;
                var usuario_facturacion = document.getElementById("id_usuario_de_facturacion").value;
                var nota_producto = document.getElementById("nota_producto_pos").value;
                var lista_precios = document.getElementById("lista_precios").value;
                var select_lista_precios = document.getElementById("select_lista_precios").value;

                elemento = document.getElementById("cargar_producto_al_pedido");
                elemento.blur();


                $.ajax({
                    data: {
                        codigo_interno_producto,
                        cantidad,
                        usuario_facturacion,
                        nota_producto,
                        lista_precios,
                        select_lista_precios
                    },
                    url: "<?php echo base_url(); ?>/producto/cargar_producto_al_pedido",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {
                            document.getElementById("codigointernoproducto").value = ""
                            document.getElementById("nota_producto_pos").value = ""
                            document.getElementById("buscar_producto").value = ""
                            //  document.getElementById("valor_venta_producto").value = ""
                            document.getElementById("cantidad_factura_pos").value = 1
                            document.getElementById("buscar_producto").focus();
                            $('#productos_de_pedido_pos').empty();
                            $("#productos_de_pedido_pos").append(resultado.productos);
                            document.getElementById("total_pedido_pos").value = resultado.total
                            document.getElementById("valor_venta_producto").value = ''
                            if (resultado.select_lista_precios == 1) {
                                $("#lista_precios").val('').trigger('change')
                            }
                            if (resultado.select_lista_precios == 0) {
                                document.getElementById("lista_precios").value = 0
                            }
                        }
                        if (resultado.resultado == 2) {
                            $('#productos_de_pedido_pos').empty();
                            $("#productos_de_pedido_pos").append(resultado.productos);
                            document.getElementById("total_pedido_pos").value = resultado.total
                            document.getElementById("codigointernoproducto").value = ""
                            document.getElementById("buscar_producto").value = ""
                            //document.getElementById("valor_venta_producto").value = ""
                            document.getElementById("cantidad_factura_pos").value = 1
                            document.getElementById("nota_producto_pos").value = ""
                            $("#lista_precios").val('').trigger('change')
                            if (resultado.select_lista_precios == 1) {

                                document.getElementById("buscar_producto").focus();

                            }
                            if (resultado.select_lista_precios == 0) {
                                document.getElementById("lista_precios").value = 0
                                document.getElementById("buscar_producto").focus();
                            }
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
            function soloNumerosEnCantidad(e) {
                var key = window.Event ? e.which : e.keyCode;
                return key >= 48 && key <= 57;
            }
        </script>

        <script>
            $(document).ready(function() {
                $('#departamento').val(0);
                recargarLista();
                $('#departamento').change(function() {
                    recargarLista();
                });
            })

            function recargarLista() {
                $.ajax({
                    type: "POST",
                    //url: "http://localhost/agroexito-development/predios/municipios",
                    url: "<?php echo base_url(); ?>/factura_pos/municipios",
                    data: "id_departamento=" + $('#departamento').val(),
                    success: function(r) {
                        $('#municipios').html(r);
                    }
                });
            }
        </script>



        <script>
            var input = document.getElementById('buscar_producto');

            input.onkeydown = function() {
                const key = event.key;
                if (key === "Backspace") {
                    document.getElementById('codigointernoproducto').value = "";
                    $("#error_cliente").html("");
                }
            };
        </script>

        <script>
            var input = document.getElementById('clientes_factura_pos');

            input.onkeydown = function() {
                const key = event.key;
                if (key === "Backspace") {
                    $("#error_cliente").html("");
                }
            };
        </script>

        <script>
            function reset_error() {
                $("#error_fecha_limite").html("");

            }
        </script>


        <script>
            $(document).ready(function() {
                var url = document.getElementById("url").value;
                $('#lista_precios').select2({
                    width: '100%',
                    placeholder: "Lista de precios",
                    language: "es",
                    theme: "bootstrap-5",
                    allowClear: false,
                    minimumResultsForSearch: -1,
                    language: {

                        noResults: function() {

                            return "No hay resultados";
                        },
                        searching: function() {

                            return "Buscando...";
                        }
                    }
                });
            });
        </script>

        <script>
            function tecla_enter(e, codigo) {
                var enterKey = 13;

                if (codigo != '') {
                    if (e.which == enterKey) {
                        var codigo_de_barras = codigo
                        codigo_interno = document.getElementById("codigointernoproducto").value
                        lista_precios = document.getElementById("lista_precios").value
                        if (codigo_interno == '') { // si el codigo interno es vacio se realiza busqueda por codigo de barras 

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
        </script>


        <script>
            $(function() {
                $("#fecha_limite").datepicker({

                });
            });
        </script>


        <script>
            $(document).ready(function() {
                //Para mostrar un modal
                shortcut.add("f8", function() {
                    var url = document.getElementById("url").value;
                    var usuario = document.getElementById("id_usuario_de_facturacion").value;
                    var estado = document.getElementById("estado_pos").value;
                    var fecha_limite = document.getElementById("fecha_limite").value;
                    var nit_cliente = document.getElementById("id_cliente_factura_pos").value;

                    var date1 = new Date(fecha_limite);
                    var date2 = new Date();
                    if (estado == 2) {
                        if (fecha_limite == "") {
                            $("#error_fecha_limite").html("Falta definir una fecha ");
                        } else if (date1 <= date2) {
                            $("#error_fecha_limite").html(
                                "La fecha no puede ser menor o igual a la fecha actual "
                            );
                        }
                        if (nit_cliente != 22222222) {
                            if (date1 > date2) {
                                $.ajax({
                                    data: {
                                        usuario,
                                        estado,
                                        nit_cliente
                                    },
                                    url: url + "/" + "pedido/forma_pago",
                                    type: "POST",
                                    success: function(resultado) {
                                        var resultado = JSON.parse(resultado);

                                        if (resultado.resultado == 1) {
                                            swal
                                                .fire({
                                                    title: "Va a realizar una transacción  " +
                                                        resultado.estado +
                                                        " por un valor de " +
                                                        resultado.valor_total +
                                                        " al señor(a) " +
                                                        resultado.nombres_clientes,
                                                    icon: "info",
                                                    showCancelButton: true,
                                                    confirmButtonText: "Aceptar ",
                                                    confirmButtonColor: "#2AA13D",
                                                    cancelButtonText: "Cancelar",
                                                    cancelButtonColor: "#C13333",
                                                    //reverseButtons: true,
                                                })
                                                .then((result) => {
                                                    if (result.isConfirmed) {
                                                        var url = document.getElementById("url").value;
                                                        $.ajax({
                                                            data: {
                                                                nit_cliente,
                                                                usuario,
                                                                estado
                                                            },
                                                            url: url + "/" + "pedido/facturar_credito",
                                                            type: "POST",
                                                            success: function(resultado) {
                                                                var resultado = JSON.parse(resultado);

                                                                if (resultado.resultado == 1) {
                                                                    $("#estado_pos").val("1"); // Select the option with a value of '1'
                                                                    $("#estado_pos").trigger("change");

                                                                    document.getElementById(
                                                                        "id_cliente_factura_pos"
                                                                    ).value = 22222222;
                                                                    document.getElementById(
                                                                        "clientes_factura_pos"
                                                                    ).value = "CLIENTE GENERAL";



                                                                    // crea un nuevo objeto `Date`
                                                                    var today = new Date();

                                                                    // `getDate()` devuelve el día del mes (del 1 al 31)
                                                                    var day = today.getDate();

                                                                    // `getMonth()` devuelve el mes (de 0 a 11)
                                                                    var month = today.getMonth() + 1;

                                                                    // `getFullYear()` devuelve el año completo
                                                                    var year = today.getFullYear();

                                                                    // muestra la fecha de hoy en formato `MM/DD/YYYY`
                                                                    // console.log(`${month}/${day}/${year}`);

                                                                    document.getElementById("fecha_limite").value = `${year}/${month}/${day}`;
                                                                    document.getElementById("total_pedido_pos").value = 0;
                                                                    //$("#productos_de_pedido_pos").empty();
                                                                    $("#tablaProductos").html(resultado.tabla);


                                                                    document.getElementById("buscar_producto").autofocus;
                                                                    Swal.fire({
                                                                        icon: "success",
                                                                        title: "Factura crédito grabada con éxito",
                                                                        confirmButtonText: "Imprimir factura crédito",
                                                                        confirmButtonColor: "#2AA13D",
                                                                        showDenyButton: true,
                                                                        denyButtonText: `No imprimir`,
                                                                        denyButtonColor: "#C13333",
                                                                        reverseButtons: true,
                                                                    }).then((result) => {
                                                                        /* Read more about isConfirmed, isDenied below */
                                                                        if (result.isConfirmed) {
                                                                            (numero_de_factura = resultado.id_factura),
                                                                            $.ajax({
                                                                                data: {
                                                                                    numero_de_factura,
                                                                                },
                                                                                url: url + "/" + "factura_pos/imprimir_factura",
                                                                                type: "POST",
                                                                                success: function(resultado) {
                                                                                    var resultado = JSON.parse(resultado);

                                                                                    if (resultado.resultado == 0) {
                                                                                        $("#creacion_cliente_factura_pos").modal(
                                                                                            "hide"
                                                                                        );
                                                                                        Swal.fire({
                                                                                            icon: "success",
                                                                                            title: "Cliente agregado",
                                                                                        });
                                                                                    }
                                                                                    if (resultado.resultado == 1) {
                                                                                        const Toast = Swal.mixin({
                                                                                            toast: true,
                                                                                            position: 'top-end',
                                                                                            showConfirmButton: false,
                                                                                            timer: 3000,
                                                                                            timerProgressBar: true,
                                                                                            didOpen: (toast) => {
                                                                                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                                                                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                                                                                            }
                                                                                        })

                                                                                        Toast.fire({
                                                                                            icon: 'success',
                                                                                            title: 'Impresión de factura correcta'
                                                                                        })
                                                                                    }
                                                                                },
                                                                            });
                                                                        } else if (result.isDenied) {
                                                                            Swal.fire({
                                                                                icon: "info",
                                                                                confirmButtonText: "Aceptar",
                                                                                confirmButtonColor: "#2AA13D",
                                                                                title: "No se imprime la factura crédito",
                                                                            });
                                                                        }
                                                                    });
                                                                }
                                                                if (resultado.resultado == 0) {
                                                                    Swal.fire({
                                                                        icon: "warning",
                                                                        title: "No se pudo eliminar",
                                                                        confirmButtonText: "ACEPTAR",
                                                                    });
                                                                }
                                                            },
                                                        });
                                                    } else if (result.isCancel) {
                                                        alert("cancelacion de pedido222");
                                                    }
                                                });
                                        }
                                    },
                                });
                            }
                        } else if (nit_cliente == 22222222) {
                            $("#error_cliente").html(
                                "Para factura crédito no puede ser cliente general"
                            );
                        }
                    } else if (estado == 1) {
                        var url = document.getElementById("url").value;

                        $.ajax({
                            data: {
                                usuario
                            },
                            url: url + "/" + "pedido/valor_pedido",
                            type: "POST",
                            success: function(resultado) {
                                var resultado = JSON.parse(resultado);
                                shortcut.remove('f8');
                                if (resultado.resultado == 1) {
                                    document.getElementById("valor_a_pagar").value =
                                        resultado.valor_total;
                                    document.getElementById("base_impuestos_pos").value = resultado.base;
                                    document.getElementById("impuesto_iva_pos").value = resultado.iva;
                                    document.getElementById("impuesto_al_consumo_pos").value =resultado.ico;
                                    
                                    $('#pago_con_efectivo').select()

                                    myModal = new bootstrap.Modal(
                                        document.getElementById("finalizar_venta"), {}
                                    );
                                    myModal.show();
                                }
                                if (resultado.resultado == 2) {
                                    //$("#base_impuestos_pos").hide();
                                    shortcut.remove('f8');
                                    document.getElementById("impuestos_pos").style.display = "none";
                                    document.getElementById("valor_a_pagar").value =resultado.valor_total;
                                    $('#pago_con_efectivo').select()
                                    
                                    myModal = new bootstrap.Modal(
                                        document.getElementById("finalizar_venta"), {}
                                    );
                                    myModal.show();
                                }

                                if (resultado.resultado == 3) {
                                    shortcut.remove('f8');
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.addEventListener('mouseenter', Swal.stopTimer)
                                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                                        }
                                    })

                                    Toast.fire({
                                        icon: 'info',
                                        title: 'No hay productos para facturar'
                                    })
                                }
                                if (resultado.resultado == 4) {
                                    shortcut.remove('f8');
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.addEventListener('mouseenter', Swal.stopTimer)
                                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                                        }
                                    })

                                    Toast.fire({
                                        icon: 'error',
                                        title: 'No hay apertura de caja '
                                    })
                                }
                            },
                        });
                    }
                });
            });

            shortcut.add("f4", function() {

                var url = document.getElementById("url").value;
                var id_usuario = document.getElementById("id_usuario_de_facturacion").value;

                $.ajax({
                    data: {
                        id_usuario,
                    },
                    url: url + "/" + "producto/usuario_pedido",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 0) {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                icon: 'info',
                                title: 'No hay pedido para cagar una nota '
                            })
                        }
                        if (resultado.resultado == 1) {
                            document.getElementById("nota_de_producto").value = resultado.observacion_general
                            myModal = new bootstrap.Modal(
                                document.getElementById("observacion_general"), {}
                            );
                            myModal.show();
                        }
                    },
                });
            })


            shortcut.add("f1", function() {

                myModal = new bootstrap.Modal(
                    document.getElementById("retiro"), {}
                );
                myModal.show();
            })

            shortcut.add("f5", function() {

                myModal = new bootstrap.Modal(
                    document.getElementById("devolucion"), {}
                );
                myModal.show();
            })

            shortcut.add("f10", function() {
                var usuario = document.getElementById("id_usuario").value;
                var url = document.getElementById("url").value;
                swal
                    .fire({
                        title: "Seguro de resetear la factura ",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Eliminar ",
                        confirmButtonColor: "#2AA13D",
                        cancelButtonText: "Cancelar",
                        cancelButtonColor: "#C13333",
                        //reverseButtons: true,
                    })
                    .then((result) => {
                        $.ajax({
                            data: {
                                usuario,
                            },
                            url: url + "/" + "factura_pos/reset_factura",
                            type: "POST",
                            success: function(resultado) {
                                var resultado = JSON.parse(resultado);

                                if (resultado.resultado == 1) {
                                    shortcut.remove('f10');
                                    $("#productos_de_pedido_pos").empty();
                                    $("#productos_de_pedido_pos").append(resultado.productos);
                                    document.getElementById("total_pedido_pos").value = 0;
                                    Swal.fire({
                                        icon: "success",
                                        title: "Haz reseteado la facturación ",
                                        confirmButtonText: "Aceptar",
                                        confirmButtonColor: "#2AA13D",
                                    });
                                }
                            },
                        });
                    });
            })
        </script>

        <script>
            function total() {
                var precio = document.getElementById("precio_devolucion").value;
                var cantidad = document.getElementById("cantidad_devolucion").value;
                var precioFormat = precio.replace(/[.]/g, "");

                res = parseInt(precioFormat) * parseInt(cantidad);

                resultado = res.toLocaleString();
                $("#total_devolucion").val(resultado);

            }
        </script>

        <script>
            $(document).ready(function() {
                let button = document.querySelector("#btn_retiro");
                button.disabled = false;
            });
        </script>


        <script>
            function imprimir_prefactura() {
                var url = document.getElementById("url").value;
                var usuario = document.getElementById("id_usuario_de_facturacion").value;
                $.ajax({
                    data: {
                        usuario
                    },
                    url: url + "/" + "comanda/directa",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 0) {


                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                icon: 'info',
                                title: 'No hay productos para imprimir'
                            })

                            $("#edicion_de_apertura_de_caja").modal("hide");
                            $("#valor_modificado_apertura").html(resultado.valor_apertura);
                            $("#nuevo_saldo").html(resultado.saldo);
                            $("#cambiar_valor_apertura").val(resultado.val_apertura);

                        }
                        if (resultado.resultado == 1) {


                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                icon: 'success',
                                title: 'Impresión de prefactura'
                            })

                            $("#edicion_de_apertura_de_caja").modal("hide");
                            $("#valor_modificado_apertura").html(resultado.valor_apertura);
                            $("#nuevo_saldo").html(resultado.saldo);
                            $("#cambiar_valor_apertura").val(resultado.val_apertura);

                        }
                    },
                });
            }
        </script>


        <script>
            function descuento_de_factura(porcentaje_descuento) {
                var url = document.getElementById("url").value;
                var descuento = document.getElementById("descuento_porcentaje").value;
                var desc_propina = document.getElementById("total_descuento_propina").value;
                let propina = parseInt(desc_propina.replace(/[.]/g, ""))

                var tot = document.getElementById("total_pedido_pos").value;
                total = parseFloat(tot.replace(/[.]/g, ""));



                if (descuento == 1) {

                    if (porcentaje_descuento <= 100) {

                        porcentaje = (total * porcentaje_descuento) / 100
                        let descontar = total - porcentaje
                        document.getElementById("total_descuento%").value = porcentaje.toLocaleString("es-ES")
                        descuento_total = (total - porcentaje) + propina
                        document.getElementById("gran_total_pedido_pos").value = descuento_total.toLocaleString("es-ES")
                    } else if (porcentaje_descuento > 100) {
                        $('#error_descuento').html('Error porcentaje ')
                    }

                }
                if (descuento == 2) {
                    descuento = porcentaje_descuento
                    document.getElementById("total_descuento%").value = descuento.toLocaleString("es-ES")
                    totalizado = (total - porcentaje_descuento) + propina
                    document.getElementById("gran_total_pedido_pos").value = totalizado.toLocaleString("es-ES")
                }
            }
        </script>

        <script>
            function propina_descuento(propina) {
                var url = document.getElementById("url").value;
                var descuento_propina = document.getElementById("descuento_propina").value;
                var descuento_plata = document.getElementById("total_descuento%").value;
                let descuento_pesos = parseInt(descuento_plata.replace(/[.]/g, ""))

                var tot = document.getElementById("total_pedido_pos").value;

                total = parseFloat(tot.replace(/[.]/g, ""));

                if (descuento_propina == 1) {
                    if (propina <= 100) {
                        porcentaje_propina = (total * propina) / 100
                        document.getElementById("total_descuento_propina").value = porcentaje_propina.toLocaleString()

                        descuent = (total - descuento_pesos) + porcentaje_propina

                        document.getElementById("gran_total_pedido_pos").value = descuent.toLocaleString("es-ES")
                    } else if (propina > 100) {
                        $('#error_propina').html('Error porcentaje ')
                    }
                }
                if (descuento_propina == 2) {
                    porcentaje_propina = propina
                    document.getElementById("total_descuento_propina").value = porcentaje_propina.toLocaleString()

                    descuento_de_propina = (total - descuento_pesos) + parseInt(porcentaje_propina);
                    document.getElementById("gran_total_pedido_pos").value = descuento_de_propina.toLocaleString("es-ES")
                }
            }
        </script>


        <script>
            function cambio(efectivo) {

                let total_factura = document.getElementById("total_factura").value;
                let temp_factura = total_factura.replace(/[.]/g, "");

                let temp_efect = efectivo.replace(/[.]/g, "");
                let temp_efectivo = parseInt(temp_efect)

                /*   let temp_transaccion = document.getElementById("pago_con_transaccion").value;
            
                    let temp_transa = temp_transaccion.replace(/[.]/g, "");
                    let transaccion = parseInt(temp_transa)
                
                console.log(transaccion) */

                let cambio = temp_efectivo - temp_factura
                if (cambio >= 0) {
                    document.getElementById("cambio_del_pago").style.color = "black";
                    document.getElementById("cambio_del_pago").value = cambio.toLocaleString()

                } else if (cambio < 0) {
                    document.getElementById("cambio_del_pago").style.color = "red";
                    document.getElementById("cambio_del_pago").value = cambio.toLocaleString()
                }

            }
        </script>

        <script>
            //SOLO NUMEROS
            $(function() {
                $(".validar").keydown(function(event) {
                    //alert(event.keyCode);
                    if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !== 190 && event.keyCode !== 110 && event.keyCode !== 8 && event.keyCode !== 9) {
                        return false;
                    }
                });
            });
        </script>

        <script>
            //SOLO NUMEROS
            $(function() {
                $(".validar_propina").keydown(function(event) {
                    //alert(event.keyCode);
                    if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !== 190 && event.keyCode !== 110 && event.keyCode !== 8 && event.keyCode !== 9) {
                        return false;
                    }
                });
            });
        </script>


</body>

</html>