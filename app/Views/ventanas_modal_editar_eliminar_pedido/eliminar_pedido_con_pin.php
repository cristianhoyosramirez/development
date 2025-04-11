<!-- Modal -->
<script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
<div class="modal fade" id="eliminar_pedido_con_pin_pad" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <p class="text-warning">Pin necesario para la eliminación </p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="valor_id_tabla_producto">
                <div class="container text-center">
                
                        <div class="otp-input-wrapper">

                            <div class="row">
                                <input type="text" maxlength="4" pattern="[0-9]*" autocomplete="off" autofocus id="eliminacion_pedido_usuario" onkeyup="eliminar_con_pin_pad(event, this.value)">
                                <svg viewBox="0 0 240 1" xmlns="http://www.w3.org/2000/svg">
                                    <line x1="0" y1="0" x2="240" y2="0" stroke="#3e3e3e" stroke-width="2" stroke-dasharray="44,22" />
                                </svg>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-5">

                            </div>
                            <div class="col-2 ">
                                <button type="button" class="btn btn-primary" onclick="eliminar_el_pedido_con_pin()">Eliminar</button>
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>