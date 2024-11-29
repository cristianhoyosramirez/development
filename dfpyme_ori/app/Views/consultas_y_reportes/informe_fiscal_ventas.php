<div class="card container">
    <div class="card-body">
        <div class="row">
            <div class="col-1 text-end">
                <form action="<?php echo $action_url; ?>" method="POST">
                    <input type="hidden" value="<?php echo $fecha_apertura ?>" name="fecha_reporte">
                    <input type="hidden" value="<?php echo $id_apertura; ?>" name="id_apertura">

                    <button type="submit" title="Exportar a pdf" class="btn btn-outline-danger w-150 btn-icon">
                        Pdf
                    </button>
                </form>
            </div>

            <div class="col-1">

                <button type="submit" title="Imprimir" class="btn btn-outline-green w-150 btn-icon" onclick="imprimir_fiscal()">
                    Imprimir
                </button>

            </div>


        </div>
        <div class="row">
            <div class="col-6">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th><?php echo $nombre_comercial ?></th>
                        </tr>
                        <tr>
                            <td><?php echo $nombre_juridico ?></td>
                        </tr>
                        <tr>
                            <td>Nit: <?php echo $nit ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $nombre_regimen ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $direccion . " " . $nombre_ciudad . " " . $nombre_departamento ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php //dd($ico_devolucion); 
            ?>
            <div class="col-6">
                <table class="table table-borderless">

                    <tbody>
                        <tr>
                            <th><?php echo $titulo ?></th>
                        </tr>
                        <tr>
                            <td>N°:<?php echo $consecutivo ?></td>
                        </tr>
                        <tr>
                            <td>Caja N° 1 </td>
                        </tr>
                        <tr>
                            <td>Fecha: <?php echo $fecha_apertura; ?> </td>
                        </tr>
                    </tbody>

            </div>
        </div>
        <div class="col-12 row ">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <th>Registro inicial</td>
                        <td><?php echo $registro_inicial ?></td>
                        <th>Registro final</td>
                        <td><?php echo $registro_final ?></td>
                        <th>Total registros</td>
                        <td><?php echo $total_registros ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr>
        <p class="h2 text-primary"> TOTALES POR TARIFA IVA</p>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td scope="col">Tarifa </th>
                    <td scope="col">Base grabable</th>
                    <td scope="col">Valor iva </th>
                    <td scope="col">Valor total </th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($iva as $detalle) { ?>
                    <tr>
                        <th><?php echo $detalle['tarifa_iva'] ?>%</th> <!-- TARIFA IVA  -->
                        <td><?php echo "$" . number_format($detalle['base'], 0, ",", ".") ?></td> <!-- BASE -->
                        <td><?php echo "$" . number_format($detalle['total_iva'], 0, ",", ".") ?></td> <!-- TOTAL IVA  -->
                        <td><?php echo "$" . number_format($detalle['valor_venta'], 0, ",", ".") ?></td> <!-- TOTAL  -->
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <p class="h2 text-primary"> IMPUESTO AL CONSUMO</p>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td scope="col">Tarifa </th>
                    <td scope="col">Base grabable</th>
                    <td scope="col">Valor ICO</th>
                    <td scope="col">Valor total</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($ico as $detalle) { ?>
                    <tr>
                        <th><?php echo $detalle['tarifa_ico'] ?>%</th> <!-- TARIFA ICO  -->
                        <td><?php echo "$" . number_format($detalle['base'], 0, ",", ".") ?></td> <!-- BASE -->
                        <td><?php echo "$" . number_format($detalle['total_ico'], 0, ",", ".") ?></td> <!-- TOTAL ICO  -->
                        <td><?php echo "$" . number_format($detalle['valor_venta'], 0, ",", ".") ?></td> <!-- TOTAL  -->
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <p class="h2 text-primary">DETALLE DE LA VENTA</p>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td scope="col">VENTAS CONTADO </th>
                    <td scope="col">VENTAS CRÉDITO</th>
                    <td scope="col">TOTAL VENTAS</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                    <td><?php echo "$" . number_format($vantas_contado, 0, ",", ".") ?></td>
                    <td>$0</td>
                    <td><?php echo "$" . number_format($vantas_contado, 0, ",", ".") ?></td>
                </tr>
            </tbody>
        </table>


        <p class="h2 text-primary">IVA EN DEVOLUCIONES</p>

        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td scope="col">Numero de factura </th>
                    <td scope="col">Tarifa</th>
                    <td scope="col">Base</th>
                    <td scope="col">Impuesto</th>
                    <td scope="col">Sub total</th>
                </tr>
            </thead>
            <tbody>

                <?php if (!empty($iva_devolucion)): ?>
                    <?php foreach ($iva_devolucion as $item): ?>
                        <tr>
                            <th>Factura General</th> <!-- TARIFA ICO -->
                            <th><?php echo $item['tarifa']; ?>%</th> <!-- TARIFA ICO -->
                            <th><?php echo "$" . number_format($item['base'], 0, ",", "."); ?></th> <!-- TARIFA ICO -->
                            <th><?php echo "$" . number_format($item['impuesto'], 0, ",", "."); ?></th> <!-- TARIFA ICO -->
                            <th><?php echo "$" . number_format($item['total'], 0, ",", "."); ?></th> <!-- TARIFA ICO -->
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <th>Factura General</th>
                        <th>0%</th>
                        <th>$0</th>
                        <th>$0</th>
                        <th>$0</th>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>





        <p class="h2 text-primary">IMPUESTO AL CONSUMO EN DEVOLUCIONES</p>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td scope="col">Numero de factura </th>
                    <td scope="col">Tarifa</th>
                    <td scope="col">Base</th>
                    <td scope="col">Impuesto</th>
                    <td scope="col">Sub total</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($ico_devolucion as $ico_devolucion) { ?>
                    <tr>
                        <th>Factura General</th> <!-- TARIFA ICO  -->
                        <th><?php echo $ico_devolucion['tarifa'] ?>%</th> <!-- TARIFA ICO  -->
                        <th><?php echo "$" . number_format($ico_devolucion['base'], 0, ",", ".") ?></th> <!-- TARIFA ICO  -->
                        <th><?php echo "$" . number_format($ico_devolucion['impuesto'], 0, ",", ".") ?></th> <!-- TARIFA ICO  -->
                        <th><?php echo "$" . number_format($ico_devolucion['total'], 0, ",", ".") ?></th> <!-- TARIFA ICO  -->

                    </tr>
                <?php } ?>
            </tbody>
        </table>

      

    </div>
</div>