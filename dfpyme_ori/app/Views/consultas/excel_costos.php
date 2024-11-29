<?php
// Especificar la codificación de caracteres
header('Content-Type: text/html; charset=utf-8');

// Encabezados para la descarga del archivo Excel
header("Pragma: public");
header("Expires: 0");
$filename = "Reporte_de_costos.xls"; // Cambiado para reflejar un nombre de archivo sin espacios
header("Content-type: application/vnd.ms-excel"); // Cambiado el tipo de contenido
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
?>

<meta charset="UTF-8">



<p class="text-center-blue h3">INFORME COSTO DE VENTA </p>

<table class="table table-borderless">
    <tbody>
        <tr>
            <th style="    border:display "><?php echo $nombre_comercial ?></th>
            <?php
            $fecha_inicial_timestamp = strtotime($fecha_inicial);
            $fecha_final_timestamp = strtotime($fecha_final);

            $formato_fecha = datefmt_create('es_ES', IntlDateFormatter::FULL, IntlDateFormatter::FULL);
            datefmt_set_pattern($formato_fecha, 'EEEE, d MMMM y');

            $fecha_inicial_formateada = datefmt_format($formato_fecha, $fecha_inicial_timestamp);
            $fecha_final_formateada = datefmt_format($formato_fecha, $fecha_final_timestamp);
            ?>

            <th >
                Período desde <?php echo $fecha_inicial_formateada ?> hasta <?php echo $fecha_final_formateada ?>
            </th>




        </tr>
        <tr>
            <td ><?php echo $nombre_juridico ?></td>
            <th ></th>

        </tr>
        <tr>
            <td >Nit: <?php echo $nit ?></td>
            <td > </td>

        </tr>
        <tr>
            <td ><?php echo $nombre_regimen ?></td>
            <td ></td>

        </tr>
        <tr>
            <td ><?php echo $direccion . " " . $nombre_ciudad . " " . $nombre_departamento ?></td>
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



<table style="border-collapse: collapse; width: 100%;">

<thead>
    <tr>
        <th style="border: 1px solid #000; background-color: black; color: white;">Nit</th>
        <th style="border: 1px solid #000; background-color: black; color: white;">Tercero</th>
        <th style="border: 1px solid #000; background-color: black; color: white;">Documento</th>
        <th style="border: 1px solid #000; background-color: black; color: white;">Número</th>
        <th style="border: 1px solid #000; background-color: black; color: white;">Fecha</th>
        <th style="border: 1px solid #000; background-color: black; color: white;">Costo</th>
        <th style="border: 1px solid #000; background-color: black; color: white;">Base</th>
        <th style="border: 1px solid #000; background-color: black; color: white;">IVA</th>
        <th style="border: 1px solid #000; background-color: black; color: white;">INC</th>
        <th style="border: 1px solid #000; background-color: black; color: white;">Venta</th>
    </tr>
</thead>

<tbody>
        <?php foreach ($datos as $detalle_datos) { ?>
            <tr>
                <td style="border: 1px solid #000;"><?php echo $detalle_datos['nit'] ?></td>
                <td style="border: 1px solid #000;"><?php echo $detalle_datos['nombre_cliente'] ?></td>
                <td style="border: 1px solid #000;"><?php echo $detalle_datos['documento'] ?></td>
                <td style="border: 1px solid #000;"><?php echo $detalle_datos['numero'] ?></td>
                <td style="border: 1px solid #000;"><?php echo $detalle_datos['fecha'] ?></td>
                <td style="border: 1px solid #000;"><?php echo $detalle_datos['costo'] ?></td>
                <td style="border: 1px solid #000;"><?php echo $detalle_datos['base'] ?></td>
                <td style="border: 1px solid #000;"><?php echo $detalle_datos['iva'] ?></td>
                <td style="border: 1px solid #000;"><?php echo $detalle_datos['ico'] ?></td>
                <td style="border: 1px solid #000;"><?php echo $detalle_datos['venta'] ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>



<table class="table">
<thead>
    <tr>
        <td style="border: 1px solid #000; background-color: black; color: white;">Total costo: <?php echo $total_costo ?></td>
        <td style="border: 1px solid #000; background-color: black; color: white;">Base IVA: <?php echo $base_iva ?></td>
        <td style="border: 1px solid #000; background-color: black; color: white;">Total IVA: <?php echo $total_iva ?></td>
        <td style="border: 1px solid #000; background-color: black; color: white;">Base ICO: <?php echo $base_ico ?></td>
        <td style="border: 1px solid #000; background-color: black; color: white;">Total ICO: <?php echo $total_ico ?></td>
        <td style="border: 1px solid #000; background-color: black; color: white;">Total base: <?php echo $total_base ?></td>
        <td style="border: 1px solid #000; background-color: black; color: white;">Total impuesto: <?php echo $total_impuesto ?></td>
        <td style="border: 1px solid #000; background-color: black; color: white;">Total venta: <?php echo $total_venta ?></td>
    </tr>
</thead>

    <tbody>

    </tbody>
</table>