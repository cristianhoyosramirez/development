function detalle_de_ventas(id_apertura, fecha_inicial, fecha_final) {
  var url = document.getElementById("url").value;

  $.ajax({
    data: {
      id_apertura, fecha_inicial, fecha_final
    },
    url: url + "/" + "consultas_y_reportes/detalle_de_ventas",
    type: "POST",
    success: function (resultado) {
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
          title: 'Movimientos de caja '
        })

        $("#movimientos_de_caja").html(resultado.detalle_de_ventas);

        /*   myModal = new bootstrap.Modal(
            document.getElementById("detalle_de_ventas"),
            {}
          );
          myModal.show(); */

      }
    },
  });
}
