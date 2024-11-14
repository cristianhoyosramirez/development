<table class="table table-hover table-striped">
    <thead class="table-dark">
        <tr>
            <td scope="col">Fecha factura</td>
            <td scope="col">Numero factura</td>
            <td scope="col">Valor pago </td>
            <td scope="col">Medio de pago</td>
        </tr>
    </thead>
    <tbody>

        <?php foreach ($movimientos as $detalle) { ?>
            <tr>
                <td><?php echo $detalle['fechafactura_forma_pago'] ?></th>
                <td><?php echo $detalle['numerofactura_venta'] ?></td>
                <td><?php echo  "$".number_format($detalle['valorfactura_forma_pago'], 0, ",", ".") ?></td>
                <td><?php echo $detalle['nombreforma_pago'] ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>