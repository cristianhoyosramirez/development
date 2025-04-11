<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/SalonMesa') ?>
<?= $this->section('title') ?>
SALONES
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>
<style>
    .contenedor {
        height: 55vh;
        overflow: scroll;
    }

    .contenedorProductos {
        height: 30vh;
        overflow: scroll;
    }

    .div {
        background-color: #FBD603;
    }

    .boton-subir {
        position: fixed;
        right: 5%;
        bottom: 10px;
        background-color: #17181C;
        padding: 20px;
        display: none;
        opacity: 0.7
    }

    .boton-subir a {
        font-size: 24px;
        color: #fff;

    }


    .otp-input-wrapper {
        width: 240px;
        text-align: center;
        display: inline-block;
    }

    .otp-input-wrapper input {
        padding: 0;
        width: 264px;
        font-size: 32px;
        font-weight: 600;
        color: #3e3e3e;
        background-color: transparent;
        border: 0;
        margin-left: 12px;
        letter-spacing: 48px;
        font-family: sans-serif !important;
    }

    .otp-input-wrapper input:focus {
        box-shadow: none;
        outline: none;
    }

    .otp-input-wrapper svg {
        position: relative;
        display: block;
        width: 240px;
        height: 2px;
    }
</style>
<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>

<div class="card container">
    <div class="card-body">
        <input type="hidden" value="<?= base_url() ?>" id="url">
        <div class="row" id="mesa_directa">
            <?php foreach ($mesas as $detalle) { ?>
                <div class="col-sm-4 col-md-3 col-xs-6">
                    <!-- La mesa no tiene pedido -->
                    <?php $tiene_pedido = model('pedidoModel')->pedido_mesa($detalle['id']); ?>



                    <?php if (empty($tiene_pedido)) : ?>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="cursor-pointer">
                                        <div class="text-white bg-green-lt border border-4 rounded container " onClick="mesa_pedido_directo(<?php echo $detalle['id'] ?>);">
                                            <div class="row">
                                                <p class=" text-center text-white-90"> Mesa: <span id="nombre_mesa"><?php echo $detalle['nombre'] ?></span></p>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <img src="<?php echo base_url(); ?>/Assets/img/libre.png" height="5" class="img-fluid mx-auto d-block cursor-pointer w-20">
                                                </div>
                                            </div><br>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?php endif ?>



                    <?php if (!empty($tiene_pedido)) { ?>

                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="cursor-pointer">

                                        <div class="text-white bg-red-lt border border-4 rounded container" onClick="mesa_pedido_directo(<?php echo $detalle['id'] ?>);">
                                            <div class="row">
                                                <p class=" text-center text-white-90"> Mesa: <span id="nombre_mesa"><?php echo $detalle['nombre'] ?></span></p>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <img src="<?php echo base_url(); ?>/Assets/img/libre.png" height="5" class="img-fluid mx-auto d-block cursor-pointer w-20">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <span class="text-center "> Mesero: <span id="nombre_mesa"><?php #echo $detalle['nombresusuario_sistema'] 
                                                                                                            ?></span></span>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <a title="Imprimir prefactura" class="btn bg-cyan text-white w-100 btn-icon" onclick="impresion_prefactura(event,<?php echo $detalle['id'] ?>)">
                                                        Prefactura
                                                    </a>
                                                </div>
                                                <?php $administrador = model('usuariosModel')->select('idtipo')->where('idusuario_sistema', $user_session->id_usuario)->first(); ?>


                                                <?php if ($administrador['idtipo'] == 0) {    ?>
                                                    <div class="col">
                                                        <form action="<?= base_url('producto/facturar_pedido') ?>" method="POST">
                                                            <input type="hidden" value="<?php echo $detalle['id'] ?>" name="id_mesa_facturacion">
                                                            <button title="Facturar " onclick="event.stopPropagation()" class="btn btn-success w-100 btn-icon" type="submit">
                                                                <?php echo "$" . number_format($tiene_pedido[0]['valor_total'], 0, ",", "."); ?>
                                                            </button>
                                                        </form>
                                                    </div>
                                                <?php } ?>


                                                <?php if ($administrador['idtipo'] == 1) {    ?>
                                                    <div class="col">

                                                        <button title="Facturar " onclick="event.stopPropagation()" class="btn btn-success w-100 btn-icon" type="button">
                                                            <?php echo "$" . number_format($tiene_pedido[0]['valor_total'], 0, ",", "."); ?>
                                                        </button>

                                                    </div>
                                                <?php } ?>
                                            </div><br>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                    <?php } ?>




                </div>
            <?php } ?>
        </div>




        <div class="col-md-12 container" id="pedido_directo" style="display: none;">

            <div class="card-body ">
                <!-- Tabla de encabezado de pedido-->
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-6">
                        Mesa: <span id="nombre_de_mesa"></span>
                        <input type="hidden" id="id_mesa">
                    </div>
                    <div class="col-sm-12 col-md-3 col-6">
                        <input type="hidden" id="numero_pedido_salvar">
                        Pedido: <span id="numero_pedido_mostrar"></span>
                    </div>
                    <div class="col-sm-12 col-md-3 col-3">
                        <a href="#" class="btn  btn-icon w-6 text-white bg-indigo" title="Detalle de pedido" onclick="detalle_pedido()">
                            Detalle
                        </a>
                    </div>

                    <div class="col-sm-12 col-md-3 col-3">
                        <a href="#" class="btn btn-success w-6 btn-icon" title="Búsqueda por categorias" data-bs-toggle="modal" data-bs-target="#categorias_producto">
                            Categoria
                        </a>
                    </div>
                </div>
                <!-- Tabla de busqueda de productos por descripcion o categoria -->
                <br>
                <div class="input-icon mb-3">
                    <input type="hidden" id="id_producto">
                    <input type="text" class="form-control" placeholder="Buscar producto " id="producto" name="producto" autofocus>
                    <span class="input-icon-addon">
                        <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <circle cx="10" cy="10" r="7" />
                            <line x1="21" y1="21" x2="15" y2="15" />
                        </svg>
                    </span>
                </div>
                <br>

                <div id="productos_pedido">

                </div>


                <div class="row align-items-start">

                    <div class="col-sm-6">
                        <span class="text-primary">Cantidad de items: <span id="cantidad_de_productos">0</span></span>
                    </div>
                </div>

                <div class="row  gx-1 gy-1">

                    <div class="col-sm-4 col-md-2 col-xs-6">
                        <a href="#" class="btn btn-success w-100 btn-icon" title="Cambiar de mesa" onclick="cambiar_de_mesa()">
                            Cambio mesa
                        </a>
                    </div>
                    <div class="col-sm-4 col-md-2 col-xs-6">
                        <form action="<?= base_url('comanda/imprimir_comanda') ?>" method="POST">
                            <input type="hidden" name="numero_pedido_imprimir_comanda" id="numero_pedido_imprimir_comanda">

                            <button type="button" onclick="imprimir_comanda()" title="Imprimir Comanda" class="btn btn-blue w-100 btn-icon">
                                Comanda
                            </button>
                        </form>
                    </div>
                    <div class="col-sm-4 col-md-2 col-xs-6">
                        <a title="Nota del pedido" class="btn btn-warning w-100 btn-icon" onclick="agregar_nota_al_pedido()">
                            Nota general
                        </a>
                    </div>

                    <div class="col-sm-4 col-md-2 col-xs-6">
                        <form action="<?= base_url('mesas/todas_las_mesas') ?>" method="GET">
                            <button type="submit" title="Salones" href="<?= base_url('salones/salones') ?>" class="btn btn-info w-100 btn-icon">
                                Mesas
                            </button>
                        </form>
                    </div>
                    <div class="col-sm-4 col-md-2 col-xs-6">
                        <button type="submit" title="Salones" onclick="imprimir_prefactura()" class="btn bg-cyan text-white w-100 btn-icon">
                            Prefactura
                        </button>
                    </div>
                    <div class="col-sm-4 col-md-2 col-xs-6">

                    <?php $administrador = model('usuariosModel')->select('idtipo')->where('idusuario_sistema', $user_session->id_usuario)->first(); ?>
                        <?php if ($administrador['idtipo'] == 0) {    ?>
                            <form action="<?= base_url('producto/facturar_pedido') ?>" method="POST">
                                <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $user_session->id_usuario; ?>">
                                <input type="hidden" name="id_mesa_facturacion" id="id_mesa_facturacion">

                                <button type="submit" title="Facturar pedido" class="btn btn-success w-100 btn-icon" id="valor_total">
                                    0
                                </button>
                            </form>
                        <?php } ?>

                        <?php if ($administrador['idtipo'] == 1) {    ?>
                            <button type="button" title="Facturar pedido" class="btn btn-success w-100 btn-icon" id="valor_total">
                                0
                            </button>
                        <?php } ?>

                        <input type="hidden" name="id_mesa_facturacion" id="id_mesa_facturacion">



                    </div>

                    <div class="col-sm-4 col-md-2 col-xs-6">
                        <input type="hidden" name="usuario_eliminacion" id="usuario_eliminacion" value="<?php echo $user_session->id_usuario; ?>">
                        <button type="button" title="Eliminar pedido" onclick="eliminacion_de_pedido()" class="btn bg-danger text-white w-100 btn-icon" id="btn_eliminar_pedido">
                            Eliminar pedido
                        </button>
                    </div>

                    <div class="col-12">
                        <label for="exampleFormControlTextarea1" class="form-label">Observaciones generales </label>
                        <textarea class="form-control" id="observacion_general_de_pedido" rows="3" readonly></textarea>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div id="stop" class="boton-subir">
        <a href="#top">
            <!-- Download SVG icon from http://tabler-icons.io/i/arrow-narrow-up -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="16" y1="9" x2="12" y2="5" />
                <line x1="8" y1="9" x2="12" y2="5" />
            </svg>
        </a>
    </div>
    <?= $this->include('ventanas_modal_pedido/eliminar_con_pin_pad') ?>
    <?= $this->include('ventanas_modal_editar_eliminar_pedido/eliminar_pedido_con_pin') ?>
    <!--jQuery -->
    <script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
    <!-- jQuery-ui -->
    <script src="<?php echo base_url() ?>/Assets/plugin/jquery-ui/jquery-ui.js"></script>

    <script>
        function imprimir_comanda() {
            var url = document.getElementById("url").value;
            var numero_pedido_imprimir_comanda = document.getElementById("numero_pedido_imprimir_comanda").value;
            $.ajax({
                data: {
                    numero_pedido_imprimir_comanda,
                },
                url: url + "/" + "comanda/imprimir_comanda",
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
                            title: 'Impresión de comanda correcto '
                        })
                    } else if (resultado.resultado == 0) {
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
                            icon: 'info',
                            title: 'No hay productos para imprimir en comanda'
                        })
                    }
                },
            });
        }
    </script>

    <script>
        function imprimir_prefactura() {
            var url = document.getElementById("url").value;
            var numero_de_pedido_pre_factura = document.getElementById("numero_pedido_imprimir_comanda").value;
            $.ajax({
                data: {
                    numero_de_pedido_pre_factura,
                },
                url: url + "/" + "pre_factura/imprimir_desde_pedido",
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
                            title: 'Impresión de prefactura correcto'
                        })
                    } else if (resultado.resultado == 0) {
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
                            icon: 'info',
                            title: 'No hay productos para imprimir en comanda'
                        })
                    }
                },
            });
        }
    </script>

    <script>
        function agregar_producto(id_tabla_prducto) {
            var url = document.getElementById("url").value;

            $.ajax({
                data: {
                    id_tabla_prducto
                },
                url: url + "/" + "pedido/actualizar_cantidades",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {
                        $("#productos_pedido").html(resultado.productos);
                        $("#valor_total").html(resultado.total);

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
                            title: 'Se ha agregado un ' + resultado.nombre_producto
                        })
                    } else if (resultado.resultado == 0) {
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
                            icon: 'info',
                            title: 'No hay productos para imprimir en comanda'
                        })
                    }
                },
            });
        }
    </script>

    <script>
        function eliminar_cantidades(id_tabla_prducto) {
            var url = document.getElementById("url").value;
            var id_usuario = document.getElementById("id_usuario").value;
            $.ajax({
                data: {
                    id_tabla_prducto,
                    id_usuario
                },
                url: url + "/" + "pedido/eliminar_cantidades",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {
                        $("#productos_pedido").html(resultado.productos);
                        $("#valor_total").html(resultado.total);

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
                            title: 'Se ha eliminado un ' + resultado.nombre_producto
                        })
                    } else if (resultado.resultado == 0) {
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
                            icon: 'error',
                            title: 'No se puede eliminar por que no cuenta con los permisos o ya hiso impreso en comanda'
                        })
                    }
                },
            });
        }
    </script>
    <script>
        function cancelar_productos_tabla_pedido() {
            $('#autocompletar_producto').modal('hide');
            document.getElementById("producto").value = "";
            document.getElementById("id_producto").value = "";
        }
    </script>

    <script>
        function eliminacion_de_pedido() {
            var numero_pedido = document.getElementById("numero_pedido_salvar").value;
            if (numero_pedido == "") {
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
                    icon: 'info',
                    title: 'No hay pedido para borrar '
                })
            } else if (numero_pedido != "") {
                Swal.fire({
                    title: 'Realmente desea eliminar el pedido ?',
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: 'Si borrar',
                    confirmButtonColor: "#2AA13D",
                    denyButtonText: `Cancelar`,
                    denyButtonColor: "#C13333",
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        elemento = document.getElementById("btn_eliminar_pedido");
                        elemento.blur();
                        var url = document.getElementById("url").value;
                        var numero_pedido = document.getElementById("numero_pedido_salvar").value;
                        var usuario_eliminacion = document.getElementById("usuario_eliminacion").value;
                        $.ajax({
                            data: {
                                numero_pedido,
                                usuario_eliminacion
                            },
                            url: url + "/" + "producto/eliminacion_de_pedido_desde_pedido",
                            type: "post",
                            success: function(resultado) {
                                var resultado = JSON.parse(resultado);

                                if (resultado.resultado == 1) {
                                    $('#productos_pedido').html('');
                                    $("id_mesa").val = "";
                                    document.getElementById("numero_pedido_salvar").value = "";
                                    $("#nombre_mesa").html('')
                                    $("#numero_pedido_mostrar").html('')
                                    document.getElementById("id_producto").value = "";
                                    document.getElementById("producto").value = "";
                                    document.getElementById("numero_pedido_imprimir_comanda").value = "";
                                    document.getElementById("id_mesa_facturacion").value = "";
                                    document.getElementById("cantidad_de_productos").value = "";
                                    $('#cantidad_de_productos').html('')

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
                                        title: 'Pedido borrado'
                                    })
                                } else if (resultado.resultado == 0) {
                                    myModal = new bootstrap.Modal(document.getElementById("eliminar_pedido_con_pin_pad"), {});
                                    myModal.show();
                                }
                            },
                        });

                    } else if (result.isDenied) {
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
                            icon: 'info',
                            title: 'Pedido no se borra'
                        })
                    }
                })
            }
        }
    </script>


    <script>
        function impresion_prefactura(event, id_mesa) {
            event.stopPropagation();
            var url = document.getElementById("url").value;

            $.ajax({
                data: {
                    id_mesa
                },
                url: url + "/" + "pre_factura/imprimir_prefactura",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {
                        $("#productos_pedido").html(resultado.productos);
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
                            title: 'Impresión de comanda'
                        })
                    } else if (resultado.resultado == 0) {
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
                            icon: 'info',
                            title: 'No hay productos para imprimir en comanda'
                        })
                    }
                },
            });


        }
    </script>


    <script type="text/javascript">
        function valideKey(evt) {

            // code is the decimal ASCII representation of the pressed key.
            var code = (evt.which) ? evt.which : evt.keyCode;

            if (code == 8) { // backspace.
                return true;
            } else if (code >= 48 && code <= 57) { // is a number.
                return true;
            } else { // other keys.
                return false;
            }
        }
    </script>

    <script>
        function actualizar_producto_cantidad(e, cantidad, id_tabla_producto) {
            var enterKey = 13;
            if (e.which == enterKey) {
                if (cantidad != "") {
                    var url = document.getElementById("url").value;
                    var id_usuario = document.getElementById("id_usuario").value;

                    $.ajax({
                        data: {
                            cantidad,
                            id_tabla_producto,
                            id_usuario
                        },
                        url: url + "/" + "producto/actualizacion_cantidades",
                        type: "post",
                        success: function(resultado) {
                            var resultado = JSON.parse(resultado);

                            if (resultado.resultado == 1) {
                                $('#productos_pedido').html(resultado.productos);
                                $("#valor_total").html(resultado.total_pedido);
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
                                    title: 'Cantidades agregadas'
                                })
                            } else if (resultado.resultado == 0) {
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
                                    icon: 'error',
                                    title: 'Usuario no tiene permiso de eliminacion de pedido '
                                })
                            }
                        },
                    });





                }
            }
        }
    </script>


    <?= $this->endSection('content') ?>