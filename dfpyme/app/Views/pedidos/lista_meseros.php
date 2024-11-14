<div class="row">
    <?php foreach ($meseros as $detalle) : ?>
    <div class="col-6 col-sm-4 col-lg-3 col-xl-2 col-xxl-3">
        <div class="card card_mesas text-white bg-red-lt cursor-pointer" onclick="pedido_mesa('<?php echo $detalle['fk_mesa'] ?>','<?php echo $detalle['nombre_mesa'] ?>')">
            <div class="row">
                <div class="col-3">
                    <span class="avatar">
                        <img src="<?php echo base_url(); ?>/Assets/img/ocupada.png" width="110" height="32" alt="Macondo" class="navbar-brand-image">
                    </span>
                </div>
                <div class="col-9">
                    <div class="text-truncate text-center">
                        <strong class="text-truncate text-center small"><?php echo $detalle['nombre'] ?></strong><br>
                        <strong class="text-truncate text-center small"><?php echo $detalle['nombresusuario_sistema'] ?></strong><br>
                        <strong class="text-truncate text-center small"><?php echo "$ ".number_format($detalle['valor_total']+$detalle['propina'], 0, ",", ".") ?></strong><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>
