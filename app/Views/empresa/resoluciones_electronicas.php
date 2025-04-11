<?php foreach ($resoluciones_dian as $detalle) { ?>
    <tr>

        <td><?php echo $detalle['numero'] ?></td>
        <td><?php echo $detalle['date_begin'] ?></td>
        <td><?php echo $detalle['number_begin'] ?></td>
        <td><?php echo $detalle['number_end'] ?></td>
        <td><?php echo $detalle['vigency'] ?></td>
        <td><?php #echo $detalle['alerta_facturacion'] 
            ?></td>
        <td>

            <div class="breadcrumb m-0">
                <div class="form-check form-switch">



                </div> &nbsp; &nbsp;&nbsp; <button type="button" class="btn btn-outline-success btn-icon" onclick="editar_resolucion(<?php echo $detalle['id']
                                                                                                                                        ?>)">Editar</button>

            </div>


        </td>
    </tr>
<?php } ?>