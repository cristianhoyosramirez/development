<?= $this->extend('template/producto') ?>
<?= $this->section('title') ?>
PRODUCTO
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<!--Star Breadcum-->
<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Facturación</a></li>
                    <li class="breadcrumb-item"><a href="#">Facturación</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">REPORTE DE VENTAS DE PRODUCTO POR FECHA Y HORA </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>

<!-- End Breadcum-->



<div class=" container col-md-12">
    <div class="card">

        <div class="card-body">
            <form class="row g-3" action="<?= base_url('consultas_y_reportes/tabla_consultar_producto') ?>" method="POST" id="consultar_producto">
                <input type="hidden" value="<?php echo base_url(); ?>" id="url">
                <div class="col-md-3">
                    <label for="inputEmail4" class="form-label">Fecha inicial </label>
                    <input type="date" class="form-control" id="fecha_inicial" name="fecha_inicial" onkeyup="saltar(event,'hora_inicial')" value="<?php echo date('Y-m-d'); ?>" autofocus>
                    <span class="text-danger" id="error_fecha_inicial"></span>
                </div>
                <!-- <div class="col-md-3">
                    <label for="inputPassword4" class="form-label">Hora inicial </label>
                    <input type="hidden" class="form-control" id="hora_inicial" name="hora_inicial" onkeyup="saltar(event,'fecha_final')">

                    <span class="text-danger" id="error_hora_inicial"></span></h1>
                </div>-->

                <input type="hidden" class="form-control" id="hora_inicial" name="hora_inicial" onkeyup="saltar(event,'fecha_final')">


                <div class="col-3">
                    <label for="inputAddress" class="form-label">Fecha final </label>
                    <input type="date" class="form-control" id="fecha_final" name="fecha_final" onkeyup="saltar(event,'hora_final')" value="<?php echo date('Y-m-d'); ?>">
                    <span class="text-danger" id="error_fecha_final"></span>
                </div>
                <!--  <div class="col-3">
                    <label for="inputAddress2" class="form-label">Hora final </label>
                    <input type="time" class="form-control" id="hora_final" name="hora_final" onkeyup="saltar(event,'buscar_producto')">
                    <span class="text-danger" id="error_hora_final"></span>
                </div> -->

                <input type="hidden" class="form-control" id="hora_final" name="hora_final" onkeyup="saltar(event,'buscar_producto')">

                <div class="col-12">
                    <button type="button" id="buscar_producto" onclick="busqueda_producto()" class="btn btn-primary">Buscar</button>
                </div>
            </form>

        </div>
    </div>

    <?= $this->endSection('content') ?>