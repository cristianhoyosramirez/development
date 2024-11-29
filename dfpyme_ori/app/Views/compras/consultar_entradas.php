<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
Entradas de productos
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-1" style="margin-bottom: -10px; padding-bottom: 0;">
                <div class="card-title">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="text-center text-primary">CONSULTAR COMPRAS </p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
               <!--  <p class="text-primary h3 ">Buscar por :</p>
                <div class="row">
                    <div class="col-4">
                        <div class="accordion" id="accordionExample1">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="fecha">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        Fecha
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="fecha">
                                    <div class="accordion-body">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="" class="form-label">Inicial</label>
                                                <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" id="fecha_inicial">
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">Final</label>
                                                <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" id="fecha_final">
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col text-end">
                                                <a href="#" class="btn btn-outline-success w-100">
                                                    Buscar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="accordion" id="accordionExample2">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Proveedor
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo">
                                    <div class="accordion-body">
                                        <div class="row mb-3">
                                            <label for="" class="form-label">Proveedores</label>
                                            <select name="" id="proveedor" class="form-select">
                                                <option value=""></option>
                                                <?php foreach ($proveedores as $proveedores): ?>
                                                    <option value="<?php echo $proveedores['id'] ?>"><?php echo $proveedores['nombrecomercialproveedor'] ?></option>

                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="row ">
                                            <div class="col text-end">
                                                <a href="#" class="btn btn-outline-success w-100">
                                                    Buscar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="accordion" id="accordionExample3">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Fecha y proveedor
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree">
                                    <div class="accordion-body">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="" class="form-label">Inicial</label>
                                                <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" id="fecha_inicial">
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">Final</label>
                                                <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" id="fecha_final">
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label text-light">Proveedor</label>
                                                <select name="" id="fecha_proveed" class="form-select">
                                                    <option value=""></option>
                                                    <?php foreach ($provee as $detalle_proveedor): ?>
                                                        <option value="<?php echo $detalle_proveedor['id']; ?>"><?php echo $detalle_proveedor['nombrecomercialproveedor']; ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col text-end">
                                                <a href="#" class="btn btn-outline-success w-100">
                                                    Buscar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> -->

                <br>
                <div class="table-responsive">
                    <input type="hidden" id="url" value="<?php echo base_url() ?>">
                    <input type="hidden" value="general" id="buscar_por">
                    <table id="consulta_compras" class="table">
                        <thead class="table-dark">
                            <td>Fecha
                                </th>
                            <td>Proveedor</th>
                            <td>Nit</th>
                            <td>Valor </th>
                            <td>Usuario</th>
                            <td>Accion</th>

                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modal_compra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle de compras </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="" id="detalle_productos_compra"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>



<script>
    function imprimir_compra(id) {

        var url = document.getElementById("url").value;

        $.ajax({
            data: {
                id

            },
            url: url + "/comanda/imprimir_compra",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {


                    sweet_alert_centrado('success','Impresion de compra')


                }
            },
        });

    }
</script>


<script>
    function detalle_compra(id) {

        var url = document.getElementById("url").value;

        $.ajax({
            data: {
                id

            },
            url: url + "/inventario/consultar_productos_compra",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {


                    $('#detalle_productos_compra').html(resultado.productos)
                    $('#modal_compra').modal('show')


                }
            },
        });

    }
</script>


<?= $this->endSection('content') ?>