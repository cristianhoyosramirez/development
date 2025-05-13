
$("#clientes").autocomplete({
  source: function(request, response) {
      var url = document.getElementById("url").value;
      $.ajax({
          type: "POST",
          url: url + "/" + "clientes/clientes_autocompletado",
          data: request,
          success: response,
          dataType: "json",
      });
  },
}, {
  minLength: 1,
}, {
  select: function(event, ui) {
      $("#clientes").val(ui.item.value);
      $("#cliente").val(ui.item.nit_cliente);
  },
});

var input = document.getElementById('clientes');

input.onkeydown = function() {
    const key = event.key;
    if (key === "Backspace") {
        document.getElementById('cliente').value = "";
    }
};

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

/**
 * Aufoco en el modal de creacion de cliente en el modulo de facturar pedido
 */
$(function () {
  $("#crear_cliente").on("shown.bs.modal", function (e) {
    $("#identificacion_cliente").focus();
  });
});


function soloNumeros(e) {
  var key = window.Event ? e.which : e.keyCode;
  return key >= 48 && key <= 57;
}

const identificacion_cliente = document.querySelector("#identificacion_cliente");
function formatNumber(n) {
  n = String(n).replace(/\D/g, "");
  return n === "" ? n : Number(n).toLocaleString();
}
identificacion_cliente.addEventListener("keyup", (e) => {
  const element = e.target;
  const value = element.value;
  element.value = formatNumber(value);
});

$(document).ready(function () {
  $('#estado').select2({
      width: '100%',
      placeholder: "Tipo de venta",
      language: "es",
      theme: "bootstrap-5",
      allowClear: true
  });

  $('#regimen_cliente_pedido').select2({
      width: '100%',
      placeholder: "Régimen",
      language: "es",
      theme: "bootstrap-5",
      allowClear: true,
      dropdownParent: $('#crear_cliente')
  });
  $('#tipo_cliente_pedido').select2({
      width: '100%',
      placeholder: "Tipo de cliente",
      language: "es",
      theme: "bootstrap-5",
      allowClear: true,
      dropdownParent: $('#crear_cliente')
  });
  $('#clasificacion_cliente_pedido').select2({
      width: '100%',
      placeholder: "Clasificación del cliente",
      language: "es",
      theme: "bootstrap-5",
      allowClear: true,
      dropdownParent: $('#crear_cliente')
  });
  $('#departamento_pedido').select2({
      width: '100%',
      placeholder: "Departamentos",
      language: "es",
      theme: "bootstrap-5",
      allowClear: true,
      dropdownParent: $('#crear_cliente')
  });
  $('#municipios_pedido').select2({
      width: '100%',
      placeholder: "Municipios",
      language: "es",
      theme: "bootstrap-5",
      allowClear: true,
      dropdownParent: $('#crear_cliente')
  });
});


/**
 * Validacion de email
 */

function validar_email(){
  var email= document.getElementById("e-mail_pedido").value;
  var pattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
  var error_email=document.getElementById("error_email")

  if(email.match(pattern)){
    error_email.innerHTML="";
  }else{
     //alert('Es un es correo valido ')
     //$("#error_email").html("No es un correo válido");
     error_email.innerHTML="No es un correo válido";
     error_email.style.color="red";
  }
}
