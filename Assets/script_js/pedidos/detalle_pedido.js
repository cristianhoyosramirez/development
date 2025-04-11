function detalle_pedido() {
  var url = document.getElementById("url").value;
  var num_pedido = document.getElementById("numero_pedido_salvar").value;
  var numero_pedido = num_pedido;

  $.ajax({
    data: { numero_pedido },
    url: url + "/" + "producto/detalle_pedido",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      if (resultado.resultado == 1) {
        $("#detalle_pedido").html(resultado.detalle_pedido);
        myModal = new bootstrap.Modal(
          document.getElementById("resumen_pedido"),
          {}
        );
        myModal.show();
      }
    },
  });
}

function detalle_pedido_facturar(pedido) {
  var url = document.getElementById("url").value;
  var num_pedido = pedido;
  var numero_pedido = num_pedido;

  $.ajax({
    data: { numero_pedido },
    url: url + "/" + "producto/detalle_pedido",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      if (resultado.resultado == 1) {
        $("#detalle_pedido").html(resultado.detalle_pedido);
        myModal = new bootstrap.Modal(
          document.getElementById("resumen_pedido"),
          {}
        );
        myModal.show();
      }
    },
  });
}
