<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
COMPRAS-ENTRADA
<?= $this->endSection('title') ?>
<style>
    .centered-toast {
        left: 50% !important;
        /* Centra horizontalmente */
        transform: translateX(-50%);
        /* Ajusta el centro */
    }
</style>
<?= $this->section('content') ?>

<style>
    .card-body-scrollable {
        max-height: 65vh;
        /* Ajuste automático según la pantalla */
        overflow-y: auto;
        /* Scroll vertical si es necesario */
        padding: 10px;
        /* Espaciado interno */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Sombra opcional */
        border-radius: 5px;
        /* Bordes redondeados opcionales */
    }
</style>

<div class="page-wrapper">
    <!-- Page body -->

    <div class="container-fluid">
        <div class="row row-deck row-cards">

            <?php $alturaCalc = "34rem + 10px"; // Calcula la altura 
            ?>

            <input type="hidden" value="<?php echo base_url() ?>" id="url">
            <input type="hidden" value="<?php echo $user_session->id_usuario ?>" id="id_usuario">
            <div class="col-12 col-sm-12 col-md-8 col-xl-8" id="productos" style="display: block">

                <div class="card" style="height: calc(<?php echo $alturaCalc; ?>)">
                    <div class="card-body ">

                        <!--  <div class="row">
                            <div class="col-12">
                                <p class="text-center text-primary h3">REGISTRO DE COMPRAS </p>
                            </div>
                            <div class="col-1">Fecha:</div>
                            <div class="col-3"><?php
                                                // Establece el idioma y el país para la localización en español
                                                $locale = 'es_ES';

                                                // Crea un objeto IntlDateFormatter
                                                $dateFormatter = new IntlDateFormatter($locale, IntlDateFormatter::FULL, IntlDateFormatter::NONE);

                                                // Configura el formato de la fecha
                                                $dateFormatter->setPattern('EEEE, d MMMM yyyy');

                                                // Crea un objeto DateTime con la fecha actual
                                                $fecha = new DateTime();

                                                // Muestra la fecha formateada
                                                echo $dateFormatter->format($fecha); // Ejemplo: lunes, 16 de octubre de 2024
                                                ?>
                            </div>
                            <div class="col-1">Usuario:</div>
                            <div class="col-3"><?php echo $user_session->usuario; ?></div>

                        </div> 
                        <hr>-->
                        <div class="row mb-0">

                            <div class="col-4">

                                <label class="form-label text-dark">Producto</label>
                                <div class="input-group mb-3">
                                    <input type="text" value="" class="form-control " id="producto_compra" placeholder="Buscar producto" autofocus>
                                    <button class="btn btn-icon btn-outline-secondary d-flex align-items-center justify-content-center" onclick="limpiar_input()" title="Limpiar" data-bs-toggle="tooltip" data-bs-placement="bottom" type="button" id="clearButton">
                                        <!-- Ícono SVG para el botón de borrado -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <line x1="7" y1="4" x2="17" y2="20" />
                                            <line x1="17" y1="4" x2="7" y2="20" />
                                        </svg>
                                    </button>
                                </div>

                                <span id="error_producto" class="text-red"></span>
                            </div>


                            <div class="col-1">

                                <label class="form-label">Cant </label>
                                <input type="text" class="form-control text-center " placeholder="Cantidad a ingresar" value="1" onkeyup="saltar_factura_pos(event,'precio');sumarValores()" id="cantidad">

                                <span class="text-danger" id="error_cantidad"> </span>
                            </div>


                            <div class="col-3">

                                <label class="form-label">Precio de compra</label>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                            <path d="M12 3v3m0 12v3" />
                                        </svg>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Precio de compra" id="precio" onkeyup="pre_procesar(event)">
                                </div>

                                <span class="text-danger" id="error_precio"> </span>
                            </div>

                            <div class="col-4">

                                <div class="input-group">
                                    <div class="row">

                                        <div class="col"><label for="" class="form-label">Inventario actual</label><input type="text" class="form-control" id="actual" readonly></div>
                                        <div class="col"><label for="" class="form-label">Nuevo inventario</label><input type="text" class="form-control" id="nuevo" readonly></div>

                                    </div>
                                </div>
                                <span class="text-danger"> </span>
                            </div>

                        </div>
                        <div class="row mb-1">


                            <div class="col-4">
                                <!-- <label class="form-label text-light">Producto</label> -->
                                <input type="text" id="display" class="form-control" placeholder="Producto seleccionado" readonly>
                                <span id="error_display" class="text-danger"></span>
                                <input type="hidden" id="id_producto">
                            </div>


                            <div class="col-1">
                                <!--  <label for="" class="form-label text-light"> 
                                    Agregar
                                </label>-->
                                <button href="#" class="btn btn-outline-success d-flex justify-content-center align-items-center" id="btnCompra" onclick="entrada_inventario()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <line x1="12" y1="5" x2="12" y2="19" />
                                        <line x1="5" y1="12" x2="19" y2="12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="row mb-0">

                        </div>


                        <div class="card-body-scrollable card-body-scrollable-shadow mb-1">
                            <div id="productos_compra">


                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-muted">
                        <div class="mb-2">
                            <textarea class="form-control" id="nota" rows="1" style="width: 100%;" placeholder="Nota de la entrada "></textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="#" class="btn btn-outline-danger me-2" onclick="borrar_compra()">Borrar compra </a>
                            <a href="#" class="btn btn-outline-success" onclick="impresion_compra()">Imprimir compra </a>
                        </div>
                    </div>

                </div>
            </div>

            <!--valor Pedido-->
            <div class="col-12 d-sm-none d-md-block col-md-4 col-xl-4 d-none ">
                <div class="card " style="height: calc(34rem + 10px)">
                    <div class="card-header border-1" style="margin-bottom: -10px; padding-bottom: 0;">
                        <div class="card-title">
                            <div class="row align-items-start">
                                <div class="col">
                                    <p>RESUMEN </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body ">
                        <form class="mb-3">

                            <div class="row mb-3">
                                <!-- <div class="col-4"><label class="form-label">Proveedor</label></div> -->
                                <div class="col-12">
                                    <?php
                                    $proveedor = model('proveedorModel')
                                        ->select('codigointernoproveedor, nitproveedor, nombrecomercialproveedor')
                                        ->findAll();
                                    ?>

                                    <!--    <select name="select_proveedor" id="select_proveedor" class="form-select" onchange="borrar_error()">
                                        <option value=""></option>
                                        <?php foreach ($proveedor as $detalle): ?>
                                            <option value="<?php echo $detalle['codigointernoproveedor'] ?>"><?php echo $detalle['nombrecomercialproveedor'] ?></option>
                                        <?php endforeach ?>
                                    </select> -->
                                    <input type="text" class="form-control" placeholder="Buscar proveedor por NIT  o nombre " id="buscar_proveedor">
                                    <span class="text-danger" id="error_proveedor"></span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <input type="text" class="form-control" id="input_proveedor" placeholder="Proveedor" readonly>
                                    <input type="hidden" class="form-control" id="codigo_proveedor" placeholder="Proveedor">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label for="" class="form-label h3">Fecha factura: </label>
                                </div>
                                <div class="col-sm-6">
                                    <div id="fecha_factura1">
                                        <input type="date" class="form-control" id="fecha_factura" value="<?php echo date('Y-m-d') ?>">
                                    </div>
                                    <span id="error_fecha_factura" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label for="" class="form-label h3">Factura número: </label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="numero_factura">
                                    <span id="error_numero_factura" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label for="" class="form-label h3">Total items: </label>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-orange h3" id="numero_articulos"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label for="" class="form-label h3">Valor factura: </label>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-orange h3" id="valor_factura"></span>
                                </div>
                            </div>


                            <hr>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <a href="#" class="btn btn-outline-azure w-100 h2" id="val_pedido" onclick="procesar()" title="Procesar compra " data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                        Finalizar entrada
                                    </a>
                                </div>
                                <div id="procesar_entrada" style="display:none">
                                    <label for="" class="form-label">Procesando compra</label>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-indeterminate bg-green"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Detalle de la compra "></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-outline-success">Aceptar </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_finalizar_compra" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Compra finalizada </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="proveedor_compra"></p>
                <p id="total_compra"></p>
            </div>
            <input type="hidden" id="id_compra">
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" onclick="imprimir_compra()" data-bs-dismiss="modal">Imprimir</button>
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Nueva compra </button>
            </div>
        </div>
    </div>
