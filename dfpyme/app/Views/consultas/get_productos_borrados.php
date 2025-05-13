<?php if (!empty($productos)) {  ?>
    <?php foreach ($productos as $detalle) : ?>

        <tr>

            <td><?php echo $detalle['codigointernoproducto'] ?></td>
            <td><?php echo $detalle['nombreproducto'] ?></td>
            <td><?php echo $detalle['cantidad'] ?></td>
            <td><?php echo $detalle['fecha_eliminacion'] ?></td>
            <td><?php echo $detalle['hora_eliminacion'] ?></td>
            <!-- <td><?php #echo $detalle['nombresusuario_sistema'] ?></td> -->

        </tr>

    <?php endforeach ?>
<?php } ?>
