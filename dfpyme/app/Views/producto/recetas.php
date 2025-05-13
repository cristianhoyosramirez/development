<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title><?= $this->renderSection('title') ?>RECETAS &nbsp;-&nbsp;DF PYME</title>
    <!-- Jquery-ui -->
    <link href="<?php echo base_url(); ?>/Assets/plugin/jquery-ui/jquery-ui.css" rel="stylesheet">

    <!-- CSS files -->
    <link href="<?= base_url() ?>/Assets/css/tabler.min.css" rel="stylesheet" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/Assets/img/favicon.png">
</head>
<?php $session = session(); ?>

<input type="hidden" value="<?php echo base_url() ?>" id="url">
<input type="hidden" value="<?php echo $modal ?>" id="modal">

<body>
    <div class="wrapper">
        <div class="page-wrapper">
            <?= $this->include('layout/header_mesas') ?>

            <div class="page-body">
                <p class="text-center text-primary h3">Configuración de componentes de producto </p>
                <div class="container mt-4">
                    <div class="card p-4 shadow">
                        <div class="row">
                            <div class="col-6">
                                <h3 class="text-start text-primary mb-3">Información de producto</h3>
                                <div class="row align-items-center">
                                    <label for="recetaSelect" class="col-3 fw-bold">Producto:</label>
                                    <div class="col-9">
                                        <div class="input-group input-group-flat">
                                            <input type="text" class="form-control" autocomplete="off" placeholder="Buscar un producto receta" id="buscar_receta" oninput="buscar_receta(this.value)">

                                            <span class="input-group-text">
                                                <a href="#" class="link-secondary" title="Limpiar búsqueda" data-bs-toggle="tooltip" data-bs-placement="bottom" onclick="limpiar(buscar_receta)"><!-- Download SVG icon from http://tabler-icons.io/i/x -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <line x1="18" y1="6" x2="6" y2="18" />
                                                        <line x1="6" y1="6" x2="18" y2="18" />
                                                    </svg>
                                                </a>
                                            </span>
                                        </div>

                                    </div>
                                </div>
                                <span id="resList"></span>
                                <div class="mb-3"></div>
                                <div class="row">
                                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                        <table class="table table-striped">
                                            <thead class="table-dark">
                                                <tr>
                                                    <td scope="col">Código</th>
                                                    <td scope="col">Producto</th>
                                                    <td scope="col">Costo</th>
                                                    <td scope="col">Venta</th>
                                                    <td scope="col">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbodyRecetas">

                                                <?php foreach ($recetas as $detalleRecetas): ?>

                                                    <tr id="rowInsumo<?php echo $detalleRecetas['codigointernoproducto']; ?>" onclick="detalleReceta(<?php echo $detalleRecetas['codigointernoproducto']; ?>)" style="cursor: pointer;">
                                                        <td><?php echo $detalleRecetas['codigointernoproducto']; ?></td>
                                                        <td><?php echo $detalleRecetas['nombreproducto']; ?></td>
                                                        <td><?php echo $detalleRecetas['precio_costo']; ?></td>
                                                        <td><?php echo $detalleRecetas['valorventaproducto']; ?></td>
                                                        <td>
                                                            <button class="btn btn-outline-primary btn-sm" onclick="detalleReceta(<?php echo $detalleRecetas['codigointernoproducto']; ?>)">Ver</button>
                                                        </td>

                                                    </tr>

                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="mb-3"></div>
                                <div class="row">

                                    <label for="recetaSelect" class="col-3 fw-bold">Insumos:</label>

                                    <div class="col-9">
                                        <div class="input-group input-group-flat">
                                            <input type="text" id="productoInsumo" class="form-control" autocomplete="off" placeholder="Buscar un producto insumo" oninput="buscarInsumos(this.value)">
                                            <span class="input-group-text">
                                                <a href="#" class="link-secondary" title="Limpiar búsqueda" data-bs-toggle="tooltip" data-bs-placement="bottom" onclick="limpiarBusqueda() "><!-- Download SVG icon from http://tabler-icons.io/i/x -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <line x1="18" y1="6" x2="6" y2="18" />
                                                        <line x1="6" y1="6" x2="18" y2="18" />
                                                    </svg>
                                                </a>
                                            </span>
                                        </div>
                                        <span id="resListInsumos"></span>
                                    </div>
                                    <div class="mb-3"></div>
                                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                        <table class="table table-striped">
                                            <thead class="table-dark">
                                                <tr>
                                                    <td scope="col">Código</th>
                                                    <td scope="col">componente</th>
                                                    <td scope="col">Unidad medida</th>
                                                    <td scope="col">Costo </th>
                                                    <td scope="col">Agregar</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbodyInsumos">

                                                <?php $unidadesMedida = model('unidadMedidaModel')->findAll(); ?>
                                                <?php foreach ($insumos as $detalleInsumo):
                                                    //$idMedida=model('productoMedidaModel')->select('idvalor_unidad_medida')->where('codigointernoproducto', $detalleInsumo['codigointernoproducto'])->first();
                                                    $unidadMedida = model('productoFabricadoModel')->GetMedida($detalleInsumo['codigointernoproducto']);
                                                    //echo $unidadMedida[0]['idunidad_medida']."</br>";
                                                ?>
                                                    <tr>
                                                        <td><?php echo $detalleInsumo['codigointernoproducto'] ?></td>
                                                        <td><?php echo $detalleInsumo['nombreproducto'] ?></td>
                                                        <td>
                                                            <select name="" class="form-select" onchange="cambiarUnidadMedida(this, '<?php echo $detalleInsumo['codigointernoproducto']; ?>')">
                                                                <!-- Opción seleccionada por defecto -->
                                                                <option value="<?php echo $unidadMedida[0]['idunidad_medida']; ?>" selected>
                                                                    <?php echo $unidadMedida[0]['medida']; ?>
                                                                </option>

                                                                <!-- Filtramos el array para eliminar la opción ya seleccionada -->
                                                                <?php foreach ($unidadesMedida as $detalleUnidad): ?>
                                                                    <option value="<?php echo $detalleUnidad['idvalor_unidad_medida']; ?>">
                                                                        <?php echo $detalleUnidad['descripcionvalor_unidad_medida']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                        <td><?php echo $detalleInsumo['precio_costo'] ?></td>
                                                        <td><button class="btn btn-outline-success btn-icon btn-sm" id="insu<?= $detalleInsumo['codigointernoproducto']; ?>" onclick="selectInsumo(<?= $detalleInsumo['codigointernoproducto']; ?>, '<?= htmlspecialchars($detalleInsumo['nombreproducto'], ENT_QUOTES, 'UTF-8'); ?>')"><!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <line x1="12" y1="5" x2="12" y2="19" />
                                                                    <line x1="5" y1="12" x2="19" y2="12" />
                                                                </svg></button></td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>

                            </div>
                            <div class="col-6">
                                <label for="recetaSelect" class="col-12 fw-bold text-primary" id="componentesReceta">Componentes producto: </label>
                                <input type="hidden" id="codigoReceta">
                                <input type="hidden" id="nombreReceta">
                                <div class="mb-3"></div>
                                <table class="table table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <td scope="col">Item</th>
                                            <td scope="col">Código</th>
                                            <td scope="col">Producto</th>
                                            <td scope="col">Unidad medida</th>
                                            <td scope="col">Cantidad </th>
                                            <td scope="col">Costo unidad</th>
                                            <td scope="col">Costo total</th>
                                            <td scope="col">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="insumosReceta">

                                        <tr>

                                        </tr>

                                    </tbody>
                                </table>

                                <div class="row w-100">
                                    <div class="col-4">
                                        <label for="total_costo" class="form-label">Total Costo</label>
                                        <input type="text" class="form-control" id="totalCosto" readonly>
                                    </div>

                                    <div class="col-4">
                                        <label for="precio_venta" class="form-label">Precio Venta</label>
                                        <input type="text" class="form-control" id="precioVenta" readonly>
                                    </div>

                                    <div class="col-4">
                                        <label for="rentabilidad" class="form-label">Rentabilidad</label>
                                        <input type="text" class="form-control" id="rentabilidad" readonly>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?= $this->include('layout/footer') ?>
        </div>



        <!-- Libs JS -->
        <!-- Tabler Core -->
        <script src="<?= base_url() ?>/Assets/js/tabler.min.js"></script>
        <!-- J QUERY -->
        <script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
        <!-- jQuery-ui -->
        <script src="<?php echo base_url() ?>/Assets/plugin/jquery-ui/jquery-ui.js"></script>
        <!-- Sweet alert -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_centrado.js"></script>

        <!-- Modal -->
        <div class="modal fade" id="adicionarInsumo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tittleReceta">Insumo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <label for="">Insumo</label>
                                <input type="text" class="form-control" id="insumoForReceta" readonly>
                                <input type="hidden" id="idInsumoReceta">
                            </div>
                            <div class="col">
                                <label for="">Cantidad</label>
                                <input type="text" class="form-control" id="cantidadInsumo">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success" id="btnAddInsumo" onclick="agregarInsumo()">Aceptar</button>
                        <button type="button" class="btn btn-outline-danger">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
