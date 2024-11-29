<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>

<?= $this->section('title') ?>
Ventas
<?= $this->endSection('title') ?>
<style>
    .custom-select {
        position: relative;
        width: 200px;
        font-family: Arial, sans-serif;
    }

    .custom-select select {
        display: none;
        /* Oculta el elemento <select> */
    }

    .custom-select .select-selected {
        background-color: #eee;
        padding: 8px 20px 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        display: inline-block;
    }

    .custom-select .select-selected::after {
        content: '';
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-top: 6px solid #000;
    }

    .custom-select .select-items {
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 4px;
        overflow-y: auto;
        max-height: 200px;
        z-index: 1;
        display: none;
    }

    .custom-select .select-items div {
        padding: 8px 16px;
        cursor: pointer;
    }

    .custom-select .select-items div:hover {
        background-color: #f3f3f3;
    }

    .custom-select.open .select-items {
        display: block;
    }

    .custom-select.open .select-selected::after {
        border-top: none;
        border-bottom: 6px solid #000;
    }

    /* Agrega el ícono SVG */
    .custom-select .select-selected svg {
        vertical-align: middle;
        margin-right: 8px;
    }
</style>
<?= $this->section('content') ?>

<div class="card container">
    <div class="card-body">
        <input type="hidden" value="<?php echo $user_session->id_usuario ?>" id="id_usuario">
        <div class="row">

            <div class="col">
                <label for="" class="form-label">Criterio de consulta </label>
                <input type="hidden" id="opcion_seleccionada" value=2>
                <input type="hidden" id="url" value="<?php echo base_url() ?>">
                <select name="criterio_consulta" id="criterio_consulta" class="form-select" onchange="seleccionCambiada(this.value)">
                    <!--  <option value="1">NÚMERO</option> -->
                    <option value="2" selected>TIPO DE DOCUMENTO</option> <!-- Corrección: Añadido 'selected' -->
                    <option value="3">CLIENTE</option>
                </select>
                <span id="error_de_seleccion" class="text-danger"></span>
            </div>

            <div class="col" style="display:block" id="tipo_de_documento">
                <label for="" class="form-label">Tipo de documento </label>
                <select name="tipo_documento" id="tipo_documento" class="form-select" onchange="limpiar_error_documento(this.value)">

                    <?php foreach ($estado as $detalle) : ?>
                        <option value="<?php echo $detalle['idestado'] ?>" <?php if ($detalle['idestado'] == 5) : ?>selected <?php endif; ?>><?php echo $detalle['descripcionestado'] ?> </option>
                    <?php endforeach ?>
                </select>

                <span id="error_tipo_documento" class="text-danger"></span>


            </div>


            <div class="col" id="numero" style="display:none">
                <label for="" class="form-label">Numero </label>

                <input type="text" class="form-control" id="numero_factura" name="numero_factura">
                <span id="error_numero" class="text-danger"></span>
            </div>

            <div class="col" style="display:none" id="cliente">
                <label for="" class="form-label">Cliente </label>
                <input type="hidden" id="nit_cliente" name="nit_cliente">
                <div class="input-group input-group-flat">
                    <input type="text" class="form-control" id="buscar_cliente" name="buscar_cliente">
                    <span class="input-group-text">
                        <a href="#" class="link-secondary" title="Limpiar campo" data-bs-toggle="tooltip" onclick="limpiar_busqueda()"><!-- Download SVG icon from http://tabler-icons.io/i/x -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M18 6l-12 12" />
                                <path d="M6 6l12 12" />
                            </svg>
                        </a>
                    </span>
                </div>
                <span id="error_cliente" class="text-danger"></span>
            </div>


            <div class="col" style="display: none;">

                <label for="" class="form-label">Período</label>
                <select name="" id="periodo_tiempo" name="periodo" class="form-select" onchange="criterio_fecha(this.value)">

                    <option value="1">Desde el inicio </option>
                    <option value="2">Fecha </option>
                    <option value="3">Período </option>
                </select>

            </div>

            <div class="col" style="display:block" id="periodo">
                <div class="row">

                    <div class="col">
                        <label for="" class="form-label">Desde </label>
                        <input type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" id="fecha_inicial">
                    </div>
                    <div class="col">
                        <label for="" class="form-label">Hasta </label>
                        <input type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" id="fecha_final">
                    </div>

                </div>
            </div>
            <div class="col" style="display:none" id="fecha">
                <div class="row">


                    <label for="" class="form-label">Fecha </label>
                    <input type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" id="fecha_inicial">



                </div>
            </div>
            <div class="col text-end" id="periodo">
                <label for="" class="form-label text-light">Periodo </label>
                <button type="button" class="btn btn-primary w-100" onclick="buscar()">Buscar</button>
            </div>
        </div><br>
        <div class="row">
            <div class="col">
         
                <a class="card card-link cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Documentos acetados por la DIAN " onclick="estado_dian(2)">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="font-weight-medium text-primary h5 text-center">DIAN ACEPTADO </div>
                                <div class="text-muted text-center" id="dian_aceptado"></div>
                            </div>
                            <div class="col-auto ">
                                <span class="avatar rounded text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-check">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                        <path d="M9 15l2 2l4 -4" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3">
                

                <a class="card card-link cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Documentos no enviados a la DIAN " onclick="estado_dian(1)">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="font-weight-medium text-primary h5 text-center">DIAN NO ENVIADO </div>
                                <div class="text-muted text-center" id="dian_no_enviado"></div>
                            </div>
                            <div class="col-auto ">
                                <span class="avatar rounded text-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-alert">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                        <path d="M12 17l.01 0" />
                                        <path d="M12 11l0 3" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>



            </div>


            <div class="col">
      


                <a class="card card-link cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Documentos rechazados por la  DIAN " onclick="estado_dian(3)">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="font-weight-medium text-primary h5 text-center">DIAN RECHAZADO </div>
                                <div class="text-muted text-center" id="dian_rechazado"></div>
                            </div>
                            <div class="col-auto ">
                                <span class="avatar rounded text-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-off">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 3l18 18" />
                                        <path d="M7 3h7l5 5v7m0 4a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-14" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

            </div>
            <div class="col">
               
                <a class="card card-link cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Documentos con error " onclick="estado_dian(4)">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="font-weight-medium text-primary h5 text-center">DIAN ERROR </div>
                                <div class="text-muted text-center" id="dian_error"></div>
                            </div>
                            <div class="col-auto ">
                                <span class="avatar rounded text-azure">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-off">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 3l18 18" />
                                        <path d="M7 3h7l5 5v7m0 4a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-14" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <br>

        <div id="data_table_ventas">


            <table class="table" id="consulta_ventas">
                <thead class="table-dark">
                    <tr>

                        <td>Fecha </th>
                        <td>Nit cliente </th>
                        <td>Cliente</th>
                        <td>Documento</th>
                        <td>Valor </th>
                        <td>Saldo </th>
                        <td>Tipo documento</th>
                        <td>Acción </th>
                    </tr>
                </thead>
                <tbody id="estados_dian">


                </tbody>
            </table>

        </div>

        <br>

        <div id="resultado_consultado"></div>
        <div class="row justify-content-end">


            <div class="col-md-3 col-lg-3">

            </div>
            <div class="col-md-3 col-lg-3" style="display: none;" id="pagos_recibidos">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="chart-sparkline chart-sparkline-square" id="sparkline-orders"></div>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium text-center">
                                    Pagos recibidos
                                </div>
                                <div class="text-muted text-center">
                                    <span id="abonos"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-lg-3" style="display: none;" id="saldo_pendiente_pago">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="chart-sparkline chart-sparkline-square" id="sparkline-orders"></div>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium text-center">
                                    Saldo pendiente de cobro
                                </div>
                                <div class="text-muted text-center">
                                    <span id="saldo"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($user_session->tipo == 0) { ?>
                <div class="col-md-3 col-lg-3 w-10">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="chart-sparkline chart-sparkline-square" id="sparkline-orders"></div>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium text-center">
                                        <span id="total_ventas"></span>
                                    </div>
                                    <div class="text-muted text-center">
                                        <span id="total_documentos"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php  } ?>
        </div>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_eliminacion_f_e" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Eliminación de factura electrónica </h5>
                <button type="button" class="btn-close" onclick="cerrarModal()"></button>
            </div>
            <div class="modal-body">

                <input type="hidden" id="id_factura">
                <label for="formGroupExampleInput" class="form-label">Contraseña de eliminación </label>
                <div class="input-group input-group-flat">
                    <input type="password" class="form-control" id="password" name="password">
                    <span class="input-group-text">
                        <a href="#" class="link-secondary" title="Limpiar" data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler-icons.io/i/x -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M18 6l-12 12" />
                                <path d="M6 6l12 12" />
                            </svg>
                        </a>
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" onclick="eliminacion_fac_ele()">Aceptar</button>
                <button type="button" class="btn btn-outline-danger" onclick="cerrar_modal()"  data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_actualizacion_fechas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tittle_up">Actualización de fechas </h5>
                <button type="button" class="btn-close" onclick="cerrarModalTrasmision()"></button>
            </div>
            <div class="modal-body">

                <input type="hidden" id="id_doc">

                <p id="titulo_actualizacion_fechas">!La fecha de generacion es inferior a la fecha de validacion¡</p>
                <p id="texto_fact"></p>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" id="btnSenDian" data-bs-dismiss="modal">Aceptar </button>
                <button type="button" class="btn btn-outline-danger" onclick="cerrarModalTrasmision()">Cancelar </button>
            </div>
        </div>
    </div>
