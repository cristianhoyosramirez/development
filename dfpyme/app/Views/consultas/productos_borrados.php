<?php $session = session(); ?>
<?= $this->extend('template/template') ?>
<?= $this->section('title') ?>
Reporte de costos
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<!-- Jquery date picker  -->
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/Assets/plugin/calendario/jquery-ui-1.12.1.custom/jquery-ui.css">
<!-- Data tables -->
<link href="<?= base_url() ?>/Assets/plugin/data_tables/bootstrap.min.css" />
<link href="<?= base_url() ?>/Assets/plugin/data_tables/dataTables.bootstrap5.min.css" />

<!-- Select 2 -->
<link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="container">
    <p class="text-center text-primary h3">INFORME PRODUCTOS BORRADOS </p>

    <div class="my-4"></div> <!-- Added space between the title and date inputs -->


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
            <div class="col-3">
                <label class="form-label">Período</label>
                <select class="form-select" id="periodo" onchange="select_periodo(this.value)">
                    <option></option>
                    <option value="1">Desde el inicio </option>
                    <option value="2">Fecha </option>
                    <option value="3">Periodo</option>
                </select>

                <span id="error_periodo" class="text-red"></span>

            </div>
            <div class="col-md-2" id="inicial" style="display:none">
                <label for="fecha_inicial" class="form-label">Fecha inicial </label>

                <div class="input-group input-group-flat">
                    <input type="text" class="form-control" id="fecha_inicial" value="<?php echo date('Y-m-d'); ?>" onkeyup="document.getElementById('error_de_fecha_inicial').innerText = '';"  onchange="document.getElementById('error_de_fecha_inicial').innerText = '';">
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
                <span id="error_de_fecha_inicial" class="text-danger"></span>
            </div>
            <div class="col-md-2" id="final" style="display: none;">
                <label for="fecha_final" class="form-label">Fecha final </label>
                <div class="input-group input-group-flat">
                    <input type="text" class="form-control" id="fecha_final" value="<?php echo date('Y-m-d'); ?>">
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
            <div class="col-1" id="boton_consulta">
                <label for="fecha_final" class="form-label text-light">Fecha final </label>
                <button type="button" class="btn btn-primary btn-icon" onclick="buscar()" title="Buscar datos" data-bs-toggle="tooltip">
                    <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="10" cy="10" r="7" />
                        <line x1="21" y1="21" x2="15" y2="15" />
                    </svg>
                </button>
            </div>
            <div class="col-4">
                <p class="text-red" id="error_fecha"></p>
            </div>
            <div class="col-md-2 text-end"><br>
                <!--  <form action="<?= base_url('reportes/exportar_reporte_costo') ?>" method="POST">
                        <input type="hidden" id="inicial" name="inicial">
                        <input type="hidden" id="final" name="final">
                        <button class="btn btn-outline-danger" type="submit" title="Exportar a PDF" data-bs-toggle="tooltip">PDF</button>
                    </form> -->
            </div>
            <div class="col-md-1 text-start"><br>

                <!-- <button class="btn btn-outline-success" onclick="exportToExcel()" title="Exportar a excel " data-bs-toggle="tooltip">Excel</button> -->

                <!-- <form action="<?= base_url('reportes/exportar_reporte_costo') ?>" method="POST">
                        <input type="hidden" id="inicial" name="inicial">
                        <input type="hidden" id="final" name="final">
                        <button class="btn btn-outline-danger" type="submit" title="Exportar a PDF" data-bs-toggle="tooltip">PDF</button>
                    </form> -->
            </div>
        </div>

    </div>





    <div class="my-3"></div> <!-- Added space between the buttons and the table -->
    <div class="table-responsive">
        <table class="table">
            <thead class="table-dark">
               <!--  <td scope="col">Pedido</th> -->
                <td scope="col">Código </th>
                <td scope="col">Producto </th>
                <td scope="col">Cantidad </th>
                <td scope="col">Fecha</th>
                <td scope="col">Hora</th>
                <!-- <td scope="col">Usuario creacion</th>
                <td scope="col">Usuario eliminación</th> -->

            </thead>
            <tbody id="productos_borrados">

                <?php if (!empty($productos)) {  ?>
                    <?php foreach ($productos as $detalle) : ?>

                        <tr>

                            <td><?php echo $detalle['pedido'] ?></td>
                            <td><?php echo $detalle['codigointernoproducto'] ?></td>
                            <td><?php echo $detalle['nombreproducto'] ?></td>
                            <td><?php echo $detalle['cantidad'] ?></td>
                            <td><?php echo $detalle['fecha_eliminacion'] ?></td>
                            <td><?php echo $detalle['hora_eliminacion'] ?></td>
                            <td><?php echo $detalle['eliminador_nombre'] ?></td>
                            <td><?php echo $detalle['mesero_nombre'] ?></td>

                        </tr>

                    <?php endforeach ?>
                <?php } ?>

            </tbody>
        </table>

        <br>
        <p class="text-primary h1 text-center " id="no_hay_datos"> </p>
    </div>
