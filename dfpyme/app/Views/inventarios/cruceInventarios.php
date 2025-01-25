<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>


<div class="container">
    <div class="my-2">
        <div class="d-flex justify-content-between align-items-center">
            <p class="text-center w-100 m-0 h3 text-primary">Cruce de inventario</p>
            <div class="d-flex ms-3">
                <button type="button" data-bs-toggle="modal" data-bs-target="#conteo" class="btn btn-outline-indigo d-flex ms-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Conteo maunual" onclick="ingresar_inventario()">
                    Conteo manual
                </button>
                <button type="button" class="btn btn-outline-yellow d-flex ms-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cruzar y revisar" onclick="cruzarRevisar()">
                    Cruzar y revisar
                </button>
                <form action="<?php echo base_url() ?>/consultas_y_reportes/reporte_cruce_inventarios">
                    <button class="btn btn-outline-success ms-2" type="submit" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Exportar a excel">Descargar</button>
                </form>
                <!--  <form action="<?php echo base_url() ?>/consultas_y_reportes/reporte_sobrantes">
                    <button class="btn btn-outline-primary ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ver sobrantes" data-bs-toggle="modal" data-bs-target="#sobrantes">Sobrantes</button>
             </form> -->
                <button type="button" class="btn btn-outline-primary d-flex ms-3" data-bs-toggle="modal" data-bs-target="#sobrantes">
                    Sobrantes
                </button>
                <button type="button" class="btn btn-outline-danger d-flex ms-3" data-bs-toggle="modal" data-bs-target="#faltantes">
                    faltantes
                </button>
                <!--  <form action="<?php echo base_url() ?>/consultas_y_reportes/reporte_faltantes">
                    <button class="btn btn-outline-danger ms-2" type="submit" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ver faltantes">Faltantes</button>
                </form> -->
            </div>
        </div>
    </div>




    <div style="display:none" id="progresBar" class="container">
        <p class="h3 text-warning text-center">Cruzando inventario, esta acción tomará un momento </p>
        <div class="progress mb-3">
            <div class="progress-bar progress-bar-indeterminate bg-green"></div>
        </div>
    </div>

    <div class="card">
        <div class="car-body mb-3">



            <div style="height: calc(100vh - 150px); overflow-y: auto;">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <td scope="col">Codigo</td>
                            <td scope="col">Producto</td>
                            <td scope="col">Cantidad sistema</td>
                            <td scope="col">Cantidad conteo</td>
                            <td scope="col">Diferencia inventario</td>
                            <td scope="col">Costo unidad </td>
                            <td scope="col">Costo total</td>
                            <td scope="col">Valor venta</td>
                        </tr>
                    </thead>


                    <tbody id="productosconteo">
                        <?php if (!empty($conteo_manual)): ?>
                            <?php foreach ($inventario_sistema as $KeyInventarioFisico): ?>
                                <?php
                                $producto = model('inventarioModel')->conteo_manual($KeyInventarioFisico['codigointernoproducto']);
                                $costo = model('productoModel')->CostoProducto($KeyInventarioFisico['codigointernoproducto']);

                                ?>
                                <?php if (!empty($producto)): ?>
                                    <tr>
                                        <td><?php echo $producto[0]['codigointernoproducto']; ?></td>
                                        <td><?php echo $producto[0]['nombreproducto']; ?></td>
                                        <td><?php echo $producto[0]['cantidad_inventario']; ?></td>
                                        <td><?php echo $producto[0]['cantidad_inventario_fisico']; ?></td>
                                        <td><?php echo $producto[0]['diferencia']; ?></td>
                                        <td><?php echo number_format($costo[0]['precio_costo'], 0, ",", "."); ?></td>
                                        <td><?php echo number_format($costo[0]['precio_costo'] * $producto[0]['cantidad_inventario'], 0, ",", "."); ?></td>
                                        <td><?php echo number_format($costo[0]['valorventaproducto'] * $producto[0]['cantidad_inventario'], 0, ",", "."); ?></td>
                                    </tr>
                                <?php else: ?>
                                    <?php $dato_producto = model('inventarioModel')->getProducto($KeyInventarioFisico['codigointernoproducto']); ?>
                                    <tr>
                                        <td><?php echo $KeyInventarioFisico['codigointernoproducto']; ?></td>
                                        <td><?php echo $dato_producto[0]['nombreproducto']; ?></td>
                                        <td>0</td> <!-- Cantidad conteo -->
                                        <td>0</td> <!-- Cantidad sistema -->
                                        <td>0</td>
                                        <td><?php echo number_format($dato_producto[0]['precio_costo'], 0, ",", "."); ?></td> <!--Costo unidad -->
                                        <td><?php echo number_format($dato_producto[0]['precio_costo'] * 0, 0, ",", "."); ?></td> <!--Costo total -->
                                        <td><?php echo number_format($dato_producto[0]['valorventaproducto'] * 0, 0, ",", "."); ?></td>
                                    </tr>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

            <?php if (empty($conteo_manual)): ?>

                <p class="text-center text-primary h3">No productos para hacer cruce de inventario </p>

            <?php endif ?>
        </div>
    </div>
