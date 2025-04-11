<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>



<?= $this->section('content') ?>
<input type="hidden" value="<?php echo base_url() ?>" id="url">
<div class="container">
    <div class="card">
        <!-- Button trigger modal -->

        <div class="card-header">
            <span class="text-center">Listado de proveedores </span>
        </div>
        <div class="card-body">

            <div class="row mb-3">
                <div class="d-flex justify-content-end mt-3"><button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Agregar proveedor
                    </button></div>
            </div>

            <table class="table">
                <thead class="table-dark" id="tablaProveedores">
                    <tr>
                        <td>Nit </td>
                        <td>Nombre </td>
                        <td>Teléfono </td>
                        <td>Direccion </td>
                        <td>Acción </td>
                    </tr>
                </thead>
                <tbody id="row_proveedores">
                    <?php foreach ($proveedores as $detalle): ?>

                        <tr id="fila-<?php echo $detalle['id']; ?>">
                            <td><?php echo $detalle['nitproveedor'] ?></th>
                            <td><?php echo $detalle['nombrecomercialproveedor'] ?></td>
                            <td><?php echo $detalle['telefonoproveedor'] ?></td>
                            <td><?php echo $detalle['direccionproveedor'] ?></td>
                            <td class="text-end">
                                <div class="row">
                                    <div class="col">
                                        <button onclick="editar_proveedor(<?php echo $detalle['id'] ?>)" class="btn btn-outline-success">Editar</button>&nbsp;
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_modal">Creación de proveedor </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="card">
                        <div class="container "><br>
                            <div class="row mb-3">
                                <div class="col mb-3">
                                    <label for="nit" class="form-label">Nit</label>
                                    <input type="text" id="nit" class="form-control" autofocus onkeyup="(saltar_factura_pos(event,'nombre'))">
                                </div>
                                <div class="col mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" id="nombre" class="form-control" onkeyup="(saltar_factura_pos(event,'direccion'))">
                                </div>
                                <div class="col mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" id="direccion" class="form-control" onkeyup="(saltar_factura_pos(event,'telefono'))">
                                </div>
                                <div class="col mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" id="telefono" class="form-control" onkeyup="(saltar_factura_pos(event,'crearBtn'))">
                                </div>

                                <div class="d-flex justify-content-end mt-3">
                                    <button class="btn btn-outline-red" onclick="cerrar_modal_creacion()">Cancelar</button>&nbsp;
                                    <button id="crearBtn" class="btn btn-outline-success">Crear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="editar_proveedor" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_modal">Editar proveedor </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="card">
                        <div class="container "><br>
                            <div class="row mb-3">
                                <input type="hidden" id="edicion_id">
                                <div class="col mb-3">
                                    <label for="nit" class="form-label">Nit</label>
                                    <input type="text" id="edicion_nit" class="form-control" autofocus onkeyup="(saltar_factura_pos(event,'nombre'))">
                                </div>
                                <div class="col mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" id="edicion_nombre" class="form-control" onkeyup="(saltar_factura_pos(event,'direccion'))">
                                </div>
                                <div class="col mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" id="edicion_direccion" class="form-control" onkeyup="(saltar_factura_pos(event,'telefono'))">
                                </div>
                                <div class="col mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" id="edicion_telefono" class="form-control" onkeyup="(saltar_factura_pos(event,'crearBtn'))">
                                </div>

                                <div class="d-flex justify-content-end mt-3">
                                    <button class="btn btn-outline-red" onclick="cerra_modal_edicion()">Cancelar</button>&nbsp;
                                    <button class="btn btn-outline-success" onclick="actualizar_proveedor()">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



