function agregar_cliente_pedido() {
  var url = document.getElementById("url").value;
  var cedul = document.getElementById("identificacion_cliente").value;
  var cedula = cedul.replace(/[.]/g, "");
  var nombres_cliente = document.getElementById("nombres_cliente_pedido").value;
  var regimen_cliente = document.getElementById("regimen_cliente_pedido").value;
  var tipo_cliente = document.getElementById("tipo_cliente_pedido").value;
  var clasificacion_cliente = document.getElementById(
    "clasificacion_cliente_pedido"
  ).value;
  var telefono_cliente = document.getElementById(
    "telefono_cliente_pedido"
  ).value;
  var celular_cliente = document.getElementById("celular_cliente_pedido").value;
  var email_cliente = document.getElementById("e-mail_pedido").value;
  var departamento_cliente = document.getElementById(
    "departamento_pedido"
  ).value;
  var municipio_cliente = document.getElementById("municipios_pedido").value;
  var direccion_cliente = document.getElementById(
    "direccion_cliente_pedido"
  ).value;

  var error_identificacion = document.getElementById("error_identificacion");
  var error_nombres_cliente_pedido = document.getElementById(
    "error_nombres_cliente_pedido"
  );

  if (cedula == "") {
    error_identificacion.innerHTML = "Dato necesario";
    error_identificacion.style.color = "red";
  }

  if (nombres_cliente == "") {
    error_nombres_cliente_pedido.innerHTML = "Dato necesario";
    error_nombres_cliente_pedido.style.color = "red";
  }
  if (telefono_cliente == "(___) ___-____") {
    var telefono_cliente = 0;
  }

  if (celular_cliente == "(___) ___-____") {
    var celular_cliente = 0;
  }
  if (municipio_cliente == "") {
    municipio_cliente = 0;
  }

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
        $("#crear_cliente").modal("hide");
        document.getElementById("cliente").value = resultado.nit_cliente;
        document.getElementById("clientes").value = resultado.nombres_cliente;
        Swal.fire({
          icon: "success",
          title: "Cliente agregado",
        });
      }
      if (resultado.resultado == 1) {
        Swal.fire({
          icon: "error",
          title: "Identificacion ya existe ",
        });
      }
    },
  });
}
