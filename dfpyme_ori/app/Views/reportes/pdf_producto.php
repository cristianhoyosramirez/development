<style>
    table {
        width: 100%;
        margin: 30px auto;
        border-collapse: collapse;
    }

    thead {
        background-color: lightgray;
        color: black;
    }

    th,
    td {

        border: 1px solid #666666;
    }



    textarea {
        height: 10em;
        width: 45em;
        font-family: "oblique bold cursive";
        font-size: 80%;


    }
</style>

<table class="table table-borderless">
    <tbody>
        <tr>
            <th style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo $nombre_comercial ?></th>
            <th style="text-align:left; font: oblique bold 80% cursive; border:none "></th>
            <th style="text-align:left; font: oblique bold 80% cursive; border:none ">INFORME DE VENTAS POR PRODUCTO </th>
        </tr>
        <tr>
            <td style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo $nombre_juridico ?></td>
            <th style="text-align:left; font: oblique bold 80% cursive; border:none "></th>
            <th style="text-align:left; font: oblique bold 80% cursive; border:none "></th>
        </tr>
        <tr>
            <td style="text-align:left; font: oblique bold 80% cursive; border:none ">Nit: <?php echo $nit ?></td>
            <td style="text-align:left; font: oblique bold 80% cursive; border:none "> </td>
            <td style="text-align:left; font: oblique bold 80% cursive; border:none ">Caja N° 1 </td>
        </tr>
        <tr>
            <td style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo $nombre_regimen ?></td>
            <td style="text-align:left; font: oblique bold 80% cursive; border:none "></td>

        </tr>
        <tr>
            <td style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo $direccion . " " . $nombre_ciudad . " " . $nombre_departamento ?></td>
        </tr>
    </tbody>
</table>


<table>
    <tbody>

        <?php foreach ($categorias as $detalle) { ?>

            <?php $nombre_categoria = model('categoriasModel')->select('nombrecategoria')->where('codigocategoria', $detalle['id_categoria'])->first(); ?>

            <tr style="background-color: #4C88CF; color: white;"> <!-- Azul similar a Bootstrap -->
                <td style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo $nombre_categoria['nombrecategoria'] ?></td>
                <td style="text-align:left; font: oblique bold 80% cursive; border:none "></td>
                <td style="text-align:left; font: oblique bold 80% cursive; border:none "></td>
                <td style="text-align:left; font: oblique bold 80% cursive; border:none "></td>
                <td style="text-align:left; font: oblique bold 80% cursive; border:none "></td>
            </tr>

            <?php
            $productos_categoria = model('reporteProductoModel')->select('*')->where('id_categoria', $detalle['id_categoria'])->findAll();
            $total_categoria = model('reporteProductoModel')->selectSum('valor_total')->where('id_categoria', $detalle['id_categoria'])->findAll();
            ?>

            <?php if ($total_categoria[0]['valor_total'] > 0) { ?>
                <tr style="background-color: #2E3949; color: white;"> <!-- Gris oscuro similar a Bootstrap -->
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">CÓDIGO</td>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">PRODUCTO</td>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">CANTIDAD</td>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">VALOR UNIDAD</td>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">TOTAL</td>
                </tr>.

                <?php foreach ($productos_categoria as $detalle_producto) { ?>
                    <tr>
                        <td style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo $detalle_producto['codigo_interno_producto'] ?></td>
                        <td style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo $detalle_producto['nombre_producto'] ?></td>
                        <td style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo $detalle_producto['cantidad'] ?></td>
                        <td style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo  number_format($detalle_producto['precio_venta'], 0, ",", ".") ?></td>
                        <td style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo  number_format($detalle_producto['valor_total'], 0, ",", ".") ?></td>
                    </tr>
                <?php } ?>

                <tr>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none "></td>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none "></td>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none "></td>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none "></td>
                    <td style="background-color: #FBB282; color: white;" style="text-align:left; font: oblique bold 80% cursive; border:none "> <!-- Rojo similar a Bootstrap -->
                        <p style="background-color: #FBB282; color: white;" class="h2 text-end">TOTAL: <?php echo  number_format($total_categoria[0]['valor_total'], 0, ",", ".") ?></p>
                    </td>
                </tr>

            <?php } ?>

        <?php } ?>
    </tbody>
</table>


<?php $total = model('reporteProductoModel')->selectSum('valor_total')->findAll(); ?>
<style>
        .total-venta {
            text-align: right; /* Alinea el texto a la derecha */
            font-size: 24px; /* Tamaño de la fuente */
            font-weight: bold; /* Negrita */
            color: #007bff; /* Color de texto azul */
            padding: 10px; /* Espaciado alrededor */
            background-color: #f8f9fa; /* Fondo gris claro */
            border-radius: 8px; /* Bordes redondeados */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra sutil */
            max-width: 300px; /* Ancho máximo */
            margin: 20px auto; /* Centrado en la página */
        }
    </style>

<p class="total-venta">
    Total venta : <?php echo "$ " . number_format($total[0]['valor_total'], 0, ",", ".") ?>
</p>