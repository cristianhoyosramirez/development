<!-- <?php foreach ($pedidos as $detalle) { ?>
    <input type="hidden" id="url" value="<?= base_url() ?>">
    <input type="hidden" value="<?php echo $detalle['id'] ?>" id="numero_pedido_salvar">

    <br>
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="divide-y">
                            <div>
                                <div class="row">

                                    <div class="col">
                                        <div class="text-truncate">
                                            <table class="table table-borderless">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <td>Ubicación</td>
                                                        <td>Numero de pedido </td>
                                                        <td>Fecha y hora </td>
                                                        <td>Valor pedido</td>
                                                        <td>Acciones</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><?php echo $detalle['nombre'] ?></td>
                                                        <td><?php echo $detalle['id'] ?></td>
                                                        <td><?php echo $detalle['fecha_creacion'] ?></td>
                                                        <td><?php echo "$" . number_format($detalle['valor_total'], 0, ',', '.') ?></td>
                                                        <td>
                                                            <div class="row g-1">
                                                                <div class="col">
                                                                    <a href="#" title="Eliminar pedido" onclick="eliminacion_de_pedido(<?php echo $detalle['id'] ?>)" class="btn btn-danger w-100 btn-icon">
                                                                        Eliminar
                                                                    </a>
                                                                </div>
                                                                <div class="col">
                                                                    <form action="<?= base_url('comanda/re_imprimir_comanda') ?>" method="POST">
                                                                        <input type="hidden" value="<?php echo $detalle['id'] ?>" name="numero_de_pedido_reimprimir_comanda">
                                                                        <button type="submit" title="reimprimir comanda" class="btn btn-warning w-100 btn-icon">
                                                                            Comanda
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                                <div class="col">
                                                                    <a href="#" onclick="detalle_pedido_facturar(<?php echo $detalle['id'] ?>)" title="Detalle del pedido" class="btn btn-secondary w-100 btn-icon">
                                                                        Detalle
                                                                    </a>
                                                                </div>
                                                                <div class="col">
                                                                    <form action="<?= base_url('pre_factura/imprimir') ?>" method="POST">
                                                                        <input type="hidden" value="<?php echo $detalle['id'] ?>" name="numero_de_pedido_pre_factura">
                                                                        <button type="submit" class="btn btn-success w-100 btn-icon" title="pre factura">
                                                                            Prefactura
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                                <div class="col">
                                                                    <form action="<?= base_url('pedido/facturar_pedido') ?>" method="POST">
                                                                        <input type="hidden" value="<?php echo $detalle['id'] ?>" name="numero_de_pedido_facturar">
                                                                        <button type="submit" title="Facturar pedido" class="btn btn-primary w-100 btn-icon">
                                                                            Facturar
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <?php if (!empty($detalle['nota_pedido'])) { ?>
                                                <span class="text-primary h3" >NOTAS: <?php echo $detalle['nota_pedido'] ?></span>
                                            <?php } ?>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php  } ?> -->



<?php foreach ($pedidos as $detalle) {  ?>

    <input type="hidden" id="url" value="<?= base_url() ?>">
    <input type="hidden" value="<?php echo $detalle['id'] ?>" id="numero_pedido_salvar">
    <tr>
        <td><?php echo $detalle['nombre'] ?></td>
        <td><?php echo $detalle['id'] ?> </td>
        <td><?php echo $detalle['nota_pedido'] ?> </td>
        <td><?php echo $detalle['fecha_creacion'] ?></td>
        <td><?php echo "$" . number_format($detalle['valor_total'], 0, ',', '.') ?></td>
        <td>
            <div class="row g-1">
                <div class="col">
                  
                    <form action="<?= base_url('pedido/pedido') ?>" method="POST">
                        <input type="hidden" value="<?php echo $detalle['id'] ?>" name="numero_de_pedido">
                        <button type="submit" title="reimprimir comanda" class="btn text-white bg-azure w-100 btn-icon">
                            Pedido
                        </button>
                    </form>
                </div>
                <div class="col">
                    <a href="#" title="Eliminar pedido" onclick="eliminacion_de_pedido(<?php echo $detalle['id'] ?>)" class="btn btn-danger w-100 btn-icon">
                        <!-- Download SVG icon from http://tabler-icons.io/i/trash -->
                        Eliminar
                    </a>
                </div>
                <div class="col">
                    <form action="<?= base_url('comanda/re_imprimir_comanda') ?>" method="POST">
                        <input type="hidden" value="<?php echo $detalle['id'] ?>" name="numero_de_pedido_reimprimir_comanda">
                        <button type="submit" title="reimprimir comanda" class="btn btn-warning w-100 btn-icon">
                            Comanda
                        </button>
                    </form>
                </div>


                <div class="col">
                    <a href="#" onclick="detalle_pedido_facturar(<?php echo $detalle['id'] ?>)" title="Detalle del pedido" class="btn btn-secondary w-100 btn-icon">
                        Detalle
                    </a>
                </div>
                <div class="col">
                    <form action="<?= base_url('pre_factura/imprimir') ?>" method="POST">
                        <input type="hidden" value="<?php echo $detalle['id'] ?>" name="numero_de_pedido_pre_factura">
                        <button type="submit" class="btn btn-success w-100 btn-icon" title="pre factura">
                            Prefactura
                        </button>
                    </form>
                </div>
                <div class="col">
                    <form action="<?= base_url('pedido/facturar_pedido') ?>" method="POST">
                        <input type="hidden" value="<?php echo $detalle['id'] ?>" name="numero_de_pedido_facturar">
                        <button type="submit" title="Facturar pedido" class="btn btn-primary w-100 btn-icon">
                            Facturar
                        </button>
                    </form>
                </div>
            </div>
            </div>
        </td>
    </tr>

<?php  } ?>