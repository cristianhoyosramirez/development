

<style>
    /* Add this to your CSS */
    .modal.full-height {
        height: 100vh;
        margin: 0;
    }
</style>



<!-- Modal -->
<div class="modal fade" id="lista_todas_las_mesas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="width: 100%; height: 100vh;">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Listado completo de todas las mesas </h1>
                <button type="button" class="btn-close" onclick="cerrar_modal_mesas()"></button>
            </div>
            <div class="my-1"></div>
            <div class="container">
             
                <div class="container">
                    <div class="row">
                        <!-- Tarjeta fija "Todas las mesas" -->
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                            <a href="#" id="cardtodas" class="card card-link" onclick="mesasConPedido()">
                                <div class="card-body">MESAS CON PEDIDO </div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                            <a href="#" id="cardtodas" class="card card-link" onclick="buscar_mesa_salon('todas')">
                                <div class="card-body">TODAS LAS MESAS </div>
                            </a>
                        </div>

                        <!-- Tarjetas dinámicas de los salones -->
                        <?php $salones = model('salonesModel')->findAll(); ?>

                        <?php foreach ($salones as $detalle_salon): ?>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                                <a href="#" id="card<?php echo $detalle_salon['id'] ?>" class="card card-link" onclick="buscar_mesa_salon(<?php echo $detalle_salon['id'] ?>)">
                                    <div class="card-body"><?php echo $detalle_salon['nombre'] ?></div>
                                </a>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-6 col-6 col-md-6">
                        <div class="input-icon">
                            <input type="text" value="" class="form-control form-control-rounded" id="buscar_mesa" placeholder="Buscar mesa" onkeyup="buscar_mesas(this.value)">
                            <span class="input-icon-addon">

                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                    <path d="M21 21l-6 -6" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-6 col-6 col-md-6">
                        <div class="input-icon">
                            <input type="text" value="" class="form-control form-control-rounded" id="buscar_mesero" placeholder="Buscar mesero " onkeyup="buscar_meseros(this.value)">
                            <span class="input-icon-addon">

                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                    <path d="M21 21l-6 -6" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div id="mesas_all">
                    <div id="listado_de_mesas"></div>

                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<!-- Add this script after including Bootstrap and jQuery -->
<script>
    $(document).ready(function() {
        $('#lista_todas_las_mesas').on('show.bs.modal', function() {
            $(this).addClass('full-height');
        });

        $('#lista_todas_las_mesas').on('hidden.bs.modal', function() {
            $(this).removeClass('full-height');
        });
    });
</script>