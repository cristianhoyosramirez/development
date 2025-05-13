<?php foreach ($productos as $detalleRecetas): ?>

    <tr id="rowInsumo<?php echo $detalleRecetas['codigointernoproducto']; ?>" onclick="detalleReceta(<?php echo $detalleRecetas['codigointernoproducto']; ?>)" style="cursor: pointer;">
        <td><?php echo $detalleRecetas['codigointernoproducto']; ?></td>
        <td><?php echo $detalleRecetas['nombreproducto']; ?></td>
        <td><?php echo $detalleRecetas['precio_costo']; ?></td>
        <td><?php echo $detalleRecetas['valorventaproducto']; ?></td>
        <td>
            <button class="btn btn-outline-primary btn-sm" onclick="detalleReceta(<?php echo $detalleRecetas['codigointernoproducto']; ?>)">Ver</button>
        </td>

    </tr>

<?php endforeach ?>