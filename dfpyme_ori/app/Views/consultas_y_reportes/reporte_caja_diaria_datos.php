<div class="card container">

    <div class="card-body">
        <div class="row">
            <div class="col-1">
                <form action="<?= base_url('consultas_y_reportes/informe_fiscal_ventas_pdf') ?>">
                    <input type="hidden" value="<?php echo $fecha ?>" name="fecha_reporte" id="fecha_reporte">

                    <button type="submit" title="Exportar a pdf" class="btn btn-danger w-100 btn-icon">
                        Pdf
                    </button>
                </form>
            </div>
            <div class="col-1">
                <button type="submit" title="Imprimir reporte " class="btn btn-primary w-100 btn-icon" onclick="imprimir_reporte_fiscal(<?php echo $fecha ?>)">
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
            <div class="col-6">
                <table class="table table-borderless">

                    <tbody>
                        <tr>
                            <th>INFORME FISCAL DE VENTAS DIARIAS </th>
                        </tr>
                        <tr>
                            <td>N°:<?php echo $consecutivo ?></td>
                        </tr>
                        <tr>
                            <td>Caja N° 1 </td>
                        </tr>
                        <tr>
                            <td>Fecha de reporte de informe : <?php echo $fecha; ?> </td>
                        </tr>
                        <tr>
                            <td>Fecha de generacion de informe : <?php echo date("Y-m-d"); ?> </td>
                        </tr>
                    </tbody>

            </div>
        </div>

        <div class="col-6 row ">
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
        <div class="row">
            <div class="col-5 row ">
                <p class="h2 text-primary"> BASE 0</p>
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
                        <?php if (empty($iva)) { ?>
                            <th>0%</th> <!-- TARIFA IVA  -->
                            <td>0</td> <!-- BASE -->
                            <td>0</td> <!-- TOTAL IVA  -->
                            <td>0</td> <!-- TOTAL  -->
                        <?php } ?>

                        <?php foreach ($iva as $detalle) { ?>
                            <tr>
                                <th><?php echo $detalle[0] ?>%</th> <!-- TARIFA IVA  -->
                                <td> <?php echo  number_format($detalle[1], 0, ",", ".") ?> </td> <!-- BASE -->
                                <td><?php echo "$" . number_format($detalle[2], 0, ",", ".") ?></td> <!-- TOTAL IVA  -->
                                <td> <?php echo "$" . number_format($detalle[3], 0, ",", ".") ?></td> <!-- TOTAL  -->
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <p class="h2 text-primary"> BASE ICO</p>
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
                        <?php if (empty($ico)) { ?>
                            <th>0%</th> <!-- TARIFA IVA  -->
                            <td>0</td> <!-- BASE -->
                            <td>0</td> <!-- TOTAL IVA  -->
                            <td>0</td> <!-- TOTAL  -->
                        <?php } ?>
                        <?php foreach ($ico as $detalle) { ?>
                            <tr>
                                <th><?php echo $detalle[0] ?>%</th> <!-- TARIFA ICO  -->
                                <td><?php echo number_format($detalle[1], 0, ",", ".") ?></td> <!-- BASE -->
                                <td><?php echo  number_format($detalle[2], 0, ",", ".") ?></td> <!-- BASE -->
                                <td><?php echo number_format($detalle[3], 0, ",", ".") ?></td> <!-- BASE -->

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

                            <td><?php echo number_format($vantas_contado, 0, ",", ".") ?></td>
                            <td>$0</td>
                            <td><?php echo number_format($vantas_contado, 0, ",", ".") ?> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col row ">
            </div>

            <div class="col-6 row ">

                <!-- Consultar si la fecha tiene registro generado manualmente en la tabla fiscal -->
                <?php $tiene_fiscal_manual = model('fiscalModel')->select('*')->where('fecha', $fecha)->first(); ?>
                <!-- /Consultar si la fecha tiene registro generado manualmente en la tabla fiscal -->

                <?php if (empty($tiene_fiscal_manual)) { ?>
                    <form class="row g-3" action="<?= base_url('consultas_y_reportes/guardar_reporte_caja_diaria') ?>" method="POST" id="formulario_reporte_caja_diaria">
                        <input type="hidden" id="fecha" name="fecha" value=" <?php echo $fecha; ?>">



                        <div class="col-md-3">
                            <label for="inputEmail4" class="form-label">BASE 0 CERO </label>
                            <input type="text" class="form-control" name="base_cero" value=0 id="base_cero" onkeyup="impuesto_iva(event),this.value" autofocus>
                            <span id="falta_base_cero" style="color:#FF0000"></span>
                        </div>
                        <div class="col-md-3">
                            <label for="inputPassword4" class="form-label">IMPUESTO</label>
                            <input type="text" class="form-control" value="0" readonly name="impuesto_cero" id="impuesto_cero">
                        </div>
                        <div class="col-md-3">
                            <label for="inputPassword4" class="form-label">V IMPUESTO</label>
                            <input type="text" class="form-control" name="valor_impuesto_cero" readonly value=0 id="valor_impuesto_cero">
                        </div>
                        <div class="col-3">
                            <label for="inputAddress" class="form-label">TOTAL </label>
                            <input type="text" class="form-control" id="total_venta_cero" name="total_venta_cero" value=0 readonly>
                        </div>
                        <div class="col-3">
                            <label for="inputAddress2" class="form-label">BASE ICO</label>
                            <input type="text" class="form-control" id="base_ico" name="base_ico" onkeyup="impoconsumo(event),this.value">
                            <span id="falta_base_ico" style="color:#FF0000"></span>
                        </div>
                        <div class="col-md-3">
                            <label for="inputCity" class="form-label">IMPUESTO</label>
                            <input type="text" class="form-control" value="8" id="impuesto_8" name="impuesto_8" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="inputState" class="form-label">V IMPUESTO</label>
                            <input type="text" class="form-control" name="valor_impuesto_8" id="valor_impuesto_8" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="inputZip" class="form-label">TOTAL </label>
                            <input type="text" class="form-control" id="total_venta_8" name="total_venta_8" readonly>
                        </div>
                        <div class="col-md-12">
                            <label for="inputZip" class="form-label">TOTAL VENTA</label>
                            <input type="text" class="form-control" value=0 id="total_cuadre_caja" readonly name="total_cuadre_caja">
                        </div>
                        <div class="row gx-1">
                            <div class="col-4">
                                <button type="button" onclick="enviar_formulario()" class="btn btn-primary">Guardar e imprimir </button>
                            </div>
                            <div class="col-3">
                                <button type="button" onclick="solo_guardar_enviar_formulario()" class="btn btn-primary">Guardar </button>
                            </div>
                        </div>
                    </form>

                   

                <?php } ?>

                <?php if (!empty($tiene_fiscal_manual)) { ?>
                    <form class="row g-3" action="<?= base_url('consultas_y_reportes/guardar_reporte_caja_diaria') ?>" method="POST" id="formulario_reporte_caja_diaria">

                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Fecha ya tiene informe fiscal manual lo puede cambiar o dejar los mismo valores !</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <input type="hidden" id="fecha" name="fecha" value=" <?php echo $fecha; ?>">

                        <?php $fiscal = model('fiscalModel')->select('*')->where('fecha', $fecha)->first(); ?>

                        <div class="col-md-3">
                            <label for="inputEmail4" class="form-label">BASE 0 CERO </label>
                            <input type="text" class="form-control" name="base_cero" value="<?php echo $fiscal['base_0'] ?>" id="base_cero" onkeyup="impuesto_iva(event),this.value" autofocus>
                            <span id="falta_base_cero" style="color:#FF0000"></span>
                        </div>
                        <div class="col-md-3">
                            <label for="inputPassword4" class="form-label">IMPUESTO</label>
                            <input type="text" class="form-control" value="0" readonly name="impuesto_cero" id="impuesto_cero">
                        </div>
                        <div class="col-md-3">
                            <label for="inputPassword4" class="form-label">V IMPUESTO</label>
                            <input type="text" class="form-control" name="valor_impuesto_cero" readonly value=0 id="valor_impuesto_cero">
                        </div>
                        <div class="col-3">
                            <label for="inputAddress" class="form-label">TOTAL </label>
                            <input type="text" class="form-control" id="total_venta_cero" name="total_venta_cero" value=0 readonly>
                        </div>
                        <div class="col-3">
                            <label for="inputAddress2" class="form-label">BASE ICO</label>
                            <input type="text" class="form-control" id="base_ico" value="<?php echo $fiscal['base_ico'] ?>" name="base_ico" onkeyup="impoconsumo(event),this.value">
                            <span id="falta_base_ico" style="color:#FF0000"></span>
                        </div>
                        <div class="col-md-3">
                            <label for="inputCity" class="form-label">IMPUESTO</label>
                            <input type="text" class="form-control" value="8" id="impuesto_8" name="impuesto_8" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="inputState" class="form-label">V IMPUESTO</label>
                            <input type="text" class="form-control" name="valor_impuesto_8" id="valor_impuesto_8" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="inputZip" class="form-label">TOTAL </label>
                            <input type="text" class="form-control" id="total_venta_8" name="total_venta_8" readonly>
                        </div>
                        <div class="col-md-12">
                            <label for="inputZip" class="form-label">TOTAL VENTA</label>
                            <input type="text" class="form-control" value=0 id="total_cuadre_caja" readonly name="total_cuadre_caja">
                        </div>
                        <div class="col-4">
                            <button type="button" onclick="enviar_formulario()" class="btn btn-primary">Editar e imprimir </button>
                        </div>
                        <div class="col-2">
                            <button type="button" onclick="solo_guardar_enviar_formulario()" class="btn btn-primary">Editar </button>
                        </div>
                        <div class="col-4">
                            <button type="button" onclick="imprimir_reporte_de_caja_diario(<?php echo $tiene_fiscal_manual['id'] ?>)" class="btn btn-primary">Reimprimir reporte </button>
                        </div>
                    </form>
                    <form class="row g-3" action="<?= base_url('consultas_y_reportes/guardar_reporte_caja_diaria') ?>" method="POST" id="formulario_reporte_caja_diaria">
                        <div class="col-4">
                            <button type="button" class="btn btn-primary">Reimprimir reporte </button>
                        </div>
                    </form>
                <?php } ?>
            </div>

        </div>






    </div>