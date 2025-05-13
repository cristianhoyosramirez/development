<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/template_boletas') ?>
<?= $this->section('title') ?>
Eventos
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="card container">
    <div class="card-body">
        <form action="<?= base_url('eventos/set_boletas') ?>" method="POST">
            <input value="<?php echo base_url() ?>" id="url" name="url" type="hidden">
            <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" name="id_usuario">
            <div class="row">
                <div class="col-md-4">
                    <label for="inputEmail4" class="form-label">Cliente </label>
                    <div class="input-group input-group-flat">
                        <input type="hidden" id="id_cliente_factura_pos" name="id_cliente_factura_pos">
                        <input type="text" class="form-control" autocomplete="off" id="clientes_factura_pos" name="clientes_factura_pos" placeholder="Buscar por nombre o identificación" autofocus>
                        <span class="input-group-text">
                            <a href="#" class="link-secondary" title="Limpiar búsqueda" data-bs-toggle="tooltip" onclick="limpiarInput()"><!-- Download SVG icon from http://tabler-icons.io/i/x -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M18 6l-12 12" />
                                    <path d="M6 6l12 12" />
                                </svg>
                            </a>
                            <a href="#" class="link-secondary ms-2" title="Nuevo cliente" data-bs-toggle="tooltip" onclick="creacion_cliente()"><!-- Download SVG icon from http://tabler-icons.io/i/adjustments -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M8 7a4 4 0 1 0 8 0 4 4 0 0 0-8 0"></path>
                                    <path d="M16 19h6"></path>
                                    <path d="M19 16v6"></path>
                                    <path d="M6 21v-2a4 4 0 0 1 4-4h4"></path>
                                </svg>
                            </a>

                        </span>
                    </div>
                    <div class="text-danger"><?= session('errors.clientes_factura_pos') ?></div>
                </div>

                <div class="col-md-4">
                    <label for="inputEmail4" class="form-label">Localidad</label>
                    <select class="form-select" aria-label="Default select example" name="localidad" id="localidad">

                        <?php foreach ($localidad as $valor) : ?>
                            <option value="<?php echo $valor['id'] ?>"><?php echo $valor['nombre'] ?></option>
                        <?php endforeach ?>

                    </select>
                    <div class="text-danger"><?= session('errors.localidad') ?></div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-md"><i class="mdi mdi-plus"></i> Generar boleta </button>
            </div>
        </form>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="crear_cliente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Crear cliente</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3">
                    <input type="hidden" id="url" name="url" value="<?php echo base_url() ?>">
                    <div class="col-md-4">
                        <label for="inputEmail4" class="form-label">Cédula </label>
                        <input type="text" class="form-control" id="cedula" name="cedula">
                    </div>
                    <div class="col-md-4">
                        <label for="inputPassword4" class="form-label">Nombre </label>
                        <input type="text" class="form-control" id="nombre" name="nombre">
                    </div>
                    <div class="col-md-4">
                        <label for="inputPassword4" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="guardar_cliente()">Crear</button>
                <button type="button" class="btn btn-red">Cancelar </button>
            </div>
        </div>
    </div>
</div>


<script>
    function creacion_cliente() {
        $("#crear_cliente").modal("show");
    }
</script>

<script>
    function guardar_cliente() {
        let url = document.getElementById("url").value;
        let nombre = document.getElementById("nombre").value;
        let cedula = document.getElementById("cedula").value;
        let telefono = document.getElementById("telefono").value;


        $.ajax({
            data: {
                nombre,
                cedula,
                telefono

            },
            url: url + "/" + "eventos/cliente",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);

                if (resultado.resultado == 1) {

                    $("#crear_cliente").modal("hide");
                    $('#clientes_factura_pos').val(resultado.nit_cliente + " /" + resultado.nombre_cliente)
                    $('#id_cliente_factura_pos').val(resultado.nit_cliente)
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
                        title: 'Cliente creado'
                    })
                }
            },
        });



    }
</script>



<script>
    function limpiarInput() {
        // Obtener el elemento input por su ID
        var id_cliente = document.getElementById("id_cliente_factura_pos");
        // Limpiar el contenido del input
        id_cliente.value = "";

        var cliente = document.getElementById("clientes_factura_pos");
        // Limpiar el contenido del input
        cliente.value = "";
    }
</script>

<script>
    var input1 = document.getElementById("id_cliente_factura_pos");
    var input2 = document.getElementById("clientes_factura_pos");

    document.getElementById("clientes_factura_pos").addEventListener("keydown", function(event) {
        // Verificar si la tecla presionada es la tecla "BACKSPACE" (código 8)
        if (event.keyCode === 8) {
            // Limpiar el contenido del input
            this.value = "";
        }
    });
</script>










<?= $this->endSection('content') ?>