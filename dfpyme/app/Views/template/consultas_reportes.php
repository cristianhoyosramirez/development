<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title><?= $this->renderSection('title') ?>&nbsp;-&nbsp;DF PYME</title>
  <!-- CSS files -->
  <link href="<?= base_url() ?>/Assets/css/tabler.min.css" rel="stylesheet" />
  <!-- App favicon -->
  <link rel="shortcut icon" href="<?php echo base_url(); ?>/Assets/img/favicon.png">
  <!-- Select 2 -->
  <link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.css" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
  <!-- Data tables -->
  <link href="<?= base_url() ?>/Assets/plugin/data_tables/bootstrap.min.css" />
  <link href="<?= base_url() ?>/Assets/plugin/data_tables/dataTables.bootstrap5.min.css" />
  <!-- Jquery date picker  -->
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/Assets/plugin/calendario/jquery-ui-1.12.1.custom/jquery-ui.css">
  <!-- Jquery-ui -->
  <link href="<?php echo base_url() ?>/Assets/plugin/jquery-ui/jquery-ui.css" rel="stylesheet">
</head>
<?php $session = session(); ?>

<body>
  <div class="wrapper">
    <?= $this->include('layout/header_mesas') ?>


    <div class="page-wrapper">
      <div class="container-xl">
      </div>
      <div class="page-body">
        <?= $this->renderSection('content') ?>
        <?= $this->include('ventanas_modal_duplicado_factura/detalle_factura') ?>
        <?= $this->include('ventanas_modal_detalle_de_ventas/detalle_de_ventas') ?>
        <?= $this->include('modal_abono_factura/abono') ?>
        <?= $this->include('modal_abono_factura/modal_detalle_factura') ?>


        <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/detalle_f_e.js"></script>
      </div>
      <?= $this->include('layout/footer') ?>
    </div>
    <!-- Libs JS -->

    <!-- Modal -->
    <div class="modal fade" id="modal_informe_fiscal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="titulo_reporte">Reporte fiscal de ventas </h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="datos_informe"></div>
          </div>

        </div>
      </div>
    </div>


    <!-- Tabler Core -->
    <script src="<?= base_url() ?>/Assets/js/tabler.min.js"></script>
    <!-- JQuery -->
    <script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
    <!-- jQuery-ui -->
    <script src="<?php echo base_url() ?>/Assets/plugin/jquery-ui/jquery-ui.js"></script>
    <!-- Data tables -->
    <script src="<?= base_url() ?>/Assets/plugin/data_tables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() ?>/Assets/plugin/data_tables/dataTables.bootstrap5.min.js"></script>
    <!--select2 -->
    <script src="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.js"></script>
    <!-- Sweet alert -->
    <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
    <!-- Locales -->
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/ventas_diarias.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/consultas_movimientos_caja.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/reporte_caja_diaria.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/detalle_de_ventas.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/detalle_de_ventas_sin_cierre.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/impuesto_iva.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/impoconsumo.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/impuesto_ico.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/duplicado_factura/duplicado_factura.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/duplicado_factura/detalle_factura.js"></script>

    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/ventas_diarias.js"></script>

    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/imprimir_movimientos.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/imprimir_movimientos_sin_cierre.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/imprimir_duplicado_retiro.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/edicion_retiro_de_dinero.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/actualizar_retiro_de_dinero.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/actualizar_apertura_caja_sin_cierre.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/actualizar_de_apertura.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/cancelar_edicion_de_apertura.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/edicion_de_apertura.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/data_table_movimientos_caja.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/ver_reportes_de_caja.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/imprimir_reporte_fiscal.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/actualizar_transaccion_cierre.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/select2.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/cambiar_valor_apertura.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/edicion_efectivo_usuario.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/edicion_transaccion_usuario.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/data_table_consulta_ventas.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/data_picker_entre_fechas.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/duplicado_factura/detalle_factura.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/edicion_de_apertura_sin_cierre.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/edicion_de_apertura_sin_cierre.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/actualizar_efectivo_usuario.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_start.js"></script>
    <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_centrado.js"></script>
    <!-- Sweet alert -->
    <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
    <!-- Calendario -->
    <script src="<?= base_url() ?>/Assets/plugin/calendario/jquery-ui-1.12.1.custom/jquery-ui.js"></script>

    <script>
      function buscar_pedidos_borrados() {

        let url = document.getElementById("url").value;
        let fecha_inicial = document.getElementById("fecha_inicial").value;
        let fecha_final = document.getElementById("fecha_final").value;
        let criterio_seleccion = document.getElementById("periodo_fechas").value;

        if (criterio_seleccion == "") {
          sweet_alert_start('error', 'No hay rango seleccionado')
          return; // Agregar return para detener la ejecución si no hay rango seleccionado
        }

        if ($.fn.DataTable.isDataTable('#pedidos_borrados')) {
          $('#pedidos_borrados').DataTable().destroy();
        }

        $('#pedidos_borrados').DataTable({
          serverSide: true,
          processing: true,
          searching: false,
          dom: 'Bfrtip',
          buttons: [
            'excelHtml5' // Agregar el botón de exportar a Excel
          ],
          order: [
            [0, 'desc']
          ],
          language: {
            decimal: "",
            emptyTable: "No hay datos",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(Filtro de _MAX_ total registros)",
            infoPostFix: "",
            thousands: ",",
            lengthMenu: "Mostrar _MENU_ registros",
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            search: "Buscar",
            zeroRecords: "No se encontraron coincidencias",
            paginate: {
              first: "Primero",
              last: "Ultimo",
              next: "Próximo",
              previous: "Anterior"
            },
            aria: {
              sortAscending: ": Activar orden de columna ascendente",
              sortDescending: ": Activar orden de columna desendente"
            }
          },
          ajax: {
            url: '<?php echo base_url() ?>' + "/reportes/pedidos_borrados",
            type: 'GET',
            data: {
              fecha_inicial: fecha_inicial,
              fecha_final: fecha_final
            },
            dataSrc: function(json) {
              $('#total_pedidos').html(json.total_venta);
              return json.data;
            }
          },
          columnDefs: [{
            targets: [4],
            orderable: false
          }]
        });
      }
    </script>

    <script>
      function periodo(id) {

        if (id == 1) {
          document.getElementById("inicial").style.display = "none";
          document.getElementById("final").style.display = "none";
        }
        if (id == 2) {
          document.getElementById("inicial").style.display = "block";
          document.getElementById("final").style.display = "none";
        }
        if (id == 3) {
          document.getElementById("inicial").style.display = "block";
          document.getElementById("final").style.display = "block";
        }

      }
    </script>


    <script>
      function productos_borrados(id_pedido) {
        var url = document.getElementById("url").value;
        $.ajax({
          data: {
            id_pedido,
          },
          url: url + "/" + "inventario/productos_borrados",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {

              $('#resultado_productos_borrados').html(resultado.productos)

              myModal = new bootstrap.Modal(
                document.getElementById("productos_borrados"), {}
              );
              myModal.show();
            }
          },
        });
      }
    </script>


    <script>
      $("#periodo_fechas").select2({
        width: "100%",
        placeholder: "Selecciona un rango ",
        language: "es",
        theme: "bootstrap-5",
        allowClear: false,
        closeOnSelect: true,
        minimumResultsForSearch: Infinity
      });
    </script>


    <script>
      $(document).ready(function() {
        // Muestra el modal cuando comienza la solicitud AJAX
        /*   $(document).ajaxStart(function() {
              $('#processing-bar').show();
          });

          // Oculta el modal cuando todas las solicitudes AJAX se completan
          $(document).ajaxStop(function() {
              $('#processing-bar').hide();
          }); */

        var dataTable = $('#pedidos_borrados').DataTable({
          serverSide: true,
          processing: true,
          searching: false,
          dom: 'Bfrtip',
          buttons: [
            'excelHtml5' // Agregar el botón de exportar a Excel
          ],
          order: [
            [0, 'desc']
          ],
          language: {
            decimal: "",
            emptyTable: "No hay datos",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(Filtro de _MAX_ total registros)",
            infoPostFix: "",
            thousands: ",",
            lengthMenu: "Mostrar _MENU_ registros",
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            search: "Buscar",
            zeroRecords: "No se encontraron coincidencias",
            paginate: {
              first: "Primero",
              last: "Ultimo",
              next: "Próximo",
              previous: "Anterior"
            },
            aria: {
              sortAscending: ": Activar orden de columna ascendente",
              sortDescending: ": Activar orden de columna desendente"
            }
          },
          ajax: {
            url: '<?php echo base_url() ?>' + "/reportes/pedidos_borrados",
            data: function(d) {
              return $.extend({}, d, {
                // documento: documento,
                // fecha_inicial: fecha_inicial,
                // fecha_final: fecha_final
              });
            },
            dataSrc: function(json) {
              $('#total_pedidos').html(json.total_venta);

              return json.data;
            },
          },
          columnDefs: [{
            targets: [4],
            orderable: false
          }]
        });
      });
    </script>
    <script>
      $(document).ready(function() {
        $('#facturas_electronicas').DataTable({
            ordering: false,
            "language": {
              "decimal": "",
              "emptyTable": "No hay datos",
              "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
              "infoEmpty": "Mostrando 0 a 0 de 0 registros",
              "infoFiltered": "(Filtro de _MAX_ total registros)",
              "infoPostFix": "",
              "thousands": ",",
              "lengthMenu": "Mostrar _MENU_ registros",
              "loadingRecords": "Cargando...",
              "processing": "Procesando...",
              "search": "Buscar:",
              "zeroRecords": "No se encontraron coincidencias",
              "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Próximo",
                "previous": "Anterior"
              },
              "aria": {
                "sortAscending": ": Activar orden de columna ascendente",
                "sortDescending": ": Activar orden de columna desendente"
              }

            },
            "order": [],
            "bSort": true,
          }

        );
      })
    </script>

    <script>
      let mensaje = "<?php echo $session->getFlashdata('mensaje'); ?>";
      let iconoMensaje = "<?php echo $session->getFlashdata('iconoMensaje'); ?>";
      if (mensaje != "") {
        Swal.fire({
          title: mensaje,
          icon: iconoMensaje,
          confirmButtonText: 'ACEPTAR',
          confirmButtonColor: "#2AA13D",
        })
      }
    </script>


    <script>
      function abono_credito(id_factura) {
        var url = document.getElementById("url").value;
        $.ajax({
          data: {
            id_factura,
          },
          url: url + "/" + "consultas_y_reportes/saldo_factura",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {
              $('#abono_a_factura').html('Abono a factura :' + resultado.numero_factura)
              $('#valor_factura_credito').val(resultado.valor_factura)
              $('#saldo_factura_credito').val(resultado.saldo)
              $('#id_factura_credito').val(resultado.id_factura)

              myModal = new bootstrap.Modal(
                document.getElementById("abono_factura"), {}
              );
              myModal.show();
            }
            if (resultado.resultado == 0) {
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
                title: 'Cliente no tiene saldo cartera '
              })

            }
          },
        });

      }

      $(function() {
        $("#abono_factura").on("shown.bs.modal", function(e) {
          $("#abono_factura_credito").focus();
        });
      });


      const abonar_a_factura =
        document.querySelector("#valor_abono_factura_credito");

      function formatNumber(n) {
        n = String(n).replace(/\D/g, "");
        return n === "" ? n : Number(n).toLocaleString();
      }
      abonar_a_factura.addEventListener("keyup", (e) => {
        const element = e.target;
        const value = element.value;
        element.value = formatNumber(value);
      });

      const abonar_a_factura_transaccion =
        document.querySelector("#valor_abono_factura_credito_transaccion");

      function formatNumber(n) {
        n = String(n).replace(/\D/g, "");
        return n === "" ? n : Number(n).toLocaleString();
      }
      abonar_a_factura_transaccion.addEventListener("keyup", (e) => {
        const element = e.target;
        const value = element.value;
        element.value = formatNumber(value);
      });


      function saltar(e, id) {
        // Obtenemos la tecla pulsada
        (e.keyCode) ? k = e.keyCode: k = e.which;

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
        saldo_factura_credito
      }



      function cambio_efectivo_credito() {

        //var saldo_pendiente = document.getElementById("saldo_factura_credito").value;
        var saldo_pendiente = document.getElementById("abono_factura_credito").value;
        var saldo = parseInt(saldo_pendiente.replace(/[.]/g, ""));

        var pago_efectivo = document.getElementById("valor_abono_factura_credito").value;
        var pago_transaccion = document.getElementById("valor_abono_factura_credito_transaccion").value;


        var efectivo = parseInt(pago_efectivo.replace(/[.]/g, ""));
        var transaccion = parseInt(pago_transaccion.replace(/[.]/g, ""));
        var temp = parseInt(efectivo) + parseInt(transaccion);
        var res = parseInt(temp - saldo);
        resultado = res.toLocaleString();
        document.getElementById("cambio_abono_factura_credito").value = resultado;

      }

      function guardar_Abono() {


        var url = document.getElementById("url").value;
        var id_usuario = document.getElementById("usuario_reporte").value;
        var id_factura = document.getElementById("id_factura_credito").value;
        var efecti = document.getElementById("valor_abono_factura_credito").value;
        var efectivo = efecti.replace(/[.]/g, "");
        var transa = document.getElementById("valor_abono_factura_credito_transaccion").value;
        var transaccion = transa.replace(/[.]/g, "");

        var saldo_pendiente = document.getElementById("abono_factura_credito").value;
        var saldo = parseInt(saldo_pendiente.replace(/[.]/g, ""));

        var abono_cliente = document.getElementById("abono_factura_credito").value;
        var abono = parseInt(abono_cliente.replace(/[.]/g, ""));

        /*  if (abono > saldo) {

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
             title: 'El abono no puede ser mayor al sado '
           })
         } */


        // if (abono <= saldo) {


        var resultado = parseInt(efectivo) + parseInt(transaccion)

        if (resultado < saldo) {
          $('#abono_credito_falta_plata').html('FALTA DINERO')

        } else if (resultado >= saldo) {



          $.ajax({
            data: {
              efectivo,
              transaccion,
              id_factura,
              abono,
              saldo,
              id_usuario
            },
            url: url + "/" + "consultas_y_reportes/actualizar_saldo",
            type: "POST",
            success: function(resultado) {
              var resultado = JSON.parse(resultado);

              if (resultado.resultado == 0) {
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
                  title: 'No hay pedido para cagar una nota '
                })
              }
              if (resultado.resultado == 1) {


                $('#abono_factura').modal('hide');
                $('#id_factura_credito').val('');
                $('#saldo_factura_credito').val('0');
                $('#valor_abono_factura_credito').val('0');
                $('#valor_abono_factura_credito_transaccion').val('0');
                $('#cambio_abono_factura_credito').val('0');

                mytable = $('#consulta_ventas').DataTable();
                mytable.draw();

                Swal.fire({
                  icon: "success",
                  title: "Abono realizado con éxito",
                  confirmButtonText: "Imprimir comprobante de ingreso",
                  confirmButtonColor: "#2AA13D",
                  showDenyButton: true,
                  denyButtonText: `No imprimir`,
                  denyButtonColor: "#C13333",
                  reverseButtons: true,
                }).then((result) => {
                  /* Read more about isConfirmed, isDenied below */
                  if (result.isConfirmed) {
                    (id_ingreso = resultado.id_ingreso),
                    $.ajax({
                      data: {
                        id_ingreso,
                      },
                      url: url + "/" + "consultas_y_reportes/imprimir_ingreso",
                      type: "POST",
                      success: function(resultado) {
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
            },
          });
        }
      }



      const abonar_a_factura_credit =
        document.querySelector("#abono_factura_credito");

      function formatNumber(n) {
        n = String(n).replace(/\D/g, "");
        return n === "" ? n : Number(n).toLocaleString();
      }
      abonar_a_factura_credit.addEventListener("keyup", (e) => {
        const element = e.target;
        const value = element.value;
        element.value = formatNumber(value);
      });



      function abono_efectivo_credito() {
        var saldo_pendiente = document.getElementById("saldo_factura_credito").value;
        var saldo = parseInt(saldo_pendiente.replace(/[.]/g, ""));

        var abono_cliente = document.getElementById("abono_factura_credito").value;
        var abono = parseInt(abono_cliente.replace(/[.]/g, ""));

        if (abono > saldo) {
          $('#abono_mayor_que_saldo').html('El abono supera el saldo ')
        }



      }
    </script>

    <script>
      var input = document.getElementById('abono_factura_credito');

      input.onkeydown = function() {
        const key = event.key;
        if (key === "Backspace") {
          $("#abono_mayor_que_saldo").html("");
        }
      };
    </script>


    <script>
      $("#cliente_reporte").autocomplete({
        source: function(request, response) {
          var url = document.getElementById("url").value;
          $.ajax({
            type: "POST",
            url: url + "/" + "clientes/clientes_autocompletado",
            data: request,
            success: response,
            dataType: "json",
          });
        },
      }, {
        minLength: 1,
      }, {
        select: function(event, ui) {
          // $("#id_cliente_factura_pos").val(ui.item.value);
          //$("#clientes_factura_pos").val(ui.item.nit_cliente);
          $("#id_cliente_reporte").val(ui.item.nit_cliente);

        },
      });
    </script>

    <script>
      $(function() {
        // var dateFormat = "mm/dd/yy",
        var dateFormat = "yy/mm/dd",

          from = $("#fecha_reporte_caja")
          .datepicker({
            //defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            changeYear: true,

          })
          .on("change", function() {
            to.datepicker("option", "minDate", getDate(this));
          }),
          to = $("#fecha_final_reporte").datepicker({
            //defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1
          })
          .on("change", function() {
            from.datepicker("option", "maxDate", getDate(this));
          });

        function getDate(element) {
          var date;
          try {
            date = $.datepicker.parseDate(dateFormat, element.value);
          } catch (error) {
            date = null;
          }

          return date;
        }
      });
    </script>



    <script>
      function solo_guardar_enviar_formulario() {

        var url = document.getElementById("url").value;
        var total_venta_cero = document.getElementById("total_venta_cero").value;
        var base_cero = total_venta_cero.replace(/[.]/g, "");

        var total_venta_8 = document.getElementById("total_venta_8").value;
        var base_ico = total_venta_8.replace(/[.]/g, "");

        var fecha_reporte = document.getElementById("fecha_reporte_caja").value;

        $.ajax({
          url: url + "/" + "consultas_y_reportes/solo_guardar_reporte_caja_diaria",
          data: {
            base_cero,
            base_ico,
            fecha_reporte
          },
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);

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
                title: 'Registro guardado éxitosamente'
              })

              $("#edicion_de_apertura_de_caja").modal("hide");
              $("#valor_modificado_apertura").html(resultado.valor_apertura);
              $("#nuevo_saldo").html(resultado.saldo);
              $("#cambiar_valor_apertura").val(resultado.val_apertura);

            }
            if (resultado.resultado == 0) {


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
                title: 'Fecha ya tenia registro y se modifico éxitosamente'
              })

              $("#edicion_de_apertura_de_caja").modal("hide");
              $("#valor_modificado_apertura").html(resultado.valor_apertura);
              $("#nuevo_saldo").html(resultado.saldo);
              $("#cambiar_valor_apertura").val(resultado.val_apertura);

            }
          }
        });

      }
    </script>

    <script>
      function imprimir_reporte_de_caja_diario(id) {
        var url = document.getElementById("url").value;
        $.ajax({
          url: url + "/" + "consultas_y_reportes/imprimir_reporte_de_caja_id",
          data: {
            id
          },
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);

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
                title: 'Registro guardado éxitosamente'
              })

              $("#edicion_de_apertura_de_caja").modal("hide");
              $("#valor_modificado_apertura").html(resultado.valor_apertura);
              $("#nuevo_saldo").html(resultado.saldo);
              $("#cambiar_valor_apertura").val(resultado.val_apertura);

            }
            if (resultado.resultado == 0) {


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
                title: 'Fecha ya tenia registro y se modifico éxitosamente'
              })

              $("#edicion_de_apertura_de_caja").modal("hide");
              $("#valor_modificado_apertura").html(resultado.valor_apertura);
              $("#nuevo_saldo").html(resultado.saldo);
              $("#cambiar_valor_apertura").val(resultado.val_apertura);

            }
          }
        });
      }
    </script>


    <script>
      function reporte_movimiento_efectivo() {
        var url = document.getElementById("url").value;
        var fecha_inicial = document.getElementById("fecha_inicial").value;
        var fecha_final = document.getElementById("fecha_final").value;
        $.ajax({
          url: url + "/" + "consultas_y_reportes/datos_reporte_flujo_efectivo",
          data: {
            fecha_inicial,
            fecha_final
          },
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);

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
                title: 'Reporte generado éxitosamente '
              })

              $("#reporte_flujo_efectivo").html(resultado.retiros);


            }
            if (resultado.resultado == 0) {


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
                title: 'Fecha ya tenia registro y se modifico éxitosamente'
              })

              $("#edicion_de_apertura_de_caja").modal("hide");
              $("#valor_modificado_apertura").html(resultado.valor_apertura);
              $("#nuevo_saldo").html(resultado.saldo);
              $("#cambiar_valor_apertura").val(resultado.val_apertura);

            }
          }
        });
      }
    </script>


    <script>
      $(function() {
        $("#fecha_reporte").datepicker();
      });
    </script>

    <script>
      function reimprimir_movimiento() {
        var url = document.getElementById("url").value;
        var fecha_inicial = document.getElementById("fecha_inicial").value;
        var fecha_final = document.getElementById("fecha_final").value;
        $.ajax({
          url: url + "/" + "consultas_y_reportes/datos_reporte_flujo_efectivo",
          data: {
            fecha_inicial,
            fecha_final
          },
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);

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
                title: 'Reporte generado éxitosamente '
              })

              $("#reporte_flujo_efectivo").html(resultado.retiros);


            }
            if (resultado.resultado == 0) {


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
                title: 'Fecha ya tenia registro y se modifico éxitosamente'
              })

              $("#edicion_de_apertura_de_caja").modal("hide");
              $("#valor_modificado_apertura").html(resultado.valor_apertura);
              $("#nuevo_saldo").html(resultado.saldo);
              $("#cambiar_valor_apertura").val(resultado.val_apertura);

            }
          }
        });
      }
    </script>


    <script>
      function editar_apertura() {
        let id_apertura = document.getElementById("id_apertura").value;
        var url = document.getElementById("url").value;
        $.ajax({
          data: {
            id_apertura,
          },
          url: url + "/" + "consultas_y_reportes/editar_valor_apertura",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {
              $("#valor_de_la_apertura").val(resultado.valor_apertura);
              $("#valor_id_apertura").val(resultado.id_apertura);

              myModal = new bootstrap.Modal(
                document.getElementById("editar_apertura_caja_diaria"), {}
              );
              myModal.show();
            }
          },
        });
      }
    </script>


    <script>
      function cambiar_apertura() {
        var url = document.getElementById("url").value;
        var id_apertura = document.getElementById("valor_id_apertura").value;
        var valor_apertura = document.getElementById("valor_de_la_apertura").value;

        $.ajax({
          data: {
            id_apertura,
            valor_apertura
          },
          url: url + "/" + "consultas_y_reportes/cambiar_valor_apertura",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {
              $("#crud_apertura").html(resultado.datos);

              $("#editar_apertura_caja_diaria").modal("hide");
            }
          },
        });
      }
    </script>
    <script>
      function total_ingresos_efectivo(id_apertura) {
        var url = document.getElementById("url").value;

        $.ajax({
          data: {
            id_apertura,
          },
          url: url + "/" + "consultas_y_reportes/total_ingresos_efectivo",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {
              $("#ingresos_efectivo").html(resultado.efectivo);

              myModal = new bootstrap.Modal(
                document.getElementById("modal_tota_ingresos_efectivo"), {}
              );
              myModal.show();
            }
          },
        });
      }
    </script>
    <script>
      function total_ingresos_transaccion(id_apertura) {
        var url = document.getElementById("url").value;

        $.ajax({
          data: {
            id_apertura,
          },
          url: url + "/" + "consultas_y_reportes/total_ingresos_transaccion",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {
              $("#ingresos_transaccion").html(resultado.transaccion);

              myModal = new bootstrap.Modal(
                document.getElementById("modal_tota_ingresos_transaccion"), {}
              );
              myModal.show();
            }
          },
        });
      }
    </script>

    <script>
      function buscar_movimientos_de_caja() {
        var url = document.getElementById("url").value;
        var fecha_inicial = document.getElementById("fecha_inicio_cierre").value;
        var fecha_final = document.getElementById("fecha_final_cierre").value;

        $.ajax({
          data: {
            fecha_inicial,
            fecha_final
          },
          url: url + "/" + "consultas_y_reportes/movimientos_de_caja",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {
              $("#movimientos_de_caja").html(resultado.aperturas);




            }
          },
        });
      }
    </script>

    <script>
      function ver_retiros(id_apertura) {
        var url = document.getElementById("url").value;
        $.ajax({
          data: {
            id_apertura
          },
          url: url + "/" + "consultas_y_reportes/detalle_retiros",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {
              $("#retiros_de_dinero").html(resultado.retiros);

              myModal = new bootstrap.Modal(
                document.getElementById("modal_consulta_retiros"), {}
              );
              myModal.show();

            }
            if (resultado.resultado == 0) {
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
                title: 'No se encontraron egresos'
              })
            }
          },
        });
      }
    </script>

    <script>
      function editar_valor_de_cierre(id_apertura) {
        var url = document.getElementById("url").value;
        $.ajax({
          data: {
            id_apertura
          },
          url: url + "/" + "consultas_y_reportes/editar_valor_cierre",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {
              $("#valor_cierre_efectivo").val(resultado.valor_cierre);
              $("#id_apertura").val(resultado.id_apertura);

              myModal = new bootstrap.Modal(
                document.getElementById("modal_editar_cierre_efectivo"), {}
              );
              myModal.show();

            }
            if (resultado.resultado == 0) {
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
                title: 'No se puede cambiar el valor de cierre por que no se cerrado la caja'
              })
            }
          },
        });
      }
    </script>


    <script>
      function actualizar_valor_cierre() {
        var url = document.getElementById("url").value;
        var valor_cierre = document.getElementById("valor_cierre_efectivo").value;
        var id_apertura = document.getElementById("id_apertura").value;
        $.ajax({
          data: {
            id_apertura,
            valor_cierre

          },
          url: url + "/" + "consultas_y_reportes/actualizar_valor_cierre",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {
              $("#crud_apertura").html(resultado.datos);


              $('#modal_editar_cierre_efectivo').modal('hide');

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
                title: 'Datos de cierre de efectivo cambiados '
              })

            }
            if (resultado.resultado == 0) {
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
                title: 'No se puede cambiar el valor de cierre por que no se cerrado la caja'
              })
            }
          },
        });
      }
    </script>


    <script>
      function editar_valor_cierre_transferencias(id_apertura) {
        var url = document.getElementById("url").value;
        $.ajax({
          data: {
            id_apertura
          },
          url: url + "/" + "consultas_y_reportes/editar_valor_cierre_transferencias",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {
              $("#valor_cierre_transaccion").val(resultado.valor_cierre);
              $("#id_apertura").val(resultado.id_apertura);

              myModal = new bootstrap.Modal(
                document.getElementById("modal_editar_cierre_transaccion"), {}
              );
              myModal.show();

            }
            if (resultado.resultado == 0) {
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
                title: 'No se puede cambiar el valor de cierre por que no se cerrado la caja'
              })
            }
          },
        });
      }
    </script>


    <script>
      function actualizar_valor_cierre_transferencias() {
        var url = document.getElementById("url").value;
        var valor_cierre_transferencia = document.getElementById("valor_cierre_transaccion").value;
        var id_apertura = document.getElementById("id_apertura").value;
        $.ajax({
          data: {
            id_apertura,
            valor_cierre_transferencia

          },
          url: url + "/" + "consultas_y_reportes/actualizar_valor_cierre_transferencias",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {
              $("#crud_apertura").html(resultado.datos);


              $('#modal_editar_cierre_transaccion').modal('hide');

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
                title: 'Datos de cierre de transaccion cambiados '
              })

            }
            if (resultado.resultado == 0) {
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
                title: 'No se puede cambiar el valor de cierre por que no se cerrado la caja'
              })
            }
          },
        });
      }
    </script>


    <script>
      function imprimir_duplicado_retiro(id_retiro) {

        var url = document.getElementById("url").value;
        var id_retiro = id_retiro;
        $.ajax({
          data: {
            id_retiro
          },
          url: url + "/" + "devolucion/re_imprimir_retiro",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            $("#imprimir_retiro").modal("hide");
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
                title: 'Rempresión de comprobante de retiro de dinero '
              })
            }
          },
        });
      }
    </script>



    <script>
      function impoconsumo_manual(e, base) {
        const total_ico = document.querySelector("#total_venta_8_manual");

        function formatNumber(n) {
          n = String(n).replace(/\D/g, "");
          return n === "" ? n : Number(n).toLocaleString();
        }
        total_ico.addEventListener("keyup", (e) => {
          const element = e.target;
          const value = element.value;
          element.value = formatNumber(value);
        });

        var url = document.getElementById("url").value;
        var total = document.getElementById("total_venta_8_manual").value;
        var totalFormat = total.replace(/[.]/g, "");

        base = parseInt(totalFormat) / 1.08
        impuesto = totalFormat - base

        $("#base_ico_manual").val(base.toLocaleString('es-ES'));
        $("#valor_impuesto_8_manual").val(impuesto.toLocaleString('es-ES'));


      }
    </script>


    <script>
      /**
       * Aufoco en el modal de creacion de cliente en el modulo de facturar pedido
       */
      $(function() {
        $("#abono_saldo_cartera").on("shown.bs.modal", function(e) {
          $("#abono_general_cartera").focus();
        });
      });


      const abono =
        document.querySelector("#abono_general_cartera");

      function formatNumber(n) {
        n = String(n).replace(/\D/g, "");
        return n === "" ? n : Number(n).toLocaleString();
      }
      abono.addEventListener("keyup", (e) => {
        const element = e.target;
        const value = element.value;
        element.value = formatNumber(value);
      });

      const efectivo =
        document.querySelector("#efectivo_abono_cartera");

      function formatNumber(n) {
        n = String(n).replace(/\D/g, "");
        return n === "" ? n : Number(n).toLocaleString();
      }
      efectivo.addEventListener("keyup", (e) => {
        const element = e.target;
        const value = element.value;
        element.value = formatNumber(value);
      });

      const transaccion =
        document.querySelector("#transaccion_abono_cartera");

      function formatNumber(n) {
        n = String(n).replace(/\D/g, "");
        return n === "" ? n : Number(n).toLocaleString();
      }
      transaccion.addEventListener("keyup", (e) => {
        const element = e.target;
        const value = element.value;
        element.value = formatNumber(value);
      });
    </script>
    <script>
      $(document).ready(function() {
        //let url = document.getElementById("url").value;
        var url = document.getElementById("url").value;

        /*  $('#aperturas').DataTable({
             serverSide: true,
             processing: true,
             "language": {
                 "decimal": "",
                 "emptyTable": "No hay datos",
                 "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                 "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                 "infoFiltered": "(Filtro de _MAX_ total registros)",
                 "infoPostFix": "",
                 "thousands": ",",
                 "lengthMenu": "Mostrar _MENU_ registros",
                 "loadingRecords": "Cargando...",
                 "processing": "Procesando...",
                 "search": "Buscar",
                 "zeroRecords": "No se encontraron coincidencias",
                 "paginate": {
                     "first": "Primero",
                     "last": "Ultimo",
                     "next": "Próximo",
                     "previous": "Anterior"
                 },
                 "aria": {
                     "sortAscending": ": Activar orden de columna ascendente",
                     "sortDescending": ": Activar orden de columna desendente"
                 }

             },
             ajax: {

                 url: url + 'consultas_y_reportes/aperturas',
                 type: 'post',
             },

         }); */
        $.ajax({
          url: url + "/" + "consultas_y_reportes/aperturas",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {

              $('#aperturas').html(resultado.aperturas)

            }
          },
        });
      });
    </script>

    <script>
      function movimiento(id) {
        var url = document.getElementById("url").value;

        $.ajax({
          data: {
            id
          },
          url: url + "/" + "consultas_y_reportes/detalle_movimiento_de_caja",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {

              $('#valor_apertura').html(resultado.valor_apertura)
              $('#valor_efectivo').html(resultado.ingresos_efectivo)
              $('#valor_transferencia').html(resultado.ingresos_transaccion)
              $('#total_ingresos').html(resultado.total_ingresos)
              $('#retiros').html(resultado.retiros)
              $('#devoluciones').html(resultado.devoluciones)
              $('#ret_dev').html(resultado.retirosmasdevoluciones)
              $('#saldo_caja').html(resultado.saldo_caja)
              $('#cierre_efectivo').html(resultado.efectivo_cierre)
              $('#cierre_bancos').html(resultado.transaccion_cierre)
              $('#total_cierre').html(resultado.total_cierre)
              $('#diferencia').html(resultado.diferencia)
              $('#fecha_apertura').html(resultado.fecha_apertura)
              $('#fecha_cierre').html(resultado.fecha_cierre)
              $('#id_apertura').val(resultado.id_apertura)
              $('#id_aperturas').val(resultado.id_apertura)

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
                title: 'Movimientos de caja encontrados'
              })

            }
          },
        });

      }
    </script>


    <script>
      function fiscal_electronico() {

        var url = document.getElementById("url").value;
        var id_apertura = document.getElementById("id_aperturas").value;
        $.ajax({
          data: {
            id_apertura
          },
          url: url + "/" + "consultas_y_reportes/informe_fiscal_electronico",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {

              $("#datos_informe").html(resultado.datos);
              $("#modal_informe_fiscal").modal("show");
            }
            if (resultado.resultado == 0) {

              sweet_alert_centrado('warning','No hay datos para apertura de caja ')
            }
          },
        });

      }
    </script>
    <script>
      function fiscal() {

        var url = document.getElementById("url").value;
        var id_apertura = document.getElementById("id_aperturas").value;
        $.ajax({
          data: {
            id_apertura
          },
          url: url + "/" + "consultas_y_reportes/informe_fiscal_desde_caja",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {

              $("#datos_informe").html(resultado.datos);
              $("#modal_informe_fiscal").modal("show");
            }
          },
        });

      }
    </script>

    <script>
      function reporte_ventas() {

        var url = document.getElementById("url").value;
        var id_apertura = document.getElementById("id_apertura").value;
        var tipo_reporte = document.getElementById("tipo_reporte").value;


        $.ajax({
          data: {
            id_apertura,
            tipo_reporte
          },
          url: url + "/" + "consultas_y_reportes/reporte_de_ventas",
          type: "POST",
          success: function(resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {

              $("#datos_informe").html(resultado.datos);
              $("#titulo_reporte").html('INFORME DE VENTAS');
              $("#modal_informe_fiscal").modal("show");
              $("#modal_informe_fiscal").modal("show");
            }
          },
        });

      }
    </script>


</body>

</html>