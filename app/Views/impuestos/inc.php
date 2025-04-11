<select class="form-select" id="valor_ico" name="valor_ico">
    <?php foreach ($inc as $detalle) { ?>
        <option value="<?php echo $detalle['id_ico'] ?>"> <?php echo $detalle['valor_ico'] ?> </option>
    <?php } ?>
</select>