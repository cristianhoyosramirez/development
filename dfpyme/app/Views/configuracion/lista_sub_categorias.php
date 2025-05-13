<table class="table">
    <input type="hidden" value="<?php echo base_url() ?>" id="url">
    <thead class="table-dark">
        <tr>
            <td scope="col">Nombre</th>
            <td scope="col">Acción</th>

        </tr>
    </thead>
    <tbody>

        <?php foreach ($sub_categorias as $detalle) { ?>
            <tr>

                <td><?php echo $detalle['nombre'] ?></td>
                <td><button type="button" class="btn btn-outline-success" onclick="editar_categoria(<?php echo $detalle['id'] ?>)">
                        Editar
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="eliminar_categoria(<?php echo $detalle['id'] ?>)">
                        Eliminar
                    </button>
                </td>

            </tr>
        <?php } ?>
    </tbody>
</table>