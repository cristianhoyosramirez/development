<?php $user_session = session(); ?>
<?= $this->extend('template/factura_pos') ?>
<?= $this->section('title') ?>
FACTURA
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>
<style type="text/css">
    thead tr td {
        position: sticky;
        top: 0;
        background-color: #ffffff;
    }

    .table-responsive {
        height: 180px;
        overflow: scroll;
    }
</style>
<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
<div class="container">
</div>
<?php if ($apertura == 1) { ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Apertura de caja éxitoso',
            confirmButtonText: "Aceptar",
            confirmButtonColor: "#2AA13D",
        })
    </script>
<?php } ?>

<?php if (!empty($caja_general == 1)) { ?>
    <script>
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
            title: 'Apertura de caja general exitoso'
        })
    </script>
<?php } ?>

<?php if (!empty($caja_general == 2)) { ?>
    <script>
        const Toast_1 = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast_1.addEventListener('mouseenter', Swal.stopTimer)
                toast_1.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast_1.fire({
            icon: 'success',
            title: 'Cierre de caja general exitoso'
        })
    </script>
<?php } ?>






<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Facturación</a></li>
                    <li class="breadcrumb-item"><a href="#">Facturación</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">FACTURACIÓN POS </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>
<div class="card container">
    <div class="card-body">
        <input type="hidden" value="<?= base_url() ?>" id="url" name="url">
        <div class="row">
            <?php if ($tiene_producto == 1) { ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <p class="text-center text-danger fs-1">Usuario tiene registro pendientes</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php } ?>
            <form id="facturacion">
                <!-- MENU REMODELADO -->
                <div class="container row">

                    <div style="width:30%">
                        <div class="row g-3 align-items-center">

                            <div class="col-12">
                                <div class="input-group mb-2">
                                    <input type="hidden" id="id_cliente_factura_pos" name="id_cliente_factura_pos" value="22222222">
                                    <input type="text" class="form-control" id="clientes_factura_pos" name="clientes_factura_pos" value="CLIENTE GENERAL">
                                    <a href="#" class="btn btn-warning btn-icon" title="Agregar cliente" aria-label="Button" data-bs-toggle="modal" data-bs-target="#creacion_cliente_factura_pos">
                                        <!--   Download SVG icon from http://tabler-icons.io/i/plus -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <line x1="12" y1="5" x2="12" y2="19" />
                                            <line x1="5" y1="12" x2="19" y2="12" />
                                        </svg>
                                        <!--  <img src="<?php #echo base_url(); 
                                                        ?>/Assets/img/agregar-usuario.png" width="10" height="20" alt="Macondo" class="navbar-brand-image">-->
                                    </a>
                                </div>
                                <span style="color:#FF0000;" id="error_cliente"></span>
                            </div>
                        </div>
                    </div>

                    <div style="width:30%">
                        <div class="row g-3 align-items-center">
                            <div class="col-4">
                                <label for="inputPassword6" class="col-form-label">Documento</label>
                            </div>
                            <div class="col-8">
                                <select class="form-select select2" name="estado_pos" id="estado_pos">
                                    <?php foreach ($estado as $detalle) { ?>
                                        <?php if ($detalle['idestado'] != 5) { ?>
                                            <option value="<?php echo $detalle['idestado'] ?>"><?php echo $detalle['descripcionestado'] ?> </option>
                                        <?php } ?>
                                    <?php } ?>
                                    <!-- <option value="1">Contado</option>
                                <option value="2">Crédito</option>
                                <option value="6">Remisión crédito</option>
                                <option value="7">Remisión contado</option> -->

                                </select>
                                <span style="color:#FF0000;" id="error_cliente"></span>
                            </div>
                        </div>
                    </div>

                    <div style="width:30%">
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="inputPassword6" class="col-form-label">Fecha limite</label>
                            </div>
                            <div class="col-auto">
                                <input type="text" class="form-control" onclick="reset_error()" name="fecha_limite" id="fecha_limite" value="<?php echo date('Y-m-d'); ?>">
                                <span id="error_fecha_limite" style="color:red">
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table table-borderless">
                    <tbody>
                        <tr height="5">
                            <td style="width:30%">

                                <div class="row g-3 align-items-center">
                                    <div class="col-3">
                                        <label>Producto</label>
                                    </div>
                                    <input type="hidden" id="codigointernoproducto" name="codigointernoproducto">
                                    <?php if ($lista_precios == 'f') { ?>
                                        <div style="display:none">
                                            <input type="hidden" value=0 id="select_lista_precios">
                                            <input type="text" id="lista_precios" value=0>
                                        </div>
                                        <div class="col-9">

                                            <input type="text" class="form-control" id="buscar_producto" name="buscar_producto" placeholder="Código o descripción" onkeyup="tecla_enter(event, this.value),saltar_factura_pos(event,'cantidad_factura_pos')" autofocus>
                                        </div>
                                    <?php } ?>
                                    <?php if ($lista_precios == 't') { ?>
                                        <div class="col-9">

                                            <input type="text" class="form-control" id="buscar_producto" name="buscar_producto" placeholder="Código o descripción" onkeyup="llenar_select(event, this.value),saltar_factura_pos(event,'lista_precios')" autofocus>
                                        </div>
                                    <?php } ?>
                                    <span style="color:#FF0000;" id="mensaje_de_error"></span>
                                </div>
                            </td>
                            <?php if ($lista_precios == 't') { ?>
                                <input type="hidden" value=1 id="select_lista_precios">
                                <td style="width:29%" id="vista_lista_precios">

                                    <div class="row g-3 align-items-center">

                                        <div class="col-12">
                                            <select class="form-select" onkeyup="saltar_factura_pos(event,'cantidad_factura_pos')" id="lista_precios">

                                            </select>
                                        </div>
                                    </div>
                                </td>
                            <?php } ?>

                            <?php if ($lista_precios == 'f') { ?>

                                <input type="hidden" value=0 id="select_lista_precios">

                                <td style="width:30%">

                                    <div class="row g-3 align-items-center">
                                        <div class="col-12">
                                            <div class="input-group mb-2">
                                                <span class="input-group-text">
                                                    $
                                                </span>
                                                <input type="hidden" id="precio">
                                                <input type="text" class="form-control" placeholder="Valor unitario" id="valor_venta_producto" autocomplete="off" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            <?php } ?>
                            <td style="width:28%">

                                <div class="row g-3 align-items-center">
                                    <div class="col-4">
                                        <label for="inputPassword6" class="col-form-label">Cantidad</label>
                                    </div>
                                    <div class="col-4">
                                        <input type="text" class="form-control" id="cantidad_factura_pos" value="1" placeholder="Cantidad a agregar" onkeyup="saltar_factura_pos(event,'cargar_producto_al_pedido')" onKeyPress="return soloNumerosEnCantidad(event)">
                                    </div>
                                    <div class="col-4">
                                        <a href="#" class="btn btn-success w-100 btn-icon" title="Agregar producto al pedido" aria-label="Bitbucket" id="cargar_producto_al_pedido" onclick="cargar_producto_al_pedido()">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <line x1="12" y1="5" x2="12" y2="19" />
                                                <line x1="5" y1="12" x2="19" y2="12" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="row g-3 align-items-center">
                                    <div class="col-12">
                                        <input type="hidden" class="form-control" rows="1" id="nota_producto_pos" placeholder="Nota producto"></input>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" name="id_usuario" id="id_usuario_de_facturacion">
            <div class="row">
                <div class="table-responsive" id="productos_de_pedido_pos">
                    <table id="tablaProductos" class="table table-borderless table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <td width: 100%>Código</td>
                                <td width: 100%>Descripción</td>
                                <td width: 100%>Valor unitario</td>
                                <td width: 100%>Cantidad</td>
                                <td width: 100%>Total</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody id="productos_factura_rapida">

                            <?php if (!empty($productos)) { ?>
                                <?php foreach ($productos as $detalle) { ?>
                                    <tr>
                                        <td><?php echo $detalle['codigointernoproducto'] ?></td>
                                        <td><?php echo $detalle['nombreproducto'] . "</br>";

                                            if (!empty($detalle['nota_producto'])) {
                                                echo "NOTAS:" . $detalle['nota_producto'];
                                            }

                                            ?></td>
                                        <td><?php echo "$" . number_format($detalle['valor_unitario'], 0, ",", ".") ?></td>
                                        <td><?php echo $detalle['cantidad_producto'] ?></td>
                                        <td><?php echo "$" . number_format($detalle['valor_total'], 0, ",", ".") ?></td>
                                        <td class="strong text-end">
                                            <div class="row g-2 align-items-center mb-n3">
                                                <div class="col-4 col-sm-4 col-md-2 col-xl-auto mb-3">
                                                    <a href="#" title="Editar" onclick="editar_producto_pos(<?php echo $detalle['id'] ?>)" class="btn btn-warning btn-icon w-100">

                                                        <!-- Download SVG icon from http://tabler-icons.io/i/edit -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />
                                                            <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" />
                                                            <line x1="16" y1="5" x2="19" y2="8" />
                                                        </svg>
                                                    </a>
                                                </div>
                                                <div class="col-4 col-sm-4 col-md-2 col-xl-auto mb-3">
                                                    <a href="#" title="Eliminar" onclick="eliminacion_item_factura_pos(<?php echo $detalle['id'] ?>)" class="btn btn-danger btn-icon w-100">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/trash -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <line x1="4" y1="7" x2="20" y2="7" />
                                                            <line x1="10" y1="11" x2="10" y2="17" />
                                                            <line x1="14" y1="11" x2="14" y2="17" />
                                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>

                </div>

                <div class="container">
                    <div class="row">
                        <div class="col-2">
                            <select class="form-select" id="descuento_porcentaje" onchange="criterio_descuento()">
                                <option selected>Tipo de descuento</option>
                                <option value="1">Descuento %</option>
                                <option value="2">Descuento $</option>

                            </select>
                        </div>
                        <div class="col-1">
                            <input type="number" class="form-control validar" id="valor_descuento%" onkeyup="descuento_de_factura(this.value)" value=0 disabled>
                            <span id="error_descuento" style="color:red;"></span>
                        </div>
                        <div class="col-2">
                            <input type="text" class="form-control" readonly id="total_descuento%" value=0>
                        </div>
                        <div class="col">
                            <select class="form-select" id="descuento_propina" onchange="criterio_propina()">
                                <option disabled selected>Tipo de Propina </option>
                                <option value="1">Propina %</option>
                                <option value="2">Propina $</option>

                            </select>
                        </div>
                        <div class="col-1">
                            <input type="number" class="form-control validar_propina" id="valor_descuento_propina" onkeyup="propina_descuento(this.value)" value=0 disabled>
                            <span id="error_propina" style="color:red;"></span>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" readonly id="total_descuento_propina" value=0>
                        </div>
                        <div class="col-1">
                            <p class="text-end">SUB TOTAL</p>
                            <p class="text-end">TOTAL</p>
                        </div>
                        <div class="col-2">
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    $
                                </span>
                                <input type="text" class="form-control" id="total_pedido_pos" value=<?php echo $valor_total ?> readonly autocomplete="off">
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    $
                                </span>
                                <input type="text" class="form-control" id="gran_total_pedido_pos" value=<?php echo $valor_total ?> readonly autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <br><br>
                <div class="container">
                    <div class="row ">
                        <div style="width:11%">
                            <a href="#" class="btn btn-danger  btn-icon" onclick="reset_factura()" title="Reset factura" aria-label="Flickr">
                                Reset factura
                            </a>
                        </div>
                        <div style="width:10%">
                            <a type="button" class="btn btn-primary  btn-icon" title="Devolución" data-bs-toggle="modal" data-bs-target="#devolucion">
                                Devolución
                            </a>
                        </div>

                        <div style="width:11%">
                            <button type="button" class="btn btn-success btn-icon" data-bs-toggle="modal" title="Retiro de dinero en efectivo" data-bs-target="#retiro">
                                Retiro dinero
                            </button>
                        </div>
                        <div style="width:6.3%">
                            <a href="#" class="btn btn-warning  btn-icon" onclick="observacion_general()" title="Observaciones generales" aria-label="Flickr">
                                Nota
                            </a>
                        </div>
                        <div style="width:9.3%">
                            <a href="#" class="btn btn-success  btn-icon" onclick="imprimir_prefactura()" title="Reset factura" aria-label="Flickr">
                                Prefactura
                            </a>
                        </div>
                        <div style="width:9%">
                            <button type="button" title="imprimir comanda" onclick="impresion_comanda(<?php echo $user_session->id_usuario; ?>)" class="btn btn-primary w-md btn-icon">
                                Comanda
                            </button>
                        </div>
                        <div style="width:10.5%">
                            <button type="button" title="Finalizar venta" onclick="finalizar_venta()" class="btn btn-icon btn-primary ">
                                <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                Facturar
                            </button>
                        </div>
                    </div>
                </div>
                <br>
                <div class="container row">
                    <div style="width:50%">
                    </div>
                    <div style="width:50%">
                        <p class="fs-1 h3" id="notas_y_observaciones"></p>
                    </div>
                </div>

            </div>

            <script>
                function criterio_descuento() {

                    var x = document.getElementById("descuento_porcentaje").value;
                    var tot = document.getElementById("total_pedido_pos").value;

                    if (tot > 0) {
                        if (x == 1 || x == 2) {
                            // var y = document.getElementById("valor_descuento%");
                            document.getElementById("valor_descuento%").disabled = false;
                            //var y = document.getElementById("valor_descuento%").value;
                            //console.log(y)
                        }
                    }
                    if (tot == 0) {
                        const Toast_1 = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast_1.addEventListener('mouseenter', Swal.stopTimer)
                                toast_1.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast_1.fire({
                            icon: 'info',
                            title: 'No hay venta para aplicar descuento '
                        })
                    }
                }
            </script>

            <script>
                function criterio_propina() {

                    var x = document.getElementById("descuento_propina").value;
                    var tot = document.getElementById("total_pedido_pos").value;
                    if (tot > 0) {
                        if (x == 1 || x == 2) {
                            // var y = document.getElementById("valor_descuento%");
                            document.getElementById("valor_descuento_propina").disabled = false;
                            //var y = document.getElementById("valor_descuento%").value;
                            //console.log(y)
                        }
                    }
                    if (tot == 0) {
                        const Toast_1 = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast_1.addEventListener('mouseenter', Swal.stopTimer)
                                toast_1.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast_1.fire({
                            icon: 'info',
                            title: 'No hay venta para aplicar descuento '
                        })
                    }


                }
            </script>

            <script>
                var input = document.getElementById('valor_descuento%');

                input.addEventListener('keydown', function(event) {
                    const key = event.key; // const {key} = event; ES6+
                    if (key === "Backspace") {
                        error_descuento
                        //alert(key);
                        //return false;
                        $('#error_descuento').html('');
                    }
                });
            </script>

            <script>
                var input = document.getElementById('valor_descuento_propina');

                input.addEventListener('keydown', function(event) {
                    const key = event.key; // const {key} = event; ES6+
                    if (key === "Backspace") {
                        error_descuento
                        //alert(key);
                        //return false;
                        $('#error_propina').html('');
                    }
                });
            </script>

            <?= $this->endSection('content') ?>