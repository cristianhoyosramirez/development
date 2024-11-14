<table class="table">
    <thead class="table-dark">
        <tr>
            <td scope="col">Fecha venta</td>
            <td scope="col">Hora venta</td>
            <td scope="col">Número de factura </td>
            <td scope="col">Valor factura</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($efectivo as $detalle) { ?>
            <tr>
                <td><?php echo $detalle['fechafactura_forma_pago'] ?></th>
                <td><?php echo date($detalle['hora']) ?></td>
                <td><?php echo $detalle['numerofactura_venta'] ?></td>
                <td><?php echo "$" . number_format($detalle['valorfactura_forma_pago'], 0, ",", ".") ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<p class="text-end h2 text-primary">TOTAL: <?php  echo "$" . number_format($total, 0, ",", ".")?> </p>