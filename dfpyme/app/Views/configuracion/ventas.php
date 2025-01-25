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
    <p class="text-center text-primary h3">INFORME DE VENTA </p>

    <div class="my-4"></div> <!-- Added space between the title and date inputs -->

    <div class="row g-3">
        <input type="hidden" id="url" value="<?php echo base_url() ?>">

        <!-- Selección de Período -->
        <div class="col-md-3">
            <label for="periodo" class="form-label fw-bold">Período</label>
            <select name="periodo" id="periodo" class="form-select" onchange="select_periodo(this.value)">
                <option value="">Seleccione...</option>
                <option value="1">Desde el inicio</option>
                <option value="2">Fecha</option>
                <option value="3">Período</option>
            </select>
            <small class="text-danger" id="error_buscar"></small>
        </div>

        <form action="<?php echo base_url() ?>/caja_general/exportVentas " method="POST">
            <!-- Fecha Inicial -->
            <div class="col-md-3" id="inicial" style="display: none;">
                <label for="fecha_inicial" class="form-label fw-bold">Fecha Inicial</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="fecha_inicial" value="<?php echo date('Y-m-d'); ?>" name="fecha_inicial">
                    <button class="btn btn-outline-secondary" type="button" onclick="limpiar_campo('fecha_inicial')" title="Limpiar campo" data-bs-toggle="tooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M18 6l-12 12" />
                            <path d="M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Fecha Final -->
            <div class="col-md-3" id="final" style="display: none;">
                <label for="fecha_final" class="form-label fw-bold">Fecha Final</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="fecha_final" value="<?php echo date('Y-m-d'); ?>" name="fecha_final">
                    <button class="btn btn-outline-secondary" type="button" onclick="limpiar_campo('fecha_final')" title="Limpiar campo" data-bs-toggle="tooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M18 6l-12 12" />
                            <path d="M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>


            <!-- Botones de Acción -->
            <div class="col-md-3 d-flex align-items-end">
                <div class="d-flex justify-content-between w-100 gap-2">
                    <button type="button" class="btn btn-outline-primary w-50" onclick="buscar()" title="Buscar datos" data-bs-toggle="tooltip">
                        Buscar
                    </button>

                    <button type="submit" class="btn btn-outline-success w-50" title="Exportar a Excel" data-bs-toggle="tooltip">
                        Excel
                    </button>
        </form>
    </div>
</div>

</div>


<div class="my-3"></div> <!-- Added space between the buttons and the table -->

<table class="table table-striped table-hover" id="reporte_ventas">
    <thead class="table-dark">
        <tr>
            <td>Fecha</th>
            <td>Nit cliente </th>
            <td>Cliente</th>
            <td>Documento</th>

            <td>Tipo documento</th>
            <td>Base</td>
            <td>IVA</th>
            <td>INC</th>
            <td>Venta</th>
        </tr>
    </thead>
    <tbody id="datos_costos">

    </tbody>
</table>
<br>
<p class="text-primary h1 text-center " id="no_hay_datos"> </p>

<div id="impuestos"></div>

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
    function select_periodo(value) {

        var incial = document.getElementById('inicial');
        var final = document.getElementById('final');

        var today = new Date();
        var year = today.getFullYear();
        var month = String(today.getMonth() + 1).padStart(2, '0'); // Los meses empiezan desde 0, por lo que se suma 1
        var day = String(today.getDate()).padStart(2, '0');

        var formattedDate = year + '-' + month + '-' + day;

        var fe_inicial = formattedDate;
        var fe_final = formattedDate;

        $('#error_buscar').html('')

        if (value == 1) { // Desde el inicio 
            inicial.style.display = "none";
            final.style.display = "none";

            $('#fecha_inicial').val('')
            $('#fecha_final').val('')

        }
        if (value == 2) { // Fecha 
            inicial.style.display = "block";
            final.style.display = "none";
            $('#fecha_final').val('')
            $('#fecha_inicial').val(fe_inicial)
        }
        if (value == 3) { // Periodo 
            inicial.style.display = "block";
            final.style.display = "block";

            $('#fecha_inicial').val(fe_inicial)
            $('#fecha_final').val(fe_final)
        }
    }
