<style>
    table {
        width: 100%;
        margin: 30px auto;
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



    textarea {
        height: 10em;
        width: 45em;
        font-family: "normal bold cursive";
        font-size: 80%;



    }

    .centrado {
        text-align: left;
        font: normal bold 80% cursive;
        border: none
    }

    .tr {
        text-align: left;
        font: normal bold 80% cursive;

        border: red 5px
    }
</style>

<style>
    .table-striped>tbody>tr:nth-child(odd)>td,
    .table-striped>tbody>tr:nth-child(odd)>th {
        background-color: #cfe2ff;
        /* Choose your own color here */

    }
</style>


<div class="card container">
    <div class="card-body">

        <table class="table">

            <tbody>
                <tr>
                    <th class="centrado"><?php echo $nombre_comercial ?></th>
                    <th class="centrado"></th>
                    <th class="centrado">Informe de ventas de producto</th>

                </tr>
                <tr>
                    <th class="centrado"><?php echo $nombre_juridico ?></th>
                    <th class="centrado"></th>
                    <th class="centrado">Desde <?php echo $fecha_inicial  ?> Hasta <?php echo $fecha_final  ?> </th>
                </tr>
                <tr>
                    <th class="centrado"><?php echo $nombre_regimen ?></th>

                </tr>
                <tr>
                    <th class="centrado"><?php echo $direccion . " " . $nombre_ciudad . " " . $nombre_departamento ?></th>

                </tr>

            </tbody>
        </table>

        <table class="table" id="consulta_producto_por_fecha">
            <thead class="table-dark">
                <tr>
                    <td class="tr">Fecha </th>
                    <td class="tr" > Hora </th>
                    <td class="tr">N° Factura</th>
                    <td class="tr">Cantidad</th>
                    <td class="tr">Producto</th>
                    <td class="tr">Valor unitario</th>
                    <td class="tr">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($datos_producto as $detalle) { ?>
                    <tr>
                        <th class="tr"><?php echo $detalle['fecha_venta'] ?></th>
                        <th class="tr"><?php echo date("g:i a", strtotime($detalle['fecha_y_hora_venta'])) ?></th>
                        <th class="tr"><?php echo $detalle['numerofactura_venta'] ?></td>
                        <th class="tr"><?php echo $detalle['cantidadproducto_factura_venta'] ?></td>
                        <th class="tr"><?php echo $detalle['nombreproducto'] ?></td>
                        <th class="tr"><?php echo "$" . number_format($detalle['precio_unitario'], 0, ",", ".") ?></td>
                        <th class="tr"><?php echo "$" . number_format($detalle['total'], 0, ",", ".") ?></td>
                            <?php $datos_factura = model('facturaVentaModel')->consulta_producto($detalle['id']) ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <table>
            <tr>
                <th class="tr"></th>
                <th class="tr"></th>
                <th class="tr"></th>
                <th class="tr"></th>
                <th class="tr"></th>
                <th class="tr"></th>
                <th class="tr"><p style="font-size: 30px; text-align:right">Total ventas: <?php echo $total ?></p></th>
        </tr>
        </table>
    </div>
</div>