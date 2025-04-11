const number = document.querySelector("#transaccion_cierre");
function formatNumber(n) {
  n = String(n).replace(/\D/g, "");
  return n === "" ? n : Number(n).toLocaleString();
}
number.addEventListener("keyup", (e) => {
  const element = e.target;
  const value = element.value;
  element.value = formatNumber(value);
});

const number_efectivo = document.querySelector("#efectivo_de_cierre");
function formatNumber(n) {
  n = String(n).replace(/\D/g, "");
  return n === "" ? n : Number(n).toLocaleString();
}
number_efectivo.addEventListener("keyup", (e) => {
  const element = e.target;
  const value = element.value;
  element.value = formatNumber(value);
});

function cierre_caja() {
  var cierre_efectivo = document.getElementById("efectivo_de_cierre").value;
  var cierre_transaccion = document.getElementById("transaccion_cierre").value;
  var efectivoFormat = cierre_efectivo.replace(/[.]/g, "");
  var transaccionFormat = cierre_transaccion.replace(/[.]/g, "");

  sub_total = parseInt(efectivoFormat) + parseInt(transaccionFormat);
  resultado = sub_total.toLocaleString();

  Swal.fire({
    icon: "question",
    title:
      "!Va a realizar el cierre de caja! con un valor de " + "$" + resultado,
    showCancelButton: true,
    confirmButtonText: "Aceptar",
    confirmButtonColor: "#2AA13D",
    cancelButtonText: "Cancelar",
    cancelButtonColor: "#C13333",
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed) {
      var url = document.getElementById("url").value;
      var usuario_cierre = document.getElementById("usuario_cierre").value;
      var efectivo_de_cierre =
        document.getElementById("efectivo_de_cierre").value;
      var transaccion_de_cierre =
        document.getElementById("transaccion_cierre").value;

      var efectivoFormat = efectivo_de_cierre.replace(/[.]/g, "");
      var transaccionFormat = transaccion_de_cierre.replace(/[.]/g, "");
      var numero_caja = document.getElementById("numero_caja").value;

      $("#error_apertura_caja").html("");
      $.ajax({
        data: {
          usuario_cierre,
          efectivoFormat,
          transaccionFormat,
          numero_caja
        },
        url: url + "/" + "caja/generar_cierre",
        type: "POST",
        success: function (resultado) {
          var resultado = JSON.parse(resultado);

          if (resultado.resultado == 1) {
            var id_cierre = resultado.id_cierre;
            Swal.fire({
              icon: "question",
              title:
                "El cierre de caja fue éxitoso ¿Desea imprimir el comprobante? ",
              showCancelButton: false,
              denyButtonText: `No imprimir`,
              showDenyButton: true,
              confirmButtonText: "Imprimir",
              confirmButtonColor: "#2AA13D",
              cancelButtonText: "Cancelar",
              cancelButtonColor: "#C13333",
              denyButtonColor: "#C13333",
              reverseButtons: true,
            }).then((result) => {
              if (result.isConfirmed) {
                console.log(id_cierre)
                $.ajax({
                  data: {
                    id_cierre,
                  },
                  url: url + "/" + "caja/imprimir_cierre",
                  type: "POST",
                  success: function (resultado) {
                    var resultado = JSON.parse(resultado);

                    if (resultado.resultado == 1) {
                      let id_cierre = resultado.id_cierre

                      Swal.fire({
                        icon: "question",
                        title: "¿Desea imprimir el movimiento de caja ? ",
                        showCancelButton: false,
                        denyButtonText: `No imprimir`,
                        showDenyButton: true,
                        confirmButtonText: "Imprimir",
                        confirmButtonColor: "#2AA13D",
                        cancelButtonText: "Cancelar",
                        cancelButtonColor: "#C13333",
                        denyButtonColor: "#C13333",
                        reverseButtons: true,
                      }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        console.log(id_cierre)
                        if (result.isConfirmed) {
                          $.ajax({
                            data: {
                              id_cierre,
                            },
                            url: url + "/" + "caja/imp_movimiento_caja",
                            type: "POST",
                            success: function (resultado) {
                              var resultado = JSON.parse(resultado);

                              if (resultado.resultado == 1) {
                                $("#creacion_cliente_factura_pos").modal(
                                  "hide"
                                );
                                Swal.fire({
                                  title: "Impresión de movimiento de caja correcto",
                                  showDenyButton: true,
                                  showCancelButton: false,
                                  confirmButtonText: "Aceptar",
                                  denyButtonText: `Imprimir reporte de ventas `,
                                  confirmButtonColor: "#2AA13D",
                                  denyButtonColor: "#0d6efd",
                                }).then((result) => {
                                  /* Read more about isConfirmed, isDenied below */
                                  if (result.isConfirmed) {
                                    Swal.fire({
                                      title: "Cierre de caja finalizado?",
                                      icon: "success",
                                      confirmButtonText: "Aceptar",
                                      confirmButtonColor: "#2AA13D",
                                    });
                                  } else if (result.isDenied) {
                                    $.ajax({
                                      data: {
                                        id_cierre,
                                      },
                                      url: url + "/" + "pedidos/reporte_ventas",
                                      type: "POST",
                                      success: function (resultado) {
                                        var resultado = JSON.parse(resultado);
                
                                        if (resultado.resultado == 1) {
                                          Swal.fire({
                                            icon: "success",
                                            title: "Impresión de reporte de ventas correcto ",
                                            confirmButtonText: "Aceptar",
                                            confirmButtonColor: "#2AA13D",
                                          });
                                        }
                                      },
                                    });
                                  }
                                });
                              }
                            },
                          });
                        } else if (result.isDenied) {
                          Swal.fire({
                            icon: "info",
                            title: "No se imprime el movimiento de caja ",
                            showCancelButton: false,

                            confirmButtonText: "Aceptar",
                            confirmButtonColor: "#2AA13D",
                          });
                          document.getElementById("efectivo_de_cierre").value =
                            "";
                          document.getElementById("transaccion_cierre").value =
                            "";
                        }
                      });
                    }
                  },
                });
              } else if (result.isDenied) {
                Swal.fire({
                  icon: "question",
                  title: "¿Desea imprimir el movimiento de caja ? ",
                  showCancelButton: false,
                  denyButtonText: `No imprimir`,
                  showDenyButton: true,
                  confirmButtonText: "Imprimir",
                  confirmButtonColor: "#2AA13D",
                  cancelButtonText: "Cancelar",
                  cancelButtonColor: "#C13333",
                  denyButtonColor: "#C13333",
                  reverseButtons: true,
                }).then((result) => {
                  /* Read more about isConfirmed, isDenied below */
                  if (result.isConfirmed) {
                    let id_cierre = resultado.id_cierre

                    $.ajax({
                      data: {
                        id_cierre,
                      },
                      url: url + "/" + "pedidos/reporte_ventas",
                      type: "POST",
                      success: function (resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 0) {
                          $("#creacion_cliente_factura_pos").modal("hide");
                          Swal.fire({
                            icon: "success",
                            title: "Cliente agregado",
                          });
                        }
                        if (resultado.resultado == 1) {
                          Swal.fire({
                            icon: "success",
                            title: "Impresión de movimiento de caja correcto",
                            confirmButtonText: "Aceptar",
                            confirmButtonColor: "#2AA13D",
                          });
                        }
                      },
                    });
                  } else if (result.isDenied) {
                    Swal.fire({
                      icon: "info",
                      title: "No se imprime el movimiento de caja ",
                      showCancelButton: false,

                      confirmButtonText: "Aceptar",
                      confirmButtonColor: "#2AA13D",
                    });
                    document.getElementById("efectivo_de_cierre").value = "";
                    document.getElementById("transaccion_cierre").value = "";
                  }
                });
              }
            });
          }
          if (resultado.resultado == 2) {
            var id_cierre = resultado.id_cierre;
            Swal.fire({
              icon: "question",
              title:
                "El cierre de caja fue éxitoso ¿Desea imprimir el comprobante? ",
              showCancelButton: false,
              denyButtonText: `No imprimir`,
              showDenyButton: true,
              confirmButtonText: "Imprimir",
              confirmButtonColor: "#2AA13D",
              cancelButtonText: "Cancelar",
              cancelButtonColor: "#C13333",
              denyButtonColor: "#C13333",
              reverseButtons: true,
            }).then((result) => {
              if (result.isConfirmed) {
                $.ajax({
                  data: {
                    id_cierre,
                  },
                  url: url + "/" + "caja/imprimir_cierre",
                  type: "POST",
                  success: function (resultado) {
                    var resultado = JSON.parse(resultado);

                    if (resultado.resultado == 0) {
                      $("#creacion_cliente_factura_pos").modal("hide");
                      Swal.fire({
                        icon: "success",
                        title: "Cliente agregado",
                      });
                    }
                    if (resultado.resultado == 1) {
                      alert("No se pudo insertarss");
                    }
                  },
                });
              } else if (result.isDenied) {
                Swal.fire({
                  icon: "question",
                  title: "¿Desea imprimir el movimiento de caja ? ",
                  showCancelButton: false,
                  denyButtonText: `No imprimir`,
                  showDenyButton: true,
                  confirmButtonText: "Imprimir",
                  confirmButtonColor: "#2AA13D",
                  cancelButtonText: "Cancelar",
                  cancelButtonColor: "#C13333",
                  denyButtonColor: "#C13333",
                  reverseButtons: true,
                }).then((result) => {
                  /* Read more about isConfirmed, isDenied below */
                  if (result.isConfirmed) {
                    let id_cierre = resultado.cierre
                    console.log(id_cierre)
                    $.ajax({
                      data: {
                        id_cierre,
                      },
                      url: url + "/" + "caja/imprimir_cierre",
                      type: "POST",
                      success: function (resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 0) {
                          $("#creacion_cliente_factura_pos").modal("hide");
                          Swal.fire({
                            icon: "success",
                            title: "Cliente agregado",
                          });
                        }
                        if (resultado.resultado == 1) {
                          Swal.fire({
                            icon: "success",
                            title: "Impreisón de movimiento de caja correcto",
                            confirmButtonText: "Aceptar",
                            confirmButtonColor: "#2AA13D",
                          });
                        }
                      },
                    });
                  } else if (result.isDenied) {
                    Swal.fire("Changes are not saved", "", "info");
                  }
                });
              }
            });
          }
          if (resultado.resultado == 0) {
            Swal.fire({
              icon: "warning",
              title: "Caja no tiene apertura ",
              showCancelButton: true,
              confirmButtonText: "Aceptar",
              confirmButtonColor: "#2AA13D",
              cancelButtonText: "Cancelar",
              cancelButtonColor: "#C13333",
              reverseButtons: true,
            });
          }
        },
      });
    }
  });
}
