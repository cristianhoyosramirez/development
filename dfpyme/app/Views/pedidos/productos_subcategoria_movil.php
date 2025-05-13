

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
Subcategorias
<ul class="horizontal-list">
    
    <?php foreach ($id_sub_categoria as $detalle) : ?>

        
            <li><button type="button" class="btn btn-outline-indigo btn-pill btn-sm" id="#" onclick="productos_subcategoria(<?php echo $detalle['id'] ?>)"><?php $nombre_subcategoria = model('subCategoriaModel')->select('nombre')->where('id', $detalle['id'])->first();
                    echo $nombre_subcategoria['nombre']
                    ?></button></li>
    
       
    

    <?php endforeach ?>
</ul>