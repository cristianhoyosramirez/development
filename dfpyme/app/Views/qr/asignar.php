<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>


<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-center">URL de conexión para dispositivos móviles</h3>
                </div>

                <div class="card-body">

                    <input type="hidden" value="<?php echo base_url(); ?>" id="url">

                    <input type="text" class="form-control" id="actualizar_url" name="actualizar_url"
                        placeholder="Escribe la url completa para conectar los teléfonos"
                        value="<?php echo $url; ?>"
                        focus>
                </div>
                <!-- Card footer -->
                <div class="card-footer">
                    <a href="#" class="btn btn-outline-success" onclick="actualizar()">Actualizar</a>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    document.getElementById("actualizar_url").focus();
</script>
<script>
    function actualizar() {
        var url = document.getElementById("url").value;
        var url_conexion = document.getElementById("actualizar_url").value;
        $.ajax({
            data: {
                url_conexion
            },
            url: url +
                "/" +
                "configuracion/update_url",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    sweet_alert_start('success', 'URL actualizada  ');

                }
            },
        });
    }
</script>
<?= $this->endSection('content') ?>