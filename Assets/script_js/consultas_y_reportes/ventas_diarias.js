function ventas_diarias() {
  var url = document.getElementById("url").value;
  var fecha = document.getElementById("fecha_reporte").value;

  $.ajax({
    type: "POST",
    url: url + "/" + "consultas_y_reportes/informe_fiscal_de_ventas_datos",
    data: { fecha },
    success: function (resultado) {
      var resultado = JSON.parse(resultado);

      if (resultado.resultado == 0) {
        $("#reporte_fiscal_de_ventas").html(resultado.reporte);
      }

      if (resultado.resultado == 1) {
        Swal.fire({
          icon: "warning",
          title: "No hay registros de la fecha: " + resultado.fecha,
          confirmButtonText: "Aceptar",
          confirmButtonColor: "#2AA13D",
        });
      }
      if (resultado.resultado == 2) {
        Swal.fire({
          icon: "error",
          title: "No se ha definido fecha: ",
          confirmButtonText: "Aceptar",
          confirmButtonColor: "#2AA13D",
        });
      }
    },
  });
}
