<?php $user_session = session(); ?>
<?= $this->extend('template/consultas_reportes') ?>
<?= $this->section('title') ?>
REPORTE DE VENTAS DIARIAS
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('terceros/listado') ?>">Consultas y reportes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Reporte de ventas </li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">CONSULTA DE VENTAS </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>

</div>
<br>
<input type="hidden" value="<?php echo $user_session->id_usuario; ?>" id="usuario_reporte">
<div class="card container">
    <div class="card-body">

        <div class="row">
            <input type="hidden" id="data_table" value=0>
            <input type="hidden" id="tipo_consulta_venta" class="form-control">
            <div class="col-3">
                <select class="form-select" aria-label="Default select example" onchange="tipo_consulta()" id="criterio_consulta">
                    <option selected>Criterio de consulta de ventas </option>
                    <option value="1">Numero de documento</option>
                    <option value="2">Tipo de documento</option>
                    <option value="3">Cliente</option>
                </select>
            </div>

            <div class="col" style="display: none" id="numero_de_documento">
                <input type="text" class="form-control" placeholder="Escriba el numero del dumento" id="consulta_documento">
                <span id="error_tipo_documento" style="color:red;"></span>
            </div>
            <div class="col-2" style="display: none" id="tipo_de_documento">
                <select class="form-select select2" name="tipo_documento" id="tipo_documento">
                    <?php #foreach ($estado as $detalle) { 
                    ?>
                    <option value="<?php #echo $detalle['idestado'] 
                                    ?>"><?php #echo $detalle['descripcionestado'] 
                                        ?> </option>

                    <option value="1">Contado</option>
                    <option value="2">Crédito</option>
                    <option value="6">Remision crédito</option>
                    <option value="7">Remision contado</option>
                    <option value="5">Todos los criterios</option>
                    <?php //} 
                    ?>
                </select>
                <span id="error_tipo_documento" style="color:red;"></span>
            </div>
            <div class="col-2" style="display: none" id="cliente">
                <input type="hidden" placeholder="Buscar cliente por nombre o identificacion" class="form-control" id="id_cliente_reporte">
                <input type="text" placeholder="Buscar cliente por nombre o identificacion" class="form-control" id="cliente_reporte" onkeypress="limpiarErrorCliente()">
                <span id="error_cliente_cartera" style="color:red;"></span>
            </div>

            <div style="display: none" id="entre_fechas" class="col-4 row">
                <table>
                    <tr>
                        <td>Desde</td>
                        <td colspan="3"> <input type="text" class="form-control" id="fecha_inicial_reporte" value="<?php echo date('Y-m-d'); ?>">
                        </td>
                        <td>Hasta</td>
                        <td> <input type="text" class="form-control" id="fecha_final_reporte" value="<?php echo date('Y-m-d'); ?>">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-1" style="display: none" id="boton_consulta">
                <button type="button" class="btn btn-primary btn-icon" id="search">
                    <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="10" cy="10" r="7" />
                        <line x1="21" y1="21" x2="15" y2="15" />
                    </svg>
                </button>
            </div>
        </div>
        <hr>


        <div class="col-12" id="consultar_por_documento">

        </div>


        <input type="hidden" value="<?php echo base_url() ?>" id="url">
        <input type="hidden" value="si" id="dibuja_DataTable">

        <div class="col-12" id="data_table_documeto">
            <table id="consulta_ventas" class="table">
                <thead class="table-dark">
                    <td>Nit</td>
                    <td>Cliente</td>
                    <td>Estado</td>
                    <td>Numero</td>
                    <td>Fecha</td>
                    <td>Fecha limite</td>
                    <td>Valor factura</td>
                    <td>Saldo</td>
                    <td>Acción</td>
                </thead>

                <tbody id="t_body_consulta_ventas">
                    <?php $datos = model('facturaVentaModel')->por_defecto(date('Y-m-d'));
                    $valor_venta = model('facturaVentaModel')->valor_venta(date('Y-m-d'));
                    $saldo_venta = model('facturaVentaModel')->valor_saldo(date('Y-m-d')); ?>

                    <?php if (!empty($datos)) { ?>
                        <?php foreach ($datos as $detalle) { ?>
                            <tr>
                                <td>
                                    <?php echo $detalle['nitcliente'] ?>
                                </td>
                                <td>
                                    <?php echo $detalle['nombrescliente'] ?>
                                </td>
                                <td>
                                    <?php echo $detalle['descripcionestado'] ?>
                                </td>
                                <td>
                                    <?php echo $detalle['numerofactura_venta'] ?>
                                </td>
                                <td>
                                    <?php echo $detalle['fecha_factura_venta'] ?>
                                </td>
                                <td>
                                    <?php echo $detalle['fechalimitefactura_venta'] ?>
                                </td>
                                <td>
                                    <?php echo "$" . number_format($detalle['valor_factura'], 0, ",", ".") ?>
                                </td>
                                <td>
                                    <?php echo "$" . number_format($detalle['saldo'], 0, ",", ".") ?>
                                </td>
                                <td><a class="btn btn-primary btn-icon " title="Imprimir copia " onclick="imprimir_duplicado_factura(<?php echo $detalle['id'] ?>)">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                                            <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                                            <rect x="7" y="13" width="10" height="8" rx="2" />
                                        </svg></a>
                                    <a class="btn bg-muted-lt btn-icon " title="Ver detalle" onclick="detalle_de_factura(<?php echo $detalle['id'] ?>)"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <circle cx="12" cy="12" r="2" />
                                            <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                        </svg></a>


                                    <a class="btn bg-green-lt btn-icon " title="Realizar pago general " onclick="abonos_a_cartera(<?php echo $detalle['nitcliente'] ?>,<?php echo $detalle['idestado'] ?>)"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                            <path d="M12 3v3m0 12v3" />
                                        </svg></a>
                                    <a onclick="abono_credito(<?php echo $detalle['id'] ?>)" class="btn btn-primary  btn-icon"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                            <path d="M12 3v3m0 12v3" />
                                        </svg> </a>
                                </td>
                            </tr>
                        <?php } ?>

                </tbody>
            </table>
        </div>


        <div class="container">
            <div class="row align-items-start">
                <div class="col-4">
                    <p class="text-end h3 text-primary" id="saldo_total">VALOR FACTURAS: <?php echo "$" . number_format($valor_venta[0]['valor_venta'], 0, ",", ".") ?></p>
                </div>
                <div class="col-4">
                    <p class="text-end h3 text-primary" id="pagos_factura"> PAGOS: <?php echo "$" . number_format($valor_venta[0]['valor_venta'] - $saldo_venta[0]['saldo_venta'], 0, ",", ".") ?> </p>
                </div>
                <div class="col-4">
                    <p class="text-end h3 text-primary" id="saldo_cliente"> CARTERA: <?php echo "$" . number_format($saldo_venta[0]['saldo_venta'], 0, ",", ".") ?></p>
                </div>
            </div>


        </div>
    <?php } ?>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="abono_saldo_credito_cliente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <div class="hr-text text-primary h2">Abonar a saldo </div>

                <form class="row g-1">
                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label">Cliente</label>
                        <input type="email" class="form-control" id="inputEmail4" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="inputPassword4" class="form-label">Nit o CC</label>
                        <input type="text" class="form-control" id="inputPassword4" readonly>
                    </div>
                    <div class="col-6">
                        <label for="inputAddress" class="form-label">Saldo</label>
                        <input type="text" class="form-control" id="inputAddress" readonly>
                    </div>
                    <div class="col-6">
                        <label for="inputAddress2" class="form-label">Abono</label>
                        <input type="text" class="form-control" id="inputAddress2">
                    </div>
                    <div class="hr-text text-primary h2">Formas de pago</div>
                    <div class="col-md-12">
                        <label for="inputCity" class="form-label">Efectivo</label>
                        <input type="text" class="form-control" id="inputCity">
                    </div>
                    <div class="col-md-12">
                        <label for="inputState" class="form-label">Transaccion</label>
                        <input type="text" class="form-control" id="inputAddress2">
                    </div>
                    <div class="col-md-12">
                        <label for="inputState" class="form-label">Cambio</label>
                        <input type="text" class="form-control" id="inputAddress2">
                    </div>
                </form>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success">Abonar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal abonos a cartera  -->
