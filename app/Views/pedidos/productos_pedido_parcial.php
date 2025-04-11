<div class="table-responsive">
    <table class="table table-vcenter card-table table  table-hover table-border">

        <thead class="table-dark">
            <tr>
                <td>Producto </th>
                <td>Valor unitario </th>
                <td style="width: 60px;">Cantidad</th>
                <td>Cantidad a pagar </th>
                <td style="width: 60px;">Total </th>

            </tr>
        </thead>

        <tbody>

            <?php foreach ($productos as $detalle) { ?>

                <tr>
                    <td>
                        <?php echo $detalle['nombreproducto']; ?>

                    </td>
                    <td>
                        <?php echo "$ " . number_format($detalle['valor_unitario'], 0, ",", ".") ?>
                    </td>

                    <td style="width: 20px;">

                        <?php $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $detalle['id_tabla_producto'])->first(); ?>
                        <input type="text" class=" form-control " value="<?php echo $cantidad_producto['cantidad_producto'] ?>" readonly>

                    </td>

                    <td>


                        <div class="input-group">
                            <a href="#" class="btn bg-muted-lt w-1 btn-icon " onclick="restar_partir_factura(event,'<?php echo $detalle['cantidad_producto'] ?>','<?php echo $detalle['id'] ?>')">
                                <!-- Download SVG icon from http://tabler-icons.io/i/minus -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                            </a>


                            <input type="hidden" class="form-control w-2" value="<?php echo $detalle['cantidad_producto'] ?>">
                            <input type="number" class="w-3 form-control form-control-sm text-center" value="<?php echo $detalle['cantidad_producto'] ?>" onclick="detener_propagacion(event)" onkeypress="return valideKey(event)" min="1" max="100" readonly>

                            <a href="#" class="btn bg-muted-lt w-1 btn-icon " onclick="actualizar_partir_factura(event,'<?php echo $detalle['cantidad_producto'] ?>','<?php echo $detalle['id'] ?>')">
                                <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                            </a>
                        </div>


                    </td>

                    <td>
                        <?php echo number_format($detalle['valor_total'], 0, ",", ".") ?>
                    </td>

                </tr>
            <?php } ?>

        </tbody>
    </table>
</div>