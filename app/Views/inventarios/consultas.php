<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<style>
    #entradas_salidas {
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;
        /* Asegura que las columnas tengan un ancho uniforme */
    }

    #entradas_salidas thead {
        position: sticky;
        top: 0;
        z-index: 2;
        /* Asegura que el encabezado esté por encima de los datos */
    }

    #entradas_salidas thead th,
    #entradas_salidas thead td {
        background-color: #343a40;
        /* Color de fondo del encabezado */
        color: white;
        /* Color de texto */
    }

    #entradas_salidas tbody {
        display: block;
        max-height: 400px;
        /* Altura máxima de la tabla */
        overflow-y: auto;
        /* Habilita el scroll vertical */
    }

    #entradas_salidas thead,
    #entradas_salidas tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
        /* Mantiene el ancho de las columnas uniforme */
    }
</style>


<?= $this->section('content') ?>

<div class="container">

    <div class="card">
        <div class="card-header text-primary">
            CONSULTAS DE MOVIMIENTO DE PRODUCTO
        </div>
        <div class="card-body">

            <input type="hidden" id="url" value="<?php echo base_url() ?>">
            <input type="hidden" value="<?php echo $user_session->id_usuario ?>" id="id_usuario">

            <div class="row mb-3">

                <div class="col-3">
                    <label for="" class="form-label">Movimiento</label>
                    <select name="" class="form-select" id="concepto_busqueda">
                        <option value=""></option>
                        <option value="3">Entradas y salidas </option>
                        <option value="1">Entradas </option>
                        <option value="2">Salidas </option>
                        
                        <!-- <?php foreach ($conceptos as $detalle): ?>
                            <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['nombre'] ?></option>
                        <?php endforeach ?> -->
                    </select>
                    <span id="error_concepto_busqueda"></span>
                </div>
                <div class="col-3">
                    <label for="" class="form-label">Producto</label>
                    <input type="hidden" id="id_producto">


                    <div class="input-group input-group-flat">
                        <input type="text" class="form-control" id="producto" name="producto" autofocus>
                        <span class="input-group-text">
                            <a href="#" class="link-secondary" title="Limpiar búsqueda" data-bs-toggle="tooltip" onclick="limpiar_input()"><!-- Download SVG icon from http://tabler-icons.io/i/x -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </a>
                        </span>
                    </div>


                </div>

                <div class="col-3">
                    <div class="row">
                        <div class="col">
                            <label for="" class="form-label">Fecha inicial </label>
                            <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" id="fecha_inicial">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Fecha final </label>
                            <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" id="fecha_final">
                        </div>


                    </div>
                </div>

                <div class="col-3">
                    <label for="" class="form-label text-light">Bu</label>
                    <button type="button" class="btn btn-outline-primary" onclick="buscar_resultados()">Buscar </button>
                </div>


            </div>

            <div class="row mb-2">
                <div class="col">
                    <input type="text" class="form-control" id="prod_selec" readonly>
                </div>

            </div>

            <div class="row mb-2 " id="barra_progreso" style="display:none">
                <div class="col container">
                    <label for="" class="form-label">Buscando resultados...</label>
                    <div class="progress">
                        <div class="progress-bar progress-bar-indeterminate bg-green"></div>
                    </div>

                </div>
            </div>

            <!-- Contenedor principal -->
            <div class="d-flex justify-content-end mb-3">
                <form action="<?php echo base_url() ?>/consultas_y_reportes/excel_mov">
                    <button class="btn btn-outline-success" type="submit">EXCEL</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table" id="entradas_salidas">
                    <thead class="table-dark">

                        <td>Fecha</td>
                        <td>Hora</td>
                        <td>Movimiento</td>
                        <td>Producto</td>
                        <td>Cantidad inicial</td>
                        <td>Cantidad movimiento </td>
                        <td>Cantidad final </td>
                        <td>Documento </td>
                        <td>Usuario</td>
                        <td>Nota</td>
                    </thead>
                    <tbody id="res_producto">


                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>


<script>
    function exportar_excel() {

        let url = document.getElementById("url").value;
        $.ajax({

            url: url + "/" + "consultas_y_reportes/excel_mov",
            type: "GET",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);

            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", status, error);
            }
        })
    }
</script>



<script>
    function limpiar_input() {
        document.getElementById("producto").value = "";
        document.getElementById("id_producto").value = "";
        document.getElementById("prod_selec").value = "";
    }
</script>

<script>
    function imprimir() {
        let url = document.getElementById("url").value;
        let id_usuario = document.getElementById("id_usuario").value;

        $.ajax({
            data: {
                id_usuario
            },
            url: url + "/" + "comanda/imprimir_movimiento",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);

            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", status, error);
            }
        });
    }
</script>



<?= $this->endSection('content') ?>