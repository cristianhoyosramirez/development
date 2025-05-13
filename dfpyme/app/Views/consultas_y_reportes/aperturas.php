<table class="table">
    <thead class="table-dark">
        <tr>
            <td scope="col">Fecha apertura</td>
            <td scope="col">Hora apertura</td>
            <td scope="col">Accion</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($aperturas as $detalle) { ?>
            <tr>
                <td><?php echo $detalle['fecha'] ?></th>
                <td><?php echo $detalle['hora'] ?></td>
                <td>
                    <form class="row g-3" action="<?= base_url('consultas_y_reportes/detalle_movimiento_de_caja') ?>" method="POST">
                        <div class="col-auto">
                            <input type="hidden" name="id_apertura" id="apertura" value="<?php  echo $detalle['id']?>">
                            <button type="submit" class="btn btn-primary mb-3">Ver </button>
                        </div>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>