<p class="text-primary text-center fs-3 fw-bold">Detalle de ventas </p>
<div class="container">
    <div class="row align-items-start">

        <div class="row">
            <div class="col-3">
                <p class="text-dark fw-bold">Caja N° 1 </p>
            </div>
            <div class="col-4">
                <p class="text-dark fw-bold">Fecha apertura <?php echo $fecha_apertura ?></p>
            </div>
            <div class="col-4">
                <p class="text-dark fw-bold">Fecha cierre PENDIENTE DE CIERRE</p>
            </div>

        </div><hr>

        <div class="row">
            <div class="col-4">
                <div class="row">
                    <label for="inputEmail3" class="col-sm-6 col-form-label">
                        <p class="text-dark fw-bold" id="valor_modificado_apertura">Valor apertura:</p>
                    </label>
                    <div class="col-sm-6">
                        <label for="inputEmail3" class="col-sm-12 col-form-label">


                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="text-dark fw-bold" id="valor_modificado_apertura"><?php echo "$" . number_format($valor_apertura, 0, ",", ".") ?></p>

                                </div>
                                <div class="col-sm-4">
                                    <button type="button" class="btn btn-success btn-icon" onclick="edicion_de_apertura_sin_cierre(<?php echo $id_apertura ?>)">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                            <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                        </svg>
                                    </button>
                                </div>
                            </div>


                        </label>
                    </div>
                </div>
                <div class="row">
                    <label for="inputEmail3" class="col-sm-6 col-form-label">
                        <p class="text-dark fw-bold" id="valor_modificado_apertura">Ingresos Efectivo:</p>
                    </label>
                    <div class="col-sm-6">
                        <label for="inputEmail3" class="col-sm-6 col-form-label">
                            <p class="text-dark fw-bold" id="valor_modificado_apertura"><?php echo $ingresos_en_efectivo ?></p>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <label for="inputEmail3" class="col-sm-6 col-form-label">
                        <p class="text-dark fw-bold" id="valor_modificado_apertura">Ingresos transacción:</p>
                    </label>
                    <div class="col-sm-6">
                        <label for="inputEmail3" class="col-sm-6 col-form-label">
                            <p class="text-dark fw-bold" id="valor_modificado_apertura"><?php echo $ingresos_por_transaccion ?></p>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <label for="inputEmail3" class="col-sm-6 col-form-label">
                        <p class="text-dark fw-bold" id="valor_modificado_apertura">Total ingresos:</p>
                    </label>
                    <div class="col-sm-6">
                        <label for="inputEmail3" class="col-sm-6 col-form-label">
                            <p class="text-dark fw-bold" id="valor_modificado_apertura"><?php echo $total_ingresos ?></p>
                        </label>
                    </div>
                </div>
            </div>




            <div class="col">

                <div class="row align-items-start">
                    <div class="col">
                        <p id="efectivo_usuario">Retiros: <?php echo "$" . number_format($total_retiros, 0, ",", ".") ?>
                    </div>
                </div>
                <br>
                <div class="row align-items-start">
                    <div class="col">
                        <p id="transaccion_usuario">Devoluciones <?php echo $total_devoluciones ?></p>
                    </div>

                </div><br>
                <div class="row align-items-start">
                    <div class="col">
                        <p id="transaccion_usuario">Total retiros+devoluciones <?php echo $retiros_mas_devoluciones ?></p>
                    </div>
                </div><br>

                <div class="row align-items-start">
                    <div class="col">
                        <p id="transaccion_usuario">Subtotal: <?php echo "$" . number_format($subtotal, 0, ",", ".") ?></p>
                    </div>
                </div>
            </div>
            <div class="col">

                <div class="row align-items-start">
                    <div class="col">
                        <p id="efectivo_usuario">Efectivo $0
                    </div>

                </div>
                <br>
                <div class="row align-items-start">
                    <div class="col">
                        <p id="transaccion_usuario">Transaccion $0
                    </div>

                </div><br>
                <div class="row align-items-start">
                    <div class="col">
                        <p id="transaccion_usuario">Total $0
                    </div>

                </div>
                <br>
                <div class="row align-items-start">
                    <div class="col">
                        <p id="transaccion_usuario">Saldo <?php echo $saldo ?>
                    </div>

                </div>
            </div>
        </div>






    </div>
</div>

