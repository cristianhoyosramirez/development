<?php
$componentes = model('componentesAtributosProductoModel')->where('id_atributo', $idAtributo)->findAll();
?>

<div class="d-flex flex-wrap gap-2">
    <?php foreach ($componentes as $detalle): ?>
        <span class="badge rounded-pill btn-outline-primary d-flex align-items-center p-3 fs-5"
            style="min-width: 150px; max-width: 250px; justify-content: space-between;"
            id="badge<?php echo $detalle['id']; ?>">
            <?php echo $detalle['nombre']; ?>
            <button type="button" class="btn-close ms-2" aria-label="Close"
                onclick="eliminarBadge(<?php echo $detalle['id']; ?>, '<?php echo $detalle['nombre']; ?>')">
            </button>
        </span>
    <?php endforeach; ?>
</div>