</div>


<!-- J QUERY -->
<script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>

<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/saltar_factura_pos.js"></script>


<script>
    $(document).ready(function() {
        // Agrega el evento click para seleccionar el contenido del campo
        $('#cantidad').on('click', function() {
            $(this).select(); // Selecciona el contenido del campo
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Agrega el evento click para seleccionar el contenido del campo
        $('#precio').on('click', function() {
            $(this).select(); // Selecciona el contenido del campo
        });
    });
</script>

<script>
    function pre_procesar() {
        if (event.key === "Enter") {
            // Evita que el formulario se envíe si es el caso
            event.preventDefault();
            // Llama a la función que deseas ejecutar
            entrada_inventario()
            // Aquí puedes llamar a la función que necesites
            // miFuncion(); por ejemplo
        }
    }
</script>

<script>
    document.getElementById('producto_compra').addEventListener('keydown', function(event) {
        // Detectar si se presiona la tecla Backspace
        if (event.key === 'Backspace' || event.keyCode === 8) {
            $('#error_producto').html('')
        }
    });

    /*     document.getElementById('precio').addEventListener('keydown', function(event) {
            // Detectar si se presiona la tecla Backspace
            if (event.key === 'Enter' || event.keyCode === 13) {
                procesar()
            }
        }); */
</script>

<script>
    function imprimir_compra() {

        var url = document.getElementById("url").value;
        var id = document.getElementById("id_compra").value;

        $.ajax({
            data: {
                id
            },
            url: url + "/comanda/imprimir_compra",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {



                    sweet_alert_centrado('success', 'Impresión de compra éxitosa')


                }
            },
        });

    }
</script>

<script>
    function impresion_compra() {

        var url = document.getElementById("url").value;
        var id_usuario = document.getElementById("id_usuario").value;

        $.ajax({
            data: {
                id_usuario: id_usuario // Corregido: ahora asignamos el valor
            },
            url: url + "/comanda/impresion_compra",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {
                    sweet_alert_centrado('success', 'Impresión de compra exitosa');
                }
            },
        });

    }
