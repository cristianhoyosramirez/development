$(document).ready(function () {
  $("#partir_factura").click(function () {
    /**OPCION 1 */
    var ids_array = [];
    $("input:checkbox[class=delete_checkbox]:checked").each(function () {
      ids_array.push($(this).val());
    });

    /****OPCION 2 */
    /* var ids_array = [];
    $('.delete_check:checked').each(function(i){
        ids_array[i] = $(this).val();		
    }); */

    if (ids_array.length > 0) {
      var url = document.getElementById("url").value;
      var id_usuario = document.getElementById("id_usuario").value;
      var numero_pedido = document.getElementById(
        "numero_de_facturacion"
      ).value;
      $.ajax({
        type: "POST",
        url: url + "/" + "partir_factura/partir_factura",
        data: { ids_array: ids_array, numero_pedido, id_usuario },
        success: function (resultado) {
          var resultado = JSON.parse(resultado);

          if (resultado.resultado == 1) {
            $("#items_facturar_partir").html(resultado.productos);
            $("#total_factura_mostrar").html(resultado.valor_total_formato);
            document.getElementById("total_partir_factura").value =
              resultado.valor_total;
            document.getElementById("numero_pedido_partir_factura").value =
              resultado.numero_pedido;

            myModal = new bootstrap.Modal(
              document.getElementById("items_partir_factura"),
              {}
            );
            myModal.show();
          }
        },
      });
    }
  });
});

function finalizar_partir_factura() {
  $("#items_partir_factura").modal("hide");
  swal
    .fire({
      title: "Seguro que desea facturar parcialmente",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Aceptar ",
      confirmButtonColor: "#2AA13D",
      cancelButtonText: "Cancelar",
      cancelButtonColor: "#C13333",
      reverseButtons: true,
    })
    .then((result) => {
      /* myModal = new bootstrap.Modal(
        document.getElementById("facturar_pedido_partir_factura"),
        {}
      );
      myModal.show(); */
      var url = document.getElementById("url").value;
      var numero_pedido = document.getElementById(
        "numero_pedido_partir_factura"
      ).value;
      var numero_pedido = document.getElementById(
        "numero_pedido_partir_factura"
      ).value;

      $.ajax({
        type: "POST",
        url: url + "/" + "partir_factura/consultar_total",
        data: { numero_pedido },
        success: function (resultado) {
          var resultado = JSON.parse(resultado);

          if (resultado.resultado == 1) {
            $("#items_partir_factura").modal("hide");

            document.getElementById(
              "valor_total_a_pagar_partir_factura"
            ).value = resultado.valor_total_formato;
            myModal = new bootstrap.Modal(
              document.getElementById("facturar_pedido_partir_factura"),
              {}
            );
            myModal.show();
          }
        },
      });
    });
}

const efecti_partir_factura = document.querySelector(
  "#efectivo_partir_factura"
);
function formatNumber(n) {
  n = String(n).replace(/\D/g, "");
  return n === "" ? n : Number(n).toLocaleString();
}
efecti_partir_factura.addEventListener("keyup", (e) => {
  const element = e.target;
  const value = element.value;
  element.value = formatNumber(value);
});

const transaccion_partir_factura = document.querySelector(
  "#transaccion_partir_factura"
);
function formatNumber(n) {
  n = String(n).replace(/\D/g, "");
  return n === "" ? n : Number(n).toLocaleString();
}
transaccion_partir_factura.addEventListener("keyup", (e) => {
  const element = e.target;
  const value = element.value;
  element.value = formatNumber(value);
});

$("#facturar_pedido_partir_factura").on("shown.bs.modal", function (e) {
  $("#efectivo_partir_factura").focus();
});

