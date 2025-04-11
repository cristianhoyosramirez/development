<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title><?= $this->renderSection('title') ?>CONFIGURACIÓN DE PRODUCTO &nbsp;-&nbsp;DFPYME</title>
    <!-- Jquery-ui -->
    <link href="<?php echo base_url(); ?>/Assets/plugin/jquery-ui/jquery-ui.css" rel="stylesheet">

    <!-- CSS files -->
    <link href="<?= base_url() ?>/Assets/css/tabler.min.css" rel="stylesheet" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/Assets/img/favicon.png">
</head>
<?php $session = session(); ?>

<input type="hidden" value="<?php echo base_url() ?>" id="url">


<body>
    <div class="wrapper">
        <div class="page-wrapper">
            <?= $this->include('layout/header_mesas') ?>

            <div class="page-body">
                <p class="text-center text-primary h3">Atributos de producto </p>
                <div class="container mt-4">
                    <div class="card p-4 shadow">
                        <div class="row">
                            <div class="d-flex justify-content-end mb-2">
                                <button type="button" class="btn btn-outline-success" onclick="agregarAtributo()">Agregar atributo</button>
                            </div>
                            <div class="row align-items-center">
                                <label for="recetaSelect" class="col-3 fw-bold">Atributo:</label>
                                <div class="col-9">
                                    <div class="input-group input-group-flat">
                                        <input type="text" class="form-control" autocomplete="off" placeholder="Buscar un atributo" id="buscarAtri" oninput="buscarAtributo(this.value)">

                                        <span class="input-group-text">
                                            <a href="#" class="link-secondary" title="Limpiar búsqueda" data-bs-toggle="tooltip" data-bs-placement="bottom" onclick="limpiar()"><!-- Download SVG icon from http://tabler-icons.io/i/x -->
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

                            <div class="mb-3"></div>
                            <div id="resContainer" style="height: 60vh; overflow-y: auto;">
                                <div id="resList">
                                    <?php foreach ($atributos as $detalle): ?>
                                        <div class="accordion" id="accordion<?php echo $detalle['id']; ?>">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header d-flex align-items-center justify-content-between px-3" id="heading-<?php echo $detalle['id']; ?>">
                                                    <button class="accordion-button collapsed flex-grow-1" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse-<?php echo $detalle['id']; ?>" aria-expanded="false"
                                                        aria-controls="collapse-<?php echo $detalle['id']; ?>">
                                                        <label for="" class="form-label me-2">Atributo:</label>
                                                        <input type="text" class="form-control" value="<?php echo $detalle['nombre']; ?>" style="width: 50%;"
                                                            onclick="event.stopPropagation();" oninput="actualizarAtributo(this.value, <?php echo $detalle['id']; ?>)">
                                                    </button>
                                                    <button class="btn btn-outline-success ms-2 me-3 btn-icon"
                                                        type="button"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Agregar un componente"
                                                        onclick="addComponente(<?php echo $detalle['id']; ?>, '<?php echo $detalle['nombre']; ?>')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <line x1="12" y1="5" x2="12" y2="19" />
                                                            <line x1="5" y1="12" x2="19" y2="12" />
                                                        </svg>
                                                    </button>

                                                    <button class="btn btn-outline-danger ms-2 me-3 btn-icon" type="button" title="Borrar atributo"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        onclick="borrarAtributo(<?php echo $detalle['id']; ?>,'<?php echo $detalle['nombre']; ?>')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <line x1="4" y1="7" x2="20" y2="7" />
                                                            <line x1="10" y1="11" x2="10" y2="17" />
                                                            <line x1="14" y1="11" x2="14" y2="17" />
                                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                        </svg>
                                                    </button>
                                                </h2>

                                                <div id="collapse-<?php echo $detalle['id']; ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo $detalle['id']; ?>" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div id="resComponentes<?php echo $detalle['id']; ?>">
                                                            <?php
                                                            //$componentes = model('componentesAtributosProductoModel')->findAll();
                                                            $componentes = model('componentesAtributosProductoModel')->where('id_atributo', $detalle['id'])->findAll();
                                                            ?>
                                                            <p class="text-primary">Componentes</p>
                                                            <?php if (!empty($componentes)): ?>
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    <?php foreach ($componentes as $detalle): ?>
                                                                        <span class="badge rounded-pill btn-outline-primary d-flex align-items-center p-3 fs-5"
                                                                            style="min-width: 150px; max-width: 250px; justify-content: space-between;"
                                                                            id="badge<?php echo $detalle['id']; ?>">
                                                                            <?php echo $detalle['nombre']; ?>
                                                                            <button type="button" class="btn-close ms-2" aria-label="Close"
                                                                                onclick="eliminarBadge(<?php echo $detalle['id']; ?>, '<?php echo $detalle['nombre']; ?>')">
                                                                            </button>
                                                                        </span>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            <?php endif; ?>

                                                            <?php if (empty($componentes)): ?>
                                                                <p class="text-orange text-center">Noy hay components asociados </p>
                                                            <?php endif; ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3"></div>
                                    <?php endforeach ?>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <?= $this->include('layout/footer') ?>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="adicionarAtributo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">Adicionar atributo</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="cancelar()"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <label for="" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombreAtributo" onkeyup="validarAtributo()">
                                    <span id="nombreAtributoError" class="text-danger"></span>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-success" onclick="crearAtributo()">Aceptar</button>
                            <button type="button" class="btn btn-outline-danger" onclick="cancelar()">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="addComponentes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="">Adicionar un componente</h1>
                            <button type="button" class="btn-close" onclick="cancelarCrearComponente()" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <span id="atributoComponente"></span>
                            </div>
                            <div class="row">
                                <label for="" class="form-label"></label>
                                <input type="text" class="form-control" id="nombreComponente">
                                <input type="hidden" id="idAtributo">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" onclick="crearComponente()">Aceptar</button>
                            <button type="button" class="btn btn-danger" onclick="cancelarCrearComponente()">Cancelar </button>
                        </div>
                    </div>
                </div>
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

            <script>
                function limpiar() {
                    document.getElementById('buscarAtri').value = ""
                    document.getElementById('buscarAtri').focus()
                }
            </script>

            <script>
                async function buscarAtributo(valor) {
                    // Si el campo está vacío, limpiar resultados y salir
                    if (valor.trim() === "") {
                        document.getElementById("resultadoAtributos").innerHTML = "";
                        return;
                    }

                    try {
                        // Realizar la petición al servidor
                        const response = await fetch("<?= base_url('producto/searchAtributo') ?>", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                query: valor
                            }) // Enviar la búsqueda en JSON
                        });

                        const resultado = await response.json(); // Esperar la respuesta JSON

                        if (resultado.response == 'success') {
                            // Mostrar los resultados en el contenedor
                            document.getElementById("resList").innerHTML = resultado.atributos;
                        } else {
                            document.getElementById("resList").innerHTML = "<p class='text-orange'>No se encontraron resultados</p>";
                        }
                    } catch (error) {
                        console.error("Error en la búsqueda:", error);
                    }
                }
            </script>


            <script>
                async function borrarAtributo(id, nombre) {
                    // Confirmación con SweetAlert2
                    const confirmacion = await Swal.fire({
                        title: "¿Estás seguro?",
                        html: `El atributo: <span style="color: orange; font-weight: bold;">${nombre}</span> será eliminado permanentemente.`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#2FB344",
                        cancelButtonColor: "#D63939",
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar"
                    });


                    // Si el usuario cancela, detener ejecución
                    if (!confirmacion.isConfirmed) {
                        return;
                    }

                    try {
                        // Enviar petición al servidor
                        const response = await fetch("<?= base_url('producto/deleteAtributo') ?>", {
                            method: "DELETE",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                id: id
                            }) // Enviar el ID en JSON
                        });

                        const resultado = await response.json(); // Esperar la respuesta JSON

                        if (resultado.response == "success") {
                            // Eliminar el elemento del DOM si la eliminación fue exitosa
                            document.getElementById('accordion' + resultado.id).remove();
                            sweet_alert_centrado('success', 'Atributo eliminado')
                        } else {
                            Swal.fire("Error", "No se pudo eliminar el atributo.", "error");
                        }
                    } catch (error) {
                        console.error("Error en la petición:", error);
                        Swal.fire("Error", "Ocurrió un problema al eliminar el atributo.", "error");
                    }
                }
            </script>



            <script>
                function cancelarCrearComponente() {
                    document.getElementById('nombreComponente').value = "";
                    $('#addComponentes').modal('hide');
                }
            </script>

            <script>
                async function eliminarBadge(id, nombre) {
                    const confirmacion = await Swal.fire({
                        title: "¿Estás seguro de eliminar ? " + nombre,
                        text: "Esta acción no se puede deshacer.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar"
                    });

                    if (!confirmacion.isConfirmed) {
                        return; // Si el usuario cancela, no hace nada
                    }

                    try {
                        const response = await fetch("<?= base_url('producto/deleteComponente') ?>", {
                            method: "DELETE",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                id: id
                            }) // Enviar el ID en JSON
                        });


                        const resultado = await response.json(); // Esperar la respuesta JSON

                        if (resultado.response = "success") {
                            // Eliminar el elemento del DOM
                            document.getElementById('badge' + resultado.id).remove();

                            // Mostrar alerta de éxito
                            sweet_alert_centrado('success', 'componente eliminando')
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: "No se pudo eliminar el componente.",
                                icon: "error"
                            });
                        }
                    } catch (error) {
                        console.error("Error en la petición:", error);
                        Swal.fire({
                            title: "Error",
                            text: "Ocurrió un problema con la eliminación.",
                            icon: "error"
                        });
                    }
                }
            </script>



            <script>
                async function crearComponente() {
                    let nombreComponente = document.getElementById('nombreComponente').value;
                    let idAtributo = document.getElementById('idAtributo').value;

                    if (!nombreComponente || !idAtributo) {
                        alert("Por favor, completa todos los campos.");
                        return;
                    }

                    let datos = {
                        nombre: nombreComponente,
                        idAtributo: idAtributo
                    };

                    try {
                        let respuesta = await fetch("<?= base_url('producto/crearComponente') ?>", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(datos)
                        });


                        let resultado = await respuesta.json();

                        console.log(resultado.response)

                        if (resultado.response == 'success') {
                            document.getElementById('nombreComponente').value = ""
                            document.getElementById('resComponentes' + resultado.id).innerHTML = resultado.componentes
                            $('#addComponentes').modal('hide');
                            sweet_alert_centrado('success', 'Componente creado')
                        }
                        if (resultado.response == 'exists') {
                            document.getElementById('nombreComponente').value = "";
                            $('#addComponentes').modal('hide');
                            sweet_alert_centrado('warning', 'Componente existe')
                        }

                    } catch (error) {
                        console.error("Hubo un error:", error);
                        alert("Error al conectar con el servidor.");
                    }
                }
            </script>


            <script>
                function addComponente(idAtributo, nombreAtributo) {
                    document.getElementById('idAtributo').value = idAtributo
                    document.getElementById('atributoComponente').innerHTML = "Atributo: " + nombreAtributo;
                    $('#addComponentes').modal('show');

                    // Espera a que el modal se haya mostrado completamente y luego coloca el foco en el input
                    $('#addComponentes').on('shown.bs.modal', function() {
                        $('#nombreComponente').focus();
                    });
                }
            </script>

            <script>
                function cancelar() {
                    document.getElementById('nombreAtributo').value = '';
                    document.getElementById('nombreAtributoError').innerHTML = '';

                    $('#adicionarAtributo').modal('hide');
                }
            </script>

            <script>
                function agregarAtributo() {
                    var myModal = new bootstrap.Modal($('#adicionarAtributo')[0]);
                    myModal.show();

                    $('#adicionarAtributo').on('shown.bs.modal', function() {
                        $('#nombreAtributo').focus();
                    });
                }
            </script>



            <script>
                async function crearAtributo() {
                    try {
                        // Obtener valores del formulario (ajústalo según tu HTML)
                        const nombre = document.getElementById("nombreAtributo").value;

                        // Verificar que los campos no estén vacíos
                        if (!nombre) {
                            document.getElementById('nombreAtributoError').innerHTML = "Completa el campo"
                            return;
                        }

                        // Crear el objeto con los datos
                        const data = {
                            nombre: nombre
                        };

                        // Enviar la solicitud al backend
                        const response = await fetch('<?= base_url('producto/addAtributo') ?>', {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify(data)
                        });

                        // Procesar la respuesta
                        if (!response.ok) {
                            throw new Error("Error al crear el atributo");
                        }

                        const result = await response.json(); // Convertir la respuesta a JSON

                        if (result.response == 'success') {

                            // Opcional: limpiar campos después de la creación
                            document.getElementById("nombreAtributo").value = "";
                            sweet_alert_centrado('success', 'Registro creado');
                            $('#adicionarAtributo').modal('hide');
                            cancelar();
                            document.getElementById('resList').innerHTML = result.atributos;

                        }
                        if (result.response == 'exists') {
                            sweet_alert_centrado('warning', 'Registro existente')
                        }

                    } catch (error) {
                        console.error("Error:", error);
                        alert("Ocurrió un error al crear el atributo.");
                    }
                }
            </script>

            <script>
                async function validarAtributo() {
                    try {
                        // Obtener el valor del input
                        let valor = $('#nombreAtributo').val().trim();

                        document.getElementById('nombreAtributoError').innerHTML = "";

                        // Crear el objeto con los datos
                        const data = {
                            valor: valor
                        };

                        // Enviar la solicitud al backend
                        const response = await fetch('<?= base_url('producto/validarAtributo') ?>', {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify(data)
                        });

                        // Verificar si la respuesta es válida
                        if (!response.ok) {
                            throw new Error("Error en la respuesta del servidor");
                        }

                        const result = await response.json(); // Convertir la respuesta a JSON


                        if (result.response == 'true') { // Suponiendo que el backend devuelve { success: true/false }
                            document.getElementById('nombreAtributoError').innerHTML = "El atributo ya existe ";
                        }

                    } catch (error) {
                        console.error("Error:", error);
                        alert("Ocurrió un error al crear el atributo.");
                    }
                }
            </script>

            <script>
                async function actualizarAtributo(valor, id) {
                    try {
                        // Obtener el valor del input

                        // Crear el objeto con los datos
                        const data = {
                            valor: valor,
                            id: id
                        };

                        // Enviar la solicitud al backend
                        const response = await fetch('<?= base_url('producto/actualizarAtributo') ?>', {
                            method: "PUT",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify(data)
                        });

                        // Verificar si la respuesta es válida
                        if (!response.ok) {
                            throw new Error("Error en la respuesta del servidor");
                        }

                        const result = await response.json(); // Convertir la respuesta a JSON


                        if (result.response == 'true') { // Suponiendo que el backend devuelve { success: true/false }
                            document.getElementById('nombreAtributoError').innerHTML = "El atributo ya existe ";
                        }

                    } catch (error) {
                        console.error("Error:", error);
                        alert("Ocurrió un error al crear el atributo.");
                    }
                }
            </script>



</body>


</html>