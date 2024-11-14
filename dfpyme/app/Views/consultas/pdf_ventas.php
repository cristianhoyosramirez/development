<style>
    table.table {
        border-collapse: collapse;
        width: 100%;
        font-family: 'Arial', sans-serif;
        /* Tipo de letra personalizado, ajusta según tus necesidades */
    }

    table.table thead {
        background-color: black;
        color: white;
    }

    table.table th,
    table.table td {
        padding: 4px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        /* Línea de separación entre filas */
        font-size: 10px;
        /* Tamaño de letra ajustado según tus necesidades */
    }

    table.table tbody tr:hover {
        background-color: #f5f5f5;
        /* Color de fondo al pasar el ratón sobre una fila */

    }

    /* Dentro de tu archivo CSS o en una etiqueta <style> en tu archivo HTML */
    .text-center-blue {
        text-align: center;
        color: #007BFF;
        /* Código de color para el azul primary de Bootstrap */
        font-size: 1.0em;
    }
</style>
<p class="text-center-blue h3">INFORME DE VENTAS </p>

<table class="table table-borderless">
    <tbody>
        <tr>
            <th style="text-align:left; font:  bold 80% cursive; border:none "><?php echo $nombre_comercial ?></th>
            <?php
            $fecha_inicial_timestamp = strtotime($fecha_inicial);
            $fecha_final_timestamp = strtotime($fecha_final);

            $formato_fecha = datefmt_create('es_ES', IntlDateFormatter::FULL, IntlDateFormatter::FULL);
            datefmt_set_pattern($formato_fecha, 'EEEE, d MMMM y');

            $fecha_inicial_formateada = datefmt_format($formato_fecha, $fecha_inicial_timestamp);
            $fecha_final_formateada = datefmt_format($formato_fecha, $fecha_final_timestamp);
            ?>

            <th style="text-align:left; font: bold 80% cursive; border:none">
                Período desde <?php echo $fecha_inicial_formateada ?> hasta <?php echo $fecha_final_formateada ?>
            </th>




        </tr>
        <tr>
            <td style="text-align:left; font:  bold 80% cursive; border:none "><?php echo $nombre_juridico ?></td>
            <th style="text-align:left; font:  bold 80% cursive; border:none "></th>

        </tr>
        <tr>
            <td style="text-align:left; font:  bold 80% cursive; border:none ">Nit: <?php echo $nit ?></td>
            <td style="text-align:left; font:  bold 80% cursive; border:none "> </td>

        </tr>
        <tr>
            <td style="text-align:left; font:  bold 80% cursive; border:none "><?php echo $nombre_regimen ?></td>
            <td style="text-align:left; font:  bold 80% cursive; border:none "></td>

        </tr>
        <tr>
            <td style="text-align:left; font:  bold 80% cursive; border:none "><?php echo $direccion . " " . $nombre_ciudad . " " . $nombre_departamento ?></td>
        </tr>
    </tbody>
</table>

<br><br>
<?php $datos = array(); ?>
<?php if (!empty($id_facturas)) { ?>

    <?php foreach ($id_facturas as $valor) {    ?>
        <?php
        $productos = model('kardexModel')->get_factutras_pos($valor['id_factura']);
        $costo_total = 0;
        $ico_total = 0;
        $iva_total = 0;


        $numero_factura = model('pagosModel')->select('documento')->where('id_factura', $valor['id_factura'])->first();
        $fecha_factura = model('pagosModel')->select('fecha')->where('id_factura', $valor['id_factura'])->first();
        $venta = model('pagosModel')->select('valor')->where('id_factura', $valor['id_factura'])->first();
        $id_estado = model('pagosModel')->select('id_estado')->where('id_factura', $valor['id_factura'])->first();

        if ($id_estado['id_estado'] == 1) {
            $nit = model('facturaVentaModel')->select('nitcliente')->where('id', $valor['id_factura'])->first();
            $factura = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $valor['id_factura'])->first();
            $numero_factura = $factura['numerofactura_venta'];
            $nit_cliente = $nit['nitcliente'];
            $estado = "POS";
        }

        if ($id_estado['id_estado'] == 8) {
            $nit = model('facturaElectronicaModel')->select('nit_cliente')->where('id', $valor['id_factura'])->first();
            $factura = model('facturaElectronicaModel')->select('numero')->where('id', $valor['id_factura'])->first();
            $numero_factura = $factura['numero'];
            $nit_cliente = $nit['nit_cliente'];
            $estado = "ELECTRÓNICA";
        }
        $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $nit_cliente)->first();

        $costo = model('kardexModel')->get_costo($valor['id_factura']);
        $ico = model('kardexModel')->get_ico($valor['id_factura']);
        $iva = model('kardexModel')->get_iva($valor['id_factura']);
        //$valor_factura = model('pagosModel')->select('valor')->where('id', $valor['id_factura'])->first();
        $total_factura = model('kardexModel')->selectSum('total')->where('id_factura', $valor['id_factura'])->findAll();


        $data['nit'] = $nit_cliente;
        $data['nombre_cliente'] =  $nombre_cliente['nombrescliente'];
        $data['documento'] = $estado;
        $data['numero'] = $numero_factura;
        $data['fecha'] = $fecha_factura['fecha'];
        $data['costo'] =  "$ " . number_format($costo[0]['costo'], 0, ",", ".");
        $data['base'] = "$ " . number_format($total_factura[0]['total'] - ($iva[0]['iva'] + $ico[0]['ico']), 0, ",", ".");
        $data['iva'] = "$ " . number_format($iva[0]['iva'], 0, ",", ".");
        $data['ico'] = "$ " . number_format($ico[0]['ico'], 0, ",", ".");
        $data['venta'] = "$ " . number_format($total_factura[0]['total'], 0, ",", ".");
        array_push($datos, $data);

        ?>

    <?php } ?>

<?php } ?>



<table class="table">
    <thead>
        <tr>
            <td>Nit</th>
            <td>Tercero</th>
            <td>Documento</th>
            <td>Número</th>
            <td>Fecha</th>
            
            <td>Base</th>
            <td>IVA</th>
            <td>ICO</th>
            <td>Venta</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($datos as $detalle_datos) { ?>

            <tr>
                <td>
                    <?php echo $detalle_datos['nit']  ?>
                </td>
                <td>
                    <?php echo $detalle_datos['nombre_cliente']  ?>
                </td>
                <td>
                    <?php echo $detalle_datos['documento']  ?>
                </td>
                <td>
                    <?php echo $detalle_datos['numero']  ?>
                </td>
                <td>
                    <?php echo $detalle_datos['fecha']  ?>
                </td>
               
                <td>
                    <?php echo $detalle_datos['base']  ?>
                </td>
                <td>
                    <?php echo $detalle_datos['iva']  ?>
                </td>
                <td>
                    <?php echo $detalle_datos['ico']  ?>
                </td>
                <td>
                    <?php echo $detalle_datos['venta']  ?>
                </td>
            </tr>

        <?php } ?>
    </tbody>
</table>


<table class="table">
    <thead>
        <tr>
           <td>Base IVA: <?php echo $base_iva ?> </td>
           <td>Total IVA: <?php echo $total_iva ?> </th>
           <td>Base ICO: <?php echo $base_ico ?></td>
           <td>Total ICO: <?php echo $total_ico ?></td>
           <td>Total base: <?php echo $total_base ?> </th>
           <td>Total impuesto : <?php echo $total_impuesto ?>  </td>
           <td>Total venta: <?php echo $total_venta ?> </td>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>