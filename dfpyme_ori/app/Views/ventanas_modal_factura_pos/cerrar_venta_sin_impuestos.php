<!-- Modal -->
<div class="modal fade" id="cerrar_venta_sin_impuestos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row g-3 align-items-center">
                    <div class="col-4">
                        <p class="text-dark">TOTAL</p>
                    </div>
                    <div class="col-6">
                        <div class="input-group mb-2">
                            <span class="input-group-text">
                                $
                            </span>
                            <input type="text" class="form-control" id="total_sin_imp" readonly>
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
                            <input type="text" class="form-control" id="efectivo_pago_sin_imp" readonly>
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
                            <input type="text" class="form-control" id="cambio_pago_sin_imp" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form action="<?= base_url('factura_pos/imprimir_factura_sin_impuestos') ?>" method="post">
                    <input type="hidden" id="numero_de_factura_sin_imp" name="id_de_factura">
                    <button type="button" class="btn btn-success" onclick="impresion_de_factura()" onkeydown="return allowOnlyAlphabets(event);" id="imprimir_factura" >Imprimir factura</button>
                </form>
                <form action="<?= base_url('factura_pos/factura_pos') ?>" method="POST">
                    <input type="hidden" id="id_usuario_sin_impuestos" name="id_usuario">
                    <input type="hidden" name="apertura_cajon" value=1>
                    <button type="submit" id="ver_todos_los_pedidos" class="btn btn-primary">Guardar y registrar nueva venta</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>