<?= $this->extend('template/producto') ?>
<?= $this->section('title') ?>
RESOLUCIÓN ElECTRÓNICA
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>


<div class="container">
  <div class="row text-center align-items-center flex-row-reverse">
    <div class="col-lg-auto ms-lg-auto">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Salones</a></li>
          <li class="breadcrumb-item"><a href="#">Mesas</a></li>
          <li class="breadcrumb-item"><a href="#">Usuarios</a></li>
          <li class="breadcrumb-item"><a href="#">Empresa</a></li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<br>
<!--Sart row-->
<div class="container">
  <div class="row text-center align-items-center flex-row-reverse">
    <div class="col-lg-auto ms-lg-auto">
      <a type="button" class="btn btn-outline-warning btn-pill w-100" data-bs-toggle="modal" data-bs-target="#resolucion_electronica">
        Agregar resolución
      </a>
    </div>

    <div class="col-lg-auto ms-lg-auto">
      <p class="text-primary h3">RESOLUCIÓN ELECTRONICA </p>
    </div>
    <div class="col-12 col-lg-auto mt-3 mt-lg-0">
      <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
    </div>
  </div>
</div>
<br>


<div class=" container col-md-12">
  <div class="card">
    <div class="card-body">
      <table class="table">
        <thead class="table-dark">
          <tr>

            <td>Número resolución</td>
            <td>Fecha Dian </td>
            <td>Rango inicial</td>
            <td>Rango final</td>
            <td>Vigencia</td>
            <td>Avisar cuando falten </td>
            <td>Acciones</td>
          </tr>
        </thead>
        <tbody id="resoluciones_de_facturacion">
          <input type="hidden" id="url" value="<?php echo base_url() ?>">
          <?php foreach ($resoluciones_dian as $detalle) { ?>
            <tr>

              <td><?php echo $detalle['numero'] ?></td>
              <td><?php echo $detalle['date_begin'] ?></td>
              <td><?php echo $detalle['number_begin'] ?></td>
              <td><?php echo $detalle['number_end'] ?></td>
              <td><?php echo $detalle['vigency'] ?></td>
              <td><?php #echo $detalle['alerta_facturacion'] 
                  ?></td>
              <td>

                <div class="breadcrumb m-0">
                  <div class="form-check form-switch">



                  </div> &nbsp; &nbsp;&nbsp; <button type="button" class="btn btn-outline-success btn-icon" onclick="editar_resolucion_electronica(<?php echo $detalle['id'] ?>)">Editar</button>

                </div>


              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

    </div>
  </div>


  <!-- Modal -->
  <div class="modal fade" id="resolucion_electronica" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Agregar resolucion de facturacion electrónica </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-2">
            <div class="col-md-3">
              <label for="inputEmail4" class="form-label">Número de resolución</label>
              <input type="text" class="form-control" id="numero_dian" name="numero_dian">
              <span class="text-danger " id="error_numero_dian"></span>
            </div>
            <div class="col-md-3">
              <label for="inputPassword4" class="form-label">Fecha de expedición</label>
              <input type="date" class="form-control" id="fecha_inicial" name="fecha_dian" value="<?php echo date('Y-m-d') ?>">
              <span class="text-danger " id="error_fecha_inicial"></span>
            </div>
            <div class="col-md-3">
              <label for="inputPassword4" class="form-label">Fecha de caducidad</label>
              <input type="date" class="form-control" id="fecha_final" name="fecha_caducidad" value="<?php echo date('Y-m-d') ?>">
              <span class="text-danger " id=""></span>
            </div>


            <div class="col-3">
              <label for="inputAddress2" class="form-label">Prefijo </label>
              <input type="text" class="form-control" id="prefijo_dian" name="prefijo_dian">
              <span class="text-danger error-text prefijo_error" id="error_prefijo"></span>
            </div>
            <div class="col-md-3">
              <label for="inputCity" class="form-label">Desde el número </label>
              <input type="text" class="form-control" id="numero_inicial" name="numero_inicial">
              <span class="text-danger " id="error_numero_inicial"></span>
            </div>

            <div class="col-3">
              <label for="inputAddress2" class="form-label">Hasta el número </label>
              <input type="text" class="form-control" id="numero_final" name="numero_final">
              <span class="text-danger error-text numero_final_error" id="error_numero_final"></span>
            </div>
            <div class="col-md-3">
              <label for="inputCity" class="form-label">Vigencia</label>
              <input type="text" class="form-control" id="vigencia" name="vigencia">
              <span class="text-danger error-text vigencia_error" id="error_vigencia"></span>
            </div>



            <div class="col-md-3">
              <label for="inputCity" class="form-label">Alertar cuando falten </label>
              <input type="number" class="form-control" id="alerta" name="alerta">
              <span class="text-danger" id="error_alerta"></span>
            </div>




            <div class="modal-footer">
              <button type="submit" class="btn btn-outline-success" onclick="agregar_resolucion_electronica()">Guardar </button>
              <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>


  <!-- Modal -->
  <div class="modal fade" id="editar_resolucion_electronica" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Cambiar datos de la resolución de facturacion electrónica </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-2">
            <div class="col-md-3">
              <label for="inputEmail4" class="form-label">Número de resolución</label>
              <input type="hidden" id="id_resolucion">
              <input type="text" class="form-control" id="numero_dian_edicion" name="numero_dian_edicion">
              <span class="text-danger " id="error_numero_dian_edicion"></span>
            </div>
            <div class="col-md-3">
              <label for="inputPassword4" class="form-label">Fecha de expedición</label>
              <input type="date" class="form-control" id="fecha_inicial_edicion" name="fecha_dian_edicion" value="<?php echo date('Y-m-d') ?>">
              <span class="text-danger " id="error_fecha_inicial_edicion"></span>
            </div>
            <div class="col-md-3">
              <label for="inputPassword4" class="form-label">Fecha de caducidad</label>
              <input type="date" class="form-control" id="fecha_final_edicion" name="fecha_caducidad_edicion" value="<?php echo date('Y-m-d') ?>">
              <span class="text-danger " id=""></span>
            </div>


            <div class="col-3">
              <label for="inputAddress2" class="form-label">Prefijo </label>
              <input type="text" class="form-control" id="prefijo_dian_edicion" name="prefijo_dian_edicion">
              <span class="text-danger error-text prefijo_error" id="error_prefijo_edicion"></span>
            </div>
            <div class="col-md-3">
              <label for="inputCity" class="form-label">Desde el número </label>
              <input type="text" class="form-control" id="numero_inicial_edicion" name="numero_inicial_edicion">
              <span class="text-danger " id="error_numero_inicial_edicion"></span>
            </div>

            <div class="col-3">
              <label for="inputAddress2" class="form-label">Hasta el núemro </label>
              <input type="text" class="form-control" id="numero_final_edicion" name="numero_final_edicion">
              <span class="text-danger error-text numero_final_error_edicion" id="error_numero_final"></span>
            </div>
            <div class="col-md-3">
              <label for="inputCity" class="form-label">Vigencia</label>
              <input type="text" class="form-control" id="vigencia_edicion" name="vigencia_edicion">
              <span class="text-danger error-text vigencia_error" id="error_vigencia_edicion"></span>
            </div>



            <div class="col-md-3">
              <label for="inputCity" class="form-label">Alertar cuando falten </label>
              <input type="number" class="form-control" id="alerta_edicion" name="alerta_edicion">
              <span class="text-danger" id="error_alerta_edicion"></span>
            </div>




            <div class="modal-footer">
              <button type="submit" class="btn btn-outline-success" onclick="cambiar_resolucion_electronica()">Guardar </button>
              <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>


  <script>
    function agregar_resolucion_electronica() {

      var url = document.getElementById("url").value;
      var numero = document.getElementById("numero_dian").value;
      var numero_inicial = document.getElementById("numero_inicial").value;
      var numero_final = document.getElementById("numero_final").value;
      var fecha_inicial = document.getElementById("fecha_inicial").value;
      var fecha_final = document.getElementById("fecha_final").value;
      var vigencia = document.getElementById("vigencia").value;
      var alerta = document.getElementById("alerta").value;
      var prefijo = document.getElementById("prefijo_dian").value;


      if (numero == "") {
        $('#error_numero_dian').html('No hay numero valido')
      }
      if (fecha_inicial == "") {
        $('#error_fecha_inicial').html('No hay fecha inicial válida ')
      }
      if (fecha_final == "") {
        $('#error_fecha_final').html('No hay fecha final válida ')
      }
      if (prefijo == "") {
        $('#error_prefijo').html('No hay prefijo válido ')
      }
      if (numero_inicial == "") {
        $('#error_numero_inicial').html('No un numero válido  ')
      }
      if (numero_final == "") {
        $('#error_numero_final').html('No un numero válido  ')
      }
      if (prefijo == "") {
        $('#error_prefijo').html('No hay prefijo válido ')
      }
      if (vigencia == "") {
        $('#error_vigencia').html('No hay vigencia definida')
      }
      if (vigencia == "") {
        $('#error_alerta').html('No hay alerta definida')
      }
      if (numero != "" && fecha_inicial != "" && fecha_final != "" && numero_inicial != "" && numero_final != "" && vigencia != "" && alerta != "") {
        $.ajax({
          data: {
            numero,
            numero_inicial,
            numero_final,
            fecha_inicial,
            fecha_final,
            vigencia,
            alerta,
            prefijo
          },
          url: url + "/" + "empresa/agregar_resolucion_electronica",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {
              $("#resolucion_electronica").modal("hide");
              $('#resoluciones_de_facturacion').html(resultado.resoluciones)

              sweet_alert_start('success', 'Resolución agregada')

            }
          },
        });
      }
    }
  </script>

  <script>
    function cambiar_resolucion_electronica() {

      var url = document.getElementById("url").value;
      var numero = document.getElementById("numero_dian_edicion").value;
      var numero_inicial = document.getElementById("numero_inicial_edicion").value;
      var numero_final = document.getElementById("numero_final_edicion").value;
      var fecha_inicial = document.getElementById("fecha_inicial_edicion").value;
      var fecha_final = document.getElementById("fecha_final_edicion").value;
      var vigencia = document.getElementById("vigencia_edicion").value;
      var alerta = document.getElementById("alerta_edicion").value;
      var prefijo = document.getElementById("prefijo_dian_edicion").value;
      var id = document.getElementById("id_resolucion").value;


      if (numero == "") {
        $('#error_numero_dian_edicion').html('No hay numero valido')
      }
      if (fecha_inicial == "") {
        $('#error_fecha_inicial_edicion').html('No hay fecha inicial válida ')
      }
      if (fecha_final == "") {
        $('#error_fecha_final_edicion').html('No hay fecha final válida ')
      }
      if (prefijo == "") {
        $('#error_prefijo_edicion').html('No hay prefijo válido ')
      }
      if (numero_inicial == "") {
        $('#error_numero_inicial_edicion').html('No un numero válido  ')
      }
      if (numero_final == "") {
        $('#error_numero_final_edicion').html('No un numero válido  ')
      }
      if (prefijo == "") {
        $('#error_prefijo_edicion').html('No hay prefijo válido ')
      }
      if (vigencia == "") {
        $('#error_vigencia').html('No hay vigencia definida')
      }
      if (vigencia == "") {
        $('#error_alerta_edicion').html('No hay alerta definida')
      }
      if (numero != "" && fecha_inicial != "" && fecha_final != "" && numero_inicial != "" && numero_final != "" && vigencia != "" && alerta != "") {
        $.ajax({
          data: {
            numero,
            numero_inicial,
            numero_final,
            fecha_inicial,
            fecha_final,
            vigencia,
            alerta,
            prefijo,
            id
          },
          url: url + "/" + "empresa/actualizar_resolucion_electronica",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {
              $("#editar_resolucion_electronica").modal("hide");
              $('#resoluciones_de_facturacion').html(resultado.resoluciones)

              setTimeout(function() {
                window.location.reload();
              }, 2000); // 2000 milisegundos = 2 segundos

              sweet_alert_start('success', 'Cambios efectuados')

            }
          },
        });
      }
    }
  </script>


  <script>
    function editar_resolucion_electronica(id_resolucion) {
      var url = document.getElementById("url").value;
      $.ajax({
        data: {
          id_resolucion
        },
        url: url +
          "/" +
          "empresa/editar_resolucion_electronica",
        type: "post",
        success: function(resultado) {
          var resultado = JSON.parse(resultado);
          if (resultado.resultado == 1) {


            $("#numero_dian_edicion").val(resultado.numero);
            $("#fecha_inicial_edicion").val(resultado.fecha_inicial);
            $("#fecha_final_edicion").val(resultado.fecha_final);
            $("#prefijo_dian_edicion").val(resultado.prefijo);
            $("#numero_inicial_edicion").val(resultado.inicial);
            $("#numero_final_edicion").val(resultado.final);
            $("#vigencia_edicion").val(resultado.vigencia);
            $("#alerta_edicion").val(resultado.alerta);
            $("#id_resolucion").val(resultado.id_resolucion);
            $("#editar_resolucion_electronica").modal("show");



          }
        },
      });

    }
  </script>

  <?= $this->endSection('content') ?>