<?php $session = session(); ?>
<?= $this->extend('template/template') ?>
<?= $this->section('title') ?>
Reporte de costos
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<!-- Select 2 -->
<link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    /* Estilo para el botón de exportación a Excel */
    .buttons-excel {
        background-color: #4CAF50;
        /* Color de fondo */
        color: white;
        /* Color del texto */
        border: none;
        /* Quitar borde */
        padding: 10px 20px;
        /* Añadir espacio alrededor del texto */
        cursor: pointer;
        /* Cambiar el cursor al pasar por encima */
        border-radius: 5px;
        /* Añadir esquinas redondeadas */
        font-size: 16px;
        /* Tamaño de la fuente */
    }

    /* Estilo para el botón de exportación a Excel al pasar el ratón por encima */
    .buttons-excel:hover {
        background-color: #45a049;
        /* Cambiar color de fondo al pasar el ratón por encima */
    }
</style>
<!-- Jquery date picker  -->
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/Assets/plugin/calendario/jquery-ui-1.12.1.custom/jquery-ui.css">
<!-- Data tables -->
<link href="<?= base_url() ?>/Assets/plugin/data_tables/bootstrap.min.css" />
<link href="<?= base_url() ?>/Assets/plugin/data_tables/dataTables.bootstrap5.min.css" />
<!-- Select 2 -->
<link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="container">
    <p class="text-center text-primary h3">INFORME COSTO DE VENTA </p>

    <div class="my-4"></div> <!-- Added space between the title and date inputs -->
    <!-- Agregar una barra de progreso -->


    <input type="hidden" id="url" value="<?php echo base_url() ?>">
    <form action="<?php echo base_url() ?>/caja_general/exportCostoExcel" method="POST">
        <div class="row">
            <div class="col-2">
                <label for="" class="form-label">Fecha inicial </label>
                <input type="texr" class="form-control" value="<?php echo date('Y-m-d') ?>" id="fecha_inicial" name="fecha_inicial">
            </div>
            <div class="col-2">
                <label for="" class="form-label">Fecha final </label>
                <input type="text" class="form-control" value="<?php echo date('Y-m-d') ?>" id="fecha_final" name="fecha_final">
            </div>
            <div class="col-2">
                <label for="" class="form-label text-light">Fech </label>
                <a href="#" class="btn btn-outline-success w-100">Buscar</a>
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

    <table class="table table-striped table-hover" id="consulta_costo">
        <thead class="table-dark">
            <tr>
                <td>Fecha</th>
                <td>Nit cliente</th>
                <td>Cliente</th>
                <td>Documento</th>
                
                <td>Tipo documento</th>
                <td>Costo</th>
                <td>Base </th>
                <td>IVA</th>
                <td>INC</th>
                <td>Valor</th>

            </tr>
        </thead>
        <tbody id="datos_costos">

        </tbody>
    </table>




    <br>


</div>


</div>
<!-- jQuery -->
<script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
<!-- jQuery UI -->
<script src="<?= base_url() ?>/Assets/plugin/jquery-ui/jquery-ui.js"></script>
<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>

<!-- Data tables -->
<script src="<?= base_url() ?>/Assets/plugin/data_tables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/Assets/plugin/data_tables/dataTables.bootstrap5.min.js"></script>

<!--select2 -->
<script src="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.js"></script>



<script>
    function select_periodo(periodo) {

        var inicial = document.getElementById("inicial");
        var final = document.getElementById("final");

        if (periodo == 1) {
            inicial.style.display = "none";
            final.style.display = "none";

            document.getElementById('fecha_inicial').value = '';
            document.getElementById('fecha_final').value = '';

        }
        if (periodo == 2) {
            inicial.style.display = "block";
            final.style.display = "none";
            document.getElementById('fecha_final').value = '';
        }
        if (periodo == 3) {
            inicial.style.display = "block";
            final.style.display = "block";

            document.getElementById('fecha_inicial').value = '';
            document.getElementById('fecha_final').value = '';
        }

    }
</script>


