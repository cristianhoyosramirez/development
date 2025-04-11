function eliminar_producto(id_tabla_producto) {
  var url = document.getElementById("url").value;
  var id_usuario = document.getElementById("id_usuario").value;
  $.ajax({
    data: { id_tabla_producto, id_usuario },
    url: url + "/" + "producto/eliminar_producto",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      if (resultado.resultado == 0) {
        $('#valor_id_tabla_producto').val(resultado.id_tabla_producto)
        myModal = new bootstrap.Modal(
          document.getElementById("eliminar_con_pin_pad"),
          {}
        );
        myModal.show();

      }
      if (resultado.resultado == 1) {
        swal
          .fire({
            title: "Seguro de eliminar el " + resultado.nombre_producto,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Eliminar ",
            confirmButtonColor: "#2AA13D",
            cancelButtonText: "Cancelar",
            cancelButtonColor: "#C13333",
            //reverseButtons: true,
          })
          .then((result) => {
            if (result.isConfirmed) {
              var url = document.getElementById("url").value;
              $.ajax({
                data: { id_tabla_producto, id_usuario },
                url: url + "/" + "producto/eliminacion_de_producto",
                type: "POST",
                success: function (resultado) {
                  var resultado = JSON.parse(resultado);

                  if (resultado.resultado == 1) {
                    $("#productos_pedido").html(resultado.productos);
                    $("#valor_total").html(resultado.total_pedido);
                    $("#cantidad_de_productos").html(resultado.cantidad_de_pruductos);


                    Swal.fire({
                      icon: "success",
                      title: "Eliminacion exitosa",
                      confirmButtonText: "ACEPTAR",
                      confirmButtonColor: "#2AA13D",
                    });
                  }
                  if (resultado.resultado == 0) {
                    document.getElementById("valor_id_tabla_producto").value = id_tabla_producto;
                    myModal = new bootstrap.Modal(
                      document.getElementById("eliminar_con_pin_pad"),
                      {}
                    );
                    myModal.show();
                  }
                },
              });
            } else if (result.isCancel) {
              alert("cancelacion de pedido222");
            }
          });
      }
    },
  });
}

function eliminar_con_pin_pad(e) {
  var url = document.getElementById("url").value;
  var pin = document.getElementById("id_pin_pad").value;
  var id_tabla_producto = document.getElementById(
    "valor_id_tabla_producto"
  ).value;

  if (pin.length == 4) {
    $.ajax({
      data: { pin, id_tabla_producto },
      url: url + "/" + "producto/eliminar_con_pin_pad",
      type: "POST",
      success: function (resultado) {
        var resultado = JSON.parse(resultado);
        if (resultado.resultado == 0) {
          $("#eliminar_con_pin_pad").modal("hide");
          Swal.fire({
            icon: "warning",
            title: "Pin no cuenta con permiso",
            confirmButtonText: "ACEPTAR",
            showCancelButton: true,
            cancelButtonText: "CANCELAR",
            confirmButtonText: "ACEPTAR",
            confirmButtonColor: "#2AA13D",
            cancelButtonColor: "#C13333",
          });
          document.getElementById("id_pin_pad").value = "";
        }
        if (resultado.resultado == 1) {
          $("#productos_pedido").html(resultado.productos);
          $("#valor_total").html(resultado.total_pedido);
          $("#cantidad_de_productos").html(resultado.cantidad_de_pruductos);
          document.getElementById("valor_id_tabla_producto").value = "";
          document.getElementById("id_pin_pad").value = "";
          $("#eliminar_con_pin_pad").modal("hide");
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
            title: 'Eliminación correcta'
          });
        }
        if (resultado.resultado == 2) {
          $("#eliminar_con_pin_pad").modal("hide");
          Swal.fire({
            icon: "warning",
            title: "Pin no válido",
            confirmButtonText: "ACEPTAR",
            showCancelButton: true,
            cancelButtonText: "CANCELAR",
            confirmButtonText: "ACEPTAR",
            confirmButtonColor: "#2AA13D",
            cancelButtonColor: "#C13333",
          });
          document.getElementById("id_pin_pad").value = "";
        }
      },
    });
  }
}
