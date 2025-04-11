<div class="row g-3">
    <div class="col-sm-7">
        <label for="">Mesa origen </label>
        <input type="hidden" value="<?php echo $id_mesa_origen ?>" id="id_mesa_origen">
        <input type="text" class="form-control" id="mesa_origen" value="<?php echo $nombre_mesa ?>" readonly>
    </div>
    <div class="col-sm">
        <label for="">Mesa destino </label>
        <select class="form-select select2" name="mesa_destino" id="mesa_destino">
            <?php foreach ($mesas as $detalle) { ?>
                <?php if ($detalle['id'] != $id_mesa_origen) {  ?>
                    <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['nombre'] ?> </option>
                <?php } ?>
            <?php }
            ?>
        </select>
    </div>
</div>