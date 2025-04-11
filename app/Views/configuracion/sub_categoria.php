<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/template_boletas') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-outline-orange" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Agregar sub categoria
            </button>
        </div>

        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">LISTA CATEGORIAS Y SUB CATEGORIAS </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>



<div class="container">
    <br>
    <div id="all_subcategorias">
        <table class="table">
            <input type="hidden" value="<?php echo base_url() ?>" id="url">
            <thead class="table-dark">
                <tr>
                    <td scope="col">Nombre</th>
                    <td scope="col">Acción</th>

                </tr>
            </thead>
            <tbody>
                 
            
                <?php foreach ($id_categorias as $detalle) {  
                    $sub_categorias = model('subCategoriaModel')->where('id_categoria', $detalle['id_categoria'])->findAll();
                    $nombre_categoria = model('categoriasModel')->select('nombrecategoria')->where('id', $detalle['id_categoria'])->first();
                ?>

                    <tr class="table-primary">

                        <td><?php echo " Categoria: " . $nombre_categoria['nombrecategoria']  ?></td>
                        <td></td>
                    </tr>

                    <?php foreach ($sub_categorias as $valor_sub) : ?>
                        <tr>

                            <td><?php echo $valor_sub['nombre']?></td>
                            <td><button type="button" class="btn btn-outline-success" onclick="editar_categoria(<?php echo $valor_sub['id'] ?>)">
                                    Editar
                                </button>
                                <button type="button" class="btn btn-outline-danger" onclick="eliminar_categoria(<?php echo $valor_sub['id'] ?>)">
                                    Eliminar
                                </button>
                            </td>

                        </tr>
                    <?php endforeach ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">
                    Agregar sub categoria
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="operaciones">
                    <form class="row g-3" action="<?= base_url('categoria/sub_categoria') ?>" method="POST" id="crear_categoria">
                        <div class="row">



                            <div class="col">
                                <label for="" class="form-label">Categoria </label>
                                <select class="form-select" aria-label="Default select example" id="categoria" name="categoria">
                                    <?php foreach ($categorias as $detalle) : ?>
                                        <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['nombrecategoria'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Nombre sub categoria </label>
                                <input type="text" class="form-control" name="nombre_categoria" id="nombre_categoria" required>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Guardar</button>
                            <button type="button" class="btn btn-red" data-bs-dismiss="modal">Cancelar </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>



<script>
    function editar_categoria(valor) {
        var url = document.getElementById("url").value;
        $.ajax({
            data: {
                valor,
            },
            url: url + "/" + "configuracion/editar_sub_categoria",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    $('#operaciones').html(resultado.subcategoria)
                    $('#staticBackdropLabel').html('Edtar subcategoria ')
                    $("#staticBackdrop").modal("show");

                }
            },
        });

    }
</script>
<script>
    function actualizar_categoria(valor) {
        var url = document.getElementById("url").value;
        var nombre = document.getElementById("nombre_categoria").value;
        var categoria = document.getElementById("nombre_categoria_edicion").value;

        $.ajax({
            data: {
                valor,
                nombre,
                categoria
            },
            url: url + "/" + "configuracion/actualizar_sub_categoria",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    $("#all_subcategorias").html(resultado.subcategorias)
                    $("#staticBackdrop").modal("hide");

                }
            },
        });

    }
</script>

<!-- <script>
    function eliminar_categoria(valor) {
        var url = document.getElementById("url").value;
        
        $.ajax({
            data: {
                valor
            },
            url: url + "/" + "configuracion/eliminar_sub_categoria",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    $("#all_subcategorias").html(resultado.subcategorias)
                    $("#staticBackdrop").modal("hide");

                }
            },
        });

    }
</script> -->
<script>
    function eliminar_categoria(valor) {
        var url = document.getElementById("url").value;

        Swal.fire({
            title: "Esta seguro de eliminar la subcategoria?",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: "Aceptar",
            denyButtonText: `Cancelar`,
            icon: 'question'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    data: {
                        valor
                    },
                    url: url + "/" + "configuracion/eliminar_sub_categoria",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);
                        if (resultado.resultado == 1) {


                            $("#all_subcategorias").html(resultado.subcategorias)

                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-start',
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
                                title: 'Borrado exitoso '
                            })

                        }
                    },
                });
            } else if (result.isDenied) {
                Swal.fire("Changes are not saved", "", "info");
            }
        });

    }
</script>

<?= $this->endSection('content') ?>