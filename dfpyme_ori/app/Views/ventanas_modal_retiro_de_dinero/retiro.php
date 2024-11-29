<!-- Modal -->
<div class="modal fade" id="retiro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Retiro de dinero</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <input type="hidden" id="url" value="<?php echo base_url() ?>">
          <?php $cuentas = model('cuentaRetiroModel')->orderBy('id', 'asc')->findAll(); ?>
          <div class="col-md-6">
            <label for="inputState" class="form-label">Cuenta</label>
            <select id="cuenta_retiro" class="form-select" name="cuenta_retiro" id="cuenta_retiro" onchange="rubros_retiro()" onkeyup="saltar_factura_pos(event,'cuenta_rubro')">
              <option value="">Cuenta de gasto </option>
              <?php foreach ($cuentas as $detalle) { ?>
                <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['nombre_cuenta'] ?></option>
              <?php } ?>
            </select>
            <span id="error_cuenta_rubro" style="color:#FF0000" ;></span>
          </div>
          <div class="col-md-6">
            <label for="inputState" class="form-label">Rubro</label>
            <select id="cuenta_rubro" class="form-select" name="cuenta_rubro">
              <option></option>
            </select>
            <span id="error_cuenta_rubro" style="color:#FF0000" ;></span>
          </div>

          <div class="col-md-6">
            <label for="formGroupExampleInput" class="form-label">Valor</label>
            <input type="text" class="form-control" id="valor_retiro" onkeyup="saltar_factura_pos(event,'concepto_retiro')">
            <span id="error_valor_retiro" style="color:#FF0000" ;></span>
          </div>



          <div class="col-md-6">
            <label for="inputPassword4" class="form-label">Concepto</label>
            <textarea class="form-control" id="concepto_retiro" rows="3"></textarea>
            <span id="error_concepto_retiro" style="color:#FF0000" ;></span>
          </div>
          <div class="col-md-6">
          </div>
          <div class="col-md-4">
            <button type="button" class="btn btn-success" onclick="retiro_efectivo()" id="btn_retiro">Guardar</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  function rubros_retiro() {
    var cuenta_retiro = document.getElementById("cuenta_retiro").value;
    var url = document.getElementById("url").value;

    $.ajax({
      data: {
        cuenta_retiro,
      },
      url: url + "/" + "devolucion/cuenta_rubro",
      type: "POST",
      success: function(resultado) {
        var resultado = JSON.parse(resultado);


        if (resultado.resultado == 1) {
          $('#cuenta_rubro').html(resultado.rubros)
        }
      },
    });


  }
</script>