function cambio_partir_factura() {
  var res = 0;

  var valor_venta = document.getElementById(
    "valor_total_a_pagar_partir_factura"
  ).value;
  var efectivo = document.getElementById("efectivo_partir_factura").value;
  var transaccion = document.getElementById("transaccion_partir_factura").value;
  var efectivoFormat = efectivo.replace(/[.]/g, "");
  var transaccionFormat = transaccion.replace(/[.]/g, "");
  var valor_ventaFormat = valor_venta.replace(/[.]/g, "");

  sub_total = parseInt(efectivoFormat) + parseInt(transaccionFormat);

  res = parseInt(sub_total) - parseInt(valor_ventaFormat);

  resultado = res.toLocaleString();
  document.getElementById("cambio_partir_factura").value = resultado;
}

function finalizar_venta_partir_factura() {
  var url = document.getElementById("url").value;
  var numero_de_pedido = document.getElementById("numero_de_facturacion").value;
  var efectiv = document.getElementById("efectivo_partir_factura").value;
  var efectivo = efectiv.replace(/[.]/g, "");
  var transaccio = document.getElementById("transaccion_partir_factura").value;
  var transaccion = transaccio.replace(/[.]/g, "");
  var valor_vent = document.getElementById(
    "valor_total_a_pagar_partir_factura"
  ).value;
  var valor_venta = valor_vent.replace(/[.]/g, "");
  var nit_cliente = document.getElementById("cliente").value;
  var id_usuario = document.getElementById("id_usuario").value;
  var estado = document.getElementById("estado").value;
  var total_pagado = efectivo + transaccion;

  if (efectivo == 0 && transaccion == 0) {
    $("#error_efectivo_partir_factura").html("Debe definir un mayor a cero");
    $("#error_transaccion_partir_factura").html("Debe definir un mayor a cero");
  }
  if (efectivo == 0 && transaccion > 0 && transaccion >= valor_venta) {
    $("#facturar_pedido_partir_factura").modal("hide");
    $.ajax({
      data: {
        numero_de_pedido,
        efectivo,
        transaccion,
        valor_venta,
        nit_cliente,
        id_usuario,
        estado,
        total_pagado,
      },
      url: url + "/" + "partir_factura/facturar",
      type: "POST",
      success: function (resultado) {
        var resultado = JSON.parse(resultado);
        if (resultado.resultado == 0) {
          Swal.fire({
            icon: "error",
            confirmButtonText: "Aceptar",
            confirmButtonColor: "#2AA13D",
            title: "No es posible completar la transaccion",
          });
        }
        if (resultado.id_regimen == 2) {
          document.getElementById("total_de_factura_partir_factura").value =
            resultado.total;
          document.getElementById("pago_con_efectivo_partir_factura").value =
            resultado.efectivo;
          document.getElementById("cambio_del_pago_partir_factura").value =
            resultado.cambio;
          document.getElementById("cerrar_venta_partir_factura").value =
            resultado.numero_de_pedido;
          document.getElementById("id_de_factura_partida").value =
            resultado.id_factura;
          document.getElementById("id_pedido_factura_partida").value =
            resultado.numero_de_pedido;

          myModal = new bootstrap.Modal(
            document.getElementById("finalizacion_venta_partir_factura"),
            {}
          );
          myModal.show();
        }

        if (resultado.id_regimen == 1) {
          $("#facturar_pedido_partir_factura").modal("hide");
          $("#facturar_pedido_partir_factura").modal("hide");
          document.getElementById("id_de_factura_partida").value =
            resultado.id_factura;

          document.getElementById("id_pedido_factura_partida").value =
            resultado.numero_pedido;

          document.getElementById("cerrar_venta_partir_factura").value =
            resultado.numero_pedido;
          document.getElementById("pago_con_efectivo_partir_factura").value =
            resultado.efectivo;
          document.getElementById("total_de_factura_partir_factura").value =
            resultado.total;
          document.getElementById("cambio_del_pago_partir_factura").value =
            resultado.cambio;

          myModal = new bootstrap.Modal(
            document.getElementById("finalizacion_venta_partir_factura"),
            {}
          );
          myModal.show();
        }
      },
    });
  } else if (efectivo == 0 && transaccion > 0 && transaccion < valor_venta) {
    res = valor_venta - transaccion;
    resultado = res.toLocaleString();
    $("#error_cambio_partir_factura").html("Falta:" + "$" + resultado);
  }
  suma = parseInt(efectivo) + parseInt(transaccion);

  if (efectivo > 0 && transaccion >= 0 && suma >= valor_venta) {
    $("#facturar_pedido_partir_factura").modal("hide");

    $.ajax({
      data: {
        numero_de_pedido,
        efectivo,
        transaccion,
        valor_venta,
        nit_cliente,
        id_usuario,
        estado,
        total_pagado,
      },
      url: url + "/" + "partir_factura/facturar",
      type: "POST",
      success: function (resultado) {
        var resultado = JSON.parse(resultado);
        if (resultado.resultado == 0) {
          Swal.fire({
            icon: "error",
            confirmButtonText: "Aceptar",
            confirmButtonColor: "#2AA13D",
            title: "No es posible completar la transaccion",
          });
        }
        if (resultado.id_regimen == 2) {
          document.getElementById("total_de_factura_partir_factura").value =
            resultado.total;
          document.getElementById("pago_con_efectivo_partir_factura").value =
            resultado.efectivo;
          document.getElementById("cambio_del_pago_partir_factura").value =
            resultado.cambio;
          document.getElementById("cerrar_venta_partir_factura").value =
            resultado.numero_de_pedido;
          document.getElementById("id_de_factura_partida").value =
            resultado.id_factura;
          document.getElementById("id_pedido_factura_partida").value =
            resultado.numero_de_pedido;

          myModal = new bootstrap.Modal(
            document.getElementById("finalizacion_venta_partir_factura"),
            {}
          );
          myModal.show();
        }

        if (resultado.id_regimen == 1) {
          $("#facturar_pedido_partir_factura").modal("hide");
          $("#facturar_pedido_partir_factura").modal("hide");
          document.getElementById("id_de_factura_partida").value =
            resultado.id_factura;

          document.getElementById("id_pedido_factura_partida").value =
            resultado.numero_pedido;

          document.getElementById("cerrar_venta_partir_factura").value =
            resultado.numero_pedido;
          document.getElementById("pago_con_efectivo_partir_factura").value =
            resultado.efectivo;
          document.getElementById("total_de_factura_partir_factura").value =
            resultado.total;
          document.getElementById("cambio_del_pago_partir_factura").value =
            resultado.cambio;

          myModal = new bootstrap.Modal(
            document.getElementById("finalizacion_venta_partir_factura"),
            {}
          );
          myModal.show();
        }
      },
    });
  } else if (efectivo > 0 && transaccion > 0 && suma < valor_venta) {
    // res = valor_venta - suma;
    res = parseInt(valor_venta) - parseInt(suma);
    resultado = res.toLocaleString();
    $("#error_cambio_partir_factura").html("Falta:" + "$" + resultado);
  }
  if (efectivo == 0 && transaccion == "") {
    $("#error_efectivo_partir_factura").html(
      "No se han definido valores de pago "
    );
  }
  if (isNaN(transaccion) && isNaN(efectivo)) {
    $("#error_efectivo_partir_factura").html(
      "No se han definido valores de pago "
    );
    $("#error_transaccion_partir_factura").html(
      "No se han definido valores de pago "
    );
  }

  if (efectivo > 0 && transaccion == "" && efectivo >= valor_venta) {
    $("#facturar_pedido_partir_factura").modal("hide");
    transaccion = 0;
    $.ajax({
      data: {
        numero_de_pedido,
        efectivo,
        transaccion,
        valor_venta,
        nit_cliente,
        id_usuario,
        estado,
        total_pagado,
      },
      url: url + "/" + "partir_factura/facturar",
      type: "POST",
      success: function (resultado) {
        var resultado = JSON.parse(resultado);
        if (resultado.resultado == 0) {
          Swal.fire({
            icon: "error",
            confirmButtonText: "Aceptar",
            confirmButtonColor: "#2AA13D",
            title: "No es posible completar la transaccion",
          });
        }
        if (resultado.id_regimen == 2) {
          document.getElementById("total_de_factura_partir_factura").value =
            resultado.total;
          document.getElementById("pago_con_efectivo_partir_factura").value =
            resultado.efectivo;
          document.getElementById("cambio_del_pago_partir_factura").value =
            resultado.cambio;
          document.getElementById("cerrar_venta_partir_factura").value =
            resultado.numero_de_pedido;
          document.getElementById("id_de_factura_partida").value =
            resultado.id_factura;
          document.getElementById("id_pedido_factura_partida").value =
            resultado.numero_de_pedido;

          myModal = new bootstrap.Modal(
            document.getElementById("finalizacion_venta_partir_factura"),
            {}
          );
          myModal.show();
        }

        if (resultado.id_regimen == 1) {
          $("#facturar_pedido_partir_factura").modal("hide");
          $("#facturar_pedido_partir_factura").modal("hide");
          document.getElementById("id_de_factura_partida").value =
            resultado.id_factura;

          document.getElementById("id_pedido_factura_partida").value =
            resultado.numero_pedido;

          document.getElementById("cerrar_venta_partir_factura").value =
            resultado.numero_pedido;
          document.getElementById("pago_con_efectivo_partir_factura").value =
            resultado.efectivo;
          document.getElementById("total_de_factura_partir_factura").value =
            resultado.total;
          document.getElementById("cambio_del_pago_partir_factura").value =
            resultado.cambio;

          myModal = new bootstrap.Modal(
            document.getElementById("finalizacion_venta_partir_factura"),
            {}
          );
          myModal.show();
        }
      },
    });
  } else if (efectivo > 0 && transaccion == "" && efectivo <= valor_venta) {
    res = valor_venta - efectivo;
    resultado = res.toLocaleString();
    $("#error_cambio_partir_factura").html("Falta:" + "$" + resultado);
  }
  if (efectivo == "" && transaccion == 0) {
    $("#error_efectivo_partir_factura").html(
      "No se han definido valores de pago "
    );
    $("#error_transaccion_partir_factura").html(
      "No se han definido valores de pago "
    );
  }
  if (transaccion > 0 && efectivo == "" && transaccion >= valor_venta) {
    $("#facturar_pedido_partir_factura").modal("hide");
    efectivo = 0;
    $.ajax({
      data: {
        numero_de_pedido,
        efectivo,
        transaccion,
        valor_venta,
        nit_cliente,
        id_usuario,
        estado,
        total_pagado,
      },
      url: url + "/" + "partir_factura/facturar",
      type: "POST",
      success: function (resultado) {
        var resultado = JSON.parse(resultado);
        if (resultado.resultado == 0) {
          Swal.fire({
            icon: "error",
            confirmButtonText: "Aceptar",
            confirmButtonColor: "#2AA13D",
            title: "No es posible completar la transaccion",
          });
        }
        if (resultado.id_regimen == 2) {
          document.getElementById("total_de_factura_partir_factura").value =
            resultado.total;
          document.getElementById("pago_con_efectivo_partir_factura").value =
            resultado.efectivo;
          document.getElementById("cambio_del_pago_partir_factura").value =
            resultado.cambio;
          document.getElementById("cerrar_venta_partir_factura").value =
            resultado.numero_de_pedido;
          document.getElementById("id_de_factura_partida").value =
            resultado.id_factura;
          document.getElementById("id_pedido_factura_partida").value =
            resultado.numero_de_pedido;

          myModal = new bootstrap.Modal(
            document.getElementById("finalizacion_venta_partir_factura"),
            {}
          );
          myModal.show();
        }

        if (resultado.id_regimen == 1) {
          $("#facturar_pedido_partir_factura").modal("hide");
          $("#facturar_pedido_partir_factura").modal("hide");
          document.getElementById("id_de_factura_partida").value =
            resultado.id_factura;

          document.getElementById("id_pedido_factura_partida").value =
            resultado.numero_pedido;

          document.getElementById("cerrar_venta_partir_factura").value =
            resultado.numero_pedido;
          document.getElementById("pago_con_efectivo_partir_factura").value =
            resultado.efectivo;
          document.getElementById("total_de_factura_partir_factura").value =
            resultado.total;
          document.getElementById("cambio_del_pago_partir_factura").value =
            resultado.cambio;

          myModal = new bootstrap.Modal(
            document.getElementById("finalizacion_venta_partir_factura"),
            {}
          );
          myModal.show();
        }
      },
    });
  } else if (transaccion > 0 && efectivo == "" && transaccion <= valor_venta) {
    res = valor_venta - efectivo;
    resultado = res.toLocaleString();
    $("#error_transaccion_partir_factura").html("Falta:" + "$" + resultado);
  }
}

