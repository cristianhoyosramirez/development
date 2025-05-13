function editar_cantidades_de_pedido(id_tabla_producto_pedido) {
  var url = document.getElementById("url").value;
  var id_usuario = document.getElementById("id_usuario").value;

  $.ajax({
    data: { id_usuario, id_tabla_producto_pedido },
    url: url + "/" + "producto/editar_cantidades_de_pedido",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      if (resultado.resultado == 0) {

        Swal.fire({
          title: 'Acción requiere permiso',
          html: `<input type="hidden" id="login" class="swal2-input" placeholder="Username">
          <input type="text" id="password" class="form-control" >`,
          confirmButtonText: 'Aceptar',
          confirmButtonColor: '#2fb344',
          focusConfirm: false,
          preConfirm: () => {
            const id_tabla_producto = resultado.id_tabla_producto
            const password = Swal.getPopup().querySelector('#password').value

            return { password: password, id_tablaproducto: id_tabla_producto }
          }
        }).then((result) => {
          //console.log(result.value.id_tablaproducto)
          var url = document.getElementById("url").value
          const id_tabla_producto = result.value.id_tablaproducto
          const pin = result.value.password
          $.ajax({
            data: { id_tabla_producto, pin },
            url: url + "/" + "producto/autorizacion_pin",
            type: "POST",
            success: function (resultado) {
              var resultado = JSON.parse(resultado);

              if (resultado.resultado == 0) {
                Swal.fire({
                  icon: "warning",
                  title: "Dato de cantidad errado",
                  confirmButtonText: "ACEPTAR",
                  confirmButtonColor: "#2AA13D",
                });
              }

              if (resultado.resultado == 1) {
                //Se puede editar cantidades y notas
                document.getElementById("codigo_internoproducto_editar").value = resultado.id_tabla_producto_pedido;
                document.getElementById("notas_editar").value = resultado.notas;
                $("#codigointernoproducto_editar").html(resultado.codigo_interno);
                $("#nombre_producto_editar").html(resultado.descripcion);
                $("#precio_venta_editar").html(resultado.valor_unitario_formato);
                $("#notas_del_producto").html(resultado.notas);
                document.getElementById("precioventa_editar").value = resultado.valor_unitario;
                document.getElementById("producto_pedido_cantidad_editar").value = resultado.cantidad;
                myModal = new bootstrap.Modal(
                  document.getElementById("editar_cantidades_producto"),
                  {}
                );
                myModal.show();
              }
            },
          });

        })

      }

      if (resultado.resultado == 1) {
        //Se puede editar cantidades y notas
        document.getElementById("codigo_internoproducto_editar").value =
          resultado.id_tabla_producto_pedido;
        document.getElementById("notas_editar").value = resultado.notas;
        $("#codigointernoproducto_editar").html(resultado.codigo_interno);
        $("#nombre_producto_editar").html(resultado.descripcion);
        $("#precio_venta_editar").html(resultado.valor_unitario_formato);
        $("#notas_del_producto").html(resultado.notas);
        document.getElementById("precioventa_editar").value = resultado.valor_unitario;
        document.getElementById("producto_pedido_cantidad_editar").value = resultado.cantidad;
        myModal = new bootstrap.Modal(
          document.getElementById("editar_cantidades_producto"),
          {}
        );
        myModal.show();
      }
      if (resultado.resultado == 2) {
        // Se abre el modal solo para editar las notas
        var url = (document.getElementById("id_edicion_producto_pedido").value =
          resultado.id_tabla_producto);
        swal
          .fire({
            title:
              "Usurio requiere permisos para editar producto ya fue impreso en comanda",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Usar pin",
            confirmButtonColor: "#2AA13D",
            cancelButtonText: "Cancelar",
            cancelButtonColor: "#C13333",
            reverseButtons: true,
          })
          .then((result) => {
            if (result.isConfirmed) {
              myModal = new bootstrap.Modal(
                document.getElementById("pin_edicion_cantidades"),
                {}
              );
              myModal.show();
            }
          });
      }
      if (resultado.resultado == 3) {
        //Se puede editar cantidades y notas
        document.getElementById("codigo_internoproducto_editar").value =
          resultado.id_tabla_producto_pedido;
        document.getElementById("notas_editar").value = resultado.notas;
        $("#codigointernoproducto_editar").html(resultado.codigo_interno);
        $("#nombre_producto_editar").html(resultado.descripcion);
        $("#precio_venta_editar").html(resultado.valor_unitario_formato);
        document.getElementById("precioventa_editar").value =
          resultado.valor_unitario;
        document.getElementById("producto_pedido_cantidad_editar").value =
          resultado.cantidad;
        myModal = new bootstrap.Modal(
          document.getElementById("editar_cantidades_producto"),
          {}
        );
        myModal.show();
      }
      if (resultado.resultado == 4) {
        swal
          .fire({
            title:
              "Usurio requiere permisos para editar producto ya fue impreso en comanda",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Usar pin",
            confirmButtonColor: "#2AA13D",
            cancelButtonText: "Cancelar",
            cancelButtonColor: "#C13333",
            reverseButtons: true,
          })
          .then((result) => {
            if (result.isConfirmed) {
              //document.getElementById("valor_id_tabla_producto_editar").value =resultado.id_tabla_producto;
              var id_tabla_producto_pedido = id_tabla_producto_pedido;
              myModal = new bootstrap.Modal(
                document.getElementById("editar_con_pin_pad"),
                {}
              );
              myModal.show();
            }
          });
      }
    },
  });
}

