<div class="cursor-pointer card card_mesas text-white bg-red-lt" onclick="pedido_mesa('<?php echo $id_mesa ?>','<?php echo $nombre ?>')" style="height: auto;">
    <div class="row">
        <div class="col-3">
            <span class="avatar">
                <img src="<?php echo base_url(); ?>/Assets/img/ocupada.png" width="110" height="32" alt="Macondo" class="navbar-brand-image">
            </span>
        </div>
        <div class="col">
            <div class="text-center">
                <strong style="font-size: 12px;"><?php echo $nombre ?></strong>
            </div>
            <div class="text-center"><strong style="font-size: 12px;"><?php echo "$" . number_format($valor_pedido + $propina, 0, ",", ".") ?></strong></div>
            <div class="text-center"><strong style="font-size: 12px; height: 1em; overflow: hidden;"><?php echo substr($usuario, 0, 10) ?>...</strong></div>
        </div>
    </div>
</div>