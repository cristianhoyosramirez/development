<!-- Modal -->
<div class="modal fade" id="facturar_pedido_partir_factura" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p class="h1 text-center">FINALIZAR VENTA</p>
                <hr class="dropdown-divider text-primary">
                </li>
                
               

                <div class="row">
                    <div class="col-md-5">
                        <label for="inputEmail4" class="form-label">
                            <p class="fs-1 h5"> VALOR A PAGAR </p>
                        </label>
                    </div>
                    <div class="col-md-7">
                        <div class="input-group mb-2">
                            <span class="input-group-text">
                                $
                            </span>
                            <input type="number" class="form-control" name="valor_total_a_pagar_partir_factura" id="valor_total_a_pagar_partir_factura" readonly>
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
                            <input type="text" class="form-control" name="efectivo" value=0 onkeyup="saltar_facturar_pedido(event,'transaccion_partir_factura');" id="efectivo_partir_factura">
                        </div>
                        <span id="error_efectivo_partir_factura" style="color:red" ></span>
                    </div>
                </div><br>
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
                            <input type="text" value="0" class="form-control" value="0" onkeyup="saltar_facturar_pedido(event,'finalizar_venta_partir_factura'); cambio_partir_factura()" name="transaccion" id="transaccion_partir_factura">
                        </div>
                        <span id="error_transaccion_partir_factura" style="color:red" ></span>
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
                            <input type="number" class="form-control" name="cambio" readonly id="cambio_partir_factura">
                        </div>
                        <span id="error_cambio_partir_factura" style="color:red" ></span>
                    </div>
                </div>

                <div class="modal-footer">

                    <button type="button" data-bs-dismiss="modal" onclick="cancelar_partir_factura()" class="btn btn-danger">Cancelar</button>
                    <button type="button"  id="finalizar_venta_partir_factura" onclick="finalizar_venta_partir_factura()" class="btn btn-primary">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
</div>