</body>

<script>
    document.getElementById("productoInsumo").addEventListener("keyup", async function(event) {
        if ((event.key === "Enter" || event.key === "Backspace") && this.value.trim() === "") {
            event.preventDefault(); // Evita el envío del formulario si está dentro de uno

            await funcionAsincronaInsumos();
        }
    });

    async function funcionAsincronaInsumos() {
        try {
            let response = await fetch('<?= base_url('login/allInsumos') ?>', {
                method: 'GET', // GET no debe tener body
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            let data = await response.json();

            document.getElementById('tbodyInsumos').innerHTML = data.productos;

            // Aquí puedes actualizar la UI si es necesario
        } catch (error) {
            console.error("Error en la petición:", error);
        }
    }
</script>


<script>
    document.getElementById("buscar_receta").addEventListener("keyup", async function(event) {
        if ((event.key === "Enter" || event.key === "Backspace") && this.value.trim() === "") {
            event.preventDefault(); // Evita el envío del formulario si está dentro de uno
            await funcionAsincrona();
        }
    });

    async function funcionAsincrona() {
        try {
            let response = await fetch('<?= base_url('login/allRecetas') ?>', {
                method: 'GET', // GET no debe tener body
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            let data = await response.json();
            console.log("Respuesta del servidor:", data);

            document.getElementById('tbodyRecetas').innerHTML = data.productos;

            // Aquí puedes actualizar la UI si es necesario
        } catch (error) {
            console.error("Error en la petición:", error);
        }
    }
</script>


<script>
    async function cambiarUnidadMedida(select, codigo) {
        let idUnidad = select.value; // Obtener del id de la medida  a actualizar
        let codigoInternoProducto = codigo; // id del producto a actualizar          

        try {
            let response = await fetch('<?= base_url('login/updateMedida') ?>', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idUnidad,
                    codigoInternoProducto
                })
            });

            let data = await response.json();
            sweet_alert_centrado('success', 'Unidad de medida cambiada ')
            // Aquí puedes hacer algo con la respuesta, como actualizar un elemento en la página
        } catch (error) {
            console.error("Error en la petición:", error);
        }
    }
</script>


<script>
    async function actualizarCantidad(valor, id) {

        try {
            // Hacer una solicitud a la API en PHP
            let response = await fetch('<?= base_url('login/updateCantidadInsumo') ?>', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    valor: valor,
                    id: id
                })
            });

            // Verificar si la respuesta es correcta
            if (!response.ok) {
                throw new Error('Error en la solicitud');
            }

            // Convertir respuesta a JSON
            let data = await response.json();

            // Mostrar los datos en la consola (puedes usarlos en otra parte del código)

            document.getElementById('totalCosto').value = data.costo;
            document.getElementById('precioVenta').value = data.precio_venta;
            document.getElementById('rentabilidad').value = data.rentabilidad;
            document.getElementById('costoTotal' + data.id).innerHTML = data.costoTotal;

        } catch (error) {
            console.error('Error:', error);
        }

    }
