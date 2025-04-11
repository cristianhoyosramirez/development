<div>

    <div class="row ">
        <div class="col-md-4 col-sm-4 col-12">
            Producto: <?php echo $producto[0]['nombreproducto'] ?>
        </div>
        <div class="col-md-3 col-sm-3 col-12">
            Valor unitario: <?php echo "$" . number_format($producto[0]['valor_unitario'], 0, ',', '.') ?>
        </div>
        <div class="col-md-2 col-sm-2 col-12 ">
            Cantidad: <?php echo $producto[0]['cantidad_producto'] ?>
        </div>
        <div class="col-md-3 col-sm-3 col-12 ">
            Total: <?php echo "$" . number_format($producto[0]['valor_total'], 0, ',', '.') ?>
        </div>
    </div>

</div>
<?php if (!empty($producto[0]['nota_producto'])) { ?>
    <div class="row">
        <div class="col">
            Nota: <?php echo $producto[0]['nota_producto'] ?>
        </div>
    </div>
<?php } ?>

</div>