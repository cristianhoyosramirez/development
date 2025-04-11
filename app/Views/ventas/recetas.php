<div class="accordion accordion-flush" id="accordionFlushExample">
  <?php foreach ($recetas as $index => $detalle): ?>
    <div class="accordion-item">
      <h2 class="accordion-header" id="flush-heading<?php echo $index; ?>">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php echo $index; ?>" aria-expanded="false" aria-controls="flush-collapse<?php echo $index; ?>">
          <?php echo $detalle['codigointernoproducto'] . " - " . $detalle['nombreproducto']; ?>
        </button>
      </h2>
      <div id="flush-collapse<?php echo $index; ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?php echo $index; ?>" data-bs-parent="#accordionFlushExample">
        <?php 
        $producto = model('categoriasModel')->Recetas($detalle['codigointernoproducto']);
        $totalReceta=model('categoriasModel')->getTotalReceta($detalle['codigointernoproducto']);
         ?>
        <p class="text-primary">Ingredientes</p>

        <table class="table table-striped">
          <thead class="table-dark">
            <tr>
              <td>Código</th>
              <td>Producto</th>
              <td>Cantidad receta</th>
              <td>Costo unidad</th>
              <td>Costo total</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($producto as $item): ?>
              <tr>
                <td><?php echo htmlspecialchars($item['codigo']); ?></td>
                <td><?php echo htmlspecialchars($item['nombreproducto']); ?></td>
                <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                <td><?php echo number_format($item['costo_unidad'], 0, '', '.'); ?></td>
                <td><?php echo number_format($item['costo_total'], 0, '', '.'); ?></td>

              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <p class="text-end">Valor total costo: <?php echo number_format($totalReceta[0]['costo'], 0, '', '.');  ?></p>

      </div>
    </div>
  <?php endforeach; ?>
</div>