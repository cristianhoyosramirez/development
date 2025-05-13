<?php $user_session = session(); ?>
<!-- Modal -->
<div class="modal fade" id="finalizacion_venta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row g-3 align-items-center">
          <div class="col-4">
            <label for="inputPassword6" class="col-form-label">SUB TOTAL</label>
          </div>
          <div class="col-6">
            <div class="input-group mb-2">
              <span class="input-group-text">
                $
              </span>
              <input type="text" class="form-control" id="sub_total_de_factura" readonly>
            </div>
          </div>
        </div> <br>

        <div class="row g-3 align-items-center">
          <div class="col-4">
            <label for="inputPassword6" class="col-form-label">DESCUENTO</label>
          </div>
          <div class="col-6">
            <div class="input-group mb-2">
              <span class="input-group-text">
                $
              </span>
              <input type="text" class="form-control" id="descuento_total_de_factura" readonly>
            </div>
          </div>
        </div> <br>
        <div class="row g-3 align-items-center">
          <div class="col-4">
            <label for="inputPassword6" class="col-form-label">PROPINA</label>
          </div>
          <div class="col-6">
            <div class="input-group mb-2">
              <span class="input-group-text">
                $
              </span>
              <input type="text" class="form-control" id="propina_total_de_factura" readonly>
            </div>
          </div>
        </div> <br>

        <div class="row g-3 align-items-center">
          <div class="col-4">
            <label for="inputPassword6" class="col-form-label">TOTAL</label>
          </div>
          <div class="col-6">
            <div class="input-group mb-2">
              <span class="input-group-text">
                $
              </span>
              <input type="text" class="form-control" id="total_de_factura" readonly>
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
              <input type="text" class="form-control" id="pago_con_efectivo" readonly>
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
              <input type="text" class="form-control" id="cambio_del_pago" readonly>
            </div>
          </div>
        </div>


        <div class="modal-footer">
          <form action="<?= base_url('factura_pos/imprimir_factura_sin_impuestos') ?>" method="post">
            <input type="hidden" id="id_de_factura" name="id_de_factura">
            <button type="submit" class="btn btn-success" onkeydown="return allowOnlyAlphabets(event);" id="impresion_factura" data-bs-dismiss="modal">Imprimir factura</button>
          </form>
          <form action="<?= base_url('factura_pos/cerrar_venta') ?>" method="GET">
            <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $user_session->id_usuario; ?>">
            <button type="submit" id="ver_todos_los_pedidos" class="btn btn-primary">Regresar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>