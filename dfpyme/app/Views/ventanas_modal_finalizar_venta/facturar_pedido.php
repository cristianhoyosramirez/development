<!-- Modal -->
<div class="modal fade" id="facturar_pedido" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p class="h1 text-center">FINALIZAR VENTA</p>
                <hr class="dropdown-divider text-primary">
                </li>

                <input type="hidden" id="efectivo_format">
                <input type="hidden" id="transaccion_format">
                <input type="hidden" id="valor_total_sinPunto">
                <div id="impuestos" style="display: none;">
                    <div class="row">
                        <div class="col-md-5">
                            <label for="inputEmail4" class="form-label">
                                <p class="fs-1 h3"> BASE</p>
                            </label>
                        </div>
                        <div class="col-md-7">
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    $
                                </span>
                                <input type="text" class="form-control" id="base_grabable" readonly>
                            </div>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="inputEmail4" class="form-label">
                                <p class="fs-1 h3"> IVA </p>
                            </label>
                        </div>
                        <div class="col-md-7">
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    $
                                </span>
                                <input type="text" class="form-control" id="iva_grabable" readonly>
                            </div>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="inputEmail4" class="form-label">
                                <p class="fs-1 h3"> IMPO CONSUMO </p>
                            </label>
                        </div>
                        <div class="col-md-7">
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    $
                                </span>
                                <input type="text" class="form-control" id="ico_grabable" readonly>
                            </div>
                        </div>
                    </div><br>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <label for="inputEmail4" class="form-label">
                            <p class="fs-1 h3"> SUB TOTAL</p>
                        </label>
                    </div>
                    <div class="col-md-7">
                        <div class="input-group mb-2">
                            <span class="input-group-text">
                                $
                            </span>
                            <input type="text" class="form-control" name="sub_total_factura" id="subtotal_de_factura" readonly >
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-5">
                        <label for="inputEmail4" class="form-label">
                            <p class="fs-1 h3"> DESCUENTO</p>
                        </label>
                    </div>
                    <div class="col-md-7">
                        <div class="input-group mb-2">
                            <span class="input-group-text">
                                $
                            </span>
                            <input type="text" class="form-control" name="descuento_factura" id="descuento_factura" readonly >
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-5">
                        <label for="inputEmail4" class="form-label">
                            <p class="fs-1 h3"> PROPINA</p>
                        </label>
                    </div>
                    <div class="col-md-7">
                        <div class="input-group mb-2">
                            <span class="input-group-text">
                                $
                            </span>
                            <input type="text" class="form-control" name="propina_factura" id="propina_factura" readonly >
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-5">
                        <label for="inputEmail4" class="form-label">
                            <p class="fs-1 h3"> TOTAL </p>
                        </label>
                    </div>
                    <div class="col-md-7">
                        <div class="input-group mb-2">
                            <span class="input-group-text">
                                $
                            </span>
                            <input type="text" class="form-control" name="valor_total_a_pagar" id="valor_total_a_pagar" readonly >
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-5">
                        <label for="inputEmail4" class="form-label">
                            <p class="fs-1 h3">EFECTIVO</p>
                        </label>
                    </div>
                    <div class="col-md-7">
                        <div class="input-group mb-2">
                            <span class="input-group-text">
                                $
                            </span>
                            <input type="text" class="form-control" name="efectivo" value=0 onkeyup="saltar_facturar_pedido(event,'transaccion');cambio()" id="efectivo">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                    </div>
                    <div class="col-md-7">
                        <span id="efectivo_en_ceros" style="color:red;"></span>
                    </div>

                </div>
                <br>
                <div class="row">
                    <div class="col-md-5">
                        <label for="inputEmail4" class="form-label">
                            <p class="fs-1 h3">TRANSACCIÓN</p>
                        </label>
                    </div>
                    <div class="col-md-7">
                        <div class="input-group mb-2">
                            <span class="input-group-text">
                                $
                            </span>
                            <input type="text" value="0" class="form-control" value="0" onkeyup="cambio(),llamar_ventana_fin_venta(event) " name="transaccion" id="transaccion">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                    </div>
                    <div class="col-md-7">
                        <span id="transaccion_en_ceros" style="color:red;"></span>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-5">
                        <label for="inputEmail4" class="form-label">
                            <p class="fs-1 h3">CAMBIO</p>
                        </label>
                    </div>
                    <div class="col-md-7">
                        <div class="input-group mb-2">
                            <span class="input-group-text">
                                $
                            </span>
                            <input type="number" class="form-control" name="cambio" readonly id="cambio">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">

                    <button type="button" data-bs-dismiss="modal" onclick="reset_inputs()" class="btn btn-danger">Cancelar</button>
                    <!--   <button type="button" id="finalizar_venta" onclick="finalizar_venta()" class="btn btn-primary">Aceptar</button>-->
                </div>
            </div>
        </div>
    </div>
</div>