<script>
    $(document).ready(function() {
        var dataTable = $('#consulta_costo').DataTable({
            serverSide: true,
            processing: true,
            searching: false,
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5' // Agregar el botón de exportar a Excel
            ],
            order: [
                [0, 'desc']
            ],
            language: {
                decimal: "",
                emptyTable: "No hay datos",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                infoFiltered: "(Filtro de _MAX_ total registros)",
                infoPostFix: "",
                thousands: ",",
                lengthMenu: "Mostrar _MENU_ registros",
                loadingRecords: "Cargando...",
                processing: "Procesando...",
                search: "Buscar",
                zeroRecords: "No se encontraron coincidencias",
                paginate: {
                    first: "Primero",
                    last: "Ultimo",
                    next: "Próximo",
                    previous: "Anterior"
                },
                aria: {
                    sortAscending: ": Activar orden de columna ascendente",
                    sortDescending: ": Activar orden de columna desendente"
                }
            },
            ajax: {
                url: '<?php echo base_url() ?>' + "/reportes/data_table_reporte_costo",
                data: function(d) {
                    d.fecha_inicial = $('#fecha_inicial').val();
                    d.fecha_final = $('#fecha_final').val();
                },
                dataSrc: function(json) {
                    $('#valor_venta').html(json.total_venta);
                    return json.data;
                },
            },
            columnDefs: [{
                targets: [4],
                orderable: false
            }]
        });

        // Recargar la tabla cuando se cambien las fechas
        $('#fecha_inicial, #fecha_final').on('change', function() {
            dataTable.ajax.reload();
        });
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

        if ($.fn.DataTable.isDataTable('#consulta_costo')) {
            $('#consulta_costo').DataTable().destroy();
        }

        var url = document.getElementById("url").value;
        var fecha_inicial = document.getElementById("fecha_inicial").value;
        var fecha_final = document.getElementById("fecha_final").value;

        // Validación de fechas no vacías
        /*  if (fecha_inicial === '' || fecha_final === '') {
             $('#error_fecha').html('Ingresa ambas fechas ')
             return;
         } */

        var dataTable = $('#consulta_costo').DataTable({
            serverSide: true,
            processing: true,
            searching: false,
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5' // Agregar el botón de exportar a Excel
            ],
            order: [
                [0, 'desc']
            ],
            language: {
                decimal: "",
                emptyTable: "No hay datos",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                infoFiltered: "(Filtro de _MAX_ total registros)",
                infoPostFix: "",
                thousands: ",",
                lengthMenu: "Mostrar _MENU_ registros",
                loadingRecords: "Cargando...",
                processing: "Procesando...",
                search: "Buscar",
                zeroRecords: "No se encontraron coincidencias",
                paginate: {
                    first: "Primero",
                    last: "Ultimo",
                    next: "Próximo",
                    previous: "Anterior"
                },
                aria: {
                    sortAscending: ": Activar orden de columna ascendente",
                    sortDescending: ": Activar orden de columna desendente"
                }
            },
            ajax: {
                url: '<?php echo base_url() ?>' + "/reportes/datos_reporte_costo",
                data: function(d) {
                    return $.extend({}, d, {
                        // documento: documento,
                        fecha_inicial: fecha_inicial,
                        fecha_final: fecha_final
                    });
                },
                dataSrc: function(json) {
                    $('#base_iva_19').html(json.base_iva_19);
                    $('#iva_19').html(json.iva_19);
                    $('#base_iva_5').html(json.base_iva_5);
                    $('#iva_5').html(json.iva_5);
                    $('#valor_venta').html(json.total_venta);
                    $('#base_inc').html(json.base_inc);
                    $('#inc').html(json.inc);
                    $('#fecha_inicial_reporte').html(json.fecha_inicial);
                    $('#fecha_final_reporte').html(json.fecha_final);
                    $('#costo').html(json.costo);
                    return json.data;
                },
            },
            columnDefs: [{
                targets: [4],
                orderable: false
            }]
        });



    }
</script>


<?= $this->endSection('content') ?>