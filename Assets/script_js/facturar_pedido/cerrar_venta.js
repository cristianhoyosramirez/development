function cerrar_venta() {
  //var valor_total = document.getElementById("total_del_pedido").value;
  //document.getElementById("valor_total_a_pagar").value = valor_total;
  var numero_pedido = document.getElementById("numero_de_facturacion").value;
  //console.log("el total del valor es ");

  var url = document.getElementById("url").value;
  $.ajax({
    data: {
      numero_pedido,
    },
    url: url + "/" + "pedido/total_pedido",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      if (resultado.resultado == 1) {
        document.getElementById("base_grabable").value = resultado.base;
        document.getElementById("ico_grabable").value = resultado.ico;
        document.getElementById("iva_grabable").value = resultado.iva;
        $("#impuestos").show();

        let descuento = document.getElementById("total_descuento%").value;
        document.getElementById("descuento_factura").value = descuento;

        let propina = document.getElementById("total_descuento_propina").value;
        document.getElementById("propina_factura").value = propina;

        let tot = resultado.valor_total
        total = tot.replace(/[.]/g, "")
        gran_total = parseInt(total) - (parseInt(descuento))
        gran_totalizado = parseInt(gran_total) + parseInt(propina)

        document.getElementById("valor_total_a_pagar").value = gran_totalizado.toLocaleString();
        $('#efectivo').select()
        document.getElementById("subtotal_de_factura").value = document.getElementById("total_del_pedido").value;
        document.getElementById("descuento_factura").value = document.getElementById("total_descuento%").value;



        //document.getElementById("valor_total_a_pagar").value =resultado.valor_total;
        myModal = new bootstrap.Modal(
          document.getElementById("facturar_pedido"),
          {}
        );
        myModal.show();
      }
      if (resultado.resultado == 2) {


        let descuento = document.getElementById("total_descuento%").value;
        document.getElementById("descuento_factura").value = descuento;

        let propina = document.getElementById("total_descuento_propina").value;
        document.getElementById("propina_factura").value = propina;

        let tot = resultado.valor_total
        total = tot.replace(/[.]/g, "")
        gran_total = parseInt(total) - (parseInt(descuento))
        gran_totalizado = parseInt(gran_total) + parseInt(propina)

        // document.getElementById("valor_total_a_pagar").value = gran_totalizado.toLocaleString();
        document.getElementById("valor_total_a_pagar").value = document.getElementById("resultado_total_del_pedido").value;

        $('#efectivo').select()
        document.getElementById("subtotal_de_factura").value = document.getElementById("total_del_pedido").value;


        //document.getElementById("valor_total_a_pagar").value =resultado.valor_total;
        myModal = new bootstrap.Modal(
          document.getElementById("facturar_pedido"),
          {}
        );
        myModal.show();
      }
    },
  });

  /**
   * Autofoco en el facturar pedido
   */
  $(function () {
    $("#facturar_pedido").on("shown.bs.modal", function (e) {
      $("#efectivo").focus();
    });
  });
}

/**
 * Numeros con formato en el modal facturar_pedido en el input efectivo
 */

const efecti = document.querySelector("#efectivo");
function formatNumber(n) {
  n = String(n).replace(/\D/g, "");
  return n === "" ? n : Number(n).toLocaleString();
}
efecti.addEventListener("keyup", (e) => {
  const element = e.target;
  const value = element.value;
  element.value = formatNumber(value);
});
/**
 * Numeros con formato en el modal facturar_pedido en el input efectivo
 */

const transa = document.querySelector("#transaccion");
function formatNumber(n) {
  n = String(n).replace(/\D/g, "");
  return n === "" ? n : Number(n).toLocaleString();
}
transa.addEventListener("keyup", (e) => {
  const element = e.target;
  const value = element.value;
  element.value = formatNumber(value);
});

/**
 * Navegacion entre los inputs del formulario de finalizar venta
 * @param {*} e
 * @param {*} id
 */

