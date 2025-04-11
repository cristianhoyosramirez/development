<table class="table">
    <thead class="table-dark">
        <td scope="col">Código </th>
        <td scope="col">Producto </th>
        <td scope="col">Cantidad </th>
        
    </thead>
    <tbody>
        <?php foreach ($productos as $detalle) : ?>

            <tr>

                <td><?php echo $detalle['codigointernoproducto'] ?></td>
                
                <td><?php echo $detalle['nombreproducto'] ?></td>
                <td><?php echo $detalle['cantidad'] ?></td>
               

            </tr>

        <?php endforeach ?>
    </tbody>
</table>