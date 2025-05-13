<?php
header("Pragma: public");
header("Expires: 0");
$filename = "Inventario.xls";
header("Content-type: text/html; charset=utf-8"); // Establecer la codificación a UTF-8
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

$total = 0; // Inicializar la variable $total

use App\Libraries\Impuestos; 
?>
<table>
    <tbody>
        <tr>
            <td>REPORTE DE INVENTARIO</td>
        </tr>
        <tr>
            <th><?php echo utf8_decode($datos_empresa[0]['nombrecomercialempresa']) ?></th>
            <th style="text-align:left; font: 80% normal; border:none;"></th>
        </tr>
        <tr>
            <td style="text-align:left; font: 80% normal; border:none;"><?php echo utf8_decode($datos_empresa[0]['nombrejuridicoempresa']) ?></td>
            <th style="text-align:left; font: 80% normal; border:none; "></th>
        </tr>
        <tr>
            <td style="text-align:left; font: 80% normal; border:none;">Nit: <?php echo $datos_empresa[0]['nitempresa'] ?></td>
            <td style="text-align:left; font: 80% normal; border:none; "> </td>
        </tr>
        <tr>
            <td style="text-align:left; font: 80% normal; border:none; "><?php #echo $regimen ?></td>
            <td style="text-align:left; font: 80% normal; border:none;"></td>
            <td style="text-align:left; font: 80% normal; border:none;">
                </p>
            </td>
        </tr>
        <tr>
            <td style="text-align:left; font: 80% normal; border:none; "><?php echo $datos_empresa[0]['direccionempresa']  ?></td>
            <td style="text-align:left; font: 80% normal; border:none; "></td>
        </tr>
    </tbody>
</table>

<table>
    <thead style="background-color: black; color: white;">
        <tr>
            <td style="text-align:left; font: 80% normal; border:none; background-color:#000; color: white; ">
                Categor&iacute;a
            </td>
            <td style="text-align:left; font: 80% normal; border:none; background-color:#000; color: white; ">
                C&oacute;digo interno
            </td>
            <td style="text-align:left; font: 80% normal; border:none; background-color:#000; color: white; ">
                Nombre producto
            </td>
            <td style="text-align:left; font: 80% normal; border:none; background-color:#000; color: white; ">
                Cantidad
            </td>
            <td style="text-align:left; font: 80% normal; border:none; background-color:#000; color: white; ">
                Costo
            </td>
            <td style="text-align:left; font: 80% normal; border:none; background-color:#000; color: white; ">
                Total costo
            </td>
            <td style="text-align:left; font: 80% normal; border:none; background-color:#000; color: white; ">
                %
            </td>
            <td style="text-align:left; font: 80% normal; border:none; background-color:#000; color: white; ">
                Impuesto
            </td>
            <td style="text-align:left; font: 80% normal; border:none; background-color:#000; color: white; ">
                Valor venta
            </td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $detalle) : ?>
            <tr>
                <td style="text-align: left; border: 1px solid black;"> <?php echo $detalle['nombrecategoria'] ?></td>
                <td style="text-align: left; border: 1px solid black;"> <?php echo $detalle['codigointernoproducto'] ?></td>
                <td style="text-align: left; border: 1px solid black;"> <?php echo $detalle['nombreproducto'] ?></td>
                <td style="text-align: left; border: 1px solid black;">
                    <?php
                    $cantidad = model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                    echo  $cantidad['cantidad_inventario']  ?></td>
                <td style="text-align: left; border: 1px solid black;"> <?php echo number_format($detalle['precio_costo'], 0, ",", ".") ?></td>
                <td style="text-align: left; border: 1px solid black;"> <?php echo number_format(($detalle['precio_costo'] * $cantidad['cantidad_inventario']), 0, ",", ".") ?></td>
                <?php
                $impuestos = new Impuestos();
                $calculo = $impuestos->calcular_impuestos($detalle['codigointernoproducto'], ($detalle['valorventaproducto'] * $cantidad['cantidad_inventario']), $detalle['valorventaproducto'], $cantidad['cantidad_inventario']);
                $aplica_inc = model('productoModel')->select('aplica_ico')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                $regimen = model('empresaModel')->select('idregimen')->first();
                if ($regimen['idregimen'] == 1) {
                    if ($aplica_inc['aplica_ico'] == 't') {
                        $porcentaje = 8;
                        $impuesto = 'INC';
                    }
                    if ($aplica_inc['aplica_ico'] == 'f') {
                        $id_iva = model('productoModel')->select('idiva')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                        $porcet = model('ivaModel')->select('valoriva')->where('idiva', $id_iva['idiva'])->first();
                        $porcentaje = $porcet['valoriva'];
                        $impuesto = 'IVA';
                    }
                }
                if ($regimen['idregimen'] == 2) {
                    $porcentaje = 0;
                    $impuesto = 0;
                }
                ?>
                <td style="text-align: left; border: 1px solid black;"> <?php echo $porcentaje ?></td>
                <td style="text-align: left; border: 1px solid black;"> <?php echo $impuesto ?></td>
                <td style="text-align: left; border: 1px solid black;"> <?php echo number_format($detalle['valorventaproducto'], 0, ",", ".") ?></td>
            </tr>
            <?php
                // Cálculo del valor total de cada fila y sumarlo al total
                $total += ($detalle['precio_costo'] * $cantidad['cantidad_inventario']);
            ?>
        <?php endforeach ?>
        <tr>
            <td colspan="8" style="text-align:right; font: 80% normal; border:none; "><span style="font-size: 120%; font-weight: bold;">Valor Total:</span></td>
            <td style="text-align:left; font: 80% normal; border:none;"><span style="font-size: 120%; font-weight: bold;"><?php echo number_format($total, 0, ",", ".") ?></span></td>
        </tr>
    </tbody>
</table>
