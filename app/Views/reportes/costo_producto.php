<?php $session = session(); ?>
<?= $this->extend('template/template') ?>
<?= $this->section('title') ?>
Reporte de costos
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<!-- Select 2 -->

<!-- Jquery date picker  -->
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/Assets/plugin/calendario/jquery-ui-1.12.1.custom/jquery-ui.css">


<div class="container">
    <p class="text-center text-primary h3">INFORME COSTO DE VENTA POR PRODUCTO </p>

    <div class="my-4"></div> <!-- Added space between the title and date inputs -->
    <!-- Agregar una barra de progreso -->


    <input type="hidden" id="url" value="<?php echo base_url() ?>">
    <form action="<?php echo base_url() ?>/factura_directa/exportCostoExcel" method="POST">
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
                <a href="#" onclick="buscarProductos()" class="btn btn-outline-success w-100">Buscar</a>
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
        <table class="table table-striped table-hover">
            <thead class="table-dark" style="position: sticky; top: 0; z-index: 1000;">
                <tr>
                    <td>Fecha</th>
                    <td>Documento</th>
                    <td>Tipo documento</th>
                    <td>Código</th>
                    <td>Producto</th>
                    <td>Cantidad</th>
                    <td>Costo unid</th>
                    <td>V unidad</th>
                    <td>Base</th>
                    <td>IVA</th>
                    <td>INC</th>
                    <td>Total</th>
                </tr>
            </thead>
            <tbody id="datos_costos">
                <?php if (!empty($productos)): ?>
                    <?php foreach ($productos as $detalle): ?>
                        <tr>
                            <td><?php echo $detalle['fecha']; ?></td>
                            <td><?php echo $detalle['numerodocumento']; ?></td>
                            <td><?php echo $detalle['descripcionestado']; ?></td>
                            <td><?php echo $detalle['codigo']; ?></td>
                            <td><?php echo $detalle['nombreproducto']; ?></td>
                            <td><?php echo $detalle['cantidad']; ?></td>
                            <td><?php echo number_format($detalle['costo'] / $detalle['cantidad'], 0, '.', '.'); ?></td>
                            <td><?php echo number_format($detalle['valor_unitario'], 0, '.', '.'); ?></td>
                            <td><?php echo number_format($detalle['total'] - ($detalle['ico'] + $detalle['iva']), 0, '.', '.'); ?></td> <!-- Base -->
                            <td><?php echo number_format($detalle['iva'], 0, '.', '.'); ?></td>
                            <td><?php echo number_format($detalle['ico'], 0, '.', '.'); ?></td>
                            <td><?php echo number_format($detalle['total'], 0, '.', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (!empty($total)): ?>
        <div class="text-end h3 text-primary">
            <span id="total" >Total: <?php echo number_format($total[0]['total'], 0, '.', '.') ?></span><br>
            <span id="iva" >Iva:<?php echo number_format($total[0]['iva'], 0, '.', '.') ?></span><br>
            <span id="ico" >Impoconsumo:<?php echo number_format($total[0]['inc'], 0, '.', '.') ?></span>

        </div>
    <?php endif ?>

    <?php if (empty($productos)): ?>
        <p class="text-danger text-center h3">No hay datos para la fecha <?php echo date('Y-m-d'); ?></p>
    <?php endif; ?>
</div>
</div>


<script>
    async function buscarProductos() {
        document.getElementById("processing-bar").style.display = "block";
        url = document.getElementById('url').value;
        fecha_inicial = document.getElementById('fecha_inicial').value;
        fecha_final = document.getElementById('fecha_final').value;
        try {
            const respuesta = await fetch(url + '/factura_directa/BuscarReporteCosto', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    fecha_inicial: fecha_inicial,
                    fecha_final: fecha_final
                }) // Puedes enviar datos en el body si es necesario
            });



            if (!respuesta.ok) {
                throw new Error('No se pudo obtener el producto');
            }

            const response = await respuesta.json();

            if (response.response == "success") {
                document.getElementById("processing-bar").style.display = "none";
                document.getElementById('datos_costos').innerHTML = response.productos;
                document.getElementById('total').innerHTML = response.total;
                document.getElementById('iva').innerHTML = response.iva;
                document.getElementById('inc').innerHTML = response.inc;

            }
            return response;
        } catch (error) {
            console.error('Error:', error);
            return null;
        }
    }
</script>





























<?= $this->endSection('content') ?>