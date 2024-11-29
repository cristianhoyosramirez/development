<?= $this->extend('template/template') ?>
<?= $this->section('title') ?>
LISTADO DE IMPRESORAS
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>
<!--Sart row-->
<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Salones</a></li>
                    <li class="breadcrumb-item"><a href="#">Mesas</a></li>
                    <li class="breadcrumb-item"><a href="#">Usuarios</a></li>
                    <li class="breadcrumb-item"><a href="#">Empresa</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <a href="<?php echo base_url('impresora/datos_iniciales'); ?>" class="btn btn-warning btn-pill w-100">Agregar impresora</a>
        </div>
        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">LISTA GENERAL DE IMPRESORAS </p>
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
                    <table id="example" class="table  table-hover">
                        <thead class="table-dark">
                            <tr>
                                <td>CÓDIGO IMPRESORA</td>
                                <td>NOMBRE IMPRESORA</td>
                                <td></td>
                                <td>ACCIONES</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($impresoras as $detalle) { ?>
                                <tr>
                                    <td><?php echo $detalle['id'] ?></td>
                                    <td><?php echo $detalle['nombre'] ?></td>
                                    <td></td>

                                    <td>
                                        <form action="<?= base_url('impresora/editar') ?>" method="POST">
                                            <input type="hidden" value="<?php echo $detalle['id'] ?>" name="id_impresora">
                                            <button type="submit" class="btn btn-success" data-bs-toggle="modal">Editar</button>
                                        </form>
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