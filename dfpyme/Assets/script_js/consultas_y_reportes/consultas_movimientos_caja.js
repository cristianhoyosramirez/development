function consultas_movimientos_caja() {
  var url = document.getElementById("url").value;
  var fecha_inicial_caja = document.getElementById("fecha_inicial_caja").value;
  var fecha_final_caja = document.getElementById("fecha_final_caja").value;

  if (fecha_inicial_caja == "" && fecha_final_caja == "") {
    $("#error_fecha_inicial_caja").html("Falta la fecha inicial ");
    $("#error_fecha_final_caja").html("Falta la fecha final ");
  }
  if (fecha_inicial_caja == "" && fecha_final_caja != "") {
    $("#error_fecha_inicial_caja").html("Falta la fecha inicial ");
  }
  if (fecha_inicial_caja != "" && fecha_final_caja == "") {
    $("#error_fecha_final_caja").html("Falta la fecha inicial ");
  }
  if (fecha_inicial_caja != "" && fecha_final_caja != "") {
    $.ajax({
      data: {
        fecha_inicial_caja,
        fecha_final_caja,
      },
      url: url + "/" + "consultas_y_reportes/consultas_caja_por_fecha",
      type: "POST",
      success: function (resultado) {
        var resultado = JSON.parse(resultado);
        if (resultado.resultado == 0) {
          const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener("mouseenter", Swal.stopTimer);
              toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
          });

          Toast.fire({
            icon: "info",
            title: "No se encontraron registros ",
          });
        }
        if (resultado.resultado == 1) {
          const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener("mouseenter", Swal.stopTimer);
              toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
          });

          Toast.fire({
            icon: "error",
            title: "La fecha final debe ser mayor a la fecha inicial ",
          });
        }
        if (resultado.resultado == 2) {
          document.getElementById("formulario_movimiento_caja").submit();
        }
      },
    });
  }
}
