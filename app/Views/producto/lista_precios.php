
<select class="form-select" id="lista_precios" autofocus>
    <?php foreach ($lista_precios as $detalle) { ?>
        <option value=""></option>
        <option data-precio=1 value="0">P-1 <?php echo "$" . number_format($detalle['precio_al_detal'], 0, ',', '.') ?></option>
        <option data-precio=2 value="1">P-2 <?php echo "$" . number_format($detalle['precio_al_por_mayor'], 0, ',', '.') ?></option>
    <?php } ?>
</select>