<table class="table table-hover table-striped">
    <thead class="table-dark">
        <tr>
            <td>Item</td>
            <td width="25%">Fecha y hora de venta</td>
            <td>Número factura</td>
            <td>Fecha limite factura</td>
            <td>Tipo factura</td>
            <td>Valor factura</td>
            <td>Forma pago </td>
        </tr>
    </thead>

    <tbody class=" table-striped">
        <?php $temp = 0; ?>
        <?php foreach ($movimientos as $detalle) { ?>
            <?php $contador = $temp + 1; ?>
            <tr>
                <td><?php echo $contador; ?></td>
                <td><?php echo $detalle['fecha_factura_venta'] . " " . $detalle['horafactura_venta'] ?></td>
                <td><?php echo $detalle['numerofactura_venta'] ?></td>
                <td><?php echo $detalle['fecha_factura_venta'] ?></td>
                <td><?php echo "Contado"; ?></td>
                <td><?php echo "$" . number_format($detalle['valor_factura'], 0, ",", ".") ?></td>
                <td>
                    <?php
                    $formas_pago = model('facturaFormaPagoModel')->forma_pago($detalle['id']);
                    foreach ($formas_pago as $detalle) {
                        echo $detalle['nombreforma_pago'] . " " . "$" . number_format($detalle['valorfactura_forma_pago'], 0, ",", ".") . "</br>";
                    }
                    ?>

                </td>

            </tr>
            <?php $temp = $contador; ?>
        <?php } ?>
    </tbody>
</table>

<p class="text-primary text-center fs-3 fw-bold">Detalle de devoluciones </p>
<div class="container">
    <div class="row align-items-start">
        <div class="col">

            <p>Total devoluciones <?php echo $total_devoluciones ?></p>

        </div>
    </div>
</div>

<table class="table table-hover table-striped">
    <thead class="table-dark">
        <tr>
            <td>Item</td>
            <td width="25%">Fecha y hora devolución</td>
            <td>Cantidad</td>
            <td>Producto</td>
            <td>Valor</td>
        </tr>
    </thead>

    <tbody class=" table-striped">
        <?php $temp = 0;

        foreach ($devoluciones as $detalle) { ?>
            <?php $contador = $temp + 1; ?>
            <tr>
                <th><?php echo $contador; ?></th>
                <th><?php echo $detalle['fecha'] . " " . $detalle['hora'] ?></th>
                <th>
                    <?php
                    $cantidad = model('detalleDevolucionVentaModel')->select('cantidad')->where('id_devolucion_venta', $detalle['id'])->first();
                    echo $cantidad['cantidad'];
                    ?>
                </th>
                <th>
                    <?php
                    $codigo_interno_producto = model('detalleDevolucionVentaModel')->select('codigo')->where('id_devolucion_venta', $detalle['id'])->first();
                    $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigo'])->first();
                    echo  $nombre_producto['nombreproducto'];
                    ?>
                </th>
                <th>
                    <?php
                    $valor_devolucion = model('devolucionVentaEfectivoModel')->select('valor')->where('iddevolucion', $detalle['id'])->first();
                    echo "$" . number_format($valor_devolucion['valor'], 0, ",", ".");
                    ?>
                </th>
            </tr>
            <?php $temp = $contador; ?>






        <?php } ?>
    </tbody>
</table>

<p class="text-primary text-center fs-3 fw-bold">Detalle de retiros </p>
<div class="container">
    <div class="row align-items-start">
        <div class="col">


            <?php
            $temp_retiros = 0;
            $total_retiros = 0;
            foreach ($retiros as $detalle) {
                $valor = model('retiroFormaPagoModel')->select('valor')->where('idretiro', $detalle['id'])->first();
                $temp_retiros = $temp_retiros + $valor['valor'];
                $total_retiros = $temp_retiros;
            }
            ?>

            <p class="text-dark fw-bold">Total retiros <?php echo "$" . number_format($total_retiros, 0, ",", ".") ?></p>

        </div>
    </div>
</div>

