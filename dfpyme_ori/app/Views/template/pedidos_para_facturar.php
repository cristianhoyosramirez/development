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
                <?= $this->include('ventanas_modal_pedido/resumen_pedido') ?>
                <?= $this->include('ventanas_modal_pedido/eliminacion_de_pedido') ?>
            </div>
            <?= $this->include('layout/footer') ?>
        </div>
        <!-- Libs JS -->
        <!-- Tabler Core -->
        <script src="<?= base_url() ?>/Assets/js/tabler.min.js"></script>
        <!-- J QUERY -->
        <script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
        <!-- Locales -->
        <script src="<?= base_url() ?>/Assets/script_js/pedidos/eliminacion_de_pedido.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/pedidos/detalle_pedido.js"></script>
        <!-- Sweet alert -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>

        <script type="text/javascript">
            function tiempoReal() {
                $.ajax({

                    url: '<?php echo base_url(); ?>/pedido/pedidos_para_facturacion',
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);
                        if (resultado.resultado == 1) {
                            $("#prueba").html(resultado.pedidos);


                        } else {
                            $("#prueba").html(resultado.pedidos);

                        }
                    },
                });
            }
            setInterval(tiempoReal, 1000);
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

    

</body>

</html>