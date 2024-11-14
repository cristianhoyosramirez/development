<div class="row">
    <input type="hidden" id="id_edicion_producto" name="id_edicion_producto" value="<?php  echo $id_tabla_producto ?>" >
    <div class="col-3">
        <p><?php echo $nombre_producto ?></p>
    </div>
    <div class="col-2">
        <p> <?php echo $valor_unitario ?></p>
    </div>
    <div class="col-2">
        <input type="text text-center" class="form-control" value="<?php echo $cantidad ?>" id="cantidad_producto" name="cantidad_producto">
    </div>
    <div class="col">
        <p><?php echo $valor_total ?></p>
    </div>

</div>