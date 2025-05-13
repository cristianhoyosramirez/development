<?php
$contador = 1; // Inicializa el contador


foreach ($productos as $detalleInsumo):
    $unidadMedida = model('productoFabricadoModel')->GetMedida($detalleInsumo['codigointernoproducto']);
?>

    <tr id="insumo<?php echo $detalleInsumo['id']; ?>">
        <td><?php echo $contador; ?></td> <!-- Muestra el número de ítem -->
        <td><?php echo $detalleInsumo['codigointernoproducto']; ?></td>
        <td><?php echo $detalleInsumo['nombreproducto']; ?></td>
        <td><?php echo  $unidadMedida[0]['medida']; ?></td>
        <td><input
                type="text"
                value="<?php echo $detalleInsumo['cantidad']; ?>"
                id="cantDeInsumo<?php echo $detalleInsumo['id']; ?>"
                class="form-control text-center"
                maxlength="5"
                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').replace(/^0+/, '')"
                onkeyup="actualizarCantidad(this.value, '<?php echo $detalleInsumo['id']; ?>')">



        </td>
        <td><?php echo $detalleInsumo['precio_costo']; ?></td>
        <td id="costoTotal<?php echo $detalleInsumo['id']; ?>"><?php echo ($detalleInsumo['precio_costo'] * $detalleInsumo['cantidad']); ?></td>
        <td><button type="button" class="btn btn-outline-danger btn-icon" onclick="borrarInsumo(<?php echo $detalleInsumo['id']; ?>)"><!-- Download SVG icon from http://tabler-icons.io/i/trash -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <line x1="4" y1="7" x2="20" y2="7" />
                    <line x1="10" y1="11" x2="10" y2="17" />
                    <line x1="14" y1="11" x2="14" y2="17" />
                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                </svg></button></td>

    </tr>

<?php
    $contador++; // Incrementa el contador
endforeach;
?>