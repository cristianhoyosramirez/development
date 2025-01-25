<ul class="horizontal-list">
    <?php foreach ($mesas as $detalle) : ?>
        <?php $tiene_pedido = model('pedidoModel')->pedido_mesa($detalle['id']); ?>
        <?php if (empty($tiene_pedido)) : ?>
            <li>
                <div id="UpdateMesa<?php echo $detalle['id'] ?>">
                    <div id="mesa<?php echo $detalle['id'] ?>" class="cursor-pointer card card_mesas text-white bg-green-lt" onclick="pedido('<?php echo $detalle['id'] ?>','<?php echo $detalle['nombre'] ?>')">
                        <div class="row">
                            <div class="col-3">
                                <span class="avatar">
                                    <img src="<?php echo base_url(); ?>/Assets/img/libre.png" width="110" height="32" alt="Macondo" class="navbar-brand-image">
                                </span>
                            </div>
                            <div class="col">
                                <div class="text-center">
                                    <strong><?php echo $detalle['nombre'] ?></strong>
                                    <span id="datos"></span>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </li>
        <?php endif ?>

        <?php if (!empty($tiene_pedido)) : ?>

            <?php $notaPedido = model('configuracionPedidoModel')->select('notaPedido')->first(); ?>

            <li>
                <div class="cursor-pointer card card_mesas text-white bg-red-lt" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo $tiene_pedido[0]['nota_pedido'] ?>" id="mesa<?php echo $detalle['id'] ?>" onclick="pedido_mesa('<?php echo $detalle['id'] ?>','<?php echo $detalle['nombre'] ?>')" style="height: auto;">
                    <div class="row">
                        <div class="col-3">
                            <span class="avatar">
                                <img src="<?php echo base_url(); ?>/Assets/img/ocupada.png" width="110" height="32" alt="Macondo" class="navbar-brand-image">
                            </span>
                        </div>
                        <div class="col">
                            <div class="text-center">
                                <strong style="font-size: 12px;">
                                    <?php echo $detalle['nombre'] ?></strong>
                            </div>
                            <div class="text-center"><strong style="font-size: 12px;"><?php echo "$" . number_format($tiene_pedido[0]['valor_total'] + $tiene_pedido[0]['propina'], 0, ",", ".") ?></strong></div>
                            <?php if ($notaPedido['notaPedido'] == 'f'): ?>
                                <div class="text-center"><strong style="font-size: 12px; height: 1em; overflow: hidden;"><?php echo substr($tiene_pedido[0]['nombresusuario_sistema'], 0, 10) ?>...</strong></div>
                            <?php endif ?>
                            <?php if ($notaPedido['notaPedido'] == 't'): ?>
                                <div class="text-center"><strong style="font-size: 12px; height: 1em; overflow: hidden;"><?php echo substr($tiene_pedido[0]['nota_pedido'], 0, 10) ?>...</strong></div>
                            <?php endif ?>

                        </div>
                    </div>
                </div>
            </li>
        <?php endif ?>

    <?php endforeach ?>
</ul>