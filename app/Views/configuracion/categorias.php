<?php  $categorias=model('categoriasModel')->findAll(); ?>
<select class="form-select" id="categoria_product" name="categoria_producto"
    onchange="sub_categorias_productos(this.value)"
    onkeyup="saltar_creacion_producto(event,'marca_producto')">
    <option value="">Seleccione una categoria</option>
    <?php foreach ($categorias as $valor) { ?>
        <option value="<?php echo $valor['codigocategoria'] ?>">
            <?php echo $valor['nombrecategoria'] ?>
        </option>
    <?php } ?>
</select>