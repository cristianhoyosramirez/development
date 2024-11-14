<div class="modal modal-blur fade" id="editar_cantidades_producto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar cantidades de producto </h5>
            </div>
            <div class="modal-body">
                <table class="table  table-striped">
                    <thead class="table-dark">
                        <tr>
                            <td>CÓDIGO</td>
                            <td width: 100%>DESCRIPCIÓN</td>
                            <td width: 100%>V UNI</td>
                            <td width: 100%></td>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>
                                <input type="hidden" class="form-control" id="codigo_internoproducto_editar">
                                <p id="codigointernoproducto_editar"></p>
                            </td>
                            <td>
                                <p id="nombre_producto_editar"></p>
                            </td>
                            <td>
                                <p id="precio_venta_editar"></p>
                                <input type="hidden" id="precioventa_editar">
                            </td>
                        </tr>
                        <tr>
                            <td>Cantidad * </td>
                            <td colspan="2">
                                <div class="input-group">
                                    <input type="number" id="producto_pedido_cantidad_editar" onKeyPress="return soloNumeros(event)" class="form-control" required onkeyup="multiplicarAgregar_editar();saltar(event,'notas_editar')" autofocus>
                                </div>
                                <p class="text-danger" id="error_de_cantidad_editar"></p>
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

                                    <input type="text" class="form-control numero" id="total_editar" readonly>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Notas</td>
                            <td colspan="2">
                                <textarea class="form-control" id="notas_editar" onkeyup="minusculasAmayusculas()" rows="3"></textarea>
                            </td>
                        </tr>

                    </tbody>
                </table>

                <div id="notas_del_producto">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="actualizar_cantidades_de_pedido()">Actualizar</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>