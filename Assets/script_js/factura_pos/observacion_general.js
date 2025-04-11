function observacion_general() {
  
  var id_usuario = document.getElementById("id_usuario_de_facturacion").value;
  var url = document.getElementById("url").value;
  $.ajax({
    data: {
      id_usuario,
    },
    url: url + "/" + "producto/usuario_pedido",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);

      if (resultado.resultado == 0) {
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
          icon: 'info',
          title: 'No hay pedido para cagar una nota '
      })
      }
      if (resultado.resultado == 1) {
        document.getElementById("nota_de_producto").value = resultado.observacion_general
        myModal = new bootstrap.Modal(
          document.getElementById("observacion_general"),
          {}
        );
        myModal.show();
      }
    },
  });
}

function agregar_observacion_general() {
  id_usuario = document.getElementById("id_usuario").value;
  var url = document.getElementById("url").value;
  var observacion_general = document.getElementById("nota_de_producto").value;

  $.ajax({
    data: {
      id_usuario,
      observacion_general
    },
    url: url + "/" + "producto/agregar_observacion_general",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);

      if (resultado.resultado == 0) {
        Swal.fire({
          icon: "warning",
          title: "Usuario no tiene pedido asociado",
        });
      }
      if (resultado.resultado == 1) {
        $("#notas_y_observaciones").html(resultado.observaciones_general);
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
          title: 'Nota agregada a la factura '
      })
      }
    },
  });
}
