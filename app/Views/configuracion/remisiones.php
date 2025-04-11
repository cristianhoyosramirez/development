<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
Remisiones
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<div class="container">
    <p class="text-center text-primary h3">Borrar remisiones</p>
    <div class="card">
        <div class="card-body">
            <!-- Agrega un switch -->
            <input type="hidden" value="<?php echo base_url() ?>" id="url">
            <?php if ($remisiones == 'f') :  ?>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="inactivo" onclick="actualizar_remisiones(1)">
                    <label class="form-check-label" for="flexSwitchCheckDefault">Borrar remisiones</label>
                </div>
            <?php endif ?>

            <?php if ($remisiones == 't') : ?>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="activo" checked value="1" onclick="actualizar_remisiones(0)">
                    <label class="form-check-label" for="flexSwitchCheckDefault">Borrar remisiones</label>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<script>
    function actualizar_remisiones(valor) {
        var url = document.getElementById("url").value;

        $.ajax({
            data: {
                valor: valor,

            },
            url: url +
                "/" +
                "configuracion/actualizar_remisiones",
            type: "post",
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
                        title: "Actulización correcta ",
                    })



                }
            },
        });

    }
</script>


<?= $this->endSection('content') ?>