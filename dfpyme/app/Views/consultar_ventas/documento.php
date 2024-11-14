<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title text-center text-primary w-100"><?php echo $titulo ?> </h3>
    </div>
    <div class="list-group list-group-flush list-group-hoverable" style="max-height: 300px; overflow-y: auto;">




        <?php foreach ($documentos as $detalle) : ?>

            <?php

            $nit_cliente = model('facturaElectronicaModel')->select('nit_cliente')->where('id', $detalle['id_factura'])->first();
        
            ?>
            <div class="list-group-item">
                <div class="row align-items-center">
                    <div class="col-12 col-md-2">
                        <span class="d-block text-truncate">Nit cliente: <?php echo $nit_cliente['nit_cliente'] ?></span>
                    </div>
                    <div class="col-12 col-md-2">
                        <span class="d-block text-truncate">Fecha: <?php echo $detalle['fecha'] ?></span>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="row">

                            <div class="col">
                                <span class="d-block text-truncate">N° factura: <?php echo $detalle['documento'] ?></span>
                            </div>

                        </div>

                    </div>

                    <div class="col-12 col-md-2">
                        <span class="d-block text-truncate">Valor: <?php echo "$ " . number_format($detalle['total_documento'], 0, ",", ".") ?></span>
                    </div>

                    <?php $pdf = model('facturaElectronicaModel')->select('pdf_url')->where('id', $detalle['id_factura'])->first();
                    ?>

                    <!--         <?php #if ($pdf['pdf_url'] != "") { 
                                    ?>
                        <div class="col-12 col-md-2">

                            <a href="<?php #echo $pdf['pdf_url'];  
                                        ?>" target="_blank" class="cursor-pointer">
                                <img title="Descargar pdf " src="<?php #echo base_url() 
                                                                    ?>/Assets/img/pdf.png" width="40" height="40" />
                            </a>
                        </div>
                    <?php  #} 
                    ?>
                    <?php #if (empty($pdf['pdf_url'])) { 
                    ?>
                        <div class="col-12 col-md-2">


                        </div>
                    <?php  #} 
                    ?> -->

                    <?php if ($id_estado == 2) { ?>
                        <div class="col-12 col-md-2">
                            <span class="d-block text-truncate">Saldo : <?php echo "$ " . number_format($detalle['saldo'], 0, ",", ".") ?></span>
                        </div>
                    <?php } ?>
                    <div class="col-12 col-md-2  mt-2 mt-md-0">


                        <div class="row">
                            <div class="col">
                                <div class="dropdown">
                                    <button class="btn btn-outline-success dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        Accion
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <?php if ($id_estado == 2) {  ?>
                                            <li><a class="dropdown-item" onclick="pagar_factura(<?php echo $detalle['id'] ?>,<?php echo $detalle['saldo'] ?>)"> <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                                        <path d="M12 3v3m0 12v3" />
                                                    </svg> Pagar</a></li>
                                        <?php } ?>
                                        <li>
                                            <!--<a class="dropdown-item" href="#">  Download SVG icon from http://tabler-icons.io/i/printer
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                                                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                                                    <rect x="7" y="13" width="10" height="8" rx="2" />
                                                </svg> Imprimir</a> -->
                                            <?php if ($id_estado == 8) { ?>
                                                <a class="dropdown-item" href="#" onclick="imprimir_electronica(<?php echo $detalle['id_factura'] ?>)"> <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                                                        <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                                                        <rect x="7" y="13" width="10" height="8" rx="2" />
                                                    </svg> Imprimir</a>
                                            <?php  } ?>


                                        </li>
                                        <li><a class="dropdown-item" onclick="detalle_f_e(<?php echo $detalle['id_factura'] ?>)"> <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                                                <!-- Download SVG icon from http://tabler-icons.io/i/list-details -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M13 5h8" />
                                                    <path d="M13 9h5" />
                                                    <path d="M13 15h8" />
                                                    <path d="M13 19h5" />
                                                    <rect x="3" y="4" width="6" height="6" rx="1" />
                                                    <rect x="3" y="14" width="6" height="6" rx="1" />
                                                </svg> Detalle</a></li>
                                        <li><a class="dropdown-item" onclick="sendInvoice(<?php echo $detalle['id'] ?>)"> <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                                                <!-- Download SVG icon from http://tabler-icons.io/i/arrows-down-up -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <line x1="17" y1="3" x2="17" y2="21" />
                                                    <path d="M10 18l-3 3l-3 -3" />
                                                    <line x1="7" y1="21" x2="7" y2="3" />
                                                    <path d="M20 6l-3 -3l-3 3" />
                                                </svg> Trasmitir </a></li>
                                        <?php if ($id_estado == 2) {  ?>
                                            <li><a class="dropdown-item" onclick="sendInvoice(<?php echo $detalle['id'] ?>)"> <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                                                    <!-- Download SVG icon from http://tabler-icons.io/i/arrows-down-up -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <line x1="17" y1="3" x2="17" y2="21" />
                                                        <path d="M10 18l-3 3l-3 -3" />
                                                        <line x1="7" y1="21" x2="7" y2="3" />
                                                        <path d="M20 6l-3 -3l-3 3" />
                                                    </svg> Pdf </a></li>
                                        <?php } ?>

                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>





<?php if ($id_estado == 2) {  ?>
    <p class="text-end text-primary h3">TOTAL SALDO : <?php echo $saldo ?> </p>
<?php } ?>
<?php if ($id_estado != 2) {  ?>
    <p class="text-end text-primary h3">TOTAL DOCUMENTOS : </p>
<?php } ?>

<!-- Modal -->
<div class="modal fade" id="pagar_factura" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 w-100" id="staticBackdropLabel">Abonar a factura </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-3">
                        <label for="">Saldo </label>
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control" id="valor">
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-3">
                        <label for="">Efectivo </label>
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control">
                    </div>
                </div> <br>
                <div class="row">
                    <div class="col-3">
                        <label for="">Transacción </label>
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control">
                    </div>
                </div> <br>
                <div class="row">
                    <div class="col-3">
                        <label for="">Cambio </label>
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Abonar </button>
                <button type="button" class="btn btn-outline-danger">Cancelar </button>
            </div>
        </div>
    </div>
</div>



<script>
    function pagar_factura(id_factura, saldo) {

        $("#pagar_factura").modal("show");

    }
</script>


