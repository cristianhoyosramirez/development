<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/template_boletas') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>




<div class="container">
    <p class="text-center text-primary h3">Configuración de inventario </p>

    <form action="<?php echo base_url() ?>/configuracion/actualizar_caja" method="post">
        <input type="hidden" name="url" id="url" value="<?php echo base_url() ?>">
        <div class="row g-3">
            <div class="col-md-3">
            <?php if ($sub_categoria == 't') :  ?>
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked onclick="actualizar_sub_categoria('false')">
                            <span class="form-check-label">¿Asignacion de sub categoria a producto?</span>
                        </label>
                    <?php endif ?>

                    <?php if ($sub_categoria == 'f') :  ?>
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" onclick="actualizar_sub_categoria('true')">
                            <span class="form-check-label">¿Asignacion de sub categoria a producto?</span>
                        </label>
                    <?php endif ?>
            </div>

        </div>

       
    </form>

</div>

<script>
    function actualizar_sub_categoria(valor) {
        var url = document.getElementById("url").value;
        $.ajax({
            data: {
                valor,
            },
            url: url + "/" + "configuracion/actualizar_estado_sub_categoria",
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