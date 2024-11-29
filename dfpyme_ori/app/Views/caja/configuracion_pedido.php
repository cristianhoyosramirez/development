<?php $user_session = session(); ?>
<?= $this->extend('template/caja') ?>
<?= $this->section('title') ?>
CAJA
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>


<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Ventas</a></li>
                    <li class="breadcrumb-item"><a href="#">Caja</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('caja/apertura') ?>">Apertura de caja</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">CONFIGURACION DE PEDIDO </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>



<div class="card container">
    <div class="card-body">
        <form action="<?= base_url('administracion_impresora/actualizar_configuracion_pedido') ?>" method="POST">
            <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" name="id_usuario">
            <div class="row">
                <div class="col-md-6">
                    <label for="inputEmail4" class="form-label">Los productos se adicionan al pedido en lineas separadas ? </label>
                    <select class="form-select select2" name="actualizar_pedido" >
                        <?php if ($configuracion == 1) { ?>
                            <option value="1" selected>SI</option>
                            <option value="0">NO</option>
                        <?php } ?>
                        <?php if ($configuracion == 0) { ?>
                        <option value="0">NO</option>
                        <option value="1" selected>SI</option>
                        <?php } ?>
                    </select>
                    <div class="text-danger"><?= session('errors.salon') ?></div>
                </div>

            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-md"><i class="mdi mdi-plus"></i> Actualizar </button>
            </div>
        </form>
    </div>
</div>




<?= $this->endSection('content') ?>