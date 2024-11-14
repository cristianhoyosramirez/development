<div class=" container col-md-12">



    <div class="card">

        <div class="card-body">

            <div class="container">
                <div class="row gx-1">
                    <p class="text-primary h3">PERIODO DEL <?php echo $fecha_inicial  ?> al <?php echo $fecha_final  ?> </p>
                    <div class="col-1 w-5 ">
                        <form action="<?= base_url('consultas_y_reportes/datos_consultar_producto_agrupado_pdf') ?>">
                            <?php
                            if (!empty($id_apertura)) {
                                $id_apertura = $id_apertura;
                            }
                            if (empty($id_apertura)) {
                                $id_apertura = "";
                            }
                            ?>
                            <input type="hidden" value="<?php echo $id_apertura ?>" id="id_apertura" name="id_apertura">
                            <input type="hidden" value="<?php echo $fecha_inicial ?>" id="fecha_inicial_reporte" name="fecha_inicial_agrupado">
                            <input type="hidden" value="<?php echo $fecha_final ?>" id="fecha_final_reporte" name="fecha_final_agrupado">
                            <input type="hidden" value="<?php //echo $hora_inicial 
                                                        ?>" id="hora_inicial_reporte" name="hora_inicial_agrupado">
                            <input type="hidden" value="<?php //echo $hora_final 
                                                        ?>" id="hora_final_reporte" name="hora_final_agrupado">
                            <button type="submit" class="btn btn-danger btn-icon">Pdf</button>
                        </form>
                    </div>
                    <div class="col">
                        <form action="<?= base_url('caja/exportar_a_excel_reporte_categorias') ?>" method="POST">
                            <input type="hidden" value="<?php echo $id_apertura ?>" id="id_apertura" name="id_apertura">
                            <input type="hidden" value="<?php echo $fecha_inicial ?>" id="fecha_inicial_reporte" name="fecha_inicial_agrupado">
                            <input type="hidden" value="<?php echo $fecha_final ?>" id="fecha_final_reporte" name="fecha_final_agrupado">
                            <input type="hidden" value="<?php //echo $hora_inicial 
                                                        ?>" id="hora_inicial_reporte" name="hora_inicial_agrupado">
                            <input type="hidden" value="<?php //echo $hora_final 
                                                        ?>" id="hora_final_reporte" name="hora_final_agrupado">
                            <button type="submit" class="btn btn-success btn-icon">Excel</button>
                        </form>
                    </div>
                </div>
                <br>


                <table class="table table-hover ">
                    <thead class="table-light" style=" position: sticky;top: 0;">
                        <tr>
                            <td scope="col">
                                </th>
                            <td scope="col">
                                </th>
                            <td scope="col">
                                </th>
                            <td scope="col">
                                </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-dark">
                            <td>CÓDIGO</td>
                            <td>PRODUCTO</td>
                            <td>VALOR UNIDAD</td>
                            <td>CANTIDAD</td>

                            <td>TOTAL</td>
                        </tr>

                        <?php foreach ($productos as $detalle_producto) {

                            $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $detalle_producto['codigointernoproducto'])->first();
                            //$datos = model('productoFacturaVentaModel')->datos_producto_factura_cantidad($detalle_producto['codigointernoproducto'], $detalle_producto['valor'], $id_inicial, $id_final);

                        ?>
                            <tr>
                                <td><?php echo $detalle_producto['codigointernoproducto'] ?></td>
                                <td><?php echo $nombre_producto['nombreproducto'] ?></td>
                                <td><?php echo "$" . number_format($detalle_producto['valor'], 0, ",", ".") ?></td>
                                <td><?php echo $detalle_producto['cantidadproducto_factura_venta'] ?></td>
                                <td><?php echo "$" . number_format($detalle_producto['total'], 0, ",", ".") ?></td>
                                <td><?php #echo $detalle_producto['cantidadproducto_factura_venta']* $detalle_producto['total'] ?></td>
                                <td><?php #echo $detalle_producto['idproducto_factura_venta'] ?></td>
                                <td><?php #echo "$" . number_format($datos[0]['total'], 0, ",", ".") ?></td>

                            </tr>
                        <?php } ?>

                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="table-danger">
                                <p class="h2 text-end">TOTAL: <?php echo "$" . number_format($total, 0, ",", ".")?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                </tbody>
            </div>
        </div>