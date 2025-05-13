<?= $this->extend('template/template_boletas') ?>
<?= $this->section('title') ?>
Configuración
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="card container">
    <div class="card-body">
        <form action="<?= base_url('mesas/save') ?>" method="POST">
            <input type="hidden" value="<?php echo base_url() ?>" id="url">
            <div class="row">
                <div class="col-md-6">
                    <?php if ($requiere_mesero == 't') :  ?>
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked onclick="actualizar_mesero('false')">
                            <span class="form-check-label">¿Requerir mesero en pedido?</span>
                        </label>
                    <?php endif ?>

                    <?php if ($requiere_mesero == 'f') :  ?>
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" onclick="actualizar_mesero('true')">
                            <span class="form-check-label">¿Requerir mesero en pedido?</span>
                        </label>
                    <?php endif ?>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function actualizar_mesero(valor) {
        var url = document.getElementById("url").value;
        $.ajax({
            data: {
                valor,
            },
            url: url + "/" + "configuracion/actualizar_mesero",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'success',
                        title: 'Cambio exitoso'
                    })

                }
            },
        });

    }
</script>


<?= $this->endSection('content') ?>