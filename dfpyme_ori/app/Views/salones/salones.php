<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/salonMesa') ?>
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

<div class="col-md-12 container" id="salones">
    <div class="card">
        <div class="card-body ">
            <p class="text-primary text-center h4">Salones</p>
            <input type="hidden" value="<?= base_url() ?>" id="url">
            <br>
            <div class="row container">
                <?php foreach ($salones as $detalle) { ?>
                    <div class="col-md-3">
                        <div class="bg-azure-lt border border-4 rounded cursor-pointer" onClick="ver_mesas_por_salon(<?php echo $detalle['id'] ?>);">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <p><?php echo $detalle['nombre'] ?></p>
                                        </td>
                                        <td><img src="<?php echo base_url(); ?>/Assets/img/salon.png" height="40" class="img-fluid mx-auto d-block cursor-pointer"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <span style="color:red" id="no_hay_mesas"></span>
            <div id="mesas_salon">

            </div>
        </div>
    </div>
</div>

<div class="col-md-12 container" id="pedido" style="display: none;">
    <div class="card">
        <div class="card-body ">
            <!-- Tabla de encabezado de pedido-->
            <div class="container">
                <div class="row gx-1">
                    <div class="col-md-4 col-sm-2 col-xs-4">
                        Mesa: <span id="nombre_de_mesa"></span>
                        <input type="hidden" id="id_mesa">
                    </div>
                    <div class="col-md-3 col-sm-2 col-xs-4">
                        <input type="hidden" id="numero_pedido_salvar">
                        Pedido: <span id="numero_pedido_mostrar"></span>
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-4">
                        <a href="#" class="btn  btn-icon w-6 text-white bg-indigo" title="Detalle de pedido" onclick="detalle_pedido()">

                            <!-- Download SVG icon from http://tabler-icons.io/i/list-search 
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <circle cx="15" cy="15" r="4" />
                                                <path d="M18.5 18.5l2.5 2.5" />
                                                <path d="M4 6h16" />
                                                <path d="M4 12h4" />
                                                <path d="M4 18h4" />
                                            </svg>-->Detalle
                        </a>
                    </div>

                    <div class="col-md-3 col-sm-2 col-xs-4">
                        <a href="#" class="btn btn-success w-6 btn-icon" title="Búsqueda por categorias" data-bs-toggle="modal" data-bs-target="#categorias_producto">
                            <!-- Download SVG icon from http://tabler-icons.io/i/coffee
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M3 14c.83 .642 2.077 1.017 3.5 1c1.423 .017 2.67 -.358 3.5 -1c.83 -.642 2.077 -1.017 3.5 -1c1.423 -.017 2.67 .358 3.5 1" />
                                                <path d="M8 3a2.4 2.4 0 0 0 -1 2a2.4 2.4 0 0 0 1 2" />
                                                <path d="M12 3a2.4 2.4 0 0 0 -1 2a2.4 2.4 0 0 0 1 2" />
                                                <path d="M3 10h14v5a6 6 0 0 1 -6 6h-2a6 6 0 0 1 -6 -6v-5z" />
                                                <path d="M16.746 16.726a3 3 0 1 0 .252 -5.555" />
                                            </svg> --> Categoria
                        </a>
                    </div>


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


            <div class="row ">

                <div class="col-sm-6 col-md-6 col-xs-6">
                    <span class="text-primary">Cantidad de items: <span id="cantidad_de_productos">0</span></span>
                </div>
            </div>

            <div class="row align-items-start gx-1 gy-1">

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
                    <?php foreach ($session->tipo_permiso as $detalle) { ?>

                        <?php if ($detalle['idpermiso'] == 45) { ?>
                            <form action="<?= base_url('producto/facturar_pedido') ?>" method="POST">
                                <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $user_session->id_usuario; ?>">
                                <input type="hidden" name="id_mesa_facturacion" id="id_mesa_facturacion">

                                <button type="submit" title="Facturar pedido" class="btn btn-success w-100 btn-icon" id="valor_total">
                                    0
                                </button>
                            </form>
                        <?php } ?>
                        <?php if ($detalle['idpermiso'] == 94) { ?>
                            <input type="hidden" name="id_mesa_facturacion" id="id_mesa_facturacion">
                        <?php } ?>

                    <?php } ?>
                </div>
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
<?= $this->include('ventanas_modal_pedido/editar_con_pin_pad') ?>
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
    function eliminar_producto() {

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

<script>
    function actualizar_producto_cantidad(e, cantidad, id_tabla_producto) {
        var enterKey = 13;
        if (e.which == enterKey) {
            if (cantidad != "") {
                var url = document.getElementById("url").value;

                $.ajax({
                    data: {
                        cantidad,
                        id_tabla_producto
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
                                $("#valor_total").html(0);
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



<?= $this->endSection('content') ?>