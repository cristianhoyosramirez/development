$("#clientes_factura_pos").autocomplete(
  {
    source: function (request, response) {
      var url = document.getElementById("url").value;
      $.ajax({
        type: "POST",
        url: url + "/" + "clientes/clientes_autocompletado",
        data: request,
        success: response,
        dataType: "json",
      });
    },
  },
  {
    minLength: 1,
  },
  {
    select: function (event, ui) {
      // $("#id_cliente_factura_pos").val(ui.item.value);
      //$("#clientes_factura_pos").val(ui.item.nit_cliente);
      $("#id_cliente_factura_pos").val(ui.item.nit_cliente);
      $("#clientes_factura_pos").val(ui.item.value);
    },
  }
);

const identificacion_cliente_factura_pos =
  document.querySelector("#cedula_cliente");
function formatNumber(n) {
  n = String(n).replace(/\D/g, "");
  return n === "" ? n : Number(n).toLocaleString();
}
identificacion_cliente_factura_pos.addEventListener("keyup", (e) => {
  const element = e.target;
  const value = element.value;
  element.value = formatNumber(value);
});

$(document).ready(function () {
  $("#estado_pos").select2({
    width: "100%",
    placeholder: "Tipo de venta",
    language: "es",
    theme: "bootstrap-5",
    allowClear: true,
  });
  $("#estado_pos").select2({
    width: "100%",
    placeholder: "Tipo de venta",
    language: "es",
    theme: "bootstrap-5",
    allowClear: true,
  });
  $("#departamento").select2({
    width: "100%",
    placeholder: "Departamentos de colombia",
    language: "es",
    theme: "bootstrap-5",
    allowClear: true,
    dropdownParent: $("#creacion_cliente_factura_pos"),
  });
  $('#municipios').select2({
    width: '100%',
    placeholder: "Municipios",
    language: "es",
    theme: "bootstrap-5",
    allowClear: true,
    dropdownParent: $('#crear_cliente_factura_pos')
});

});

function validar_email() {
  var email = document.getElementById("e-mail_pedido_pos").value;
  var pattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
  var error_email = document.getElementById("error_email_factura_pos");

  if (email.match(pattern)) {
    error_email.innerHTML = "";
  } else {
    //alert('Es un es correo valido ')
    //$("#error_email").html("No es un correo válido");
    error_email.innerHTML = "No es un correo válido";
    error_email.style.color = "red";
  }
}

/**
 * Aufoco en el modal de creacion de cliente en el modulo de facturar pedido
 */
$(function () {
  $("#creacion_cliente_factura_pos").on("shown.bs.modal", function (e) {
    $("#cedula_cliente").focus();
  });
});

/**
 * Navegacion con enter en el modal de cliente
 * @param {*} e
 * @param {*} id
 */
function saltar_cliente(e, id) {
  // Obtenemos la tecla pulsada

  e.keyCode ? (k = e.keyCode) : (k = e.which);

  // Si la tecla pulsada es enter (codigo ascii 13)

  if (k == 13) {
    // Si la variable id contiene "submit" enviamos el formulario

    if (id == "submit") {
      document.forms[0].submit();
    } else {
      // nos posicionamos en el siguiente input

      document.getElementById(id).focus();
    }
  }
}
  function limpiar_creacion_cliente(){
    document.getElementById("formulario_creacion_cliente_factura_pos").reset();
  }