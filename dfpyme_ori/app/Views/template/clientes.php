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
    <!-- Select 2 -->
    <link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
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
        <!-- Tabler Core -->
        <script src="<?= base_url() ?>/Assets/js/tabler.min.js"></script>
        <!-- JQuery -->
        <script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
        <!-- Data tables -->
        <script src="<?= base_url() ?>/Assets/plugin/data_tables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url() ?>/Assets/plugin/data_tables/dataTables.bootstrap5.min.js"></script>

        <!-- Sweet alert -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
        <!--select2 -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/select_2.js"></script>




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
            function nuevo_cliente() {
                $("#crear_cliente").modal("show");



            }
        </script>

        <!-- Data table server side listado de clientes -->
        <script>
            $(document).ready(function() {
                var url = document.getElementById("url").value;
                var table = $('#clientes').DataTable({
                    responsive: true,
                    "order": [
                        [0, "desc"]
                    ],
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
                    serverSide: true,
                    processing: true,
                    ajax: {
                        url: url + '/clientes/todos_los_clientes',
                        type: 'get',
                    },
                    "aoColumnDefs": [{
                            "bSortable": false,
                            "aTargets": [5]
                        },

                    ]
                });

            });
        </script>


        <!--Crear clientes -->
        <script>
            $('#cliente_agregar').submit(function(e) {
                e.preventDefault();
                var form = this;
                let button = document.querySelector("#btn_crear_cliente");
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
                                $("#crear_cliente").modal("hide");
                                $(form)[0].reset();
                                table = $('#clientes').DataTable();
                                table.draw();
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
                                    title: 'Cliente creado exitosamente'
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
            function editar_cliente(id_cliente) {
                var url = document.getElementById("url").value;
                $.ajax({
                    data: {
                        id_cliente,
                    },
                    url: url + "/" + "clientes/editar_cliente",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {
                            $('#datos_editar_cliente').html(resultado.datos_cliente)
                            
                            myModal = new bootstrap.Modal(
                                document.getElementById("editar_cliente"), {}
                            );
                            myModal.show();



                            /* const Toast = Swal.mixin({
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
                                 title: 'Modificación de aperura éxitoso'
                             })

                             $("#edicion_de_apertura_de_caja").modal("hide");
                             $("#valor_modificado_apertura").html(resultado.valor_apertura);
                             $("#nuevo_saldo").html(resultado.saldo);
                             $("#cambiar_valor_apertura").val(resultado.val_apertura); */

                        }
                    },
                });
            }
        </script>








</body>

</html>