</script>


<script>
    function borrado_general() {
        $('#producto_compra').val('')
        $('#cantidad').val(1)
        $('#precio').val('')
        $('#actual').val('')
        $('#nuevo').val('')
        $('#display').val('')
        $('#id_producto').val('')
        $('#productos_compra').html('')
        $('#numero_articulos').html('')
        $('#valor_factura').html('')
        $('#error_precio').html('')
        $('#error_cantidad').html('')
        $('#error_proveedor').html('')
        $('#error_numero_factura').html('')
        $('#nota').val('')
        $('#buscar_proveedor').val('')
        $('#input_proveedor').val('')
        $('#codigo_proveedor').val('')
        $('#numero_factura').val('')
    }
</script>

<script>
    function borrar_compra() {

        var url = document.getElementById("url").value;
        var id_usuario = document.getElementById("id_usuario").value;

        $.ajax({
            data: {
                id_usuario
            },
            url: url + "/inventario/borrar_compra",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {


                    borrado_general()
                    sweet_alert_centrado('success', 'Compra borrada')


                }
            },
        });

    }
</script>



<script>
    function borrar_error() {
        document.getElementById('error_proveedor').innerHTML = '';
    }
</script>


<script>
    function procesar() {



        var url = document.getElementById("url").value;
        var id_usuario = document.getElementById("id_usuario").value;
        var nota = document.getElementById("nota").value;
        var proveedor = document.getElementById("codigo_proveedor").value;
        var numero_factura = document.getElementById("numero_factura").value;
        var fecha_factura = document.getElementById("fecha_factura").value;

        if (proveedor == "") {
            $('#error_proveedor').html('Falta el proveedor');
            return false; // Detiene la ejecución correctamente
        }

        if (numero_factura == "") {
            $('#error_numero_factura').html('Falta el número de la factura ');
            return false; // Detiene la ejecución correctamente
        }

        var div = document.getElementById("procesar_entrada");
        div.style.display = "block";

        $.ajax({
            data: {

                id_usuario,
                nota,
                proveedor,
                fecha_factura
            },
            url: url + "/inventario/procesar_compra",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {


                    borrado_general()


                    /* document.getElementById('select_proveedor').selectedIndex = 0;
                    var div = document.getElementById("procesar_entrada"); */

                    $('#fecha_factura1').html(resultado.select)

                    var div = document.getElementById("procesar_entrada");
                    div.style.display = "none";

                    //sweet_alert_centrado('success', 'Compra ingresada')
                    $("#id_compra").val(resultado.id_compra)
                    $("#total_compra").html(resultado.total_compra)
                    $("#modal_finalizar_compra").modal("show");


                }
                if (resultado.resultado == 0) {
                    $('#select_proveedor').html(resultado.select)
                    var div = document.getElementById("procesar_entrada");
                    div.style.display = "none";
                    sweet_alert_centrado('error', 'No hay productos para procesar ')
                }
            },
        });
    }
