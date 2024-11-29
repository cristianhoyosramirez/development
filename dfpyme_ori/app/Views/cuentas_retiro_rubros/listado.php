<?= $this->extend('template/mesas') ?>
<?= $this->section('title') ?>
LISTADO DE CUENTAS RERIRO DE DINERO
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>
<!--Sart row-->
<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <a href="<?php echo base_url('devolucion/crear_rubro'); ?>" class="btn btn-warning btn-pill w-100">Agregar rubro</a>
        </div>
        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">LISTA GENERAL CUENTAS DE RUBROS DE DINERO </p>
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
                    <table id="mesas" class="table  table-hover">
                        <thead class="table-dark">
                            <tr>
                                <td>Cuenta</td>
                                <td>Nombre del rubro</td>
                                <td>Acciones</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rubros as $detalle) { ?>
                                <tr>
                                    <td> <?php echo $detalle['nombre_cuenta'] ?> </td>
                                    <td><?php echo $detalle['nombre_rubro'] ?></td>
                                    <td>
                                        <div class="breadcrumb m-0">
                                            <form action="<?= base_url('devolucion/editar_rubro') ?>" method="POST">
                                                <input type="hidden" value="<?php echo $detalle['id'] ?>" name="id">
                                                <button type="submit" class="btn btn-primary">Editar</button>
                                            </form> &nbsp; 
                                        </div>
                                    </td>
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
<?= $this->endSection('content') ?>
<!-- end row -->