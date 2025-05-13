<?php

$valorUnitario = model('productoPedidoModel')->select('valor_unitario')->where('id', $id_tabla_producto)->first();
$valorTotal = model('productoPedidoModel')->select('valor_total')->where('id', $id_tabla_producto)->first();

?>
<div class="row">
    <div class="col-2"></div>
    <div class="col-2">
        <p id="valorUnidad"><?php echo number_format($valorUnitario['valor_unitario'], 0, '', '.'); ?>
        </p>
    </div>

    <div class="col-3">
        <div class="input-group">
            <!-- Botón para disminuir cantidad -->
            <div class="input-group-prepend">
                <a href="#" class="btn bg-muted-lt btn-icon" onclick="eliminar_cantidades(event, '<?php echo $id_tabla_producto ?>')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                </a>
            </div>

            <input type="hidden" id="valUnidad">
            <input type="hidden" id="codigoInternoProd">
            <!-- Campo de cantidad -->
            <input type="number"
                class="form-control form-control-sm text-center custom-width"
                value="<?php echo $cantidad; ?>"
                id="input_cantidadAtri<?php echo $id_tabla_producto; ?>"
                oninput="actualizacion_cantidades(this.value, <?php echo $id_tabla_producto ?>)"
                onclick="resaltar_cantidad(<?php echo $id_tabla_producto ?>)">

            <!-- Botón para aumentar cantidad -->
            <div class="input-group-append">
                <a href="#" class="btn bg-muted-lt btn-icon"
                    onclick="actualizar_cantidades(event, '<?php echo $id_tabla_producto ?>')" title="Agregar producto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="col-2">
        <p id="totalProducto<?php echo $id_tabla_producto ?>"><?php echo number_format($valorTotal['valor_total'], 0, '', '.'); ?>
        </p>
    </div>
</div>