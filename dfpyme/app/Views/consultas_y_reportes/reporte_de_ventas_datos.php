<?php $user_session = session(); ?>
<?= $this->extend('template/consultas_reportes') ?>
<?= $this->section('title') ?>
REPORTE DE VENTAS DIARIAS
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>
<style>
    .table-striped>tbody>tr:nth-child(odd)>td,
    .table-striped>tbody>tr:nth-child(odd)>th {
        background-color: #cfe2ff;
        /* Choose your own color here */
    }
</style>

<div class="card container">
    <div class="card-body">
        <p class="text-center fs-3 fw-bold">CONSULTA DE VENTAS </p>
        <form class="row g-3" action="<?= base_url('consultas_y_reportes/consulta_de_ventas') ?>" method="POST">
            <div class="col-sm-3">
                <Label>Fecha inicial </Label>
                <input type="date" class="form-control" name="fecha_inicial_reporte">
                <div class="text-danger"><?= session('errors.fecha_inicial_reporte') ?></div>
            </div>
            <div class="col-sm-3">
                <Label>Fecha final </Label>
                <input type="date" class="form-control" name="fecha_final_reporte">
                <div class="text-danger"><?= session('errors.fecha_final_reporte') ?></div>
            </div>
            <div class="col-sm">
                <Label>Cliente </Label>
                <input type="hidden" id="id_cliente_reporte" name="id_cliente_reporte">
                <input type="text" class="form-control" placeholder="Cliente" name="cliente_reporte" id="cliente_reporte" aria-label="State">
            </div>

            <div class="col-sm">
                <label for="">Tipo de documento</label>
                <select class="form-select select2" name="tipo_documento" id="tipo_documento">
                    <?php foreach ($estado as $detalle) { ?>
                        <option value=""></option>
                        <option value="<?php echo $detalle['idestado'] ?>"><?php echo $detalle['descripcionestado'] ?> </option>
                    <?php } ?>
                </select>
                <div class="text-danger"><?= session('errors.tipo_documento') ?></div>
            </div>
            <div></div>
            <div class="row">
                <div class="col-sm">
                    <button type="submit" class="btn btn-primary">Buscar </button>
                </div>
            </div>
        </form>
        <br>
        <p>Valor de las facturas: <?php echo "$" . number_format($valor_facturas)  ?></p>
        <div class="row">
            <table class="table table-striped" id="consulta_ventas_cliente">
                <thead class="table-dark">
                    <tr>
                        <td scope="col">FECHA </th>
                        <td scope="col">NIT </th>
                        <td scope="col">NOMBRE CLIENTE </th>
                        <td>N° DE FACTURA </td>
                        <td>TIPO DE VENTA </td>
                        <td>VALOR VENTA </td>
                        
                    </tr>
                </thead>
                <?php foreach ($facturas as $detalle) { ?>
                    <tbody>
                        <tr>
                            <td> <?php echo $detalle['fecha_factura_venta'] . " " . date("g:i a", strtotime($detalle['horafactura_venta'])) ?></td>
                            <td> <?php echo $detalle['nitcliente'] ?></td>
                            <td> <?php echo $detalle['nombrescliente'] ?></td>
                            <td> <?php echo $detalle['numerofactura_venta'] ?></td>
                            <td> <?php echo $detalle['descripcionestado'] ?></td>
                            <td> <?php echo "$" . number_format($detalle['valor_factura']) ?></td>
                            
                        </tr>
                    <?php } ?>
                    </tbody>
            </table>
        </div>
    </div>
</div>




<?= $this->endSection('content') ?>