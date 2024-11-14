<?= $this->extend('template/producto') ?>
<?= $this->section('title') ?>
PRODUCTO
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<div class="container">

</div>
<br>
<!--Star Breadcum-->
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
            <p class="text-primary h3">REPORTE DE VENTAS  </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>
<br>
<div class=" container col-md-12">
    <div class="card">

        <div class="card-body">
            <p class="text-primary h3">Periodo desde <?php echo $fecha_inicial ?> hasta <?php echo $fecha_final ?> </p>
            <div class="d-grid gap-2 d-md-block">
                <form action="<?= base_url('consultas_y_reportes/pdf_reporte_producto') ?>" method="POST">
                    <input type="hidden" name="fecha_inicial_pdf" value="<?php echo $fecha_inicial ?>">
                    <input type="hidden" name="fecha_final_pdf" value="<?php echo $fecha_final ?>">
                    <button type="submit" class="btn btn-danger  btn-icon">
                        PDF
                    </button>
                </form>

            </div>
            
            <table class="table" id="consulta_producto_por_fecha">
                <thead class="table-dark">
                    <tr>
                        <td style="width:10%">Fecha </th>
                        <td style="width:10%"> Hora </th>
                        <td>N° Factura</th>
                        <td>Cantidad</th>
                        <td>Producto</th>
                        <td>Valor unitario</th>
                        <td>Total</th>
                        <td>Cliente</th>

                        <td scope="col">Caja</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos_producto as $detalle) { ?>
                        <tr>
                            <th><?php echo $detalle['fecha_venta'] ?></th>
                            <th><?php echo date("g:i a", strtotime($detalle['fecha_y_hora_venta'])) ?></th>
                            <th><?php echo $detalle['numerofactura_venta'] ?></td>
                            <th><?php echo $detalle['cantidadproducto_factura_venta'] ?></td>
                            <th><?php echo $detalle['nombreproducto'] ?></td>
                            <th><?php echo "$" . number_format($detalle['precio_unitario'], 0, ",", ".") ?></td>
                            <th><?php echo "$" . number_format($detalle['total'], 0, ",", ".") ?></td>
                                <?php $datos_factura = model('facturaVentaModel')->consulta_producto($detalle['id']) ?>
                            <th>
                                <?php echo $datos_factura[0]['nombrescliente'] ?>
                            </th>

                            <th>
                                <?php echo $datos_factura[0]['numerocaja'] ?>
                            </th>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>

            <p class="text-primary h1 text-xl-end">TOTAL VENTA <?php echo "$" . number_format($total, 0, ",", ".") ?> </p>
        </div>
    </div>

    <?= $this->endSection('content') ?>