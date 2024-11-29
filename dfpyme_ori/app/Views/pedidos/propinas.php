<?php if (!empty($meseros)) { ?>
  <?php foreach ($meseros as $valor) :

    $nombre_mesero = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $valor['id_mesero'])->first();

    if (empty($nombre_mesero['nombresusuario_sistema'])) {

      $mesero = "Mesero general";
    }
    if (!empty($nombre_mesero['nombresusuario_sistema'])) {

      $mesero = $nombre_mesero['nombresusuario_sistema'];
    }

  ?>

    <?php $facturas = model('FacturaPropinaModel')->get_propinas($id_apertura, $valor['id_mesero']);   #print_r($facturas);  
    ?>

    <?php if (!empty($facturas)) : ?>

      <table class="table table-striped table-hover">
        <thead clas="thead-dark">
          <tr class="table-primary">
            <td>
              <p class="text-dark"><?php echo $mesero;   ?></p>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>



        </thead>
        <tr class="table-dark">
          <td scope="row">Mesa </th>
          <td scope="row">Documento </th>
          <td scope="row">Valor documento </th>
          <td colspan="2">Valor propina</td>

        </tr>
        <tbody>


          <?php foreach ($facturas as $detalle) :  ?>

            <?php
            $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $detalle['id_mesa'])->first();
            if ($detalle['estado'] == 1) {

              $factura = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $detalle['id_factura'])->first();
              $val_factura = model('facturaVentaModel')->select('valor_factura')->where('id', $detalle['id_factura'])->first();
              $numero_factura = $factura['numerofactura_venta'];
              $valor_factura = $val_factura['valor_factura'];
            }
            if ($detalle['estado'] == 8) {

              $factura = model('facturaElectronicaModel')->select('numero')->where('id', $detalle['id_factura'])->first();
              $val_factura = model('facturaElectronicaModel')->select('total')->where('id', $detalle['id_factura'])->first();
              $numero_factura = $factura['numero'];
              $valor_factura = $val_factura['total'];
            }
            ?>

            <tr>
              <td scope="row"><?php echo $nombre_mesa['nombre']
                              ?> </th>
              <td scope="row"><?php echo $numero_factura;
                              ?> </th>
              <td scope="row"><?php echo "$" . number_format($valor_factura, 0, ",", ".")
                              ?> </th>
              <td colspan="2"><?php echo "$" . number_format($detalle['valor_propina'], 0, ",", ".")
                              ?></td>

            </tr>
          <?php endforeach ?>
          <?php  ?>
        </tbody>
        <?php $total = model('facturaPropinaModel')->get_total_propinas($id_apertura, $valor['id_mesero']); ?>
        <?php if ($total[0]['total_propina'] > 0) : ?>
          <tr>
            <td></td>
            <td></td>
            <td></td>


            <td></td>
            <td class="table-warning">Total: <?php echo "$" . number_format($total[0]['total_propina'], 0, ",", ".") ?> </td>
          </tr>
        <?php endif  ?>
      </table>
    <?php endif ?>
  <?php endforeach ?>

<?php } ?>


<?php if (empty($meseros) or $total_propinas==0) { ?>

  <div class="card">
    <div class="card-header text-center">
      <h3 class="card-title text-primary text-center mx-auto w-100">Total propinas</h3>
    </div>
    <div class="card-body">
      <p class="text-center h3">$0</p>
    </div>
  </div>



<?php } ?>