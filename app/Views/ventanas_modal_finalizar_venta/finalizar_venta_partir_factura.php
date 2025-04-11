<!-- Modal -->
<div class="modal fade" id="finalizacion_venta_partir_factura" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row g-3 align-items-center">
          <div class="col-4">
            <label for="inputPassword6" class="col-form-label">TOTAL</label>
          </div>
          <div class="col-6">
            <div class="input-group mb-2">
              <span class="input-group-text">
                $
              </span>
              <input type="text" class="form-control" id="total_de_factura_partir_factura" readonly>
            </div>
          </div>
        </div> <br>
        <div class="row g-3 align-items-center">
          <div class="col-4">
            <label for="inputPassword6" class="col-form-label">EFECTIVO</label>
          </div>
          <div class="col-6">
            <div class="input-group mb-2">
              <span class="input-group-text">
                $
              </span>
              <input type="text" class="form-control" id="pago_con_efectivo_partir_factura" readonly>
            </div>
          </div>
        </div> <br>
        <div class="row g-3 align-items-center">
          <div class="col-4">
            <label for="inputPassword6" class="col-form-label">CAMBIO</label>
          </div>
          <div class="col-6">
            <div class="input-group mb-2">
              <span class="input-group-text">
                $
              </span>
              <input type="text" class="form-control" id="cambio_del_pago_partir_factura" readonly>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <form action="<?= base_url('factura_pos/imprimir_factura_partir_factura') ?>" method="post">
          <input type="hidden" id="id_de_factura_partida" name="id_de_factura_partida">
          <input type="hidden" id="id_pedido_factura_partida" name="id_pedido_factura_partida">
          <button type="submit" class="btn btn-success" onkeydown="return allowOnlyAlphabets(event);" id="impresion_factura" data-bs-dismiss="modal">Imprimir factura</button>
        </form>



        <form action="<?= base_url('factura_pos/cerrar_venta_partir_factura') ?>" method="POST">
          <input type="hidden" id="cerrar_venta_partir_factura" name="cerrar_venta_partir_factura">
          <button type="submit" id="ver_todos_los_pedidos" class="btn btn-primary">Seguir facturando</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</div>