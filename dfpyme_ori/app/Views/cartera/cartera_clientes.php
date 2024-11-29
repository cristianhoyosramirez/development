<?php foreach ($clientes as $detalle) { ?>
    <?php $facturas = model('facturaVentaModel')->facturas($detalle['nitcliente']); ?>

    <?php foreach ($facturas as $detalle_factura) { ?>
        <tr>

            <td><?php echo $detalle_factura['nitcliente']; ?></td>
            <td><?php echo $detalle_factura['nombre_cliente']; ?></td>
            <td><?php echo $detalle_factura['descripcionestado']; ?></td>
            <td><?php echo $detalle_factura['numerofactura_venta']; ?></td>
            <td><?php echo $detalle_factura['fechalimitefactura_venta']; ?></td>
            <td><?php echo $detalle_factura['fecha_factura_venta']; ?></td>
            <td><?php echo "$" . number_format($detalle_factura['valor_factura'], 0, ",", "."); ?></td>
            <td><?php echo "$" . number_format($detalle_factura['saldo'], 0, ",", "."); ?></td>
            <td><a class="btn btn-primary btn-icon " title="Imprimir copia " onclick="imprimir_duplicado_factura(<?php echo $detalle_factura['id'] ?>)">
                    <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                        <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                        <rect x="7" y="13" width="10" height="8" rx="2" />
                    </svg></a>
                <a class="btn bg-muted-lt btn-icon " title="Ver detalle" onclick="detalle_de_factura(<?php echo $detalle_factura['id'] ?> )"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="12" cy="12" r="2" />
                        <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                    </svg></a>
                <a class="btn bg-green-lt btn-icon " title="Realizar pago " onclick="abonos_a_cartera(<?php echo $detalle_factura['nitcliente'] ?>,<?php echo $detalle_factura['idestado'] ?> )"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                        <path d="M12 3v3m0 12v3" />
                    </svg></a>
              
               
                <a onclick="abono_credito(<?php echo $detalle_factura['id'] ?>)" class="btn btn-primary  btn-icon"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                        <path d="M12 3v3m0 12v3" />
                    </svg> </a>
            </td>
        </tr>
    <?php } ?>

    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td class="table-primary">VALOR FACTURAS </td>
        <td class="table-primary"><?php $valor_facturas = model('facturaVentaModel')->valor_facturas_cliente($detalle['nitcliente']);
                                    echo "$" . number_format($valor_facturas[0]['valor_facturas'], 0, ",", ".")   ?></td>
        <td class="table-warning">ABONOS</td>
        <td class="table-warning"><?php $saldo = model('facturaVentaModel')->valor_saldo_cliente($detalle['nitcliente']);
                                    echo "$" . number_format($valor_facturas[0]['valor_facturas'] - $saldo[0]['saldo_facturas'], 0, ",", ".")   ?></td>
        <td class="table-danger">SALDO </td>
        <td class="table-danger"><?php $saldo = model('facturaVentaModel')->valor_saldo_cliente($detalle['nitcliente']);
                                    echo "$" . number_format($saldo[0]['saldo_facturas'], 0, ",", ".")   ?></td>
    </tr>

<?php } ?>
<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>

<tr>
    <td></td>
    <td></td>
    <td></td>
    <td class="table-primary">TOTAL FACTURAS </td>

    <td class="table-primary">
        <?php $total_facturas = model('facturaVentaModel')->selectSum('valor_factura')->where('saldo > 0 ')->find(); echo "$".number_format($total_facturas[0]['valor_factura'], 0, ",", ".")   ?>
    </td>
    
    <td class="table-warning">ABONOS</td>
    <td class="table-warning"><?php $saldo = model('facturaVentaModel')->selectSum('saldo')->where('saldo > 0 ')->find(); echo "$".number_format($total_facturas[0]['valor_factura']-$saldo[0]['saldo'], 0, ",", ".") ?></td>
    <td class="table-danger"> SALDO </td>
    <td class="table-danger"><?php echo "$".number_format($saldo[0]['saldo'], 0, ",", ".") ?></td>
</tr>