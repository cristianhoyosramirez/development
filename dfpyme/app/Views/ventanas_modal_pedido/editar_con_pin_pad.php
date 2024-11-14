<!-- Modal -->
<script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
<div class="modal fade" id="editar_con_pin_pad"  data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <input type="hidden" id="valor_id_tabla_producto_editar">
        <div class="container text-center">
          <div class="otp-input-wrapper">
            
            <input type="text" maxlength="4" pattern="[0-9]*" autocomplete="off" autofocus id="edit_pin_pad" onkeyup="editar_con_pin_pad(event, this.value)"    >
            <svg viewBox="0 0 240 1" xmlns="http://www.w3.org/2000/svg">
              <line x1="0" y1="0" x2="240" y2="0" stroke="#3e3e3e" stroke-width="2" stroke-dasharray="44,22" />
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
