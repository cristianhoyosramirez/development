<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/template_boletas') ?>
<?= $this->section('title') ?>
Eventos
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>


<div class="card container">
    <div class="card-body">

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Cliente </th>
                    <th scope="col">Localidad</th>
                    <th scope="col">Estado</th>
                   
                    <th scope="col">Acción</th>

                </tr>
            </thead>
            <tbody>

                <?php $boletas = model('BoletasModel')->find(); ?>

                <?php foreach ($boletas as $detalle) : ?>
                    <tr>
                        <?php $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $detalle['nitcliente'])->first(); ?>
                        <th><?php echo $nombre_cliente['nombrescliente'] ?> </th>
                        <td><?php echo $detalle['localidad'] ?></td>
                        <td><?php echo $detalle['estado'] ?></td>
                        <td><a href="<?php echo base_url() ?>/images/<?php echo $detalle['id'] ?>.png" download>Descargar código QR</a></td>
                    </tr>

                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection('content') ?>