/**
 * Establece el autofoco en el modal autocompletar_producto en el input cantidad
 */
$(function () {
  $("#editar_cantidades_producto").on("shown.bs.modal", function (e) {
    $("#producto_pedido_cantidad_editar").focus();
  });
});

function multiplicarAgregar_editar() {
  var total = 0;
  var cantidad = document.getElementById(
    "producto_pedido_cantidad_editar"
  ).value;
  var valor_unitario = document.getElementById("precioventa_editar").value;

  total = cantidad * valor_unitario;

  resultado = total.toLocaleString();

  document.getElementById("total_editar").value = resultado;
}

function minusculasAmayusculas() {
  var x = document.getElementById("notas_editar");
  x.value = x.value.toUpperCase();
}

/* function () {
  var url = document.getElementById("url").value;
  var cantidad = document.getElementById(
    "producto_pedido_cantidad_editar"
  ).value;
  var id_tabla_producto = document.getElementById(
    "codigo_internoproducto_editar"
  ).value;

  if (cantidad == 0 || cantidad == "" || cantidad < 0) {
    alert("DATO ERRADO");
  } 
    $.ajax({
      type: "POST",
      url: url + "/" + "producto/pedido",
      data: request,
      success: response,
      dataType: "json",
    });
  
} */

function actualizar_cantidades_de_pedido() {
  var url = document.getElementById("url").value;
  var cantidad = document.getElementById(
    "producto_pedido_cantidad_editar"
  ).value;
  var id_tabla_producto = document.getElementById(
    "codigo_internoproducto_editar"
  ).value;

  var notas = document.getElementById("notas_editar").value;

  if (cantidad == 0 || cantidad == "" || cantidad < 0) {
    $("#error_de_cantidad_editar").html("DATO ERRADO");
  }
  {
    $.ajax({
      data: { cantidad, id_tabla_producto, notas },
      url: url + "/" + "producto/actualizar_cantidades_de_pedido",
      type: "POST",
      success: function (resultado) {
        var resultado = JSON.parse(resultado);

        if (resultado.resultado == 0) {
          Swal.fire({
            icon: "warning",
            title: "Dato de cantidad errado",
            confirmButtonText: "ACEPTAR",
            confirmButtonColor: "#2AA13D",
          });
        }

        if (resultado.resultado == 1) {
          $("#editar_cantidades_producto").modal("hide");
          $("#productos_pedido").html(resultado.productos);
          $("#valor_total").html(resultado.total_pedido);
          $("#cantidad_de_productos").html(resultado.cantidad_de_pruductos);
          //document.getElementById("form_editar_producto").reset();
          Swal.fire({
            icon: "success",
            title: "Producto modificado",
            confirmButtonText: "ACEPTAR",
            confirmButtonColor: "#2AA13D",
          });
        }
      },
    });
  }
}

function editar_con_pin_pad(e) {
  var url = document.getElementById("url").value;
  var pin = document.getElementById("edit_pin_pad").value;
  var id_tabla_producto = document.getElementById(
    "valor_id_tabla_producto_editar"
  ).value;

  if (pin.length == 4) {
    $.ajax({
      data: { pin, id_tabla_producto },
      url: url + "/" + "producto/editar_con_pin_pad",
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
          $("#editar_con_pin_pad").modal("hide");
          document.getElementById("codigo_internoproducto_editar").value =
            resultado.id_tabla_producto_pedido;
          document.getElementById("notas_editar").value = resultado.notas;
          $("#codigointernoproducto_editar").html(resultado.codigo_interno);
          $("#nombre_producto_editar").html(resultado.descripcion);
          $("#precio_venta_editar").html(resultado.valor_unitario_formato);
          document.getElementById("precioventa_editar").value =
            resultado.valor_unitario;
          document.getElementById("producto_pedido_cantidad_editar").value =
            resultado.cantidad;
          myModal = new bootstrap.Modal(
            document.getElementById("editar_cantidades_producto"),
            {}
          );
          myModal.show();
        }
      },
    });
  }
}