</script>

<script>
    function actualizacion_cantidades(cantidad, id) {

        var url = document.getElementById("url").value;
        var id_usuario = document.getElementById("id_usuario").value;

        $.ajax({
            data: {
                id,
                cantidad,
                id_usuario
            },
            url: url + "/inventario/actualizacion_cantidades_compra",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {


                    $('#total_producto' + resultado.id).html(resultado.total_producto)
                    $('#valor_factura').html(resultado.total)
                    $('#numero_articulos').html(resultado.numero_articulos)


                }
            },
        });

    }
</script>
<script>
    function eliminar_producto(event, id) {

        var url = document.getElementById("url").value;
        var id_usuario = document.getElementById("id_usuario").value;

        $.ajax({
            data: {
                id,
                id_usuario
            },
            url: url + "/inventario/eliminar_producto_compra",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    var elemento = document.getElementById('producto_compra' + resultado.id);
                    if (elemento) {
                        $('#numero_articulos').html(resultado.total_articulos)
                        $('#valor_factura').html(resultado.total)
                        elemento.remove(); // Elimina el div del DOM

                        var divAEliminar = document.getElementById('linea' + resultado.id); // Reemplaza con el ID real del div que deseas eliminar
                        if (divAEliminar) {
                            divAEliminar.remove(); // Elimina el div especificado
                        }

                        sweet_alert_centrado('success', 'Producto eliminado de la entrada de compras ')
                    }

                }
            },
        });

    }
</script>


<script>
    function productos_entrada() {
        var url = document.getElementById("url").value;
        var id_usuario = document.getElementById("id_usuario").value;

        $.ajax({
            data: {
                id_usuario
            },
            url: url + "/inventario/usuario_producto_compra",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {
                    // Aquí puedes agregar lógica adicional si es necesario

                    $('#productos_compra').html(resultado.productos)
                    $('#numero_articulos').html(resultado.numero_articulos)
                    $('#valor_factura').html(resultado.total)
                    sweet_alert_centrado('info', 'El usuario ya habia tenia entrada iniciada ')

                }
            },
        });
    }

    // Ejecutar la función cuando el DOM esté completamente cargado
    document.addEventListener("DOMContentLoaded", function() {
        productos_entrada();
    });
</script>



