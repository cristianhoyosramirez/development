<!-- Modal -->
<div class="modal fade" id="modal_consulta_cierres" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">CONSULTA DE MOVIMIENTO DE CAJA </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3">
          <div class="col-md-6">
            <label for="inputEmail4" class="form-label">Fecha inicio </label>
            <input type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" id="fecha_inicio_cierre">
          </div>
          <div class="col-md-6">
            <label for="inputPassword4" class="form-label">Fecha final </label>
            <input type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" id="fecha_final_cierre">
          </div>

        </form>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="buscar_movimientos_de_caja()" >Buscar </button>
        </div>

        <div id="movimientos_de_caja"></div>


      </div>

    </div>
  </div>
</div>