<style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }
</style>


<div class="table-responsive">
    <table class="table table-vcenter card-table table  table-hover table-border">
        <thead class="table-dark">
            <tr>
                <td>Producto</th>
                <td>Precio</th>
                <td>Cantidad</th>
                <td></td>
                <td class="text-dark"> total</th>
                <td>Total</td>
                <td colspan="3">Acciones</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($productos as $detalle) { ?>

                <tr>
                    <td>
                        <?php echo $detalle['codigointernoproducto']."-".$detalle['nombreproducto']; ?>
                        <?php if (!empty($detalle['nota_producto'])) { ?>
                            <p class="text-primary fw-bold">NOTA: <?php echo $detalle['nota_producto'] ?></p>

                        <?php } ?>
                    </td>
                    <td>
                        <?php echo "$" . number_format($detalle['valor_unitario'], 0, ",", ".")  ?>
                    </td>

                    <td colspan="3">

                        <div class="input-group">
                            <a href="#" class="btn bg-muted-lt w-1 btn-icon" onclick="eliminar_cantidades(<?php echo $detalle['id_tabla_producto'] ?>)">
                                <!-- Download SVG icon from http://tabler-icons.io/i/minus -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                            </a>


                            <input type="hidden" class="form-control w-2" value="<?php echo $detalle['cantidad_producto'] ?>">
                            <input type="number" class="w-1 form-control text-center" value="<?php echo $detalle['cantidad_producto'] ?>" onkeypress="return valideKey(event)" min="1" max="100" onkeyup="actualizar_producto_cantidad(event,this.value,<?php echo $detalle['id_tabla_producto'] ?>)" readonly>

                            <a href="#" class="btn bg-muted-lt w-1 btn-icon" onclick="agregar_producto(<?php echo $detalle['id_tabla_producto'] ?>)" title="Agregar producto">
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
                        <?php echo "$" . number_format($valor = $detalle['valor_total'], 0, ",", "."); ?>
                    </td>



                    <td> <a href="#" class="btn text-white bg-red-lt w-1 btn-icon" aria-label="Bitbucket" onclick="eliminar_producto(<?php echo $detalle['id_tabla_producto'] ?>)">
                            <!-- Download SVG icon from http://tabler-icons.io/i/trash -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <line x1="4" y1="7" x2="20" y2="7" />
                                <line x1="10" y1="11" x2="10" y2="17" />
                                <line x1="14" y1="11" x2="14" y2="17" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg>
                        </a></td>
                    <td>
                        <a href="#" class="btn bg-azure-lt w-1 btn-icon" aria-label="Bitbucket" onclick="editar_cantidades_de_pedido(<?php echo $detalle['id_tabla_producto'] ?>)">

                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                            </svg>
                        </a>
                    </td>
                    <td> <a href="#" class="btn bg-cyan-lt w-1 btn-icon" aria-label="Tabler" onclick="entregar_producto(<?php echo $detalle['id_tabla_producto'] ?>)" title="Entregar producto">
                            <!-- Download SVG icon from http://tabler-icons.io/i/hand-click -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 13v-8.5a1.5 1.5 0 0 1 3 0v7.5" />
                                <path d="M11 11.5v-2a1.5 1.5 0 0 1 3 0v2.5" />
                                <path d="M14 10.5a1.5 1.5 0 0 1 3 0v1.5" />
                                <path d="M17 11.5a1.5 1.5 0 0 1 3 0v4.5a6 6 0 0 1 -6 6h-2h.208a6 6 0 0 1 -5.012 -2.7l-.196 -.3c-.312 -.479 -1.407 -2.388 -3.286 -5.728a1.5 1.5 0 0 1 .536 -2.022a1.867 1.867 0 0 1 2.28 .28l1.47 1.47" />
                                <path d="M5 3l-1 -1" />
                                <path d="M4 7h-1" />
                                <path d="M14 3l1 -1" />
                                <path d="M15 6h1" />
                            </svg>
                        </a></button></td>
                </tr>
            <?php } ?>
           
        </tbody>
    </table>
</div>