<script>
    function despues_de_resultado(productos, total_articulos, total) {
        $('#productos_compra').html(productos)
        $('#numero_articulos').html(total_articulos)
        $('#valor_factura').html(total)

    }
</script>
<script>
    function limpiar_input() {
        $('#producto_compra').val('');
        $('#display').val('');
        $('#error_producto').html('');
    }
</script>



<script>
    function entrada_inventario() {
        // Obtén los valores de los campos
        var url = '<?php echo base_url(); ?>';
        var cantidad = document.getElementById("cantidad").value;
        var precio = document.getElementById("precio").value;
        var codigo = document.getElementById("id_producto").value;
        var id_usuario = document.getElementById("id_usuario").value;

        // Validación: Verifica si los campos están vacíos
        if (cantidad === "" && precio === "" && codigo === "") {
            alert("Todos los campos son obligatorios. Por favor, complete la cantidad, precio y seleccione un producto.");
            return; // Detiene la ejecución si algún campo está vacío
        }

        // Validación: Verifica si 'cantidad' y 'precio' son números válidos
        if (cantidad == "") {
            $('#error_cantidad').html('Falta la cantidad')
            return; // Detiene la ejecución si los valores no son numéricos
        }
        if (precio == "") {
            $('#error_precio').html('Falta el precio')
            return; // Detiene la ejecución si los valores no son numéricos
        }

        if (precio == "" && cantidad == "") {
            if (precio == "") {
                $('#error_precio').html('Falta el precio');
            }
            if (cantidad == "") {
                $('#error_cantidad').html('Falta cantidad');
            }
            return; // Detiene la ejecución si algún campo está vacío
        }


        // Validación específica para el campo 'id_producto'
        if (codigo === "") {
            $('#error_display').html('Debe seleccionar un producto ')
            return; // Detiene la ejecución si no se ha seleccionado un producto
        }

        // Si todo está correcto, procede con la solicitud AJAX
        $.ajax({
            data: {
                cantidad,
                precio,
                codigo,
                id_usuario
            },
            url: url + "/inventario/ingresar_entrada",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {


                    /* $('#productos_compra').html(resultado.productos)
                    $('#numero_articulos').html(resultado.total_articulos)
                    $('#valor_factura').html(resultado.total) */
                    despues_de_resultado(resultado.productos, resultado.total_articulos, resultado.total)
                    $('#producto_compra').val('')
                    $('#cantidad').val(1)
                    $('#precio').val('')
                    $('#display').val('')
                    $('#id_producto').val('')
                    $('#actual').val('')
                    $('#nuevo').val('')
                    $('#producto_compra').focus()
                    $('#error_precio').html('')
                    $('#error_cantidad').html('')


                }
            },
        });
    }
</script>

<script>
    const precio = document.querySelector("#precio");

    function formatNumber(n) {
        // Elimina cualquier carácter que no sea un número
        n = n.replace(/\D/g, "");
        // Formatea el número
        return n === "" ? n : parseFloat(n).toLocaleString('es-CO');
    }

    precio.addEventListener("input", (e) => {
        const element = e.target;
        const value = element.value;
        element.value = formatNumber(value);
    });
</script>

<script>
    function sumarValores() {
        var actual = parseFloat($("#actual").val()) || 0; // Obtiene el valor de 'actual'
        var cantidad = parseFloat($("#cantidad").val()) || 0; // Obtiene el valor de 'cantidad'

        var total = actual + cantidad; // Suma ambos valores
        $("#nuevo").val(total); // Asigna el total al campo 'total'
    }
</script>

<script>
    $(document).ready(function() {
        var url = document.getElementById("url").value;
        $("#buscar_proveedor").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",

                    url: url + "/" + "inventario/proveedor",
                    data: {
                        term: request.term, // Término de búsqueda 
                    },
                    dataType: "json",
                    success: function(data) {
                        response(data); // Pasar los datos devueltos por Ajax a la función response
                    }
                });
            },
            minLength: 1, // Número mínimo de caracteres para iniciar la búsqueda
            select: function(event, ui) {
                // Maneja la selección de un elemento de la lista


                $("#input_proveedor").val(ui.item.nombre_proveedor); // Asigna el nombre del producto
                $("#codigo_proveedor").val(ui.item.codigo); // Asigna el nombre del producto
                $("#buscar_proveedor").val(''); // Asigna el nombre del producto



            }
        });
    });
