<!-- Modal -->
<div class="modal fade" id="agregar_marca" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Crear marca de producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <label for="">Nombre de marca</label>
            <input type="text" class="form-control" placeholder="Nombre de marca" name="nombre_marca" id="nombre_marca">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" value="<?php echo base_url() ?>" id="url">
        <button type="button" class="btn btn-success" onclick="generar_marca()">Guardar</button>
        <button type="button" class="btn btn-danger">Cancelar </button>
      </div>
    </div>
  </div>
</div>