</div>




<div class="modal fade" id="conteo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ingresar inventario </h5>
                <button type="button" class="btn-close" onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col">
                        <label for="" class="form-label">Buscar producto</label>


                        <div id="autocomplete-container"></div>

                        <div class="input-group input-group-flat mb-2">
                            <input type="text" class="form-control" id="inventarioInput" name="inventario" onkeyup="buscarProducto(this.value)" placeholder="Buscar por código o nombre de producto">
                            <span class="input-group-text">
                                <a href="#" class="link-secondary" onclick="limpiarInput()" title="Limpiar búsqueda " data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler-icons.io/i/x -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <line x1="18" y1="6" x2="6" y2="18" />
                                        <line x1="6" y1="6" x2="18" y2="18" />
                                    </svg>
                                </a>


                            </span>
                        </div>
                        <span id="noHay" class="text-red"></span>
                    </div>

                </div>

                <style>
                    /* Ajustar el ancho del nombre del producto */
                    .nombre-producto {
                        width: 40%;
                        /* Ajusta el ancho según tus necesidades */
                    }

                    /* Reducir el ancho de los inputs */
                    .input-inventario {
                        width: 70%;
                        /* Ajusta el ancho según tus necesidades */
                    }
                </style>

                <?php $inv = model('inventarioFisicoModel')->select('cantidad_inventario_fisico')->where('corte_inventario_fisico', 'false')->first();  ?>

                <?php if (!empty($inv)):  ?>

                    <p class="text-center text-primary h3 ">Hay un conteo de inventario en curso </p>

                <?php endif ?>


                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <td scope="col">Código</th>
                            <td scope="col">Producto</th>
                            <td scope="col">Inventario actual</th>
                            <td scope="col">Ingresar inventario</th>
                            <td scope="col">Diferencia</th>
                        </tr>
                    </thead>
                    <tbody id="ProdInv">
                        <?php foreach ($productos as $keyProducto): ?>
                            <tr>
                                <td><?php echo $keyProducto['codigointernoproducto']; ?></td>
                                <td class="nombre-producto"><?php echo $keyProducto['nombreproducto']; ?></td>
                                <td>
                                    <input type="text" class="form-control input-inventario"
                                        value="<?php echo $keyProducto['cantidad_inventario']; ?>">
                                </td>
                                <?php
                                //$registro = model('inventarioFisicoModel')->select('cantidad_inventario_fisico')->where('codigointernoproducto', $keyProducto['codigointernoproducto'])->first();  
                                $registro = model('inventarioFisicoModel')->existeProducto($keyProducto['codigointernoproducto']);
                                ?>
                                <?php if (empty($registro)): ?>
                                    <td>
                                        <input type="text" class="form-control input-inventario" id="<?php echo $keyProducto['id']; ?>" onkeyup="ingresarInv(this.value,<?php echo $keyProducto['id']; ?> )">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-inventario" id="diferencia<?php echo $keyProducto['id'] ?>" readonly>
                                    </td>
                                <?php endif ?>

                                <?php if (!empty($registro)): ?>
                                    <td>
                                        <input type="text" value="<?php echo $registro[0]['cantidad_inventario_fisico'];  ?>" class="form-control input-inventario" id="<?php echo $keyProducto['id']; ?>" onkeyup="ingresarInv(this.value,<?php echo $keyProducto['id']; ?> )">
                                    </td>

                                    <?php $diferencia = model('inventarioModel')->conteo_manual($keyProducto['codigointernoproducto']); ?>
                                    <td>
                                        <input type="text" class="form-control input-inventario" id="diferencia<?php echo $keyProducto['id'] ?>" value="<?php echo $diferencia[0]['diferencia']; ?>" readonly>
                                    </td>
                                <?php endif ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>




