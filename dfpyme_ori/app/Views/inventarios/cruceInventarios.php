<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>


<div class="container">
    <div class="my-2">
        <div class="d-flex justify-content-between align-items-center">
            <p class="text-center w-100 m-0">Cruce de inventario</p>
            <form action="<?php echo base_url() ?>/consultas_y_reportes/reporte_cruce_inventarios">
                <button class="btn btn-outline-success ms-3" type="submit" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Exportar a excel ">Excel</button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="car-body">

            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <td scope="col">Codigo </td>
                        <td scope="col">Producto </td>
                        <td scope="col">Cantidad conteo  </td>
                        <td scope="col">Cantidad sistema </td>
                        <td scope="col">Diferencia inventario </td>
                        <td scope="col">Valor costo </td>
                        <td scope="col">Valor venta </td>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($productos as $detalle): ?>
                        <tr>
                            <td>
                                <?php echo $detalle['codigointernoproducto'] ?>
                            </td>
                            <td>
                                <?php echo $detalle['nombreproducto'] ?>
                            </td>
                            <td>
                                <?php echo $detalle['cantidad_inventario_fisico'] ?>
                            </td>
                            <td>
                                <?php echo $detalle['cantidad_inventario'] ?>
                            </td>
                            <td>
                                <?php echo $detalle['diferencia'] ?>
                            </td>
                            <td>
                                <?php echo "$ " . number_format($detalle['valor_costo'], 0, ",", ".") ?>
                            </td>
                            <td>
                                <?php echo "$ " . number_format($detalle['valor_venta'], 0, ",", ".") ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?php if (empty($productos)): ?>

                <p class="text-center text-primary h3">No productos para hacer cruce de inventario </p>

            <?php endif ?>
        </div>
    </div>
</div>


<?= $this->endSection('content') ?>