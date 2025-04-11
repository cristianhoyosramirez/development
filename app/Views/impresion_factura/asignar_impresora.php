<?= $this->extend('template/pre_factura') ?>
<?= $this->section('title') ?>
LISTADO DE IMPRESORAS
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>
<!--Sart row-->
<div class="container">
    <div class="row text-center align-items-center flex-row">
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="col-12">
        <?php if ($resultado == 0) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>No hay impresora asignada desea asignar una </strong> 
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>
        <div class="card">
            <div class="card-body">
                <form class="row g-3" action="<?= base_url('administracion_impresora/asignar_impresora_facturacion') ?>" method="POST">
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">Impresoras</label>
                        <select id="id_impresora" name="id_impresora" class="form-select">
                            <?php foreach ($impresoras as $detalle) { ?>
                                <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['nombre'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Asignar impresora para facturación </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<?= $this->endSection('content') ?>
<!-- end row -->