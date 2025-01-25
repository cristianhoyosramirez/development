<?php foreach ($productos as $keyProducto): ?>
    <tr>
        <td><?php echo $keyProducto['codigointernoproducto']; ?></td>
        <td class="nombre-producto"><?php echo $keyProducto['nombreproducto']; ?></td>
        <td>
            <input type="text" class="form-control input-inventario"
                value="<?php echo $keyProducto['cantidad_inventario']; ?>">
        </td>
        <?php
        //$registro = model('inventarioFisicoModel')->select('cantidad_inventario_fisico')->where('codigointernoproducto', $keyProducto['codigointernoproducto'])->first(); 
        $registro = model('inventarioFisicoModel')->existeProducto($keyProducto['codigointernoproducto']);
        ?>
        <?php if (empty($registro)): ?>
            <td>
                <input type="text" class="form-control input-inventario" id="<?php echo $keyProducto['id']; ?>" onkeyup="ingresarInv(this.value,<?php echo $keyProducto['id']; ?> )">
            </td>
            <td>
                <input type="text" class="form-control input-inventario" id="diferencia<?php echo $keyProducto['id'] ?>" readonly>
            </td>
        <?php endif ?>
        <?php if (!empty($registro)): ?>
            <td>
                <input type="text" value="<?php echo $registro[0]['cantidad_inventario_fisico'];  ?>" class="form-control input-inventario" id="<?php echo $keyProducto['id']; ?>" onkeyup="ingresarInv(this.value,<?php echo $keyProducto['id']; ?> )">
            </td>
            <?php $diferencia = model('inventarioModel')->conteo_manual($keyProducto['codigointernoproducto']); ?>
            <td>
                <input type="text" class="form-control input-inventario" id="diferencia<?php echo $keyProducto['id'] ?>" value="<?php echo $diferencia[0]['diferencia']; ?>" readonly>
            </td>
        <?php endif ?>
    </tr>
<?php endforeach; ?>