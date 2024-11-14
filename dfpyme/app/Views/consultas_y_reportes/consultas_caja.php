<?php $user_session = session(); ?>
<?= $this->extend('template/consultas_reportes') ?>
<?= $this->section('title') ?>
REPORTE DE VENTAS DIARIAS
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>
<div class="card container">
    <div class="card-body">
        <p class="text-center fs-3 fw-bold text-primary">CONSULTAS DE MOVIMIENTO DE CAJA </p>
        <form class="row g-3" id="formulario_movimiento_caja" action="<?= base_url('consultas_y_reportes/datos_consultas_caja_por_fecha') ?>" method="POST">
            <div class="col-3">
                <select class="form-select" name="numero_caja">

                    <?php foreach ($caja as $detalle) { ?>
                        <option value="<?php echo $detalle['idcaja'] ?>"><?php echo $detalle['numerocaja'] ?></option>
                    <?php } ?>
                </select>
                <div class="text-danger"><?= session('errors.fecha_inicial_caja') ?></div>
            </div>

            <div class="col-3">
                <button type="submit" class="btn btn-primary">Buscar </button>
            </div>


        </form>
        <br>

    </div>
</div>

<?= $this->endSection('content') ?>