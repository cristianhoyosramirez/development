<!-- <?php foreach ($recetas as $detalle): ?>
  <div class="accordion accordion-flush" id="accordionFlushExample">
    <div class="accordion-item">
      <h2 class="accordion-header" id="flush-headingOne">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
          <?php echo $detalle['codigointernoproducto'] . "-" . $detalle['nombreproducto']; ?>
        </button>
      </h2>
      <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
        <?php $producto = model('categoriasModel')->Recetas($detalle['codigointernoproducto']);  ?>
        <div class="container">
          <?php foreach ($producto as $detalle):   ?>
            <li><?php echo htmlspecialchars($detalle['nombreproducto']); ?></li>
          <?php endforeach ?>
        </div>
      </div>
    </div>
  </div>
<?php endforeach ?> -->

<div class="accordion accordion-flush" id="accordionFlushExample">
  <?php foreach ($recetas as $index => $detalle): ?>
    <div class="accordion-item">
      <h2 class="accordion-header" id="flush-heading<?php echo $index; ?>">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php echo $index; ?>" aria-expanded="false" aria-controls="flush-collapse<?php echo $index; ?>">
          <?php echo $detalle['codigointernoproducto'] . " - " . $detalle['nombreproducto']; ?>
        </button>
      </h2>
      <div id="flush-collapse<?php echo $index; ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?php echo $index; ?>" data-bs-parent="#accordionFlushExample">
        <?php $producto = model('categoriasModel')->Recetas($detalle['codigointernoproducto']); ?>
        <p>Ingredientessssss</p>
        <div class="container">
          <table>
            <thead class="table-dark">
              <tr>
                <td>Código</td>
                <td>Producto</td>
                <td>Cantidad receta</td>
                <td>Costo unidad </td>
                <td>Costo total</td>
              </tr>
            </thead>
          </table>
          <?php foreach ($producto as $item): ?>
            <li><?php echo htmlspecialchars($item['nombreproducto']); ?></li>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>