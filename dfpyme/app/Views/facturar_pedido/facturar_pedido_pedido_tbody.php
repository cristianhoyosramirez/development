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