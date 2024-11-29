<select class="form-select" aria-label="Default select example">
<?php foreach ($ciudad as $detalle){?>

    <option value="<?php echo $detalle['idciudad']?>"><?php echo $detalle['nombreciudad'] ?></option>

    <?php }?>
</select>