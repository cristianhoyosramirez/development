<div class="hr-text text-green">
    <p class="h3 text-success">movimiento de efectivo clasificado en cuentas y rubros </p>
</div>
<div class="row row-cols-2 row-cols-lg-5 g-1 g-lg-3">

    <div class="col">
        <form action="<?= base_url('consultas_y_reportes/excel_reporte_flujo_efectivo') ?>" method="POST">
           <input type="hidden" name="fecha_inicial" value="<?php  echo $fecha_inicial?>" >
           <input type="hidden" name="fecha_final" value="<?php  echo $fecha_final?>" >
            <button type="submit" class="btn btn-success">Excel</button>
        </form>

    </div>
</div>


<br>
<table class="table table-striped table-borderless table-hover">
    <tbody>
        <tr><?php $cuentas = model('retiroModel')->retiros_cuentas($fecha_inicial, $fecha_final)   ?>
            <?php foreach ($cuentas as $detalle_cuentas) { ?>
                <td class="table-dark">FECHA</td>
                <td class="table-dark"><?php echo $detalle_cuentas['nombre_cuenta']; ?></td>
                <td class="table-dark">CONCEPTO</td>
                <td class="table-dark">VALOR</td>
                <?php $movimientos = model('retiroModel')->retiros($fecha_inicial, $fecha_final, $detalle_cuentas['id_cuenta_retiro']) ?>
                <?php foreach ($movimientos as $detalle_movimientos) { ?>
        <tr>
            <td> <?php echo $detalle_movimientos['fecha']  ?></td>
            <td>
                <?php

                    $id_rubro = model('retiroModel')->select('id_rubro_cuenta_retiro')->where('id', $detalle_movimientos['idretiro'])->first();
                    $nombre_rubro = model('rubrosModel')->select('nombre_rubro')->where('id', $id_rubro['id_rubro_cuenta_retiro'])->first();
                    echo  $nombre_rubro['nombre_rubro'];
                ?>

            </td>
            <td>
                <?php echo $detalle_movimientos['concepto']  ?>
            </td>
            <td>
                <?php echo number_format($detalle_movimientos['valor'], 0, ",", ".")  ?>
            </td>

        </tr>
    <?php } ?>
    <td></td>
    <td></td>
    <td class="table-success">TOTAL:</td>
    <td class="table-success"><?php echo number_format($detalle_cuentas['total'], 0, ",", "."); ?></td>
    </tr>

<?php } ?>

<tr>
    <td></td>
    <td></td>
    <td>TOTAL SALIDAS: <?php $total = model('retiroModel')->total_retiros($fecha_inicial, $fecha_final); ?> </td>
    <td><?php echo number_format($total[0]['total'], 0, ",", "."); ?></td>
</tr>

    </tbody>
</table>