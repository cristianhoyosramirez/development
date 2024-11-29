<!-- Modal -->
<div class="modal fade" id="partir_factura" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Pagos parciales </h1>
        <button type="button" class="btn-close" onclick="cancelar_pago_parcial()" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="productos_pago_parcial"></div>
      </div>

      <div class="row">
        <div class="col-9">

        </div>
        <div class="col">
          <p class=" h3 " id="total_pago_parcial"></p>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-success" onclick="pagar_parcial()">Pagar</button>
        <button type="button" class="btn btn-outline-red" onclick="cancelar_pago_parcial()">Cancelar </button>
      </div>
    </div>
  </div>

</div>