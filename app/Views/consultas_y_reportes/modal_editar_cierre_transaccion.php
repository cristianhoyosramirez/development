<!-- Modal -->
<div class="modal fade" id="modal_editar_cierre_transaccion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">CAMBIAR CIERRE TRANSACCION </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


        <input id="valor_cierre_transaccion" class="form-control"><br>
        <input type="hidden" id="id_apertura" class="form-control"><br>
        <button type="button" class="btn btn-primary" onclick="actualizar_valor_cierre_transferencias()">Cambiar cierre</button>

      </div>

    </div>
  </div>
</div>

<script>
  const transaccion =
    document.querySelector("#valor_cierre_transaccion");

  function formatNumber(n) {
    n = String(n).replace(/\D/g, "");
    return n === "" ? n : Number(n).toLocaleString();
  }
  transaccion.addEventListener("keyup", (e) => {
    const element = e.target;
    const value = element.value;
    element.value = formatNumber(value);
  });
</script>