<?= $this->extend('template/producto') ?>
<?= $this->section('title') ?>
PRODUCTO
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<div class=" container col-md-12">

    <div class="container">
        <div class="row text-center align-items-center flex-row-reverse">
            <div class="col-lg-auto ms-lg-auto">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Facturación</a></li>
                        <li class="breadcrumb-item"><a href="#">Facturación</a></li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-auto ms-lg-auto">
                <p class="text-primary h3">REPORTE DE VENTAS POR UNIDAD DE PRODUCTO</p>
            </div>
            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form class="row g-3" action="<?= base_url('consultas_y_reportes/datos_consultar_producto_agrupado') ?>" method="POST" id="consulta_de_producto_agrupado">
                <input type="hidden" value="<?php echo base_url(); ?>" id="url">
                <div class="col-md-3">
                    <label for="inputEmail4" class="form-label">Fecha inicial </label>
                    <input type="date" class="form-control" id="fecha_inicial_agrupado" name="fecha_inicial_agrupado" onkeyup="saltar(event,'hora_inicial_agrupado')" value="<?php echo date('Y-m-d'); ?>" autofocus>
                    <span class="text-danger" id="error_fecha_inicial_agrupado"></span>
                </div>
                <div class="col-md-3">
                    <label for="inputPassword4" class="form-label">Hora inicial </label>
                    <input type="time" class="form-control" id="hora_inicial_agrupado" name="hora_inicial_agrupado" onkeyup="saltar(event,'fecha_final_agrupado')">

                    <span class="text-danger" id="error_hora_inicial"></span></h1>
                </div>
                <div class="col-3">
                    <label for="inputAddress" class="form-label">Fecha final </label>
                    <input type="date" class="form-control" id="fecha_final_agrupado" name="fecha_final_agrupado" onkeyup="saltar(event,'hora_final_agrupado')" value="<?php echo date('Y-m-d'); ?>">
                    <span class="text-danger" id="error_fecha_final_agrupado"></span>
                </div>
                <div class="col-3">
                    <label for="inputAddress2" class="form-label">Hora final </label>
                    <input type="time" class="form-control" id="hora_final_agrupado" name="hora_final_agrupado" onkeyup="saltar(event,'buscar_producto_agrupado')">
                    <span class="text-danger" id="error_hora_final"></span>
                </div>
                <div class="col-12">
                    <button type="button" id="buscar_producto_agrupado" onclick="reporte_venta_fecha_hora_agrupados()" class="btn btn-primary">Buscar</button>
                </div>
            </form>

        </div>
    </div>
    <br>
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
                            <input type="hidden" value="<?php echo $hora_inicial ?>" id="hora_inicial_reporte" name="hora_inicial_agrupado">
                            <input type="hidden" value="<?php echo $hora_final ?>" id="hora_final_reporte" name="hora_final_agrupado">
                            <button type="submit" class="btn btn-danger btn-icon">Pdf</button>
                        </form>
                    </div>
                    <div class="col">
                        <form action="<?= base_url('caja/exportar_a_excel_reporte_categorias') ?>" method="POST">
                            <input type="hidden" value="<?php echo $id_apertura ?>" id="id_apertura" name="id_apertura">
                            <input type="hidden" value="<?php echo $fecha_inicial ?>" id="fecha_inicial_reporte" name="fecha_inicial_agrupado">
                            <input type="hidden" value="<?php echo $fecha_final ?>" id="fecha_final_reporte" name="fecha_final_agrupado">
                            <input type="hidden" value="<?php echo $hora_inicial ?>" id="hora_inicial_reporte" name="hora_inicial_agrupado">
                            <input type="hidden" value="<?php echo $hora_final ?>" id="hora_final_reporte" name="hora_final_agrupado">
                            <button type="submit" class="btn btn-success btn-icon">Excel</button>
                        </form>
                    </div>
                </div>
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
                                        </tr>

                                        <?php
                                        $productos_categoria = model('reporteProductoModel')->select('*')->where('id_categoria', $detalle['id_categoria'])->findAll();
                                        $total_categoria = model('reporteProductoModel')->selectSum('valor_total')->where('id_categoria', $detalle['id_categoria'])->findAll();
                                        ?>

                                        <?php if ($total_categoria[0]['valor_total'] > 0) { ?>
                                            <tr class="table-dark">
                                                <td>CÓDIGO</td>
                                                <td>PRODUCTO</td>
                                                <td>CANTIDAD</td>
                                                <td>VALOR UNIDAD</td>
                                                <td>TOTAL</td>
                                            </tr>

                                            <?php foreach ($productos_categoria as $detalle_producto) { ?>



                                                <tr>
                                                    <td><?php echo $detalle_producto['codigo_interno_producto'] ?></td>
                                                    <td><?php echo $detalle_producto['nombre_producto'] ?></td>
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
                    <?php model('reporteProductoModel')->truncate(); ?>
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

    <?= $this->endSection('content') ?>