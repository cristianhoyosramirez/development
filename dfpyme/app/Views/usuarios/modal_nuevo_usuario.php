<!-- Modal -->
<div class="modal fade" id="nuevo_usuario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-body">
        <div class="hr-text text-green">
          <p class="h3 text-success">Nuevo usuario </p>
        </div>
        <form class="row g-3" action="<?= base_url('usuarios/crear') ?>" method="POST" id="usuario_agregar">
         
          <div class="col-md-3">
            <label for="inputPassword4" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario">
            <span class="text-danger error-text nombre_usuario_error"></span>
          </div>
          <div class="col-3">
            <label for="inputAddress" class="form-label">Usuario</label>
            <input type="text" class="form-control" id="usuario" name="usuario">
            <span class="text-danger error-text usuario_error"></span>
          </div>
         
          <div class="col-md-3">
            <label for="inputCity" class="form-label">Pin</label>
            <input type="text" class="form-control" id="pin" name="pin">
            <span class="text-danger error-text pin_error"></span>
          </div>
          <div class="col-md-3">
            <label for="inputCity" class="form-label">Rol</label>
            <select class="form-select" aria-label="Default select example" name="id_rol" id="id_rol">
              <option value=""></option>
              <?php foreach ($rol as $detalle) { ?>
                <option value="<?php echo $detalle['idtipo'] ?>"><?php echo $detalle['descripciontipo'] ?></option>
              <?php } ?>
            </select>

            <span class="text-danger error-text id_rol_error"></span>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="btn_agregar_usuario">Guardar</button>
            <button type="button" class="btn btn-danger" onclick="cancelar_creacion_usuario()">Cancelar </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

