function editar_datos_producto(id_tabla_producto) {
  var url = document.getElementById("url").value;

  $.ajax({
    data: { id_tabla_producto },
    url: url + "/" + "edicion_eliminacion_factura_pedido/edicion",
    type: "POST",
    success: function (resultado) {  //Solo pueden modificar las cantidades articulo no permite descuento 
      var resultado = JSON.parse(resultado);
      if (resultado.resultado == 0) {

        $("#editar_cantidad_producto_pedido").html(resultado.codigo_interno);
        //$("#editar_producto_pedido_cantidad").html(resultado.cantidad); 
        $("#editar_nombre_producto").html(resultado.descripcion);
        $("#editar_precio_venta").html(resultado.valor_unitario_formato);

        document.getElementById("id_tabla_producto").value = resultado.id_tabla_producto_pedido;
        document.getElementById("editar_precioventa").value = resultado.valor_unitario;
        document.getElementById("editar_producto_pedido_cantidad").value = resultado.cantidad;

        myModal = new bootstrap.Modal(
          document.getElementById("editar_cantidad_producto_pedidoFactura"),
          {}
        );
        myModal.show();
      }
      if (resultado.resultado == 1) {

        $("#editar_precio_cantidad_producto_pedido").html(resultado.codigo_interno);
        $("#editar_precio_nombre_producto").html(resultado.descripcion);

        document.getElementById("editar_precio_id_tabla_producto").value = resultado.id_tabla_producto_pedido;
        document.getElementById("editar_precio_y_cantidad_venta").value = resultado.valor_unitario_formato;

        myModal = new bootstrap.Modal(
          document.getElementById("editar_cantidad_y_precio_producto_pedidoFactura"),
          {}
        );
        myModal.show();
      }
    },
  });
}

function soloNumeros(e) {
  var key = window.Event ? e.which : e.keyCode;
  return key >= 48 && key <= 57;
}

/**
 * Establece el autofoco en el modal autocompletar_producto en el input cantidad
 */
$(function () {
  $("#editar_cantidad_producto_pedidoFactura").on(
    "shown.bs.modal",
    function (e) {
      $("#editar_producto_pedido_cantidad").focus();
    }
  );
});

function editar_multiplicarAgregar() {
  var total = 0;
  var cantidad = document.getElementById(
    "editar_producto_pedido_cantidad"
  ).value;
  var valor_unitario = document.getElementById("editar_precioventa").value;

  total = cantidad * valor_unitario;

  resultado = total.toLocaleString();

  document.getElementById("editar_total").value = resultado;
}

function saltar(e, id) {
  // Obtenemos la tecla pulsada

  e.keyCode ? (k = e.keyCode) : (k = e.which);

  // Si la tecla pulsada es enter (codigo ascii 13)

  if (k == 13) {
    // Si la variable id contiene "submit" enviamos el formulario

    if (id == "submit") {
      document.forms[0].submit();
    } else {
      // nos posicionamos en el siguiente input

      document.getElementById(id).focus();
    }
  }
}

function minusculasAmayusculas() {
  var x = document.getElementById("editar_notas");
  x.value = x.value.toUpperCase();
}

function actualizar_registro() {
  var url = document.getElementById("url").value;
  var numero_pedido = document.getElementById("numero_de_facturacion").value;
  var id_tabla_producto = document.getElementById("id_tabla_producto").value;
  var cantidad = document.getElementById(
    "editar_producto_pedido_cantidad"
  ).value;
  var valor_unitario = document.getElementById("editar_precioventa").value;
  if (cantidad == 0 || cantidad == "" || cantidad < 0) {
    $("#editar_error_de_cantidad").html("DATO ERRADO");
  } else {
    $.ajax({
      data: { numero_pedido, id_tabla_producto, cantidad, valor_unitario },
      url:
        url +
        "/" +
        "edicion_eliminacion_factura_pedido/actualizar_producto_pedido",
      type: "POST",
      success: function (resultado) {
        var resultado = JSON.parse(resultado);
        if (resultado.resultado == 1) {
          $("#editar_cantidad_producto_pedidoFactura").modal("hide");
          $("#productos_pedido_a_facturar").html(resultado.productos);

          document.getElementById("total_del_pedido").value =
            resultado.valor_total;
          document.getElementById("total_del_pedido_sin_formato").value =
            resultado.valor_total_sin_formato;
          Swal.fire({
            icon: "success",
            confirmButtonText: "Aceptar",
            confirmButtonColor: "#2AA13D",
            title: "Edición correcta",
          });
        }
        if (resultado.resultado == 0) {
          alert("No hubo coincidencias ");
        }
      },
    });
  }
}
