function devolucion() {
  var usuario = document.getElementById("id_usuario").value;
  //var nit_cliente = document.getElementById("id_cliente_factura_pos").value;
  var nit_cliente = '22222222';
  var codigo_producto_devolucion = document.getElementById(
    "codigo_producto_devolucion"
  ).value;
  var cantidad_devolucion = document.getElementById(
    "cantidad_devolucion"
  ).value;
  var precio_devolucion = document.getElementById("precio_devolucion").value;

  var url = document.getElementById("url").value;
  $.ajax({
    data: {
      usuario,
      nit_cliente,
      codigo_producto_devolucion,
      cantidad_devolucion,
      precio_devolucion,
    },
    url: url + "/" + "devolucion/guardar_devolucion",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      console.log(resultado);
      if (resultado.resultado == 0) {
        Swal.fire({
          icon: "error",
          title: "Error en la cantidad",
          confirmButtonText: "Aceptar",
          confirmButtonColor: "#2AA13D",
        });
      }
      if (resultado.resultado == 1) {
        $('#formulario_devolucion')[0].reset();
        $("#devolucion").modal("hide");
        Swal.fire({
          icon: "success",
          title: "Devolucion del producto : " + resultado.nombre_producto,
          confirmButtonText: "Aceptar",
          confirmButtonColor: "#2AA13D",
        });
      }

      if (resultado.resultado == 2) {
        sweet_alert_start('warning', 'No hay apertura de caja ')
      }
    },
  });
}


/**
 * Establece el autofoco en el modal finalizar venta , facturacion directa
 */
$(function () {
  $("#devolucion").on("shown.bs.modal", function (e) {
    $("#devolucion_producto").focus();
  });
});


const precio_devolucion = document.querySelector("#precio_devolucion");
function formatNumber(n) {
  n = String(n).replace(/\D/g, "");
  return n === "" ? n : Number(n).toLocaleString();
}
precio_devolucion.addEventListener("keyup", (e) => {
  const element = e.target;
  const value = element.value;
  element.value = formatNumber(value);
});
