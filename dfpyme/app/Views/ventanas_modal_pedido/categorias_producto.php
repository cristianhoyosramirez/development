<div class="modal modal-blur fade" id="categorias_producto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Categorias de producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <select class="form-select" id="categoria" onchange="categoriaProductos();">
                        <?php foreach ($categorias as $detalle) { ?>
                            <option value=""></option>
                            <option value="<?php echo $detalle['codigocategoria'] ?>"><?php echo $detalle['nombrecategoria'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <hr>
                <div id="categoria_productos"></div>
            </div>
        </div>
    </div>
</div>