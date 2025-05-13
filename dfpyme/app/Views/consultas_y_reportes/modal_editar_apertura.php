<!-- Modal -->
<div class="modal fade" id="editar_apertura_caja_diaria" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Cambiar valores de la apertura </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-3"> valor apertura</div>
          <div class="col">
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">$</span>
              <input type="hidden" id="valor_id_apertura">
              <input type="text" id="valor_de_la_apertura" class="form-control">
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="cambiar_apertura()">Cambiar valor</button>
        <button type="button" class="btn btn-danger">Cancelar </button>
      </div>
    </div>
  </div>
</div>

<script>
  const apertura =
    document.querySelector("#valor_de_la_apertura");

  function formatNumber(n) {
    n = String(n).replace(/\D/g, "");
    return n === "" ? n : Number(n).toLocaleString();
  }
  apertura.addEventListener("keyup", (e) => {
    const element = e.target;
    const value = element.value;
    element.value = formatNumber(value);
  });
</script>