</div>







<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/f_e.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/imprimir_electronica.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/impreasion_factura_electronica.js"></script>


<script>
    function cerrar_modal(){

        document.getElementById("password").value = '';
      


    }
</script>



<script>
    function actualizar_fechas(id_doc) {

        var url = document.getElementById("url").value;

        $.ajax({
            data: {
                id_doc,
            },
            url: url + "/" + "reportes/actualizar_fechas",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);

                table = $('#consulta_ventas').DataTable();
                if (table) {
                    table.draw();
                }
                if (resultado.resultado == 1) { //Se retrasmite por que ya tiene trasnsaccion ID O UUID

                    sendInvoiceDian(resultado.id_doc);
                }
                if (resultado.resultado == 2) { //Se trasmite  ya que no tiene trasnsaccion ID O UUID

                    enviarDoc(resultado.id_doc);
                }

            },
        });

    }
</script>


<script>
    function cerrarModal() {

        $('#modal_eliminacion_f_e').modal('hide');
        $('#password').val('');


    }
</script>
<script>
    function cerrarModalTrasmision() {

        $('#modal_actualizacion_fechas').modal('hide');
        $('#titulo_actualizacion_fechas').html('');
        $('#texto_fact').html('');


    }
</script>

<script>
    function estado_dian(id_estado) {

        var url = document.getElementById("url").value;

        /*   $.ajax({
              data: {
                  id_estado,
              },
              url: url + "/" + "reportes/estado_dian",
              type: "POST",
              success: function(resultado) {
                  var resultado = JSON.parse(resultado);
                  if (resultado.resultado == 1) {
                     

                      
                  }
                  
              },
          }); */

        $.ajax({
            data: {
                id_estado: id_estado, // Asegúrate de pasar el ID de estado correctamente
            },
            url: url + "/" + "reportes/estado_dian",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {
                    // Limpiar el contenido previo de la tabla
                    $('#estado_dian').empty();

                    // Iterar sobre los datos obtenidos y agregarlos a la tabla
                    $.each(resultado.datos, function(index, item) {
                        $('#estado_dian').append(
                            '<tr>' +
                            '<td>' + item.fecha + '</td>' +
                            '<td>' + item.nit_cliente + '</td>' +
                            '<td>' + item.numero + '</td>' +
                            '<td>' + item.neto + '</td>' +
                            '<td>' + item.nombrescliente + '</td>' +
                            '</tr>'
                        );
                    });
                } else {
                    // Manejar el caso en que no se encontraron resultados
                    $('#estado_dian').html('<tr><td colspan="5">No se encontraron resultados.</td></tr>');
                }
            },
            error: function() {
                alert('Error al obtener los datos del servidor.');
            }
        });
    }
