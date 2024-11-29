<!-- Modal -->
<div class="modal fade" id="crear_cliente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">
          Crear cliente
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3" id="creacion_cliente_electronico" method="POST" action="<?php echo base_url() ?>/clientes/agregar">
          <div class="col-md-2">
            <label for="inputEmail4" class="form-label">Tipo de persona</label>
            <select class="form-select" id="tipo_persona" name="tipo_persona">
              <option value="2">Natural </option>
              <option value="1">Juridica</option>
            </select>
            <span class="text-danger error-text tipo_persona_error"></span>
          </div>
          <div class="col-md-3">
            <label for="inputPassword4" class="form-label">
              Tipo de documento
            </label>
            <?php $identificacion = model('TiposDocumento')->findALL(); ?>
            <select class="form-select" aria-label="Default select example" id="tipo_documento" name="tipo_documento">
              <?php foreach ($identificacion as $detalle) : ?>

                <option value="<?php echo $detalle['codigo'] ?>"><?php echo $detalle['descripcion'] ?></option>

              <?php endforeach  ?>
            </select>
          </div>
          <div class="col-3">
            <label for="inputAddress" class="form-label">Número de identificación </label>
            <input type="text" class="form-control" id="identificacion" name="identificacion" onkeyup="saltar_factura_pos(event,'dv')">
            <span class="text-danger error-text identificacion_error"></span>
          </div>
          <div class="col-1">
            <label for="inputAddress2" class="form-label">DV</label>
            <input type="text" class="form-control" id="dv" name="dv" onkeyup="saltar_factura_pos(event,'nombres')">
            <span class="text-danger error-text dv_error"></span>
          </div>
          <div class="col-md-3">
            <label for="inputCity" class="form-label">Régimen</label>
            <?php $regimen = model('regimenModel')->findALL(); ?>
            <select class="form-select" aria-label="Default select example" id="regimen" name="regimen">
              <?php foreach ($regimen as $detalle) : ?>

                <option value="<?php echo $detalle['idregimen'] ?>"><?php echo $detalle['nombreregimen'] ?></option>

              <?php endforeach  ?>
            </select>

          </div>
          <div class="col-md-3">
            <label for="inputCity" class="form-label">Tipo de cliente </label>

            <?php $tipo_cliente = model('tipoClienteModel')->findALL(); ?>
            <select class="form-select" aria-label="Default select example" name="tipo_ventas" id="tipo_ventas">
              <?php foreach ($tipo_cliente as $detalle) : ?>

                <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['descripcion'] ?></option>

              <?php endforeach  ?>
            </select>
          </div>
          <div class="col-md-3">
            <label for="inputCity" class="form-label">Clasificación comercial
            </label>

            <?php $clasificacion_cliente = model('clasificacionClienteModel')->findALL(); ?>
            <select class="form-select" aria-label="Default select example" name="clasificacion" id="clasificacion">
              <?php foreach ($clasificacion_cliente as $detalle) : ?>

                <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['descripcion'] ?></option>

              <?php endforeach  ?>
            </select>
          </div>
          <div class="col-md-3">
            <label for="inputCity" class="form-label">Nombres
            </label>
            <input type="text" class="form-control" id="nombres" name="nombres" onkeyup="saltar_factura_pos(event,'apellidos')">
            <span class="text-danger error-text nombres_error"></span>
          </div>
          <div class="col-md-3">
            <label for="inputCity" class="form-label">Apellidos
            </label>
            <input type="text" class="form-control" id="apellidos" name="apellidos" onkeyup="saltar_factura_pos(event,'razon_social')">
            <span class="text-danger error-text apellidos_error"></span>
          </div>
          <div class="col-md-4">
            <label for="inputCity" class="form-label">Razón social
            </label>
            <input type="text" class="form-control" id="razon_social" name="razon_social" onkeyup="saltar_factura_pos(event,'nombre_comercial')">
            <span class="text-danger error-text razon_social_error"></span>
          </div>
          <div class="col-md-4">
            <label for="inputCity" class="form-label">Nombre comercial
            </label>
            <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial" onkeyup="saltar_factura_pos(event,'direccion')">
            <span class="text-danger error-text nombre_comercial_error"></span>
          </div>
          <div class="col-md-4">
            <label for="inputCity" class="form-label">Dirección
            </label>
            <input type="text" class="form-control" id="direccion" name="direccion" onkeyup="saltar_factura_pos(event,'departamento')">
            <span class="text-danger error-text direccion_error"></span>
          </div>
          <div class="col-md-3">
            <label for="inputCity" class="form-label">Departamento
            </label>
            <?php $departamento = model('departamentoModel')->where('idpais', 49)->findAll(); ?>
            <select class="form-select" name="departamento" id="departamento" onchange="departamentoCiudad()">
              <?php foreach ($departamento as $detalle) : ?>

                <option value="<?php echo $detalle['iddepartamento'] ?>"><?php echo $detalle['nombredepartamento'] . "-" . $detalle['code']  ?></option>

              <?php endforeach  ?>
            </select>
            <span class="text-danger error-text departamento_error"></span>
          </div>
          <div class="col-md-3">
            <label for="inputCity" class="form-label">Ciudad
            </label>
            <?php $postal = model('CodigoPostalModel')->findAll();  ?>

            <select class="form-select" id="ciudad" name="ciudad">

            </select>
            <span class="text-danger error-text ciudad_error"></span>
          </div>
          <div class="col-md-3">
            <label for="inputCity" class="form-label">Confirmar ciudad
            </label>


            <select class="form-select" name="municipios" id="municipios">


            </select>
            <span class="text-danger error-text municipios_error"></span>
          </div>

          <div class="col-md-3">
            <label for="inputCity" class="form-label">Código postal
            </label>
            <?php $postal = model('CodigoPostalModel')->findAll();  ?>

            <select class="form-select" id="codigo_postal" name="codigo_postal">

              <?php foreach ($postal as $detalle) { ?>

                <option value="<?php echo $detalle['c_postal'] ?>"><?php echo $detalle['ciudad'] . "-" . $detalle['departamento'] . "-" . $detalle['c_postal'] ?> </option>

              <?php } ?>

            </select>
            <span class="text-danger error-text codigo_postal_error"></span>
          </div>
          <div class="col-md-3">
            <label for="inputCity" class="form-label">Correo electrónico
            </label>
            <input type="email" class="form-control" id="correo_electronico" name="correo_electronico">
            <span class="text-danger error-text correo_electronico_error"></span>
          </div>
          <div class="col-md-3">
            <label for="inputCity" class="form-label">Teléfono /celular
            </label>
            <input type="text" class="form-control" id="telefono" name="telefono">
            <span class="text-danger error-text telefono_error"></span>
          </div>
          <div class="col-md-3">
            
            <label for="inputCity" class="form-label">Detalles tributarios del cliente
            </label>
              <?php $impuestos = model('impuestosModel')->where('estado', 'true')->findAll(); ?>
              <select name="impuestos" id="impuestos">
                <?php foreach ($impuestos as $detalle) { ?>
                  <option value="<?php echo $detalle['codigo'] ?>"><?php echo $detalle['codigo'] . "_" . $detalle['nombre'] . "_" . $detalle['descripcion'] ?></option>
                <?php } ?>
              </select>
              <span class="text-danger error-text impuestos_error"></span>
          </div>

          <div class="col-md-3">
            <label for="inputCity" class="form-label">Valores de la casilla 53 o 54 de RUT
            </label>
            <?php $responsabilidad_fiscal = model('responsabilidadFiscalModel')->where('estado', 'true')->findAll() ?>

            <select name="responsabilidad_fiscal[]" id="responsabilidad_fiscal" multiple="multiple">
              <?php foreach ($responsabilidad_fiscal as $detalle) { ?>
                <option value="<?php echo $detalle['codigo'] ?>"><?php echo $detalle['descripcion'] ?></option>
              <?php } ?>
            </select>
            <span class="text-danger error-text responsabilidad_fiscal_error"></span>
          </div>

          <div class="modal-footer">
            <div class="row">
              <div class="col">
                <button type="submit" class="btn btn-outline-success w-100" id="btn_crear_cliente">Crear </button>
              </div>
              <div class="col">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>

<!-- <script>
  $(document).ready(function() {
    $('#departamento').on('change', function() {
      var valorSelect1 = $(this).val();

      var url = document.getElementById("url").value;
      //var code_departamento = document.getElementById("departamento").value;


      $.ajax({
        url: url +
          "/" +
          "eventos/municipios",
        type: "post",
        method: 'POST',
        dataType: 'json',
        data: {
          valorSelect1: valorSelect1
        },
        success: function(data) {
          //$('#municipios').empty();
          //$('#ciudad').empty();

          var resultado = JSON.parse(data);

          

          /*   $.each(data, function(key, value) {
              $('#municipios').append($('<option>', {
                value: value.value,
                text: value.text
              }));
            }); */

        
        },
        error: function(xhr, textStatus, errorThrown) {
          console.log('Error: ' + errorThrown);
        }
      });
    });
  });
</script>  -->

<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/departamentoCiudad.js"></script>