</script>







<script>
    async function selectInsumo(codigo, nombre) {
        let codigoReceta = document.getElementById("codigoReceta").value.trim();
        let nombreReceta = document.getElementById("nombreReceta").value;
        document.getElementById('tittleReceta').innerHTML = `Adicionar insumo: <span style="color: blue;">${nombre}</span> a la receta: <span style="color: blue;">${nombreReceta}</span>`;
        document.getElementById('insumoForReceta').value = nombre
        document.getElementById('idInsumoReceta').value = codigo
        modal = document.getElementById('modal').value;


        $('#adicionarInsumo').on('shown.bs.modal', function() {
            document.getElementById('cantidadInsumo').focus();
        });

        // Validar si el campo está vacío
        if (codigoReceta === "") {
            sweet_alert_centrado('warning', 'No hay receta seleccionada');
            return;
        }
        if (modal === 't') {
            var myModal = new bootstrap.Modal(document.getElementById('adicionarInsumo'));
            myModal.show();
            //$('#adicionarInsumo').modal('show');
        } else if (modal === 'f') {
            agregarInsumo();
        }
    }

    async function agregarInsumo() {

        codigoReceta = document.getElementById('codigoReceta').value;
        codigoInsumo = document.getElementById('idInsumoReceta').value;
        modal = document.getElementById('modal').value;

        if (modal === 't') {
            cantidad = document.getElementById('cantidadInsumo').value;
        } else if (modal === 'f') {
            cantidad = 1;
        }

        document.getElementById('btnAddInsumo').btnAddInsumo = true;


        try {
            // Hacer una solicitud a la API en PHP
            let response = await fetch('<?= base_url('login/addInsumo') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    codigoReceta: codigoReceta,
                    codigoInsumo: codigoInsumo,
                    cantidad: cantidad
                })
            });

            // Verificar si la respuesta es correcta
            if (!response.ok) {
                throw new Error('Error en la solicitud');
            }

            // Convertir respuesta a JSON
            let data = await response.json();

            // Mostrar los datos en la consola (puedes usarlos en otra parte del código)

            if (data.response == "success") {

                document.getElementById('insumosReceta').innerHTML = data.insumos
                document.getElementById('cantidadInsumo').value = "";
                document.getElementById('productoInsumo').value = "";
                document.getElementById('resListInsumos').innerHTML = "";

                document.getElementById('totalCosto').value = data.costo;
                document.getElementById('precioVenta').value = data.precio_venta;
                document.getElementById('rentabilidad').value = data.rentabilidad;
                sweet_alert_centrado('success', 'Insumo adicionado')
                /* 
                            var modalInstance = bootstrap.Modal.getInstance(document.getElementById('adicionarInsumo'));
                            if (modalInstance) {
                                modalInstance.hide();
                            }
                 */

                $("#adicionarInsumo").modal("hide");
            } else if (data.response == "exist") {
                sweet_alert_centrado('error', 'No se puede adicionar el insumos ya esta presente en el producto')
            }


        } catch (error) {
            console.error('Error:', error);
        }
    }
