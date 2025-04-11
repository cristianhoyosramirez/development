function eliminar_producto_pedido(id_tabla_producto) {
  var url = document.getElementById("url").value;
  $.ajax({
    data: { id_tabla_producto },
    url:
      url + "/" + "edicion_eliminacion_factura_pedido/eliminar_producto_pedido",
    type: "POST",
    success: function (resultado) {
      //Solo pueden modificar las cantidades articulo no permite descuento
      var resultado = JSON.parse(resultado);

      if (resultado.resultado == 1) {
        swal
          .fire({
            title: "Seguro de eliminar el producto " + resultado.descripcion,
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
                data: { id_tabla_producto },
                url: url + "/" + "edicion_eliminacion_factura_pedido/borrar_producto",
                type: "POST",
                success: function (resultado) {
                  var resultado = JSON.parse(resultado);

                  if (resultado.resultado == 1) {
                    $("#productos_pedido_a_facturar").html(resultado.productos);
                    document.getElementById("total_del_pedido_sin_formato").value=resultado.valor_total_sin_formato
                    document.getElementById("total_del_pedido").value=resultado.valor_total
                    Swal.fire({
                      icon: "success",
                      title: "Eliminacion exitosa",
                      confirmButtonText: "ACEPTAR",
                      confirmButtonColor: "#2AA13D",
                    });
                  }
                  if (resultado.resultado == 0) {
                    Swal.fire({
                      icon: "warning",
                      title: "No se pudo eliminar",
                      confirmButtonText: "ACEPTAR",
                    });
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



