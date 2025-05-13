<?php if (!empty($sub_categorias)) : ?>
    <select class="form-select" aria-label="Default select example">
        <?php foreach ($sub_categorias as $detalle) { ?>

            <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['nombre'] ?> </option>
        <?php } ?>
    </select>
<?php endif ?>

<?php if (empty($sub_categorias)) : ?>

    <p>No hay sub categorias creadas </p>

<?php endif ?>