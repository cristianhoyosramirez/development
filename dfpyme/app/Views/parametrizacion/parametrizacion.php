<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container">
    <input type="hidden" value="<?php echo base_url() ?>" id="url">
    <div class="row">
        <div class="col-4">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#conf_ped" aria-expanded="false" aria-controls="collapseThree">
                            Configuración pedido
                        </button>
                    </h2>
                    <div id="conf_ped" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <label for="" class="form-label">Mostrar código en pantalla</label>
                            <select class="form-select" aria-label="Default select example" onchange="actualizar_codigo(this.value)">
                                <option value="true" <?= $codigo_pantalla == "t" ? 'selected' : '' ?>>Si</option>
                                <option value="false" <?= $codigo_pantalla == "f" ? 'selected' : '' ?>>No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Altura de pantalla
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <label for="altura_de_mesa" class="form-label">Altura de la pantalla</label>
                                        <input type="text" class="form-control" id="altura_de_pantalla" name="altura_de_pantalla" value="<?php echo $altura ?>">
                                    </div>
                                    <div class="ms-3"><br>
                                        <button type="button" class="btn btn-outline-primary" onclick="actualizar_altura()">Actualizar</button>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function actualizar_codigo(opcion) {
        let url = document.getElementById("url").value;
        $.ajax({
            data: {
                opcion,
            },
            url: url + "/" + "actualizacion/actualizar_codigo",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-start',
                        showConfirmButton: false,
                        timer: 900,
                        timerProgressBar: false,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'Visualización de código en pantalla '
                    });

                }
            },
        });
    }
</script>

<script>
    function actualizar_altura() {
        let url = document.getElementById("url").value;
        let altura = document.getElementById("altura_de_pantalla").value;
        $.ajax({
            data: {
                altura,
            },
            url: url + "/" + "actualizacion/actualizar_altura",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-start',
                        showConfirmButton: false,
                        timer: 900,
                        timerProgressBar: false,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'Altura de pantalla actualizada  '
                    });

                }
            },
        });
    }
</script>

<?= $this->endSection('content') ?>