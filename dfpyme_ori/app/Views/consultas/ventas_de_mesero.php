<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<p class="text-center text-primary h1">Reporte de ventas por mesero </p> <br>

<div class="container">
    <div class="row">
        <input type="hidden" id="url" value="<?php echo base_url() ?>">
        <!--    <div id="entre_fechas" class="col-4">
            <table>
                <tr>
                    <td>Desde </td>
                    <td colspan="3"> <input type="text" class="form-control" id="fecha_inicial" value="<?php echo date('Y-m-d'); ?>">
                    </td>
                    <td>Hasta</td>
                    <td> <input type="text" class="form-control" id="fecha_final" value="<?php echo date('Y-m-d'); ?>">
                    </td>
                </tr>
            </table>
        </div> -->
        <div id="entre_fechas" class="col-12">
            <div class="row">
                <div class="col-2">
                    <label for="">Mesero </label>
                    <select class="form-select" aria-label="Default select example">
                        <?php foreach ($meseros as $detalle) : ?>
                            <option value="<?php echo $detalle['idusuario_sistema'] ?>"><?php echo $detalle['nombresusuario_sistema'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="fecha_inicial">Desde</label>

                    <div class="input-group input-group-flat">
                        <input type="text" class="form-control" id="fecha_inicial_mesero" value="<?php echo date('Y-m-d'); ?>">
                        <span class="input-group-text">
                            <a href="#" class="link-secondary" title="Limpiar campo" data-bs-toggle="tooltip" onclick="limpiar_campo('fecha_inicial')"><!-- Download SVG icon from http://tabler-icons.io/i/x -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M18 6l-12 12" />
                                    <path d="M6 6l12 12" />
                                </svg>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="fecha_final">Hasta</label>
                    <div class="input-group input-group-flat">
                        <input type="text" class="form-control" id="fecha_final_mesero" value="<?php echo date('Y-m-d'); ?>">
                        <span class="input-group-text">
                            <a href="#" class="link-secondary" title="Limpiar campo" data-bs-toggle="tooltip" onclick="limpiar_campo('fecha_final')"><!-- Download SVG icon from http://tabler-icons.io/i/x -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M18 6l-12 12" />
                                    <path d="M6 6l12 12" />
                                </svg>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="col-1" id="boton_consulta"> <br>
                    <button type="button" class="btn btn-primary btn-icon" onclick="buscar()" title="Buscar datos" data-bs-toggle="tooltip">
                        <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <circle cx="10" cy="10" r="7" />
                            <line x1="21" y1="21" x2="15" y2="15" />
                        </svg>
                    </button>
                </div>
                <div class="col-3">
                    <p class="text-red" id="error_fecha"></p>
                </div>
                <div class="col-md-1 text-end"><br>
                    <form action="#" method="POST">
                        <input type="hidden" id="inicial" name="inicial" value="<?php echo date('Y-m-d'); ?>">
                        <input type="hidden" id="final" name="final" value="<?php echo date('Y-m-d'); ?>">
                        <button class="btn btn-outline-success w-100 " type="submit" title="Exportar a Excel" data-bs-toggle="tooltip">Excel </button>
                    </form>
                </div>
                <div class="col-md-1 text-end"><br>

                    <!-- <button class="btn btn-outline-success" onclick="exportToExcel()" title="Exportar a excel " data-bs-toggle="tooltip">Excel</button> -->

                    <form action="#" method="POST">
                        <input type="hidden" id="inicial" name="inicial" value="<?php echo date('Y-m-d'); ?>">
                        <input type="hidden" id="final" name="final" value="<?php echo date('Y-m-d'); ?>">
                        <button class="btn btn-outline-danger w-100" type="submit" title="Exportar a PDF" data-bs-toggle="tooltip">PDF</button>
                    </form>
                </div>
            </div>

        </div>


    </div>

    <br>
    <table class="table">
        <thead class="table-dark">
            <tr>
                <td scope="col">Mesero </th>
                <td scope="col">Total ventas </th>
                <td scope="col">Número de ventas</th>
                <td scope="col">Acción </th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($id_meseros)) { ?>
                <?php foreach ($id_meseros as $detalle) : ?>
                    <?php $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $detalle['id_mesero'])->first(); ?>
                    <?php $total_venta = model('pagosModel')->get_ventas_mesero(date('Y-m-d'), $detalle['id_mesero']); ?>
                    <?php $numero_ventas = model('pagosModel')->get_total_ventas_mesero(date('Y-m-d'), $detalle['id_mesero']); ?>

                    <tr>
                        <td scope="row"><?php echo $nombre_usuario['nombresusuario_sistema'] ?></th>
                        <td scope="row"><?php echo "$ " . number_format($total_venta[0]['total_venta'], 0, ',', '.') ?></th>
                        <td scope="row"><?php echo $numero_ventas[0]['numero_ventas'] ?></th>

                        <td><button type="button" class="btn btn-outline-success" onclick="detalle_venta(<?php echo $detalle['id_mesero'] ?>) ">Detalle</button></td>
                    </tr>
                <?php endforeach ?>
            <?php } ?>
            <?php if (empty($id_meseros)) { ?>
                <tr>
                    <td colspan="4">
                        <p class="text-center h2 text-warning">!No se encontraron registros de ventas¡ </p>
                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>
    <p class="text-end text-primary h1">Total de ventas:0</p>
</div>

<script>
    function detalle_venta(id_mesero) {

        var url = document.getElementById("url").value;
        var fecha_inicial = document.getElementById("fecha_inicial_mesero").value;
        var fecha_final = document.getElementById("fecha_final_mesero").value;

        $.ajax({
            data: {
                id_mesero,fecha_inicial, fecha_final
            },
            url: url + "/" + "inventario/reporte_meseros",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    $('#productos_categoria').html(resultado.productos)

                }
            },
        });

    }
</script>

<?= $this->endSection('content') ?>