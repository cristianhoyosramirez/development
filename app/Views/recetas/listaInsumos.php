<?php $unidadesMedida = model('unidadMedidaModel')->findAll(); ?>

<?php foreach ($productos as $detalleInsumo):

    $unidadMedida = model('productoFabricadoModel')->GetMedida($detalleInsumo['codigointernoproducto']);
?>

    <tr id="rowInsumo<?php echo $detalleInsumo['codigointernoproducto']; ?>" style="cursor: pointer;">
        <td><?php echo $detalleInsumo['codigointernoproducto']; ?></td>
        <td><?php echo $detalleInsumo['nombreproducto']; ?></td>
        <td>
            <select name="" class="form-select" onchange="cambiarUnidadMedida(this, '<?php echo $detalleInsumo['codigointernoproducto']; ?>')">
                <!-- Opción seleccionada por defecto -->
                <option value="<?php echo $unidadMedida[0]['idunidad_medida']; ?>" selected>
                    <?php echo $unidadMedida[0]['medida']; ?>
                </option>

                <!-- Filtramos el array para eliminar la opción ya seleccionada -->
                <?php foreach ($unidadesMedida as $detalleUnidad): ?>

                    <option
                        value="<?php echo $detalleUnidad['idvalor_unidad_medida']; ?>">
                        <?php echo $detalleUnidad['descripcionvalor_unidad_medida']; ?>
                    </option>

                <?php endforeach; ?>
            </select>


        </td>
        <td><?php echo $detalleInsumo['valorventaproducto']; ?></td>
        <td>
            <button class="btn btn-outline-success btn-icon btn-sm" onclick="selectInsumo(<?= $detalleInsumo['codigointernoproducto']; ?>, '<?= htmlspecialchars($detalleInsumo['nombreproducto'], ENT_QUOTES, 'UTF-8'); ?>')"><!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg></button>
        </td>

    </tr>

<?php endforeach ?>