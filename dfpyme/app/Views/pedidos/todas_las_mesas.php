
<ul class="horizontal-list">
        <?php foreach ($mesas as $detalle) : ?>
            <?php $tiene_pedido = model('pedidoModel')->pedido_mesa($detalle['id']); ?>
            <?php if (empty($tiene_pedido)) : ?>
                <li>
                    <div class="cursor-pointer card card_mesas text-white bg-green-lt" >
                        <div class="row " onclick="pedido('<?php echo $detalle['id'] ?>','<?php echo $detalle['nombre'] ?>')">
                            <div class="col-3">
                                <span class="avatar">
                                    <img src="<?php echo base_url(); ?>/images/productos/producto.png" width="110" height="32" alt="Macondo" class="navbar-brand-image">
                                </span>
                            </div>
                            <div class="col">
                                <div class="text-truncate">
                                    <strong><?php echo $detalle['nombre'] ?></strong>
                                </div>
                                
                            </div>

                        </div>
                    </div>
                </li>
            <?php endif ?>

            <?php if (!empty($tiene_pedido)) : ?>

                <li>

                    <div class="cursor-pointer card card_mesas text-white bg-red-lt" onclick="pedido_mesa('<?php echo $detalle['id'] ?>','<?php echo $detalle['nombre']?>')">
                        <div class="row">
                            <div class="col-3">
                                <span class="avatar">
                                    <img src="<?php echo base_url(); ?>/images/productos/producto.png" width="110" height="32" alt="Macondo" class="navbar-brand-image">
                                </span>
                            </div>
                            <div class="col">
                                <div class="text-truncate">
                                    <strong><?php echo $detalle['nombre'] ?></strong>
                                </div>
                                <div class="text-truncate"><strong><?php echo "$" . number_format($tiene_pedido[0]['valor_total'], 0, ",", ".") ?></strong></div>
                                <div class="text-truncate"><strong><?php #echo $tiene_pedido[0]['nombresusuario_sistema'] ?></strong></div>
                            </div>

                        </div>
                    </div>

                </li>
            <?php endif ?>

        <?php endforeach ?>
    </ul>
