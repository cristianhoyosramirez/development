<div class="modal modal-blur fade" id="editar_cantidad_y_precio_producto_pedidoFactura" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar cantidades y precio de producto </h5>
            </div>
            <div class="modal-body">
                <form id="form_editar_producto">
                    <table class="table  table-striped">
                        <thead class="table-dark">
                            <tr>
                                <td>CÓDIGO</td>
                                <td width: 100%>DESCRIPCIÓN</td>
                                <td width: 100%></td>
                                <td width: 100%></td>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>
                                    <input type="hidden" class="form-control" id="editar_precio_id_tabla_producto">
                                    <p id="editar_precio_cantidad_producto_pedido"></p>
                                </td>
                                <td>
                                    <p id="editar_precio_nombre_producto"></p>
                                </td>
                            </tr>
                            <tr>
                                <td>Precio </td>
                                <td colspan="2">
                                    <div class="input-icon mb-3">
                                        <span class="input-icon-addon">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                                <path d="M12 3v3m0 12v3" />
                                            </svg>
                                        </span>
                                        <input type="text" class="form-control numero" id="editar_precio_y_cantidad_venta" onKeyPress="return soloNumeros(event)" onkeyup="saltar(event,'editar_precio_producto_pedido_cantidad')">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Cantidad * </td>
                                <td colspan="2">
                                    <div class="input-group">
                                        <input type="number" id="editar_precio_producto_pedido_cantidad" onKeyPress="return soloNumeros(event)" class="form-control" required onkeyup="editar_precio_multiplicarAgregar();saltar(event,'edicion_precio_cantidad_producto')" autofocus>
                                    </div>
                                    <p class="text-danger" id="editar_precio_error_de_cantidad"></p>
                                </td>
                            </tr>
                            <tr>
                                <td>Sub total</td>
                                <td colspan="2">
                                    <div class="input-icon mb-3">
                                        <span class="input-icon-addon">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                                <path d="M12 3v3m0 12v3" />
                                            </svg>
                                        </span>

                                        <input type="text" class="form-control numero" id="editar_precio_total" readonly>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Notas</td>
                                <td colspan="2">
                                    <textarea class="form-control" id="editar_precio_notas" onkeyup="minusculasAmayusculas()" rows="3"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="edicion_precio_cantidad_producto" onclick="actualizar_precio_cantidad()">Actualizar</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>