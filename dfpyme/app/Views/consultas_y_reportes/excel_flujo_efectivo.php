<?php
header("Pragma: public");
header("Expires: 0");
$filename = "Movimiento_efectivo.xls";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

?>
<table>
    <tr>

        <td>
            <p><?php echo $datos_empresa[0]['nombrecomercialempresa'] ?></p>
        </td>
    </tr>
    <tr>

        <td>
            <p>Telefono:<?php echo $datos_empresa[0]['telefonoempresa'] ?></p>
        </td>
    </tr>
    <tr>

        <td>
            <p>Direccion:<?php echo $datos_empresa[0]['direccionempresa'] ?></p>
        </td>
    </tr>
</table>
<br>
<table border="1">
    <tbody>
        <tr style="background-color:black;" ><?php $cuentas = model('retiroModel')->retiros_cuentas($fecha_inicial, $fecha_final)   ?>
            <?php foreach ($cuentas as $detalle_cuentas) { ?>
                <td ><p style="color:#FFFFFF">FECHA</p></td>
                <td ><p style="color:#FFFFFF"><?php echo $detalle_cuentas['nombre_cuenta']; ?></p></td>
                <td ><p style="color:#FFFFFF">CONCEPTO</p></td>
                <td ><p style="color:#FFFFFF">VALOR</p></td>
                <?php $movimientos = model('retiroModel')->retiros($fecha_inicial, $fecha_final, $detalle_cuentas['id_cuenta_retiro']);     ?>
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
    <td style="background-color:#a9ffa8;">TOTAL:</td>
    <td style="background-color:#a9ffa8;"><?php echo number_format($detalle_cuentas['total'], 0, ",", "."); ?></td>
    </tr>

<?php } ?>

<tr>
    <td></td>
    <td></td>
    <td> <p style="font-size: 100%;" > TOTAL SALIDAS: <?php $total = model('retiroModel')->total_retiros($fecha_inicial, $fecha_final); ?> </p></td>
    <td><?php echo number_format($total[0]['total'], 0, ",", "."); ?></td>
</tr>

    </tbody>
</table>