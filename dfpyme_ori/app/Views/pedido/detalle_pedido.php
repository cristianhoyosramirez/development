<!-- <div class="container">
    <div class="row align-items-start">
        <div class="col">
            Pedido N° : <?php echo $pedido ?>
        </div>
        <div class="col">
            Fecha: <?php echo $fecha_pedido ?>
        </div>
        <div class="col">
            Mesero: <?php echo $usuario ?>
        </div>
    </div>
</div>



<?php foreach ($productos as $detalle) { ?>
    <?php if ($detalle['cantidad_entregada'] < $detalle['cantidad_producto']) { ?>
        <div class="container bg-red-lt border border-2 rounded border-dark gy-2 ">
            <span class="text-primary"><?php echo $detalle['nombreproducto']  ?> </span>
            <div class="row align-items-start ">
                <div class="col">
                    <span class="text-dark"> V unidad </span> </br>
                    <span class="text-dark"> <?php echo "$" . number_format($detalle['valor_unitario'], 0, ',', '.') ?></span>
                </div>
                <div class="col">
                    <span class="text-dark"> Cant <br><?php echo $detalle['cantidad_producto']  ?> </span>
                </div>
                <div class="col-2">
                    <span class="text-dark"> Entrega <br> <?php echo $detalle['cantidad_entregada']  ?></span>
                </div>
                <div class="col-4">
                    <span class="text-dark"> Total </span> <br> <span class="text-dark h3"> <?php echo "$" . number_format($detalle['valor_total'], 0, ',', '.') ?></span>

                </div>
            </div>
            <?php if (!empty($detalle['nota_producto'])) { ?>
                <span class="text-dark">Notas:</span> <span class="text-danger"> <?php echo $detalle['nota_producto']  ?> </span>
            <?php } ?>
        </div>
    <?php } ?>

    <?php if ($detalle['cantidad_entregada'] == $detalle['cantidad_producto']) { ?>
        <div class="container bg-green-lt border border-2 rounded border-dark gy-2 ">
            <span class="text-primary"><?php echo $detalle['nombreproducto']  ?> </span>
            <div class="row align-items-start ">
                <div class="col">
                    <span class="text-dark"> V unidad </span> </br>
                    <span class="text-dark"> <?php echo "$" . number_format($detalle['valor_unitario'], 0, ',', '.') ?></span>
                </div>
                <div class="col">
                    <span class="text-dark"> Cant <br><?php echo $detalle['cantidad_producto']  ?> </span>
                </div>
                <div class="col-2">
                    <span class="text-dark"> Entrega <br> <?php echo $detalle['cantidad_entregada']  ?></span>
                </div>
                <div class="col-4">
                    <span class="text-dark"> Total </span> <br> <span class="text-dark h3"> <?php echo "$" . number_format($detalle['valor_total'], 0, ',', '.') ?></span>

                </div>
            </div>
            <?php if (!empty($detalle['nota_producto'])) { ?>
                <span class="text-dark">Notas:</span> <span class="text-danger"> <?php echo $detalle['nota_producto']  ?> </span>
            <?php } ?>
        </div>
    <?php } ?>

<?php } ?>





<div class="row align-items-start">

    <div class="col">
        <p class="text-end text-dark h2"> Total pedido : <?php echo "$" . number_format($valor_total, 0, ',', '.') ?></p>
    </div>

</div>
<th>Nota pedido:</th>
<td><?php echo $nota_pedido ?></td> -->


<table class="table table-borderless">
    <tbody>
        <tr>
            <th>Pedido N° :</th>
            <td><?php echo $pedido ?></td>
            <th>Fecha : </th>
            <td><?php echo $fecha_pedido ?></td>
            <th>Mesero :</th>
            <td><?php echo $usuario ?></td>
        </tr>
        <tr>
            <th>Mesa :</th>
            <td><?php echo $mesa ?></td>
        </tr>
    </tbody>
</table>

<table class="table table-borderless">
    <thead class="table-dark">
        <tr>
            <td scope="col">Producto</td>
            <td scope="col">Valor unitario</td>
            <td scope="col">Cantidad</td>
            <td scope="col">Cantidad entregada</td>
            <td scope="col">Total</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $detalle) { ?>
            <?php if ($detalle['cantidad_entregada'] < $detalle['cantidad_producto']) { ?>
                <tr class="table-danger">
                    <th><?php echo $detalle['nombreproducto']  ?></th>
                    <th><?php echo "$" . number_format($detalle['valor_unitario']) ?></th>
                    <th><?php echo $detalle['cantidad_producto']  ?></th>
                    <th><?php echo $detalle['cantidad_entregada']  ?></th>
                    <th><?php echo "$" . number_format($detalle['valor_total']) ?></td>
                        <?php if (!empty($detalle['nota_producto'])) { ?>
                <tr class="table-danger">
                    <th>Notas:</th>
                    <td colspan="4"><?php echo $detalle['nota_producto']  ?></th>
                </tr>
                </tr>
            <?php } ?>
        <?php } ?>
        <?php if ($detalle['cantidad_entregada'] == $detalle['cantidad_producto']) { ?>
            <tr class="table-success">
                <th><?php echo $detalle['nombreproducto']  ?></th>
                <th><?php echo "$" . number_format($detalle['valor_unitario']) ?></th>
                <th><?php echo $detalle['cantidad_producto']  ?></th>
                <th><?php echo $detalle['cantidad_entregada']  ?></th>
                <th><?php echo "$" . number_format($detalle['valor_unitario']) ?></td>
                    <?php if (!empty($detalle['notas'])) { ?>
            <tr class="table-success">
                <td>Nota:</td>
                <td><?php echo $detalle['nota_producto']  ?></th>
            </tr>
            </tr>
        <?php } ?>
        </tr>
    <?php } ?>
<?php } ?>
    </tbody>
</table>

<div class="row align-items-start">

    <div class="col">
        <p class="text-end text-dark h2"> Total pedido : <?php echo "$" . number_format($valor_total, 0, ',', '.') ?></p>
    </div>

</div>
<th>Nota pedido:</th>
<td><?php echo $nota_pedido ?></td>