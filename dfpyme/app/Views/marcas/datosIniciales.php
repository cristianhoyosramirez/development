<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/mesas') ?>
<?= $this->section('title') ?>
MESAS
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="card container">
    <div class="card-body">
        <form action="<?= base_url('categoria/actualizar_marca') ?>" method="POST">
            <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" name="id_usuario">
            <div class="row">
              
                <div class="col-md-6">
                    <label for="inputEmail4" class="form-label">Nombre marca</label>
                    <input type="hidden" value="<?php echo $id_marca ?> " id="id">
                    <input type="text" class="form-control" name="nombre" value="<?php  echo $nombre_marca ?>" utofocus>
                    <div class="text-danger"><?= session('errors.nombre') ?></div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-md"><i class="mdi mdi-plus"></i> Editar marca</button>
            </div>
        </form>
    </div>
</div>




<?= $this->endSection('content') ?>