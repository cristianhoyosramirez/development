<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/template_boletas') ?>
<?= $this->section('title') ?>
Eventos
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<p class="text-center">Cover </p>
<div class="card container">
    <div class="card-body">
        <form action="<?= base_url('eventos/set_boletas') ?>" method="POST">
            <input value="<?php echo base_url() ?>" id="url" name="url" type="hidden">
            <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" name="id_usuario">
            <div class="row">
                <div class="col-md-4">
                    <label for="" class="form-label">Boleta </label>
                    <input type="text" class="form-control" id="codigo_qr" name="codigo_qr" placeholder="Escanea el código QR" autofocus>
                </div>
                <div class="col-md-4">
                    <label for="inputEmail4" class="form-label">Cliente </label>
                    <div class="input-group input-group-flat">
                        <input type="hidden" id="id_cliente_factura_pos" name="id_cliente_factura_pos">
                        <input type="text" class="form-control" autocomplete="off" id="clientes_factura_pos" name="clientes_factura_pos" placeholder="Buscar por nombre o identificación">

                    </div>
                    <div class="text-danger"><?= session('errors.clientes_factura_pos') ?></div>
                </div>

                <div class="col-md-4">
                    <label for="inputEmail4" class="form-label">Localidad</label>
                    <input type="text" class="form-control">
                    <div class="text-danger"><?= session('errors.localidad') ?></div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-md"><i class="mdi mdi-plus"></i> Generar entrada </button>
            </div>
        </form>
    </div>
</div>










<?= $this->endSection('content') ?>