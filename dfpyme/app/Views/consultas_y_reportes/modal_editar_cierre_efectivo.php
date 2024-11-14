<!-- Modal -->
<div class="modal fade" id="modal_editar_cierre_efectivo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">CAMBIAR CIERRE EFECTIVO </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


        <input id="valor_cierre_efectivo" class="form-control"><br>
        <input type="hidden" id="id_apertura" class="form-control"><br>
        <button type="button" class="btn btn-primary" onclick="actualizar_valor_cierre()">Cambiar cierre</button>

      </div>

    </div>
  </div>
</div>

<script>
  const cierre =
    document.querySelector("#valor_cierre_efectivo");

  function formatNumber(n) {
    n = String(n).replace(/\D/g, "");
    return n === "" ? n : Number(n).toLocaleString();
  }
  cierre.addEventListener("keyup", (e) => {
    const element = e.target;
    const value = element.value;
    element.value = formatNumber(value);
  });
</script>