<div class="modal fade" id="abono_saldo_cartera" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">

                <div class="hr-text text-primary h3">
                    <p class="text-primary h3"> ABONAR A CARTERA CLIENTE </p>
                </div>
                <form class="row g-3" action="<?= base_url('consultas_y_reportes/pagar_cartera_cliente') ?>" method="POST" id="pagar_cartera_cliente">
                    <div class="col-md-6">
                        <input type="hidden" id="fecha_inicial_cartera">
                        <input type="hidden" id="fecha_final_cartera">

                        <label for="inputEmail4" class="form-label">Cliente</label>
                        <input type="text" class="form-control" id="nombre_cliente_cartera" name="nombre_cliente_cartera" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="inputPassword4" class="form-label">Nit o CC</label>
                        <input type="text" class="form-control" id="nit_cliente_cartera" name="nit_cliente_cartera" readonly>
                    </div>
                    <div class="col-6">
                        <label for="inputAddress" class="form-label">Saldo</label>
                        <input type="text" class="form-control" name="saldo_cliente_cartera" id="saldo_cliente_cartera" readonly>
                    </div>
                    <div class="col-6">
                        <label for="inputAddress2" class="form-label">Abono</label>
                        <input type="text" class="form-control" id="abono_general_cartera" onkeyup="saltar(event,'efectivo_abono_cartera'),validar_abono()" name="abono_general_cartera" onkeydown="checkPressedKey(event)">
                        <span style="color:red" id="error_abono_cartera"></span>
                    </div>
                    <div class="hr-text text-primary h2">
                        <p class="text-primary h3"> FORMA PAGO </p>
                    </div>
                    <div class="col-md-4">
                        <label for="inputCity" class="form-label">Efectivo</label>
                        <input type="text" class="form-control" value="0" name="efectivo_abono_cartera" id="efectivo_abono_cartera" onkeyup="saltar(event,'transaccion_abono_cartera'),cambio_cartera()">
                    </div>
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">Transaccion</label>
                        <input type="text" class="form-control" value="0" id="transaccion_abono_cartera" name="transaccion_abono_cartera" onkeyup="saltar(event,'pagar_abono'),cambio_cartera()">
                    </div>
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">Cambio</label>
                        <input type="text" class="form-control" id="cambio_abono_cartera" value=0>
                    </div>
                    <div class="modal-footer container">
                        <button type="button" class="btn btn-danger" onclick="cerrar_formulario_cartera()">Cancelar</button>
                        <button type="button" class="btn btn-success" id="pagar_abono" onclick="pagar_cartera()">Pagar</button>
                    </div>
                </form>


            </div>

        </div>
    </div>
