<?php
header("Pragma: public");
header("Expires: 0");
$filename = "Movimiento_efectivo.xls";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
?>

<style type="text/css">
    thead tr td {
        position: sticky;
        top: 0;
        background-color: #000009;
        color: #ffffff;
    }
</style>
<p style="aling:center"> Consulta de ingresos</p>

<table>
    <thead>
        <tr style="background-color: #000009; color: #ffffff;">
            <td scope="col">FECHA</td>
            <td scope="col">HORA</td>
            <td scope="col">DOCUMENTO</td>
            <td scope="col">VALOR</td>
            <td scope="col">PROPINA</td>
            <td scope="col">TOTAL</td>
            <td scope="col">EFECTIVO</td>
            <td scope="col">TRANSFERENCIA</td>
            <td scope="col">TOTAL PAGO</td>
            <td scope="col">CAMBIO</td>
            <td scope="col">USUARIO</td>
        </tr>
    </thead>
    <tbody class="table-scroll">
        <?php foreach ($movimientos as $valor) :

            $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $valor['id_mesero'])->first();

        ?>
            <tr>
                <td><?php echo $valor['fecha'] ?></td>
                <td><?php echo date("h:i A", strtotime($valor['hora'])) ?></td>
                <td><?php echo $valor['documento'] ?></td>
                <td><?php echo "$ " . number_format($valor['valor'], 0, ",", ".") ?></td>
                <td><?php echo "$ " . number_format($valor['propina'], 0, ",", ".") ?></td>
                <td><?php echo "$ " . number_format($valor['total_documento'], 0, ",", ".") ?></td>
                <td><?php echo "$ " . number_format($valor['recibido_efectivo'], 0, ",", ".") ?></td>
                <td><?php echo "$ " . number_format($valor['recibido_transferencia'], 0, ",", ".") ?></td>
                <td><?php echo "$ " . number_format($valor['total_pago'], 0, ",", ".") ?></td>
                <td><?php echo "$ " . number_format($valor['cambio'], 0, ",", ".") ?></td>
                <td><?php echo $nombre_usuario['nombresusuario_sistema'] ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<br>

<table class="table">

    <tbody class="table-scroll">

        <tr style="background-color: #000009; color: #ffffff;">
            <td class="table-dark">
                <p>Ventas POS </p>
                <p><?php echo $ventas_pos ?></p>
            </td>
            <td class="table-dark">
                <p>ELECTRONICA:</p>
                <p><?php echo $ventas_electronicas ?></p>
            </td>
            <td class="table-dark">
                <p>VALOR NETO:</p>
                <p> <?php echo $ventas_pos ?></p>
            </td>
            <td class="table-dark">
                <p>PROPINA:</p>
                <p><?php echo $propinas ?></p>
            </td>
            <td class="table-dark">
                <p>TOTAL DOCUMENTO:</p>
                <p> <?php echo $total_ingresos ?></p>
            </td>
            <td class="table-dark">
                <p>EFECTIVO:</p>
                <p> <?php echo $efectivo ?></p>
            </td>
            <td class="table-dark">
                <p>TRANSFERENCIA:</p>
                <p><?php echo $transferencia ?></p>
            </td>
            <td class="table-dark">
                <p>CAMBIO:</p>
                <p><?php echo $cambio ?>
            </td>
            <td class="table-dark">
                <p>TOTAL INGRESOS:</p>
                <p><?php echo $total_ingresos ?></p>
            </td>

        </tr>


    </tbody>
</table>