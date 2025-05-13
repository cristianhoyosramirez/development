<!-- Modal -->
<div class="modal fade" id="cerrar_venta_con_impuestos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">

                <form action="" id="form_cerrar_venta_con_impuestos">
                    <div class="row g-3 align-items-center">
                        <div class="col-4">
                            <p class="text-dark">SUB TOTAL</p>
                        </div>

                        <div class="col-6">
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    $
                                </span>
                                <input type="text" class="form-control" id="sub_total_imp" readonly>
                            </div>
                        </div>
                    </div> <br>
                    <div class="row g-3 align-items-center">
                        <div class="col-4">
                            <label for="inputPassword6" class="col-form-label">IVA </label>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    $
                                </span>
                                <input type="text" class="form-control" id="impuesto_iva_imp" readonly>
                            </div>
                        </div>
                    </div> <br>
                    <div class="row g-3 align-items-center">
                        <div class="col-4">
                            <label for="inputPassword6" class="col-form-label">IMPO CONSUMO</label>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    $
                                </span>
                                <input type="text" class="form-control" id="total_ico_imp" readonly>
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
                                <input type="text" class="form-control" id="total_factura_imp" readonly>
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
                                <input type="text" class="form-control" id="efectivo_pago_imp" readonly>
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
                                <input type="text" class="form-control" id="cambio_pago_imp" readonly>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">

                <input type="hidden" id="numero_de_factura_imp" name="numero_de_factura">
                <button type="button" class="btn btn-success" onclick="imprimir_factura_imp()" onkeydown="return allowOnlyAlphabets(event);" id="imprimir_factura">Imprimir factura</button>
                <button type="button" onclick="modulo_facturacion()" class="btn btn-primary">Facturar</button>
            </div>
        </div>
        </form>
    </div>
</div>
</div>
</div>