function saltar_facturar_pedido(e, id) {
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

function cambio() {
  var res = 0;

  var valor_venta = document.getElementById("valor_total_a_pagar").value;
  var efectivo = document.getElementById("efectivo").value;
  var transaccion = document.getElementById("transaccion").value;
  var efectivoFormat = efectivo.replace(/[.]/g, "");
  var transaccionFormat = transaccion.replace(/[.]/g, "");
  var valor_ventaFormat = valor_venta.replace(/[.]/g, "");

  sub_total = parseInt(efectivoFormat) + parseInt(transaccionFormat);

  res = parseInt(sub_total) - parseInt(valor_ventaFormat);

  resultado = res.toLocaleString();
  document.getElementById("cambio").value = resultado;
  document.getElementById("efectivo_format").value = efectivoFormat;
  document.getElementById("transaccion_format").value = transaccionFormat;
  document.getElementById("valor_total_sinPunto").value = valor_ventaFormat;
}

function finalizar_venta() {
  $("#facturar_pedido").modal("hide");

  Swal.fire({
    title: "¿ESTA SEGURO QUE DESEA FACTURAR'",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#2AA13D",
    cancelButtonColor: "#d33",
    confirmButtonText: "Facturar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      var url = document.getElementById("url").value;
      var numero_de_pedido = document.getElementById(
        "numero_de_facturacion"
      ).value;
      var efectivo = document.getElementById("efectivo_format").value;
      var transaccion = document.getElementById("transaccion_format").value;
      var valor_venta = document.getElementById("valor_total_sinPunto").value;
      var nit_cliente = document.getElementById("cliente").value;
      var id_usuario = document.getElementById("id_usuario").value;
      var estado = document.getElementById("estado").value;
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

      if (nit_cliente == "") {
        var error_cliente = document.getElementById("error_falta_cliente");
        error_cliente.innerHTML = "DATO NECESARIO";
        error_cliente.style.color = "red";
      } else {
        if (total_pago < valor_venta_parse) {
          Swal.fire({
            icon: "error",
            title: "Faltan:" + " " + "$" + resultado,
            confirmButtonText: "ACEPTAR",
          });
          myModal = new bootstrap.Modal(
            document.getElementById("facturar_pedido"),
            {}
          );
          myModal.show();
        } else {
          let descuento = document.getElementById("total_descuento%").value;
          let propina = document.getElementById("total_descuento_propina").value;

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
              descuento,
              propina
            },
            url: url + "/" + "pedido/cerrar_venta",
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
                document.getElementById("descuento_fact").value = resultado.descuento;
                document.getElementById("propina_fact").value = resultado.propina;
                $(function () {
                  $("#cerrar_venta").on("shown.bs.modal", function (e) {
                    $("#imprimir_factura").focus();
                  });
                });
                myModal = new bootstrap.Modal(
                  document.getElementById("fin_de_venta"),
                  {}
                );
                myModal.show();
                document.getElementById("numero_de_factura").value = resultado.id_factura;
                document.getElementById("impuesto_iva").value = resultado.iva;
                document.getElementById("sub_total").value = resultado.Sub_total;
                document.getElementById("total_ico").value = resultado.impuesto_al_consumo;
                document.getElementById("total_factura").value = resultado.total;
                document.getElementById("efectivo_pago").value = resultado.efectivo;
                document.getElementById("cambio_pago").value = resultado.cambio;
              }

              if (resultado.id_regimen == 1) {
                $(function () {
                  $("#finalizacion_venta").on("shown.bs.modal", function (e) {
                    $("#impresion_factura").focus();
                  });
                });

                document.getElementById("id_de_factura").value = resultado.id_factura;
                document.getElementById("pago_con_efectivo").value = resultado.efectivo;
                document.getElementById("total_de_factura").value = resultado.total;
                document.getElementById("cambio_del_pago").value = resultado.cambio;
                document.getElementById("sub_total_de_factura").value = resultado.total;

                myModal = new bootstrap.Modal(
                  document.getElementById("finalizacion_venta"),
                  {}
                );
                myModal.show();
              }
            },
          });
        }
      }
    }
  });
}

