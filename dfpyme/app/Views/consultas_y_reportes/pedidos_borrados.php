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
<br>
<!--Sart row-->
<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Salones</a></li>
                    <li class="breadcrumb-item"><a href="#">Mesas</a></li>
                    <li class="breadcrumb-item"><a href="#">Usuarios</a></li>
                    <li class="breadcrumb-item"><a href="#">Empresa</a></li>
                </ol>
            </nav>
        </div>

        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">BUSCAR PEDIDOS BORRADOS EN UN PERIODO DE TIEMPO </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
        <div class="card-body">

            <form class="row g-3" id="formulario_movimiento_caja" action="<?= base_url('consultas_y_reportes/pedidos_borrados') ?>" method="POST">
                <div class="col-3">

                    <input type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" name="fecha_inicial">
                </div>
                <div class="col-3">

                    <input type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" name="fecha_final">
                </div>

                <div class="col-3">
                    <button type="submit" class="btn btn-outline-primary">Buscar </button>
                </div>


            </form>
            <br>

        </div>
    </div>
</div>
<div class="card container">

    <br>
    <div class="card-body">
        <input type="hidden" id="url" value="<?php echo base_url() ?>">
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td scope="col">Fecha eliminación</th>
                    <td scope="col">Hora eliminación</th>
                    <td scope="col">Fecha creación</th>
                    <td scope="col">Pedido </th>
                    <td scope="col">Valor pedido </th>
                    <td scope="col">Usuario cración </th>
                    <td scope="col">Usuario eliminación </th>
                    <td scope="col">Acción </th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($pedidos_borrados as $detalle) { ?>
                    <tr>
                        <td><?php echo $detalle['fecha_eliminacion'] ?></th>
                        <td><?php echo $detalle['hora_eliminacion'] ?></th>
                        <td><?php echo $detalle['fecha_creacion'] ?></th>
                        <td><?php echo $detalle['numero_pedido'] ?></th>
                        <td><?php echo "$" . number_format($detalle['valor_pedido']) ?></th>
                            <?php
                            $nombre_eliminacion = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $detalle['usuario_eliminacion'])->first();
                            $nombre_creacion = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $detalle['id_mesero'])->first();


                            ?>
                        <td><?php echo $nombre_creacion['nombresusuario_sistema'] ?></td>
                        <td><?php echo $nombre_eliminacion['nombresusuario_sistema'] ?></td>
                        <td><button type="button" class="btn btn-outline-success" onclick="ver_productos_borrados(<?php echo $detalle['numero_pedido'] ?>)">Productos elimindados </button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <br>

    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="productos_borrados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="detalle">Productos borrados de pedido</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="todos_los_productos_borrados"></div>
            </div>
            
        </div>
    </div>
</div>

<script>
    function ver_productos_borrados(valor) {
        var url = document.getElementById("url").value;

        $.ajax({
            url: url + "/" + "inventario/productos_borrados",
            data: {
                valor
            },
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    $('#todos_los_productos_borrados').html(resultado.productos)
                    $("#productos_borrados").modal("show");



                }
            },
        });
    }
</script>

<?= $this->endSection('content') ?>