<!-- Modal -->
<div class="modal fade" id="sobrantes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl  modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reporte de productos sobrantes en el inventario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <td scope="col">Codigo </td>
                            <td scope="col">Producto </td>
                            <td scope="col">Cantidad conteo </td>
                            <td scope="col">Cantidad sistema </td>
                            <td scope="col">Diferencia inventario </td>
                            <td scope="col">Valor costo </td>
                            <td scope="col">Valor venta </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($conteo_manual)): ?>
                            <?php $total_diferencia_venta = 0;
                            foreach ($inventario_sistema as $KeyInventarioFisico): ?>

                                <?php $producto = model('inventarioModel')->conteo_manual($KeyInventarioFisico['codigointernoproducto']); //d($producto);
                                ?>

                                <?php if (!empty($producto)): ?>
                                    <?php if ($producto[0]['diferencia'] > 0): ?>
                                        <tr>
                                            <td><?php echo $producto[0]['codigointernoproducto'];  ?></td>
                                            <td><?php echo $producto[0]['nombreproducto'];  ?></td>
                                            <td><?php echo $producto[0]['cantidad_inventario_fisico'];  ?></td>
                                            <td><?php echo $producto[0]['cantidad_inventario'];  ?></td>
                                            <td><?php echo $producto[0]['diferencia'];  ?></td>
                                            <td><?php echo number_format($producto[0]['valor_costo'], 0, ",", ".");  ?></td>
                                            <td><?php echo number_format($producto[0]['valor_venta'], 0, ",", ".");  ?></td>
                                        </tr>

                                        <?php
                                        // Sumar la diferencia al total
                                        $total_diferencia_venta += $producto[0]['diferencia'] * $producto[0]['valor_venta'];
                                        ?>

                                    <?php endif ?>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                <form action="<?php echo base_url() ?>/consultas_y_reportes/reporte_sobrantes">
                    <button class="btn btn-outline-success ms-2" type="submit" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ver faltantes">Exportar EXCEL</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="faltantes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-xl  modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reporte de productos faltantes en el inventario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <td scope="col">Codigo </td>
                            <td scope="col">Producto </td>
                            <td scope="col">Cantidad conteo </td>
                            <td scope="col">Cantidad sistema </td>
                            <td scope="col">Diferencia inventario </td>
                            <td scope="col">Valor costo </td>
                            <td scope="col">Valor venta </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($conteo_manual)): ?>
                            <?php $total_diferencia_venta = 0;
                            foreach ($inventario_sistema as $KeyInventarioFisico): ?>

                                <?php $producto = model('inventarioModel')->conteo_manual($KeyInventarioFisico['codigointernoproducto']);
                                ?>

                                <?php if (!empty($producto)): ?>
                                    <?php if ($producto[0]['diferencia'] < 0): ?>
                                        <tr>
                                            <td><?php echo $producto[0]['codigointernoproducto'];  ?></td>
                                            <td><?php echo $producto[0]['nombreproducto'];  ?></td>
                                            <td><?php echo $producto[0]['cantidad_inventario_fisico'];  ?></td>
                                            <td><?php echo $producto[0]['cantidad_inventario'];  ?></td>
                                            <td><?php echo $producto[0]['diferencia'];  ?></td>
                                            <td><?php echo number_format($producto[0]['valor_costo'], 0, ",", ".");  ?></td>
                                            <td><?php echo number_format($producto[0]['valor_venta'], 0, ",", ".");  ?></td>
                                        </tr>
                                    <?php endif ?>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                <form action="<?php echo base_url() ?>/consultas_y_reportes/reporte_faltantes">
                    <button class="btn btn-outline-success ms-2" type="submit" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ver faltantes">Exportar EXCEL</button>
                </form>
            </div>
        </div>
    </div>
</div>








<script>
    async function closeModal() {
        const baseUrl = "<?php echo base_url(); ?>"; // Obtiene el base_url desde PHP
        const url = `${baseUrl}/consultas_y_reportes/closeModal`; // Construye la URL dinámica

        // Limpia el contenido de 'noHay' si existe
        const noHayElement = document.getElementById('noHay');
        if (noHayElement) {
            noHayElement.innerHTML = "";
        }

        try {
            // Realiza la solicitud GET al servidor
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`Error en la solicitud: ${response.statusText}`);
            }

            const data = await response.json();

            if (data.success === true) {


                var myModalElement = document.getElementById('conteo'); // Obtén el elemento del modal
                var myModal = bootstrap.Modal.getInstance(myModalElement); // Obtén la instancia del modal

                if (myModal) {
                    myModal.hide(); // Cierra el modal
                } else {
                    console.error("No se encontró una instancia del modal. Asegúrate de que el modal esté inicializado.");
                }

                const input = document.getElementById('inventarioInput');
                if (input) {
                    input.value = ''; // Limpia el valor del input
                    input.focus(); // Da el foco al input
                }

                document.getElementById('productosconteo').innerHTML = data.productos;

            } else if (data.success === false) {
                sweet_alert_centrado('warning', 'No hay inventario para cruzar');
            }
        } catch (error) {
            console.error("Error al cruzar el inventario:", error);
            sweet_alert_centrado('error', 'Ocurrió un error inesperado al cruzar el inventario');
        }
    }
