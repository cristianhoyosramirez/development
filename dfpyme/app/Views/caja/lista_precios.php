<?php $user_session = session(); ?>
<?= $this->extend('template/caja') ?>
<?= $this->section('title') ?>
CAJA
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<!--Sart row-->
<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">

        </div>

        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">FACTURAR CON LISTA DE PRECIOS </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>
<br>
<div class="card container">
    <div class="card-body">
        <br>
        <form class="row row-cols-lg-auto g-3 align-items-center" action="<?= base_url('caja/actualizar_lista_precios') ?>" method="POST">
            <div class="row mb-3">
                <label for="colFormLabel" class="col-sm-8 col-form-label">Permitir visualizar lista de precio 2 al momento de facturar</label>
                <div class="col-sm-2">
                    <select class="form-select" name="list_precios">
                        <option value=0 <?php if ($lista_precios == 'f') : ?>selected <?php endif; ?>>No</option>
                        <option value=1 <?php if ($lista_precios == 't') : ?>selected <?php endif; ?>>Si</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </form>
    </div>




    <?= $this->endSection('content') ?>