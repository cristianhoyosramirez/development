<?php $user_session = session(); ?>
<!-- Modal -->
<div class="modal fade" id="fin_de_venta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <div id="cerrar_venta_sin_impuestos">
          <div class="row g-3 align-items-center">
            <div class="col-4">
              <p class="text-dark">SUB TOTAL</p>
            </div>
            <div class="col-6">
              <div class="input-group mb-2">
                <span class="input-group-text">
                  $
                </span>
                <input type="text" class="form-control" id="sub_total" readonly>
              </div>
            </div>
          </div>
          <div class="row g-3 align-items-center">
            <div class="col-4">
              <label for="inputPassword6" class="col-form-label">IVA </label>
            </div>
            <div class="col-6">
              <div class="input-group mb-2">
                <span class="input-group-text">
                  $
                </span>
                <input type="text" class="form-control" id="impuesto_iva" readonly>
              </div>
            </div>
          </div> 
          <div class="row g-3 align-items-center">
            <div class="col-4">
              <label for="inputPassword6" class="col-form-label">IMPO CONSUMO</label>
            </div>
            <div class="col-6">
              <div class="input-group mb-2">
                <span class="input-group-text">
                  $
                </span>
                <input type="text" class="form-control" id="total_ico" readonly>
              </div>
            </div>
          </div>
          <div class="row g-3 align-items-center">
            <div class="col-4">
              <label for="inputPassword6" class="col-form-label">DESCUENTO</label>
            </div>
            <div class="col-6">
              <div class="input-group mb-2">
                <span class="input-group-text">
                  $
                </span>
                <input type="text" class="form-control" id="descuento_fact" readonly>
              </div>
            </div>
          </div>
          <div class="row g-3 align-items-center">
            <div class="col-4">
              <label for="inputPassword6" class="col-form-label">PROPINA</label>
            </div>
            <div class="col-6">
              <div class="input-group mb-2">
                <span class="input-group-text">
                  $
                </span>
                <input type="text" class="form-control" id="propina_fact" readonly>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row g-3 align-items-center">
          <div class="col-4">
            <label for="inputPassword6" class="col-form-label">TOTAL</label>
          </div>
          <div class="col-6">
            <div class="input-group mb-2">
              <span class="input-group-text">
                $
              </span>
              <input type="text" class="form-control" id="total_factura" readonly>
            </div>
          </div>
        </div> 
        <div class="row g-3 align-items-center">
          <div class="col-4">
            <label for="inputPassword6" class="col-form-label">EFECTIVO</label>
          </div>
          <div class="col-6">
            <div class="input-group mb-2">
              <span class="input-group-text">
                $
              </span>
              <input type="text" class="form-control" id="efectivo_pago" readonly>
            </div>
          </div>
        </div> 
        <div class="row g-3 align-items-center">
          <div class="col-4">
            <label for="inputPassword6" class="col-form-label">CAMBIO</label>
          </div>
          <div class="col-6">
            <div class="input-group mb-2">
              <span class="input-group-text">
                $
              </span>
              <input type="text" class="form-control" id="cambio_pago" readonly>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <form action="<?= base_url('factura_pos/imprimir_factura_desde_pedido') ?>" method="post">
          <input type="hidden" id="numero_de_factura" name="numero_de_factura">
          <button type="submit" class="btn btn-success" onkeydown="return allowOnlyAlphabets(event);" id="imprimir_factura" data-bs-dismiss="modal">Imprimir factura</button>
        </form>
        <form action="<?= base_url('factura_pos/cerrar_venta') ?>" method="GET">
          <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" id="id_usuario" name="id_usuario">
          <button type="submit" id="ver_todos_los_pedidos" class="btn btn-primary">Ver todos los pedidos</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</div>