<table class="table table-hover table-striped">
    <thead class="table-dark">
        <tr>
            <td>Item</td>
            <td width="25%">Fecha y hora retiro</td>
            <td>Valor</td>
            <td>Concepto</td>
            <td>Acciones</td>
        </tr>
    </thead>

    <tbody class=" table-striped">
        <?php $temp = 0;
        foreach ($retiros as $detalle) { ?>
            <?php $contador = $temp + 1; ?>
            <tr>
                <th><?php echo $contador; ?></th>
                <th><?php echo $detalle['fecha'] . " " . $detalle['hora'] ?></th>


                <th>
                    <?php
                    $valor = model('retiroFormaPagoModel')->select('valor')->where('idretiro', $detalle['id'])->first();
                    echo "$" . number_format($valor['valor'], 0, ",", ".");
                    ?>
                </th>

                <th>
                    <?php
                    $concepto = model('retiroFormaPagoModel')->select('concepto')->where('idretiro', $detalle['id'])->first();
                    echo $concepto['concepto'];
                    ?>
                </th>

                <td>
                    <div class="row g-2 align-items-center mb-n3">
                        <div class="col-6 col-sm-4 col-md-2 col-xl-auto mb-3">


                            <button type="button" onclick="imprimir_duplicado_retiro('<?php echo $detalle['id'] ?>','<?php echo $detalle['idusuario'] ?>')" class="btn btn-success btn-icon" title="Reimprimir retiro de dinero ">
                                <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                                    <rect x="7" y="13" width="10" height="8" rx="2" />
                                </svg>
                            </button>
                        </div>
                        <div class="col-6 col-sm-4 col-md-2 col-xl-auto mb-3">
                            <button onclick="edicion_retiro_de_dinero(<?php echo $detalle['id'] ?>)" type="button" class="btn btn-warning btn-icon" title="Editar retiro de dinero">
                                <input type="hidden" value="<?= base_url() ?>" id="url">
                                <!-- Download SVG icon from http://tabler-icons.io/i/edit -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />
                                    <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" />
                                    <line x1="16" y1="5" x2="19" y2="8" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </td>

            </tr>
            <?php $temp = $contador; ?>
        <?php } ?>
    </tbody>
</table>



<!-- Modal Retiro de dinero -->
<div class="modal fade" id="edicion_retiro_de_dinero" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar retiro de dinero</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                            <path d="M12 3v3m0 12v3" />
                        </svg>
                    </span>
                    <input type="text" class="form-control form-control-rounded" id="edit_retiro_de_dinero">
                    <input type="hidden" class="form-control form-control-rounded" id="id_edicion_retiro_de_dinero">

                </div>
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Concepto</label>
                    <textarea class="form-control" id="concepto_retiros" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="actualizar_retiro_de_dinero()">Guardar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal edicion de apertura de caja  -->
<div class="modal fade" id="edicion_de_apertura_de_caja_sin_cierre" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar valor de apertura de cajas </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                            <path d="M12 3v3m0 12v3" />
                        </svg>
                    </span>
                    <input type="text" class="form-control form-control-rounded" id="editar_valor_apertura_sin_cierre" value="<?php echo  number_format($valor_apertura, 0, ",", ".") ?>">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="actualizar_apertura_caja_sin_cierre(<?php echo $id_apertura ?>)">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal edicion de efectivo de usuario -->
<div class="modal fade" id="edicion_efectivo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar retiro de dinero</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                            <path d="M12 3v3m0 12v3" />
                        </svg>
                    </span>
                    <input type="text" class="form-control form-control-rounded" id="edit_retiro_de_dinero">
                    <input type="hidden" class="form-control form-control-rounded" id="id_edicion_retiro_de_dinero">

                </div>
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Concepto</label>
                    <textarea class="form-control" id="concepto_retiros" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="actualizar_retiro_de_dinero()">Guardar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal edicion de transaccion -->
<div class="modal fade" id="edicion_de_transaccion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar retiro de dinero</h5>
                <button type="button" class="btn-close" onclick="editar_" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                            <path d="M12 3v3m0 12v3" />
                        </svg>
                    </span>
                    <input type="text" class="form-control form-control-rounded" id="edit_retiro_de_dinero">
                    <input type="hidden" class="form-control form-control-rounded" id="id_edicion_retiro_de_dinero">

                </div>
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Concepto</label>
                    <textarea class="form-control" id="concepto_retiros" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="actualizar_retiro_de_dinero()">Guardar</button>
            </div>
        </div>
    </div>
</div>



<script>
    const cambiar_valor_apertura_sin_cierre =
        document.querySelector("#editar_valor_apertura_sin_cierre");

    function formatNumber(n) {
        n = String(n).replace(/\D/g, "");
        return n === "" ? n : Number(n).toLocaleString();
    }
    cambiar_valor_apertura_sin_cierre.addEventListener("keyup", (e) => {
        const element = e.target;
        const value = element.value;
        element.value = formatNumber(value);
    });
</script>