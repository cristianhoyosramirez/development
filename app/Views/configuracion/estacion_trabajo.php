<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>




<div class="container">
    <p class="text-center text-primary h3">Configuración de estación de trabajo </p>
    <?php foreach ($cajas as $detalle) : ?>
        <form action="<?php echo base_url() ?>/configuracion/actualizar_caja" method="post">
            <input type="hidden" value="<?php echo $detalle['idcaja']  ?>" name="id_caja" id="id_caja">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Número caja </label>
                    <input type="text" class="form-control" value="<?php echo $detalle['numerocaja'] ?>" name="numerocaja">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Impresora</label>
                    <select name="id_impresora" id="id_impresora" class="form-select">

                        <?php foreach ($impresoras as $valor) { ?>
                            <option value="<?php echo $valor['id'] ?>" <?php if ($valor['id'] == $detalle['id_impresora']) : ?>selected <?php endif; ?>><?php echo $valor['nombre'] ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-outline-success">Aceptar</button>
                <button type="submit" class="btn btn-outline-danger">Cancelar </button>
            </div>
        </form>
    <?php endforeach ?>
</div>

<?= $this->endSection('content') ?>