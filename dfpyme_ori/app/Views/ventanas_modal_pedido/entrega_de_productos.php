<!-- Modal -->
<div class="modal fade" id="entrega_de_productos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Entregar cantidades</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">

          <div class="col mb-3">
            <label class="form-label">Cantidad solicitada</label>
            <input type="text" class="form-control" id="cantidad_de_producto" name="cantidad_solicitada" readonly>
          </div>
          <div class=" col mb-3">
            <label class="form-label">Cantidad Entregada</label>
            <input type="text" class="form-control" id="cantidad_ya_entregada" name="cantidad_entregada" readonly>
          </div>
          <div class=" col  mb-3">
            <label class="form-label">Cantidad a entregar </label>
            <input type="number" class="form-control" id="cantidad_a_entregar" name="cantidad_a_entregar" onKeyPress="return entregarProducto(event)">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="actualizar_entregar_producto()" id="guardar_cantidades_entregar">Guardar</button>
        <input type="hidden" id="id_producto_pedido">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>