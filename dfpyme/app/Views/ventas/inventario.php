<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/inventarios') ?>
<?= $this->section('title') ?>
INVENTARIOS
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<!-- CSS de Select2 -->

<style>
    /* Establece que la tabla se ajuste a la altura disponible */
    .table-container {
        height: 70vh;
        /* Usa el 100% de la altura de la ventana */
        overflow-y: auto;
        /* Habilita la barra de desplazamiento si es necesario */
    }

    /* Si deseas que el contenido de la tabla tenga un área visible con scroll */
    .table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 10px;
        text-align: left;
    }

    .table thead {
        position: sticky;
        top: 0;
        background-color: #343a40;
        /* Cambia según el color de fondo de tu cabecera */
        z-index: 1;
    }
</style>

<div class="container">

    <div class="row align-items-center mb-1">
        <!-- Columna para el título centrado -->
        <div class="col text-center">
            <p class="h3 text-primary mb-0">INVENTARIO GENERAL </p>
        </div>
        <!-- Columna para el botón alineado a la derecha -->
        <div class="col-auto ms-auto">
            <form action="<?php echo base_url() ?>/consultas_y_reportes/Inventario" method="POST">
                <button type="submit" class="btn btn-outline-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Exportar a EXCEL ">Excel</button>
            </form>

        </div>
    </div>

    <div class="row mb-1">
        <div class="col">
            <label for="" class="form-label">Buscar producto</label>
            <div class="mb-3">
                <div class="input-group input-group-flat">
                    <input type="text" class="form-control" onkeyup="Busqueda(this.value)" placeholder="Buscar por nombre o código">
                    <span class="input-group-text">
                        <a href="#" class="link-secondary" title="Limpiar" data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler-icons.io/i/x -->
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
        <div class="col">
            <label for="" class="form-label">Buscar por categoria </label>
            <select name="" id="BusCategorias1" class="form-select" onchange="categoriaSeleccionada(this.value)">

                <?php foreach ($categorias as $keyCategorias): ?>
                    <option value="<?php echo $keyCategorias['id']; ?>"><?php echo $keyCategorias['nombrecategoria']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-6">

        </div>
    </div>


    <div class="card">
        <div class="card-body">

            <div class="mb-3" id="ResultadosCategoria" style="display: none;">
                <label class="form-label">Buscando registros ...</label>

                <div class="progress">
                    <div class="progress-bar progress-bar-indeterminate bg-green"></div>
                </div>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <td scope="col" style="width: 30%;">Categoría</th>
                            <td scope="col" style="width: 8%;">Código</th>
                            <td scope="col" style="width: 30%;">Producto</th>
                            <td scope="col" style="width: 8%;">Cantidad</th>
                            <td scope="col">Costo unidad</th>
                            <td scope="col">Costo total</th>
                        </tr>
                    </thead>
                    <tbody id="inventario">
                        <tr>

                        </tr>

                    </tbody>
                </table>
            </div>

            <div class="text-end text-primary h3 ">
                <span>Costo total inventario:</span> <span id="costoInventario"> </span><br>
                <span>Total unidades: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span id="totalUnidades"> </span>
            </div>





        </div>
    </div>
</div>


