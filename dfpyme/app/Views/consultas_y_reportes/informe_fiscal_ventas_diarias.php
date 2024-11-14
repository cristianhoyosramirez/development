<?php $user_session = session(); ?>
<?= $this->extend('template/consultas_reportes') ?>
<?= $this->section('title') ?>
REPORTE DE VENTAS DIARIAS
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>
<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Consultas y reportes</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('consultas_y_reportes/informe_fiscal_de_ventas'); ?>">Informe fiscal de ventas diarias </a></li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">INFORME FISCAL DE VENTAS </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>


<div class="card container">
    <div class="card-body">
        <div class="container">

        </div>
        <div class="col-md-6">
            <label for="inputEmail4" class="form-label">Fecha de informe</label>
            <input type="text" class="form-control" id="fecha_reporte" value="<?php echo date('Y-m-d') ?>"> <br>
            <button type="button" onclick="ventas_diarias()" class="btn btn-success">Generar</button>
        </div>
        <input type="hidden" value="<?= base_url() ?>" id="url"> <br>
        <div id="reporte_fiscal_de_ventas">

        </div>

    </div>
</div>

<?= $this->endSection('content') ?>