<table class="table">
    <thead>
        <tr >
            <td width="50%" > <p>Factura de Venta</p>   </th>
            <td width="50%" > <p><?php echo $fecha_factura ."  " . date("g:i a", strtotime($hora_factura)); ?></p></th>
        </tr>
        <tr>
            <td>Fecha de factura</th>
            <td><?php echo $numero_factura; ?></th>
        </tr>
        <tr>
            <td>Nit cliente</th>
            <td><?php echo $nit_cliente; ?></th>
        </tr>
        
    </thead>
    <tbody>
        <tr class="table-dark">
            <td>Codigo interno</th>
            <td>Descripción</th>
            <td>Cantidad</th>
            <td>Valor unitario</th>
            <td>Total</th>
        </tr>
        <?php foreach ($productos as $detalle) {
            $valor_venta = $detalle['total'] / $detalle['cantidadproducto_factura_venta']; ?>

            <tr>
                <th><?php echo $detalle['codigointernoproducto']  ?></th>
                <td><?php echo $detalle['nombreproducto']  ?></td>
                <td><?php echo $detalle['cantidadproducto_factura_venta']  ?></td>
                <td><?php echo "$" . number_format($valor_venta, 0, ',', '.')  ?></td>
                <td><?php echo "$" . number_format($detalle['total'], 0, ',', '.')  ?></td>
            </tr>
        <?php } ?>
        <th>Total factura</th>
        <th><?php echo "$".number_format($total_factura)?></th>
    </tbody>
</table>