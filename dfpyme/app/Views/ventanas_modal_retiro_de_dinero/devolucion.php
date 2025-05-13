<!-- Modal -->
<div class="modal fade" id="devolucion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Devolución de articulo</h5>

      </div>
      <div class="modal-body">
        <form class="row g-3" id="formulario_devolucion">
          <div class="col-md-3">
            <label for="inputEmail4" class="form-label">Producto</label>
            <input type="hidden" class="form-control" id="codigo_producto_devolucion">
            <input type="text" class="form-control" id="devolucion_producto" onkeyup="saltar_factura_pos(event,'precio_devolucion'),buscar_por_codigo_de_barras_devolucion(event, this.value)">
            <span id="error_producto_devolucion" style="color:#FF0000;"></span>
          </div>
          <div class="col-3">
            <label for="inputAddress" class="form-label">Precio</label>
            <input type="text" class="form-control" id="precio_devolucion" onkeyup="saltar_factura_pos(event,'cantidad_devolucion')">
          </div>
          <div class="col-md-3">
            <label for="inputPassword4" class="form-label">Cantidad</label>
            <input type="text" class="form-control" value="1" id="cantidad_devolucion" onkeyup="saltar_factura_pos(event,'generar_devolucion'),total()">
          </div>
          <div class="col-md-3">
            <label for="inputPassword4" class="form-label">Total</label>
            <input type="text" class="form-control" id="total_devolucion">
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-success" onclick="devolucion()" id="generar_devolucion">Guardar</button>
      <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" onclick="cancelar_devolucion()">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<script>
  function cancelar_devolucion() {
    document.getElementById('codigo_producto_devolucion').value = ''
    document.getElementById('devolucion_producto').value = ''
    document.getElementById('precio_devolucion').value = ''
    document.getElementById('cantidad_devolucion').value = 1
    document.getElementById('total_devolucion').value = ''
    
  }
</script>