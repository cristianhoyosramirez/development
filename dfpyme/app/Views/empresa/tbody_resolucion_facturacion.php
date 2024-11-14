<input type="hidden" id="url" value="<?php echo base_url() ?>">

<?php foreach ($resoluciones_dian as $detalle) { ?>
    <tr>
        <td><?php echo $detalle['iddian'] ?></td>
        <td><?php echo $detalle['numeroresoluciondian'] ?></td>
        <td><?php echo $detalle['fechadian'] ?></td>
        <td><?php echo $detalle['rangoinicialdian'] ?></td>
        <td><?php echo $detalle['rangofinaldian'] ?></td>
        <td><?php echo $detalle['vigencia_mes'] ?></td>
        <td>

            <div class="breadcrumb m-0">
                <div class="form-check form-switch">
                    <?php if ($detalle['iddian'] == $id_dian) { ?>
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked onclick="estado_resolucion(<?php echo $detalle['iddian'] ?>)">
                    <?php } ?>
                    <?php if ($detalle['iddian'] != $id_dian) { ?>
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" onclick="estado_resolucion(<?php echo $detalle['iddian'] ?>)">
                    <?php } ?>
                </div> &nbsp; &nbsp;&nbsp; <button type="button" class="btn btn-success btn-icon" onclick="editar_resolucion(<?php echo $detalle['iddian'] ?>)">Editar</button>

            </div>


        </td>
    </tr>
<?php } ?>