<script>
    function actualizar_proveedor() {

        let url = document.getElementById("url").value;
        let id = document.getElementById("edicion_id").value;
        let nombre = document.getElementById("edicion_nombre").value;
        let nit = document.getElementById("edicion_nit").value;
        let telefono = document.getElementById("edicion_telefono").value;
        let direccion = document.getElementById("edicion_direccion").value;

        $.ajax({
            data: {
                id,
                nit,
                nombre,
                nit,
                telefono,
                direccion
            },
            url: url + "/" + "administracion_impresora/actualizar_proveedor",
            type: "post",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    sweet_alert_centrado('success', 'Cambios realizados')

                    // Limpiar los campos del formulario
                    $('#edicion_nit').val('');
                    $('#edicion_nombre').val('');
                    $('#edicion_direccion').val('');
                    $('#edicion_telefono').val('');
                    $('#edicion_id').val('');

                    // Crear un nuevo tr o actualizar el existente
                    var newRow = `
            <tr id="fila-${resultado.id}">
                <td>${resultado.nit}</td>
                <td>${resultado.nombre}</td>
                 <td>${resultado.telefono}</td>
                <td>${resultado.direccion}</td>
               
                <td class="text-end">
                    <div class="row">
                        <div class="col">
                            <button onclick="editar_proveedor(${resultado.id})" class="btn btn-outline-success">Editar</button>
                        </div>
                    </div>
                </td>
            </tr>`;

                    // Comprobar si ya existe una fila con el mismo ID
                    if ($('#fila-' + resultado.id).length) {
                        // Si existe, actualiza solo los td
                        $('#fila-' + resultado.id).html(`
                <td>${resultado.nit}</td>
                <td>${resultado.nombre}</td>
                <td>${resultado.telefono}</td>
                <td>${resultado.direccion}</td>
                
                 <td class="text-center">
                    <div class="row">
                        <div class="col">
                            <button onclick="editar_proveedor(${resultado.id})" class="btn btn-outline-success">Editar</button>
                        </div>
                    </div>
                </td>
            `);
                    } else {
                        // Si no existe, agrega la nueva fila
                        $('#miTabla tbody').append(newRow);
                    }

                    // Ocultar el modal de edición
                    $('#editar_proveedor').modal('hide');
                }
            }

        });



    }
</script>


<script>
    function cerrar_modal_creacion() {


        $('#nit').val('')
        $('#nombre').val('')
        $('#direccion').val('')
        $('#telefono').val('')
        $('#id').val('')
        $('#staticBackdrop').modal('hide');

    }
</script>
<script>
    function cerra_modal_edicion() {


        $('#edicion_nit').val('')
        $('#edicion_nombre').val('')
        $('#edicion_direccion').val('')
        $('#edicion_telefono').val('')
        $('#edicion_id').val('')
        $('#editar_proveedor').modal('hide');

    }
</script>

<script>
    function editar_proveedor(id) {

        let url = document.getElementById("url").value;

        $.ajax({
            data: {
                id
            },
            url: url + "/" + "administracion_impresora/editar_proveedor",
            type: "post",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    $('#edicion_nit').val(resultado.nit)
                    $('#edicion_nombre').val(resultado.nombre)
                    $('#edicion_direccion').val(resultado.direccion)
                    $('#edicion_telefono').val(resultado.telefono)
                    $('#edicion_id').val(resultado.id)
                    $('#editar_proveedor').modal('show');


                }
            },
        });



    }
</script>

<script>
    function crearTablaProveedores(proveedores) {
        const tabla = document.getElementById('row_proveedores'); // Selecciona el tbody de tu tabla
        tabla.innerHTML = ''; // Limpia el contenido anterior (si lo hubiera)

        proveedores.proveedores.forEach(detalle => {

            // Puedes realizar otras acciones aquí, como crear filas en la tabla
            const fila = document.createElement('tr');
            fila.id = `fila-${detalle.id}`;
            fila.innerHTML = `
        <td>${detalle.nitproveedor || ''}</td>
        <td>${detalle.nombrecomercialproveedor || ''}</td>
        <td>${detalle.telefonoproveedor || ''}</td>
        <td>${detalle.direccionproveedor || ''}</td>
        <td class="text-center">
            <div class="row">
                <div class="col">
                    <button onclick="editar_proveedor(${detalle.id})" class="btn btn-outline-success">Editar</button>
                </div>
            </div>
        </td>
    `;
            tabla.appendChild(fila);
        });
    }
</script>


<script>
    document.getElementById('crearBtn').addEventListener('click', function() {
        const nit = document.getElementById('nit').value;
        const nombre = document.getElementById('nombre').value;
        const direccion = document.getElementById('direccion').value;
        const telefono = document.getElementById('telefono').value;
        const url = document.getElementById('url').value;


        const data = {
            nit: nit,
            nombre: nombre,
            direccion: direccion,
            telefono: telefono
        };

        fetch(url + '/administracion_impresora/crear_proveedor', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Error al guardar en la base de datos');
                }
            })
            .then(data => {
                if (data.status === 'success') {
                    crearTablaProveedores(data);

                    sweet_alert_centrado('success', 'Proveedor creado')
                    // Aquí puedes redirigir o limpiar el formulario si lo deseas
                    document.getElementById('nit').value = "";
                    document.getElementById('nombre').value = "";
                    document.getElementById('direccion').value = "";
                    document.getElementById('telefono').value = "";
                    $('#staticBackdrop').modal('hide');
                } else {
                    alert('Ocurrió un error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al guardar los datos');
            });

    });
</script>


<?= $this->endSection('content') ?>