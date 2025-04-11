function reset_factura() {
  var usuario = document.getElementById("id_usuario").value;
  var url = document.getElementById("url").value;
  swal
    .fire({
      title: "Seguro de resetear la factura ",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Eliminar ",
      confirmButtonColor: "#2AA13D",
      cancelButtonText: "Cancelar",
      cancelButtonColor: "#C13333",
      //reverseButtons: true,
    })
    .then((result) => {
      $.ajax({
        data: {
          usuario,
        },
        url: url + "/" + "factura_pos/reset_factura",
        type: "POST",
        success: function (resultado) {
          var resultado = JSON.parse(resultado);

          if (resultado.resultado == 1) {
            $("#productos_de_pedido_pos").empty();
            $("#productos_de_pedido_pos").append(resultado.productos);
            document.getElementById("total_pedido_pos").value = 0;
            Swal.fire({
              icon: "success",
              title: "Haz reseteado la facturación ",
              confirmButtonText: "Aceptar",
              confirmButtonColor: "#2AA13D",
            });
          }
        },
      });
    });
}
