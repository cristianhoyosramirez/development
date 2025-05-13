<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
Impuestos
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container">
    <input type="hidden" value="<?php echo base_url() ?>" id="url">

    <?php $impuesto = model('configuracionPedidoModel')->select('impuesto')->first();   ?>

    <?php if ($impuesto['impuesto'] == 't'):  ?>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked value="1" onclick="cambiar_tributo_producto(this.value)">
            <label class="form-check-label" for="flexSwitchCheckChecked">Productos tienen impuestos </label>
        </div>
    <?php endif ?>
    <?php if ($impuesto['impuesto'] == 'f'):  ?>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" value="0" onclick="cambiar_tributo_producto(this.value)">
            <label class="form-check-label" for="flexSwitchCheckChecked">Productos tienen impuestos </label>
        </div>
    <?php endif ?>

</div>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_start.js"></script>
<script>
    function cambiar_tributo_producto(opcion) {

        let url = document.getElementById("url").value;
        $.ajax({
            data: {
                opcion
            },
            url: url + "/" + "configuracion/actualizar_impuestos",
            type: "get",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);

                if (resultado.resultado == 1) {

                    sweet_alert_start('success', 'Actualizacion de tributos correcta   ');

                }

            },

        });

    }
</script>
<?= $this->endSection('content') ?>