</script>

<!-- <script>
    $(document).ready(function() {
        var url = document.getElementById("url").value;

        $("#producto_compra").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: url + "/inventario/producto_entrada",
                    data: {
                        term: request.term, // Término de búsqueda 
                    },
                    dataType: "json",
                    success: function(data) {
                        response(data); // Pasar los datos devueltos por Ajax a la función response
                    }
                });
            },
            minLength: 1, // Número mínimo de caracteres para iniciar la búsqueda
            select: function(event, ui) {
                // Maneja la selección de un elemento de la lista
                if (ui.item.id_inventario == 3) {
                    $("#display").val(ui.item.value); // Asigna el nombre del producto
                    $('#producto_compra').val(''); // Limpiar
                    $("#id_producto").val(ui.item.codigo); // Asigna el código del producto 
                    $("#actual").val(ui.item.cantidad); // Asigna la cantidad del producto
                    $("#cantidad").focus();
                    $('#error_producto').html('');

                    // Sumar los valores de 'actual' y 'cantidad'
                    var actual = parseFloat($("#actual").val()) || 0; // Obtiene el valor del campo actual y lo convierte a número
                    var cantidad = parseFloat($("#cantidad").val()) || 0; // Obtiene el valor del campo cantidad y lo convierte a número
                    var total = actual + cantidad; // Suma ambos valores

                    $("#nuevo").val(total); // Asigna el total al input con id 'nuevo'

                } else if (ui.item.id_inventario == 1) {
                    $('#error_producto').html('Este producto es una receta y no se puede ingresar por compras ');
                }
            }
        });

        // Evento keydown para manejar la tecla Enter
        $("#producto_compra").keydown(function(event) {
            if (event.key === "Enter") { // Comprueba si la tecla presionada es Enter
                event.preventDefault(); // Evita que el formulario se envíe automáticamente
                var producto = $(this).val(); // Obtiene el valor actual del input

                // Cerrar el autocomplete


                // Realiza el nuevo AJAX aquí
                /* $.ajax({
                    type: "POST",
                    url: url + "/inventario/buscar_por_codigo", // Cambia a tu endpoint deseado
                    data: {
                        producto: producto // Envía el valor del producto
                    },
                    dataType: "json",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);
                        if (resultado.resultado == 1) {
                            // Aquí puedes agregar lógica adicional si es necesario

                            $('#producto_compra').val(resultado.nombre_producto	)
                           

                        }
                    },
                    error: function(xhr, status, error) {
                        // Manejo de errores
                        console.error("Error en la solicitud AJAX:", error);
                    }
                }); */


                $.ajax({
                    type: "POST",
                    url: url + "/inventario/buscar_por_codigo", // URL del endpoint
                    data: {
                        producto: producto // Dato enviado en la solicitud AJAX
                    },
                    dataType: "json", // Asegúrate de que el tipo de datos sea JSON
                    success: function(resultado) {
                        if (resultado.resultado == 1) {
                            $("#producto_compra").autocomplete("close");
                            if (resultado.id_tipo_inventario == 3) {
                                // Asigna el nombre del producto al campo de texto
                                $('#producto_compra').val(resultado.nombre_producto);
                                $('#display').val(resultado.nombre_producto);
                                $('#id_producto').val(resultado.codigo);
                                $('#cantidad').focus()
                            }
                            if (resultado.id_tipo_inventario == 1) {
                                $('#producto_compra').val(resultado.nombre_producto);
                                $('#error_producto').html('Este producto es una receta y no se puede ingresar por compras ')

                            }
                        } else {
                            // Aquí puedes manejar otros resultados si es necesario
                            console.error('No se encontró el producto o hubo otro error');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Manejo de errores en caso de que la solicitud falle
                        console.error("Error en la solicitud AJAX:", error);
                    }
                });

            }
        });
    });
