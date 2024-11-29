<?= $this->extend('template/facturar_pedido') ?>
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
        height: 250px;
        overflow: scroll;
    }
</style>

<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('mesas/todas_las_mesas') ?>">Mesas</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('salones/salones') ?>">Salones</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('pedido/pedidos_para_facturar') ?>">Ver todos los pedidos</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">FACTURACIÓN DE PEDIDOS </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>

<div class="card container">
    <div class="card-body">
        <input type="hidden" value="<?= base_url() ?>" id="url" name="url">
        <input type="hidden" value="<?php echo $numero_de_pedido['id'] ?>" id="numero_de_facturacion" name="url">
        <div class="col-lg-12">
            <div class="row">
                <div style="width:25%">
                    <div class="input-group mb-3">
                        <input type="hidden" id="cliente" value="22222222">
                        <input type="text" class="form-control" id="clientes" value="CLIENTE GENERAL"> <a href="#" class="btn btn-yellow btn-icon" title="Agregar cliente" aria-label="Button" data-bs-toggle="modal" data-bs-target="#crear_cliente">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                        </a>
                    </div>
                    <span id="error_falta_cliente"></span>
                </div>

                <div style="width:25%">
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Documento</label>
                        <div class="col-sm-8">
                            <select class="form-select select2" name="estado" id="estado">
                              <!--   <?php foreach ($estado as $detalle) { ?>
                                    <?php if ($detalle['idestado'] != 5) { ?>
                                        <option value="<?php echo $detalle['idestado'] ?>"><?php echo $detalle['descripcionestado'] ?> </option>
                                    <?php } ?>
                                <?php } ?> -->
                                <option value="1">CONTADO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div style="width:30%">
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Fecha limite </label>
                        <div class="col-sm-8">
                            <input type="date" value="<?php echo date('Y-m-d') ?>" class="form-control">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">

            <div style="width:25%">

                <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Producto</label>
                    <div class="col-sm-8">
                        <input type="hidden" id="id_item_pedido" name="id_item_pedido">
                        <input type="text" class="form-control" id="agregar_item" name="producto_sin_pedido" placeholder="Código o descripción" onkeyup="busqueda_por_codigo_de_barras(event, this.value)" autofocus>
                        <p id="item_nombre_producto"></p>
                    </div>
                </div>

            </div>


            <?php $lista_precios = model('cajaModel')->select('requiere_lista_de_precios')->first(); ?>

            <?php if ($lista_precios['requiere_lista_de_precios'] == 'f') { ?>

                <input type="hidden" id="lista_precios" value=0>

                <div style="width:25%">

                    <div class="row mb-3">

                        <div class="col-sm-13">
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    $
                                </span>
                                <input type="hidden" id="precio">
                                <input type="text" class="form-control" placeholder="Valor unitario" id="valor_unitario_item" readonly>
                            </div>
                        </div>
                    </div>

                </div>
            <?php } ?>

            <?php if ($lista_precios['requiere_lista_de_precios'] == 't') { ?>

                <input type="hidden" id="lista_precios" value=1>

                <div style="width:25%">


                    <div class="col-sm-12">
                        <div id="lista_precios_pedido">
                            <select class="form-select" aria-label="Default select example" id="select_lista_precios_pedido" onchange="myFunction()">

                            </select>
                        </div>

                    </div>


                </div>

            <?php } ?>

            <div style="width:30%">

                <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Cantidad</label>
                    <div class="col-sm-8">
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" onkeypress="return soloNumerosEnPedido(event)" onkeyup="saltar_item_pedido(event,'item_pedido_cantidad')" id="item_cantidad" name="nueva_cantidad_sin_pedido" value="1" placeholder="Cantidad a agregar">
                        </div>
                    </div>
                </div>

            </div>
            <div style="width:2%">
                <div class="col-sm-12">
                    <div class="input-group mb-12">
                        <input type="hidden" class="form-control" rows="2" cols="50" placeholder="Nota producto"></input>
                    </div>
                </div>
            </div>

            <div style="width:5%">
                <a href="#" class="btn btn-yellow btn-icon" id="item_pedido_cantidad" onclick="agregar_item_pedido()">
                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                </a>
            </div>



        </div>

        <div class="row">
            <div class="row">
                <div class="table-responsive">
                    <div id="productosAdicionales_sin_pedido">
                        <table class="table table-borderless table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <td>#</td>
                                    <td width: 100%>Código</td>
                                    <td width: 100%>Descripción</td>
                                    <td width: 100%>Valor unitario</td>
                                    <td width: 100%>Cantidad</td>
                                    <td width: 100%>Total</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody id="productos_pedido_a_facturar">
                                <?php foreach ($productos as $detalle) { ?>
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="<?php echo $detalle['id']; ?>" class="delete_checkbox"> </td>
                                        <td><?php echo $detalle['codigointernoproducto'] ?></td>
                                        <td><?php echo $detalle['nombreproducto'] ?></td>
                                        <td><?php echo "$" . number_format($detalle['valor_unitario'], 0, ",", ".") ?></td>
                                        <td><?php echo $detalle['cantidad_producto'] ?></td>
                                        <td><?php echo "$" . number_format($detalle['valor_total'], 0, ",", ".") ?></td>
                                        <td class="strong text-end">
                                            <div class="row g-2 align-items-center mb-n3">
                                                <div class="col-4 col-sm-4 col-md-2 col-xl-auto mb-3">
                                                    <a href="#" title="Editar" onclick="editar_datos_producto(<?php echo $detalle['id'] ?>)" data-bs-toggle="modal" data-bs-target="#pr" class="btn btn-warning btn-icon w-100">

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
                                                    <a href="#" title="Eliminar" onclick="eliminar_producto_pedido(<?php echo $detalle['id'] ?>)" class="btn btn-danger btn-icon w-100">
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-2">
                    <select class="form-select" id="descuento_porcentaje" onchange="criterio_descuento_pedido()">
                        <option selected>Tipo descuento </option>
                        <option value="1">Descuento %</option>
                        <option value="2">Descuento $</option>

                    </select>
                </div>
                <div class="col-1">
                    <input type="text" class="form-control validar_descuento" id="valor_descuento%" onkeyup="descuento_de_factura(this.value)" value=0 disabled>
                    <span id="error_porcentaje_descuento" style="color:red;"></span>
                </div>
                <div class="col-2">
                    <input type="number" class="form-control" readonly id="total_descuento%" value=0>
                    
                </div>
                <div class="col">
                    <select class="form-select" id="descuento_propina" onchange="criterio_propina_pedido()">
                        <option disabled selected>Tipo propina </option>
                        <option value="1">Propina %</option>
                        <option value="2">Propina $</option>

                    </select>
                </div>
                <div class="col-1">
                    <input type="text" class="form-control" id="valor_descuento_propina" onkeyup="propina_descuento(this.value)" value=0 disabled>
                    <span id="error_porcentaje_propina" style="color:red;"></span>
                </div>
                <div class="col">
                    <input type="text" class="form-control" readonly id="total_descuento_propina" value=0>
                </div>
                <div class="col-1">
                    <p class="text-end">SUB TOTAL</p>
                    <p></p>
                    <p class="text-end">TOTAL</p>
                    
                </div>
                <div class="col-2">
                    <div class="input-group mb-2">
                        <span class="input-group-text">
                            $
                        </span>
                        <input type="hidden" class="form-control" id="total_del_pedido_sin_formato" value="<?php echo $total_hidden ?>">
                        <input type="text" class="form-control" id="total_del_pedido" value="<?php echo number_format($total, 0, ',', '.') ?>" readonly autocomplete="off">
                    </div>
                    <div class="input-group mb-2">
                        <span class="input-group-text">
                            $
                        </span>
                        <input type="text" class="form-control" id="resultado_total_del_pedido" value="<?php echo number_format($total, 0, ',', '.') ?>" readonly autocomplete="off">
                    </div>

                </div>
            </div>
        </div>

        <div class="container">

            <div class="row ">
                <div style="width:25%">

                </div>
                <div style="width:17%">
                    <input type="hidden" value="<?php echo $numero_de_pedido['id'] ?>" name="numero_pedido_imprimir_comanda" id="numero_pedido_imprimir_comanda">
                    <button type="button" onclick="imprimir_comanda_desde_pedido()" class="btn btn-primary w-md">Imprimir comanda[F2]</button>
                </div>
                <div style="width:12%">
                    <button type="submit" id="partir_factura" class="btn btn-primary w-md">Partir factura </button>
                </div>
                <div style="width:20%">
                    <a type="button" id="fin_venta" onclick="cerrar_venta()" class="btn btn-success"><i class="mdi mdi-plus"></i>Finalizar venta [F8]</a>
                </div>
            </div>
        </div>









        <?= $this->endSection('content') ?>