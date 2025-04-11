<select class="form-select" id="valor_iv" name="valor_iva">
    <?php foreach ($iva as $detalle) { ?>
        <option value="<?php echo $detalle['idiva'] ?>"><?php echo $detalle['valoriva'] . "-" . $detalle['conceptoiva'] ?></option>
    <?php } ?>
</select>