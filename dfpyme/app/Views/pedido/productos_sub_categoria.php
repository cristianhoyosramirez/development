<div class="row">
    
    <?php foreach ($productos as $valor) : ?>


        <!--   <div class="col-12 col-md-4 col-lg-4 mb-4"> -->
        <div class="col-12">

            <!-- <div class="cursor-pointer  elemento " onclick="agregar_al_pedido(<?php echo $valor['codigointernoproducto'] ?>)"> -->
            <div class="cursor-pointer  elemento " onclick="agregarProductoPedido(<?php echo $valor['codigointernoproducto'] ?>,'<?php echo $valor['nombreproducto'] ?>',<?php echo $valor['id'] ?>,<?php echo $valor['valorventaproducto'] ?>)">
            
                <div class="row">

                    <div class="col" title="<?php echo $valor['nombreproducto'] ?>">
                        <div class="text-truncate">
                            <strong><?php echo $valor['nombreproducto'] ?></strong>
                        </div>
                        <div class="text-muted"><?php echo "$" . number_format($valor['valorventaproducto'], 0, ",", ".") ?></div>
                    </div>
                </div>
                <hr class="my-1"> <!-- Línea de separación -->
                <br>
            </div>
            <br>

        </div>

    <?php endforeach; ?>
</div>