<?php $session = session(); ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title><?= $this->renderSection('title') ?>&nbsp;-&nbsp;DF PYME</title>

    <!-- CSS files -->
    <link href="<?= base_url() ?>/Assets/css/tabler.min.css" rel="stylesheet" />
    <!-- Pin pad -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/Assets/plugin/pin_pad/css/main.css" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/Assets/img/favicon.png">
    <!-- Jquery-ui -->
    <link href="<?php echo base_url() ?>/Assets/plugin/jquery-ui/jquery-ui.css" rel="stylesheet">
    <!-- Select 2 -->
    <link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2-bootstrap-5-theme.min.css" rel="stylesheet" />



</head>

<body>
    <div class="wrapper">
        <?= $this->include('layout/header_mesas') ?>
        

        <div class="page-wrapper">
            <div class="container-xl">
            </div>
            <div class="page-body">
                <?= $this->renderSection('content') ?>
                <?= $this->include('ventanas_modal_pedido/pin_edicion_cantidades') ?>
                <?= $this->include('ventanas_modal_pedido/categorias_producto') ?>
                <?= $this->include('ventanas_modal_pedido/autocompletar_producto') ?>


            </div>
            <?= $this->include('layout/footer') ?>
            <?= $this->include('ventanas_modal_pedido/productos_x_categoria') ?>
            <?= $this->include('ventanas_modal_pedido/nota_pedido') ?>
            <?= $this->include('ventanas_modal_pedido/resumen_pedido') ?>
            <?= $this->include('ventanas_modal_pedido/entrega_de_productos') ?>
            <?= $this->include('ventanas_modal_pedido/cambiar_de_mesa') ?>
            <?= $this->include('ventanas_modal_editar_eliminar_pedido/editar_cantidad_producto') ?>

        </div>
        <!-- Libs JS -->
        <!-- Tabler Core -->
        <script src="<?= base_url() ?>/Assets/js/tabler.min.js"></script>
        <!-- Script locales -->
        <script src="<?= base_url() ?>/Assets/script_js/salonMesa/ver_mesas_por_salon.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/salonMesa/mesa_pedido.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/pedidos/producto_pedido.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/pedidos/agregar_producto_pedido.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/pedidos/agregar_nota_al_pedido.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/pedidos/detalle_pedido.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/pedidos/nota_de_pedido.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/pedidos/editar_cantidades_de_pedido.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/pedidos/entregar_producto.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/pedidos/insertar_producto_pedido_desde_categoria.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/pedidos/eliminar_producto.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/pedidos/cambiar_de_mesa.js"></script>
        <!--jQuery -->
        <script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
        <!-- Sweet alert -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
        <!--select2 -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.js"></script>

        <script>
            let mensaje = "<?php echo $session->getFlashdata('mensaje'); ?>";
            let iconoMensaje = "<?php echo $session->getFlashdata('iconoMensaje'); ?>";
            if (mensaje != "") {
                Swal.fire({
                    title: mensaje,
                    icon: iconoMensaje,
                    confirmButtonText: 'ACEPTAR',
                    confirmButtonColor: "#2AA13D",
                })
            }
        </script>
        <script>
            $(window).scroll(function() {
                var position = $(this).scrollTop();
                // Si el usuario baja el scroll muestro el div qeu contiene el enlace botón
                if (position > 300) {
                    $(".boton-subir").fadeIn('slow');

                } else {
                    $(".boton-subir").fadeOut('slow');
                }
            });
        </script>

        <script>
            $(document).ready(function() {
                const input_value = $("#password");

                //disable input from typing

                $("#password").keypress(function() {
                    return false;
                });

                //add password
                $(".calc").click(function() {
                    let value = $(this).val();
                    field(value);
                });

                function field(value) {
                    input_value.val(input_value.val() + value);
                }
                $("#clear").click(function() {
                    input_value.val("");
                });
                $("#enter").click(function() {
                    // alert("Your password " + input_value.val() + " added");

                    var url = document.getElementById("url").value;
                    var pin = input_value.val();



                    var id_tabla_producto_pedido = document.getElementById("id_edicion_producto_pedido").value;

                    $.ajax({
                        data: {
                            pin,
                            id_tabla_producto_pedido
                        },
                        url: url + "/" + "producto/editar_con_pin",
                        type: "POST",
                        success: function(resultado) {
                            var resultado = JSON.parse(resultado);

                            if (resultado.resultado == 0) {
                                Swal.fire({
                                    icon: "warning",
                                    title: "Dato de cantidad errado",
                                    confirmButtonText: "ACEPTAR",
                                    confirmButtonColor: "#2AA13D",
                                });
                            }

                            if (resultado.resultado == 1) {
                                //Se puede editar cantidades y notas
                                document.getElementById("codigo_internoproducto_editar").value =
                                    resultado.id_tabla_producto_pedido;
                                document.getElementById("notas_editar").value = resultado.notas;
                                $("#codigointernoproducto_editar").html(resultado.codigo_interno);
                                $("#nombre_producto_editar").html(resultado.descripcion);
                                $("#precio_venta_editar").html(resultado.valor_unitario_formato);
                                document.getElementById("precioventa_editar").value =
                                    resultado.valor_unitario;
                                document.getElementById("producto_pedido_cantidad_editar").value =
                                    resultado.cantidad;
                                $("#pin_edicion_cantidades").modal("hide");
                                myModal = new bootstrap.Modal(
                                    document.getElementById("editar_cantidades_producto"), {}
                                );
                                myModal.show();
                            }
                        },
                    });

                });
            });
        </script>

        <script>
            function mesa_pedido_directo(id_mesa) {
                var salon = document.querySelector("#mesa_directa");
                salon.style.display = "none";
                var pedido = document.querySelector("#pedido_directo");
                pedido.style.display = "block";
                var url = document.getElementById("url").value;

                $.ajax({
                    data: {
                        id_mesa,
                    },
                    url: url + "/" + "mesas/pedido",
                    type: "post",
                    success: function(resultado) {

                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {
                            // La mesa tiene pedido
                            
                            document.getElementById("id_mesa").value = resultado.id_mesa;
                            document.getElementById("numero_pedido_salvar").value = resultado.numero_pedido;
                            document.getElementById("numero_pedido_imprimir_comanda").value = resultado.numero_pedido;
                            document.getElementById("observacion_general_de_pedido").value = resultado.nota_pedido;
                            document.getElementById("id_mesa_facturacion").value = resultado.id_mesa;

                            $("#valor_total").html(resultado.total);
                            $("#cantidad_de_productos").html(resultado.cantidad_de_productos);

                            $("#nombre_de_mesa").html(resultado.nombre_mesa);
                            $("#numero_pedido_mostrar").html(resultado.numero_pedido);
                            $("#productos_pedido").html(resultado.productos_pedido);



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
                                title: "La mesa:" + " " + resultado.nombre_mesa + " " + "tiene pedido cargado",
                            })



                        } else if (resultado.resultado == 0) {
                            //la mesa esta libre sin pedido
                            document.getElementById("numero_pedido_salvar").value = "";
                            $("#nombre_de_mesa").html(resultado.nombre_mesa);
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
                                title: "Mesa:" + " " + resultado.nombre_mesa + " " + "disponible",
                            })


                            document.getElementById("id_mesa").value = resultado.id_mesa;
                            document.getElementById("id_mesa_facturacion").value = resultado.id_mesa;
                            $("#nombre_mesa").html(resultado.nombre_mesa);
                        }
                    },
                });
            }
        </script>

        <script>
            function mayusculas(e) {
                e.value = e.value.toUpperCase();
            }
        </script>

        <script>
            function eliminar_el_pedido_con_pin() {
                var pin = document.getElementById("eliminacion_pedido_usuario").value;
                var numero_pedido = document.getElementById("numero_pedido_salvar").value;
                var url = document.getElementById("url").value;

                $.ajax({
                    data: {
                        pin,
                        numero_pedido
                    },
                    url: url + "/" + "producto/eliminar_pedido_usuario",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 0) {
                            Swal.fire({
                                icon: "warning",
                                title: "Dato de cantidad errado",
                                confirmButtonText: "ACEPTAR",
                                confirmButtonColor: "#2AA13D",
                            });
                        }

                        if (resultado.resultado == 1) {
                            $("#eliminar_pedido_con_pin_pad").modal("hide");
                            window.location.reload()

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
                                title: "Pedido eliminado:",
                            })
                        }
                    },
                });

            }
        </script>


</body>

</html>