<?php $user_session = session(); ?>
<?= $this->extend('template/consultas_reportes') ?>
<?= $this->section('title') ?>
BUSCAR PEDIDOS BORRADOS
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>
<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">

        </div>
    </div>
</div>

<div class="container">
    <p class="text-center h2 text-primary">PEDIDOS ELIMINADOS</p>
</div>
<br>
<div class="card container">

    <div class="card-body">

        <div class="row">
            <div class="col-3">
                <label for="" class="form-label">Período</label>
                <select class="form-select" id="periodo_fechas" onchange="periodo(this.value)">
                    <option value=""></option>
                    <option value="1">Desde el inicio </option>
                    <option value="2">Fecha </option>
                    <option value="3">Periodo </option>
                </select>
            </div>

            <div class="col-3" id="inicial" style="display:none">
                <label for="" class="form-label">Fecha inicial </label>
                <input type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" name="fecha_inicial" id="fecha_inicial">
                <span class="text-danger" id="error_fecha_inicial"></span>
            </div>
            <div class="col-3" id="final" style="display:none">
                <label for="" class="form-label">Fecha final </label>
                <input type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" name="fecha_final" id="fecha_final">
            </div>

            <div class="col-3">
                <label for="" class="form-label text-light">Período</label>
                <button type="submit" class="btn btn-outline-primary" onclick="buscar_pedidos_borrados()">Buscar </button>
            </div>

        </div>
        <br>


        <table class="table" id="pedidos_borrados">
            <thead class="table-dark">
                <tr>
                    <td scope="col">Fecha eliminación</th>
                    <td scope="col">Hora eliminación</th>
                    <td scope="col">Fecha creación</th>
                    <td scope="col">Número pedido</th>
                    <td scope="col">Valor pedido</th>
                    <td scope="col">Usuario creación</th>
                    <td scope="col">Usuario eliminación</th>
                    <td scope="col">Accion
                        </th>
                </tr>
            </thead>
            <tbody>



            </tbody>
        </table>

        <div class="row">
            <div class="col-10"></div>
            <div class="col-2">

                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="chart-sparkline chart-sparkline-square" id="sparkline-orders"></div>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium ">
                                    Total
                                </div>
                                <div class="text-muted ">
                                    <span id="total_pedidos"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>
</div>

<input type="hidden" value="<?php echo base_url() ?>" id="url">
<!-- Modal -->
<div class="modal fade" id="productos_borrados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Productos borrados </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="resultado_productos_borrados"></div>
            </div>

        </div>
    </div>
</div>



<?= $this->endSection('content') ?>