</script>



<script>
    async function limpiarInput(valor) {
        const baseUrl = "<?php echo base_url(); ?>"; // Obtiene el base_url desde PHP
        const url = `${baseUrl}/consultas_y_reportes/productos_inventario`; // Construye la URL dinámica

        document.getElementById('noHay').innerHTML = "";

        try {
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
                const input = document.getElementById('inventarioInput');
                if (input) {
                    input.value = ''; // Limpia el valor del input
                    input.focus(); // Da el foco al input
                }
                document.getElementById('ProdInv').innerHTML = data.productos;
            } else if (data.success === false) {
                sweet_alert_centrado('warning', 'No hay inventario para cruzar');
            }
        } catch (error) {
            console.error("Error al cruzar el inventario:", error);
            sweet_alert_centrado('error', 'Ocurrió un error inesperado al cruzar el inventario');
        }
    }
</script>


<script>
    async function buscarProducto(valor) {
        const baseUrl = "<?php echo base_url(); ?>"; // Obtiene el base_url desde PHP
        const url = `${baseUrl}/pre_factura/buscarProducto`; // Construye la URL dinámica

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
                document.getElementById('ProdInv').innerHTML = data.productos;
                document.getElementById('noHay').innerHTML = "";
            } else if (data.success === false) {
                document.getElementById('noHay').innerHTML = "No hay productos disponibles.";
            }
        } catch (error) {
            console.error("Error al cruzar el inventario:", error);
            sweet_alert_centrado('error', 'Ocurrió un error inesperado al cruzar el inventario');
        }
    }
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById("inventario");
        const container = document.getElementById("autocomplete-container");
        const url = <?php echo base_url(); ?>;

        input.addEventListener("input", function() {
            const term = input.value.trim();

            if (term.length < 1) {
                container.innerHTML = "";
                return;
            }

            // Realiza la solicitud AJAX con fetch
            fetch(`${url}/inventario/producto_entrada`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        term: term
                    })
                })
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = ""; // Limpia la lista anterior

                    if (data.length > 0) {
                        const list = document.createElement("div");
                        list.className = "autocomplete-list";

                        data.forEach(item => {
                            const listItem = document.createElement("div");
                            listItem.className = "autocomplete-item";
                            listItem.textContent = item.value;

                            // Al hacer clic en un elemento
                            listItem.addEventListener("click", function() {
                                if (item.id_inventario === 1 || item.id_inventario === 4) {
                                    input.value = "";
                                    document.getElementById("display").value = item.value;
                                    document.getElementById("id_producto").value = item.codigo;
                                    document.getElementById("actual").value = item.cantidad;
                                    document.getElementById("precio").value = item.precio_costo;
                                    document.getElementById("cantidad").focus();
                                    document.getElementById("cantidad").select();

                                    // Calcula el nuevo total
                                    const actual = parseFloat(item.cantidad) || 0;
                                    const cantidad = parseFloat(document.getElementById("cantidad").value) || 0;
                                    document.getElementById("nuevo").value = actual + cantidad;
                                } else if (item.id_inventario === 3) {
                                    document.getElementById("error_producto").innerHTML =
                                        "Este producto es una receta y no se puede ingresar por compras";
                                }
                                container.innerHTML = ""; // Limpia la lista
                            });

                            list.appendChild(listItem);
                        });

                        container.appendChild(list);
                    }
                })
                .catch(error => {
                    console.error("Error en la solicitud:", error);
                });
        });

        // Cierra el autocomplete si se hace clic fuera
        document.addEventListener("click", function(event) {
            if (!container.contains(event.target) && event.target !== input) {
                container.innerHTML = "";
            }
        });
    });
</script>





<script>
    // Inicializa el modal
    const myModalElement = document.getElementById('sobrantes');
    const myModal = new bootstrap.Modal(myModalElement);

    // Muestra el modal
    myModal.show();

    /*  // Cambia dinámicamente el contenido
     const modalBody = myModalElement.querySelector('.sobrantes');
     modalBody.textContent = "Nuevo contenido dinámico."; */

    // Actualiza la posición y el estilo del modal
    myModal.handleUpdate();
</script>



