<div class="modal fade" id="autocompletar_producto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar productos</h5>
                <button type="button" class="btn-close" onclick="cancelar_productos_tabla_pedido()" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_autocompletar_producto">
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
                                    <input type="hidden" class="form-control" id="codigo_internoproducto_autocompletar">
                                    <p id="codigointernoproducto_autocompletar"></p>
                                </td>
                                <td>
                                    <p id="nombre_producto_autocompletar"></p>
                                </td>
                                <td>
                                    <p id="precio_venta_autocompletar"></p>
                                    <input type="hidden" id="precioventa_autocompletar">
                                </td>
                            </tr>
                            <tr>
                                <td>Cantidad * </td>
                                <td colspan="2">
                                    <!--<div class="input-group">
                                       <a href="#" class="btn bg-muted-lt w-1 btn-icon" onclick="agregar_producto(<?php #echo $detalle['id_tabla_producto'] 
                                                                                                                    ?>)" title="Agregar producto">

                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <line x1="5" y1="12" x2="19" y2="12" />
                                            </svg>
                                        </a>
                                        <input type="number" id="producto_pedido_cantidad_autocompletar" onKeyPress="return soloNumeros(event)" class="form-control" required onkeyup="multiplicarAgregar();saltar(event,'agregar_producto_pedido')" value="1" autofocus>
                                        <a href="#" class="btn bg-muted-lt w-1 btn-icon" onclick="eliminar_cantidades(<?php #echo $detalle['id_tabla_producto'] 
                                                                                                                        ?>)">

                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <line x1="12" y1="5" x2="12" y2="19" />
                                                <line x1="5" y1="12" x2="19" y2="12" />
                                            </svg>
                                        </a>
                                    </div>-->
                                    <input type="number" id="producto_pedido_cantidad_autocompletar" onKeyPress="return soloNumeros(event)" class="form-control" required onkeyup="multiplicarAgregar();saltar(event,'agregar_producto_pedido')" value="1" autofocus>
                                    <p class="text-danger" id="error_de_cantidad"></p>
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

                                        <input type="text" class="form-control numero" id="total_autocompletar" readonly>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Notas</td>
                                <td colspan="2">
                                    <textarea class="form-control" id="notas" onkeyup="mayusculas(this);" rows="3"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="insertar_productos_tabla_pedido()" id="agregar_producto_pedido">Agregar</button>
                    <button type="button" class="btn btn-danger" onclick="cancelar_productos_tabla_pedido()">Cancelar</button>
                </div>
            </div>
        </div>
    </div>