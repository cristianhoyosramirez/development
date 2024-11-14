<div class="modal fade" id="edicion_apertura" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Edicion de valores de apertura </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
  
          <div class="row">
            <div class="col-3">
              <span>Valor apertura</span>
            </div>
            <div class="col-6">
              <input type="hidden" id="id_apertura_caja_general">
              <input type="text" class="form-control" id="cambiar_valor_apertura" onkeyup="saltar(event,'actualizacion')">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" id="actualizacion" onclick="actualizar_apertura()">Actualizar</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
          </div>
      
      </div>


    </div>
  </div>
</div>


<script>
  const actualizacion_apertura = document.querySelector("#cambiar_valor_apertura");

  function formatNumber(n) {
    n = String(n).replace(/\D/g, "");
    return n === "" ? n : Number(n).toLocaleString();
  }
  actualizacion_apertura.addEventListener("keyup", (e) => {
    const element = e.target;
    const value = element.value;
    element.value = formatNumber(value);
  });
</script>