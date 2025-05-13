$(document).ready(function () {
  var url = document.getElementById("url").value;
  $("#cliente").select2({
    width: "100%",
    placeholder: "Buscar el cliente",
    language: "es",
    theme: "bootstrap-5",
    allowClear: true,
    ajax: {
      url: url + "/" + "clientes/listado",
      type: "post",
      dataType: "json",
      delay: 200,
      data: function (params) {
        return {
          palabraClave: params.term,
        };
      },
      processResults: function (response) {
        return {
          results: response,
        };
      },
      cache: true,
    },
  });
});

function agregar_cliente() {
  var url = document.getElementById("url").value;
  var cedula = document.getElementById("cedula_cliente").value;
  var nombres_cliente = document.getElementById("nombres_cliente").value;
  var regimen_cliente = document.getElementById("regimen_cliente").value;
  var tipo_cliente = document.getElementById("tipo_cliente").value;
  var tipo_cliente = document.getElementById("tipo_cliente").value;
  var clasificacion_cliente = document.getElementById(
    "clasificacion_cliente"
  ).value;
  var telefono_cliente = document.getElementById("telefono_cliente").value;
  var celular_cliente = document.getElementById("celular_cliente").value;
  var email_cliente = document.getElementById("e-mail").value;
  var departamento_cliente = document.getElementById(
    "departamento"
  ).value;
  var municipio_cliente = document.getElementById("municipios").value;
  var direccion_cliente = document.getElementById("direccion_cliente").value;

  $.ajax({
    data: {
      cedula,
      nombres_cliente,
      regimen_cliente,
      tipo_cliente,
      clasificacion_cliente,
      telefono_cliente,
      celular_cliente,
      email_cliente,
      municipio_cliente,
      direccion_cliente,
    },
    url: url + "/" + "clientes/agregar",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);

      if (resultado.resultado == 0) {
        $('#creacion_cliente_factura_pos').modal('hide');
        Swal.fire({
          icon: 'success',
          title: 'Cliente agregado',
          
        })
      }
      if (resultado.resultado == 1) {
        alert("No se pudo insertar");
      }
    },
  });
}
