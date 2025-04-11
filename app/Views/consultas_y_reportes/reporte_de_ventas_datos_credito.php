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
        <p class="text-center fs-1 fw-bold">CONSULTA DE VENTAS </p>
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

        <table>
            <tr>
                <td  width="30%">
                    Valor de las facturas: <?php echo "$" . number_format($valor_facturas)  ?>
                </td>
                <td width="30%">Saldo pendiente: <?php #echo "$" . number_format($saldo_pendiente)  ?></td>
            </tr>
        </table>
        <br> 
        <div class="row">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <td scope="col">FECHA DE ELEBORACION </th>
                        <td scope="col">NIT </th>
                        <td scope="col">NOMBRE CLIENTE </th>
                        <td>N° DE FACTURA </td>
                        <td>TIPO DE VENTA </td>
                        <td>FECHA VENCIMIENTO </td>
                        <td>VALOR VENTA </td>
                        <td>SALDO</td>
                        <td>ACCIONES </td>
                    </tr>
                </thead>
                <?php foreach ($facturas as $detalle) { ?>
                    <tbody>
                        <tr>
                            <td width="10%"> <?php echo $detalle['fecha_factura_venta'] ?></td>
                            <td> <?php echo $detalle['nitcliente'] ?></td>
                            <td> <?php echo $detalle['nombrescliente'] ?></td>
                            <td> <?php echo $detalle['numerofactura_venta'] ?></td>
                            <td> <?php echo $detalle['descripcionestado'] ?></td>
                            <td> <?php echo $detalle['fechalimitefactura_venta'] ?></td>
                            <td> <?php echo "$" . number_format($detalle['valor_factura']) ?></td>
                            <td><?php echo "$" . number_format($detalle['saldo']) ?></td>
                            <td class="text-end">
                                <div class="btn-list flex-nowrap">
                                    <form action="#" method="POST">
                                        <input type="hidden" value="<?php #echo $detalle['idusuario_sistema'] 
                                                                    ?>" name="id">
                                        <button class="btn btn-primary btn-icon">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                                <path d="M12 3v3m0 12v3" />
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="#" method="POST">
                                        <button class="btn btn-secondary btn-icon" name="id_usuario" value="<?php # echo $detalle['idusuario_sistema'] 
                                                                                                            ?>">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/eyeglass-2 -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M8 4h-2l-3 10v2.5" />
                                                <path d="M16 4h2l3 10v2.5" />
                                                <line x1="10" y1="16" x2="14" y2="16" />
                                                <circle cx="17.5" cy="16.5" r="3.5" />
                                                <circle cx="6.5" cy="16.5" r="3.5" />
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="#" method="POST">
                                        <button class="btn btn-primary btn-icon" name="id_usuario" value="<?php #echo $detalle['idusuario_sistema'] 
                                                                                                            ?>">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                                                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                                                <rect x="7" y="13" width="10" height="8" rx="2" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    <?php } ?>
                    </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection('content') ?>