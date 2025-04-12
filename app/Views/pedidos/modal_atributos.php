<!-- Modal -->
<div class="modal fade" id="modalAtributos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="cerrarModalAtributos()"></button>
      </div>

      <div class="modal-body">
        <p class="h2 text-center text-primary" id="productoAddAtri">Asignación de componentes</p>
        <div class="mb-3"></div>

        <div id="divInput" style="display:none">
          <div class="row text-center align-items-center gx-2 gy-2">
            <div class="col-12 col-sm-2 d-none d-sm-block">
              <!-- Espacio en pantallas grandes -->
            </div>

            <div class="col-12 col-sm-2">
              <p id="valorUnidad" class="mb-0"></p>
            </div>

            <div class="col-12 col-sm-4">
              <div class="input-group justify-content-center">
                <!-- Botón disminuir -->
                <div class="input-group-prepend">
                  <button type="button" class="btn bg-muted-lt btn-icon" onclick="disminuir()" title="Disminuir">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                      stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                  </button>
                </div>

                <input type="hidden" id="valUnidad">
                <input type="hidden" id="codigoInternoProd">
                <!-- Input oculto para enviar al backend -->
                <input type="hidden" id="inputComponentes" name="componentes" placeholder="">
                <input
                  type="number"
                  class="form-control form-control-sm text-center custom-width"
                  value="1"
                  onkeyup="cantidad_manual(this.value)"
                  id="input_cantidadAtri"
                  onclick="this.select()"
                  inputmode="numeric"
                  oninput="validarSoloNumeros(this)"
                  pattern="[0-9]*">

                <!-- Botón aumentar -->
                <div class="input-group-append">
                  <button type="button" class="btn bg-muted-lt btn-icon" onclick="aumentar()" title="Aumentar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                      stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <line x1="12" y1="5" x2="12" y2="19" />
                      <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-2 text-sm-end text-center">
              <p id="totalProducto" class="mb-0 fw-bold"></p>
            </div>
          </div>
        </div>


        <div id="inputCantidad" style="display:none">
        </div>

        <br>
        <div id="asigCompo"></div>

        <div id="contenedorBotonesComponentes">


        </div>

        <input type="text" id="id_tabla_producto" hidden>

        <div id="row">
          <label for="" class="form-label">Nota producto</label>
          <textarea name="notaAtributo" id="notaAtributo" class="form-control" onkeyup="notaAtributo(this.value)"></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-success" id="btnAtri" onclick="finalizarAtributos()">Aceptar</button>
        <button type="button" class="btn btn-outline-danger" onclick="cerrarModalAtributos()">Cancelar</button>

      </div>
    </div>
  </div>
</div>





<script>
  function cerrarModalAtributos() {

    document.getElementById('codigoInternoProd').value = ""
    document.getElementById('valUnidad').value = ""
    document.getElementById('id_tabla_producto').value = ""
    document.getElementById('notaAtributo').value = ""
    document.getElementById('input_cantidadAtri').value = 1

    $("#modalAtributos").modal("hide");
  }
</script>

<script>
  function validarSoloNumeros(input) {
    // Elimina cualquier carácter que no sea número
    input.value = input.value.replace(/[^0-9]/g, '');
  }
