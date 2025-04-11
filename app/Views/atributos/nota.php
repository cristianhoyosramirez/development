<?php 
foreach ($atributos as $detalle): 
    $nombreAtributo = model('atributosProductoModel')->select('nombre')->where('id', $detalle['id_atributo'])->first();
    $componentes = model('atributosDeProductoModel')->getComponentes($id_tabla_producto, $detalle['id_atributo']);
?>

    <p style="margin: 2px 0;">
        <span style="color: green; font-weight: bold;"><?php echo $nombreAtributo['nombre']; ?></span>
        <?php if (!empty($componentes)): ?>
            (<span style="color: black;"><?php echo implode(', ', array_column($componentes, 'nombre')); ?></span>)
        <?php else: ?>
            (<span style="color: red;">No hay componentes disponibles</span>)
        <?php endif; ?>
    </p>

<?php endforeach; ?>

