<!-- Modal -->
<div class="modal fade" id="resolucion_facturacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-body">
                <div class="hr-text text-green ">
                    <p class="h3 text-primary">RESOLUCIÓN FACTURACÓN</p>
                </div>
                <form class="row g-2" action="<?= base_url('empresa/guardar_resolucion_facturacion') ?>" method="POST" id="guardar_resolucion_facturacion">
                    <div class="col-md-3">
                        <label for="inputEmail4" class="form-label">Número de resolución</label>
                        <input type="text" class="form-control" id="numero_dian" name="numero_dian">
                        <span class="text-danger error-text numero_dian_error"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="inputPassword4" class="form-label">Fecha de expedición</label>
                        <input type="date" class="form-control" id="fecha_dian" name="fecha_dian" value="<?php echo date('Y-m-d') ?>">
                        <span class="text-danger error-text fecha_dian_error"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="inputPassword4" class="form-label">Fecha de caducidad</label>
                        <input type="date" class="form-control" id="fecha_caducidad" name="fecha_caducidad" value="<?php echo date('Y-m-d') ?>">
                        <span class="text-danger error-text fecha_caducidad_error"></span>
                    </div>
                    <div class="col-3">
                        <label for="inputAddress" class="form-label">Modalidad</label>
                        <select class="form-select" aria-label="Default select example" id="modalidad_dian" name="modalidad_dian">
                            <option value="1">ELECTRÓNICA</option>
                            <option value="2">POS</option>
                        </select>
                        <span class="text-danger error-text modalidad_dian_error"></span>
                    </div>

                    <div class="col-3">
                        <label for="inputAddress2" class="form-label">Prefijo </label>
                        <input type="text" class="form-control" id="prefijo_dian" name="prefijo_dian">
                        <span class="text-danger error-text prefijo_error"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="inputCity" class="form-label">Desde el número </label>
                        <input type="text" class="form-control" id="numero_inicial" name="numero_inicial">
                        <span class="text-danger error-text numero_inicial_error"></span>
                    </div>

                    <div class="col-3">
                        <label for="inputAddress2" class="form-label">Hasta el núemro </label>
                        <input type="text" class="form-control" id="numero_final" name="numero_final">
                        <span class="text-danger error-text numero_final_error"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="inputCity" class="form-label">Vigencia</label>
                        <input type="text" class="form-control" id="vigencia" name="vigencia">
                        <span class="text-danger error-text vigencia_error"></span>
                    </div>

                    <div class="col-md-4">
                        <label for="inputCity" class="form-label">Texto inicial </label>
                        <input type="text" class="form-control" id="texto_inicial" name="texto_inicial" value="Resolución DIAN No.">
                        <span class="text-danger error-text texto_inicial_error"></span>
                    </div>
                    <div class="col-md-4">
                        <label for="inputCity" class="form-label">Tipo de solicitud </label>
                        <input type="text" class="form-control" id="tipo_de_solicitud" name="tipo_de_solicitud" value="Autorización">
                        <span class="text-danger error-text tipo_de_solicitud_error"></span>
                    </div>
                    <div class="col-md-4">
                        <label for="inputCity" class="form-label">Alertar cuando falten </label>
                        <input type="number" class="form-control" id="alerta" name="alerta">
                        <span class="text-danger error-text alerta_error"></span>
                    </div>




                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="btnguardar_resolucion_facturacion">Guardar </button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>