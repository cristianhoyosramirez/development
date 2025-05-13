<?php foreach ($productos as $detalle): ?>



    <tr>
        <td><?php echo $detalle['numero']; ?></td> <!--Factura -->
        <td><?php echo $detalle['fecha']; ?></td> <!--Fecha -->
        <td><?php echo $detalle['codigo']; ?></td> <!--Codigo -->
        <td><?php echo $detalle['nombreproducto']; ?></td> <!--Nombre producto  -->
        <td><?php echo $detalle['cantidad']; ?></td> <!--cantidad -->

        <!-- Costo base sin IVA unidad -->
        <td>
            <?php echo $detalle['costo']; ?>
        </td>



        <!-- Costo base sin IVA total -->
        <td>
            <?php echo number_format($detalle['costo'] * $detalle['cantidad'], 0, '.', '.'); ?>
        </td>

        <!-- Costo + IVA unidad -->
        <td>
            <?php

            $valor_base = $detalle['costo'];
            $iva_porcentaje = $detalle['iva'];
            $factor_iva = 1 + ($iva_porcentaje / 100);

            echo number_format($valor_con_iva = $valor_base * $factor_iva, 0, '.', '.');

            ?>
        </td>

        <!-- Costo + IVA total -->
        <td>
            <?php

            echo number_format(($valor_con_iva = $valor_base * $factor_iva)*$detalle['cantidad'], 0, '.', '.')

            ?>
        </td>

        <!-- valor venta base unidad  -->
        <td>
            <?php echo number_format($detalle['precio_unitario'], 0, '.', '.');
            ?>
        </td>

        <!-- Valor venta base total -->
        <td>
            <?php echo number_format(($detalle['precio_unitario'] * $detalle['cantidad']), 0, '.', '.');
            ?>
        </td>
        <!-- ICO 8 % total -->
        <td>

            <?php

            if ($detalle['icn'] == 0) { //Tiene IVA 
                echo number_format(0, 0, '.', '.');
            } else if ($detalle['icn'] == 8) { //No tiene IVA tiene impuesto al consumo 
                echo number_format(($detalle['neto'] - $detalle['precio_unitario']) * $detalle['cantidad'], 0, '.', '.');
            }
            ?>
        </td>
        <!-- IVA  5 % total -->
        <td>
            <?php
            if ($detalle['icn'] == 0) { // Tiene IVA
                if ($detalle['iva'] == 5) {
                    echo number_format(($detalle['neto'] - $detalle['precio_unitario']) * $detalle['cantidad'], 0, '.', '.');
                } else {
                    echo number_format(0, 0, '.', '.'); // Tiene IVA pero no es 5%
                }
            } else { // Tiene impuesto al consumo
                echo number_format(0, 0, '.', '.');
            }
            ?>
        </td>

        <!-- IVA  19 % total -->
        <td>
            <?php
            if ($detalle['ic'] == 0) { // Tiene IVA
                if ($detalle['iva'] == 19) {
                    echo number_format(($detalle['neto'] - $detalle['precio_unitario']) * $detalle['cantidad'], 0, '.', '.');
                } else {
                    echo number_format(0, 0, '.', '.'); // No es IVA 19, igual muestra 0
                }
            } else { // No tiene IVA, tiene impuesto al consumo
                echo number_format(0, 0, '.', '.');
            }
            ?>
        </td>


        <td>
            <?php echo number_format($detalle['total'], 0, '.', '.'); ?>
        </td>

    </tr>
<?php endforeach; ?>