</div>

<!-- jQuery -->
<script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
<!-- jQuery UI -->
<script src="<?= base_url() ?>/Assets/plugin/jquery-ui/jquery-ui.js"></script>
<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>

<!--select2 -->
<script src="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.js"></script>

<script>
    function select_periodo(periodo) {

        var inicial = document.getElementById("inicial");
        var final = document.getElementById("final");
        $('#error_periodo').html('')

        if (periodo == 1) {
            inicial.style.display = "none";
            final.style.display = "none";
        }
        if (periodo == 2) {
            inicial.style.display = "block";
            final.style.display = "none";
        }
        if (periodo == 3) {
            inicial.style.display = "block";
            final.style.display = "block";
        }

    }
</script>

<script>
    $("#periodo").select2({
        width: "100%",
        language: "es",
        theme: "bootstrap-5",
        allowClear: false,
        placeholder: "Seleccionar un rango ",
        minimumResultsForSearch: -1,
        language: {
            noResults: function() {
                return "No hay resultado";
            },
            searching: function() {
                return "Buscando..";
            }
        },

    });
</script>

<script>
    let mensaje = "<?php echo $session->getFlashdata('mensaje'); ?>";
    let iconoMensaje = "<?php echo $session->getFlashdata('iconoMensaje'); ?>";
    if (mensaje != "") {

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            //position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: iconoMensaje,
            title: mensaje
        })


    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var input = document.getElementById('fecha_inicial');
        var input2 = document.getElementById('fecha_final');
        var parrafo = document.getElementById('error_fecha');

        // Función para borrar el párrafo
        function borrarParrafo() {
            parrafo.textContent = ""; // o parrafo.innerHTML = "";
        }

        // Evento clic en el input
        input.addEventListener('click', borrarParrafo);

        // Evento de escritura en el input
        input.addEventListener('input', borrarParrafo);


        // Eventos clic y escritura para el input2
        input2.addEventListener('click', borrarParrafo);
        input2.addEventListener('input', borrarParrafo);
    });
</script>
<script>
    function limpiar_campo(campo) {
        $('#' + campo).val('');
    }
</script>


<script>
    $(function() {
        var dateFormat = "yy-mm-dd"; // Cambia el formato de fecha a "yy-mm-dd"

        var from = $("#fecha_inicial").datepicker({
            changeMonth: true,
            numberOfMonths: 1,
            changeYear: true,
            dateFormat: dateFormat, // Establece el nuevo formato de fecha
            onClose: function(selectedDate) {
                to.datepicker("option", "minDate", selectedDate);
            }
        });

        var to = $("#fecha_final").datepicker({
            changeMonth: true,
            numberOfMonths: 1,
            changeYear: true,
            dateFormat: dateFormat, // Establece el nuevo formato de fecha
            onClose: function(selectedDate) {
                from.datepicker("option", "maxDate", selectedDate);
            }
        });

        // Export to Excel and PDF functionality
        $('#exportExcelBtn').click(function() {
            // Agrega tu código para exportar a Excel aquí
        });

        $('#exportPdfBtn').click(function() {
            // Agrega tu código para exportar a PDF aquí
        });
    });
</script>

<script>
    function buscar() {
        var url = document.getElementById("url").value;
        var fecha_inicial = document.getElementById("fecha_inicial").value;
        var fecha_final = document.getElementById("fecha_final").value;
        var periodo = document.getElementById("periodo").value;

        if (periodo != "") {


            if (periodo == 1) {
                buscar_productos_borrados(periodo, fecha_inicial, fecha_final,url)
            }

            if (periodo == 2){
                if(fecha_inicial !=""){
                    buscar_productos_borrados(periodo, fecha_inicial, fecha_final,url)
                }else if (fecha_inicial ==""){
                    $('#error_de_fecha_inicial').html('Debe definir una fecha')                }
            }
            if (periodo == 3){
                if(fecha_inicial !="" && fecha_final !="" ){
                    buscar_productos_borrados(periodo, fecha_inicial, fecha_final,url)
                }else if (fecha_inicial =="" || fecha_final =="" ){
                    $('#error_de_fecha_inicial').html('Debe definir una fecha')                }
            }


        } else if (periodo == "") {

            $('#error_periodo').html('No hay periodo seleccionado')

        }
    }
</script>


<script>
    function buscar_productos_borrados(periodo, fecha_inicial, fecha_final,url) {

        $.ajax({
            url: url + "/" + "reportes/datos_productos_borrados",
            type: "POST",
            data: {
                fecha_inicial,
                fecha_final,
                periodo
            },
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {
                    $('#productos_borrados').html(resultado.productos);

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        //position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'success',
                        title: 'Regsitros encontrados'
                    })
                }
                if (resultado.resultado == 0) {
                    $('#no_hay_datos').html('!No hay datos para el rango de fecha solicitado¡');
                }
            },
        });
    }
</script>


<?= $this->endSection('content') ?>