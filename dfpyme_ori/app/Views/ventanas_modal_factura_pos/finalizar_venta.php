<!-- Modal -->
<div class="modal fade" id="finalizar_venta" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p class="text-center h3">FINALIZAR VENTA</p>
                <form action="" id="clean_form">
                    <div id="impuestos_pos">
                        <div class="row g-3 align-items-center">
                            <div class="col-4">
                                <label for="inputPassword6" class="col-form-label">BASE</label>
                            </div>
                            <div class="col-6">
                                <div class="input-group mb-2">
                                    <span class="input-group-text">
                                        $
                                    </span>
                                    <input type="text" class="form-control" id="base_impuestos_pos" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-4">
                                <label for="inputPassword6" class="col-form-label">IVA</label>
                            </div>
                            <div class="col-6">
                                <div class="input-group mb-2">
                                    <span class="input-group-text">
                                        $
                                    </span>
                                    <input type="text" class="form-control" id="impuesto_iva_pos" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-4">
                                <label for="inputPassword6" class="col-form-label">IPO CONSUMO</label>
                            </div>
                            <div class="col-6">
                                <div class="input-group mb-2">
                                    <span class="input-group-text">
                                        $
                                    </span>
                                    <input type="text" class="form-control" id="impuesto_al_consumo_pos" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 align-items-center">
                        <div class="col-4">
                            <label for="inputPassword6" class="col-form-label"> SUB TOTAL</label>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    $
                                </span>
                                <input type="text" class="form-control" id="valor_a_pagar" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-items-center">
                        <div class="col-4">
                            <label for="inputPassword6" class="col-form-label"> DESCUENTOS</label>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    $
                                </span>
                                <input type="text" class="form-control" id="descuento_factura" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-items-center">
                        <div class="col-4">
                            <label for="inputPassword6" class="col-form-label"> PROPINA</label>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    $
                                </span>
                                <input type="text" class="form-control" id="propina_factura" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-items-center">
                        <div class="col-4">
                            <label for="inputPassword6" class="col-form-label"> TOTAL</label>
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
                                <input type="text" class="form-control" value=0 onkeyup="saltar_facturar_pedido_pos(event,'pago_con_transaccion'),limpiar_errores(),cambio(this.value)" id="pago_con_efectivo">
                            </div>
                            <span id="mensaje_forma_efectivo" style="color:red;"></span> <br>
                        </div>
                    </div>
                    <div class="row g-3 align-items-center">
                        <div class="col-4">
                            <label for="inputPassword6" class="col-form-label">TRANSACCION</label>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    $
                                </span>
                                <!-- <input type="text" class="form-control" value=0 id="pago_con_transaccion" onKeyPress="return cambio_pedido_pos(event),saltar_facturar_pedido_pos(event,'facturar_pedido_pos')" onkeyup="cambio(),llamar_ventana_fin_venta(event) ">-->
                                <input type="text" class="form-control" value=0 id="pago_con_transaccion" onkeyup="devuelta(),llamar_ventana_de_finalizar_venta_pos(event),limpiar_errores() ">

                            </div>
                            <span id="mensaje_forma_transaccion" style="color:red;"></span> <br>
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
                                <input type="text" class="form-control" id="cambio_del_pago" readonly>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!--  <button type="button" class="btn btn-primary" onclick="confirmacion_finalizar_venta()" id="facturar_pedido_pos">Aceptar</button>-->

                <button type="button" id="cancelar_pedido_pos" class="btn btn-danger" data-bs-dismiss="modal" onclick="reset_inputs_factura_pos()">Cancelar</button>
            </div>
        </div>
    </div>
</div>
</div>
</div>