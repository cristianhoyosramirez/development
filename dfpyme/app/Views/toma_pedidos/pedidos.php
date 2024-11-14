<?php $user_session = session(); ?>
<?= $this->extend('pedidos/template_mesa') ?>
<?= $this->section('title') ?>
Bienvenido DFpyme
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<style>
    /* Estilo para ocultar el texto en dispositivos móviles */
    .mobile-hidden {
        display: none;
    }

    /* Estilo para mostrar el icono en dispositivos móviles */
    .mobile-visible {
        display: inline-block;
    }

    /* Estilo para ocultar el icono en dispositivos de escritorio */
    @media (min-width: 768px) {
        .mobile-hidden {
            display: inline-block;
        }

        .mobile-visible {
            display: none;
        }
    }
</style>

<style>
    /* Estilo para la fila de botones en dispositivos móviles */
    @media (max-width: 767px) {
        .row.mb-2 {
            display: flex;
            flex-wrap: wrap;
        }

        .col-md-4 {
            flex: 0 0 33.33%;
            /* Hace que los botones ocupen el 33.33% del ancho en dispositivos móviles */
            max-width: 33.33%;
        }
    }
</style>



<div class="page">
    <!-- Navbar -->
    <div id="header">
        <?= $this->include('layout/header_mesas') ?>
    </div>
    <div id="header_oculto" class="container" style="display:none">
        <div class="row text-center align-items-center flex-row-reverse">
            <div class="col-lg-auto ms-lg-auto">
                <ul class="list-inline list-inline-dots mb-0">

                    <li class="list-inline-item">
                        <a class=" cursor-pointer" onclick="mostrar_menu()" title="Mostar menu" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-toggle="tooltip" data-bs-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <line x1="3" y1="3" x2="21" y2="21" />
                                <path d="M10.584 10.587a2 2 0 0 0 2.828 2.83" />
                                <path d="M9.363 5.365a9.466 9.466 0 0 1 2.637 -.365c4 0 7.333 2.333 10 7c-.778 1.361 -1.612 2.524 -2.503 3.488m-2.14 1.861c-1.631 1.1 -3.415 1.651 -5.357 1.651c-4 0 -7.333 -2.333 -10 -7c1.369 -2.395 2.913 -4.175 4.632 -5.341" />
                            </svg></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-wrapper">
        <!-- Page body -->
        <div class="div"></div>
        <input type="hidden" value="<?php echo base_url() ?>" id="url">
        <input type="hidden" value="<?php echo $user_session->id_usuario ?>" id="id_usuario">
        <input type="hidden" value="<?php echo $requiere_mesero ?>" id="requiere_mesero" name="requiere_mesero">
        <input type="hidden" value="<?php echo $user_session->tipo ?>" id="tipo_usuario" name="tipo_usuario">
        <input type="hidden" id="mesero" name="mesero">
        <input type="hidden" id="tipo_pedido" name="tipo_pedido" value="movil">
        <div class="container-fluid">
            <div class="row row-deck row-cards">
                <div class="col-md-12 col-xl-12">
                    <div class="card">
                        <ul class="nav nav-tabs" data-bs-toggle="tabs">

                            <li class="nav-item">
                                <a href="#" class="nav-link" data-bs-toggle="offcanvas" data-bs-target="#mesasOffcanvas" onclick="listado_mesas()"><!-- Download SVG icon from http://tabler-icons.io/i/arrows-maximize -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <polyline points="16 4 20 4 20 8" />
                                        <line x1="14" y1="10" x2="20" y2="4" />
                                        <polyline points="8 20 4 20 4 16" />
                                        <line x1="4" y1="20" x2="10" y2="14" />
                                        <polyline points="16 20 20 20 20 16" />
                                        <line x1="14" y1="14" x2="20" y2="20" />
                                        <polyline points="8 4 4 4 4 8" />
                                        <line x1="4" y1="4" x2="10" y2="10" />
                                    </svg></a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link " data-bs-toggle="tab" onclick="get_mesas()">TODAS</a>
                            </li>
                            <?php foreach ($salones as $detalle) : ?>

                                <li class="nav-item">
                                    <a href="#tabs-home-7" class="nav-link" data-bs-toggle="tab" onclick="mesas_salon(<?php echo $detalle['id'] ?>)"><?php echo $detalle['nombre'] ?> </a>
                                </li>

                            <?php endforeach ?>
                            <li class="nav-item ms-auto">
                                <a href="#tabs-settings-7" class="nav-link" title="Settings" data-bs-toggle="tab"><!-- Download SVG icon from http://tabler-icons.io/i/settings -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active show">


                                    <div style="display: block" id="todas_las_mesas">


                                    
                                            <div id="lista_completa_mesas">
                                                <?= $this->include('pedidos/todas_las_mesas_lista') ?>
                                            </div>
                                    

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php $alturaCalc = "37rem + 10px"; // Calcula la altura 
                ?>


                <div class="col-12 col-sm-12 col-md-8 col-xl-8" id="productos" style="display: block">
                    <input type="hidden" id="id_mesa_pedido">
                    <div class="card" style="height: calc(<?php echo $alturaCalc; ?>)">
                        <div class="card-header border-1" style="margin-bottom: -10px; padding-bottom: 0;">
                            <div class="card-title">
                                <div class="row align-items-start">
                                    <div class="col-6">
                                        <p id="mesa_pedido" class="text-warning "> Mesa:</p>
                                    </div>
                                   <!--  <div class="col-3">
                                        <p id="pedido_mesa">Pedio: </p>
                                    </div>
                                    <div class="col-3">
                                        <p id="nombre_mesero" class="cursor-pointer text-primary" onclick="cambiar_mesero()" title="Cambiar de mesero " data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-toggle="tooltip" data-bs-placement="bottom">Mesero </p>
                                    </div> -->
                                    <div class="col-4 text-end">
                                        <a class="btn btn-outline-indigo " href="#" onclick="validarInputYAbrirOffcanvas()" role="button" w-100>

                                          <!--   <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M3 14c.83 .642 2.077 1.017 3.5 1c1.423 .017 2.67 -.358 3.5 -1c.83 -.642 2.077 -1.017 3.5 -1c1.423 -.017 2.67 .358 3.5 1" />
                                                <path d="M8 3a2.4 2.4 0 0 0 -1 2a2.4 2.4 0 0 0 1 2" />
                                                <path d="M12 3a2.4 2.4 0 0 0 -1 2a2.4 2.4 0 0 0 1 2" />
                                                <path d="M3 10h14v5a6 6 0 0 1 -6 6h-2a6 6 0 0 1 -6 -6v-5z" />
                                                <path d="M16.746 16.726a3 3 0 1 0 .252 -5.555" />
                                            </svg> -->Adicionar productos
                                        </a>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="card-body card-body-scrollable card-body-scrollable-shadow">

                            <div id="mesa_productos"></div>

                        </div>

                        <div class="container">

                            <div class="row"> <!-- Fila para el textarea -->
                                <div class="col-md-12 mb-2">
                                    <textarea class="form-control" rows="1" id="nota_pedido" onkeyup="insertarDatos(this.value)" placeholder="Nota general del pedido "></textarea>
                                </div>
                            </div>


                            <div class="row gy-2"> <!-- Fila para los botones -->
                                <div class="col-md col-sm col-6 ">
                                    <a href="#" class="btn btn-outline-indigo w-100" onclick="cambiar_mesas()">
                                        <span class="mobile-hidden">Cambio de mesa</span>
                                        <span class="mobile-visible"><!-- Download SVG icon from http://tabler-icons.io/i/refresh 
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                            </svg>--> Cambio mesa </span>
                                    </a>
                                </div>
                                <div class="col-md col-sm col-6">
                                    <a href="#" class="btn btn-outline-purple w-100" onclick="imprimir_comanda()">
                                        <span class="mobile-hidden">Comanda</span>
                                        <span class="mobile-visible"><!-- Download SVG icon from http://tabler-icons.io/i/file-invoice 
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                                <line x1="9" y1="7" x2="10" y2="7" />
                                                <line x1="9" y1="13" x2="15" y2="13" />
                                                <line x1="13" y1="17" x2="15" y2="17" />
                                            </svg> --> Comanda </span>
                                    </a>
                                </div>
                                <div class="col-md col-sm col-6">
                                    <a href="#" class="btn btn-outline-red w-100" onclick="eliminar_pedido()">
                                        <span class="mobile-hidden">Eliminar pedido</span>
                                        <span class="mobile-visible"><!-- Download SVG icon from http://tabler-icons.io/i/trash 
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <line x1="4" y1="7" x2="20" y2="7" />
                                                <line x1="10" y1="11" x2="10" y2="17" />
                                                <line x1="14" y1="11" x2="14" y2="17" />
                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                            </svg> --> Eliminar pedido </span>
                                    </a>
                                </div>
                                <div class="col-sm-3 d-md-none d-sm-block col-6">

                                    <button class="btn btn-outline-azure w-100" type="button" id="valor_pedido" onclick="valores_pedido()">
                                        $ 0
                                    </button>
                                </div>



                            </div>

                        </div>
                    </div>
                </div>



                <!--valor Pedido-->
                <div class="col-12 d-sm-none d-md-block col-md-4 col-xl-4 d-none ">

                    <div class="card " style="height: calc(20rem + 10px)">
                        <div class="card-header border-1" style="margin-bottom: -10px; padding-bottom: 0;">
                            <div class="card-title">
                                <div class="row align-items-start">
                                    <div class="col">
                                        <p id="pedido_mesa">Valor pedido </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body ">

                            <form>
                                <div class="row mb-3">

                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="subtotal_pedido" disabled="">
                                    </div>
                                </div>

                                <div class="row mb-2 gy-2">
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <!-- 
                        <select class="form-select" aria-label="Default select example" id="criterio_propina" style="width: 90px;">
                            <option value="1">Propina %</option>
                            <option value="2">Propina $</option>

                        </select> -->



                                            <a href="#" class="btn btn-outline-green  col-sm-4" onclick="calculo_propina()" title="Propina" style="width: 100px;" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-toggle="tooltip" data-bs-placement="bottom"> <!-- Download SVG icon from http://tabler-icons.io/i/mood-happy -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <circle cx="12" cy="12" r="9" />
                                                    <line x1="9" y1="9" x2="9.01" y2="9" />
                                                    <line x1="15" y1="9" x2="15.01" y2="9" />
                                                    <path d="M8 13a4 4 0 1 0 8 0m0 0h-8" />
                                                </svg></a>


                                            <input type="text" aria-label="Last name" class="form-control w-1" style="width: 50px;" value=0 onkeyup="calcular_propina(this.value)" id="propina_pesos" placeholder="%">
                                            <input type="text" aria-label="Last name" class="form-control" style="width: 50px;" id="propina_del_pedido" name="propina_del_pedido" onkeyup="total_pedido(this.value)" value=0 placeholder="$">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">

                                    <div class="col-sm-12">
                                        <a href="#" class="btn btn-outline-azure w-100 h2" id="val_pedido" onclick="prefactura()" title="Imprimir prefactura" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                            $ 0
                                        </a>

                                    </div>
                                </div>

                            </form>

                        </div>

                    </div>
                </div>


                <!--partida-->
            </div>
        </div>









        <!-- Gestion pedido  -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasRightLabel">Valor pedido </h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
            </div>
            <div class="offcanvas-body">
                <!-- Contenido del offcanvas -->
                <div class="car " style="height: calc(20rem + 10px)">
                    <div class="card-heade border-1" style="margin-bottom: -10px; padding-bottom: 0;">
                        <div class="card-title">
                            <div class="row align-items-start">
                                <div class="col">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body ">

                        <form>
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">Subtotal</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="subtotal_movil" disabled="">
                                </div>
                            </div>

                            <div class="row mb-2 gy-2">
                                <div class="col-sm-12">
                                    <div class="input-group">
                       



                                        <a href="#" class="btn btn-outline-green  col-sm-4" onclick="calculo_propina_movil()" title="Propina" style="width: 100px;" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-toggle="tooltip" data-bs-placement="bottom"> <!-- Download SVG icon from http://tabler-icons.io/i/mood-happy -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <circle cx="12" cy="12" r="9" />
                                                <line x1="9" y1="9" x2="9.01" y2="9" />
                                                <line x1="15" y1="9" x2="15.01" y2="9" />
                                                <path d="M8 13a4 4 0 1 0 8 0m0 0h-8" />
                                            </svg></a>


                                        <input type="text" aria-label="Last name" class="form-control w-1" style="width: 50px;" value=0 onkeyup="calcular_propina_movil(this.value)" id="propina_pesos" placeholder="%">
                                        <input type="text" aria-label="Last name" class="form-control" style="width: 50px;" id="propina_movil" name="propina_movil" onkeyup="total_pedido(this.value)" value=0 placeholder="$">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword3" class="col-sm-4 col-form-label  h2">Prefactura </label>
                                <div class="col-sm-8">
                                    <a href="#" class="btn btn-outline-azure w-100 h2" id="total_movil" onclick="prefactura()" title="Pagar" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                        $ 0
                                    </a>

                                </div>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>





    </div>
