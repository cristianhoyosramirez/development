function editar_producto_pos(id_tabla_producto) {
  var url = document.getElementById("url").value;
  $.ajax({
    data: { id_tabla_producto },
    url: url + "/" + "edicion_eliminacion_factura_pedido/edicion_pos",
    type: "POST",
    success: function (resultado) {
      //Solo pueden modificar las cantidades articulo no permite descuento
      var resultado = JSON.parse(resultado);
      if (resultado.resultado == 0) {
        $("#editar_nombre_producto_pos").html(resultado.descripcion);
        $("#editar_precio_venta_pos").html(resultado.valor_unitario_formato);
        // $("#editar_cantidad_producto_pedido_pos").html(resultado.total);
        $("#editar_cantidad_producto_pedido_pos").html(resultado.codigo_interno);
        document.getElementById("id_tabla_producto_pos").value = resultado.id_tabla_producto_pedido;
        document.getElementById("editar_precioventa_pos").value = resultado.valor_unitario;
        document.getElementById("editar_producto_pedido_cantidad_pos").value = resultado.cantidad;
        document.getElementById("editar_notas_pos").value = resultado.notas;
        document.getElementById("editar_total_pos").value = resultado.total;
        myModal = new bootstrap.Modal(
          document.getElementById("editar_cantidad_producto_pos"),
          {}
        );
        myModal.show();
      }
      if (resultado.resultado == 1) {
        document.getElementById("id_tabla_producto_factura_directa").value = resultado.id_tabla_producto_pedido;
        $("#editar_cantidad_producto_pedido_factura_directa").html(resultado.codigo_interno);
        $("#editar_nombre_producto_factura_directa").html(resultado.descripcion);
        $("#editar_precio_venta_factura_directa").html(resultado.valor_unitario_formato);


        document.getElementById("editar_precioventa_factura_directa").value = resultado.valor_unitario;
        document.getElementById("editar_producto_pedido_cantidad_factura_directa").value = resultado.cantidad;
        document.getElementById("precio_variable_factura_directa").value = resultado.valor_unitario_formato;
        document.getElementById("editar_total_factura_directa").value = resultado.total;

        myModal = new bootstrap.Modal(
          document.getElementById("editar_cantidad_producto_factura_pos"),
          {}
        );
        myModal.show();
      }
    },
  });
}

function editar_multiplicarAgregar_pos() {
  var total = 0;
  var cantidad = document.getElementById(
    "editar_producto_pedido_cantidad_pos"
  ).value;
  var valor_unitario = document.getElementById("editar_precioventa_pos").value;

  total = cantidad * valor_unitario;

  resultado = total.toLocaleString();

  document.getElementById("editar_total_pos").value = resultado;
}

function soloNumeros(e) {
  var key = window.Event ? e.which : e.keyCode;
  return key >= 48 && key <= 57;
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
  var x = document.getElementById("editar_notas_pos");
  x.value = x.value.toUpperCase();
}

function actualizar_registro_pos() {
  var url = document.getElementById("url").value;

  var id_tabla_producto = document.getElementById(
    "id_tabla_producto_pos"
  ).value;
  var cantidad = document.getElementById(
    "editar_producto_pedido_cantidad_pos"
  ).value;
  var valor_unitario = document.getElementById("editar_precioventa_pos").value;
  var nota = document.getElementById("editar_notas_pos").value;
  if (cantidad == 0 || cantidad == "" || cantidad < 0) {
    $("#editar_error_de_cantidad").html("DATO ERRADO");
  } else {
    $.ajax({
      data: { id_tabla_producto, cantidad, valor_unitario, nota },
      url:
        url +
        "/" +
        "edicion_eliminacion_factura_pedido/actualizar_producto_pos",
      type: "POST",
      success: function (resultado) {
        var resultado = JSON.parse(resultado);
        if (resultado.resultado == 1) {
          $("#editar_cantidad_producto_pos").modal("hide");

          $("#productos_de_pedido_pos").empty();
          $("#productos_de_pedido_pos").append(resultado.productos);

          document.getElementById("total_pedido_pos").value =
            resultado.valor_total;
          // document.getElementById("total_del_pedido_sin_formato").value =
          //  resultado.valor_total_sin_formato;
          Swal.fire({
            icon: "success",
            confirmButtonText: "Aceptar",
            confirmButtonColor: "#2AA13D",
            title: "Edicion correcta",
          });
        }
        if (resultado.resultado == 0) {
          alert("No hubo coincidencias ");
        }
      },
    });
  }
}

function editar_precios() {
  var total = 0;
  var cantidad = document.getElementById(
    "editar_producto_pedido_cantidad_factura_directa"
  ).value;
  var valor_unitario = document.getElementById("precio_variable_factura_directa").value;
  var valor_unitario_format = valor_unitario.replace(/[.]/g, "");
  var valor_unitario_parse = parseInt(valor_unitario_format)

  total = cantidad * valor_unitario_parse;

  resultado = total.toLocaleString();

  document.getElementById("editar_total_factura_directa").value = resultado;
}

$(function () {
  $("#editar_cantidad_producto_factura_pos").on("shown.bs.modal", function (e) {
    $("#precio_variable_factura_directa").focus();
  });
});


const precio_factura_directa = document.querySelector("#precio_variable_factura_directa");
function formatNumber(n) {
  n = String(n).replace(/\D/g, "");
  return n === "" ? n : Number(n).toLocaleString();
}
precio_factura_directa.addEventListener("keyup", (e) => {
  const element = e.target;
  const value = element.value;
  element.value = formatNumber(value);
});

function actualizar_registro_factura_directa() {
  var url = document.getElementById("url").value;
  var id_tabla_producto = document.getElementById("id_tabla_producto_factura_directa").value;
  var cantidad = document.getElementById("editar_producto_pedido_cantidad_factura_directa").value;
  var precio = document.getElementById("precio_variable_factura_directa").value;

  $.ajax({
    data: {
      id_tabla_producto, cantidad, precio

    },
    url: url + "/" + "edicion_eliminacion_factura_pedido/actualizar_registro_factura_directa",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);

      if (resultado.resultado == 0) {
        $("#creacion_cliente_factura_pos").modal("hide");
        document.getElementById("id_cliente_factura_pos").value = resultado.nit_cliente;
        document.getElementById("clientes_factura_pos").value = resultado.nombres_cliente;
        Swal.fire({
          icon: "success",
          title: "Cliente agregado",
        });
      }
      if (resultado.resultado == 1) {

        $("#productos_de_pedido_pos").empty();
        $("#productos_de_pedido_pos").append(resultado.productos);
        document.getElementById("total_pedido_pos").value = resultado.valor_total;

        $("#editar_cantidad_producto_factura_pos").modal("hide");

        Swal.fire({
          icon: "success",
          confirmButtonText: 'Aceptar',
          confirmButtonColor: "#2AA13D",
          title: "Producto:" + " " + resultado.nombre_producto + " modificado en precio y/o cantidad",
        });
      }
    },
  });
}