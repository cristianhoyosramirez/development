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
    <!-- Data tables -->
    <link href="<?= base_url() ?>/Assets/plugin/data_tables/bootstrap.min.css" />
    <link href="<?= base_url() ?>/Assets/plugin/data_tables/dataTables.bootstrap5.min.css" />
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

            </div>
            <?= $this->include('layout/footer') ?>
        </div>
        <!-- Libs JS -->
        <!-- Tabler Core -->
        <script src="<?= base_url() ?>/Assets/js/tabler.min.js"></script>
        <!-- J QUERY -->
        <script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
        <!-- Data tables -->
        <script src="<?= base_url() ?>/Assets/plugin/data_tables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url() ?>/Assets/plugin/data_tables/dataTables.bootstrap5.min.js"></script>
        <!--select2 -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.js"></script>
        <!-- Script locales -->
        <script src="<?= base_url() ?>/Assets/script_js/mesas/data_table.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/mesas/datos_iniciales.js"></script>
        <!-- Sweet alert -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
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
            function editar_categoria(id_categoria) {
                myModal = new bootstrap.Modal(
                    document.getElementById("editar_categoria"), {}
                );
                myModal.show();
            }
        </script>

        <!-- Actualizar la tabla consecutivos  -->
        <script>
            function actualizar_consecutivos(e, valor_consecutivo, id_consecutivos) {
                var enterKey = 13;

                if (valor_consecutivo != '') {
                    if (e.which == enterKey) {
                        $.ajax({
                            data: {
                                valor_consecutivo,
                                id_consecutivos
                            },
                            url: '<?php echo base_url(); ?>/empresa/actualizar_consecutivos',
                            type: "POST",
                            success: function(resultado) {
                                var resultado = JSON.parse(resultado);


                                if (resultado.resultado == 0) {
                                    $('#tabla_categorias').html(resultado.categorias)
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
                                        title: 'Consecutivo actualizado'
                                    })

                                }
                            },
                        });


                    }
                } else if (valor_consecutivo == '') {
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
                        title: 'El valor del consecutivo no puede estar vacio '
                    })
                }

            }
        </script>
        <!--/ Actualizar la tabla consecutivos  -->


        <!-- Actualizar el nombre de la cuenta -->
        <script>
            function actualizar_nombre_cuenta(e, nombre_cuenta, id_cuenta) {
                var enterKey = 13;

                if (nombre_cuenta != '') {
                    if (e.which == enterKey) {
                        $.ajax({
                            data: {
                                nombre_cuenta,
                                id_cuenta
                            },
                            url: '<?php echo base_url(); ?>/empresa/actualizar_nombre_cuenta',
                            type: "POST",
                            success: function(resultado) {
                                var resultado = JSON.parse(resultado);


                                if (resultado.resultado == 1) {
                                    $('#tabla_categorias').html(resultado.categorias)
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
                                        title: 'Nombre de cuenta actualizada'
                                    })

                                }
                            },
                        });


                    }
                } else if (nombre_cuenta == '') {
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
                        title: 'El nombre de la cuenta no puede estar vacio '
                    })
                }

            }
        </script>
        <!--/ Actualizar el nombre de la cuenta -->

        <!--Crear marca  -->
        <script>
            function generar_marca() {
                var url = document.getElementById("url").value;
                var marca = document.getElementById("nombre_marca").value;
                $.ajax({
                    data: {
                        marca,
                    },
                    url: '<?php echo base_url(); ?>/categoria/crear_marcas',
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);


                        if (resultado.resultado == 1) {
                            $("#agregar_marca").modal("hide");
                            $("#todas_las_marcas").html(resultado.marcas);

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
                                title: 'Marca creada'
                            })

                        }
                    },
                });




            }
        </script>




</body>
</body>

</html>