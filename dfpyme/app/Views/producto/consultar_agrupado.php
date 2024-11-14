<?= $this->extend('template/producto') ?>
<?= $this->section('title') ?>
PRODUCTO
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<style>
    /* Estilos personalizados para el datepicker */
    .ui-datepicker {
        font-family: Arial, sans-serif;
        font-size: 14px;
    }

    .ui-datepicker-header {
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .ui-datepicker-title {
        color: #333;
    }

    .ui-datepicker-prev,
    .ui-datepicker-next {
        background-color: #ccc;
        border: 1px solid #aaa;
        border-radius: 3px;
        color: #333;
        font-weight: bold;
    }

    .ui-datepicker-prev:hover,
    .ui-datepicker-next:hover {
        background-color: #bbb;
    }

    .ui-datepicker-calendar {
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .ui-datepicker-calendar th {
        background-color: #f0f0f0;
        color: #333;
    }

    .ui-datepicker-calendar td {
        padding: 5px;
    }

    .ui-datepicker-calendar .ui-state-default {
        background-color: #fff;
        color: #333;
    }

    .ui-datepicker-calendar .ui-state-default:hover {
        background-color: #f0f0f0;
    }

    .ui-datepicker-calendar .ui-state-highlight {
        background-color: #ffd700;
        color: #333;
    }

    .ui-datepicker-calendar .ui-state-highlight:hover {
        background-color: #ffec8b;
    }
</style>

<style>
    #datos {
        overflow-y: auto;
        /* Agrega una barra de desplazamiento vertical si el contenido excede el alto del div */
        max-height: 340px;
        /* Define una altura máxima para el div */
        height: auto;
        /* Permitir que el div se ajuste automáticamente a la altura máxima especificada */
    }

    /* Estilos adicionales para hacer que los datos sean responsivos */
    #datos table {
        width: 100%;
        border-collapse: collapse;
    }

    #datos th,
    #datos td {
        border: none;
        /* Elimina los bordes de las celdas */
        text-align: left;
        padding: 8px;
    }

    #datos th {
        background-color: #f2f2f2;
    }
</style>



<div class=" container col-md-12">

    <div class="container">
        <div class="row text-center align-items-center flex-row-reverse">
            <div class="col-lg-auto ms-lg-auto">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Facturación</a></li>
                        <li class="breadcrumb-item"><a href="#">Facturación</a></li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-auto ms-lg-auto">
                <p class="text-primary h3">REPORTE DE VENTAS</p>
            </div>
            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
            </div>
        </div>
    </div>

    <!-- End Breadcum-->




    <div class="card">
        <div class="card-body">

            <div class="row">


                <input type="hidden" value="<?php echo base_url(); ?>" id="url">

                <div class="col">
                    <label for="inputEmail4" class="form-label">Desde </label>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="fecha_inicial" value="<?php echo date('Y-m-d'); ?>">
                        <span class="input-group-text">
                            <!-- Download SVG icon from http://tabler-icons.io/i/calendar -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <rect x="4" y="5" width="16" height="16" rx="2" />
                                <line x1="16" y1="3" x2="16" y2="7" />
                                <line x1="8" y1="3" x2="8" y2="7" />
                                <line x1="4" y1="11" x2="20" y2="11" />
                                <line x1="11" y1="15" x2="12" y2="15" />
                                <line x1="12" y1="15" x2="12" y2="18" />
                            </svg>
                        </span>
                    </div>
                    <span id="error_fecha_inicial"></span>
                </div>


                <div class="col">
                    <label for="inputAddress" class="form-label">hasta </label>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="fecha_final" value="<?php echo date('Y-m-d'); ?>">
                        <span class="input-group-text">
                            <!-- Download SVG icon from http://tabler-icons.io/i/calendar -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <rect x="4" y="5" width="16" height="16" rx="2" />
                                <line x1="16" y1="3" x2="16" y2="7" />
                                <line x1="8" y1="3" x2="8" y2="7" />
                                <line x1="4" y1="11" x2="20" y2="11" />
                                <line x1="11" y1="15" x2="12" y2="15" />
                                <line x1="12" y1="15" x2="12" y2="18" />
                            </svg>
                        </span>
                    </div>
                    <span id="error_fecha_final"></span>
                </div>



                <div class="col">
                    <label for="" class="form-label"></label><br>
                    <button type="button" id="buscar_producto_agrupado" onclick="reporte_venta()" class="btn btn-outline-primary">Buscar</button>
                </div>

                <div class="col text-end" style="display: flex; justify-content: flex-end; align-items: end;">
                    <form action="<?= base_url('inventario/export_pdf') ?>" method="post">
                        <button type="submit" class="btn btn-outline-danger btn-icon">Pdf</button>
                    </form>
                    &nbsp;&nbsp;
                   <!--  <form action="<?= base_url('caja/exportar_a_excel_reporte_categorias') ?>" method="POST">
                        <button type="submit" class="btn btn-outline-success btn-icon">Excel</button>
                    </form> -->
                </div>


            </div>
        </div>

        <div id="buscando_datos" style="display:none">
            <p class="text-center">Buscando...</p>
            <div class="progress">
                <div class="progress-bar progress-bar-indeterminate bg-green"></div>
            </div>
        </div>

        <div id="datos"></div>
    </div>

    <!-- Modal 
    <div class="modal fade" id="buscando_datos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Buscando datos </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <div class="progress">
                            <div class="progress-bar progress-bar-indeterminate bg-green"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>-->


    <script>
    function reporte_venta() {
        var url = document.getElementById("url").value;

        // Mostrar el modal o div de búsqueda de datos
        document.getElementById('buscando_datos').style.display = 'block';

        // Definir las variables de fecha (asegúrate de tener los elementos con estos IDs en el HTML)
        var fecha_inicial = document.getElementById("fecha_inicial").value;
        var fecha_final = document.getElementById("fecha_final").value;

        $.ajax({
            data: {
                fecha_inicial: fecha_inicial,
                fecha_final: fecha_final
            },
            url: url + "/inventario/reporte_ventas",
            type: "POST",
            success: function(resultado) {
                var parsedResult = JSON.parse(resultado);

                if (parsedResult.resultado === 0) {
                    $('#datos').html(parsedResult.datos);
                    document.getElementById('buscando_datos').style.display = 'none';
                } else if (parsedResult.resultado === 2) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });

                    Toast.fire({
                        icon: 'error',
                        title: 'No hay datos para la consulta'
                    });

                    document.getElementById('buscando_datos').style.display = 'none';
                }
            },
            error: function() {
                document.getElementById('buscando_datos').style.display = 'none';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema con la solicitud.'
                });
            }
        });
    }
</script>


    <script>
        function rango(opcion) {


            if (opcion == 1) {
                var inicial = document.getElementById("inicial");
                inicial.style.display = "none";

                var final = document.getElementById("final");
                final.style.display = "none";

            }
            if (opcion == 2) {
                var inicial = document.getElementById("inicial");
                inicial.style.display = "block";

                var final = document.getElementById("final");
                final.style.display = "none";

            }
            if (opcion == 3) {
                var final = document.getElementById("final");
                final.style.display = "block";

                var inicial = document.getElementById("inicial");
                inicial.style.display = "block";

            }

        }
    </script>

    <?= $this->endSection('content') ?>