function cancelar_partir_factura() {
  var url = document.getElementById("url").value;
  var numero_de_pedido = document.getElementById("numero_de_facturacion").value;

  $.ajax({
    data: {
      numero_de_pedido,
    },
    url: url + "/" + "partir_factura/cancelar_partir_factura",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      if (resultado.resultado == 0) {
        Swal.fire({
          icon: "success",
          confirmButtonText: "Aceptar",
          confirmButtonColor: "#2AA13D",
          title: "Operación cancelada",
        });
      }
    },
  });
}

function actualizar_cantidad_partir_factura(
  e,
  cantidad,
  id,
  cantidad_producto
) {
  var url = document.getElementById("url").value;
  var enterKey = 13;
  if (e.which == enterKey) {
    $("#items_partir_factura").modal("hide");
    swal
      .fire({
        title: "Solo va a facturar " + cantidad + " unidad(es) ",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "confirmar",
        confirmButtonColor: "#2AA13D",
        cancelButtonText: "Cancelar",
        cancelButtonColor: "#C13333",
        reverseButtons: true,
      })
      .then((result) => {
        if (result.isConfirmed) {
          console.log('la cantidad es ', cantidad)
          console.log('la cantidad de producto  ', cantidad_producto)
          if (parseInt(cantidad) < 0 || parseInt(cantidad) > parseInt(cantidad_producto) || parseInt(cantidad) == 0) {
            $("#items_partir_factura").modal("show");
            $("#error_cantidad_partir_factura").html("No se puede facturar cantidad supera a la del pedido ");
            //alert('Error de cantidad')
          } else {
            $("#error_cantidad_partir_factura").html("");
            var numero_de_pedido = document.getElementById(
              "numero_de_facturacion"
            ).value;
            $.ajax({
              data: {
                cantidad,
                id,
                cantidad_producto,
                numero_de_pedido,
              },
              url:
                url +
                "/" +
                "partir_factura/actualizar_cantidad_tabla_partir_factura",
              type: "POST",
              success: function (resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 0) {
                  Swal.fire({
                    icon: "success",
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#2AA13D",
                    title: "Operacion cancelada",
                  });
                } else if (resultado.resultado == 1) {
                  /*$("#valor_producto_partir_factura").html(
                    resultado.valor_total_producto
                  );*/
                  $("#total_factura_mostrar").html(
                    resultado.valor_total_pedido
                  );
                  $("#items_facturar_partir").html(resultado.productos);
                  Swal.fire({
                    icon: "success",
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#2AA13D",
                    title: "Operación exitosa",
                  }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                      myModal = new bootstrap.Modal(
                        document.getElementById("items_partir_factura"),
                        {}
                      );
                      myModal.show();
                    }
                  });
                }
              },
            });
          }
        }
      });
  }
}
