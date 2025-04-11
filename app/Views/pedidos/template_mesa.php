<?php $user_session = session(); ?>
<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <!-- CSS files -->
    <link href="<?= base_url() ?>/public/css/tabler.min.css" rel="stylesheet" />
    <link href="<?= base_url() ?>/Assets/css/tabler-payments.min.css?1684106062" rel="stylesheet" />
    <link href="<?= base_url() ?>/Assets/css/mesas.css" rel="stylesheet" />

    <!-- Jquery date picker  -->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/Assets/plugin/calendario/jquery-ui-1.12.1.custom/jquery-ui.css">
    <!-- Jquery-ui -->
    <link href="<?php echo base_url() ?>/Assets/plugin/jquery-ui/jquery-ui.css" rel="stylesheet">
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/Assets/img/favicon.png">
    <!-- Select 2 -->
    <link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        /* CSS para desactivar el resaltado de selección de texto */
        body {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
    </style>
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
    <style>
        /* Estilo personalizado para reducir el espaciado entre las líneas */
        .custom-hr {
            margin: 5px 0;
            /* Ajusta el espaciado vertical según tus preferencias */
        }

        /* Estilo personalizado para alinear a la derecha */
        .text-right-custom {
            text-align: right;
        }
    </style>

    <style>
        .card_mesas {
            width: 170px;
            /* Puedes ajustar este valor según tus necesidades */
            padding: 10px;
            /* Ajusta el relleno según lo necesario */
            margin-bottom: 0.5 px;
            /* Ajusta el margen según lo necesario */
            border: 1px solid #ccc;
            /* Agrega bordes para una apariencia de tarjeta */

        }
    </style>
    <style>
        .form-control-rounded {
            width: 100%;
        }

        .card-title {
            width: 100%;
            /* Asegura que el contenedor tiene ancho completo */
        }
    </style>


    <style>
        /* Aplicar a un input de tipo number específico */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Para navegadores que no son WebKit (como Firefox) */
        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>


    <style>
        .div {

            width: 100px;
            height: 10px;
            padding-right: 5px;
            /* Pequeño relleno entre los divs */
            box-sizing: border-box;
            /* Asegura que el relleno no afecte las dimensiones totales */
        }
    </style>



</head>

<body>
    <script src="<?= base_url() ?>/public/js/demo-theme.min.js?1684106062"></script>
    <?= $this->renderSection('content') ?>



    <?= $this->include('pedidos/modal_meseros') ?>
    <?= $this->include('pedidos/modal_todas_las_mesas') ?>
    <?= $this->include('pedidos/modal_pago_parcial') ?>
    <?= $this->include('pedidos/modal_agregar_nota') ?>
    <?= $this->include('pedidos/modal_finaliza_venta') ?>
    <?= $this->include('pedidos/modal_retiro_dinero') ?>
    <?= $this->include('ventanas_modal_pedido/cambiar_de_mesa') ?>
    <?= $this->include('ventanas_modal_retiro_de_dinero/imprimir_retiro') ?>
    <?= $this->include('ventanas_modal_retiro_de_dinero/devolucion') ?>
    <?= $this->include('pedidos/modal_trasmision_electronica') ?>

    <?= $this->include('pedidos/crear_cliente') ?>
    <?= $this->include('toma_pedidos/offcanva_mesas') ?>
    <?= $this->include('toma_pedidos/offcanva_productos') ?> <!-- Modal -->
    <?= $this->include('pedidos/modal_atributos') ?> <!-- Modal -->
    <div class="modal fade" id="editar_cantidades" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Editar cantidades de producto </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="info_producto"></div>
                </div>
                <div class="modal-footer text-end ">
                    <button type="button" class="btn btn-outline-success" onclick="actualizacion_cantidades()">Confirmar</button>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar </button>
                </div>
            </div>
        </div>
    </div>






    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="<?= base_url() ?>/public/js/tabler.min.js?1684106062" defer></script>
    <script src="<?= base_url() ?>/public/js/demo.min.js?1684106062" defer></script>
    <!--jQuery -->
    <script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
    <!--select2 -->
    <script src="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.js"></script>

    <!-- jQuery-ui -->
    <script src="<?php echo base_url() ?>/Assets/plugin/jquery-ui/jquery-ui.js"></script>
    <!-- Sweet alert -->
    <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
    <!-- Locales -->
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/actualizar_nota.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/agregar_nota.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_start.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/prefactura.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/pedido.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/productos_categoria.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/limpiar_todo.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/agregar_al_pedido.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/producto_autocomplete.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/pedido_mesa.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/imprimir_comanda.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/insertar_nota.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/limpiar_campos.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/cambiar_mesas.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/intercambio_mesas.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/todas_las_mesas.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/ocultar_menu.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/mostrar_menu.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/mesas_salon.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/get_mesas.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/eliminar_producto.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/actualizar_cantidades.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/eliminar_pedido.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/pago_parcial.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/eliminar_cantidades.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/retiro_efectivo.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/imprimir_retiro_de_dinero.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/cambio.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/retiro_dinero.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/total.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/finalizar_venta.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/saltar_factura_pos.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/detener_propagacion.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/actualizar_partir_factura.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/factura_electronica.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/pagar.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/habilitarBotonPago.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/cancelar_pagar.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/factura_pos/devolucion.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/calcular_propina.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/total_pedido.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/criterio_propina.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/buscar_por_codigo_de_barras_devolucion.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/actualizar_producto_cantidad.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/calculo_propina.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/pagar_parcial.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/cancelar_pago_parcial.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/restar_partir_factura.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/pago_efectivo.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/cambio_transaccion.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/actualizar_cantidad_partir_factura.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/meseros.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/select_2.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/nueva_factura.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/cambio_precio.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/abrir_cajon.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/header_pedido.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/header_mesa.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/impresion_factura_electronica.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_centrado.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/agregarProductoPedido.js"></script>

    <script>
        async function mesasConPedido() {
            try {
                const response = await fetch('<?= base_url('mesas/index') ?>');
                if (!response.ok) {
                    throw new Error('Error al obtener los datos');
                }
                const datos = await response.json();

                document.getElementById('mesas_all').innerHTML = datos.mesas
            } catch (error) {
                console.error('Hubo un problema:', error);
            }
        }

        // Llamar a la función
        mesasConPedido();
    </script>



    <script>
        function cambiarValorfrmPago(idFrmPago, valor) {


            var idFrmPago = idFrmPago;

            const dynamicId = idFrmPago;

            const input = document.querySelector("#frmPago" + dynamicId);
            // Verificar si se encontró el input
            if (!input) {
                console.error("Input con ID '" + id + "' no encontrado.");
                return;
            }
            // Definir la función para formatear el número
            function format(n) {
                // Elimina cualquier carácter que no sea un número
                n = n.replace(/\D/g, "");
                // Formatea el número
                return n === "" ? n : parseFloat(n).toLocaleString('es-CO');
            }
            // Agregar el evento "input" al input
            input.addEventListener("input", (e) => {
                const element = e.target;
                const value = element.value;
                element.value = format(value);
            });


            let url = document.getElementById("url").value;
            let id_mesa = document.getElementById("id_mesa_pedido").value;

            $.ajax({
                data: {
                    id_mesa
                },
                url: url + "/" + "reportes/productos_pedido",
                type: "get",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        let mesas = resultado.mesas;



                        // Asumiendo que tienes una tabla con un cuerpo de tabla con el id "tabla-mesas"

                    }
                },
            });


        }
    </script>


    <script>
        function actualizacion_productos_pedido() {
            let url = document.getElementById("url").value;
            let id_mesa = document.getElementById("id_mesa_pedido").value;

            if (id_mesa != "") {

                $.ajax({
                    data: {
                        id_mesa
                    },
                    url: url + "/" + "reportes/productos_pedido",
                    type: "post",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);
                        if (resultado.resultado == 1) {
                            $('#mesa_productos').html(resultado.productos_pedido)
                            $('#valor_pedido').html(resultado.total_pedido)
                            $('#subtotal_pedido').val(resultado.sub_total)
                            $('#propina_del_pedido').val(resultado.propina)
                            // Aquí puedes actualizar la tabla u otros elementos
                        }
                    },
                });
            }
        }

        // Ejecutar cada segundo solo si id_mesa tiene valor
        /*  setInterval(function() {
             let id_mesa = document.getElementById("id_mesa_pedido").value;
             if (id_mesa != "") {
                 actualizacion_productos_pedido();
             }
         }, 3000);  */
    </script>


    <script>
        function actualizar_mesas_pedido() {
            let url = document.getElementById("url").value;
            $.ajax({

                url: url + "/" + "reportes/actualizacion_estado_mesas",
                type: "get",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        let mesas = resultado.mesas;

                        mesas.forEach(function(mesa) {

                            // Aseguramos que los valores sean números
                            let total = parseFloat(mesa.valor_total) + parseFloat(mesa.propina);

                            // Formateamos el total a moneda con separador de miles y 2 decimales
                            let totalFormateado = total.toLocaleString('es-CO', {
                                style: 'currency',
                                currency: 'COP',
                                minimumFractionDigits: 0
                            });

                            let card = `
    <div class="cursor-pointer card card_mesas text-white bg-red-lt" onclick="pedido_mesa('${mesa.fk_mesa}', '${mesa.nombre}')" style="height: auto;">
        <div class="row">
            <div class="col-3">
                <span class="avatar">
                    <img src="${url}/Assets/img/ocupada.png" width="110" height="32" alt="${mesa.nombre}" class="navbar-brand-image">
                </span>
            </div>
            <div class="col">
                <div class="text-center">
                    <strong style="font-size: 12px;">${mesa.nombre}</strong>
                </div>
                <div class="text-center"><strong style="font-size: 12px;">${totalFormateado}</strong></div>
                <div class="text-center"><strong style="font-size: 12px; height: 1em; overflow: hidden;">${mesa.nombresusuario_sistema.substr(0, 10)}...</strong></div>
            </div>
        </div>
    </div>`;

                            $('#UpdateMesa' + mesa.fk_mesa).html(card);
                        });


                        // Asumiendo que tienes una tabla con un cuerpo de tabla con el id "tabla-mesas"

                    }
                },
            });
        }
        setInterval(actualizar_mesas_pedido, 1000);
    </script>




    <script>
        function buscar_mesa_salon(id) {

            let url = document.getElementById("url").value;

            $.ajax({
                data: {
                    id
                },
                url: url + "/" + "pedidos/mesas_de_salon",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    // Aquí puedes manejar el resultado como desees
                    var tarjetas = document.querySelectorAll('.card-link'); // Seleccionar todas las tarjetas

                    tarjetas.forEach(function(tarjeta) {
                        tarjeta.classList.remove('bg-indigo-lt'); // Quitar la clase de todas las tarjetas
                    });
                    $('#mesas_all').html(resultado.mesas)
                    var tarjetaSeleccionada = document.getElementById('card' + resultado.id);
                    tarjetaSeleccionada.classList.add('bg-indigo-lt');
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", status, error);
                }
            });

        }
    </script>


    <script>
        function total_venta_electronicas() {
            let url = document.getElementById("url").value;
            $.ajax({
                url: url + "/" + "reportes/total_ventas_electronicas",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    // Aquí puedes manejar el resultado como desees
                    $('#ventas_electronicas').html('Total ventas electrónicas: ' + resultado.ventas_electronicas)
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", status, error);
                }
            });
        }

        // Ejecuta la función total_venta_electronicas cada 5 segundos
        setInterval(total_venta_electronicas, 5000); // 5000 ms = 5 segundos
    </script>


    <script>
        $(document).ready(function() {
            var autocomplete = function() {
                // Tu función de autocompletado aquí
                console.log("Autocompletado ejecutado");
            };
            let url = document.getElementById("url").value;

            let id_usuario = document.getElementById("id_usuario").value;
            let mesero = document.getElementById("mesero").value;


            $('#producto').on('keypress', function(e) {
                if (e.which === 13) { // Si se pulsa Enter
                    e.preventDefault();

                    let id_mesa = document.getElementById("id_mesa_pedido").value;
                    var codigo = document.getElementById("producto").value;
                    if (id_mesa.trim() === "") {
                        $('#error_producto').html('No hay venta seleccionada ')
                    } else if (id_mesa.trim() !== "") {
                        $.ajax({
                            data: {
                                codigo,
                                id_mesa,
                                id_usuario,
                                mesero
                            },
                            url: url + "/" + "pre_factura/buscar_por_codigo",
                            type: "POST",
                            success: function(resultado) {
                                var resultado = JSON.parse(resultado);
                                if (resultado.resultado == 1) {

                                    $('#producto').val('');
                                    $('#mesa_productos').html(resultado.productos_pedido)
                                    $('#valor_pedido').html(resultado.total_pedido)
                                    $('#mesa_pedido').html(resultado.nombre_mesa)
                                    $('#subtotal_pedido').val(resultado.total_pedido)
                                    $('#id_mesa_pedido').val(resultado.id_mesa)
                                    $('#estado_mesa').val(resultado.estado)
                                    $("#producto").autocomplete("close");

                                    //$('#input' + resultado.id).select()
                                    $('#producto').focus();

                                    if (resultado.estado == 1) {
                                        header_mesa()
                                    }

                                }
                                if (resultado.resultado == 0) {
                                    $('error_producto').html('No hay coincidencias')
                                    $("#producto").autocomplete("close");

                                }
                            },
                        });
                    }
                } else {
                    let id_mesa = document.getElementById("id_mesa_pedido").value;
                    if (id_mesa.trim() === "") {
                        $('#error_producto').html('No hay venta seleccionada ')
                    } else if (id_mesa.trim() !== "") {
                        $("#producto").autocomplete({
                            source: function(request, response) {

                                $.ajax({
                                    type: "POST",
                                    url: url + "/" + "producto/pedido",
                                    data: request,
                                    success: response,
                                    dataType: "json",
                                });
                            },
                        }, {
                            minLength: 1,
                        }, {
                            select: function(event, ui) {


                                let url = document.getElementById("url").value;
                                let id_mesa = document.getElementById("id_mesa_pedido").value;
                                let id_usuario = document.getElementById("id_usuario").value;
                                let mesero = document.getElementById("mesero").value;
                                let id_producto = ui.item.id_producto;
                                let kit = ui.item.kit;

                                //if (kit == 'f') {

                                $.ajax({
                                    data: {
                                        id_producto,
                                        id_mesa,
                                        id_usuario,
                                        mesero
                                    },
                                    url: url + "/" + "pedidos/agregar_producto",
                                    type: "POST",
                                    success: function(resultado) {
                                        var resultado = JSON.parse(resultado);
                                        if (resultado.resultado == 1) {

                                            $('#producto').val('');
                                            $('#mesa_productos').html(resultado.productos_pedido)
                                            $('#valor_pedido').html(resultado.total_pedido)
                                            $('#subtotal_pedido').val(resultado.total_pedido)
                                            $('#id_mesa_pedido').val(resultado.id_mesa)

                                            if (resultado.estado == 1) {
                                                $('#mesa_pedido').html('Ventas de mostrador ')
                                                //$('#input' + resultado.id).select()
                                                $('#producto').focus();

                                            }
                                        }
                                    },
                                });
                                /* } else if (kit === "t") {
                                    id_producto
                                    async function cargarInsumos() {
                                        try {
                                            let response = await fetch('<?= base_url('producto/getAtributos') ?>', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json'
                                                },
                                                body: JSON.stringify({
                                                    id_producto: id_producto,
                                                })
                                            });

                                            if (!response.ok) {
                                                throw new Error(`Error en la respuesta: ${response.status} - ${response.statusText}`);
                                            }

                                            let data = await response.json(); // Aquí no debería dar error

                                            if (data.response=="success") {
                                                //document.getElementById('tbodyInsumos').innerHTML = data.productos;
                                                document.getElementById('asigCompo').innerHTML = data.atributos;
                                                $("#modalAtributos").modal("show");
                                                
                                            } else {
                                                console.warn("La respuesta no contiene 'productos'.", data);
                                            }

                                        } catch (error) {
                                            console.error("Error en la petición:", error);
                                        }
                                    }

                                    // Llamar a la función
                                    cargarInsumos();

                                } */

                            },
                        });
                    }
                }
            });

        });
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let url = document.getElementById("url").value;
            $.ajax({
                url: url + "/" + "eventos/validar_venta_directa",
                type: "GET",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {
                        // Aquí puedes agregar lo que necesitas hacer si resultado.resultado es 1
                        $('#mesa_productos').html(resultado.productos)
                        $('#id_mesa_pedido').val(resultado.id_mesa)
                        $('#valor_pedido').html(resultado.total)
                        $('#valor_total_a_pagar').val(resultado.total)
                        $('#subtotal_pedido').val(resultado.sub_total)
                        $('#propina_del_pedido').val(resultado.propina)
                        $('#estado_mesa').val(resultado.estado)
                        $('#mesa_pedido').html(resultado.nombre_mesa)

                        if (resultado.estado == 1) {
                            const cardHeader = document.getElementById('myCardHeader');
                            cardHeader.classList.add('border-1', 'bg-indigo-lt');
                            const img = document.getElementById('img_ventas_directas');
                            img.style.display = 'block';

                            const mesa = document.getElementById('mesa_pedido');
                            mesa.style.display = 'none';

                            const pedido = document.getElementById('pedido_mesa');
                            const mesero = document.getElementById('nombre_mesero');
                            if (pedido) {
                                pedido.textContent = 'VENTAS DE MOSTRADOR';
                            }

                            if (mesero) {
                                mesero.style.display = 'none';

                            }

                        }


                    }
                },
            });
        });
    </script>

    <script>
        function cambiar_valor_precio(id_producto) {


            var id_producto_pedido = id_producto;


            //cambio_precio(url, valor, id_producto_pedido);

            const dynamicId = id_producto;
            // formatNumber(dynamicId);


            const input = document.querySelector("#input" + dynamicId);
            // Verificar si se encontró el input
            if (!input) {
                console.error("Input con ID '" + id + "' no encontrado.");
                return;
            }
            // Definir la función para formatear el número
            function format(n) {
                // Elimina cualquier carácter que no sea un número
                n = n.replace(/\D/g, "");
                // Formatea el número
                return n === "" ? n : parseFloat(n).toLocaleString('es-CO');
            }
            // Agregar el evento "input" al input
            input.addEventListener("input", (e) => {
                const element = e.target;
                const value = element.value;
                element.value = format(value);
            });


        }
    </script>



    <script>
        function cambiar_precio(valor, id_producto) {

            var url = document.getElementById("url").value;
            var id_producto_pedido = id_producto;

            valor = valor.trim() === '' ? 0 : parseInt(valor.replace(/\./g, ''));

            //cambio_precio(url, valor, id_producto_pedido);



            const dynamicId = id_producto;
            // formatNumber(dynamicId);


            const input = document.querySelector("#input" + dynamicId);
            // Verificar si se encontró el input
            if (!input) {
                console.error("Input con ID '" + id + "' no encontrado.");
                return;
            }
            // Definir la función para formatear el número
            function format(n) {
                // Elimina cualquier carácter que no sea un número
                n = n.replace(/\D/g, "");
                // Formatea el número
                return n === "" ? n : parseFloat(n).toLocaleString('es-CO');
            }
            // Agregar el evento "input" al input
            input.addEventListener("input", (e) => {
                const element = e.target;
                const value = element.value;
                element.value = format(value);
            });


            $.ajax({
                data: {
                    valor,
                    id_producto_pedido
                },
                url: url +
                    "/" +
                    "eventos/editar_precio_producto",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        var precioConSeparador = resultado.precio_producto.toLocaleString();

                        // Asigna el valor con separador de miles al elemento con id "descuento_manual"
                        $('#total_producto' + resultado.id).html(resultado.total_producto);
                        //$('#input' + resultado.id).val(resultado.precio_producto);
                        $('#valor_pedido').html(resultado.total_pedido);
                        $('#mesa_productos').html(resultado.productos_pedido);



                    }
                },
            });
        }
    </script>


    <script>
        $(document).ready(function() {
            // Resaltar el input al hacer clic
            $(".cantidad-input").click(function() {
                $(this).select(); // Selecciona todo el texto dentro del input
            });
        });
    </script>


    <script>
        function incrementarCantidad(event, id) {
            event.preventDefault();
            let cantidad = document.getElementById(id).value;

            if (cantidad !== "") {
                let nueva_cantidad = parseInt(cantidad);
                document.getElementById(id).value = nueva_cantidad + 1; // Incrementar en 1 la cantidad
            }
        }



        function decrementarCantidad(event, id) {
            event.preventDefault();
            let cantidad = document.getElementById(id).value;

            if (cantidad !== "" && parseInt(cantidad) > 1) {
                let nueva_cantidad = parseInt(cantidad) - 1;
                document.getElementById(id).value = nueva_cantidad;
            }
        }
    </script>






    <script>
        function agregar_al_pedido_celular(id_producto, id_input) {
            let url = document.getElementById("url").value;
            let id_mesa = document.getElementById("id_mesa_pedido").value;
            let id_usuario = document.getElementById("id_usuario").value;
            let mesero = document.getElementById("mesero").value;
            let cantidad = document.getElementById(id_input).value;

            console.log(cantidad)

            if (cantidad === undefined || isNaN(cantidad) || cantidad === "") {
                cantidad = 1;
            } else {
                cantidad = Number(cantidad);
            }



            //let cantidad = 1;

            $.ajax({
                data: {
                    id_producto,
                    id_mesa,
                    id_usuario,
                    mesero,
                    cantidad,
                    id_input
                },
                url: url + "/" + "pedidos/agregar_producto_celular",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-start',
                            showConfirmButton: false,
                            timer: 900,
                            timerProgressBar: false,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseleave', Swal.resumeTimer);
                            }
                        });

                        Toast.fire({
                            icon: 'success',
                            title: 'Agregado'
                        });

                        $('#mesa_productos').html(resultado.productos_pedido)
                        $('#valor_pedido').html(resultado.total_pedido)
                        $('#val_pedido').html(resultado.total_pedido)
                        $('#pedido_mesa').html('Pedido: ' + resultado.numero_pedido)
                        $('#subtotal_pedido').val(resultado.total_pedido)
                        $('#input_cantidad').val(1)
                        $('#' + resultado.id).val(1)
                        $('#producto').val('')
                        //$('#productos_categoria').empty()
                        //$('#canva_producto').show()
                    }
                },
            });
        }
    </script>






    <script>
        function actualizar_propina(valor_propina) {

            var url = document.getElementById("url").value;
            let id_mesa = document.getElementById("id_mesa_pedido").value;

            $.ajax({
                data: {
                    id_mesa,
                    valor_propina
                },
                url: url + "/" + "eventos/actualizar_propina",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {



                        sweet_alert_start('success', 'Propina actualizada  ');


                    }
                },
            });

        }
    </script>


    <script>
        function borrar_propina() {
            var url = document.getElementById("url").value;
            let id_mesa = document.getElementById("id_mesa_pedido").value;
            let criterio_propina = document.getElementById("criterio_propina_final").value;

            if (criterio_propina == 1) {
                $.ajax({
                    data: {
                        id_mesa,
                    },
                    url: url + "/" + "eventos/borrar_propina",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);
                        if (resultado.resultado == 1) {

                            $('#propina_pesos').val(0)
                            $('#propina_del_pedido').val(0)
                            $('#propina_pesos_final').val(0)
                            $('#total_propina').val(0)
                            $('#valor_total_a_pagar').val(resultado.total_sin_formato)
                            //$('#efectivo').val(resultado.total)
                            $('#total_pedido').html("Valor pago: " + resultado.total)
                            $('#valor_pedido').html('$' + resultado.total)

                            sweet_alert_start('success', 'Propina borrada  ');


                        }
                    },
                });
            }

            if (criterio_propina == 0) {

                $.ajax({
                    data: {
                        id_mesa,
                    },
                    url: url + "/" + "eventos/borrar_propina_parcial",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);
                        if (resultado.resultado == 1) {

                            $('#propina_pesos').val(0)
                            $('#propina_del_pedido').val(0)
                            $('#propina_pesos_final').val(0)
                            $('#total_propina').val(0)

                            $('#efectivo').val(resultado.total)
                            $('#total_pedido').html("Valor pago: " + resultado.total)
                            $('#valor_pedido').html('$' + resultado.total)
                            $('#valor_total_a_pagar').val(resultado.total_pedido)


                            sweet_alert_start('success', 'Propina borrada  ');


                        }
                    },
                });

            }

        }
    </script>




    <script>
        function imprimir_orden_pedido() {

            var url = document.getElementById("url").value;
            var id_factura = document.getElementById("id_de_factura").value;



            $.ajax({
                data: {
                    id_factura, // Incluye el número de factura en los datos
                },
                url: url + "/" + "pedidos/imprimir_factura_electronica",
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
    </script>
    <script>
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
    </script>



    <script>
        function productos_subcategoria(id_subcategoria) {
            var url = document.getElementById("url").value;

            $.ajax({
                data: {
                    id_subcategoria
                },
                url: url + "/" + "inventario/productos_subcategoria",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        $('#productos_categoria').html(resultado.productos)

                    }
                },
            });
        }
    </script>



    <script>
        function cerrar_modal_mesas() {
            document.getElementById("buscar_mesa").value = "";
            document.getElementById("buscar_mesero").value = "";

            var url = document.getElementById("url").value;

            $.ajax({
                url: url +
                    "/" +
                    "pedidos/todas_las_mesas",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        $('#mesas_all').html(resultado.mesas)
                        $("#lista_todas_las_mesas").modal("hide");



                    }
                },
            });
        }
    </script>

    <script>
        $('#creacion_cliente_electronico').submit(function(e) {
            e.preventDefault();
            var form = this;
            let button = document.querySelector("#btn_crear_cliente");
            button.disabled = false;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $(form).find('span.error-text').text('');
                    button.disabled = false;
                },
                success: function(data) {
                    if ($.isEmptyObject(data.error)) {
                        if (data.code == 1) {
                            $("#crear_cliente").modal("hide");
                            $("#finalizar_venta").modal("show");
                            $("#nit_cliente").val(data.nit_cliente);
                            $("#nombre_cliente").val(data.cliente);


                            $(form)[0].reset();



                            //$('#countries-table').DataTable().ajax.reload(null, false);
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-start',
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
                                title: 'Cliente agregado a la base de datos '
                            })
                        } else {
                            alert(data.msg);
                        }
                    } else {
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val);
                        });
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Escucha el evento de cambio en el elemento con ID "departamento"
            $('#departamento').on('change', function() {
                // Obtiene el valor seleccionado en el campo "departamento"
                var valorSelect1 = $(this).val();

                // Obtiene la URL de la solicitud AJAX desde un elemento con ID "url"
                var url = $("#url").val();

                // Realiza la solicitud AJAX
                $.ajax({
                    url: url + "/eventos/municipios",
                    type: "post", // Puede ser 'POST' o 'GET' según tus necesidades

                    data: {
                        valorSelect1: valorSelect1
                    },
                    success: function(data) {
                        var resultado = JSON.parse(data);


                        $('#ciudad').html(resultado.ciudad)
                        $('#municipios').html(resultado.municipios)

                        // Puedes realizar acciones con los datos aquí
                        // Por ejemplo, llenar un elemento HTML con los datos
                        // $('#municipios').empty();
                        // $('#ciudad').empty();


                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Manejar errores
                        console.log('Error: ' + errorThrown);
                    }
                });
            });
        });
    </script>
    <script>
        function actualizacion_cantidades(cantidad, id) {
            var url = document.getElementById("url").value;
            //var id_producto = document.getElementById("id_edicion_producto").value;
            var id_producto = id;
            //var cantidad_producto = document.getElementById("cantidad_producto").value;
            var cantidad_producto = cantidad;
            var id_usuario = document.getElementById("id_usuario").value;

            $.ajax({
                data: {
                    id_producto,
                    cantidad_producto,
                    id_usuario

                },
                url: url +
                    "/" +
                    "eventos/actualizar_cantidades",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {
                        $("#editar_cantidades").modal("hide");
                        $('#mesa_productos').html(resultado.productos_pedido)
                        $('#valor_pedido').html(resultado.total_pedido)
                        $('#val_pedido').html(resultado.total_pedido)
                        $('#pedido_mesa').html('Pedido: ' + resultado.numero_pedido)
                        $('#subtotal_pedido').val(resultado.total_pedido)
                        $('#mesa_productos').html(resultado.productos_pedido)

                        if (resultado.estado_mesa == 0) {
                            header_pedido();
                        }
                        if (resultado.estado_mesa == 1) {
                            header_mesa();
                        }

                    }

                    if (resultado.resultado == 0) {
                        sweet_alert_start('warning', 'No se puede eliminar ')
                    }
                },
            });


        }
    </script>

    <script>
        function abrir_modal_editar_cantidad(id) {

            var url = document.getElementById("url").value;


            $.ajax({
                data: {
                    id,
                },
                url: url +
                    "/" +
                    "eventos/editar_cantidades",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        $('#info_producto').html(resultado.producto_cantidad)
                        $("#editar_cantidades").modal("show");



                    }
                },
            });



        }
    </script>

    <script>
        function flecha() {

            $("#operaciones").show();
            $("#nota").hide();
            $("#descuento").hide();
            $("#descuento_porcentaje").hide();
            $("#edicion_precio").hide();
            $("#descuentos_manuales").hide();
            $("#lista_precios").hide();

        }
    </script>

    <script>
        function cancelar_descuento() {
            var url = document.getElementById("url").value;
            var id_producto_pedido = document.getElementById("id_producto_pedido").value;
            $.ajax({
                data: {
                    id_producto_pedido,
                },
                url: url +
                    "/" +
                    "eventos/cancelar_descuentos",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        $("#agregar_nota").modal("hide");
                        reset_modal_agregar_nota()



                    }
                },
            });
        }
    </script>



    <script>
        function asignar_p1(valor) {
            var url = document.getElementById("url").value;
            var id_producto_pedido = document.getElementById("id_producto_pedido").value;
            $.ajax({
                data: {
                    id_producto_pedido,
                    valor
                },
                url: url +
                    "/" +
                    "eventos/asignar_p1",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        sweet_alert('success', 'Asignación de precio correcta ')
                        $("#val_uni" + resultado.id).html(resultado.valor_unitario)
                        $("#total_producto" + resultado.id).html(resultado.valor_total)
                        $("#subtotal_pedido").val(resultado.sub_total)
                        $("#propina_del_pedido").val(resultado.propina)
                        $("#valor_pedido").html(resultado.total_pedido)
                        $("#agregar_nota").modal("hide");
                    }
                },
            });
        }
    </script>




    <script>
        function generar_cortesia() {

            var url = document.getElementById("url").value;
            var id_producto_pedido = document.getElementById("id_producto_pedido").value;

            $.ajax({
                data: {
                    id_producto_pedido
                },
                url: url +
                    "/" +
                    "eventos/generar_cortesia",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        sweet_alert('success', " Cortesía generada exitosamente. ")
                        $("#modal_cortesia").modal("hide");
                        $('#mesa_productos').html(resultado.productos);
                        $("#valor_pedido").html(resultado.total_pedido);
                        $("#subtotal_pedido").val(resultado.total_pedido);
                        reset_modal_agregar_nota()


                    }
                },
            });

        }
    </script>

    <script>
        function reset_modal_agregar_nota() {
            $("#operaciones").show();
            $("#nota").hide();
            $("#descuento").hide();
            $("#descuento_porcentaje").hide();
            $("#edicion_precio").hide();
            $("#descuentos_manuales").hide();
            $("#lista_precios").hide();
        }
    </script>



    <script>
        function editar_precio() {
            var operaciones = document.getElementById("operaciones");
            operaciones.style.display = "none";

            var descuento = document.getElementById("descuento");
            descuento.style.display = "none";


            var edicion_precio = document.getElementById("edicion_precio");
            edicion_precio.style.display = "block";
        }
    </script>

    <script>
        function abrir_descuento_manual() {
            var operaciones = document.getElementById("operaciones");
            operaciones.style.display = "none";

            var descuento = document.getElementById("descuento");
            descuento.style.display = "none";


            var manual = document.getElementById("descuentos_manuales");
            manual.style.display = "block";
        }
    </script>
    <script>
        function mostrar_lista_precios() {
            var operaciones = document.getElementById("operaciones");
            operaciones.style.display = "none";

            var url = document.getElementById("url").value;
            var id_producto_pedido = document.getElementById("id_producto_pedido").value;


            $.ajax({
                data: {
                    id_producto_pedido
                },
                url: url +
                    "/" +
                    "eventos/lista_precios",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        var lista_precios = document.getElementById("lista_precios");
                        lista_precios.style.display = "block";
                        $('#precio_1').html(resultado.precio_1)
                        $('#precio_2').html(resultado.precio_2)
                        $('#precio_3').html(resultado.precio_3)


                    }
                },
            });


            var manual = document.getElementById("lista_precios");
            manual.style.display = "block";
        }
    </script>

    <script>
        function cortesia() {


            var url = document.getElementById("url").value;
            var id_producto_pedido = document.getElementById("id_producto_pedido").value;

            $.ajax({
                data: {
                    id_producto_pedido
                },
                url: url +
                    "/" +
                    "eventos/nombre_producto",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {


                        $('#mensaje_cortesia').html(resultado.nombre_producto)
                        $("#agregar_nota").modal("hide");
                        $("#modal_cortesia").modal("show");


                    }
                },
            });




        }
    </script>

    <script>
        function cortesia_1(id) {


            var url = document.getElementById("url").value;


            $.ajax({
                data: {
                    id_producto_pedido: id
                },
                url: url +
                    "/" +
                    "eventos/nombre_producto",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        document.getElementById("id_producto_pedido").value = id
                        $('#mensaje_cortesia').html(resultado.nombre_producto)
                        $("#agregar_nota").modal("hide");
                        $("#modal_cortesia").modal("show");


                    }
                },
            });




        }
    </script>

    <script>
        function cortesia() {


            var url = document.getElementById("url").value;
            var id_producto_pedido = document.getElementById("id_producto_pedido").value;

            $.ajax({
                data: {
                    id_producto_pedido
                },
                url: url +
                    "/" +
                    "eventos/nombre_producto",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {


                        $('#mensaje_cortesia').html(resultado.nombre_producto)
                        $("#agregar_nota").modal("hide");
                        $("#modal_cortesia").modal("show");


                    }
                },
            });




        }
    </script>

    <script>
        function cerrar_modal() {
            var url = document.getElementById("url").value;
            let id_mesa = document.getElementById("id_mesa_pedido").value;

            $.ajax({
                data: {
                    id_mesa,
                },
                url: url + "/" + "eventos/cerrar_modal",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        $("#agregar_nota").modal("hide");
                        $('#mesa_productos').html(resultado.productos);
                        $("#valor_pedido").html(resultado.total_pedido);
                        $("#subtotal_pedido").val(resultado.total_pedido);
                        $("#operaciones").show();
                        $("#nota").hide();
                        $("#descuento").hide();
                        $("#descuento_porcentaje").hide();
                        $("#edicion_precio").hide();
                        $("#descuentos_manuales").hide();
                        $("#lista_precios").hide();


                    }
                },
            });

        }
    </script>

    <script>
        function descontar_dinero(valor) {
            var url = document.getElementById("url").value;
            var id_producto_pedido = document.getElementById("id_producto_pedido").value;

            valor = valor.trim() === '' ? 0 : parseInt(valor.replace(/\./g, ''));


            $.ajax({
                data: {
                    valor,
                    id_producto_pedido
                },
                url: url +
                    "/" +
                    "eventos/descontar_dinero",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {


                        $('#restar_plata').val(resultado.precio_producto)


                    }
                },
            });
        }
    </script>
    <script>
        function cambio_manual_precio(valor) {


            var url = document.getElementById("url").value;
            var id_producto_pedido = document.getElementById("id_producto_pedido").value;

            valor = valor.trim() === '' ? 0 : parseInt(valor.replace(/\./g, ''));

            cambio_precio(url, valor, id_producto_pedido);

            $.ajax({
                data: {
                    valor,
                    id_producto_pedido
                },
                url: url +
                    "/" +
                    "eventos/editar_precio_producto",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        var precioConSeparador = resultado.precio_producto.toLocaleString();

                        // Asigna el valor con separador de miles al elemento con id "descuento_manual"
                        $('#descuento_manual').val(precioConSeparador);


                    }
                },
            });
        }
    </script>



    <script>
        function adicionar_nota() {

            var nota = document.getElementById("nota");
            nota.style.display = "block";

            var nota = document.getElementById("descuento");
            nota.style.display = "none";

            var operaciones = document.getElementById("operaciones");
            operaciones.style.display = "none";


        }
    </script>


    <script>
        function descuento() {
            var nota = document.getElementById("nota");
            nota.style.display = "none";

            var nota = document.getElementById("descuento");
            nota.style.display = "block";


            var operaciones = document.getElementById("operaciones");
            operaciones.style.display = "none";

        }
    </script>

    <script>
        function calcular_porcentaje(valor) {
            var url = document.getElementById("url").value;
            var id_producto_pedido = document.getElementById("id_producto_pedido").value;
            var id_usuario = document.getElementById("id_usuario").value;

            $.ajax({
                data: {
                    valor,
                    id_producto_pedido
                },
                url: url +
                    "/" +
                    "eventos/actualizar_producto_porcentaje",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        $('#lista_completa_mesas').html(resultado.mesas)
                        $('#precio_producto').val(resultado.total)


                    }
                },
            });
        }
    </script>

    <script>
        function descuento_porcentaje() {
            var descuento = document.getElementById("descuento");
            descuento.style.display = "none";

            var descuento_porcentaje = document.getElementById("descuento_porcentaje");
            descuento_porcentaje.style.display = "block";
        }
    </script>



    <script>
        function mesas_actualizadas() {
            $("#canva_producto").show();
            $("#productos_categoria").hide();
            var url = document.getElementById("url").value;
            $.ajax({

                url: url +
                    "/" +
                    "pedidos/tiempo_real",
                type: "get",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        $('#lista_completa_mesas').html(resultado.mesas)

                        $('#producto').val();

                    }
                },
            });

        }
    </script>

    <script>
        function buscar_productos(valor) {

            var url = document.getElementById("url").value;


            $.ajax({
                data: {
                    valor
                },
                url: url +
                    "/" +
                    "inventario/buscar",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        $('#canva_producto').html(resultado.productos)

                        var categoria = document.getElementById("productos_categoria");
                        categoria.style.display = "none";

                        var productos = document.getElementById("canva_producto");
                        productos.style.display = "block";


                    }
                },
            });
        }
    </script>




    <script>
        function nuevo_cliente() {
            $("#crear_cliente").modal("show");
            $("#finalizar_venta").modal("hide");
        }
    </script>

    <script>
        function cambiar_mesero() {
            $("#modal_meseros").modal("show");
        }
    </script>

    <script>
        function cambio_teclado() {
            var tipo_pago = document.getElementById("tipo_pago").value;
            var efectivo = document.getElementById("efectivo").value;
            var transaccion = document.getElementById("transaccion").value;

            // Convierte efectivo y transaccion a números enteros o asigna 0 si están vacíos
            efectivo = efectivo.trim() === '' ? 0 : parseInt(efectivo.replace(/\./g, ''));
            transaccion = transaccion.trim() === '' ? 0 : parseInt(transaccion.replace(/\./g, ''));

            let total_venta = document.getElementById("valor_total_a_pagar").value;
            total_venta = parseInt(total_venta.replace(/\./g, ''));

            // Suma efectivo y transaccion
            let suma_efectivo_transaccion = efectivo + transaccion;

            if (tipo_pago == 1) {
                // Calcula el cambio
                let cambio = suma_efectivo_transaccion - total_venta;

                // Muestra el cambio en el campo de transacción (o donde desees)
                //document.getElementById("transaccion").value = cambio.toLocaleString('es-CO');

                $('#pago').html('Valor pago: $ ' + suma_efectivo_transaccion.toLocaleString('es-CO'))

                if (cambio > 1) {
                    $('#cambio').html("Cambio: $ " + cambio.toLocaleString('es-CO'))
                }
                // Calcula el faltante y asegura que nunca sea negativo
                let faltante = total_venta - efectivo - transaccion;
                faltante = Math.max(faltante, 0); // Asegura que faltante no sea negativo

                $('#faltante').html('Faltante: $' + faltante.toLocaleString('es-CO'))
            }


            if (tipo_pago == 0) {
                // Asegúrate de convertir total_propina a número antes de sumarlo
                let propina_parcial = document.getElementById("total_propina").value;
                // Reemplaza todos los puntos en la propina
                let total_propina = propina_parcial.replace(/\./g, '');

                let total_venta = document.getElementById("valor_total_a_pagar").value;
                venta = parseInt(total_venta.replace(/\./g, ''));


                //console.log(efectivo)
                let suma_efectivo_transaccion = parseInt(efectivo) - parseInt(transaccion)

                let faltante = parseInt(total_venta) + parseInt(propina_parcial) - parseInt(efectivo) - parseInt(transaccion);

                faltante = Math.max(faltante, 0); // Asegura que faltante no sea negativo

                $('#faltante').html('Faltante: $' + faltante.toLocaleString('es-CO'))
                //$('#fpago').html('Faltante: $' + faltante.toLocaleString('es-CO'))
                if (faltante == 0) {
                    $('#cambio').html("Cambio: $0 ")
                }
                let cambio = (parseInt(total_venta) + parseInt(propina_parcial)) - parseInt(efectivo) - parseInt(transaccion);



                if (cambio > 1) {
                    $('#cambio').html("Cambio: $ " + cambio.toLocaleString('es-CO'))
                }



                $('#pago').html('Valor pago: $ ' + suma_efectivo_transaccion.toLocaleString('es-CO'))


            }
        }
    </script>


    <script>
        function pago_transaccion() {
            var tipo_pago = document.getElementById("tipo_pago").value;
            let total_venta = parseFloat(document.getElementById("valor_total_a_pagar").value);
            let propina_parcial = document.getElementById("total_propina").value;
            // Reemplaza todos los puntos en la propina
            let total_propina = propina_parcial.replace(/\./g, '');

            //console.log(tipo_pago)

            if (tipo_pago == 1) {
                $('#transaccion').val(total_venta.toLocaleString('es-CO'))
                $('#efectivo').val(0)
                $('#pago').html('Valor pago: ' + total_venta.toLocaleString('es-CO'))
                $('#faltante').html('Faltante: 0 ')
                $('#cambio').html('Cambio: 0')
            }


            if (tipo_pago == 0) {
                //let total = total_venta + parseFloat(total_propina);
                let total = total_venta;
                $('#transaccion').val(total.toLocaleString('es-CO'))
                $('#pago').html('Valor pago: ' + total.toLocaleString('es-CO'))
                $('#faltante').html('Faltante: 0')
                $('#cambio').html('Cambio: 0')
                $('#efectivo').val(0)
            }

        }
    </script>
    <!--   <script>
        function pago_transaccion() {
            var tipo_pago = document.getElementById("tipo_pago").value;
            let total_venta = parseFloat(document.getElementById("valor_total_a_pagar").value);
            let propina_parcial = document.getElementById("total_propina").value;
            // Reemplaza todos los puntos en la propina
            let total_propina = propina_parcial.replace(/\./g, '');


            if (tipo_pago == 1) {
                $('#transaccion').val(total_venta.toLocaleString('es-CO'))
                $('#efectivo').val(0)
                $('#pago').html('Valor pago: ' + total_venta.toLocaleString('es-CO'))
                $('#faltante').html('Faltante: 0 ')
                $('#cambio').html('Cambio: 0')
            }


            if (tipo_pago == 0) {
                let total = total_venta + parseFloat(total_propina);
                $('#transaccion').val(total.toLocaleString('es-CO'))
                $('#pago').html('Valor pago: ' + total.toLocaleString('es-CO'))
                $('#faltante').html('Faltante: 0')
                $('#cambio').html('Cambio: 0')
                $('#efectivo').val(0)
            }

        }
    </script> -->




    <script>
        let campoActual = null; // Variable global para almacenar el campo de entrada actual

        function guardarCampoActual(input) {
            campoActual = input;
        }

        function agregarNumero() {
            if (campoActual) {
                // Obtiene el número del botón que se hizo clic
                let numero = event.target.textContent;

                // Agrega el número al campo de entrada actual
                campoActual.value += numero;

                // Formatea el valor del campo de entrada actual
                campoActual.value = formatearNumero(campoActual.value);
            }
        }

        function formatearNumero(valor) {
            // Elimina cualquier separador de miles o punto decimal existente
            valor = valor.replace(/[.,]/g, '');

            // Formatea el valor con separador de miles y punto decimal
            let numeroFormateado = parseFloat(valor).toLocaleString('es-CO');

            return numeroFormateado;
        }

        function borrarCampoActual() {
            if (campoActual) {
                campoActual.value = ''; // Borra el contenido del campo de entrada actual
            }
        }
    </script>

    <script>
        function buscar_mesas(valor) {
            var url = document.getElementById("url").value;


            $.ajax({
                data: {
                    valor
                },
                url: url +
                    "/" +
                    "pedidos/buscar_mesas",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {
                        let tipo_pedido = document.getElementById("tipo_pedido").value;

                        if (tipo_pedido == "movil") {
                            $('#resultado_mesa').html(resultado.mesas)
                            //$("#lista_todas_las_mesas").modal("show");
                        }
                        if (tipo_pedido == "computador") {
                            $('#mesas_all').html(resultado.mesas)
                            $("#lista_todas_las_mesas").modal("show");
                        }

                    }
                },
            });

        }
    </script>
    <script>
        function buscar_meseros(valor) {
            var url = document.getElementById("url").value;


            $.ajax({
                data: {
                    valor
                },
                url: url +
                    "/" +
                    "pedidos/buscar_mesero",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {
                        let tipo_pedido = document.getElementById("tipo_pedido").value;

                        if (tipo_pedido == "movil") {
                            $('#resultado_mesa').html(resultado.meseros)
                            //$("#lista_todas_las_mesas").modal("show");
                        }
                        if (tipo_pedido == "computador") {
                            $('#mesas_all').html(resultado.meseros)
                            $("#lista_todas_las_mesas").modal("show");
                        }
                    }
                },
            });

        }
    </script>


    <script>
        function todas_las_mesas() {
            var url = document.getElementById("url").value;

            $.ajax({
                url: url +
                    "/" +
                    "pedidos/todas_las_mesas",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        $('#mesas_all').html(resultado.mesas)
                        $("#lista_todas_las_mesas").modal("show");



                    }
                },
            });
        }
    </script>

    <script>
        function listado_mesas() {
            var url = document.getElementById("url").value;

            $.ajax({
                url: url +
                    "/" +
                    "pedidos/todas_las_mesas",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        $('#resultado_mesa').html(resultado.mesas)

                    }
                },
            });
        }
    </script>

    <script>
        const total_propina = document.querySelector("#total_propina");

        function formatNumber(n) {
            // Elimina cualquier carácter que no sea un número
            n = n.replace(/\D/g, "");
            // Formatea el número
            return n === "" ? n : parseFloat(n).toLocaleString('es-CO');
        }

        total_propina.addEventListener("input", (e) => {
            const element = e.target;
            const value = element.value;
            element.value = formatNumber(value);
        });
    </script>

    <script>
        const edicion_manual = document.querySelector("#descontar_dinero");

        function formatNumber(n) {
            // Elimina cualquier carácter que no sea un número
            n = n.replace(/\D/g, "");
            // Formatea el número
            return n === "" ? n : parseFloat(n).toLocaleString('es-CO');
        }

        edicion_manual.addEventListener("input", (e) => {
            const element = e.target;
            const value = element.value;
            element.value = formatNumber(value);
        });
    </script>


    <script>
        function total_pedido_final(valor) {
            var sub_total = document.getElementById("valor_total_a_pagar").value;

            temp_valor = "";

            if (valor === "") {
                temp_valor = 0
            } else {
                temp_valor = valor;
            }

            if (temp_valor != 0) {
                temp_valor = valor.replace(/\./g, ''); // Elimina el punto
            }

            valor_pedido = parseInt(sub_total) + parseInt(temp_valor)

            $('#total_pedido').html('Total: $ ' + valor_pedido.toLocaleString('es-CO'))

        }
    </script>

    <script>
        function calculo_propina_parcial() {

            let url = document.getElementById("url").value;
            let id_mesa = document.getElementById("id_mesa_pedido").value;
            let criterio_propina = document.getElementById("criterio_propina_final").value;

            if (criterio_propina == 1) {

                $.ajax({
                    data: {
                        id_mesa,
                    },
                    url: url + "/" + "pedidos/propinas",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);
                        if (resultado.resultado == 1) {

                            $('#propina_del_pedido').val(resultado.propina)
                            $('#valor_pedido').html(resultado.total_pedido)
                            $('#val_pedido').html(resultado.total_pedido)
                            $('#total_propina').val(resultado.propina)
                            //$('#efectivo').val(resultado.total_pedido)
                            $('#total_pedido').html("Valor pago:  " + resultado.total_pedido)
                            $('#valor_total_a_pagar').val(resultado.val_pedido)


                        }
                    },
                });
            }

            if (criterio_propina == 0) {

                $.ajax({
                    data: {
                        id_mesa,
                    },
                    url: url + "/" + "configuracion/propina_parcial",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);
                        if (resultado.resultado == 1) {

                            $('#total_propina').val(resultado.propina)
                            $('#total_pedido').html("Valor pago: " + resultado.valor_total)
                            $('#valor_total_a_pagar').val(resultado.total)
                            $('#efectivo').val(resultado.valor_total)



                        }
                    },
                });
            }


        }
    </script>

    <script>
        function calcular_propina_final(propina) {


            var criterio_propina = document.getElementById("criterio_propina_final").value;
            var subtotal = document.getElementById("valor_total_a_pagar").value;
            var id_mesa = document.getElementById("id_mesa_pedido").value;
            var url = document.getElementById("url").value;


            $.ajax({
                data: {
                    id_mesa,
                },
                url: url + "/" + "pedidos/propinas",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        $('#propina_del_pedido').val(resultado.propina)
                        $('#valor_pedido').html(resultado.total_pedido)
                        $('#val_pedido').html(resultado.total_pedido)

                    }
                },
            });


            temp_propina = 0;

            if (propina === "") {
                temp_propina = 0;
            } else {
                temp_propina = propina
            }

            if (criterio_propina == 1) {

                if (temp_propina >= 0 && temp_propina < 100) {
                    let porcentaje = parseInt(temp_propina) / 100
                    valor_propina = parseInt(subtotal) * parseFloat(porcentaje)
                    var propinaFormateada = valor_propina.toLocaleString('es-ES');

                    //console.log(valor_propina)
                    total = parseInt(valor_propina) + parseInt(subtotal)

                    //$('#valor_total_a_pagar').val(total)
                    $('#total_propina').val(propinaFormateada)

                    if (total >= 0) {
                        $('#total_pedido').html("Total " + total.toLocaleString('es-ES'))
                    }

                }

            }


            if (criterio_propina == 2) {

                const propina_en_pesos = document.querySelector("#propina_pesos");

                function formatNumber(n) {
                    // Elimina cualquier carácter que no sea un número
                    n = n.replace(/\D/g, "");
                    // Formatea el número
                    return n === "" ? n : parseFloat(n).toLocaleString('es-CO');
                }

                propina_en_pesos.addEventListener("input", (e) => {
                    const element = e.target;
                    const value = element.value;
                    element.value = formatNumber(value);
                });

                var propina_pesos = document.getElementById("propina_pesos").value;
                var propina_pesos_limpio = subtotal.replace(/\$|\.+/g, "");

                total = parseInt(propina_pesos_limpio) + parseInt(subtotalLimpio)

                //$('#propina_del_pedido').val(propinaFormateada)
                $('#valor_pedido').html(total.toLocaleString('es-ES'))

            }



        }
    </script>

    <script>
        const propina_pedido = document.querySelector("#propina_del_pedido");

        function formatNumber(n) {
            // Elimina cualquier carácter que no sea un número
            n = n.replace(/\D/g, "");
            // Formatea el número
            return n === "" ? n : parseFloat(n).toLocaleString('es-CO');
        }

        propina_pedido.addEventListener("input", (e) => {
            const element = e.target;
            const value = element.value;
            element.value = formatNumber(value);
        });
    </script>

    <script>
        const descuento = document.querySelector("#descontar_dinero");

        function formatNumber(n) {
            // Elimina cualquier carácter que no sea un número
            n = n.replace(/\D/g, "");
            // Formatea el número
            return n === "" ? n : parseFloat(n).toLocaleString('es-CO');
        }

        descuento.addEventListener("input", (e) => {
            const element = e.target;
            const value = element.value;
            element.value = formatNumber(value);
        });
    </script>

    <script>
        let mensaje = "<?php echo $user_session->getFlashdata('mensaje'); ?>";
        let iconoMensaje = "<?php echo $user_session->getFlashdata('iconoMensaje'); ?>";
        if (mensaje != "") {
            sweet_alert(iconoMensaje, mensaje)
        }
    </script>


    <script>
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






    <script type="text/javascript">
        function valideKey(evt) {

            // code is the decimal ASCII representation of the pressed key.
            var code = (evt.which) ? evt.which : evt.keyCode;

            if (code == 8) { // backspace.
                return true;
            } else if (code >= 48 && code <= 57) { // is a number.
                return true;
            } else { // other keys.
                return false;
            }
        }
    </script>

    <script>
        $("#buscar_cliente").autocomplete({
            source: function(request, response) {
                var url = document.getElementById("url").value;
                $.ajax({
                    type: "POST",
                    url: url + "/" + "clientes/clientes_autocompletado",
                    data: request,
                    success: response,
                    dataType: "json",
                });
            },
            appendTo: "#finalizar_venta",
        }, {
            minLength: 1,
        }, {
            select: function(event, ui) {
                // $("#id_cliente_factura_pos").val(ui.item.value);
                //$("#clientes_factura_pos").val(ui.item.nit_cliente);
                $("#nit_cliente").val(ui.item.nit_cliente);
                $("#nombre_cliente").val(ui.item.value);
                $(this).val("");
                return false;
                //$('#buscar_cliente').val(''); 
            },
        });
    </script>


    <script>
        const efectivo = document.querySelector("#efectivo");

        function formatNumber(n) {
            // Elimina cualquier carácter que no sea un número
            n = n.replace(/\D/g, "");
            // Formatea el número
            return n === "" ? n : parseFloat(n).toLocaleString('es-CO');
        }

        efectivo.addEventListener("input", (e) => {
            const element = e.target;
            const value = element.value;
            element.value = formatNumber(value);
        });
    </script>

    <script>
        const transaccion = document.querySelector("#transaccion");

        function formatNumber(n) {
            // Elimina cualquier carácter que no sea un número
            n = n.replace(/\D/g, "");
            // Formatea el número
            return n === "" ? n : parseFloat(n).toLocaleString('es-CO');
        }

        transaccion.addEventListener("input", (e) => {
            const element = e.target;
            const value = element.value;
            element.value = formatNumber(value);
        });
    </script>

    <script>
        const retiro = document.querySelector("#valor_retiro");

        function formatNumber(n) {
            // Elimina cualquier carácter que no sea un número
            n = n.replace(/\D/g, "");
            // Formatea el número
            return n === "" ? n : parseFloat(n).toLocaleString('es-CO');
        }

        retiro.addEventListener("input", (e) => {
            const element = e.target;
            const value = element.value;
            element.value = formatNumber(value);
        });
    </script>

</body>

</html>