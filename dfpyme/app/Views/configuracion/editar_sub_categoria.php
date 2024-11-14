<div class="row">

    <div class="col">
        <label for="" class="form-label">Categoria </label>
        <select class="form-select" aria-label="Default select example" name="nombre_categoria_edicion" id="nombre_categoria_edicion">
            <?php foreach ($categorias as $detalle) : ?>
                <option value="<?php echo $detalle['id'] ?>" <?php if ($detalle['id'] == $id_categoria) : ?>selected <?php endif; ?>><?php echo $detalle['nombrecategoria'] ?> </option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="col">
        <label for="" class="form-label">Nombre sub categoria </label>
        <input type="text" class="form-control" name="nombre_categoria" id="nombre_categoria" value="<?php echo $subcategoria['nombre'] ?>">
    </div>

</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-outline-success" onclick="actualizar_categoria(<?php echo $subcategoria['id'] ?>)">Guardar</button>
    <button type="button" class="btn btn-outline-red" data-bs-dismiss="modal">Cancelar </button>
</div>