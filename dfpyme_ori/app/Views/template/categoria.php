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
        <!-- Tabler Core -->
        <script src="<?= base_url() ?>/Assets/js/tabler.min.js"></script>
        <!--jQuery -->
        <script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
        <!-- Script locales -->
        <script src="<?= base_url() ?>/Assets/script_js/categoria/categoria.js"></script>
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
            $("#nueva_categoria").on("shown.bs.modal", function(e) {
                $("#nombre_categoria").focus();
            });
        </script>

        <script>
            function cancelar_crear_categoria() {
                $("#nueva_categoria").modal("hide");
                document.getElementById("nombre_categoria").value = ""
            }
        </script>


        <!--Crear categoria de producto -->
        <script>
            $('#crear_categoria').submit(function(e) {
                e.preventDefault();
                var form = this;
                let button = document.querySelector("#btn_crear_categoria");
                button.disabled = true;
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
                                $("#nueva_categoria").modal("hide");


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
                                    title: 'Categoria creada'
                                })
                                window.location.href = window.location.href
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
            function estado_categoria(opcion, id_categoria) {
                var url = document.getElementById("url").value;
                $.ajax({
                    data: {
                        opcion,
                        id_categoria
                    },
                    url: url + "/" + "categoria/actualizar_estado_categoria",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 0) {
                            $('#creacion_cliente_factura_pos').modal('hide');
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
                                title: 'Estado de categoria actualizado'
                            })

                            //window.location.href = window.location.href
                            $('#tabla_categorias').html(resultado.categorias)
                        }
                        if (resultado.resultado == 1) {
                            alert("No se pudo insertar");
                        }
                    },
                });


            }
        </script>

        <script>
            function asociar_impresora(opcion, id_categoria) {
                var url = document.getElementById("url").value;
                $.ajax({
                    data: {
                        opcion,
                        id_categoria
                    },
                    url: url + "/" + "categoria/actualizar_impresora",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 0) {
                            //$('#creacion_cliente_factura_pos').modal('hide');
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
                                title: 'Impresora asociada'
                            })

                            //window.location.href = window.location.href
                            $('#tabla_categorias').html(resultado.categorias)
                        }
                        if (resultado.resultado == 1) {
                            alert("No se pudo insertar");
                        }
                    },
                });


            }
        </script>

        <script>
            function sub_categoria(opcion, id_categoria) {
                var url = document.getElementById("url").value;
                
                $.ajax({
                    data: {
                        opcion,
                        id_categoria
                    },
                    url: url + "/" + "categoria/actualizar_sub_categoria",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 0) {
                            //$('#creacion_cliente_factura_pos').modal('hide');
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
                                title: 'Actualizacion de sub categoria '
                            })

                            //window.location.href = window.location.href
                            $('#tabla_categorias').html(resultado.categorias)
                        }
                        if (resultado.resultado == 1) {
                            alert("No se pudo insertar");
                        }
                    },
                });


            }
        </script>


        <script>
            function actualizar_categoria(e, nombre_categoria, codigo_categoria) {
                var enterKey = 13;

                if (nombre_categoria != '') {
                    if (e.which == enterKey) {
                        $.ajax({
                            data: {
                                nombre_categoria,
                                codigo_categoria
                            },
                            url: '<?php echo base_url(); ?>/categoria/actualizar_nombre',
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
                                        title: 'Nombre de categoria actualizado'
                                    })

                                }
                            },
                        });


                    }
                } else if (nombre_categoria == '') {
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
                        title: 'El campo nombre de la categoria no debe ir vacio '
                    })
                }

            }
        </script>






</body>

</html>