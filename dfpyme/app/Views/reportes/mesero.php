<?php $session = session(); ?>
<?= $this->extend('template/template') ?>
<?= $this->section('title') ?>
Reporte de ventas por usuario
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<!-- Select 2 -->

<!-- Jquery date picker  -->
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/Assets/plugin/calendario/jquery-ui-1.12.1.custom/jquery-ui.css">


<div class="container">
    <p class="text-center text-primary h3">INFORME DE VENTAS POR MESERO </p>

    <div class="my-4"></div> <!-- Added space between the title and date inputs -->
    <!-- Agregar una barra de progreso -->


    <input type="hidden" id="url" value="<?php echo base_url() ?>">
    <form action="<?php echo base_url() ?>/factura_directa/exportCostoExcel" method="POST">
        <div class="row">
            <div class="col-2">
                <label for="" class="form-label">Mesero </label>
                <select name="idMesero" id="idMesero" class="form-select">
                    <?php foreach ($meseros as $detalle): ?>
                        <option value="<?php echo $detalle['idusuario_sistema']; ?>"><?php echo $detalle['nombresusuario_sistema']; ?></option>
                    <?php endforeach ?>
                </select>
            </div>
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
                <a href="#" onclick="buscarReporte()" class="btn btn-outline-success w-100">Buscar</a>
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

    <table class="table">
        <thead class="table-dark">
            <tr>

                <td scope="col">Usuario</th>
                <td scope="col">total venta </th>
            </tr>
        </thead>
        <tbody id="datosUsuario">

            <?php foreach ($usuarios as $detalle):
                $total = model('kardexModel')->getTotal($fechaInicial, $fechaFinal, $detalle['idusuario']);
                $usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $detalle['idusuario'])->first();
            ?>
                <tr>

                    <td><?php echo $usuario['nombresusuario_sistema']; ?> </td>
                    <td>
                        <?php echo number_format($total[0]['total'], 0, '', '.'); ?>

                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <input type="hidden" value="<?php echo base_url() ?> " id="url">
</div>
</div>
<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_centrado.js"></script>
<script>
    async function buscarReporte() {
        const fechaInicial = document.getElementById('fecha_inicial').value;
        const fechaFinal = document.getElementById('fecha_final').value;
        const idMesero = document.getElementById('idMesero').value;
        const url = document.getElementById('url').value;

        try {
            //const response = await fetch('/ruta/al/controlador', {
            const response = await fetch(url + '/reportes/reporteVentasUsuario', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest' // útil si usas CodeIgniter
                },
                body: JSON.stringify({
                    fechaInicial: fechaInicial,
                    fechaFinal: fechaFinal,
                    idMesero: idMesero
                })
            });

            if (!response.ok) throw new Error('Error en la solicitud');

            const data = await response.json();
            //document.getElementById('resultado').innerHTML = JSON.stringify(data, null, 2);
            if (data.response == "success") {
                document.getElementById('datosUsuario').innerHTML = data.ventas
                sweet_alert_centrado('success', 'Registro encontrados')
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('resultado').innerText = 'Ocurrió un error al buscar el reporte.';
        }
    }
</script>

































<?= $this->endSection('content') ?>