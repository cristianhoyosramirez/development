<select class="form-select" aria-label="Default select example" name="municipios" id="municipios">
 
<?php foreach ($municipios as $detalle){?>

    <option value="<?php echo $detalle['code']?>"><?php echo $detalle['nombre'] ?></option>

    <?php }?>
</select>