function consultar_facturas_por_rango_de_fechas() {
  var fecha_inicial = document.getElementById("fecha_inicial").value;
  var fecha_final = document.getElementById("fecha_final").value;
  var id_usuario = document.getElementById("id_usuario").value;

  var url = document.getElementById("url").value;
  $.ajax({
    data: {
      fecha_inicial,
      fecha_final,
      id_usuario
    },
    url: url + "/" + "consultas_y_reportes/facturas_por_rango_de_fechas",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      if (resultado.resultado == 1) {
        $("#facturas").html(resultado.facturas);
      }
    },
  });
}
