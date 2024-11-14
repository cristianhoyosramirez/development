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
        height: 170px;
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
            <table class="table table-borderless">
                <tbody>
                    <tr height="5">
                        <!--<th>Cliente:</th>-->
                        <td width="30%">
                            <input type="hidden" id="id_cliente_factura_pos" name="id_cliente_factura_pos" value="22222222">
                            <div class="mb-3">
                                <label for="">Cliente</label><br>
                                <div class="input-group mb-2">
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
                        </td>
                        <td width="20%">
                            <label for=""> Tipo de factura</label>
                            <select class="form-select select2" name="estado_pos" id="estado_pos">
                                <?php foreach ($estado as $detalle) {
                                ?>
                                    <option value="<?php echo $detalle['idestado']
                                                    ?>"><?php echo $detalle['descripcionestado']
                                                        ?> </option>
                                <?php }
                                ?>
                                <!-- <option value="1" selected>Contado</option>-->
                            </select>
                        </td>

                        <td width="20%"> <label for="">Fecha limite</label><input type="date" class="form-control" onclick="reset_error()" name="fecha_limite" id="fecha_limite"><br> <span id="error_fecha_limite" style="color:red"></span></td>
                    </tr>
                    <tr height="5">
                        <td>
                            <label>Producto</label>
                            <input type="hidden" id="codigointernoproducto" name="codigointernoproducto">
                            <input type="text" class="form-control" id="buscar_producto" name="buscar_producto" placeholder="Código o descripción" onkeyup="buscar_por_codigo_de_barras(event, this.value),saltar_factura_pos(event,'cantidad_factura_pos')" autofocus>
                            <span style="color:#FF0000;" id="mensaje_de_error"></span>
                        </td>
                        <td>
                            <label>Lista de precios </label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected value="0">Al detal </option>
                                <option value="1">Al por mayor </option>
                            </select>
                        </td>

                        <td>
                            <label>Valor unitario</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    $
                                </span>
                                <input type="hidden" id="precio">
                                <input type="text" class="form-control" placeholder="Valor unitario" id="valor_venta_producto" autocomplete="off" readonly>
                            </div>
                        </td>

                        <td>
                            <label for="">Cantidad</label>
                            <input type="text" class="form-control" id="cantidad_factura_pos" value="1" placeholder="Cantidad a agregar" onkeyup="saltar_factura_pos(event,'cargar_producto_al_pedido')" onKeyPress="return soloNumerosEnCantidad(event)">
                        </td>

                        <td>
                            <label for="">Nota producto</label>
                            <textarea class="form-control" rows="1" cols="50" id="nota_producto_pos" placeholder="Nota del producto"></textarea>
                        </td>
                        <td>
                            <p></p>
                            <a href="#" class="btn btn-success w-100 btn-icon" title="Agregar producto al pedido" aria-label="Bitbucket" id="cargar_producto_al_pedido" onclick="cargar_producto_al_pedido()">
                                <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                                <!-- <img src="<?php #echo base_url(); 
                                                ?>/Assets/img/guardar.png" width="110" height="32" alt="Macondo" class="navbar-brand-image">-->

                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>



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
                    <tbody>

                    </tbody>
                </table>

            </div>
            <div class="row g-3 align-items-center">

                <div class="col">
                    <a href="#" class="btn btn-danger  btn-icon" onclick="reset_factura()" title="Reset factura" aria-label="Flickr">
                        <!-- Download SVG icon from http://tabler-icons.io/i/letter-x -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <line x1="7" y1="4" x2="17" y2="20" />
                            <line x1="17" y1="4" x2="7" y2="20" />
                        </svg>

                    </a>
                </div>
                <div class="col">
                    <a type="button" class="btn btn-primary  btn-icon" title="Devolución" data-bs-toggle="modal" data-bs-target="#devolucion">
                        <!-- Download SVG icon from http://tabler-icons.io/i/arrow-back-up -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1" />
                        </svg>
                    </a> </tr>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-success btn-icon" data-bs-toggle="modal" title="Retiro de dinero en efectivo" data-bs-target="#retiro">
                        <!-- Download SVG icon from http://tabler-icons.io/i/cash -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <rect x="7" y="9" width="14" height="10" rx="2" />
                            <circle cx="14" cy="14" r="2" />
                            <path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" />
                        </svg>
                    </button>
                </div>
                <div class="col-3">
                </div>

                <div class="col-1">
                    <label for="inputPassword6" class="col-form-label">
                        <p class="fs-1 h1">TOTAL:</p>
                    </label>
                </div>
                <div class="col-3" id="valor_total_pedido">
                    <div class="input-group mb-2">
                        <span class="input-group-text">
                            $
                        </span>
                        <input type="text" class="form-control" id="total_pedido_pos" value=<?php echo $valor_total ?> readonly autocomplete="off">
                    </div>
                </div>

                <div class="col">
                    <a href="#" class="btn btn-warning w-100 btn-icon" onclick="observacion_general()" title="Observaciones generales" aria-label="Flickr">
                        <!-- Download SVG icon from http://tabler-icons.io/i/note -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <line x1="13" y1="20" x2="20" y2="13" />
                            <path d="M13 20v-6a1 1 0 0 1 1 -1h6v-7a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7" />
                        </svg>
                    </a>
                </div>
                <div class="col">

                    <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" name="id_usuario" id="id_usuario">
                    <button type="button" title="imprimir comanda" onclick="impresion_comanda(<?php echo $user_session->id_usuario; ?>)" class="btn btn-primary w-md btn-icon">
                        <!-- Download SVG icon from http://tabler-icons.io/i/printer-->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                            <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                            <rect x="7" y="13" width="10" height="8" rx="2" />
                        </svg>
                    </button>

                </div>
                <div class="col">
                    <button type="submit" title="Finalizar venta" onclick="finalizar_venta()" class="btn btn-icon btn-primary ">
                        <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                            <path d="M12 3v3m0 12v3" />
                        </svg>
                    </button>
                </div>

            </div>
            <div class="row">
                <div class="col-10">
                    <label for="inputPassword6" class="col-form-label">
                        <p class="fs-1 h1"></p>
                    </label>
                </div>
            </div>

        </div>

        <?= $this->endSection('content') ?>