function allowOnlyAlphabets(event) {
  var charCode = event.keyCode;

  if (charCode == 39) {
    $("#ver_todos_los_pedidos").focus();
  } else if (charCode == 37) {
    $("#imprimir_factura").focus();
  }
}

function reset_inputs() {
  document.getElementById("efectivo_format").value = "";
  document.getElementById("cambio").value = "";
  document.getElementById("valor_total_sinPunto").value = "";
  document.getElementById("efectivo").value = "0";
  document.getElementById("transaccion_format").value = "";
  document.getElementById("transaccion").value = "0";
}

function llamar_ventana_fin_venta(e) {
  var efect = document.getElementById("efectivo_format").value;
  var efectivo = parseInt(efect);
  var transa = document.getElementById("transaccion_format").value;
  var transaccion = parseInt(transa);
  var valor_vent = document.getElementById("valor_total_sinPunto").value;
  var valor_venta = parseInt(valor_vent);
  var total_pagad = transaccion;
  var total_pagado = parseInt(total_pagad);
  var numero_de_pedido = document.getElementById("numero_de_facturacion").value;

  suma = efectivo + transaccion;

  var url = document.getElementById("url").value;
  var enterKey = 13;
  if (e.which == enterKey) {
    if (efectivo == 0 && transaccion == 0) {
      $("#efectivo_en_ceros").html("Debe definir un mayor a cero");
      $("#transaccion_en_ceros").html("Debe definir un mayor a cero");
    }
    if (efectivo == 0 && transaccion > 0 && transaccion >= valor_venta) {
      $("#facturar_pedido").modal("hide");

      Swal.fire({
        title: "¿ESTA SEGURO QUE DESEA FACTURAR?",
        icon: "question",
        showCancelButton: true,
        //confirmButtonColor: "#3085d6",
        confirmButtonColor: "#2AA13D",
        cancelButtonColor: "#d33",
        confirmButtonText: "Facturar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          var nit_cliente = document.getElementById("cliente").value;
          var id_usuario = document.getElementById("id_usuario").value;
          var estado = document.getElementById("estado").value;

          if (nit_cliente == "") {
            var error_cliente = document.getElementById("error_falta_cliente");
            error_cliente.innerHTML = "DATO NECESARIO";
            error_cliente.style.color = "red";
          } else {
            let descuento = document.getElementById("total_descuento%").value;
            let propina = document.getElementById("total_descuento_propina").value;
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
                descuento,
                propina
              },
              url: url + "/" + "pedido/cerrar_venta",
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
                  document.getElementById("descuento_fact").value = resultado.descuento;
                  document.getElementById("propina_fact").value = resultado.propina;
                  $(function () {
                    $("#cerrar_venta").on("shown.bs.modal", function (e) {
                      $("#imprimir_factura").focus();
                    });
                  });
                  myModal = new bootstrap.Modal(
                    document.getElementById("fin_de_venta"),
                    {}
                  );
                  myModal.show();
                  document.getElementById("numero_de_factura").value =
                    resultado.id_factura;
                  document.getElementById("impuesto_iva").value = resultado.iva;
                  document.getElementById("sub_total").value =
                    resultado.Sub_total;
                  document.getElementById("total_ico").value =
                    resultado.impuesto_al_consumo;
                  document.getElementById("total_factura").value =
                    resultado.total;
                  document.getElementById("efectivo_pago").value =
                    resultado.efectivo;
                  document.getElementById("cambio_pago").value =
                    resultado.cambio;
                }

                if (resultado.id_regimen == 1) {
                  $(function () {
                    $("#finalizacion_venta").on("shown.bs.modal", function (e) {
                      $("#impresion_factura").focus();
                    });
                  });

                  document.getElementById("id_de_factura").value = resultado.id_factura;
                  document.getElementById("pago_con_efectivo").value = resultado.efectivo;
                  document.getElementById("total_de_factura").value = resultado.total;
                  document.getElementById("cambio_del_pago").value = resultado.cambio;
                  document.getElementById("sub_total_de_factura").value = resultado.total;
                  document.getElementById("descuento_total_de_factura").value = resultado.descuento;
                  document.getElementById("propina_total_de_factura").value = resultado.propina;

                  myModal = new bootstrap.Modal(
                    document.getElementById("finalizacion_venta"),
                    {}
                  );
                  myModal.show();
                }
              },
            });
          }
        } else {
          document.getElementById("efectivo_format").value = "";
          document.getElementById("cambio").value = "";
          document.getElementById("valor_total_sinPunto").value = "";
          document.getElementById("efectivo").value = "0";
          document.getElementById("transaccion_format").value = "";
          document.getElementById("transaccion").value = "0";
        }
      });
    } else if (efectivo == 0 && transaccion > 0 && transaccion < valor_venta) {
      res = valor_venta - transaccion;
      resultado = res.toLocaleString();
      $("#transaccion_en_ceros").html("Falta:" + "$" + resultado);
    }

    if (efectivo > 0 && transaccion == 0 && efectivo >= valor_venta) {
      $("#facturar_pedido").modal("hide");
      Swal.fire({
        title: "¿ESTA SEGURO QUE DESEA FACTURAR?",
        icon: "question",
        showCancelButton: true,
        //confirmButtonColor: "#3085d6",
        confirmButtonColor: "#2AA13D",
        cancelButtonColor: "#d33",
        confirmButtonText: "Facturar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          var nit_cliente = document.getElementById("cliente").value;
          var id_usuario = document.getElementById("id_usuario").value;
          var estado = document.getElementById("estado").value;

          if (nit_cliente == "") {
            var error_cliente = document.getElementById("error_falta_cliente");
            error_cliente.innerHTML = "DATO NECESARIO";
            error_cliente.style.color = "red";
          } else {
            let descuento = document.getElementById("total_descuento%").value;
            let propina = document.getElementById("total_descuento_propina").value;
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
                descuento,
                propina
              },
              url: url + "/" + "pedido/cerrar_venta",
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
                  
                  $(function () {
                    $("#cerrar_venta").on("shown.bs.modal", function (e) {
                      $("#imprimir_factura").focus();
                    });
                  });
                  document.getElementById("descuento_fact").value = resultado.descuento;
                  document.getElementById("propina_fact").value = resultado.propina;
                  myModal = new bootstrap.Modal(
                    document.getElementById("fin_de_venta"),
                    {}
                  );
                  myModal.show();
                  document.getElementById("numero_de_factura").value = resultado.id_factura;
                  document.getElementById("impuesto_iva").value = resultado.iva;
                  document.getElementById("sub_total").value = resultado.Sub_total;
                  document.getElementById("total_ico").value = resultado.impuesto_al_consumo;
                  document.getElementById("total_factura").value = resultado.total;
                  document.getElementById("efectivo_pago").value = resultado.efectivo;
                  document.getElementById("cambio_pago").value = resultado.cambio;

                }

                if (resultado.id_regimen == 1) {
                  $(function () {
                    $("#finalizacion_venta").on("shown.bs.modal", function (e) {
                      $("#impresion_factura").focus();
                    });
                  });

                  document.getElementById("id_de_factura").value = resultado.id_factura;
                  document.getElementById("pago_con_efectivo").value = resultado.efectivo;
                  document.getElementById("total_de_factura").value = resultado.total;
                  document.getElementById("cambio_del_pago").value = resultado.cambio;

                  document.getElementById("descuento_total_de_factura").value = resultado.descuento;
                  document.getElementById("propina_total_de_factura").value = resultado.propina;


                  document.getElementById("sub_total_de_factura").value = resultado.total;

                  myModal = new bootstrap.Modal(
                    document.getElementById("finalizacion_venta"),
                    {}
                  );
                  myModal.show();
                }
              },
            });
          }
        } else {
          document.getElementById("efectivo_format").value = "";
          document.getElementById("cambio").value = "";
          document.getElementById("valor_total_sinPunto").value = "";
          document.getElementById("efectivo").value = "0";
          document.getElementById("transaccion_format").value = "";
          document.getElementById("transaccion").value = "0";
        }
      });
    } else if (efectivo > 0 && transaccion == 0 && efectivo < valor_venta) {
      res = valor_venta - efectivo;
      resultado = res.toLocaleString();
      $("#efectivo_en_ceros").html("Falta:" + "$" + resultado);
    }

    if (efectivo > 0 && transaccion >= 0 && suma >= valor_venta) {
      $("#facturar_pedido").modal("hide");

      Swal.fire({
        title: "¿ESTA SEGURO QUE DESEA FACTURAR?",
        icon: "question",
        showCancelButton: true,
        //confirmButtonColor: "#3085d6",
        confirmButtonColor: "#2AA13D",
        cancelButtonColor: "#d33",
        confirmButtonText: "Facturar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          var nit_cliente = document.getElementById("cliente").value;
          var id_usuario = document.getElementById("id_usuario").value;
          var estado = document.getElementById("estado").value;

          if (nit_cliente == "") {
            var error_cliente = document.getElementById("error_falta_cliente");
            error_cliente.innerHTML = "DATO NECESARIO";
            error_cliente.style.color = "red";
          } else {
            let descuento = document.getElementById("total_descuento%").value;
            let propina = document.getElementById("total_descuento_propina").value;
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
                descuento,
                propina
              },
              url: url + "/" + "pedido/cerrar_venta",
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
                      title: 'De la resolución actual solo quedan ' + resultado.facturas_restantes + ' facturas'
                    })
                  }
                  $(function () {
                    $("#cerrar_venta").on("shown.bs.modal", function (e) {
                      $("#imprimir_factura").focus();
                    });
                  });
                  document.getElementById("descuento_fact").value = resultado.descuento;
                  document.getElementById("propina_fact").value = resultado.propina;
                  myModal = new bootstrap.Modal(
                    document.getElementById("fin_de_venta"),
                    {}
                  );
                  myModal.show();
                  document.getElementById("numero_de_factura").value =
                    resultado.id_factura;
                  document.getElementById("impuesto_iva").value = resultado.iva;
                  document.getElementById("sub_total").value =
                    resultado.Sub_total;
                  document.getElementById("total_ico").value =
                    resultado.impuesto_al_consumo;
                  document.getElementById("total_factura").value =
                    resultado.total;
                  document.getElementById("efectivo_pago").value =
                    resultado.efectivo;
                  document.getElementById("cambio_pago").value =
                    resultado.cambio;
                }

                if (resultado.id_regimen == 1) {
                  $(function () {
                    $("#finalizacion_venta").on("shown.bs.modal", function (e) {
                      $("#impresion_factura").focus();
                    });
                  });

                  document.getElementById("id_de_factura").value = resultado.id_factura;
                  document.getElementById("pago_con_efectivo").value = resultado.efectivo;
                  document.getElementById("total_de_factura").value = resultado.total;
                  document.getElementById("cambio_del_pago").value = resultado.cambio;

                  document.getElementById("sub_total_de_factura").value = resultado.total;
                  document.getElementById("descuento_total_de_factura").value = resultado.descuento;
                  document.getElementById("propina_total_de_factura").value = resultado.propina;

                  myModal = new bootstrap.Modal(
                    document.getElementById("finalizacion_venta"),
                    {}
                  );
                  myModal.show();
                }
              },
            });
          }
        } else {
          document.getElementById("efectivo_format").value = "";
          document.getElementById("cambio").value = "";
          document.getElementById("valor_total_sinPunto").value = "";
          document.getElementById("efectivo").value = "0";
          document.getElementById("transaccion_format").value = "";
          document.getElementById("transaccion").value = "0";
        }
      });
    } else if (efectivo > 0 && transaccion > 0 && suma < valor_venta) {
      // res = valor_venta - suma;
      res = parseInt(valor_venta) - parseInt(suma);
      resultado = res.toLocaleString();
      $("#efectivo_en_ceros").html("Falta:" + "$" + resultado);
    }

    if (efectivo == 0 && isNaN(transaccion)) {
      $("#efectivo_en_ceros").html("No se han definido valores de pago ");
    }
    if (isNaN(transaccion) && isNaN(efectivo)) {
      $("#efectivo_en_ceros").html("No se han definido valores de pago ");
      $("#transaccion_en_ceros").html("No se han definido valores de pago ");
    }

    if (efectivo > 0 && isNaN(transaccion) && efectivo >= valor_venta) {
      $("#facturar_pedido").modal("hide");

      transaccion = 0;

      Swal.fire({
        title: "¿ESTA SEGURO QUE DESEA FACTURAR?",
        icon: "question",
        showCancelButton: true,
        //confirmButtonColor: "#3085d6",
        confirmButtonColor: "#2AA13D",
        cancelButtonColor: "#d33",
        confirmButtonText: "Facturar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          var nit_cliente = document.getElementById("cliente").value;
          var id_usuario = document.getElementById("id_usuario").value;
          var estado = document.getElementById("estado").value;

          if (nit_cliente == "") {
            var error_cliente = document.getElementById("error_falta_cliente");
            error_cliente.innerHTML = "DATO NECESARIO";
            error_cliente.style.color = "red";
          } else {
            let descuento = document.getElementById("total_descuento%").value;
            let propina = document.getElementById("total_descuento_propina").value;
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
                descuento,
                propina
              },
              url: url + "/" + "pedido/cerrar_venta",
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
                  console.log('hola mundo5 ')
                  $(function () {
                    $("#cerrar_venta").on("shown.bs.modal", function (e) {
                      $("#imprimir_factura").focus();
                    });
                  });
                  document.getElementById("descuento_fact").value = resultado.descuento;
                  document.getElementById("propina_fact").value = resultado.propina;
                  myModal = new bootstrap.Modal(
                    document.getElementById("fin_de_venta"),
                    {}
                  );
                  myModal.show();
                  document.getElementById("numero_de_factura").value =
                    resultado.id_factura;
                  document.getElementById("impuesto_iva").value = resultado.iva;
                  document.getElementById("sub_total").value =
                    resultado.Sub_total;
                  document.getElementById("total_ico").value =
                    resultado.impuesto_al_consumo;
                  document.getElementById("total_factura").value =
                    resultado.total;
                  document.getElementById("efectivo_pago").value =
                    resultado.efectivo;
                  document.getElementById("cambio_pago").value =
                    resultado.cambio;
                }

                if (resultado.id_regimen == 1) {
                  $(function () {
                    $("#finalizacion_venta").on("shown.bs.modal", function (e) {
                      $("#impresion_factura").focus();
                    });
                  });

                  document.getElementById("id_de_factura").value = resultado.id_factura;
                  document.getElementById("pago_con_efectivo").value = resultado.efectivo;
                  document.getElementById("total_de_factura").value = resultado.total;
                  document.getElementById("cambio_del_pago").value = resultado.cambio;

                  document.getElementById("sub_total_de_factura").value = resultado.total;
                  document.getElementById("descuento_total_de_factura").value = resultado.descuento;
                  document.getElementById("propina_total_de_factura").value = resultado.propina;

                  myModal = new bootstrap.Modal(
                    document.getElementById("finalizacion_venta"),
                    {}
                  );
                  myModal.show();
                }
              },
            });
          }
        } else {
          document.getElementById("efectivo_format").value = "";
          document.getElementById("cambio").value = "";
          document.getElementById("valor_total_sinPunto").value = "";
          document.getElementById("efectivo").value = "0";
          document.getElementById("transaccion_format").value = "";
          document.getElementById("transaccion").value = "0";
        }
      });
    } else if (efectivo > 0 && isNaN(transaccion) && efectivo < valor_venta) {
      res = valor_venta - efectivo;
      resultado = res.toLocaleString();
      $("#efectivo_en_ceros").html("Falta:" + "$" + resultado);
    }
    if (isNaN(efectivo) && transaccion == 0) {
      $("#efectivo_en_ceros").html("No se han definido valores de pago ");
      $("#transaccion_en_ceros").html("No se han definido valores de pago ");
    }
    if (transaccion > 0 && isNaN(efectivo) && transaccion >= valor_venta) {
      $("#facturar_pedido").modal("hide");
      efectivo = 0;
      Swal.fire({
        title: "¿ESTA SEGURO QUE DESEA FACTURAR?",
        icon: "question",
        showCancelButton: true,
        //confirmButtonColor: "#3085d6",
        confirmButtonColor: "#2AA13D",
        cancelButtonColor: "#d33",
        confirmButtonText: "Facturar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          var nit_cliente = document.getElementById("cliente").value;
          var id_usuario = document.getElementById("id_usuario").value;
          var estado = document.getElementById("estado").value;

          if (nit_cliente == "") {
            var error_cliente = document.getElementById("error_falta_cliente");
            error_cliente.innerHTML = "DATO NECESARIO";
            error_cliente.style.color = "red";
          } else {
            let descuento = document.getElementById("total_descuento%").value;
            let propina = document.getElementById("total_descuento_propina").value;
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
                descuento,
                propina
              },
              url: url + "/" + "pedido/cerrar_venta",
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
                
                  $(function () {
                    $("#cerrar_venta").on("shown.bs.modal", function (e) {
                      $("#imprimir_factura").focus();
                    });
                  });
                  document.getElementById("descuento_fact").value = resultado.descuento;
                  document.getElementById("propina_fact").value = resultado.propina;
                  myModal = new bootstrap.Modal(
                    document.getElementById("fin_de_venta"),
                    {}
                  );
                  myModal.show();
                  document.getElementById("numero_de_factura").value =
                    resultado.id_factura;
                  document.getElementById("impuesto_iva").value = resultado.iva;
                  document.getElementById("sub_total").value =
                    resultado.Sub_total;
                  document.getElementById("total_ico").value =
                    resultado.impuesto_al_consumo;
                  document.getElementById("total_factura").value =
                    resultado.total;
                  document.getElementById("efectivo_pago").value =
                    resultado.efectivo;
                  document.getElementById("cambio_pago").value =
                    resultado.cambio;
                }

                if (resultado.id_regimen == 1) {
                  $(function () {
                    $("#finalizacion_venta").on("shown.bs.modal", function (e) {
                      $("#impresion_factura").focus();
                    });
                  });

                  document.getElementById("id_de_factura").value = resultado.id_factura;
                  document.getElementById("pago_con_efectivo").value = resultado.efectivo;
                  document.getElementById("total_de_factura").value = resultado.total;
                  document.getElementById("cambio_del_pago").value = resultado.cambio;

                  document.getElementById("sub_total_de_factura").value = resultado.total;
                  document.getElementById("descuento_total_de_factura").value = resultado.descuento;
                  document.getElementById("propina_total_de_factura").value = resultado.propina;

                  myModal = new bootstrap.Modal(
                    document.getElementById("finalizacion_venta"),
                    {}
                  );
                  myModal.show();
                }
              },
            });
          }
        } else {
          document.getElementById("efectivo_format").value = "";
          document.getElementById("cambio").value = "";
          document.getElementById("valor_total_sinPunto").value = "";
          document.getElementById("efectivo").value = "0";
          document.getElementById("transaccion_format").value = "";
          document.getElementById("transaccion").value = "0";
        }
      });
    } else if (
      transaccion > 0 &&
      isNaN(efectivo) &&
      transaccion < valor_venta
    ) {
      res = valor_venta - transaccion;
      resultado = res.toLocaleString();
      $("#transaccion_en_ceros").html("Falta:" + "$" + resultado);
    }
  }
}
