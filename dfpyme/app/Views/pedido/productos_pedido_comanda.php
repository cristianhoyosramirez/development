<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/salonMesa') ?>
<?= $this->section('title') ?>
SALONES
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>

<style>
    .contenedor {
        height: 55vh;
        overflow: scroll;
    }

    .contenedorProductos {
        height: 30vh;
        overflow: scroll;
    }

    .div {
        background-color: #FBD603;
    }

    .boton-subir {
        position: fixed;
        right: 5%;
        bottom: 10px;
        background-color: #17181C;
        padding: 20px;
        display: none;
        opacity: 0.7
    }

    .boton-subir a {
        font-size: 24px;
        color: #fff;

    }


    .otp-input-wrapper {
        width: 240px;
        text-align: center;
        display: inline-block;
    }

    .otp-input-wrapper input {
        padding: 0;
        width: 264px;
        font-size: 32px;
        font-weight: 600;
        color: #3e3e3e;
        background-color: transparent;
        border: 0;
        margin-left: 12px;
        letter-spacing: 48px;
        font-family: sans-serif !important;
    }

    .otp-input-wrapper input:focus {
        box-shadow: none;
        outline: none;
    }

    .otp-input-wrapper svg {
        position: relative;
        display: block;
        width: 240px;
        height: 2px;
    }
</style>
<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
<script>
    Swal.fire({
        icon: "success",
        title: "Impresión de comanda OK",
    });
