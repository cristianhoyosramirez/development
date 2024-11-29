function entregar_producto(id_producto_pedido) {
  var url = document.getElementById("url").value;
  $.ajax({
    data: { id_producto_pedido },
    url: url + "/" + "producto/entregar_producto",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);

      if (resultado.resultado == 1) {

        myModal = new bootstrap.Modal(
          document.getElementById("entrega_de_productos"),
          {}
        );
        myModal.show();
        document.getElementById("cantidad_ya_entregada").value = resultado.cantidad_entregada;
        document.getElementById("cantidad_de_producto").value = resultado.cantidad_solicitada;
        document.getElementById("id_producto_pedido").value = resultado.id_producto_pedido;

        $("#entrega_de_productos").on("shown.bs.modal", function () {
          $(this).find("#cantidad_a_entregar").focus();
        });

      }
      if (resultado.resultado == 2) {
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
          title: 'Cantidad completa'
        })

      }
      if (resultado.resultado == 3) {
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
          icon: 'error',
          title: 'Falta definir cantidad'
        })

      }

    },
  });
}
function entregarProducto(e) {
  var key = window.Event ? e.which : e.keyCode;
  return key >= 48 && key <= 57;
}

function actualizar_entregar_producto() {
  var url = document.getElementById("url").value;
  var id_producto_pedido = document.getElementById("id_producto_pedido").value;

  var cantidad = document.getElementById("cantidad_a_entregar").value;
  if (cantidad <= 0) {
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
      title: 'Valor no válido '
    })
  } else if (cantidad >= 1) {


    $.ajax({
      data: { id_producto_pedido, cantidad },
      url: url + "/" + "producto/actualizar_entregar_producto",
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
            title: 'Excede cantidad'
          })


        }
        if (resultado.resultado == 1) {
          $("#productos_pedido").html(resultado.productos);
          $('#entrega_de_productos').modal('hide');
          document.getElementById("cantidad_a_entregar").value = "";

          const Toast_1 = Swal.mixin({
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

          Toast_1.fire({
            icon: 'success',
            title: resultado.mensaje
          })


        }
        if (resultado.resultado == 2) {

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
            title: 'Cantidad excede la cantidad de producto '
          })

        }
        if (resultado.resultado == 3) {

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
            title: 'Las cantidades ya estan entregadas'
          })

        }

      },
    });
  }
} 
