<?php $session = session(); ?>
<?= $this->extend('template/template') ?>
<?= $this->section('title') ?>
Reporte formas pago
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<!-- Select 2 -->

<!-- Jquery date picker  -->
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/Assets/plugin/calendario/jquery-ui-1.12.1.custom/jquery-ui.css">


<div class="container">
    <p class="text-center text-primary h3">INFORME DE FORMAS DE PAGO </p>

    <div class="my-4"></div> <!-- Added space between the title and date inputs -->
    <!-- Agregar una barra de progreso -->


    <input type="hidden" id="url" value="<?php echo base_url() ?>">
    <form action="<?php echo base_url() ?>/caja_general/exportFormasPagoExcel" method="POST">
        <div class="row">
            <div class="col-2">
                <label for="" class="form-label">Fecha inicial </label>
                <input type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" id="fecha_inicial" name="fecha_inicial">
            </div>
            <div class="col-2">
                <label for="" class="form-label">Fecha final </label>
                <input type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" id="fecha_final" name="fecha_final">
            </div>
            <div class="col-2">
                <label for="" class="form-label text-light">Fech </label>
                <a href="#" onclick="buscarPorFechas()" class="btn btn-outline-success w-100">Buscar</a>
            </div>
            <div class="col-2">
                <label for="" class="form-label text-light">Fech </label>
                <button type="submit" class="btn btn-outline-success "> Excel </button>
            </div>
        </div>
    </form>


    <div id="processing-bar" style="display: none;">
        <p class="text-primary h3">Procesando petición</p>
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
        </div>
    </div>
    <div class="my-3"></div> <!-- Added space between the buttons and the table -->

    <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
        <table class="table">
            <thead class="table-dark">
                <tr>

                    <td scope="col">FECHA </th>
                    <td scope="col">HORA</th>
                    <td scope="col">FACTURA</th>
                    <td scope="col">VALOR DOCUMENTO</th>
                    <td>PAGO EFECTIVO </td>
                    <td>PAGO BANCO</td>
                </tr>
            </thead>
            <tbody id="resFormasPago">

                <?php foreach ($formasPago as $detalle): ?>
                    <tr>

                        <td>
                            <?php
                            $meses = [
                                "",
                                "enero",
                                "febrero",
                                "marzo",
                                "abril",
                                "mayo",
                                "junio",
                                "julio",
                                "agosto",
                                "septiembre",
                                "octubre",
                                "noviembre",
                                "diciembre"
                            ];
                            $fecha = new DateTime($detalle['fecha']);
                            echo $fecha->format('d') . ' ' . $meses[(int)$fecha->format('m')] . ' ' . $fecha->format('Y');
                            ?>
                        </td>

                        <td>
                            <?php
                            $hora = date("h:i A", strtotime($detalle['hora']));
                            echo $hora;
                            ?>
                        </td>
                        <td>
                            <?php echo $detalle['numero'] ?>
                        </td>
                        <td>
                            <?php echo number_format($detalle['total_documento'], 0, ',', '.'); ?>
                        </td>
                        <td>
                            <?php echo number_format($detalle['efectivo'], 0, ',', '.'); ?>
                        </td>
                        <td>
                            <?php echo number_format($detalle['transferencia'], 0, ',', '.'); ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>




</div>
</div>

<script>
    async function buscarPorFechas() {
        try {
            const fechaInicial = document.getElementById('fecha_inicial').value;
            const fechaFinal = document.getElementById('fecha_final').value;

            let response = await fetch('<?= base_url('administracion_impresora/getFormasPago') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    fecha_inicial: fechaInicial,
                    fecha_final: fechaFinal
                })
            });

            let data = await response.json();

            //document.getElementById('tbodyInsumos').innerHTML = data.productos;

            if (data.response=="success"){
             document.getElementById('resFormasPago').innerHTML=data.formasPago
            }

        } catch (error) {
            console.error("Error en la petición:", error);
        }
    }
</script>

































<?= $this->endSection('content') ?>