</script>




<script>
    function limpiarBusqueda() {

        document.getElementById('productoInsumo').value = "";
        document.getElementById('productoInsumo').focus();
        document.getElementById('resListInsumos').innerHTML = "";
    }
</script>

<script>
    // Función para seleccionar el producto y obtener datos de la BD
    async function detalleReceta(codigointernoproducto) {

        try {
            // Hacer una solicitud a la API en PHP
            let response = await fetch('<?= base_url('login/SearchInsumosRecetas') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    codigointernoproducto: codigointernoproducto
                })
            });

            // Verificar si la respuesta es correcta
            if (!response.ok) {
                throw new Error('Error en la solicitud');
            }

            // Convertir respuesta a JSON
            let data = await response.json();

            // Mostrar los datos en la consola (puedes usarlos en otra parte del código)
            if (data.response == 'success') {
                document.getElementById('insumosReceta').innerHTML = data.productos
                document.getElementById('componentesReceta').innerHTML = data.receta
                document.getElementById('totalCosto').value = data.costo
                document.getElementById('precioVenta').value = data.precioVenta
                document.getElementById('rentabilidad').value = data.rentabilidad
                document.getElementById('codigoReceta').value = data.codigo
                document.getElementById('nombreReceta').value = data.nombreReceta
                document.getElementById('buscar_receta').value = "";
            }
            if (data.response == 'fail') {
                document.getElementById('componentesReceta').innerHTML = data.receta
                document.getElementById('totalCosto').value = data.costo
                document.getElementById('precioVenta').value = data.precioVenta
                document.getElementById('rentabilidad').value = data.rentabilidad
                document.getElementById('codigoReceta').value = data.codigo
                document.getElementById('nombreReceta').value = data.nombreReceta
                document.getElementById('buscar_receta').value = "";
                document.getElementById('insumosReceta').innerHTML = "";
                sweet_alert_centrado('warning', 'Producto no tiene insumos asociados')
            }


        } catch (error) {
            console.error('Error:', error);
        }
    }
</script>



<script>
    function limpiar(inputId) {
        let inputElement = document.getElementById('buscar_receta').value = "";
        document.getElementById('buscar_receta').focus();
        document.getElementById('resList').innerHTML = "";


    }
</script>




<script>
    async function buscar_receta(valorBusqueda) {
        const BASE_URL = document.getElementById('url').value.trim();
        try {
            const response = await fetch(BASE_URL + '/login/Searchrecetas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    valorBusqueda,
                })
            });

            const result = await response.json();

            if (result.response === 'success') {
                /*     const suggestionsDiv = document.getElementById('resList');
                    suggestionsDiv.innerHTML = ''; // Limpia el contenido previo
                    // Asegúrate de que result.terceros contenga el contenido que quieres mostrar
                    suggestionsDiv.innerHTML = result.productos; */

                document.getElementById('tbodyRecetas').innerHTML = result.productos;

            }
            if (result.response === 'empty') {
                document.getElementById('tbodyRecetas').innerHTML = "";
                sweet_alert_centrado('warning', 'No hay recetas asociadas')
            }


        } catch (error) {


        }
    }
