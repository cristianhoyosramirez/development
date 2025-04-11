<select class="form-select" aria-label="Default select example">
    <?php foreach ($rubros as $detalle) { ?>
        <option value="<?php echo $detalle['id'] ?>"><?php  echo $detalle['nombre_rubro']?></option>
    <?php } ?>
</select>