</script>
<div class="col-md-12 container" id="pedido">
    <input type="hidden" id="url" value="<?php echo $url ?>">
    <div class="card">
        <div class="card-body ">
            <!-- Tabla de encabezado de pedido-->
            <table class="table">
                <tr>
                    <th style="width:250px">
                        <input type="hidden" id="id_mesa" value="<?php echo $id_mesa ?>">
                        <p class="text-dark">Mesa:</p>
                    </th>
                    <th>
                        <p class="text-dark h4" id="nombre_mesa"></p>
                    </th>
                    <th style="width:250px">
                        <p class="text-dark ">Pedido :</p>
                    </th>
                    <th style="width:250px">
                        <input type="hidden" id="numero_pedido_salvar" value="<?php echo $numero_pedido ?>">
                        <p class="text-dark" id="numero_pedido_mostrar"><?php echo $numero_pedido ?></p>
                    </th>
                    <th>
                        <button type="button" title="Detalle del pedido" onclick="detalle_pedido()" class="btn btn-secondary btn-icon">
                            <!-- Download SVG icon from http://tabler-icons.io/i/eye-check -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <circle cx="12" cy="12" r="2" />
                                <path d="M12 19c-4 0 -7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7c-.42 .736 -.858 1.414 -1.311 2.033" />
                                <path d="M15 19l2 2l4 -4" />
                            </svg>
                        </button>
                    </th>
                </tr>
            </table>


            <!-- Tabla de busqueda de productos por descripcion o categoria -->
            <table class="table">
                <tr>
                    <th scope="col">
                        <div class="col-sm-12">
                            <div class="input-group mb-2">
                                <input type="hidden" id="id_producto">
                                <input type="text" class="form-control" placeholder="Buscar por codigo o nombre" id="producto" name="producto" autofocus>
                                <button type="button" class="btn btn-success btn-icon" onclick="consultar_producto()">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <circle cx="6" cy="19" r="2" />
                                        <circle cx="17" cy="19" r="2" />
                                        <path d="M17 17h-11v-14h-2" />
                                        <path d="M6 5l14 1l-1 7h-13" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </th>
                    <th scope="col">
                        <div class="col-sm-4">
                            <a type="button" title="Búsqueda por categorias" class="btn btn-primary btn-icon" data-bs-toggle="modal" data-bs-target="#categorias_producto">
                                <!-- Download SVG icon from http://tabler-icons.io/i/pizza -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 21.5c-3.04 0 -5.952 -.714 -8.5 -1.983l8.5 -16.517l8.5 16.517a19.09 19.09 0 0 1 -8.5 1.983z" />
                                    <path d="M5.38 15.866a14.94 14.94 0 0 0 6.815 1.634a14.944 14.944 0 0 0 6.502 -1.479" />
                                    <path d="M13 11.01v-.01" />
                                    <path d="M11 14v-.01" />
                                </svg>
                            </a>
                        </div>
                    </th>
                </tr>
            </table>
            <!-- Tabla de producto de pedido-->
            <table class="table table-borderless">
                <thead class="table-dark">
                    <tr>
                        <td> CÓD </td>
                        <td> DESCRIPCIÓN </td>
                        <td>TOTAL</td>
                    <tr>
                        <td>CANTIDAD</td>
                        <td>V UNITARIO</td>
                        <td></td>
                    </tr>
                    </tr>
                </thead>
                <tbody id="productos_pedido">
                    <?php foreach ($productos as $detalle) { ?>
                        <?php { ?>
                            <tr class="table-primary">
                                <td><?php echo "<b>Código.</b>" . " " . $detalle['codigointernoproducto'] ?></td>
                                <td><?php echo $detalle['nombreproducto'] ?></td>
                                <td></td>
                            <tr class="table-primary">
                                <td><?php echo "<b>Cantidad.</b>" . " " . $detalle['cantidad_producto'] . "-" . "(" . $detalle['cantidad_entregada'] . ")" ?></td>
                                <td><?php echo "$" . number_format($detalle['valor_unitario'], 0, ",", ".")  ?></td>
                                <td><?php echo "$" . number_format($valor = $detalle['valor_total'], 0, ",", "."); ?></td>
                            </tr>
                            <?php if (!empty($detalle['nota_producto'])) { ?>
                                <tr class="table-primary">
                                    <th>Notas</th>
                                    <td>
                                        <p> <?php echo $detalle['nota_producto']; ?></p>
                                    </td>
                                    <td></td>
                                </tr>
                            <?php } ?>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="1">
                                    <div class="row g-2 align-items-center mb-n3">
                                        <div class="col-4 col-sm-4 col-md-2 col-xl-auto mb-3">

                                            <button type="button" onclick="editar_cantidades_de_pedido(<?php echo $detalle['id'] ?>)" onkeyup="multiplicar()" class="btn btn-warning  btn-icon w-100">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                                    <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                                </svg>
                                            </button>
                                        </div>
                                        &nbsp;
                                        <div class="col-4 col-sm-4 col-md-2 col-xl-auto mb-3">
                                            <a href="#" class="btn btn-danger  btn-icon w-100" onclick="eliminar_producto(<?php echo $detalle['id'] ?>)">
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
                                <td>
                                    <div class="row g-2 align-items-center mb-n3">
                                        <div class="mb-3 row">
                                            <div class="input-group mb-3">
                                                <button class="btn btn-icon btn-success" onclick="entregar_producto(<?php echo $detalle['id'] ?>)" type="button" title="Entregar">
                                                    <!-- Download SVG icon from http://tabler-icons.io/i/medal -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M12 4v3m-4 -3v6m8 -6v6" />
                                                        <path d="M12 18.5l-3 1.5l.5 -3.5l-2 -2l3 -.5l1.5 -3l1.5 3l3 .5l-2 2l.5 3.5z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>

                                </td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                            </tr>
                        <?php }    ?>
                    <?php } ?>
                </tbody>
            </table>
            <hr>
            <table class="table table-borderless">
                <tr>
                    <th>
                        <p>TOTAL</p>
                    </th>
                    <th>
                        <p id="valor_total"><?php echo "$" . number_format($total, 0, ',', '.') ?></p>
                    </th>
                    <th>
                        <p>Cantidad de productos</p>
                    </th>
                    <th>
                        <p id="cantidad_de_productos"><?php echo $cantidad_de_productos ?></p>
                    </th>
                </tr>
            </table>
            <hr>
            <div class="col-12">

                <div class="row g-2 align-items-center mb-n3">
                    <div class="col-4 col-sm-4 col-md-2 col-xl-auto mb-3">
                        <a href="#" class="btn btn-success w-100 btn-icon" title="Cambiar de mesa" onclick="cambiar_de_mesa()">
                            <!-- Download SVG icon from http://tabler-icons.io/i/exchange -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <circle cx="5" cy="18" r="2" />
                                <circle cx="19" cy="6" r="2" />
                                <path d="M19 8v5a5 5 0 0 1 -5 5h-3l3 -3m0 6l-3 -3" />
                                <path d="M5 16v-5a5 5 0 0 1 5 -5h3l-3 -3m0 6l3 -3" />
                            </svg>
                        </a>

                    </div>
                    <div class="col-3 col-sm-4 col-md-2 col-xl-auto mb-3">
                        <form action="<?= base_url('comanda/imprimir_comanda') ?>" method="POST">
                            <input type="hidden" name="numero_pedido_imprimir_comanda" id="numero_pedido_imprimir_comanda" value="<?php echo $numero_pedido ?>">

                            <button type="submit" title="Imprimir Comanda" class="btn btn-blue w-100 btn-icon">
                                <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                                    <rect x="7" y="13" width="10" height="8" rx="2" />
                                </svg>
                            </button>
                        </form>
                    </div>

                    <div class="col-3 col-sm-4 col-md-2 col-xl-auto mb-3">
                        <a title="Nota del pedido" class="btn btn-warning w-100 btn-icon" onclick="agregar_nota_al_pedido()">
                            <!-- Download SVG icon from http://tabler-icons.io/i/note -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <line x1="13" y1="20" x2="20" y2="13" />
                                <path d="M13 20v-6a1 1 0 0 1 1 -1h6v-7a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7" />
                            </svg>
                        </a>
                    </div>
                    <?php foreach ($session->tipo_permiso as $detalle) { ?>

                        <?php if ($detalle['idpermiso'] == 45) { ?>
                            <div class="col-3 col-sm-4 col-md-2 col-xl-auto mb-3">
                                <form action="<?= base_url('producto/facturar_pedido') ?>" method="POST">
                                    <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $user_session->id_usuario; ?>">
                                    <input type="hidden" name="id_mesa_facturacion" id="id_mesa_facturacion" value="<?php echo $id_mesa ?>">

                                    <button type="submit" title="Facturar pedido" class="btn btn-secondary w-100 btn-icon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/report-money -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                            <rect x="9" y="3" width="6" height="4" rx="2" />
                                            <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                            <path d="M12 17v1m0 -8v1" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        <?php } ?>
                        <?php if ($detalle['idpermiso'] == 94) { ?>
                            <input type="hidden" name="id_mesa_facturacion" id="id_mesa_facturacion">
                        <?php } ?>

                    <?php } ?>

                    <div class="col-3 col-sm-4 col-md-2 col-xl-auto mb-3">
                        <form action="<?= base_url('mesas/todas_las_mesas') ?>" method="GET">
                            <button type="submit" title="Salones" href="<?= base_url('salones/salones') ?>" class="btn btn-info w-100 btn-icon">
                                <!-- Download SVG icon from http://tabler-icons.io/i/building-pavilon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 21h7v-3a2 2 0 0 1 4 0v3h7" />
                                    <line x1="6" y1="21" x2="6" y2="12" />
                                    <line x1="18" y1="21" x2="18" y2="12" />
                                    <path d="M6 12h12a3 3 0 0 0 3 -3a9 8 0 0 1 -9 -6a9 8 0 0 1 -9 6a3 3 0 0 0 3 3" />
                                </svg>
                            </button>
                        </form>
                    </div>

                </div>

                <div class="col-12">
                    <label for="exampleFormControlTextarea1" class="form-label">Observaciones generales </label>
                    <textarea class="form-control" id="observacion_general_de_pedido" rows="3" readonly><?php echo $nota_pedido ?></textarea>
                </div>
            </div>

        </div>
    </div>
</div>
<div id="stop" class="boton-subir">
    <a href="#top">
        <!-- Download SVG icon from http://tabler-icons.io/i/arrow-narrow-up -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="16" y1="9" x2="12" y2="5" />
            <line x1="8" y1="9" x2="12" y2="5" />
        </svg>
    </a>
</div>
<?= $this->include('ventanas_modal_pedido/eliminar_con_pin_pad') ?>
<?= $this->include('ventanas_modal_pedido/editar_con_pin_pad') ?>
<!--jQuery -->
<script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
<!-- jQuery-ui -->
<script src="<?php echo base_url() ?>/Assets/plugin/jquery-ui/jquery-ui.js"></script>


<?= $this->endSection('content') ?>