<!--<label> LISTA PRECIOS </label>-->
<select class="form-select" id="select_lista_precios_pedido">
    <?php foreach ($lista_precios as $detalle) { ?>
        <option value=""></option>
        <option  value="0">P-1 <?php echo "$" . number_format($detalle['precio_al_detal'], 0, ',', '.') ?></option>
        <option  value="1">P-2 <?php echo "$" . number_format($detalle['precio_al_por_mayor'], 0, ',', '.') ?></option>
    <?php } ?>
</select>