</script>

<script>
    function criterio_fecha(criterio) {

        console.log(criterio)

    }
</script>

<script>
    function limpiar_busqueda() {
        $('#nit_cliente').val('')
        $('#buscar_cliente').val('')
        $('#buscar_cliente').focus()
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
        var id_usuario = document.getElementById("id_usuario").value;
        var id_factura = document.getElementById("id_factura_credito").value;
        var efecti = document.getElementById("valor_abono_factura_credito").value;
        var efectivo = efecti.replace(/[.]/g, "");
        var transa = document.getElementById("valor_abono_factura_credito_transaccion").value;
        var transaccion = transa.replace(/[.]/g, "");

        var saldo_pendiente = document.getElementById("abono_factura_credito").value;
        var saldo = parseInt(saldo_pendiente.replace(/[.]/g, ""));

        var abono_cliente = document.getElementById("abono_factura_credito").value;
        var abono = parseInt(abono_cliente.replace(/[.]/g, ""));



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
                        $('#abono_factura_credito').val('0');

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
    function limpiar_error_documento(documento) {
        $('#error_tipo_documento').html('')

        /*

         if (documento != 8) {
             var div = document.getElementById("estado_dian");
             div.style.display = "none";
         }*/
    }
</script>



<script>
    function seleccionCambiada(opcion) {
        $('#error_de_seleccion').html('')
        if (opcion == 1) {
            $("#numero").show();
            $("#cliente").hide();
            $("#tipo_de_documento").hide();
            $("#periodo").hide();
        }

        if (opcion == 2) {
            $("#numero").hide();
            $("#cliente").hide();
            $("#tipo_de_documento").show();
            $("#periodo").show();
        }
        if (opcion == 3) {
            $("#numero").hide();
            $("#cliente").show();
            $("#tipo_de_documento").show();
            $("#periodo").show();
        }

        $('#opcion_seleccionada').val(opcion);

    }
</script>


<script>
    function eliminar_f_e(id) {

        $('#password').val('');
        myModal = new bootstrap.Modal(
            document.getElementById("modal_eliminacion_f_e"), {}
        );
        myModal.show();

        $('#id_factura').val(id)

        $("#modal_eliminacion_f_e").on('shown.bs.modal', function() {
            $("#password").focus();
        });

        /*   var url = document.getElementById("url").value;

          $.ajax({
              data: {
                  id
              },
              url: url + "/" + "eventos/eliminar_f_e",
              type: "POST",
              success: function(resultado) {
                  var resultado = JSON.parse(resultado);


                  if (resultado.resultado == 1) {



                      mytable = $('#consulta_ventas').DataTable();
                      mytable.draw();

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
                          title: 'Registro borrado  '
                      })



                  }
              },
          }); */

    }
</script>

<script>
    function eliminacion_fac_ele() {
        var url = document.getElementById("url").value;
        var password = document.getElementById("password").value;

        $.ajax({
            data: {
                password
            },
            url: url + "/" + "eventos/validar_pass",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);


                if (resultado.resultado == 1) {

                    /*   $('#modal_eliminacion_f_e').modal('hide');
                      $('#password').val('');


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
                            title: 'Registro borrado  '
                        }) */

                    var url = document.getElementById("url").value;
                    var id = document.getElementById("id_factura").value;
                    $.ajax({
                        data: {
                            id
                        },
                        url: url + "/" + "eventos/eliminar_f_e",
                        type: "POST",
                        success: function(resultado) {
                            var resultado = JSON.parse(resultado);


                            if (resultado.resultado == 1) {
                                $('#id_factura').val('')
                                $('#password').val('')
                                $('#modal_eliminacion_f_e').modal('hide');
                                mytable = $('#consulta_ventas').DataTable();
                                mytable.draw();

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
                                    title: 'Registro borrado  '
                                })

                            }
                        },
                    });
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
                        icon: 'warning',
                        title: 'Contraseña errada '
                    })
                }
            },
        });
    }
</script>


<?= $this->endSection('content') ?>