</div>


<script>
    function tipo_consulta() {
        var tipo_de_documento = document.getElementById("criterio_consulta").value;
        var cliente = document.getElementById("cliente").value;
        var periodo = document.getElementById("entre_fechas").value;
        var boton_consulta = document.getElementById("boton_consulta").value;
        var numero_de_documento = document.getElementById("numero_de_documento").value;

        if (tipo_de_documento == 1) {
            document.getElementById("numero_de_documento").style.display = "block";
            document.getElementById("boton_consulta").style.display = "block";
            document.getElementById("tipo_de_documento").style.display = "none";
            document.getElementById("entre_fechas").style.display = "none";
            document.getElementById("cliente").style.display = "none";
            $("#tipo_consulta_venta").val(1);
        }
        if (tipo_de_documento == 2) {
            //tipo_de_documento.style.display = "block";
            document.getElementById("tipo_de_documento").style.display = "block";
            document.getElementById("entre_fechas").style.display = "block";
            document.getElementById("boton_consulta").style.display = "block";
            document.getElementById("numero_de_documento").style.display = "none";
            document.getElementById("cliente").style.display = "none";
            $("#tipo_consulta_venta").val(2);
        }

        if (tipo_de_documento == 3) {
            //tipo_de_documento.style.display = "block";
            document.getElementById("tipo_de_documento").style.display = "block";
            document.getElementById("entre_fechas").style.display = "block";
            document.getElementById("boton_consulta").style.display = "block";
            document.getElementById("cliente").style.display = "block";
            document.getElementById("numero_de_documento").style.display = "none";
            $("#tipo_consulta_venta").val(3);
        }


    }
