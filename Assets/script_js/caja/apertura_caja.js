const apertura_caja = document.querySelector("#apertura_caja");

function formatNumber(n) {
  n = String(n).replace(/\D/g, "");
  return n === "" ? n : Number(n).toLocaleString();
}
apertura_caja.addEventListener("keyup", (e) => {
  const element = e.target;
  const value = element.value;
  element.value = formatNumber(value);
});

function abrir_caja() {
  var apertura = document.getElementById("apertura_caja").value;
  if (apertura == "") {
    $("#error_apertura_caja").html("Dato errado");
    document.getElementById("apertura_caja").autofocus; 
    
  } else if (apertura != "") {
    Swal.fire({
      icon: "question",
      title: "!Va a realizar la apertura de caja! con un valor de " + "$"+apertura,
      showCancelButton: true,
      confirmButtonText: "Aceptar",
      confirmButtonColor: "#2AA13D",
      cancelButtonText: "Cancelar",
      cancelButtonColor: "#C13333",
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        $("#error_apertura_caja").html("");
        $("#formulario_apertura").submit();
      }
    });
  }
}

function saltar_apertura(e, id) {
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





