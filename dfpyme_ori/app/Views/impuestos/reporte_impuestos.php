<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
Entradas de productos
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="col-12">
        <p class="text-center text-primary">Informe de impuestos </p>
        <div class="card">

            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-3">
                        <label for="" class="form-label">Fecha incial </label>
                        <input type="date" class="form-control" id="fecha_inicial" value="<?php echo date('Y-m-d') ?>">
                    </div>
                    <div class="col-3">
                        <label for="" class="form-label">Fecha final </label>
                        <input type="date" class="form-control" id="fecha_final" value="<?php echo date('Y-m-d') ?>">
                    </div>
                    <div class="col-3">
                        <label for="" class="form-label text-light">Fecha final </label>
                        <a href="#" class="btn btn-outline-success active w-100" onclick="obtenerReporte()">
                            Buscar
                        </a>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-3">
                        <span id="fact_ini" class="text-primary h3"></span>
                    </div>
                    <div class="col-3">
                        <span id="fact_fin" class="text-primary h3"></span>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <form action="<?php echo base_url() ?>/consultas_y_reportes/reporte_impuestos">
                            <button type="submit" class="btn btn-outline-success">EXCEL</button>
                        </form>
                    </div>
                </div>

                <input type="hidden" value="<?php echo base_url() ?>" id="url">
                <div class="mb-3" id="resultados_bar" style="display: none;">
                    <p class=" h3 text-primary">Buscando registros </p>
                    <div class="progress">
                        <div class="progress-bar progress-bar-indeterminate bg-green"></div>
                    </div>
                </div>
                <div class="table-responsive" style="max-height: 65vh; overflow-y: auto;">
                    <table class="table" id="tableImpuestos">
                        <thead class="table-dark">
                            <tr>
                                <td scope="col">Dia </th>
                                <td scope="col">Fecha </th>
                                <td scope="col">Base INC 0 </th>
                                <td scope="col">INC 0 </th>
                                <td scope="col">Base INC 8 </th>
                                <td scope="col">INC 8 </th>
                                    <!--  <td scope="col">Venta INC</th> -->
                                <td scope="col">Base IVA 0 </th>
                                <td scope="col">IVA 0 </th>
                                    <!--  <td scope="col">Venta IVA 0 </th> -->
                                <td scope="col"> Base IVA 5 </th>
                                <td scope="col"> IVA 5 </th>
                                    <!-- <td scope="col"> Venta IVA 5 </th> -->
                                <td scope="col">Base 19 </th>
                                <td scope="col">IVA 19 </th>
                                    <!-- <td scope="col">Venta IVA 19 </th> -->
                                <td scope="col">Total INC </th>
                                <td scope="col">Total IVA</th>
                                <td scope="col">Total venta </th>
                            </tr>
                        </thead>
                        <tbody id="resultados">

                        </tbody>
                    </table>
                </div>
                <p id="total_venta" class="text-end h2 text-primary">total venta</p>
                <p id="total_iva" class="text-end h2 text-primary">total IVA</p>
                <p id="total_inc" class="text-end h2 text-primary">total INC</p>

            </div>
        </div>
    </div>
</div>

<!-- 
<script>
    var url = document.getElementById("url").value;
    var fecha_inicial = document.getElementById("fecha_inicial").value;
    var fecha_final = document.getElementById("fecha_final").value;
    $.ajax({
        data: {
            fecha_inicial,
            fecha_final
        },
        url: url +
            "/" +
            "eventos/numero_documento",
        type: "post",
        success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {

               

            }
        },
    });
</script>
 -->

<!-- J QUERY -->
<script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
<script>
    $(document).ready(function() {
        var url = document.getElementById("url").value;
        var fecha_inicial = document.getElementById("fecha_inicial").value;
        var fecha_final = document.getElementById("fecha_final").value;

        $.ajax({
            data: {
                fecha_inicial,
                fecha_final
            },
            url: url + "/reportes/reporte_impuestos",
            type: "post",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    var rows = ""; // Inicializamos la variable `rows`
                    resultado.datos.forEach(item => {
                        rows += `<tr>
                               <td>${item.dia_proceso}</td>
                               <td>${item.fecha}</td>
                               <td>${item.base_inc_0}</td>
                               <td>${item.inc_0}</td>
                               <td>${item.base_inc_8}</td>
                               <td>${item.inc_8}</td>
                               <td>${item.base_iva_0}</td>
                               <td>${item.iva_0}</td>
                               <td>${item.base_iva_5}</td>
                               <td>${item.iva_5}</td>
                               <td>${item.base_iva_19}</td>
                               <td>${item.iva_19}</td>
                               <td>${item.total_inc }</td>
                               <td>${item.total_iva}</td>
                               <td>${item.total_venta}</td>
                           </tr>`;

                    });

                    // Actualizamos el contenido de `#resultados` solo una vez, después de completar el bucle
                    document.getElementById('resultados').innerHTML = rows;
                    document.getElementById('fact_ini').innerHTML = resultado.primer_factura;
                    document.getElementById('fact_fin').innerHTML = resultado.ultima_factura;
                    document.getElementById('total_venta').innerHTML = resultado.total_venta;
                    document.getElementById('total_iva').innerHTML = resultado.total_iva;
                    document.getElementById('total_inc').innerHTML = resultado.total_inc;

              
                }

                if (resultado.resultado == 0) {
                    sweet_alert_centrado('warning', 'No hay resultados debe seleccionar otro rango de fechas ')
                }
            },
        });
    });
</script>

<script>
    function obtenerReporte() {
        var url = document.getElementById("url").value;
        var fecha_inicial = document.getElementById("fecha_inicial").value;
        var fecha_final = document.getElementById("fecha_final").value;

        const div = document.getElementById("resultados_bar");
        div.style.display = "block";

        $.ajax({
            data: {
                fecha_inicial,
                fecha_final
            },
            url: url + "/reportes/reporte_impuestos",
            type: "post",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {
                    const div = document.getElementById("resultados_bar");
                    div.style.display = "none";
                    var rows = ""; // Inicializamos la variable `rows`
                    resultado.datos.forEach(item => {
                        rows += `<tr>
                            <td>${item.dia_proceso}</td>
                               <td>${item.fecha}</td>
                               <td>${item.base_inc_0}</td>
                               <td>${item.inc_0}</td>
                               <td>${item.base_inc_8}</td>
                               <td>${item.inc_8}</td>
                               <td>${item.base_iva_0}</td>
                               <td>${item.iva_0}</td>
                               <td>${item.base_iva_5}</td>
                               <td>${item.iva_5}</td>
                               <td>${item.base_iva_19}</td>
                               <td>${item.iva_19}</td>
                               <td>${item.total_iva}</td>
                               <td>${item.total_inc }</td>
                               <td>${item.total_venta}</td>
                        </tr>`;
                    });
                    // Actualizamos el contenido de `#resultados` solo una vez, después de completar el bucle
                    document.getElementById('resultados').innerHTML = rows;
                    document.getElementById('fact_ini').innerHTML = resultado.primer_factura;
                    document.getElementById('fact_fin').innerHTML = resultado.ultima_factura;
                    document.getElementById('total_venta').innerHTML = resultado.total_venta;
                    document.getElementById('total_iva').innerHTML = resultado.total_iva;
                    document.getElementById('total_inc').innerHTML = resultado.total_inc;
                }

                if (resultado.resultado == 0) {
                    const div = document.getElementById("resultados_bar");
                    div.style.display = "none";
                    sweet_alert_centrado('warning', 'No hay resultados debe seleccionar otro rango de fechas ')
                }
            },
        });
    }
</script>







<?= $this->endSection('content') ?>