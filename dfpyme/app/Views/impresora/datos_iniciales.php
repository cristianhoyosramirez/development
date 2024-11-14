<?= $this->extend('template/template') ?>
<?= $this->section('title') ?>
IMPRESORAS
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="card container">
    <div class="card-body">
        <form action="<?= base_url('impresora/salvar') ?>" method="POST">
            <div class="row">
                <div class="col-md-3">
                    <label for="inputEmail4" class="form-label">Nombre impresora </label>
                    <input type="text" class="form-control" name="nombre_impresora" autofocus>
                    <div class="text-danger"><?= session('errors.nombre_impresora') ?></div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-md"><i class="mdi mdi-plus"></i> Crear impresora</button>
            </div>
        </form>
    </div>
</div>




<?= $this->endSection('content') ?>