</script> -->

<script>
    $(document).ready(function() {
        var url = document.getElementById("url").value;

        $("#producto_compra").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: url + "/inventario/producto_entrada",
                    data: {
                        term: request.term, // Término de búsqueda 
                    },
                    dataType: "json",
                    success: function(data) {
                        response(data); // Pasar los datos devueltos por Ajax a la función response
                    }
                });
            },
            minLength: 1, // Número mínimo de caracteres para iniciar la búsqueda
            select: function(event, ui) {
                // Maneja la selección de un elemento de la lista
                if (ui.item.id_inventario == 1 || ui.item.id_inventario==4 ) {
                    $("#display").val(ui.item.value); // Asigna el nombre del producto
                    $('#producto_compra').val(''); // Limpiar
                    $("#id_producto").val(ui.item.codigo); // Asigna el código del producto 
                    $("#actual").val(ui.item.cantidad); // Asigna la cantidad del producto
                    $("#precio").val(ui.item.precio_costo); // Asigna la cantidad del producto
                    $("#cantidad").focus();
                    $("#cantidad").select();
                    $('#error_producto').html('');

                    // Sumar los valores de 'actual' y 'cantidad'
                    var actual = parseFloat($("#actual").val()) || 0; // Obtiene el valor del campo actual y lo convierte a número
                    var cantidad = parseFloat($("#cantidad").val()) || 0; // Obtiene el valor del campo cantidad y lo convierte a número
                    var total = actual + cantidad; // Suma ambos valores

                    $("#nuevo").val(total); // Asigna el total al input con id 'nuevo'

                } else if (ui.item.id_inventario == 3) {
                    $('#error_producto').html('Este producto es una receta y no se puede ingresar por compras ');
                }
            }
        });

        // Evento keydown para manejar la tecla Enter solo si el campo tiene valor
        $("#producto_compra").keydown(function(event) {
            if (event.key === "Enter") { // Comprueba si la tecla presionada es Enter
                event.preventDefault(); // Evita que el formulario se envíe automáticamente

                var producto = $(this).val().trim(); // Obtiene el valor actual del input y lo recorta

                // Verifica si el campo tiene algún valor antes de continuar
                if (producto === "") {
                    console.log("El campo está vacío. No se ejecuta la solicitud AJAX.");
                    return; // Detiene la ejecución si el campo está vacío
                }

                // Realiza el nuevo AJAX aquí
                $.ajax({
                    type: "POST",
                    url: url + "/inventario/buscar_por_codigo", // URL del endpoint
                    data: {
                        producto: producto // Dato enviado en la solicitud AJAX
                    },
                    dataType: "json", // Asegúrate de que el tipo de datos sea JSON
                    success: function(resultado) {
                        if (resultado.resultado == 1) {
                            $("#producto_compra").autocomplete("close");
                            if (resultado.id_tipo_inventario == 3) {
                                // Asigna el nombre del producto al campo de texto
                                $('#producto_compra').val(resultado.nombre_producto);
                                $('#display').val(resultado.nombre_producto);
                                $('#id_producto').val(resultado.codigo);
                                $('#cantidad').focus()
                                $('#cantidad').select()
                            }
                            if (resultado.id_tipo_inventario == 1) {
                                $('#producto_compra').val(resultado.nombre_producto);
                                $('#error_producto').html('Este producto es una receta y no se puede ingresar por compras ')
                            }
                        } else {
                            // Aquí puedes manejar otros resultados si es necesario
                            console.error('No se encontró el producto o hubo otro error');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Manejo de errores en caso de que la solicitud falle
                        console.error("Error en la solicitud AJAX:", error);
                    }
                });

            }
        });
    });
</script>




<?= $this->endSection('content') ?>