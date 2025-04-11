<?php foreach ($productos as $detalle): ?>
    <tr>
        <td><?php echo $detalle['fecha']; ?></td>
        <td><?php echo $detalle['numerodocumento']; ?></td>
        <td><?php echo $detalle['descripcionestado']; ?></td>
        <td><?php echo $detalle['codigo']; ?></td>
        <td><?php echo $detalle['nombreproducto']; ?></td>
        <td><?php echo $detalle['cantidad']; ?></td>
        <td><?php echo number_format($detalle['costo'] / $detalle['cantidad'], 0, '.', '.'); ?></td>
        <td><?php echo number_format($detalle['valor_unitario'], 0, '.', '.'); ?></td>
        <td><?php echo number_format($detalle['total'] - ($detalle['ico'] + $detalle['iva']), 0, '.', '.'); ?></td> <!-- Base -->
        <td><?php echo number_format($detalle['iva'], 0, '.', '.'); ?></td>
        <td><?php echo number_format($detalle['ico'], 0, '.', '.'); ?></td>
        <td><?php echo number_format($detalle['total'], 0, '.', '.'); ?></td>
    </tr>
<?php endforeach; ?>