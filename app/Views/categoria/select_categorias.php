<select class="form-select" aria-label="Default select example">
    <?php foreach ($categorias as $detalle) { ?>

        <option value="<?php echo $detalle['codigocategoria'] ?>" <?php if ($detalle['codigocategoria'] == $codigo_categoria) : ?>selected <?php endif; ?>><?php echo $detalle['nombrecategoria'] ?> </option>

    <?php } ?>
</select>