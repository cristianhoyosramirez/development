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
        <!-- Data tables -->
        <script src="<?= base_url() ?>/Assets/plugin/data_tables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url() ?>/Assets/plugin/data_tables/dataTables.bootstrap5.min.js"></script>
        <!-- Sweet alert -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
        <script>
            $(document).ready(function() {
                $('#usuarios').DataTable({
                        "language": {
                            "decimal": "",
                            "emptyTable": "No hay datos",
                            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                            "infoFiltered": "(Filtro de _MAX_ total registros)",
                            "infoPostFix": "",
                            "thousands": ",",
                            "lengthMenu": "Mostrar _MENU_ registros",
                            "loadingRecords": "Cargando...",
                            "processing": "Procesando...",
                            "search": "Buscar:",
                            "zeroRecords": "No se encontraron coincidencias",
                            "paginate": {
                                "first": "Primero",
                                "last": "Ultimo",
                                "next": "Próximo",
                                "previous": "Anterior"
                            },
                            "aria": {
                                "sortAscending": ": Activar orden de columna ascendente",
                                "sortDescending": ": Activar orden de columna desendente"
                            }

                        },
                        "order": [
                            [3, "desc"]
                        ]
                    }

                );
            });
        </script>
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
            $('#usuario_agregar').submit(function(e) {
                e.preventDefault();
                var form = this;
                let button = document.querySelector("#btn_agregar_usuario");
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
                                $("#staticBackdrop").modal("hide");

                                $(form)[0].reset();

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
                                    title: 'Usuario creado exitosamente '
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
            $(function() {
                $("#nuevo_usuario").on("shown.bs.modal", function(e) {
                    $("#documento_usuario").focus();
                });
            });
        </script>
<script>
    function cancelar_creacion_usuario(){
        $("#nuevo_usuario").modal("hide");
        document.getElementById('usuario_agregar').reset();
    }
</script>

</body>

</html>