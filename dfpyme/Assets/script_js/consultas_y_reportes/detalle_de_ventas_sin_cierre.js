function detalle_de_ventas_sin_cierre(id_apertura, fecha_y_hora_apertura,) {
  var url = document.getElementById("url").value;
  $.ajax({
    data: {
      id_apertura,
      fecha_y_hora_apertura,
     
    },
    url: url + "/" + "consultas_y_reportes/detalle_de_ventas_sin_cierre",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      $("#movimientos_de_caja").html(resultado.detalle_de_ventas_sin_cierre);
      if (resultado.resultado == 1) {
       /*  myModal = new bootstrap.Modal(
          document.getElementById("detalle_de_ventas"),
          {}
        );
        myModal.show(); */

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
          title: 'Movimientos encontrados'
      })
      }
    },
  });
}