<script>
    function cruzarRevisar() {

        Swal.fire({
            title: "¿Seguro desea cruzar el inventario?",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: "Cruzar",
            denyButtonText: "Cancelar",
            customClass: {
                confirmButton: 'btn btn-success mx-2',
                cancelButton: 'btn btn-danger mx-2'
            },
            buttonsStyling: false // Necesario para que las clases personalizadas funcionen
        }).then((result) => {
            if (result.isConfirmed) {
                cruceInventario();
            } else if (result.isDenied) {
                Swal.fire("Changes are not saved", "", "info");
            }
        });


    }
</script>


<script>
    async function cruceInventario() {
        const baseUrl = "<?php echo base_url(); ?>"; // Obtiene el base_url desde PHP
        const url = `${baseUrl}/pre_factura/cruzarInventario`; // Construye la URL dinámica

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`Error en la solicitud: ${response.statusText}`);
            }

            const data = await response.json();

            if (data.success === true) {
                sweet_alert_centrado('success', 'Inventario cruzado');
                location.reload();


            } else if (data.success === false) {
                sweet_alert_centrado('warning', 'No hay inventario para cruzar');
            }
        } catch (error) {
            console.error("Error al cruzar el inventario:", error);
            sweet_alert_centrado('error', 'Ocurrió un error inesperado al cruzar el inventario');
        }
    }
</script>





<script>
    async function ingresarInv(valor, id) {
        try {
            const baseUrl = "<?php echo base_url(); ?>"; // Obtiene el base_url desde PHP
            const url = `${baseUrl}/pre_factura/ingresarInv`; // Construye la URL dinámica

            // Crea el payload (datos a enviar)
            const payload = {
                id: id, // ID del producto
                valor: valor // Valor ingresado por el usuario
            };

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload) // Enviar datos como JSON
            });

            if (!response.ok) {
                throw new Error(`Error en la solicitud: ${response.statusText}`);
            }

            const data = await response.json();

            if (data.success === true) {

                document.getElementById('diferencia' + data.id).value = data.diferencia
                sweet_alert_centrado('success', 'Ingresado al inventario');

            } else {
                console.warn('Respuesta inesperada del servidor:', data);
                alert(data.message || 'Hubo un problema en la actualización.');
            }
        } catch (error) {
            console.error('Hubo un problema al actualizar el producto:', error);
            alert('No se pudo actualizar el producto. Por favor, intenta de nuevo.');
        }
    }
</script>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById("producto_compra");
        const container = document.getElementById("autocomplete-container");
        const url = document.getElementById("url").value;

        input.addEventListener("input", function() {
            const term = input.value.trim();

            if (term.length < 1) {
                container.innerHTML = "";
                return;
            }

            // Realiza la solicitud AJAX con fetch
            fetch(`${url}/inventario/producto_entrada`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        term: term
                    })
                })
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = ""; // Limpia la lista anterior

                    if (data.length > 0) {
                        const list = document.createElement("div");
                        list.className = "autocomplete-list";

                        data.forEach(item => {
                            const listItem = document.createElement("div");
                            listItem.className = "autocomplete-item";
                            listItem.textContent = item.value;

                            // Al hacer clic en un elemento
                            listItem.addEventListener("click", function() {
                                if (item.id_inventario === 1 || item.id_inventario === 4) {
                                    input.value = "";
                                    document.getElementById("display").value = item.value;
                                    document.getElementById("id_producto").value = item.codigo;
                                    document.getElementById("actual").value = item.cantidad;
                                    document.getElementById("precio").value = item.precio_costo;
                                    document.getElementById("cantidad").focus();
                                    document.getElementById("cantidad").select();

                                    // Calcula el nuevo total
                                    const actual = parseFloat(item.cantidad) || 0;
                                    const cantidad = parseFloat(document.getElementById("cantidad").value) || 0;
                                    document.getElementById("nuevo").value = actual + cantidad;
                                } else if (item.id_inventario === 3) {
                                    document.getElementById("error_producto").innerHTML =
                                        "Este producto es una receta y no se puede ingresar por compras";
                                }
                                container.innerHTML = ""; // Limpia la lista
                            });

                            list.appendChild(listItem);
                        });

                        container.appendChild(list);
                    }
                })
                .catch(error => {
                    console.error("Error en la solicitud:", error);
                });
        });

        // Cierra el autocomplete si se hace clic fuera
        document.addEventListener("click", function(event) {
            if (!container.contains(event.target) && event.target !== input) {
                container.innerHTML = "";
            }
        });
    });
</script>





<?= $this->endSection('content') ?>