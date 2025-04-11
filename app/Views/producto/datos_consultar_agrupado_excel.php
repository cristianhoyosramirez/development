<?php
header("Pragma: public");
header("Expires: 0");
$filename = "Reporte de producto .xls";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

?>


<style>
    table {
        width: 100%;
        margin: 10px auto;
        border-collapse: collapse;
    }

    thead {
        background-color: lightgray;
        color: black;
    }

    th,
    td {

        border: 1px solid #666666;
    }
</style>



<table>
    <tbody>
        <tr>
            <th style="text-align:left; font:   80% nomal; border:none;"><?php echo $datos_empresa[0]['nombrecomercialempresa'] ?></th>
            <th style="text-align:left; font:   80% nomal; border:none;"></th>
            <th style="text-align:left; font:   80% nomal; border:none; ">INFORME DE VENTAS POR CATEGORIA</th>
        </tr>
        <tr>
            <td style="text-align:left; font:   80% nomal; border:none;"><?php echo $datos_empresa[0]['nombrejuridicoempresa'] ?></td>
            <th style="text-align:left; font:   80% nomal; border:none; "></th>

        </tr>
        <tr>
            <td style="text-align:left; font:   80% nomal; border:none;">Nit: <?php echo $datos_empresa[0]['nitempresa'] ?></td>
            <td style="text-align:left; font:   80% nomal; border:none; "> </td>

        </tr>
        <tr>
            <td style="text-align:left; font:   80% nomal; border:none; "><?php echo $regimen ?></td>
            <td style="text-align:left; font:   80% nomal; border:none;"></td>
            <td style="text-align:left; font:   80% nomal; border:none;">
                </p>
            </td>
        </tr>
        <tr>
            <td style="text-align:left; font:   80% nomal; border:none; "><?php echo $datos_empresa[0]['direccionempresa'] . " " . $nombre_ciudad . " " . $nombre_departamento ?></td>
            <td style="text-align:left; font:   80% nomal; border:none; "></td>
            <td style="text-align:left; font:   80% nomal; border:none; ">Desde <?php echo $fecha_inicial ?> Hasta <?php echo $fecha_final ?></td>
        </tr>
    </tbody>
</table>

<table>
    <tbody>
        <?php foreach ($categorias as $detalle) { ?>
            <?php $nombre_categoria = model('categoriasModel')->select('nombrecategoria')->where('codigocategoria', $detalle['id_categoria'])->first(); ?>
            <tr>
                <td style="text-align:left; font:   80% normal; border:none; background-color:#C5D3E5;  "><?php echo $nombre_categoria['nombrecategoria'] ?></td>
                <td style="text-align:left; font:   80% nomal; border:none; background-color:#C5D3E5"></td>
                <td style="text-align:left; font:   80% nomal; border:none; background-color:#C5D3E5"></td>
                <td style="text-align:left; font:   80% nomal; border:none; background-color:#C5D3E5"></td>
                <td style="text-align:left; font:   80% nomal; border:none; background-color:#C5D3E5"></td>
            </tr>
            <?php
            $productos_categoria = model('reporteProductoModel')->select('*')->where('id_categoria', $detalle['id_categoria'])->findAll();
            $total_categoria = model('reporteProductoModel')->selectSum('valor_total')->where('id_categoria', $detalle['id_categoria'])->findAll();
            ?>
            <tr>
                <td style="text-align:left; font:    80% nomal; border:none; background-color:#000; color: white; ">CÓDIGO</td>
                <td style="text-align:left; font:    80% nomal; border:none; background-color:#000; color: white; ">PRODUCTO</td>
                <td style="text-align:left; font:    80% nomal; border:none; background-color:#000; color: white; ">CANTIDAD</td>
                <td style="text-align:left; font:    80% nomal; border:none; background-color:#000; color: white; ">VALOR UNIDAD</td>
                <td style="text-align:left; font:    80% nomal; border:none; background-color:#000; color: white; ">TOTAL</td>
            </tr>
            <?php foreach ($productos_categoria as $detalle_producto) { ?>



                <tr>
                    <td style="text-align:left; font:   80% nomal; border:none;  "><?php echo $detalle_producto['codigo_interno_producto'] ?></td>
                    <td style="text-align:left; font:    80% nomal; border:none;  "><?php echo $detalle_producto['nombre_producto'] ?></td>
                    <td style="text-align:left; font:    80% nomal; border:none;  "><?php echo $detalle_producto['cantidad'] ?></td>
                    <td style="text-align:left; font:    80% nomal; border:none;  "><?php echo "$" . number_format($detalle_producto['precio_venta'], 0, ",", ".") ?></td>
                    <td style="text-align:right; font:    80% nomal; border:none;  "><?php echo "$" . number_format($detalle_producto['valor_total'], 0, ",", ".") ?></td>
                </tr>
            <?php } ?>

            <tr>
                <td style="text-align:left; font:    80% ; border:none;  "></td>
                <td style="text-align:left; font:    80% ; border:none;  "></td>
                <td style="text-align:left; font:    80% ; border:none;  "></td>
                <td style="text-align:left; font:    80% ; border:none;  "></td>
                <td class="table-danger" style="text-align:left; font:    80% ; border:none;  ">
                    <p style="text-align:right; font:    100% nomal; border:none; ">TOTAL: <?php echo "$" . number_format($total_categoria[0]['valor_total'], 0, ",", ".") ?></p>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>


<p style="text-align:right; font:   100% nomal; border:none; ">
    <?php $total_ventas = model('reporteProductoModel')->selectSum('valor_total')->findAll(); ?>
    TOTAL VENTAS :<?php echo "$" . number_format($total_ventas[0]['valor_total'], 0, ",", "."); ?>
    <?php model('reporteProductoModel')->truncate(); ?>
</p>

<div class="row">
    <p style="text-align:left; font:   80% nomal; border:none; ">DEVOLUCIONES </p>

    <table class="table" id="consulta_producto_por_fecha_devolucion">
        <thead class="table-dark">
            <tr>
                <td style="text-align:left; font:    80% nomal; border:none; background-color:#000; color: white; ">Código

                    </th>
                <td style="text-align:left; font:    80% nomal; border:none; background-color:#000; color: white; ">Nombre producto
                    </th>
                <td style="text-align:left; font:    80% nomal; border:none; background-color:#000; color: white; ">Cantidad
                    </th>
                <td style="text-align:left; font:    80% nomal; border:none; background-color:#000; color: white; ">Valor unitario
                    </th>
                <td style="text-align:left; font:    80% nomal; border:none; background-color:#000; color: white; ">Valor total
                    </th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($devoluciones as $detalle) {  ?>
                <tr>
                    <td style="text-align:left; font:   80% nomal; border:none;  "><?php echo $detalle['codigo'] ?></td>
                    <td style="text-align:left; font:   80% nomal; border:none;  "><?php echo $detalle['nombreproducto'] ?></td>
                    <td style="text-align:left; font:   80% nomal; border:none;  "><?php echo $detalle['cantidad'] ?></td>
                    <td style="text-align:left; font:   80% nomal; border:none;  "><?php echo "$" . number_format($detalle['valor_unitario'], 0, ",", ".") ?></td>
                    <td style="text-align:left; font:   80% nomal; border:none;  "><?php echo "$" . number_format($detalle['total'], 0, ",", ".") ?></td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
    <p style="text-align:right; font:    100% nomal; border:none;  ">TOTAL DEVOLUCIONES: <?php echo $total_devoluciones ?> </p>

</div>