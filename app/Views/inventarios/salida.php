<?php $user_session = session(); ?>
<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta19
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Bienvenido DFpyme</title>
    <!-- CSS files -->
    <link href="<?= base_url() ?>/public/css/tabler.min.css" rel="stylesheet" />
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


</head>

<body>
    <script src="<?= base_url() ?>/public/js/demo-theme.min.js?1684106062"></script>
    <?= $this->include('layout/header_mesas') ?>
    <div class="page">
        <!-- Navbar -->
        <div class="row">
            <p></p>
        </div>
        <br>
        <!--Sart row-->
        <div class="container">
            <div class="row text-center align-items-center flex-row-reverse">
                <div class="col-lg-auto ms-lg-auto">

                </div>

                <div class="col-lg-auto ms-lg-auto">
                    <p class="text-primary h3">SALIDA DE INVENTARIO </p>
                </div>
                <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                    <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
                </div>
            </div>
        </div>
        <br>


        <div class="page-wrapper">
            <!-- Page body -->

            <div class="card container">
                <div class="card-body">
                    <form action="<?= base_url('inventario/salida_inventario') ?>" method="POST">
                        <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" name="id_usuario">
                        <div class="row mb-3">


                            <div class="col-2">
                                <label class="form-label">Concepto</label>
                                <?php $concepto = model('KardexConceptoModel')->where('idoperacion', 2)->findAll(); ?>

                                <select name="concepto_kardex" id="concepto_kardex" class="form-select" required>
                                    <option value=""></option>
                                    <?php foreach ($concepto as $detalle): ?>
                                        <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['nombre'] ?></option>
                                    <?php endforeach ?>
                                </select>

                            </div>
                            <div class="col-md-2">

                                <input type="hidden" value="<?php echo base_url() ?>" id="url">
                                <input type="hidden" id="id_producto" name="id_producto">
                                <div class="mb-3">
                                    <label class="form-label">Producto</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" class="form-control" name="producto" id="producto" placeholder="Buscar por nombre, código interno o codigo de barras " required>
                                        <span class="input-group-text">
                                            <a href="#" class="link-secondary" title="Limpiar campo" data-bs-toggle="tooltip" onclick="limpiar_campo()"><!-- Download SVG icon from http://tabler-icons.io/i/x -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <line x1="18" y1="6" x2="6" y2="18" />
                                                    <line x1="6" y1="6" x2="18" y2="18" />
                                                </svg>
                                            </a>


                                        </span>
                                    </div>
                                </div>
                                <div class="text-danger"><?= session('errors.salon') ?></div>
                            </div>
                            <div class="col-md-2">
                                <label for="inputEmail4" class="form-label">Cantidad inventario </label>
                                <input type="text" class="form-control" name="cantidad_inventario" id="cantidad_inventario" disabled>
                                <div class="text-danger"><?= session('errors.nombre') ?></div>
                            </div>
                            <div class="col-md-2">
                                <label for="inputEmail4" class="form-label">Cantidad salida </label>
                                <input type="number" class="form-control" name="cantidad_entrada" id="cantidad_entrada" required onkeyup="cantidad_entrada_final()">
                                <div class="text-danger"><?= session('errors.nombre') ?></div>
                            </div>
                            <div class="col-md-2">
                                <label for="inputEmail4" class="form-label">Cantidad final </label>
                                <input type="text" class="form-control" name="cantidad_final" id="cantidad_final" disabled>
                                <div class="text-danger"><?= session('errors.nombre') ?></div>
                            </div>

                            <div class="col-md-2">
                                <label for="inputEmail4" class="form-label">Nota </label>
                                <textarea name="nota" id="nota" class="form-control" required></textarea>
                                <div class="text-danger"><?= session('errors.nombre') ?></div>
                            </div>




                        </div>
                        <div class="col-md-4">

                            <button type="submit" class="btn btn-primary w-md"><i class="mdi mdi-plus"></i> Salida de inventario </button>
                        </div>
                    </form>
                    <br>
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
    <!-- jQuery-ui -->
    <script src="<?php echo base_url() ?>/Assets/plugin/jquery-ui/jquery-ui.js"></script>
    <!-- Sweet alert -->
    <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
    <!--select2 -->
    <script src="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.js"></script>

    <script>
        $("#concepto_kardex").select2({
            width: "100%",
            placeholder: "Seleccionar un concepto para el ingreso ",
            language: "es",
            theme: "bootstrap-5",
            allowClear: true,
            //minimumResultsForSearch: -1,
        });
    </script>

    <script>
        let mensaje = "<?php echo $user_session->getFlashdata('mensaje'); ?>";
        let iconoMensaje = "<?php echo $user_session->getFlashdata('iconoMensaje'); ?>";
        if (mensaje != "") {

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                //position: 'bottom-end',
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
        function limpiar_campo() {
            $('#producto').val('')
            $('#id_producto').val('')
            $('#cantidad_inventario').val('')
        }
    </script>

    <script>
        function cantidad_entrada_final() {
            // Get the values from the input fields
            var cantidadEntrada = $('#cantidad_entrada').val();
            var cantidadInventario = $('#cantidad_inventario').val();


            if (parseInt(cantidadInventario) < 0) {
                var resultado = parseInt(cantidadInventario) + parseInt(cantidadEntrada);
            }
            if (parseInt(cantidadInventario) > 0) {
                var resultado = parseInt(cantidadInventario) - parseInt(cantidadEntrada);
            }
            // Update the result in another input or do something with it
            $('#cantidad_final').val(resultado);

        }
    </script>


    <script>
        $("#producto").autocomplete({
            source: function(request, response) {
                var url = document.getElementById("url").value;
                $.ajax({
                    type: "POST",
                    url: url + "/" + "producto/InvSalida",
                    data: request,
                    success: response,
                    dataType: "json",
                });
            },
        }, {
            minLength: 1,
        }, {
            select: function(event, ui) {


                $('#id_producto').val(ui.item.id_producto)
                $('#cantidad_inventario').val(ui.item.cantidad)


            },
        });
    </script>











































</body>

</html>