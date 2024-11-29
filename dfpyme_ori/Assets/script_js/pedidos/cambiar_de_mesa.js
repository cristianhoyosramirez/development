function cambiar_de_mesa() {
  var url = document.getElementById("url").value;
  var id_mesa = document.getElementById("id_mesa").value;

  $.ajax({
    data: { id_mesa },
    url: url + "/" + "mesas/cambiar_de_mesa",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      if (resultado.resultado == 1) {
        $("#detalle_pedido").html(resultado.detalle_pedido);
        $("#cambio_de_mesa").html(resultado.mesas);
        myModal = new bootstrap.Modal(
          document.getElementById("cambiar_de_mesa"),
          {}
        );
        myModal.show();
      }

      if (resultado.resultado == 0) {
        Swal.fire({
          icon: "error",
          confirmButtonText: "Aceptar",
          confirmButtonColor: "#2AA13D",
          title: "La mesa no tiene pedido",
        });
      }
    },
  });
}

function intercambio_mesa() {
  var url = document.getElementById("url").value;
  var id_mesa_origen = document.getElementById("id_mesa_origen").value;
  var id_mesa_destino = document.getElementById("mesa_destino").value;

  $.ajax({
    data: { id_mesa_origen, id_mesa_destino },
    url: url + "/" + "mesas/intercambio_mesa",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      if (resultado.resultado == 1) {
        $("#cambiar_de_mesa").modal("hide");
        document.getElementById("id_mesa").value = resultado.id_mesa;
        document.getElementById("id_mesa_facturacion").value = resultado.id_mesa;
        document.getElementById("numero_pedido_salvar").value =
          resultado.pedido;
        document.getElementById("numero_pedido_imprimir_comanda").value =
          resultado.pedido;
        document.getElementById("observacion_general_de_pedido").value =
          resultado.observaciones_generales;
        $("#numero_pedido_mostrar").html(resultado.pedido);
        $("#valor_total").html(resultado.valor_total);
        $("#nombre_mesa").html(resultado.nombre_mesa);
        $("#cantidad_de_productos").html(resultado.cantidad_productos);
        $("#productos_pedido").html(resultado.productos_pedido);

        Swal.fire({
          icon: "success",
          confirmButtonText: "Aceptar",
          confirmButtonColor: "#2AA13D",
          title: "Cambio de mesas",
        });
      }

      if (resultado.resultado == 0) {
        $("#cambiar_de_mesa").modal("hide");
        document.getElementById("id_mesa").value = resultado.id_mesa;
        document.getElementById("id_mesa_facturacion").value = resultado.id_mesa;
        document.getElementById("numero_pedido_salvar").value =
          resultado.pedido;
        document.getElementById("numero_pedido_imprimir_comanda").value =
          resultado.pedido;
        document.getElementById("observacion_general_de_pedido").value =
          resultado.observaciones_generales;
        $("#numero_pedido_mostrar").html(resultado.pedido);
        $("#valor_total").html(resultado.valor_total);
        $("#nombre_mesa").html(resultado.nombre_mesa);
        $("#cantidad_de_productos").html(resultado.cantidad_productos);
        $("#productos_pedido").html(resultado.productos_pedido);

        Swal.fire({
          icon: "success",
          confirmButtonText: "Aceptar",
          confirmButtonColor: "#2AA13D",
          title: "Cambio de mesas",
        });
      }
    },
  });
}
