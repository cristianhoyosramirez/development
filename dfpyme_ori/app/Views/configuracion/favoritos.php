<?= $this->extend('template/template_boletas') ?>
<?= $this->section('title') ?>
Configuración
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<p class="text-center h3 text-primary">Permitir productos favoritos </p><br>
<div class="card container">
    <input type="hidden" value="<?php echo base_url() ?>" id="url">


    <div class="card-body">

        <?php if ($favorito == "f") : ?>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" onclick="actualizar_comanda()" value="false">
                <label class="form-check-label" for="flexSwitchCheckDefault">No</label>
            </div>
        <?php endif ?>
        <?php if ($favorito == "t") : ?>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked onclick="actualizar_comanda()" value="true">
                <label class="form-check-label" for="flexSwitchCheckDefault">si </label>
            </div>
        <?php endif ?>

    </div>
</div>

<script>
    const switchInput = document.getElementById('flexSwitchCheckDefault');
    const label = document.querySelector('.form-check-label');

    switchInput.addEventListener('change', function() {
        if (this.checked) {
            label.textContent = 'Sí';
            this.value = true;
        } else {
            label.textContent = 'No';
            this.value = false;
        }
    });
</script>


<script>
    function actualizar_comanda() {

        const temp_valor = document.getElementById('flexSwitchCheckDefault').value;

        

        if (temp_valor == "false") {
            valor = "true"
        }

        if (temp_valor == "true") {
            valor = "false"
        }


        let url = document.getElementById("url").value;

        $.ajax({
            data: {
                valor
            },
            url: url + "/" + "configuracion/actualizar_favorito",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {
                    // Aquí puedes agregar lo que necesitas hacer si resultado.resultado es 1

                    sweet_alert_start('success', ' Configuración de productos favoritos correcta   ');


                }
            },
        });

    }
</script>


<?= $this->endSection('content') ?>