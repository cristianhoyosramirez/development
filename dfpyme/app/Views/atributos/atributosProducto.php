<style>
    .scroll-container {
        overflow-x: auto;
        /* Habilita el desplazamiento horizontal */
        white-space: nowrap;
        /* Evita que los botones salten de línea */
        display: flex;
        /* Asegura que los botones se alineen en fila */
        gap: 10px;
        /* Espacio entre botones */
        padding-bottom: 10px;
        /* Espacio entre los botones y el scroll */
        margin-bottom: 10px;
        /* Espacio entre los botones y el siguiente contenido */
    }

    .btn-ajustable {
        flex-shrink: 0;
        /* Evita que los botones se reduzcan demasiado */
        width: auto;
        /* Permite que el ancho se adapte al contenido */
        min-width: 120px;
        /* Define un ancho mínimo */
        text-align: center;
        margin-bottom: 5px;
        /* Espacio entre el botón y el scroll */
    }
</style>
<style>
    .atributo-container {
        margin-bottom: 6px;
        /* Reduce la separación entre cada atributo */
    }

    .scroll-container {
        margin-bottom: 4px;
        /* Reduce la separación entre botones y la siguiente sección */
    }

    hr {
        margin: 4px 0;
        /* Reduce el espacio vertical de la línea */
    }
</style>


<?php if (!empty($atributos)): ?>

    <?php foreach ($atributos as $index => $detalle): ?>

        <p class="fw-bold text-orange"><?php echo $detalle['nombre']; ?></p>
        <?php $componentes = model('componentesAtributosProductoModel')->select('nombre,id')->where('id_atributo', $detalle['id'])->orderBy('nombre','asc')->findAll(); ?>

        <div class="scroll-container">
            <?php foreach ($componentes as $detalleComponentes): ?>
                <button type="button"
                    class="btn btn-outline-primary btn-sm btn-pill btn-ajustable text-truncate"
                    style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                    onclick="seleccionarComponente(<?php echo $detalle['id']; ?>, <?php echo $detalleComponentes['id']; ?>, <?php echo $idProducto; ?>)">
                    <?php echo htmlspecialchars($detalleComponentes['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <?php
        $componente = model('configuracionAtributosProductoModel')->getIdComponentes($id_tabla_producto, $detalle['id']);

        ?>
        <div id="componentesDeProducto<?php echo $detalle['id']; ?>">
            <?php foreach ($componente as $keyComponente): ?>
                <button type="button" class="btn btn-success rounded-pill position-relative" id="btnComponente<?php echo $keyComponente['id'] ?>">
                    <?php echo htmlspecialchars($keyComponente['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                    <span class="badge rounded-pill bg-success" onclick="eliminacionComponente(<?php echo $keyComponente['id'] ?>)">
                        <!-- Download SVG icon from http://tabler-icons.io/i/x -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </span>
                </button>
            <?php endforeach; ?>
            <!--  <button type="button" class="btn btn-outline-success rounded-pill position-relative">
            <?php #echo htmlspecialchars($componente['nombre'], ENT_QUOTES, 'UTF-8'); 
            ?>
            <span class="badge rounded-pill bg-success">X</span>
        </button> -->
        </div>

        <hr class="divider">
    <?php endforeach; ?>

<?php endif ?>

<!-- <?php #if (empty($atributos)): ?>
    <p class="text-center text-primary h3">No hay atributos asociados al producto</p>
<?php #endif ?> -->