</script>


<script>
    function imprimir_duplicado_factura(id_factura) {
        var url = document.getElementById("url").value;
        id_de_factura = id_factura
        $.ajax({
            type: "POST",
            url: url + "/" + "consultas_y_reportes/imprimir_duplicado_factura",
            data: {
                id_de_factura
            },
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
                        title: 'Duplicado de factura'
                    })
                }
            },
        });


    }
</script>

<script>
    function abonos_a_cartera(nit_cliente, documento) {
        var url = document.getElementById("url").value;

        //var documento = document.getElementById("tipo_documento").value;

        $.ajax({
            data: {
                nit_cliente,
                documento
            },
            url: url + "/" + "consultas_y_reportes/cartera_cliente",
            type: "post",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 0) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener("mouseenter", Swal.stopTimer);
                            toast.addEventListener("mouseleave", Swal.resumeTimer);
                        },
                    });

                    Toast.fire({
                        icon: "info",
                        title: "Cliente no tiene cartera activa ",
                    });
                }
                if (resultado.resultado == 1) {
                    //document.getElementById("numero_de_documento").style.display = "block";
                    $('#nit_cliente_cartera').val(resultado.nit)
                    $('#nombre_cliente_cartera').val(resultado.cliente)
                    $('#saldo_cliente_cartera').val(resultado.saldo)
                    $('#fecha_inicial_cartera').val(resultado.fecha_inicial)
                    $('#fecha_final_cartera').val(resultado.fecha_final)

                    myModal = new bootstrap.Modal(
                        document.getElementById("abono_saldo_cartera"), {}
                    );
                    myModal.show();
                }
            },
        });

    }
</script>

<script>
    function pagar_cartera() {
        var url = document.getElementById("url").value;
        var nit_cliente = document.getElementById("nit_cliente_cartera").value;
        var efectivo = document.getElementById("efectivo_abono_cartera").value;
        var transaccion = document.getElementById("transaccion_abono_cartera").value;
        var fecha_inicial = document.getElementById("fecha_inicial_cartera").value;
        var fecha_final = document.getElementById("fecha_final_cartera").value;
        var abono = document.getElementById("abono_general_cartera").value;
        var id_usuario = document.getElementById("id_usuario").value;
        var dibuja_DataTable = document.getElementById("dibuja_DataTable").value;

        $.ajax({
            data: {
                nit_cliente,
                efectivo,
                transaccion,
                fecha_inicial,
                fecha_final,
                abono,
                id_usuario,
                dibuja_DataTable
            },
            url: url + "/" + "consultas_y_reportes/pagar_cartera_cliente",
            type: "post",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 0) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener("mouseenter", Swal.stopTimer);
                            toast.addEventListener("mouseleave", Swal.resumeTimer);
                        },
                    });

                    Toast.fire({
                        icon: "info",
                        title: "Cliente no tiene cartera activa ",
                    });
                }
                if (resultado.resultado == 1) {
                    document.getElementById("pagar_cartera_cliente").reset();
                    mytable = $('#consulta_ventas').DataTable();
                    mytable.draw();

                    $("#abono_saldo_cartera").modal("hide");


                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    })
                    swalWithBootstrapButtons.fire({
                        title: 'Imprimir comprobante abono cartera',
                        showCancelButton: true,
                        confirmButtonText: 'Si',
                        cancelButtonText: 'No',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {

                            let id_ingreso = resultado.id_ingreso

                            $.ajax({
                                data: {
                                    id_ingreso
                                },
                                url: url + "/" + "consultas_y_reportes/imprimir_comprobante_ingreso",
                                type: "post",
                                success: function(resultado) {
                                    var resultado = JSON.parse(resultado);
                                    if (resultado.resultado == 1) {
                                        const Toast = Swal.mixin({
                                            toast: true,
                                            position: "top-end",
                                            showConfirmButton: false,
                                            timer: 3000,
                                            timerProgressBar: true,
                                            didOpen: (toast) => {
                                                toast.addEventListener("mouseenter", Swal.stopTimer);
                                                toast.addEventListener("mouseleave", Swal.resumeTimer);
                                            },
                                        });

                                        Toast.fire({
                                            icon: "success",
                                            title: "Impresion de comprobante correcto ",
                                        });
                                    }

                                },
                            });

                        } else if (
                            /* Read more about handling dismissals below */
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            swalWithBootstrapButtons.fire(
                                'Cancelled',
                                'Your imaginary file is safe :)',
                                'error'
                            )
                        }
                    })


                }
            },
        });
    }
