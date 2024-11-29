<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>
<style>
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        /* Ocupa toda la altura de la viewport */
    }
</style>

<?= $this->section('content') ?>
<div class="container">


    <div class="card">
        <div class="card-header">
            <h3 class="card-title text-center">URL de conexión para dispositivos móviles</h3>
        </div>

        <div class="card-body">
            <div class="row justify-content-center">
                <?= $simple ?>
            </div>
        </div>
        <!-- Card footer -->
        <div class="card-footer">
            <p class="text-primary  ">Instrucciones para la conexion de tablets y telfonos</p>
            <p>1. Escanear el codigo QR con la camara del dispositivo móvil</p>
            <p>2. Una vez escaneado el código abrirlo con el navegador </p>
            <p><img src="<?php echo base_url() ?>/Assets/img/enlace.jpg" alt="" ></p>
            <p>3. Una vez al darle click le llevara a la siguiente pantalla </p>
            <p><img src="<?php echo base_url() ?>/Assets/img/enlace.jpg" alt="" ></p>

        </div>
    </div>


</div>
<?= $this->endSection('content') ?>