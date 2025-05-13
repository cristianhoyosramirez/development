function agregar_productos_desde_categoria() {
  var url = document.getElementById("url").value;
  var codigo_interno_producto = document.getElementById(
    "codigo_internoproducto_producto_categoria"
  ).value;
  var cantidad = document.getElementById(
    "cantidad_producto_pedido_x_categoria"
  ).value;
  var numero_pedido = document.getElementById("numero_pedido_salvar").value;
  var id_usuario = document.getElementById("id_usuario").value;
  var id_mesa = document.getElementById("id_mesa").value;
  var valor_unitario = document.getElementById("precioventa_x_categoria").value;
  var nota_producto = document.getElementById("notas_x_categoria").value;
  var valor_total = valor_unitario * cantidad;

  if (cantidad == 0 || cantidad < 0 || cantidad == "") {
    $("#error_de_cantidad_categoria").html("DATO ERRADO");
  } else {
    $("#productos_x_categorias").modal("hide");
    $.ajax({
      data: {
        codigo_interno_producto,
        cantidad,
        numero_pedido,
        id_usuario,
        valor_total,
        nota_producto,
        valor_unitario,
        id_mesa,
      },
      url: url + "/" + "producto/insertar_producto_desde_categoria",
      type: "POST",
      success: function (resultado) {
        var resultado = JSON.parse(resultado);
        if (resultado.resultado == 0) {
          Swal.fire({
            icon: "error",
            title: "DATO ERRADO EN LA CANTIDAD ",
          });
        }
        if (resultado.resultado == 1) {
          $("#productos_pedido").html(resultado.productos_pedido);
          document.getElementById("numero_pedido_salvar").value =
            resultado.numero_pedido;
          $("#numero_pedido_mostrar").html(resultado.numero_pedido);
          $("#valor_total").html(resultado.total_pedido);
          $("#cantidad_de_productos").html(resultado.cantidad_de_pruductos);

          document.getElementById("id_mesa").value = resultado.id_mesa;
          document.getElementById("numero_pedido_imprimir_comanda").value =
            resultado.numero_pedido;
           
          Swal.fire({
            icon: "success",
            confirmButtonText: "Aceptar",
            confirmButtonColor: "#2AA13D",
            title: "Producto agregado a la mesa" + " " + resultado.nombre_mesa,
          });
          $("#productos_x_categorias").modal("hide");
        }
        if (resultado.resultado == 2) {
          $("#productos_pedido").html(resultado.productos_pedido);
          document.getElementById("numero_pedido_salvar").value =
            resultado.numero_pedido;
          document.getElementById("id_mesa").value = resultado.id_mesa;
          $("#valor_total").html(resultado.total);
          $("#cantidad_de_productos").html(resultado.cantidad_de_productos);
          $("#autocompletar_producto").modal("hide");
          Swal.fire({
            icon: "success",
            confirmButtonText: "Aceptar",
            confirmButtonColor: "#2AA13D",
            title: "Producto agregado a la mesa" + " " + resultado.nombre_mesa,
          });
        }
      },
    });
  }
}
