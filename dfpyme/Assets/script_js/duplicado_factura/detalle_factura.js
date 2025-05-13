function detalle_de_factura(id_factura) {
  
  var url = document.getElementById("url").value;
  $.ajax({
    data: {
      id_factura,
    },
    url: url + "/" + "consultas_y_reportes/detalle_factura",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      if (resultado.resultado == 1) {
        $("#productos_factura").html(
          resultado.productos
        );
        myModal = new bootstrap.Modal(
          document.getElementById("detalle_factura"),
          {}
        );
        myModal.show();
      }
    },
  });
}
