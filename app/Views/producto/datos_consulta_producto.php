<div class=" container col-md-12">

    <div class="card">

        <div class="card-body">

            <div class="container">
                <!--        <div class="row gx-1">
                    

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
                            <input type="hidden" value="<?php echo $hora_inicial ?>" id="hora_inicial_reporte" name="hora_inicial_agrupado">
                            <input type="hidden" value="<?php echo $hora_final ?>" id="hora_final_reporte" name="hora_final_agrupado">
                            <button type="submit" class="btn btn-danger btn-icon">Pdf</button>
                        </form>
                    <div class="col">
                        <form action="<? # base_url('caja/exportar_a_excel_reporte_categorias') 
                                        ?>" method="POST">
                            <input type="hidden" value="<?php echo $id_apertura ?>" id="id_apertura" name="id_apertura">
                            <input type="hidden" value="<?php echo $fecha_inicial ?>" id="fecha_inicial_reporte" name="fecha_inicial_agrupado">
                            <input type="hidden" value="<?php echo $fecha_final ?>" id="fecha_final_reporte" name="fecha_final_agrupado">
                            <input type="hidden" value="<?php echo $hora_inicial ?>" id="hora_inicial_reporte" name="hora_inicial_agrupado">
                            <input type="hidden" value="<?php echo $hora_final ?>" id="hora_final_reporte" name="hora_final_agrupado">
                            <button type="submit" class="btn btn-success btn-icon">Excel</button>
                        </form>
                    </div>
                </div> -->
                <br>

                <table class="table" id="consulta_producto_por_fecha">
                    <thead>
                        <tr>
                            <td></td>

                        </tr>
                    </thead>
                    <tbody>
                        <table>
                            <tr>
                            </tr>
                        </table>
                        <tr>
                            <table class="table table-hover ">
                                <thead class="table-light">
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

                                <?php

                                $validar_categoria = model('productoFacturaVentaModel')->validar_categoria($fecha_inicial, $fecha_final);

                                foreach ($validar_categoria as $detalle) {
                                    if (empty($detalle['id_categoria'])) {
                                        $codigo_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                                        $data = [
                                            'id_categoria' => $codigo_categoria['codigocategoria']
                                        ];

                                        $model = model('productoFacturaVentaModel');
                                        $actualizar_id_categoria = $model->set($data);
                                        $actualizar_id_categoria = $model->where('idproducto_factura_venta', $detalle['idproducto_factura_venta']);
                                        $actualizar_id_categoria = $model->update();
                                    }
                                }

                                ?>

                                <tbody>

                                    <?php foreach ($categorias as $detalle) {  //echo $detalle['id_categoria']."</br>"; 
                                    ?>

                                        <?php $nombre_categoria = model('categoriasModel')->select('nombrecategoria')->where('codigocategoria', $detalle['id_categoria'])->first(); ?>

                                        <tr class="table-primary">

                                            <td><?php echo $nombre_categoria['nombrecategoria'] ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        <?php
                                        $productos_categoria = model('reporteProductoModel')->select('*')->where('id_categoria', $detalle['id_categoria'])->findAll();
                                        $total_categoria = model('reporteProductoModel')->selectSum('valor_total')->where('id_categoria', $detalle['id_categoria'])->findAll();
                                        ?>

                                        <?php if ($total_categoria[0]['valor_total'] > 0) { ?>
                                            <tr class="table-dark">
                                                <td>CÓDIGO</td>
                                                <td>PRODUCTO</td>
                                                <td>VALOR COSTO UNIDAD </td>
                                                <td>VALOR COSTO TOTAL</td>
                                                <td>CANTIDAD</td>
                                                <td>VALOR UNIDAD</td>
                                                <td>TOTAL</td>
                                            </tr>

                                            <?php foreach ($productos_categoria as $detalle_producto) { ?>

                                                <?php $costo = model('productoModel')->select('precio_costo')->where('codigointernoproducto',$detalle_producto['codigo_interno_producto'])->first(); ?>

                                                <tr>
                                                    <td><?php echo $detalle_producto['codigo_interno_producto'] ?></td>
                                                    <td><?php echo $detalle_producto['nombre_producto'] ?></td>
                                                    <td><?php echo "$" . number_format($costo['precio_costo'], 0, ",", ".") ?></td>
                                                    <td><?php echo "$" . number_format($costo['precio_costo']*$detalle_producto['cantidad'], 0, ",", ".") ?></td>
                                                    <td><?php echo $detalle_producto['cantidad'] ?></td>
                                                    <td><?php echo "$" . number_format($detalle_producto['precio_venta'], 0, ",", ".") ?></td>
                                                    <td><?php echo "$" . number_format($detalle_producto['valor_total'], 0, ",", ".") ?></td>
                                                </tr>
                                            <?php } ?>

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="table-danger">
                                                    <p class="h2 text-end">TOTAL: <?php echo "$" . number_format($total_categoria[0]['valor_total'], 0, ",", ".") ?></p>
                                                </td>
                                            </tr>

                                        <?php } ?>

                                    <?php } ?>
                                </tbody>
                            </table>
                        </tr>
                    </tbody>
                </table>

                <p class="text-end text-dark h1">
                    <?php $total_ventas = model('reporteProductoModel')->selectSum('valor_total')->findAll(); ?>
                    TOTAL VENTAS :<?php echo "$" . number_format($total_ventas[0]['valor_total'], 0, ",", "."); ?>
                    <?php  ?>
                </p>

                <div class="row">
                    <p class="text-center text-primary fs-3">DEVOLUCIONES </p>

                    <table class="table" id="consulta_producto_por_fecha_devolucion">
                        <thead class="table-dark">
                            <tr>
                                <td scope="col">Código
                                    <!-- Download SVG icon from http://tabler-icons.io/i/arrows-down-up -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <line x1="17" y1="3" x2="17" y2="21" />
                                        <path d="M10 18l-3 3l-3 -3" />
                                        <line x1="7" y1="21" x2="7" y2="3" />
                                        <path d="M20 6l-3 -3l-3 3" />
                                    </svg> </th>
                                <td scope="col">Nombre producto
                                    <!-- Download SVG icon from http://tabler-icons.io/i/arrows-down-up -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <line x1="17" y1="3" x2="17" y2="21" />
                                        <path d="M10 18l-3 3l-3 -3" />
                                        <line x1="7" y1="21" x2="7" y2="3" />
                                        <path d="M20 6l-3 -3l-3 3" />
                                    </svg></th>
                                <td scope="col">Cantidad
                                    <!-- Download SVG icon from http://tabler-icons.io/i/arrows-down-up -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <line x1="17" y1="3" x2="17" y2="21" />
                                        <path d="M10 18l-3 3l-3 -3" />
                                        <line x1="7" y1="21" x2="7" y2="3" />
                                        <path d="M20 6l-3 -3l-3 3" />
                                    </svg></th>
                                <td scope="col">Valor unitario
                                    <!-- Download SVG icon from http://tabler-icons.io/i/arrows-down-up -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <line x1="17" y1="3" x2="17" y2="21" />
                                        <path d="M10 18l-3 3l-3 -3" />
                                        <line x1="7" y1="21" x2="7" y2="3" />
                                        <path d="M20 6l-3 -3l-3 3" />
                                    </svg></th>
                                <td scope="col">Valor total
                                    <!-- Download SVG icon from http://tabler-icons.io/i/arrows-down-up -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <line x1="17" y1="3" x2="17" y2="21" />
                                        <path d="M10 18l-3 3l-3 -3" />
                                        <line x1="7" y1="21" x2="7" y2="3" />
                                        <path d="M20 6l-3 -3l-3 3" />
                                    </svg></th>
                            </tr>
                        </thead>


                    </table>


                </div>



            </div>
        </div>