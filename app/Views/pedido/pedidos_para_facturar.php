<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/pedidos_para_facturar') ?>
<?= $this->section('title') ?>
PEDIDOS
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>


<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('mesas/todas_las_mesas') ?>">Mesas</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('salones/salones') ?>">Salones</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('pedido/pedidos_para_facturar') ?>">Ver todos los pedidos</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">LISTA DE PEDIDOS </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>



<!--Sart row-->
<div class="container">
    <br>
    <div class="container">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table  table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <td>Ubicación</td>
                                    <td>No.P</td>
                                    <td>Nota</td>
                                    <td>Fecha y hora </td>
                                    <td>valor pedido</td>
                                    <td>Acciones</td>
                                </tr>
                            </thead>
                            <tbody id="prueba">

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- end row -->
</div>


<?= $this->endSection('content') ?>

