<tbody id="productosconteo">
    
        <?php foreach ($inventario_sistema as $KeyInventarioFisico): ?>
            <?php
            $producto = model('inventarioModel')->conteo_manual($KeyInventarioFisico['codigointernoproducto']);
            $costo = model('productoModel')->CostoProducto($KeyInventarioFisico['codigointernoproducto']);

            ?>
            <?php if (!empty($producto)): ?>
                <tr>
                    <td><?php echo $producto[0]['codigointernoproducto']; ?></td>
                    <td><?php echo $producto[0]['nombreproducto']; ?></td>
                    <td><?php echo $producto[0]['cantidad_inventario']; ?></td>
                    <td><?php echo $producto[0]['cantidad_inventario_fisico']; ?></td>
                    <td><?php echo $producto[0]['diferencia']; ?></td>
                    <td><?php echo number_format($costo[0]['precio_costo'], 0, ",", "."); ?></td>
                    <td><?php echo number_format($costo[0]['precio_costo'] * $producto[0]['cantidad_inventario'], 0, ",", "."); ?></td>
                    <td><?php echo number_format($costo[0]['valorventaproducto'] * $producto[0]['cantidad_inventario'], 0, ",", "."); ?></td>
                </tr>
            <?php else: ?>
                <?php $dato_producto = model('inventarioModel')->getProducto($KeyInventarioFisico['codigointernoproducto']); ?>
                <tr>
                    <td><?php echo $KeyInventarioFisico['codigointernoproducto']; ?></td>
                    <td><?php echo $dato_producto[0]['nombreproducto']; ?></td>
                    <td>0</td> <!-- Cantidad conteo -->
                    <td>0</td> <!-- Cantidad sistema -->
                    <td>0</td>
                    <td><?php echo number_format($dato_producto[0]['precio_costo'], 0, ",", "."); ?></td> <!--Costo unidad -->
                    <td><?php echo number_format($dato_producto[0]['precio_costo'] * 0, 0, ",", "."); ?></td> <!--Costo total -->
                    <td><?php echo number_format($dato_producto[0]['valorventaproducto'] * 0, 0, ",", "."); ?></td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
</tbody>