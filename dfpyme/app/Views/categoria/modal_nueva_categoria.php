<!-- Modal -->
<div class="modal fade" id="nueva_categoria" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <form class="row g-3" action="<?= base_url('categoria/guardar') ?>" method="POST" id="crear_categoria">
                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label">Nombre categoria</label>
                        <input type="text" class="form-control" id="nombre_categoria" name="nombre_categoria">
                        <span class="text-danger error-text nombre_categoria_error"></span>
                    </div>
                    <div class="col-md-6">
                        <label for="inputPassword4" class="form-label">Impresora asociada</label>
                        <select class="form-select" aria-label="Default select example" id="impresora_categoria" name="impresora_categoria">
                            <?php foreach ($impresoras as $detalle) { ?>
                                <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['nombre'] ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="btn_crear_categoria">Crear categoria</button>
                        <button type="button" class="btn btn-danger" onclick="cancelar_crear_categoria()">Cancelar </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>