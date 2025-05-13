
function imprimir_duplicado_factura(id_factura) {
  var url = document.getElementById("url").value;
  id_de_factura = id_factura
  $.ajax({
    type: "POST",
    url: url + "/" + "consultas_y_reportes/imprimir_duplicado_factura",
    data: {
      id_de_factura
    },
    success: function(resultado) {
      var resultado = JSON.parse(resultado);

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
          title: 'Duplicado de factura'
        })
      }
    },
  });


}