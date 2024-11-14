function imprimir_reporte_fiscal() {

    var url = document.getElementById("url").value;
    var fecha = document.getElementById("fecha_reporte").value;
    $.ajax({
      type: "POST",
      url: url + "/" + "consultas_y_reportes/imprimir_reporte_fiscal",
      data: {
        fecha
      },
      success: function(resultado) {
        var resultado = JSON.parse(resultado);

        if (resultado.resultado == 0) {
          $("#reporte_fiscal_de_ventas").html(resultado.reporte);
        }

        if (resultado.resultado == 1) {
          const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })

          Toast.fire({
            icon: 'success',
            title: 'Impresion de informe fiscal '
          })
        }

      },
    });
  }