<script>
    async function categoriaSeleccionada(valor) {
        const baseUrl = "<?php echo base_url(); ?>"; // Obtiene el base_url desde PHP
        const url = `${baseUrl}/pre_factura/busquedaCategoria`; // Construye la URL dinámica

        const div = document.getElementById('ResultadosCategoria');
        div.style.display = 'block';
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    valor
                }) // Envía el valor en el cuerpo de la solicitud
            });

            if (!response.ok) {
                throw new Error(`Error en la solicitud: ${response.statusText}`);
            }

            const data = await response.json();

            if (data.success === true) {
                sweet_alert_centrado('success', 'Registros encontrados')
                div.style.display = 'none';
                let rows = '';
                data.productos.forEach(producto => {
                    // Construimos las filas con los datos del producto
                    rows += `<tr>
                                 <td>${producto.nombrecategoria}</td>
                                 <td>${producto.codigointernoproducto}</td>
                                 <td>${producto.nombreproducto}</td>
                                 <td>${producto.cantidad_inventario}</td>
                               <td>${producto.costo_unitario.toLocaleString()}</td>
<td>${producto.costo_producto.toLocaleString()}</td>
                            </tr>`;

                    document.getElementById('inventario').innerHTML = rows;


                });
                document.getElementById('costoInventario').innerHTML = data.costo_total;
                document.getElementById('totalUnidades').innerHTML = 0;
            } else if (data.success === false) {
                sweet_alert_centrado('error', 'No hay registros asociados')
                div.style.display = 'none';
            }
        } catch (error) {
            console.error("Error al cruzar el inventario:", error);
            sweet_alert_centrado('error', 'Ocurrió un error inesperado al cruzar el inventario');
        }

    }
</script>


<script>
    document.addEventListener("DOMContentLoaded", async function() {
        await inventario(); // Llama a la función tan pronto como se carga la página
    });

    async function inventario() {
        try {
            const baseUrl = "<?php echo base_url(); ?>"; // Obtiene el base_url desde PHP
            const url = `${baseUrl}/administracion_impresora/ProductosInventario`; // Construye la URL dinámica

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
            });

            if (!response.ok) {
                throw new Error(`Error en la solicitud: ${response.statusText}`);
            }

            const data = await response.json();

            if (data.success === true) {
                // Inicializamos la variable rows antes del ciclo
                document.getElementById('costoInventario').innerHTML = data.costo_total;
                document.getElementById('totalUnidades').innerHTML = data.unidades;
                let rows = '';

                // Iteramos sobre los productos
                data.productos.forEach(producto => {
                    // Construimos las filas con los datos del producto
                    rows += `<tr>
                                 <td>${producto.nombrecategoria}</td>
                                 <td>${producto.codigointernoproducto}</td>
                                 <td>${producto.nombreproducto}</td>
                                 <td>${producto.cantidad_inventario}</td>
                               <td>${producto.costo_unitario.toLocaleString()}</td>
<td>${producto.costo_producto.toLocaleString()}</td>
                            </tr>`;

                    document.getElementById('inventario').innerHTML = rows;

                });



                // Finalmente, actualizamos el contenido del tbody con las filas acumuladas

            } else if (data.success === false) {
                sweet_alert_centrado('warning', 'No hay productos receta');
            }
        } catch (error) {
            console.error('Hubo un problema al cargar las recetas:', error);
            alert('No se pudo cargar las recetas. Por favor, intenta de nuevo.');
        }
    }
</script>


<script>
    async function Busqueda(valor) {
        const baseUrl = "<?php echo base_url(); ?>"; // Obtiene el base_url desde PHP
        const url = `${baseUrl}/pre_factura/busqueda`; // Construye la URL dinámica

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    valor
                }) // Envía el valor en el cuerpo de la solicitud
            });

            if (!response.ok) {
                throw new Error(`Error en la solicitud: ${response.statusText}`);
            }

            const data = await response.json();

            if (data.success === true) {
                let rows = '';
                data.productos.forEach(producto => {
                    // Construimos las filas con los datos del producto
                    rows += `<tr>
                                 <td>${producto.nombrecategoria}</td>
                                 <td>${producto.codigointernoproducto}</td>
                                 <td>${producto.nombreproducto}</td>
                                 <td>${producto.cantidad_inventario}</td>
                               <td>${producto.costo_unitario.toLocaleString()}</td>
<td>${producto.costo_producto.toLocaleString()}</td>
                            </tr>`;

                    document.getElementById('inventario').innerHTML = rows;

                });

            } else if (data.success === false) {
                document.getElementById('noHay').innerHTML = "No hay productos disponibles.";
            }
        } catch (error) {
            console.error("Error al cruzar el inventario:", error);
            sweet_alert_centrado('error', 'Ocurrió un error inesperado al cruzar el inventario');
        }
    }
</script>


<?= $this->endSection('content') ?>