<style>
    table {
        width: 100%;
        margin: 20px auto;
        border-collapse: collapse;
    }

    thead {
        background-color: black;
        /* Fondo negro para el encabezado */
        color: white;
        /* Texto blanco para el encabezado */
    }

    th,
    td {
        border: none;
    }
</style>



<table class="table table-borderless">
    <tbody>
        <tr>
            <th style="text-align:left; font:  bold 80% cursive; border:none "><?php echo $nombre_comercial ?></th>
            <th style="text-align:left; font:  bold 80% cursive; border:none "></th>
            <th style="text-align:left; font:  bold 80% cursive; border:none ">REPORTE DE PRODUCTO </th>
        </tr>
        <tr>
            <td style="text-align:left; font:  bold 80% cursive; border:none "><?php echo $nombre_juridico ?></td>
            <th style="text-align:left; font:  bold 80% cursive; border:none "></th>

        </tr>
        <tr>
            <td style="text-align:left; font:  bold 80% cursive; border:none ">Nit: <?php echo $nit ?></td>
            <td style="text-align:left; font:  bold 80% cursive; border:none "> </td>

        </tr>
        <tr>
            <td style="text-align:left; font:  bold 80% cursive; border:none "><?php echo $nombre_regimen ?></td>
            <td style="text-align:left; font:  bold 80% cursive; border:none "></td>
            <td style="text-align:left; font:  bold 80% cursive; border:none ">Fecha: <?php echo date('Y-m-d'); ?></td>
        </tr>
        <tr>
            <td style="text-align:left; font:  bold 80% cursive; border:none "><?php echo $direccion . " " . $nombre_ciudad . " " . $nombre_departamento ?></td>
        </tr>
    </tbody>
</table>

<table class="table">
    <thead class="table-dark">
        <tr>
            <td scope="col">Categoria </th>
            <td scope="col">Código </th>
            <td scope="col">Nombre</th>
            <td scope="col">Cantidad </th>
            <td scope="col">Valor costo</th>
            <td scope="col">Total </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $detalle) { ?>
            <tr>
                <td><?php echo $detalle['nombrecategoria'] ?></td>
                <td><?php echo $detalle['codigointernoproducto'] ?></td>
                <td><?php echo $detalle['nombreproducto'] ?></td>
                <td><?php echo $detalle['cantidad_inventario'] ?></td>
                <td><?php echo "$ " . number_format($detalle['precio_costo']) ?></td>
                <td><?php echo "$ " . number_format($detalle['precio_costo'] * $detalle['cantidad_inventario']) ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<table>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>
            Total inventario : <?php echo $total_inventario ?>
        </td>
    </tr>
</table>
<?php   ?>