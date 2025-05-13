function finalizar_venta() {
  var url = document.getElementById("url").value;
  var usuario = document.getElementById("id_usuario_de_facturacion").value;
  var estado = document.getElementById("estado_pos").value;
  var fecha_limite = document.getElementById("fecha_limite").value;
  var nit_cliente = document.getElementById("id_cliente_factura_pos").value;

  var date1 = new Date(fecha_limite);
  var date2 = new Date();

  if (estado == 8) {

    Swal.fire({
      title: '¿Va a realizar una transacción de facturación electrónica?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#2AA13D',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Facturar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          data: {
            usuario,
            nit_cliente,
          },
          url: url + "/" + "factura_electronica/pre_factura",
          type: "POST",
          success: function (resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 0) {
              Swal.fire({
                icon: "error",
                title: "Error en la cantidad",
                confirmButtonText: "Aceptar",
                confirmButtonColor: "#2AA13D",
              });
            }
            if (resultado.resultado == 1) {
              //$('#formulario_devolucion')[0].reset();
              document.getElementById("facturacion").reset();
              document.getElementById("buscar_producto").focus();
              //$("#estado_pos").select2("val", "");
              //$('#estado_pos').val(null).trigger("change");
              $("#estado_pos").select2("val", "1");
              $("#productos_factura_rapida").html('')
              $("#total_pedido_pos").val(0)

              const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer)
                  toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
              })

              Toast.fire({
                icon: 'success',
                title: 'Prefactura electrónica generada exitosamente'
              })
            }
          },
        });
      }
    })




  } else if (estado != 8) {



    if (estado == 2 || estado == 3 || estado == 6) {
      if (fecha_limite == "" && estado == 2) {
        $("#error_fecha_limite").html("Falta definir una fecha ");
      } else if (date1 < date2) {
        $("#error_fecha_limite").html(
          "La fecha no puede ser menor a la fecha actual "
        );
      }
      if (nit_cliente != 22222222) {
        if (date1 >= date2) {
          $.ajax({
            data: { usuario, estado, nit_cliente },
            url: url + "/" + "pedido/forma_pago",
            type: "POST",
            success: function (resultado) {
              var resultado = JSON.parse(resultado);

              if (resultado.resultado == 1) {
                swal
                  .fire({
                    title:
                      "Va a realizar una transacción  " +
                      resultado.estado +
                      " por un valor de " +
                      resultado.valor_total +
                      " al señor(a) " +
                      resultado.nombres_clientes,
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonText: "Aceptar ",
                    confirmButtonColor: "#2AA13D",
                    cancelButtonText: "Cancelar",
                    cancelButtonColor: "#C13333",
                    //reverseButtons: true,
                  })
                  .then((result) => {
                    if (result.isConfirmed) {
                      var url = document.getElementById("url").value;
                      $.ajax({
                        data: { nit_cliente, usuario, estado },
                        url: url + "/" + "pedido/facturar_credito",
                        type: "POST",
                        success: function (resultado) {
                          var resultado = JSON.parse(resultado);

                          if (resultado.resultado == 1) {
                            $("#estado_pos").val("1"); // Select the option with a value of '1'
                            $("#estado_pos").trigger("change");

                            document.getElementById(
                              "id_cliente_factura_pos"
                            ).value = 22222222;
                            document.getElementById(
                              "clientes_factura_pos"
                            ).value = "CLIENTE GENERAL";

                            // crea un nuevo objeto `Date`
                            var today = new Date();

                            // `getDate()` devuelve el día del mes (del 1 al 31)
                            var day = today.getDate();

                            // `getMonth()` devuelve el mes (de 0 a 11)
                            var month = today.getMonth() + 1;

                            // `getFullYear()` devuelve el año completo
                            var year = today.getFullYear();

                            // muestra la fecha de hoy en formato `MM/DD/YYYY`
                            // console.log(`${month}/${day}/${year}`);

                            document.getElementById("fecha_limite").value = `${year}/${month}/${day}`;
                            document.getElementById("total_pedido_pos").value = 0;
                            //$("#productos_de_pedido_pos").empty();
                            $("#tablaProductos").html(resultado.tabla);

                            document.getElementById("buscar_producto").autofocus;
                            Swal.fire({
                              icon: "success",
                              title: "Factura crédito grabada con éxito",
                              confirmButtonText: "Imprimir factura crédito",
                              confirmButtonColor: "#2AA13D",
                              showDenyButton: true,
                              denyButtonText: `No imprimir`,
                              denyButtonColor: "#C13333",
                              reverseButtons: true,
                            }).then((result) => {
                              /* Read more about isConfirmed, isDenied below */
                              if (result.isConfirmed) {
                                (numero_de_factura = resultado.id_factura),
                                  $.ajax({
                                    data: {
                                      numero_de_factura,
                                    },
                                    url:
                                      url + "/" + "factura_pos/imprimir_factura",
                                    type: "POST",
                                    success: function (resultado) {
                                      var resultado = JSON.parse(resultado);

                                      if (resultado.resultado == 0) {
                                        $("#creacion_cliente_factura_pos").modal(
                                          "hide"
                                        );
                                        Swal.fire({
                                          icon: "success",
                                          title: "Cliente agregado",
                                        });
                                      }
                                      if (resultado.resultado == 1) {
                                        const Toast = Swal.mixin({
                                          toast: true,
                                          position: 'top-end',
                                          showConfirmButton: false,
                                          timer: 3000,
                                          timerProgressBar: true,
                                          didOpen: (toast) => {
                                            toast.addEventListener('mouseenter', Swal.stopTimer)
                                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                                          }
                                        })

                                        Toast.fire({
                                          icon: 'success',
                                          title: 'Impresón de factura correcta'
                                        })
                                      }
                                    },
                                  });
                              } else if (result.isDenied) {
                                Swal.fire({
                                  icon: "info",
                                  confirmButtonText: "Aceptar",
                                  confirmButtonColor: "#2AA13D",
                                  title: "No se imprime la factura crédito",
                                });
                              }
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
      } else if (nit_cliente == 22222222) {
        $("#error_cliente").html(
          "Documento requiere tercero válido"
        );
      }
    } else if (estado == 1 || estado == 7) {
      var url = document.getElementById("url").value;

      $.ajax({
        data: { usuario },
        url: url + "/" + "pedido/valor_pedido",
        type: "POST",
        success: function (resultado) {
          var resultado = JSON.parse(resultado);

          if (resultado.resultado == 1) {
            document.getElementById("valor_a_pagar").value = resultado.valor_total;
            document.getElementById("base_impuestos_pos").value = resultado.base;
            document.getElementById("impuesto_iva_pos").value = resultado.iva;
            document.getElementById("impuesto_al_consumo_pos").value = resultado.ico;

            $('#pago_con_efectivo').select()

            let descuento = document.getElementById("total_descuento%").value;
            document.getElementById("descuento_factura").value = descuento;

            let propina = document.getElementById("total_descuento_propina").value;
            document.getElementById("propina_factura").value = propina;

            let tot = resultado.valor_total
            total = tot.replace(/[.]/g, "")
            gran_total = parseInt(total) - (parseInt(descuento))
            gran_totalizado = parseInt(gran_total) + parseInt(propina)

            text = gran_totalizado.toLocaleString('en-EN')

            const number = gran_totalizado;

            document.getElementById("total_factura").value = gran_totalizado.toLocaleString();


            myModal = new bootstrap.Modal(
              document.getElementById("finalizar_venta"),
              {}
            );
            myModal.show();
          }
          if (resultado.resultado == 2) {
            //$("#base_impuestos_pos").hide();
            document.getElementById("impuestos_pos").style.display = "none";
            document.getElementById("valor_a_pagar").value = resultado.valor_total;

            let descuento = document.getElementById("total_descuento%").value;
            document.getElementById("descuento_factura").value = descuento;

            let propina = document.getElementById("total_descuento_propina").value;
            document.getElementById("propina_factura").value = propina;

            let tot = resultado.valor_total
            total = tot.replace(/[.]/g, "")
            gran_total = parseInt(total) - (parseInt(descuento))
            gran_totalizado = parseInt(gran_total) + parseInt(propina)

            text = gran_totalizado.toLocaleString('en-EN')

            const number = gran_totalizado;

            //console.log(new Intl.NumberFormat('es-En').format(number));
            let esNum = new Intl.NumberFormat("en-US");
            //console.log(esNum.format(gran_totalizado))


            document.getElementById("total_factura").value = gran_totalizado.toLocaleString();

            myModal = new bootstrap.Modal(
              document.getElementById("finalizar_venta"),
              {}
            );
            myModal.show();
          }

          if (resultado.resultado == 3) {
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            })

            Toast.fire({
              icon: 'info',
              title: 'No hay productos para facturar'
            })
          }
          if (resultado.resultado == 4) {
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            })

            Toast.fire({
              icon: 'error',
              title: 'No hay apertura de caja '
            })
          }
        },
      });
    }
  }
}
/**
 * Solo numeros
 * @param {*} e
 * @returns
 */
function pago_con_efectivo(e) {
  var key = window.Event ? e.which : e.keyCode;
  return key >= 48 && key <= 57;
}

/**
 * Establece el autofoco en el modal finalizar venta , facturacion directa
 */
$(function () {
  $("#finalizar_venta").on("shown.bs.modal", function (e) {
    $("#pago_con_efectivo").focus();
  });
});

/**
 * Numeros con formato en el modal facturar_pedido en el input efectivo
 */

const efectivo = document.querySelector("#pago_con_efectivo");
function formatNumber(n) {
  n = String(n).replace(/\D/g, "");
  return n === "" ? n : Number(n).toLocaleString();
}
efectivo.addEventListener("keyup", (e) => {
  const element = e.target;
  const value = element.value;
  element.value = formatNumber(value);
});

/**
 * Numeros con formato ,esta funcion le da formato a la cadena del numero y solo permite entrada de
 * numeros
 */
const transaccion = document.querySelector("#pago_con_transaccion");
function formatNumber(n) {
  n = String(n).replace(/\D/g, "");
  return n === "" ? n : Number(n).toLocaleString();
}
transaccion.addEventListener("keyup", (e) => {
  const element = e.target;
  const value = element.value;
  element.value = formatNumber(value);
});

function saltar_facturar_pedido_pos(e, id) {
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

function cambio_pedido_pos() {
  var res = 0;

  var valor_venta = document.getElementById("valor_a_pagar").value;
  var efectivo = document.getElementById("pago_con_efectivo").value;
  var transaccion = document.getElementById("pago_con_transaccion").value;
  var efectivoFormat = efectivo.replace(/[.]/g, "");
  var transaccionFormat = transaccion.replace(/[.]/g, "");
  var valor_ventaFormat = valor_venta.replace(/[.]/g, "");

  sub_total = parseInt(efectivoFormat) + parseInt(transaccionFormat);

  res = parseInt(sub_total) - parseInt(valor_ventaFormat);

  resultado = res.toLocaleString();
  document.getElementById("cambio_del_pago").value = resultado;
}

function confirmacion_finalizar_venta() {
  $("#finalizar_venta").modal("hide");
  Swal.fire({
    title: "ESTA SEGURO QUE DESEA FACTURAR",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Facturar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      var url = document.getElementById("url").value;
      var efectivo = document.getElementById("pago_con_efectivo").value;
      var transaccion = document.getElementById("pago_con_transaccion").value;
      var valor_venta = document.getElementById("valor_a_pagar").value;
      var nit_cliente = document.getElementById("id_cliente_factura_pos").value;
      var id_usuario = document.getElementById("id_usuario_de_facturacion").value;
      var estado = document.getElementById("estado_pos").value;
      var total_pagado = efectivo + transaccion;

      var efectivoFormat = efectivo.replace(/[.]/g, "");
      var transaccionFormat = transaccion.replace(/[.]/g, "");
      var valor_ventaFormat = valor_venta.replace(/[.]/g, "");
      efectivo_parse = parseInt(efectivoFormat);
      transaccion_parse = parseInt(transaccionFormat);
      valor_venta_parse = parseInt(valor_ventaFormat);
      total_pago = efectivo_parse + transaccion_parse;

      diferencia = valor_venta_parse - total_pago;
      resultado = diferencia.toLocaleString();

      if (total_pago < valor_venta_parse) {
        Swal.fire({
          icon: "error",
          title: "Faltan:" + " " + "$" + resultado,
          confirmButtonText: "ACEPTAR",
        });
      } else {
        var descuento = document.getElementById("descuento_factura").value;
        var propina = document.getElementById("propina_factura").value;
        $.ajax({
          data: {
            efectivo,
            transaccion,
            valor_venta,
            nit_cliente,
            id_usuario,
            estado,
            total_pagado,
            descuento,
            propina
          },
          url: url + "/" + "factura_directa/facturacion",
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
              document.getElementById("total_sin_imp").value = resultado.total;
              document.getElementById("efectivo_pago_sin_imp").value = resultado.efectivo;
              document.getElementById("cambio_pago_sin_imp").value = resultado.cambio;
              document.getElementById("numero_de_factura_sin_imp").value = resultado.id_factura;
              document.getElementById("id_usuario_sin_impuestos").value = resultado.id_usuario;

              document.getElementById("valor_descuento%").value = 0;
              document.getElementById("total_descuento%").value = 0;
              document.getElementById("valor_descuento_propina").value = 0;
              document.getElementById("total_descuento_propina").value = 0;


              myModal = new bootstrap.Modal(
                document.getElementById("cerrar_venta_sin_impuestos"),
                {}
              );
              myModal.show();
            }

            if (resultado.id_regimen == 1) {
              document.getElementById("sub_total_imp").value = resultado.Sub_total;
              document.getElementById("impuesto_iva_imp").value = resultado.iva;
              document.getElementById("total_ico_imp").value = resultado.impuesto_al_consumo;
              document.getElementById("total_factura_imp").value = resultado.total;
              document.getElementById("efectivo_pago_imp").value = resultado.efectivo;
              document.getElementById("cambio_pago_imp").value = resultado.cambio;
              document.getElementById("numero_de_factura_imp").value = resultado.id_factura;
              document.getElementById("numero_de_factura_imp").value = resultado.id_factura;
              document.getElementById("id_usuario").value = resultado.id_usuario;

              document.getElementById("valor_descuento%").value = 0;
              document.getElementById("total_descuento%").value = 0;
              document.getElementById("valor_descuento_propina").value = 0;
              document.getElementById("total_descuento_propina").value = 0;


              $("#cerrar_venta_con_impuestos").on(
                "shown.bs.modal",
                function (e) {
                  $("#imprimir_factura").focus();
                }
              );

              myModal = new bootstrap.Modal(
                document.getElementById("cerrar_venta_con_impuestos"),
                {}
              );
              myModal.show();
            }
          },
        });
      }
    }
  });
}

function devuelta() {
  var res = 0;

  var valor_venta = document.getElementById("total_factura").value;
  var efectivo = document.getElementById("pago_con_efectivo").value;
  var transaccion = document.getElementById("pago_con_transaccion").value;
  var efectivoFormat = efectivo.replace(/[.]/g, "");
  var transaccionFormat = transaccion.replace(/[.]/g, "");
  var valor_ventaFormat = valor_venta.replace(/[.]/g, "");

  sub_total = parseInt(efectivoFormat) + parseInt(transaccionFormat);

  res = parseInt(sub_total) - parseInt(valor_ventaFormat);

  //resultado = res.toLocaleString();

  if (res >= 0) {
    document.getElementById("cambio_del_pago").style.color = "black";
    document.getElementById("cambio_del_pago").value = res.toLocaleString()

  } else if (res < 0) {
    document.getElementById("cambio_del_pago").style.color = "red";
    document.getElementById("cambio_del_pago").value = res.toLocaleString()
  }

}

function llamar_ventana_de_finalizar_venta_pos(e) {
  var enterKey = 13;
  if (e.which == enterKey) {
    var url = document.getElementById("url").value;
    var efectivo = document.getElementById("pago_con_efectivo").value;
    var transaccion = document.getElementById("pago_con_transaccion").value;
    var valor_venta = document.getElementById("total_factura").value;
    var nit_cliente = document.getElementById("id_cliente_factura_pos").value;
    var id_usuario = document.getElementById("id_usuario_de_facturacion").value;
    var estado = document.getElementById("estado_pos").value;
    var total_pagado = efectivo + transaccion;
    var efectivoFormat = efectivo.replace(/[.]/g, "");
    var transaccionFormat = transaccion.replace(/[.]/g, "");
    var valor_ventaFormat = valor_venta.replace(/[.]/g, "");
    efectivo_parse = parseInt(efectivoFormat);
    transaccion_parse = parseInt(transaccionFormat);
    valor_venta_parse = parseInt(valor_ventaFormat);
    total_pago = efectivo_parse + transaccion_parse;

    if (efectivo_parse == 0 && transaccion_parse == 0) {
      $("#mensaje_forma_efectivo").html("Debe definir un mayor a cero");
      $("#mensaje_forma_transaccion").html("Debe definir un mayor a cero");
    }

    if (
      efectivo_parse == 0 &&
      transaccion_parse > 0 &&
      transaccion_parse >= valor_ventaFormat
    ) {
      $("#finalizar_venta").modal("hide");
      Swal.fire({
        title: "ESTA SEGURO QUE DESEA FACTURAR",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Facturar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          var descuento = document.getElementById("descuento_factura").value;
          var propina = document.getElementById("propina_factura").value;
          $.ajax({
            data: {
              efectivo,
              transaccion,
              valor_venta,
              nit_cliente,
              id_usuario,
              estado,
              total_pagado,
              descuento,
              propina
            },
            url: url + "/" + "factura_directa/facturacion",
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
                document.getElementById("total_sin_imp").value = resultado.total;
                document.getElementById("efectivo_pago_sin_imp").value = resultado.efectivo;
                document.getElementById("cambio_pago_sin_imp").value = resultado.cambio;
                document.getElementById("numero_de_factura_sin_imp").value = resultado.id_factura;
                document.getElementById("id_usuario_sin_impuestos").value = resultado.id_usuario;

                document.getElementById("valor_descuento%").value = 0;
                document.getElementById("total_descuento%").value = 0;
                document.getElementById("valor_descuento_propina").value = 0;
                document.getElementById("total_descuento_propina").value = 0;

                myModal = new bootstrap.Modal(
                  document.getElementById("cerrar_venta_sin_impuestos"),
                  {}
                );
                myModal.show();
              }

              if (resultado.id_regimen == 1) {
                document.getElementById("sub_total_imp").value = resultado.Sub_total;
                document.getElementById("impuesto_iva_imp").value = resultado.iva;
                document.getElementById("total_ico_imp").value = resultado.impuesto_al_consumo;
                document.getElementById("total_factura_imp").value = resultado.total;
                document.getElementById("efectivo_pago_imp").value = resultado.efectivo;
                document.getElementById("cambio_pago_imp").value = resultado.cambio;
                document.getElementById("numero_de_factura_imp").value = resultado.id_factura;
                document.getElementById("numero_de_factura_imp").value = resultado.id_factura;
                document.getElementById("id_usuario").value = resultado.id_usuario;

                document.getElementById("valor_descuento%").value = 0;
                document.getElementById("total_descuento%").value = 0;
                document.getElementById("valor_descuento_propina").value = 0;
                document.getElementById("total_descuento_propina").value = 0;

                if (resultado.result == 0) {
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                      toast.addEventListener('mouseenter', Swal.stopTimer)
                      toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                  })

                  Toast.fire({
                    icon: 'info',
                    title: 'Ya casi se le acaba la resolucion '
                  })
                }

                $("#cerrar_venta_con_impuestos").on(
                  "shown.bs.modal",
                  function (e) {
                    $("#imprimir_factura").focus();
                  }
                );

                myModal = new bootstrap.Modal(
                  document.getElementById("cerrar_venta_con_impuestos"),
                  {}
                );
                myModal.show();
              }
            },
          });
        } else {
          document.getElementById("pago_con_efectivo").value = "0";
          document.getElementById("pago_con_transaccion").value = "0";
          document.getElementById("cambio_del_pago").value = "0";
          $("#mensaje_forma_efectivo").html("");
          $("#mensaje_forma_transaccion").html("");
        }
      });
    } else if (transaccion_parse > 0 && transaccion_parse < valor_ventaFormat) {
      res = valor_ventaFormat - transaccion_parse;

      resultado = res.toLocaleString();
      $("#mensaje_forma_transaccion").html("Falta:" + "$" + resultado);
    }
    if (efectivo_parse > 0 && transaccion_parse == 0 && efectivo_parse >= valor_ventaFormat) {
      $("#finalizar_venta").modal("hide");
      Swal.fire({
        title: "ESTA SEGURO QUE DESEA FACTURAR",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Facturar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          var descuento = document.getElementById("descuento_factura").value;
          var propina = document.getElementById("propina_factura").value;
          $.ajax({
            data: {
              efectivo,
              transaccion,
              valor_venta,
              nit_cliente,
              id_usuario,
              estado,
              total_pagado,
              descuento,
              propina
            },
            url: url + "/" + "factura_directa/facturacion",
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
                document.getElementById("total_sin_imp").value = resultado.total;
                document.getElementById("efectivo_pago_sin_imp").value = resultado.efectivo;
                document.getElementById("cambio_pago_sin_imp").value = resultado.cambio;
                document.getElementById("numero_de_factura_sin_imp").value = resultado.id_factura;
                document.getElementById("id_usuario_sin_impuestos").value = resultado.id_usuario;

                document.getElementById("valor_descuento%").value = 0;
                document.getElementById("total_descuento%").value = 0;
                document.getElementById("valor_descuento_propina").value = 0;
                document.getElementById("total_descuento_propina").value = 0;

                myModal = new bootstrap.Modal(
                  document.getElementById("cerrar_venta_sin_impuestos"),
                  {}
                );
                myModal.show();
              }

              if (resultado.id_regimen == 1) {
                document.getElementById("sub_total_imp").value = resultado.Sub_total;
                document.getElementById("impuesto_iva_imp").value = resultado.iva;
                document.getElementById("total_ico_imp").value = resultado.impuesto_al_consumo;
                document.getElementById("total_factura_imp").value = resultado.total;
                document.getElementById("efectivo_pago_imp").value = resultado.efectivo;
                document.getElementById("cambio_pago_imp").value = resultado.cambio;
                document.getElementById("numero_de_factura_imp").value = resultado.id_factura;
                document.getElementById("numero_de_factura_imp").value = resultado.id_factura;
                document.getElementById("id_usuario").value = resultado.id_usuario;

                document.getElementById("valor_descuento%").value = 0;
                document.getElementById("total_descuento%").value = 0;
                document.getElementById("valor_descuento_propina").value = 0;
                document.getElementById("total_descuento_propina").value = 0;

                if (resultado.result == 0) {
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                      toast.addEventListener('mouseenter', Swal.stopTimer)
                      toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                  })

                  Toast.fire({
                    icon: 'info',
                    title: 'De la resolución actual solo quedan ' + resultado.facturas_restantes + ' facturas'
                  })
                }


                $("#cerrar_venta_con_impuestos").on(
                  "shown.bs.modal",
                  function (e) {
                    $("#imprimir_factura").focus();
                  }
                );

                myModal = new bootstrap.Modal(
                  document.getElementById("cerrar_venta_con_impuestos"),
                  {}
                );
                myModal.show();
              }
            },
          });
        } else {
          document.getElementById("pago_con_efectivo").value = "0";
          document.getElementById("pago_con_transaccion").value = "0";
          document.getElementById("cambio_del_pago").value = "0";
          $("#mensaje_forma_efectivo").html("");
          $("#mensaje_forma_transaccion").html("");
        }
      });
    } else if (
      efectivo_parse > 0 &&
      transaccion_parse == 0 &&
      efectivo_parse < valor_ventaFormat
    ) {
      res = valor_ventaFormat - efectivo_parse;

      resultado = res.toLocaleString();
      $("#mensaje_forma_efectivo").html("Falta:" + "$" + resultado);
    }

    if (
      efectivo_parse > 0 &&
      transaccion_parse > 0 &&
      total_pago >= valor_ventaFormat
    ) {
      $("#finalizar_venta").modal("hide");
      Swal.fire({
        title: "ESTA SEGURO QUE DESEA FACTURAR",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Facturar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          var descuento = document.getElementById("descuento_factura").value;
          var propina = document.getElementById("propina_factura").value;
          $.ajax({
            data: {
              efectivo,
              transaccion,
              valor_venta,
              nit_cliente,
              id_usuario,
              estado,
              total_pagado,
              descuento,
              propina
            },
            url: url + "/" + "factura_directa/facturacion",
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
                document.getElementById("total_sin_imp").value = resultado.total;
                document.getElementById("efectivo_pago_sin_imp").value = resultado.efectivo;
                document.getElementById("cambio_pago_sin_imp").value = resultado.cambio;
                document.getElementById("numero_de_factura_sin_imp").value = resultado.id_factura;
                document.getElementById("id_usuario_sin_impuestos").value = resultado.id_usuario;

                document.getElementById("valor_descuento%").value = 0;
                document.getElementById("total_descuento%").value = 0;
                document.getElementById("valor_descuento_propina").value = 0;
                document.getElementById("total_descuento_propina").value = 0;

                myModal = new bootstrap.Modal(
                  document.getElementById("cerrar_venta_sin_impuestos"),
                  {}
                );
                myModal.show();
              }

              if (resultado.id_regimen == 1) {
                document.getElementById("sub_total_imp").value =
                  resultado.Sub_total;
                document.getElementById("impuesto_iva_imp").value =
                  resultado.iva;
                document.getElementById("total_ico_imp").value =
                  resultado.impuesto_al_consumo;
                document.getElementById("total_factura_imp").value =
                  resultado.total;
                document.getElementById("efectivo_pago_imp").value =
                  resultado.efectivo;
                document.getElementById("cambio_pago_imp").value =
                  resultado.cambio;
                document.getElementById("numero_de_factura_imp").value =
                  resultado.id_factura;
                document.getElementById("numero_de_factura_imp").value =
                  resultado.id_factura;
                document.getElementById("id_usuario").value =
                  resultado.id_usuario;

                $("#cerrar_venta_con_impuestos").on(
                  "shown.bs.modal",
                  function (e) {
                    $("#imprimir_factura").focus();
                  }
                );

                myModal = new bootstrap.Modal(
                  document.getElementById("cerrar_venta_con_impuestos"),
                  {}
                );
                myModal.show();
              }
            },
          });
        } else {
          document.getElementById("pago_con_efectivo").value = "0";
          document.getElementById("pago_con_transaccion").value = "0";
          document.getElementById("cambio_del_pago").value = "0";
          $("#mensaje_forma_efectivo").html("");
          $("#mensaje_forma_transaccion").html("");
        }
      });
    } else if (
      efectivo_parse > 0 &&
      transaccion_parse > 0 &&
      total_pago < valor_ventaFormat
    ) {
      res = valor_ventaFormat - total_pago;

      resultado = res.toLocaleString();
      $("#mensaje_forma_efectivo").html("Falta:" + "$" + resultado);
    }

    if (efectivo_parse == 0 && isNaN(transaccion_parse)) {
      $("#mensaje_forma_efectivo").html(
        "No se han definido valores para los medios de pago "
      );
      $("#mensaje_forma_transaccion").html(
        "No se han definido valores para los medios de pago"
      );
    }
    if (
      efectivo_parse > 0 &&
      isNaN(transaccion_parse) &&
      efectivo_parse >= valor_ventaFormat
    ) {
      $("#finalizar_venta").modal("hide");
      Swal.fire({
        title: "ESTA SEGURO QUE DESEA FACTURAR",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Facturar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          var descuento = document.getElementById("descuento_factura").value;
          var propina = document.getElementById("propina_factura").value;
          $.ajax({
            data: {
              efectivo,
              transaccion,
              valor_venta,
              nit_cliente,
              id_usuario,
              estado,
              total_pagado,
              descuento,
              propina
            },
            url: url + "/" + "factura_directa/facturacion",
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
                document.getElementById("total_sin_imp").value = resultado.total;
                document.getElementById("efectivo_pago_sin_imp").value = resultado.efectivo;
                document.getElementById("cambio_pago_sin_imp").value = resultado.cambio;
                document.getElementById("numero_de_factura_sin_imp").value = resultado.id_factura;
                document.getElementById("id_usuario_sin_impuestos").value = resultado.id_usuario;

                document.getElementById("valor_descuento%").value = 0;
                document.getElementById("total_descuento%").value = 0;
                document.getElementById("valor_descuento_propina").value = 0;
                document.getElementById("total_descuento_propina").value = 0;

                myModal = new bootstrap.Modal(
                  document.getElementById("cerrar_venta_sin_impuestos"),
                  {}
                );
                myModal.show();
              }

              if (resultado.id_regimen == 1) {
                document.getElementById("sub_total_imp").value =
                  resultado.Sub_total;
                document.getElementById("impuesto_iva_imp").value =
                  resultado.iva;
                document.getElementById("total_ico_imp").value =
                  resultado.impuesto_al_consumo;
                document.getElementById("total_factura_imp").value =
                  resultado.total;
                document.getElementById("efectivo_pago_imp").value =
                  resultado.efectivo;
                document.getElementById("cambio_pago_imp").value =
                  resultado.cambio;
                document.getElementById("numero_de_factura_imp").value =
                  resultado.id_factura;
                document.getElementById("numero_de_factura_imp").value =
                  resultado.id_factura;
                document.getElementById("id_usuario").value =
                  resultado.id_usuario;

                $("#cerrar_venta_con_impuestos").on(
                  "shown.bs.modal",
                  function (e) {
                    $("#imprimir_factura").focus();
                  }
                );

                myModal = new bootstrap.Modal(
                  document.getElementById("cerrar_venta_con_impuestos"),
                  {}
                );
                myModal.show();
              }
            },
          });
        } else {
          document.getElementById("pago_con_efectivo").value = "0";
          document.getElementById("pago_con_transaccion").value = "0";
          document.getElementById("cambio_del_pago").value = "0";
          $("#mensaje_forma_efectivo").html("");
          $("#mensaje_forma_transaccion").html("");
        }
      });
    } else if (
      efectivo_parse > 0 &&
      isNaN(transaccion_parse) &&
      efectivo_parse < valor_ventaFormat
    ) {
      res = valor_ventaFormat - efectivo_parse;

      resultado = res.toLocaleString();
      $("#mensaje_forma_efectivo").html("Falta:" + "$" + resultado);
    }

    if (isNaN(efectivo_parse) && transaccion_parse == 0) {
      res = valor_ventaFormat - efectivo_parse;

      resultado = res.toLocaleString();
      $("#mensaje_forma_efectivo").html("No se ha definido valores de pago ");
      $("#mensaje_forma_transaccion").html(
        "No se ha definido valores de pago "
      );
    }
    if (
      isNaN(efectivo_parse) &&
      transaccion_parse > 0 &&
      transaccion_parse >= valor_ventaFormat
    ) {
      $("#finalizar_venta").modal("hide");
      Swal.fire({
        title: "ESTA SEGURO QUE DESEA FACTURAR",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Facturar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          var descuento = document.getElementById("descuento_factura").value;
          var propina = document.getElementById("propina_factura").value;
          $.ajax({
            data: {
              efectivo,
              transaccion,
              valor_venta,
              nit_cliente,
              id_usuario,
              estado,
              total_pagado,
              descuento,
              propina
            },
            url: url + "/" + "factura_directa/facturacion",
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
                document.getElementById("total_sin_imp").value = resultado.total;
                document.getElementById("efectivo_pago_sin_imp").value = resultado.efectivo;
                document.getElementById("cambio_pago_sin_imp").value = resultado.cambio;
                document.getElementById("numero_de_factura_sin_imp").value = resultado.id_factura;
                document.getElementById("id_usuario_sin_impuestos").value = resultado.id_usuario;

                document.getElementById("valor_descuento%").value = 0;
                document.getElementById("total_descuento%").value = 0;
                document.getElementById("valor_descuento_propina").value = 0;
                document.getElementById("total_descuento_propina").value = 0;

                myModal = new bootstrap.Modal(
                  document.getElementById("cerrar_venta_sin_impuestos"),
                  {}
                );
                myModal.show();
              }

              if (resultado.id_regimen == 1) {
                document.getElementById("sub_total_imp").value = resultado.Sub_total;
                document.getElementById("impuesto_iva_imp").value = resultado.iva;
                document.getElementById("total_ico_imp").value = resultado.impuesto_al_consumo;
                document.getElementById("total_factura_imp").value = resultado.total;
                document.getElementById("efectivo_pago_imp").value = resultado.efectivo;
                document.getElementById("cambio_pago_imp").value = resultado.cambio;
                document.getElementById("numero_de_factura_imp").value = resultado.id_factura;
                document.getElementById("numero_de_factura_imp").value = resultado.id_factura;
                document.getElementById("id_usuario").value = resultado.id_usuario;

                document.getElementById("valor_descuento%").value = 0;
                document.getElementById("total_descuento%").value = 0;
                document.getElementById("valor_descuento_propina").value = 0;
                document.getElementById("total_descuento_propina").value = 0;

                $("#cerrar_venta_con_impuestos").on(
                  "shown.bs.modal",
                  function (e) {
                    $("#imprimir_factura").focus();
                  }
                );

                myModal = new bootstrap.Modal(
                  document.getElementById("cerrar_venta_con_impuestos"),
                  {}
                );
                myModal.show();
              }
            },
          });
        } else {
          document.getElementById("pago_con_efectivo").value = "0";
          document.getElementById("pago_con_transaccion").value = "0";
          document.getElementById("cambio_del_pago").value = "0";
          $("#mensaje_forma_efectivo").html("");
          $("#mensaje_forma_transaccion").html("");
        }
      });
    } else if (
      isNaN(efectivo_parse) &&
      transaccion_parse > 0 &&
      transaccion_parse < valor_ventaFormat
    ) {
      res = valor_ventaFormat - transaccion_parse;

      resultado = res.toLocaleString();
      $("#mensaje_forma_transaccion").html("Falta:" + "$" + resultado);
    }
    if (isNaN(efectivo_parse) && isNaN(transaccion_parse)) {
      $("#mensaje_forma_efectivo").html("No se han definido valores de pago ");
      $("#mensaje_forma_transaccion").html(
        "No se han definido valores de pago "
      );
    }
  }
}

function reset_inputs_factura_pos() {
  document.getElementById("pago_con_efectivo").value = "0";
  document.getElementById("pago_con_transaccion").value = "0";
  document.getElementById("cambio_del_pago").value = "0";
  $("#mensaje_forma_efectivo").html("");
  $("#mensaje_forma_transaccion").html("");
}

function limpiar_errores() {
  $("#mensaje_forma_efectivo").html("");
  $("#mensaje_forma_transaccion").html("");
}