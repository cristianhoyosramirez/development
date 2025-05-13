<?php {
    $total = model('kardexModel')->getTotal($fechaInicial, $fechaFinal, $usuario);
    $usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $usuario)->first();
?>
    <tr>

        <td><?php echo $usuario['nombresusuario_sistema']; ?> </td>
        <td>
            <?php echo number_format($total[0]['total'], 0, '', '.'); ?>

        </td>
    </tr>
<?php } ?>