<!-- Modal -->
<div class="modal fade" id="imprimir_retiro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Retiro de caja </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="valor_retirado"></p>
            </div>
            <div class="modal-footer">
                <form action="<?= base_url('devolucion/imprimir_retiro') ?>" method="POST">
                    <input type="hidden" id="id_retiro" name="id_retiro">
                    <input type="hidden"  name="apertura" value="1">
                    <input type="hidden" id="id_usuario_retiro" name="id_usuario">
                    <button type="button" class="btn btn-outline-green w-100" onclick="imprimir_retiro_de_dinero()">
                        Imprimir el comprobante
                    </button>
                </form>

                <button type="button" class="btn btn-outline-dark " data-bs-dismiss="modal" onclick="no_imprimir_retiro_de_dinero()">
                    Regresar
                </button>
            </div>
        </div>
    </div>
</div>