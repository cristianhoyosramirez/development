<style>
    /* Contenedor principal */
    .input-container {
        position: relative;
        display: inline-block;
    }

    /* Lista de sugerencias */
    .input-container .sugerencias-lista {
        list-style-type: none;
        padding: 0;
        margin: 0;
        position: absolute;
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow-y: auto;
        z-index: 1000;
        width: max-content;
        min-width: 200px;
        max-width: 300px;

        /* Ajuste dinámico basado en el número de elementos */
        min-height: 40px;
        /* Altura mínima (para al menos un elemento) */
        max-height: calc(70px * 5);
        /* Máximo 5 elementos visibles antes del scroll */
    }

    /* Estilo de los elementos de la lista */
    .input-container .sugerencias-lista li {
        padding: 10px;
        cursor: pointer;
        border-bottom: 1px solid #ddd;
        transition: background-color 0.3s, color 0.3s;
        white-space: nowrap;
    }

    /* Último elemento sin borde inferior */
    .input-container .sugerencias-lista li:last-child {
        border-bottom: none;
    }

    /* Efecto hover */
    .input-container .sugerencias-lista li:hover {
        background-color: #007bff;
        color: #ffffff;
    }
</style>


<div class="input-container">
    <!-- Lista de sugerencias -->
    <ul id="sugerencias" class="sugerencias-lista">
        <?php foreach ($productos as $keyTercero): ?>
            <li onclick="selectProducto(<?= $keyTercero['codigointernoproducto']; ?>, '<?= htmlspecialchars($keyTercero['nombreproducto'], ENT_QUOTES, 'UTF-8'); ?>')">
                <?= htmlspecialchars($keyTercero['codigointernoproducto'], ENT_QUOTES, 'UTF-8') . ' - ' . htmlspecialchars($keyTercero['nombreproducto'], ENT_QUOTES, 'UTF-8'); ?>
            </li>
        <?php endforeach; ?>
    </ul>

</div>