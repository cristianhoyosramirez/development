<div class="card">
    <div class="card-body">
        <p class="text-center text-primary h4">Mesas</p>
        <div class="row">
            <?php foreach ($mesas as $detalle) { ?>
                <div class="col-sm-4 col-md-3 col-xs-6">
                    <?php if ($detalle['estado'] == 0) { ?> <!-- La mesa no tiene pedido -->

                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="cursor-pointer">
                                        <div class="text-white bg-green-lt border border-4 rounded container " onClick="mesa_pedido(<?php echo $detalle['id'] ?>);">
                                            <div class="row">
                                                <p class=" text-center text-white-90"> Mesa: <span id="nombre_mesa"><?php echo $detalle['nombre'] ?></span></p>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <img src="<?php echo base_url(); ?>/Assets/img/libre.png" height="5" class="img-fluid mx-auto d-block cursor-pointer w-20">
                                                </div>
                                            </div><br>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?php } ?>
                    <?php if ($detalle['estado'] == 1) { ?>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="cursor-pointer">

                                        <div class="text-white bg-red-lt border border-4 rounded container" onClick="mesa_pedido(<?php echo $detalle['id'] ?>);">
                                            <div class="row">
                                                <p class=" text-center text-white-90"> Mesa: <span id="nombre_mesa"><?php echo $detalle['nombre'] ?></span></p>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <img src="<?php echo base_url(); ?>/Assets/img/libre.png" height="5" class="img-fluid mx-auto d-block cursor-pointer w-20">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <span class="text-center "> Mesero: <span id="nombre_mesa"><?php echo $detalle['nombresusuario_sistema'] ?></span></span>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <a title="Imprimir prefactura" class="btn bg-cyan text-white w-100 btn-icon" onclick="impresion_prefactura(event,<?php echo $detalle['id'] ?>)">
                                                        Prefactura
                                                    </a>
                                                </div>
                                                <div class="col">
                                                    <form action="<?= base_url('producto/facturar_pedido') ?>" method="POST">
                                                        <input type="hidden" value="<?php echo $detalle['id'] ?>" name="id_mesa_facturacion">
                                                        <button title="Facturar " class="btn btn-success w-100 btn-icon" type="submit">
                                                            <?php echo "$" . number_format($detalle['valor_pedido'], 0, ",", "."); ?>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div><br>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                    <?php } ?>
                    <?php if ($detalle['estado'] == 2) { ?>
                        <div class="bg-azure-lt">
                            <p class="mb-1 text-center"><?php echo $detalle['nombre'] ?></p>
                            <img src="<?php echo base_url(); ?>/public/assets/images/reservado.png" height="40" class="img-fluid mx-auto d-block cursor-pointer">
                            <div class="row">
                                <button type="button" class="btn btn-primary">Primary</button>
                            </div>
                            <div onClick="id_mesa(<?php echo $detalle['id'] ?>);" class="text-center cursor-auto cursor-pointer">
                                <p class="text-dark">Reservada</p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>




        </div>
    </div>
</div>