</script>
<script>
    async function buscarInsumos(valorBusqueda) {
        const BASE_URL = document.getElementById('url').value.trim();
        try {
            const response = await fetch(BASE_URL + '/login/SearchInsumos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    valorBusqueda,
                })
            });

            const result = await response.json();

            if (result.response === 'success') {
                /*  const suggestionsDivInsu = document.getElementById('resListInsumos');
                 suggestionsDivInsu.innerHTML = ''; // Limpia el contenido previo
                 // Asegúrate de que result.terceros contenga el contenido que quieres mostrar
                 suggestionsDivInsu.innerHTML = result.productos; */

                document.getElementById('tbodyInsumos').innerHTML = result.productos;

            }
            if (result.response === 'empty') {
                /*  const suggestionsDivInsu = document.getElementById('resListInsumos');
                 suggestionsDivInsu.innerHTML = ''; // Limpia el contenido previo
                 // Asegúrate de que result.terceros contenga el contenido que quieres mostrar
                 suggestionsDivInsu.innerHTML = result.productos; */

                document.getElementById('tbodyInsumos').innerHTML = "";
                sweet_alert_centrado('warning', 'No hay coincidencias con el insumo solicitado')

            }


        } catch (error) {


        }
    }
</script>
<script>
    // Función para seleccionar el producto y obtener datos de la BD
    async function selectProducto(codigointernoproducto, nombreProducto) {
        // Asignar el nombre al input
        document.getElementById('buscar_receta').value = nombreProducto;

        // Ocultar la lista de sugerencias
        document.getElementById('sugerencias').style.display = 'none';

        try {
            // Hacer una solicitud a la API en PHP
            let response = await fetch('<?= base_url('login/SearchInsumosRecetas') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    codigointernoproducto: codigointernoproducto
                })
            });

            // Verificar si la respuesta es correcta
            if (!response.ok) {
                throw new Error('Error en la solicitud');
            }

            // Convertir respuesta a JSON
            let data = await response.json();

            // Mostrar los datos en la consola (puedes usarlos en otra parte del código)

            document.getElementById('insumosReceta').innerHTML = data.productos
            document.getElementById('componentesReceta').innerHTML = data.receta
            document.getElementById('totalCosto').value = data.costo
            document.getElementById('precioVenta').value = data.precioVenta
            document.getElementById('rentabilidad').value = data.rentabilidad
            document.getElementById('codigoReceta').value = data.codigo
            document.getElementById('nombreReceta').value = data.nombreReceta



        } catch (error) {
            console.error('Error:', error);
        }
    }
</script>


<!-- <script>
     async function borrarInsumo(id) {

        try {
            // Hacer una solicitud a la API en PHP
            let response = await fetch('<?= base_url('login/deleteInsumo') ?>', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: id
                })
            });

            // Verificar si la respuesta es correcta
            if (!response.ok) {
                throw new Error('Error en la solicitud');
            }

            // Convertir respuesta a JSON
            let data = await response.json();

            // Mostrar los datos en la consola (puedes usarlos en otra parte del código)

            document.getElementById('insumosReceta').innerHTML = data.productos
            document.getElementById('componentesReceta').innerHTML = data.receta



        } catch (error) {
            console.error('Error:', error);
        }

    }
</script> -->


<script>
    async function borrarInsumo(id) {
        try {
            const resultado = await Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            });

            if (!resultado.isConfirmed) {
                return; // Si el usuario cancela, no hacer nada
            }

            // Hacer una solicitud a la API en PHP
            let response = await fetch('<?= base_url('login/deleteInsumo') ?>', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: id
                })
            });

            // Verificar si la respuesta es correcta
            if (!response.ok) {
                throw new Error('Error en la solicitud');
            }

            // Convertir respuesta a JSON
            let data = await response.json();

            // Actualizar la interfaz después del borrado
            document.getElementById('insumo' + data.id).remove();
            document.getElementById('totalCosto').value = data.costo;
            document.getElementById('precioVenta').value = data.precio_venta;
            document.getElementById('rentabilidad').value = data.rentabilidad;


            sweet_alert_centrado('success', 'Insumo borrado')

        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                title: "Error",
                text: "Hubo un problema al eliminar el insumo.",
                icon: "error"
            });
        }
    }
</script>






</html>