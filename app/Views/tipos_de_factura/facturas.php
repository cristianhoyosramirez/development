<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
DFpyme
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <div class="d-flex justify-content-end mb-3">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#documento">
                            Agregar documento
                        </button>
                    </div>
                    <table id="mesas" class="table  table-hover">
                        <thead class="table-dark">
                            <tr>
                                <td>Id</td>
                                <td>Documento</td>
                                <td>Factura</td>
                                <td>Consultas y reportes </td>
                                <td>Orden</td>
                                <td class="text-center">Acciones</td>
                            </tr>
                        </thead>
                        <tbody>
                            <input type="hidden" value="<?php echo base_url() ?>" id="url">
                            <?php foreach ($tipo_factura as $detalle) { ?>
                                <tr>
                                    <td><?php echo $detalle['idestado'] ?></td>
                                    <td><input type="text" value="<?php echo $detalle['descripcionestado'] ?>" class="form-control" id=nombre<?php echo $detalle['idestado'] ?>></td>
                                    <td><select class="form-select" name="estado" id="estado<?php echo $detalle['idestado'] ?>">
                                            <?php if ($detalle['estado'] == "t") : ?>
                                                <option value="true" selected>Habilitado</option>
                                                <option value="false">No habilitado</option>
                                            <?php endif ?>
                                            <?php if ($detalle['estado'] == "f") : ?>
                                                <option value="true">Habilitado</option>
                                                <option value="false" selected>No habilitado</option>
                                            <?php endif ?>

                                        </select></td>
                                    <td><select class="form-select" name="consulta" id="consulta<?php echo $detalle['idestado'] ?>">
                                            <?php if ($detalle['consulta'] == "t") : ?>
                                                <option value="true" selected>Habilitado</option>
                                                <option value="false">No habilitado</option>
                                            <?php endif ?>
                                            <?php if ($detalle['consulta'] == "f") : ?>
                                                <option value="true">Habilitado</option>
                                                <option value="false" selected>No habilitado</option>
                                            <?php endif ?>

                                        </select></td>
                                    <td><input type="text" value="<?php echo $detalle['orden'] ?>" class="form-control" id="orden<?php echo $detalle['idestado'] ?>"></td>
                                    <td class="text-end">



                                        <button type="submit" class="btn btn-outline-success" onclick="actualizar_estados(<?php echo $detalle['idestado'] ?>)">Actualizar</button>
                                        <button type="submit" class="btn btn-outline-danger">Eliminar</button>


                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="documento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <label for="" class="form-label">Documento</label>
                        <input type="text" class="form-control" id="newDocumento">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-outline-success">Guardar </button>
            </div>
        </div>
    </div>
</div>


<script>
    async function verComponentes(id) {
        try {
            const baseUrl = "<?php echo base_url(); ?>"; // Obtiene el base_url desde PHP
            const url = `${baseUrl}/configuracion/AddDocument`; // Construye la URL dinámica

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: id
                })
            });


            if (!response.ok) {
                throw new Error(`Error en la solicitud: ${response.statusText}`);
            }

            const data = await response.json();

            if (data.success === true) {
                
               

    

                
            }



        } catch (error) {
            console.error('Hubo un problema al actualizar el producto:', error);
            alert('No se pudo actualizar el producto. Por favor, intenta de nuevo.');
        }
    }
</script>

<script>
    function actualizar_estados(id_estado) {

        var url = document.getElementById("url").value;
        var descripcion = document.getElementById("nombre" + id_estado).value;
        // var descripcion = $("#nombre" + id_estado).val();
        var estado = document.getElementById("estado" + id_estado).value;
        var orden = document.getElementById("orden" + id_estado).value;
        var consulta = document.getElementById("consulta" + id_estado).value;


        $.ajax({
            data: {
                id_estado,
                descripcion,
                estado,
                orden,
                consulta
            },
            url: url + "/" + "configuracion/actualizar_estado",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    $('#propina_pesos').val(0)
                    $('#propina_del_pedido').val(0)

                    sweet_alert_start('success', 'Registro actualizado ');


                }
            },
        });

    }
</script>



<?= $this->endSection('content') ?>