<?php $session = session(); ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title><?= $this->renderSection('title') ?>&nbsp;-&nbsp;DF PYME</title>
    <!-- CSS files -->
    <link href="<?= base_url() ?>/Asstes/css/tabler.min.css" rel="stylesheet" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/Asstes/img/favicon.png">
     
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
        <script src="<?= base_url() ?>/Asstes/js/tabler.min.js"></script>
         
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