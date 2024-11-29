<table>
    <tbody>
        <tr>
            <td>Factura electrónica de venta número:</td>
            <td><?php echo $numero  ?></td>
        </tr>
        <tr>
            <td>Cliente:</td>
            <td><?php echo $nombre  ?></td>
        </tr>
        <tr>
            <td>Fecha factura :</td>
            <td><?php
                // Asegúrate de tener habilitada la extensión intl

                // Establecer la zona horaria
                date_default_timezone_set('America/Bogota');

                // Crear un objeto IntlDateFormatter para formatear la fecha en español (Colombia)
                $formatter = new IntlDateFormatter(
                    'es_CO', // Configuración local en español de Colombia
                    IntlDateFormatter::FULL, // Formato completo (puedes cambiar a LONG, MEDIUM, SHORT)
                    IntlDateFormatter::NONE // No mostrar la hora
                );

                // Obtener la fecha actual o usar una fecha específica
                $fecha = new DateTime(); // Para la fecha actual
                // $fecha = new DateTime('2024-10-19'); // Si deseas una fecha específica

                // Formatear y mostrar la fecha
                echo $formatter->format($fecha);
                ?>
            </td>
        </tr>
    </tbody>
</table>



<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <td scope="col">Código</th>
            <td scope="col">Cantidad</th>

            <td scope="col">Producto</th>
            <td scope="col">Valor unitario </th>
            <td scope="col">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $detalle) { ?>
            <tr>
                <td><?php echo $detalle['codigo'] ?></td>
                <td><?php echo $detalle['cantidad'] ?></th>

                <td><?php echo $detalle['descripcion'] ?></td>
                <td><?php echo "$ " . number_format($detalle['neto'], 0, ',', '.') ?></td>
                <td><?php echo "$ " . number_format($detalle['neto'] * $detalle['cantidad'], 0, ',', '.') ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>