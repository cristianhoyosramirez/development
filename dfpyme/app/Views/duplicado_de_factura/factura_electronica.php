<?php $user_session = session(); ?>
<?= $this->extend('template/consultas_reportes') ?>
<?= $this->section('title') ?>
DUPLICADO DE FACTURA
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>
<!--Sart row-->
<style>
    /* Agregar estilos CSS para el desplazamiento */
    .scrolling-table {
        max-height: 300px;
        overflow-y: scroll;
    }
</style>
<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Ventas</a></li>
                    <li class="breadcrumb-item"><a href="#">Facturación</a></li>
                    <li class="breadcrumb-item"><a href="#">Copia de factura</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">Facturas electrónicas </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>


<div class="container">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="container">

               
                        <input type="hidden" value="<?php echo base_url() ?>" id="url">
                        <table class="table table-striped table-hover" id="facturas_electronicas">
                            <thead class="table-dark">
                                <tr>
                                    <td>Número </th>
                                    <td>Cliente </th>
                                    <td>ID cliente</th>
                                    <td>Fecha</th>
                                    <td>Hora</th>
                                    <td>Estado</th>
                                    <td>Neto</th>
                                    <td>Accion</th>

                                </tr>
                            </thead>
                            <tbody>
                               
                                <?php foreach ($facturas as $detalle) {
                                    $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $detalle['nit_cliente'])->first(); ?>
                                    <tr>
                                        <td><?php echo $detalle['numero'] ?></td>
                                        <td><?php echo $nombre_cliente['nombrescliente'] ?></td>
                                        <td><?php echo $detalle['nit_cliente'] ?></td>
                                        <td><?php echo $detalle['fecha'] ?></td>
                                        <td><?php $hora = $detalle['hora']; // Convertir la hora a un formato AM/PM
                                            $hora_am_pm = date("h:i A", strtotime($hora));

                                            echo $hora_am_pm; ?></td>
                                        <td><?php if ($detalle['id_status'] == 1) {
                                                echo "PENDIENTE";
                                            } else if ($detalle['id_status'] == 2) {
                                                echo "FIRMADO";
                                            } ?></td>

                                        <td><?php echo "$ " . number_format($detalle['total'], 0, ",", ".") ?></td>
                                        <td>
                                            <div class="row">
                                                <div class="col">
                                                    <a href="#" class="btn btn-outline-success w-100" onclick="imprimir(<?php echo $detalle['id'] ?>)">
                                                        Imprimir
                                                    </a>
                                                </div>
                                                <div class="col">
                                                    <a href="#" class="btn btn-outline-secondary w-100" onclick="detalle_f_e(<?php echo $detalle['id'] ?>)">
                                                        Detalle
                                                    </a>
                                                </div>


                                            </div>
                                        </td>

                                    </tr>
                                <?php  } ?>
                                <!-- Repetir filas según sea necesario -->
                            </tbody>
                        </table>
                    
                </div>

            </div>
        </div>
    </div>

</div>
</div>

<!-- Modal -->
<div class="modal fade" id="detalle_factura_electronica" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Detalle de factura electrónica</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="tabla_f_e"></div>
                <p class="text-end h3" id="total"></p>
            </div>
           
        </div>
    </div>
</div>




<script>
    function imprimir(id_factura) {

        let url = document.getElementById("url").value;

        $.ajax({
            data: {
                id_factura,
            },
            url: url + "/" + "pedidos/imprimir_factura_electronica",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {






                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 500,
                        timerProgressBar: false,

                    })

                    Toast.fire({
                        icon: 'success',
                        title: 'Impresion de prefactura electrónica '
                    })

                    /**
                     * Aca llamo a la funcion sweet alert y se le pasan los parametros.
                     */
                    sweet_alert('success', 'Nota de producto actualizada ');
                }
            },
        });
    }
</script>


<?= $this->endSection('content') ?>
<!-- end row -->