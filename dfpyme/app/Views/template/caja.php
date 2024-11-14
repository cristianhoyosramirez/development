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

            </div>
            <?= $this->include('layout/footer') ?>
        </div>
        <!-- Libs JS -->
        <!--jQuery -->
        <script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
        <!-- Tabler Core -->
        <script src="<?= base_url() ?>/Assets/js/tabler.min.js"></script>
        <!-- Sweet alert -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
        <!-- Script locales -->
        <script src="<?= base_url() ?>/Assets/script_js/caja/apertura_caja.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/caja/cierre_caja.js"></script>
        <script>
            let mensaje = "<?php echo $session->getFlashdata('mensaje'); ?>";
            let iconoMensaje = "<?php echo $session->getFlashdata('iconoMensaje'); ?>";
            if (mensaje != "") {

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
                    icon: iconoMensaje,
                    title: mensaje
                })

            }
        </script>
        <script>
            function total_ingresos(id_apertura) {
                var url = document.getElementById("url").value;
                $.ajax({
                    data: {
                        id_apertura
                    },
                    url: url + "/" + "caja_general/total_ingresos",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {

                            $("#ingressos_total").html(resultado.movimientos);
                            $("#periodo").html(resultado.periodo);
                            $("#gran_total").html(resultado.total);

                            myModal = new bootstrap.Modal(
                                document.getElementById("total_ingresos_caja_general"), {}
                            );
                            myModal.show();
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
                                title: 'Movimientos encontrados '
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
            function imprimir_reporte(id_apertura) {
                var url = document.getElementById("url").value;
                $.ajax({
                    data: {
                        id_apertura
                    },
                    url: url + "/" + "caja_general/imprimir_movimiento_caja_general",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

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
                                title: 'Impresion de movimiento éxitoso'
                            })
                        }
                    },
                });



                /*       myModal = new bootstrap.Modal(
                          document.getElementById("total_ingresos_caja_general"), {}
                      );
                      myModal.show(); */

            }
        </script>
        <script>
            function ver_retiros(id_apertura) {
                var url = document.getElementById("url").value;
                $.ajax({
                    data: {
                        id_apertura
                    },
                    url: url + "/" + "caja_general/ver_retiros",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {

                            $("#retiros_general").html(resultado.periodo);
                            $("#retiros_total").html(resultado.retiros);
                            $("#gran_total_retiros").html(resultado.total);

                            myModal = new bootstrap.Modal(
                                document.getElementById("total_retiros"), {}
                            );
                            myModal.show();
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
                                title: 'Movimientos encontrados '
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
            function editar_apertura(id_apertura) {
                var url = document.getElementById("url").value;
                $.ajax({
                    data: {
                        id_apertura
                    },
                    url: url + "/" + "caja_general/editar_apertura",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {

                            $("#cambiar_valor_apertura").val(resultado.valor_apertura);
                            $("#id_apertura_caja_general").val(resultado.id_apertura);
                            myModal = new bootstrap.Modal(
                                document.getElementById("editar_apertura"), {}
                            );
                            myModal.show();
                        }
                    },
                });
            }
        </script>
        <script>
            function actualizar_apertura() {
                var url = document.getElementById("url").value;
                var id_apertura = document.getElementById("id_apertura_caja_general").value;
                var valor_apertura = document.getElementById("cambiar_valor_apertura").value;
                $.ajax({
                    data: {
                        id_apertura,
                        valor_apertura
                    },
                    url: url + "/" + "caja_general/actualizar_valor_apertura",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {

                            $("#datos_consulta_de_caja_general").html(resultado.datos);
                            $("#editar_apertura").modal("hide");
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
                                title: 'Actualizacion de apertura correcto  '
                            })

                        }
                    },
                });
            }
        </script>
        <script>
            function saltar(e, id) {
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
            $(function() {
                $("#editar_apertura").on("shown.bs.modal", function(e) {
                    $("#cambiar_valor_apertura").focus();
                });
            });
            $(function() {
                $("#editar_cierre").on("shown.bs.modal", function(e) {
                    $("#cambiar_valor_cierre").focus();
                });
            });
            $(function() {
                $("#editar_apertura").on("shown.bs.modal", function(e) {
                    $("#cambiar_valor_apertura").focus();
                });
            });
        </script>

        <script>
            function editar_cierre(id_apertura) {
                var url = document.getElementById("url").value;
                var valor_cierre = document.getElementById("valor_cierre").value;
                $.ajax({
                    data: {
                        id_apertura
                    },
                    url: url + "/" + "caja_general/validar_cierre",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {
                            $("#cambiar_valor_cierre").val(resultado.valor_cierre);
                            $("#id_apertura_caja_general").val(resultado.id_apertura);
                            myModal = new bootstrap.Modal(
                                document.getElementById("editar_cierre"), {}
                            );
                            myModal.show();
                        }

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
                                title: 'No se ha realizado el cierre'
                            })
                        }
                    },
                });
            }
        </script>

        <script>
            function actualizar_cierre() {
                var url = document.getElementById("url").value;
                var id_apertura = document.getElementById("id_apertura_caja_general").value;
                var valor_cierre = document.getElementById("cambiar_valor_cierre").value;
                $.ajax({
                    data: {
                        id_apertura,
                        valor_cierre
                    },
                    url: url + "/" + "caja_general/actualizar_valor_cierre",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {

                            $("#datos_consulta_de_caja_general").html(resultado.datos);
                            $("#editar_cierre").modal("hide");
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
                                title: 'Actualizacion de cierre correcto  '
                            })

                        }
                    },
                });
            }
        </script>


</body>

</html>