</script>

<script>
    function validar_abono() {
        let saldo_pendiente_cartera = document.getElementById("saldo_cliente_cartera").value;

        let saldo = parseInt(saldo_pendiente_cartera.replace(/[.]/g, ""));

        let abono_cliente_cartera = document.getElementById("abono_general_cartera").value;

        let abono_cartera = parseInt(abono_cliente_cartera.replace(/[.]/g, ""));


        if (abono_cartera > saldo) {
            $('#error_abono_cartera').html('El abono supera el saldo ')
        }
    }
</script>

<script>
    function checkPressedKey(event) {
        // Imprimimos la tecla que se ha pulsado. Funciona con cualquier tecla, incluyendo Control, Shift...
        // Incluso funciona con la tecla de windows :)

        // Si es el retroceso, mostrar alerta
        if (event.key === "Backspace")
            $('#error_abono_cartera').html('')
    }

    function cambio_cartera() {

        let efectivo;
        let transaccion;

        let abono_cliente_cartera = document.getElementById("abono_general_cartera").value;
        let abono = parseInt(abono_cliente_cartera.replace(/[.]/g, ""));

        let abono_efectivo = document.getElementById("efectivo_abono_cartera").value;
        if (abono_efectivo == "") {
            efectivo = 0

        } else if (abono_efectivo != "") {
            efectivo = parseInt(abono_efectivo.replace(/[.]/g, ""));

        }


        let abono_transaccion = document.getElementById("transaccion_abono_cartera").value;
        if (abono_transaccion == "") {
            transaccion = 0
        } else if (abono_transaccion != "") {
            transaccion = parseInt(abono_transaccion.replace(/[.]/g, ""));
        }


        let pago_abono = parseInt(efectivo + transaccion)

        if (pago_abono > abono) {
            let temp_cambio = pago_abono - abono

            cambio = temp_cambio.toLocaleString();
            document.getElementById("cambio_abono_cartera").value = cambio;
        }
        if (pago_abono < abono) {
            let temp_cambio = abono - pago_abono

            cambio = temp_cambio.toLocaleString();
            document.getElementById("cambio_abono_cartera").value = cambio;
        }
        if (pago_abono == abono) {
            let temp_cambio = 0

            cambio = temp_cambio.toLocaleString();
            document.getElementById("cambio_abono_cartera").value = cambio;
        }

    }
</script>

<script>
    function cerrar_formulario_cartera() {
        document.getElementById("pagar_cartera_cliente").reset();
        $("#abono_saldo_cartera").modal("hide");

    }

    function limpiarErrorCliente() {
        document.getElementById('error_cliente_cartera').innerHTML = '';
    }
</script>




<?= $this->endSection('content') ?>