</script>


<script>
    $(document).ready(function() {
        $('#reporte_ventas').DataTable({
            serverSide: true,
            processing: true,
            searching: false,
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
                url: '<?php echo base_url() ?>' + "/reportes/data_table_ventas",
                data: function(d) {
                    return $.extend({}, d, {
                        // documento: documento,
                        // fecha_inicial: fecha_inicial,
                        // fecha_final: fecha_final
                    });
                },
                dataSrc: function(json) {
                    /* $('#saldo_total').html(json.total);
                    $('#saldo_cliente').html(json.saldo);
                    $('#pagos_factura').html(json.pagos); */
                    $('#impuestos').html(json.impuestos);
                    return json.data;
                }
            },
            columnDefs: [{
                targets: [4],
                orderable: false
            }]
        });
    });
</script>

<!-- <script>
    $(function() {
        //var dateFormat = "yy/mm/dd";
        var dateFormat = "mm-dd-yy"

        var from = $("#fecha_inicial").datepicker({
            changeMonth: true,
            numberOfMonths: 1,
            changeYear: true,
            onClose: function(selectedDate) {
                to.datepicker("option", "minDate", selectedDate);
            }
        });

        var to = $("#fecha_final").datepicker({
            changeMonth: true,
            numberOfMonths: 1,
            changeYear: true,
            onClose: function(selectedDate) {
                from.datepicker("option", "maxDate", selectedDate);
            }
        });

        // Export to Excel and PDF functionality
        $('#exportExcelBtn').click(function() {
            // Add your code to export to Excel here
        });

        $('#exportPdfBtn').click(function() {
            // Add your code to export to PDF here
        });
    });
</script> -->

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
        $('#reporte_ventas').DataTable().destroy();

        if (periodo == "") {
            $('#error_buscar').html('Debe seleccionar un criterio de busqueda')
        } else if (periodo != "") {

            /*     // Validación de fechas no vacías
                if (fecha_inicial === '' || fecha_final === '') {
                    $('#error_fecha').html('Ingresa ambas fechas ')
                    return;
                } */

            /*   $.ajax({
                  url: url + "/" + "reportes/datos_reportes_ventas",
                  type: "POST",
                  data: {
                      fecha_inicial,
                      fecha_final
                  },
                  success: function(resultado) {
                      var resultado = JSON.parse(resultado);
                      if (resultado.resultado == 1) {
                          $('#datos_costos').html(resultado.datos);
                          $('#no_hay_datos').html('');
                          $('#inicial').val(resultado.fecha_inicial);
                          $('#final').val(resultado.fecha_final);
                      }
                      if (resultado.resultado == 0) {
                          $('#no_hay_datos').html('!No hay datos para el rango de fecha solicitado¡');
                      }
                  },
              }); */

            $('#reporte_ventas').DataTable({
                serverSide: true,
                processing: true,
                searching: false,
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
                    url: '<?php echo base_url() ?>' + "/reportes/reporte_de_ventas_fecha",
                    data: function(d) {
                        return $.extend({}, d, {
                            // documento: documento,
                            fecha_inicial: fecha_inicial,
                            fecha_final: fecha_final
                        });
                    },
                    dataSrc: function(json) {
                        /* $('#saldo_total').html(json.total);
                        $('#saldo_cliente').html(json.saldo);
                        $('#pagos_factura').html(json.pagos); */
                        $('#impuestos').html(json.impuestos);
                        return json.data;
                    }
                },
                columnDefs: [{
                    targets: [4],
                    orderable: false
                }]
            })
        }
    }
</script>


<?= $this->endSection('content') ?>