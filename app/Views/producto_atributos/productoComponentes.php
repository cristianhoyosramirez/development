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

<?php foreach ($idAtributos as $keyAtributo):
    $nombre = model('atributosProductoModel')->select('nombre')->where('id', $keyAtributo['id_atributo'])->first();
    $numeroComponentes = model('configuracionAtributosProductoModel')->getNumeroComponentes($idProducto, $keyAtributo['id_atributo']);
    $id = model('configuracionAtributosProductoModel')->getId($idProducto, $keyAtributo['id_atributo']);
    
?>
    <?php if (!empty($nombre)): ?>



        <p class="text-orange fw-bold"><?php echo $nombre['nombre']; ?></p>

        <?php $componentes = model('componentesAtributosProductoModel')->select('nombre,id')->where('id_atributo', $keyAtributo['id_atributo'])->findAll(); ?>
        <div class="scroll-container">
            <?php foreach ($componentes as $detalleComponentes): ?>
                <button type="button"
                    class="btn btn-outline-primary btn-sm btn-pill btn-ajustable text-truncate"
                    style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                    onclick="seleccionarComponentes('<?php echo $detalleComponentes['nombre']; ?>', <?php echo $detalleComponentes['id']; ?>,<?php $numeroComponentes[0]['numero_componentes']; ?>)">
                    <?php echo htmlspecialchars($detalleComponentes['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                </button>
            <?php endforeach; ?>
        </div>
    <?php else: ?>

    <?php endif; ?>
<?php endforeach ?>
<br>