<!--   Bases de los impuestos -->
<?php if (!empty($iva)) : ?>
  <?php foreach ($iva as $valor) : ?>
    <?php
    $total_venta = model('kardexModel')->total_iva_fecha($fecha_inicial, $fecha_final, $valor['iva']);
    $total_iva = model('kardexModel')->total_iva_producto($fecha_inicial, $fecha_final, $valor['iva']);
    ?>
    <div class="row">
      <div class="col-8"></div>
      <div class="col-2 text-end">
        <?php echo "Base IVA " . $valor['iva'] . "%" ?>
      </div>
      <div class="col-2 text-end">
        <?php echo "$ "   . number_format($total_venta[0]['total']-$total_iva[0]['iva'], 0, ",", ".") ?>
      </div>
    </div>
  <?php endforeach ?>
<?php endif ?>
<?php if (!empty($inc)) : ?>
  <?php foreach ($inc as $valor) : ?>

    <?php
    $total_venta_inc = model('kardexModel')->total_inc_fecha($fecha_inicial, $fecha_final, $valor['inc']);
    $total_inc = model('kardexModel')->total_inc_producto($fecha_inicial, $fecha_final, $valor['inc']);
    ?>

    <div class="row">
      <div class="col-8"></div>
      <div class="col-2 text-end">
        <?php echo "Base INC " . $valor['inc'] . "%" ?>
      </div>
      <div class="col text-end">
        <?php echo  "$ ".number_format( $total_venta_inc[0]['total']-$total_inc[0]['inc'], 0, ",", ".") ?>
      </div>
    </div>
  <?php endforeach ?>
<?php endif ?>
<!--   Bases de los impuestos -->





<!--    impuestos -->
<?php if (!empty($iva)) : ?>
  <?php foreach ($iva as $valor) : ?>
    <?php
    $total_venta = model('kardexModel')->total_iva_fecha($fecha_inicial, $fecha_final, $valor['iva']);



    ?>
    <div class="row">
      <div class="col-8"></div>
      <div class="col-2 text-end">
        <?php echo "Valor IVA " . $valor['iva'] . "%" ?>
      </div>
      <div class="col-2 text-end">
        <?php echo "$ "   . number_format($total_iva[0]['iva'], 0, ",", ".") ?>
      </div>

    </div>
  <?php endforeach ?>
<?php endif ?>



<?php if (!empty($inc)) : ?>
  <?php foreach ($inc as $valor) : ?>

    <?php
    $total_venta_inc = model('kardexModel')->total_inc_fecha($fecha_inicial, $fecha_final, $valor['inc']);

    ?>

    <div class="row">
      <div class="col-8"></div>
      <div class="col-2 text-end">
        <?php echo "Valor INC " . $valor['inc'] . "%" ?>
      </div>
      <div class="col-2 text-end">
        <?php echo  "$ "   . number_format($total_inc[0]['inc'], 0, ",", ".") ?>
      </div>
    </div>
  <?php endforeach ?>
<?php endif ?>

<div class="row">
  <div class="col-8"></div>
  <div class="col-2 text-end">
    <p class="text-orange h3">Venta total</p>
  </div>
  <div class="col-2 text-end">
    <p class="text-orange h3"><?php echo "$ ".number_format($venta_total, 0, ",", ".")  ?></p>
  </div>
</div>