</script>
<script>
  async function addOtherProduct() {
    try {
      const url = document.getElementById("url").value;
      const codigoProducto = document.getElementById("codigoInternoProd").value;
      const cantidad = document.getElementById("input_cantidadAtri").value;
      const nota = document.getElementById("notaAtributo").value;
      const id_mesa = document.getElementById("id_mesa_pedido").value;
      const id_usuario = document.getElementById("id_usuario").value;
      const mesero = document.getElementById("mesero").value;
      const componentes = document.getElementById("inputComponentes").value;

    

      const payload = {
        codigoProducto: codigoProducto,
        cantidad: cantidad,
        nota: nota,
        id_mesa: id_mesa,
        id_usuario: id_usuario,
        id_mesero: mesero,
        componentes: componentes
      };

      const response = await fetch(`${url}/producto/adicionDeProducto`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload)
      });

      const data = await response.json();

      if (data.response == "success") {

        $('#mesa_productos').html(data.productos_pedido)
        $('#valor_pedido').html(data.total_pedido)
        $('#val_pedido').html(data.total_pedido)

        $('#subtotal_pedido').val(data.sub_total)
        $('#id_mesa_pedido').val(data.id_mesa)
        $("#propina_del_pedido").val(data.propina);
        //$('#mesa_pedido').html('VENTAS DE MOSTRADOR')

        $('#modalAtributos').on('shown.bs.modal', function() {
          $('#producto').focus();
        });

        document.getElementById("id_tabla_producto").value = data.id;
        document.getElementById("asigCompo").innerHTML = data.atributos;

        $("#modalAtributos").modal("hide");
        document.getElementById('input_cantidadAtri').value = 1
        document.getElementById('notaAtributo').value = "";
        document.getElementById('producto').value = "";
        sweet_alert_centrado('success', 'agregado')
      }

    } catch (error) {
      console.error('Error en addOtherProduct:', error);
    }
  }
</script>


<!-- <script>
  function cantidadManual(cantidad) {
    // Obtener el valor unitario desde el input (sin separadores de miles)
    let precioUnitario = parseFloat(document.getElementById('valUnidad').value) || 0;

    // Convertir la cantidad a número por seguridad
    let cantidadNum = parseInt(cantidad) || 0;

    console.log(cantidadNum)

    // Calcular total
    let total = precioUnitario * cantidadNum;

    // Formatear total con separadores de miles
    let totalFormateado = new Intl.NumberFormat('es-CO').format(total);

    

    // Mostrar en el <p> con id="totalProducto"
    document.getElementById('totalProducto').innerText = `$ ${totalFormateado}`;
  }
</script> -->

<script>
  function cantidad_manual(cantidad) {
    // Obtener el valor unitario desde el input (sin separadores de miles)
    let precioUnitario = parseFloat(document.getElementById('valUnidad').value) || 0;



    // Convertir la cantidad a número por seguridad
    let cantidadNum = parseInt(cantidad) || 0;

    /* // Verificar si la cantidad es mayor que cero
    if (cantidadNum > 0) {
      // Calcular total
      let total = precioUnitario * cantidadNum;

      // Formatear total con separadores de miles
      let totalFormateado = new Intl.NumberFormat('es-CO').format(total);

      // Mostrar en el <p> con id="totalProducto"
      document.getElementById('totalProducto').innerText = `$ ${totalFormateado}`;
    }  */
    /* else if (cantidad === "") {
          // Si la cantidad está vacía, no hacer nada
          document.getElementById('totalProducto').innerText = ''; // Limpiar el total
        } else {
          // Si la cantidad no es válida (cero o negativa), puedes mostrar un mensaje o simplemente no hacer nada
          document.getElementById('totalProducto').innerText = '$ 0f'; // Opcional: Mostrar $0 si la cantidad no es válida
        } */
  }
</script>


<script>
  function obtenerValorNumerico(str) {
    return parseInt(str.replace(/[^0-9]/g, '')) || 0;
  }

  function actualizarTotal() {
    const cantidad = parseInt(document.getElementById('input_cantidadAtri').value) || 0;
    const valorUnidadTexto = document.getElementById('valorUnidad').innerText;
    const valorUnidad = obtenerValorNumerico(valorUnidadTexto);

    const total = cantidad * valorUnidad;
    const totalFormateado = new Intl.NumberFormat('es-CO').format(total);

    document.getElementById('totalProducto').innerText = `$ ${totalFormateado}`;
  }

  function aumentar() {
    const input = document.getElementById('input_cantidadAtri');
    let valor = parseInt(input.value) || 0;
    input.value = valor + 1;
    actualizarTotal();
  }

  function disminuir() {
    const input = document.getElementById('input_cantidadAtri');
    let valor = parseInt(input.value) || 0;
    if (valor > 1) {
      input.value = valor - 1;
      actualizarTotal();
    }
  }

  // También ejecutamos el total si el usuario escribe a mano
  document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('input_cantidadAtri').addEventListener('input', actualizarTotal);
  });
</script>