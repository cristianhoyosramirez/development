<?= $this->extend('template/mesas') ?>
<?= $this->section('title') ?>
LISTADO DE CUENTAS RERIRO DE DINERO
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>
<!--Sart row-->
<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <a href="<?php echo base_url('devolucion/crear_cuenta'); ?>" class="btn btn-warning btn-pill w-100">Agregar cuenta</a>
        </div>
        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">LISTA CUENTAS DE RETIRO  </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="mesas" class="table  table-hover table-borderless">
                        <thead class="table-dark">
                            <tr>
                                <td>Nombre cuenta</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cuentas as $detalle) { ?>
                                <tr>
                                    <td><input type="text" class="form-control" value="<?php echo $detalle['nombre_cuenta'] ?>"  onkeyup="actualizar_nombre_cuenta(event, this.value,<?php echo $detalle['id'] ?>)" placeholder="Cambiar el nombre de la cuenta"></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?= $this->include('cuentas_retiro/modal_editar_cuenta_retiro') ?>
<?= $this->endSection('content') ?>
<!-- end row -->