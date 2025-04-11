<!-- Modal -->
<div class="modal fade" id="activar_resolucion_facturacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-body">
                <div class="hr-text text-green ">
                    <p class="h3 text-primary">ACTIVAR RESOLUCIÓN FACTURACÓN</p>
                </div>
                <p id="datos_resolucion"></p>
                <form class="row g-2" action="<?= base_url('empresa/activar_resolucion_dian') ?>" method="POST">
                    <input type="hidden" id="id_dian_guardar" name="id_dian_guardar">
                    <div class="col-md-3">
                        <label for="inputEmail4" class="form-label">Última factura generada</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="ultima_factura" name="ultima_factura" readonly>
                        <span class="text-danger error-text numero_dian_error"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="inputPassword4" class="form-label">Continuar con la factura </label>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" readonly id="prefijo_factura_dian">
                            <input type="text" class="form-control" id="continuacion_factura" name="continuacion_factura">
                        </div>
                    </div>
                    <span class="text-danger error-text fecha_dian_error"></span>


                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="btnguardar_resolucion_facturacion">Guardar </button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
</div>