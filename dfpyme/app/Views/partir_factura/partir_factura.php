<?php foreach ($productos as $detalle) { ?>
    <tr>
        <td><?php echo $detalle['codigointernoproducto'] ?></td>
        <td><?php echo $detalle['nombreproducto'] ?></td>
        <td><?php echo "$" . number_format($detalle['valor_unitario'], 0, ",", ".") ?></td>
        <!--<td><?php #echo $detalle['cantidad_producto'] 
                ?></td>-->
        <td>
            <div class="input-group mb-1">
                <input type="text" class="form-control" value="<?php echo  $detalle['cantidad_producto']  ?>" readonly>
                <input type="number" class="form-control" value="<?php echo  $detalle['cantidad_producto']  ?>" onkeyup="actualizar_cantidad_partir_factura(event, this.value,<?php echo $detalle['id'] ?>,<?php echo  $detalle['cantidad_producto']  ?>)">
            </div>
            <span id="error_cantidad_partir_factura" style="color:#FF0000"></span>
            <span id="cantidad_parcial_partir_factura" style="color:green"></span>
        </td>

        <td>
            <p id="valor_producto_partir_factura"><?php echo "$" . number_format($detalle['valor_total'], 0, ",", ".") ?></p>
        </td>
    </tr>
<?php } ?>