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
        font-family: " bold cursive";
        font-size: 80%;


    }
</style>
<div class="card container">
    <div class="card-body">

        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th style="text-align:left; font:  bold 80% cursive; border:none "><?php echo $nombre_comercial ?></th>
                    <th style="text-align:left; font:  bold 80% cursive; border:none "></th>
                    <th style="text-align:left; font:  bold 80% cursive; border:none "><?php  echo $titulo?></th>
                </tr>
                <tr>
                    <td style="text-align:left; font:  bold 80% cursive; border:none "><?php echo $nombre_juridico ?></td>
                    <th style="text-align:left; font:  bold 80% cursive; border:none "></th>
                    <th style="text-align:left; font:  bold 80% cursive; border:none ">N°:<?php echo $consecutivo ?></th>
                </tr>
                <tr>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Nit: <?php echo $nit ?></td>
                    <td style="text-align:left; font:  bold 80% cursive; border:none "> </td>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Caja N° 1 </td>
                </tr>
                <tr>
                    <td style="text-align:left; font:  bold 80% cursive; border:none "><?php echo $nombre_regimen ?></td>
                    <td style="text-align:left; font:  bold 80% cursive; border:none "></td>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Fecha: <?php echo $fecha; ?></td>
                </tr>
                <tr>
                    <td style="text-align:left; font:  bold 80% cursive; border:none "><?php echo $direccion . " " . $nombre_ciudad . " " . $nombre_departamento ?></td>
                </tr>
            </tbody>
        </table>



        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th style="text-align:left; font:  bold 80% cursive; border:none ">Registro inicial: <?php echo $registro_inicial ?></td>
                    <td style="text-align:left; font:  bold 80% cursive; border:none "></td>
                    <th style="text-align:left; font:  bold 80% cursive; border:none ">Registro final: <?php echo $registro_final ?></td>
                    <td style="text-align:left; font:  bold 80% cursive; border:none "></td>
                    <th style="text-align:left; font:  bold 80% cursive; border:none ">Total registros:<?php echo $total_registros ?></td>
                    <td style="text-align:left; font:  bold 80% cursive; border:none "></td>
                </tr>
            </tbody>
        </table>
        <hr>
        <p style="text-align:left; font:  bold 80% cursive;"> TOTALES POR TARIFA IVA</p>
        <table class="table">
            <thead>
                <tr>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Tarifa </th>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Base grabable</th>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Valor IVA </th>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Valor total </th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($iva as $detalle) { ?>
                    <tr>
                        <th style="text-align:left; font:  bold 80% cursive; border:none "><?php echo $detalle['tarifa_iva'] ?>%</th> <!-- TARIFA IVA  -->
                        <td style="text-align:left; font:  bold 80% cursive; border:none "><?php echo "$" . number_format($detalle['base'], 0, ",", ".") ?></td> <!-- BASE -->
                        <td style="text-align:left; font:  bold 80% cursive; border:none "><?php echo "$" . number_format($detalle['total_iva'], 0, ",", ".") ?></td> <!-- TOTAL IVA  -->
                        <td style="text-align:left; font:  bold 80% cursive; border:none "><?php echo "$" . number_format($detalle['valor_venta'], 0, ",", ".") ?></td> <!-- TOTAL  -->
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <p style="text-align:left; font:  bold 80% cursive;"> IMPUESTO AL CONSUMO</p>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Tarifa </th>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Base grabable</th>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Valor ICO</th>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Valor total</th>
                </tr>
            </thead>
            <tbody>


                <?php foreach ($ico as $detalle) { ?>
                    <tr>
                        <th style="text-align:left; font:  bold 80% cursive; border:none "><?php echo $detalle['tarifa_ico'] ?>%</th> <!-- TARIFA ICO  -->
                        <td style="text-align:left; font:  bold 80% cursive; border:none "><?php echo "$" . number_format($detalle['base'], 0, ",", ".") ?></td> <!-- BASE -->
                        <td style="text-align:left; font:  bold 80% cursive; border:none "><?php echo "$" . number_format($detalle['total_ico'], 0, ",", ".") ?></td> <!-- TOTAL ICO  -->
                        <td style="text-align:left; font:  bold 80% cursive; border:none "><?php echo "$" . number_format($detalle['valor_venta'], 0, ",", ".") ?></td> <!-- TOTAL  -->
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <p style="text-align:left; font:  bold 80% cursive;">DETALLE DE VENTA</p>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">VENTAS CONTADO </th>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">VENTAS CRÉDITO</th>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">TOTAL VENTAS</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                    <td style="text-align:left; font:  bold 80% cursive; border:none "><?php echo "$" . number_format($vantas_contado, 0, ",", ".") ?></td>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">$0</td>
                    <td style="text-align:left; font:  bold 80% cursive; border:none "><?php echo "$" . number_format($vantas_contado, 0, ",", ".") ?></td>
                </tr>
            </tbody>
        </table>

        <p style="text-align:left; font:  bold 80% cursive; border:none ">IVA EN DEVOLUCIONES</p>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Numero de factura </th>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Tarifa</th>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Base</th>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Impuesto</th>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Sub total</th>
                </tr>
            </thead>

            <tbody>

               <!--  <?php foreach ($iva_devolucion as $iva_devolucion) { ?>
                    <tr>
                        <th style="text-align:left; font:  bold 80% cursive; border:none ">Factura General</th>
                        <th style="text-align:left; font:  bold 80% cursive; border:none "><?php echo $iva_devolucion['tarifa'] ?>%</th> 

                        <th style="text-align:left; font:  bold 80% cursive; border:none "><?php echo "$" . number_format($iva_devolucion['base'], 0, ",", ".") ?></th> 
                        <th style="text-align:left; font:  bold 80% cursive; border:none "><?php echo "$" . number_format($iva_devolucion['impuesto'], 0, ",", ".") ?></th> 
                        <th style="text-align:left; font:  bold 80% cursive; border:none "><?php echo "$" . number_format($iva_devolucion['total'], 0, ",", ".") ?></th> 

                    </tr>
                <?php } ?> -->
                <?php if (!empty($iva_devolucion)): ?>
                    <?php foreach ($iva_devolucion as $item): ?>
                        <tr>
                            <th style="text-align:left; font:  bold 80% cursive; border:none" >Factura General</th> <!-- TARIFA ICO -->
                            <th style="text-align:left; font:  bold 80% cursive; border:none" ><?php echo $item['tarifa']; ?>%</th> <!-- TARIFA ICO -->
                            <th style="text-align:left; font:  bold 80% cursive; border:none" ><?php echo "$" . number_format($item['base'], 0, ",", "."); ?></th> <!-- TARIFA ICO -->
                            <th style="text-align:left; font:  bold 80% cursive; border:none" ><?php echo "$" . number_format($item['impuesto'], 0, ",", "."); ?></th> <!-- TARIFA ICO -->
                            <th style="text-align:left; font:  bold 80% cursive; border:none" ><?php echo "$" . number_format($item['total'], 0, ",", "."); ?></th> <!-- TARIFA ICO -->
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <th style="text-align:left; font:  bold 80% cursive; border:none">Factura General</th>
                        <th style="text-align:left; font:  bold 80% cursive; border:none">0%</th>
                        <th style="text-align:left; font:  bold 80% cursive; border:none">$0</th>
                        <th style="text-align:left; font:  bold 80% cursive; border:none">$0</th>
                        <th style="text-align:left; font:  bold 80% cursive; border:none">$0</th>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <p style="text-align:left; font:  bold 80% cursive; border:none ">IMPUESTO AL CONSUMO EN DEVOLUCIONES</p>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Numero de factura </th>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Tarifa</th>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Base</th>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Impuesto</th>
                    <td style="text-align:left; font:  bold 80% cursive; border:none ">Sub total</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($ico_devolucion as $ico_devolucion) { ?>
                    <tr>
                        <th style="text-align:left; font:  bold 80% cursive; border:none ">Factura General</th> <!-- TARIFA ICO  -->
                        <th style="text-align:left; font:  bold 80% cursive; border:none "><?php echo $ico_devolucion['tarifa'] ?>%</th> <!-- TARIFA ICO  -->
                        <th style="text-align:left; font:  bold 80% cursive; border:none "><?php echo "$" . number_format($ico_devolucion['base'], 0, ",", ".") ?></th> <!-- TARIFA ICO  -->
                        <th style="text-align:left; font:  bold 80% cursive; border:none "><?php echo "$" . number_format($ico_devolucion['impuesto'], 0, ",", ".") ?></th> <!-- TARIFA ICO  -->
                        <th style="text-align:left; font:  bold 80% cursive; border:none "><?php echo "$" . number_format($ico_devolucion['total'], 0, ",", ".") ?></th> <!-- TARIFA ICO  -->

                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <p>____________________________</p>
        <p style="text-align:left; font:  bold 80% cursive; border:none ">Firma</p>
    </div>
</div>