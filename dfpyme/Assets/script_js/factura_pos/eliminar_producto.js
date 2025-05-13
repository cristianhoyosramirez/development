
function eliminacion_item_factura_pos(id_tabla_producto) {
    var url = document.getElementById("url").value;
    var id_usuario = document.getElementById("id_usuario").value;
    $.ajax({
      data: { id_tabla_producto },
      url: url + "/" + "factura_directa/eliminar_producto",
      type: "POST",
      success: function (resultado) {
        var resultado = JSON.parse(resultado);
        
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
                  data: { id_tabla_producto },
                  url: url + "/" + "factura_directa/eliminacion_de_producto",
                  type: "POST",
                  success: function (resultado) {
                    var resultado = JSON.parse(resultado);
                
  
                    if (resultado.resultado == 1) {
                      /* $("#productos_pedido").html(resultado.productos);
                      $("#valor_total").html(resultado.total_pedido);
                      $("#cantidad_de_productos").html(
                        resultado.cantidad_de_pruductos
                      ); */
                      $('#productos_de_pedido_pos').empty();
                      $("#productos_de_pedido_pos").append(resultado.productos);
                      document.getElementById("total_pedido_pos").value=resultado.total

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