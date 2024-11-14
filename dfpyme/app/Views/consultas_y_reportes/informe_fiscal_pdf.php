<?php $user_session = session(); ?>
<?= $this->extend('template/consultas_reportes') ?>
<?= $this->section('title') ?>
REPORTE DE VENTAS DIARIAS
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>
<div class="card container">
    <div class="card-body">

        <div class="col-md-6">
            <label for="inputEmail4" class="form-label">Fecha de informe</label>
            <input type="date" class="form-control" id="fecha_reporte"> <br>
            <button type="button" onclick="ventas_diarias()" class="btn btn-success">Generar</button>
        </div>
        <input type="hidden" value="<?= base_url() ?>" id="url"> <br>
        <div id="reporte_fiscal_de_ventas">
        </div>
        <iframe class="embed-responsive-item container" width="1250" height="600" src="<?= base_url('consultas_y_reportes/generar_informe_fiscal_ventas_pdf') ?>"></iframe>

    </div>
</div>

<?= $this->endSection('content') ?>