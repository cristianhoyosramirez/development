function eliminacion_de_pedido(numero_pedido) {

  swal
    .fire({
      title: "Seguro de eliminar el pedido No " + numero_pedido,
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Eliminar ",
      confirmButtonColor: "#2AA13D",
      cancelButtonText: "Cancelar",
      cancelButtonColor: "#C13333",
      reverseButtons: true,
    })
    .then((result) => {
      if (result.isConfirmed) {
        var url = document.getElementById("url").value;
        $.ajax({
          data: { numero_pedido },
          url: url + '/' + 'pedido/eliminacion_de_pedido',
          type: 'POST',
          success: function (resultado) {

            if (resultado == 1) {
              Swal.fire({
                icon: 'success',
                title: 'Eliminacion exitosa',
                confirmButtonText: 'ACEPTAR',
              })
            }
            if (resultado == 0) {
              Swal.fire({
                showCancelButton: true,
                icon: 'warning',
                title: 'No se puede eliminar',
                confirmButtonText: 'Usar pin',
                confirmButtonColor: "#2AA13D",
                cancelButtonText: "Cancelar",
                cancelButtonColor: "#C13333",
                reverseButtons: true,
              }).then((result) => {
                if (result.isConfirmed) {
                  document.getElementById("id_borrar_pedido").value = numero_pedido;

                  myModal = new bootstrap.Modal(
                    document.getElementById("eliminacion_de_pedido"),
                    {}
                  );
                  myModal.show();
                }

              })
            }
          },
        });
      } else if (result.isCancel) {
        alert('cancelacion de pedido222')
      }
    });
}
