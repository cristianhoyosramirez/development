<style>
    /* Estilo para la lista horizontal */
    .horizontal-list {
        list-style: none;
        padding: 15px;
        display: inline-flex;
        /* Muestra los elementos en una línea horizontal */
        display: flex;
        max-height: 100px;
        /* Altura máxima deseada */
        overflow-x: auto;
        /* Agrega una barra de desplazamiento horizontal si es necesario */
    }

    /* Estilo para los elementos de la lista horizontal */
    .horizontal-list li {
        padding-right: 10px;
        /* Espaciado entre los elementos de la lista */
    }
</style>

<ul class="horizontal-list">
    <?php foreach ($categorias as $detalle) : ?>

        <?php if ($detalle['codigocategoria'] == $id_categoria) :  ?>
            <li><button type="button" class="btn btn-indigo btn-pill btn-sm" id="categoria_<?php echo $detalle['codigocategoria'] ?>" onclick="productos_categoria(<?php echo $detalle['codigocategoria'] ?>)"><?php echo $detalle['nombrecategoria'] ?></button></li>
        <?php endif ?>
        <?php if ($detalle['codigocategoria'] != $id_categoria) :  ?>
            <li><button type="button" class="btn btn-outline-indigo btn-pill btn-sm" id="categoria_<?php echo $detalle['codigocategoria'] ?>" onclick="productos_categoria(<?php echo $detalle['codigocategoria'] ?>)"><?php echo $detalle['nombrecategoria'] ?></button></li>
        <?php endif ?>

    <?php endforeach ?>
</ul>