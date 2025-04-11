<div class="modal fade" id="editar_cierre" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Edición de valores de cierre </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
  
          <div class="row">
            <div class="col-3">
              <span>Valor cierre</span>
            </div>
            <div class="col-6">
              <input type="hidden" id="id_apertura_caja_general">
              <input type="text" class="form-control" id="cambiar_valor_cierre" onkeyup="saltar(event,'actualizacion_cierre')">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" id="actualizacion_cierre" onclick="actualizar_cierre()">Actualizar</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
          </div>
      
      </div>


    </div>
  </div>
</div>


<script>
  const actualizacion_cierre = document.querySelector("#cambiar_valor_cierre");

  function formatNumber(n) {
    n = String(n).replace(/\D/g, "");
    return n === "" ? n : Number(n).toLocaleString();
  }
  actualizacion_cierre.addEventListener("keyup", (e) => {
    const element = e.target;
    const value = element.value;
    element.value = formatNumber(value);
  });
</script>