</div>


<script>
    function validarInputYAbrirOffcanvas() {
        var inputValue = document.getElementById("id_mesa_pedido").value;

        if (inputValue.trim() !== "") {
            // El input tiene un valor válido, entonces abre el offcanvas
            var offcanvas = new bootstrap.Offcanvas(document.getElementById("offcanvasExample"));
            offcanvas.show();
        } else {
            // El input no tiene un valor válido, puedes mostrar un mensaje de error o realizar alguna otra acción
            sweet_alert('warning', 'No hay mesa seleccionada')
        }
    }
</script>



<script>
    function valores_pedido() {
        var url = document.getElementById("url").value;
        let id_mesa = document.getElementById("id_mesa_pedido").value;


        // $('#offcanvasRight').modal('hide');

        if (id_mesa == "") {
            sweet_alert('warning', 'No hay mesa seleccionada')
        } else if (id_mesa != "") {

            $.ajax({
                data: {
                    id_mesa
                },
                url: url + "/" + "eventos/valor",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        $('#offcanvasRight').offcanvas('show');
                        $('#propina_movil').val(resultado.propina)
                        $('#subtotal_movil').val(resultado.sub_total)
                        $('#total_movil').html(resultado.total)
                        /*  $('#canva_producto').html(resultado.productos)

                         var categoria = document.getElementById("productos_categoria");
                         categoria.style.display = "none";

                         var productos = document.getElementById("canva_producto");
                         productos.style.display = "block"; */


                    }
                },
            });
        }
    }
</script>





<?= $this->endSection('content') ?>