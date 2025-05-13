<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
VERSIÓN
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="text-center mb-4">
        <p class="h4 text-primary">Versión actual: <?php echo $version; ?></p>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-dark text-white">
            <strong>Historial de versiones</strong>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <h5 class="text-secondary">Versión [1.0.1] - 2025-05-07</h5>
                <h6 class="text-muted">Correcciones:</h6>
                <ul>
                <li>Se corrigió el reporte de costo de productos, ya que anteriormente, si en una fecha no se registraban ventas, el informe no se generaba. Ahora, el sistema toma el rango de productos según los <strong>Según fechas válidas </strong>, permitiendo identificar correctamente los días sin ventas y generando un reporte más preciso.</li>
                </ul>
            </div>

            <div class="mb-4">
                <h5 class="text-secondary">Versión [1.0.0] - 2025-05-05</h5>
                <h6 class="text-muted">Características iniciales:</h6>
                <ul>
                    <li>Primera versión funcional del sistema.</li>
                    <li>Control básico de inventario.</li>
                    <li>Generación de reportes estándar.</li>
                </ul>
            </div>
        </div>
    </div>
</div>




<?= $this->endSection('content') ?>