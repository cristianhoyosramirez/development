<!-- Modal -->
<div class="modal fade" id="productos_x_categorias" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Productos por categoria</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                  <input type="hidden" class="form-control" id="codigo_internoproducto_producto_categoria">
                  <p id="codigointernoproducto_x_categoria"></p>
                </td>
                <td>
                  <p id="nombre_producto_x_categoria"></p>
                </td>
                <td>
                  <p id="precio_venta_x_categoria"></p>
                  <input type="hidden" id="precioventa_x_categoria">
                </td>
              </tr>
              <tr>
                <td>Cantidad * </td>
                <td colspan="2">
                  <div class="input-group">
                    <input type="number"  value=1 id="cantidad_producto_pedido_x_categoria" onKeyPress="return soloNumeros(event)" class="form-control" required onkeyup="multiplicarAgregar_x_categoria();saltar(event,'agregar_product_x_categoria')" autofocus>
                  </div>
                  <p class="text-danger" id="error_de_cantidad_categoria"></p>
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

                    <input type="text" class="form-control numero" id="total_x_categoria" readonly>
                  </div>
                </td>
              </tr>
              <tr>
                <td>Notas</td>
                <td colspan="2">
                  <textarea class="form-control" id="notas_x_categoria" onkeyup="minusculas_a_mayusculas()" rows="3"></textarea>
                </td>
              </tr>
            </tbody>
          </table>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="agregar_product_x_categoria" onclick="agregar_productos_desde_categoria()" class="btn btn-success" >Agregar</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>