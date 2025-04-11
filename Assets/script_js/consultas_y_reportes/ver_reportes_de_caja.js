function ver_reportes_de_caja() {
    var url = document.getElementById("url").value;

    $.ajax({
      type: "POST",
      url: url + "/" + "consultas_y_reportes/reporte_caja_diario",
      success: function(resultado) {
        var resultado = JSON.parse(resultado);

        if (resultado.resultado == 1) {


          $('#reporte_fiscal_de_ventas').html(resultado.movimientos)
          
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