<?php $user_session = session(); ?>
<?= $this->extend('template/consultas_reportes') ?>
<?= $this->section('title') ?>
CAJA
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <input type="hidden" id="url" value="<?php echo base_url() ?>">
                    <table class="table">
                        <thead class="table-dark">
                            <tr>
                                <td scope="col">Fecha </td>
                                <td scope="col">Base 0 </td>
                                <td scope="col">Base ico </td>
                                <td scope="col">Ico </td>
                                <td scope="col">Total</td>
                                <td scope="col">Accion</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($movimientos as $detalle) { ?>
                                <tr>
                                    <td><?php echo $detalle['fecha'] ?></td>
                                    <td><?php echo "$" . number_format($detalle['base_0'], 0, ",", ".") ?></td>

                                    <td><?php echo "$" . $detalle['base_ico'] ?></td>
                                    <td><?php echo $detalle['ico'] ?></td>
                                    <td><?php echo "$" . number_format($detalle['base_0'] + $detalle['base_ico'], 0, ",", ".") ?></td>
                                    <td>
                                        <div class="breadcrumb m-0">
                                            <input type="hidden" value="<?php echo base_url() ?>" name="url">
                                            <button type="submit" class="btn btn-primary" onclick="imprimir_reporte_de_caja_diario(<?php echo $detalle['id'] ?>)">Imprimir reporte</button>
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




<?= $this->endSection('content') ?>