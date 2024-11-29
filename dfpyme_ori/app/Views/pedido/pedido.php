<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/SalonMesa') ?>
<?= $this->section('title') ?>
PEDIDO
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
        <div class="card">
            <div class="card-body ">
                <!-- Tabla de encabezado de pedido-->
                <div class="row">
                    <div class="col">
                        Mesa: <?php echo $nombre_mesa ?>
                        <input type="hidden" id="id_mesa" value="<?php echo $fk_mesa ?>">
                    </div>
                    <div class="col">
                        <input type="hidden" id="numero_pedido_salvar" value="<?php echo $pedido ?>">
                        Pedido: <?php echo $pedido ?>
                    </div>
                    <div class="col">
                        <a href="#" class="btn  btn-icon w-6 text-white bg-indigo" title="Detalle de pedido" onclick="detalle_pedido()">
                            Detalle
                        </a>
                    </div>

                    <div class="col">
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
                    <table class="table table-vcenter card-table table  table-hover table-border">
                        <thead class="table-dark">
                            <tr>
                                <td>Producto</th>
                                <td>Precio</th>
                                <td>Cantidad</th>
                                <td></td>
                                <td class="text-dark"> total</th>
                                <td>Total</td>
                                <td colspan="3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($productos as $detalle) { ?>

                                <tr>
                                    <td>
                                        <?php echo $detalle['codigointernoproducto'] . "-" . $detalle['nombreproducto']; ?>
                                        <?php if (!empty($detalle['nota_producto'])) { ?>
                                            <p class="text-primary fw-bold">NOTA: <?php echo $detalle['nota_producto'] ?></p>

                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php echo "$" . number_format($detalle['valor_unitario'], 0, ",", ".")  ?>
                                    </td>

                                    <td colspan="3">

                                        <div class="input-group">
                                            <a href="#" class="btn bg-muted-lt w-1 btn-icon" onclick="eliminar_cantidades(<?php echo $detalle['id_tabla_producto'] ?>)">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/minus -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <line x1="5" y1="12" x2="19" y2="12" />
                                                </svg>
                                            </a>


                                            <input type="hidden" class="form-control w-2" value="<?php echo $detalle['cantidad_producto'] ?>">
                                            <input type="number" class="w-1 form-control text-center" value="<?php echo $detalle['cantidad_producto'] ?>" onkeypress="return valideKey(event)" min="1" max="100" onkeyup="actualizar_producto_cantidad(event,this.value,<?php echo $detalle['id_tabla_producto'] ?>)" readonly>

                                            <a href="#" class="btn bg-muted-lt w-1 btn-icon" onclick="agregar_producto(<?php echo $detalle['id_tabla_producto'] ?>)" title="Agregar producto">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <line x1="12" y1="5" x2="12" y2="19" />
                                                    <line x1="5" y1="12" x2="19" y2="12" />
                                                </svg>
                                            </a>
                                        </div>

                                    </td>

                                    <td>
                                        <?php echo "$" . number_format($valor = $detalle['valor_total'], 0, ",", "."); ?>
                                    </td>



                                    <td> <a href="#" class="btn text-white bg-red-lt w-1 btn-icon" aria-label="Bitbucket" onclick="eliminar_producto(<?php echo $detalle['id_tabla_producto'] ?>)">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/trash -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <line x1="4" y1="7" x2="20" y2="7" />
                                                <line x1="10" y1="11" x2="10" y2="17" />
                                                <line x1="14" y1="11" x2="14" y2="17" />
                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                            </svg>
                                        </a></td>
                                    <td>
                                        <a href="#" class="btn bg-azure-lt w-1 btn-icon" aria-label="Bitbucket" onclick="editar_cantidades_de_pedido(<?php echo $detalle['id_tabla_producto'] ?>)">

                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                                <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                            </svg>
                                        </a>
                                    </td>
                                    <td> <a href="#" class="btn bg-cyan-lt w-1 btn-icon" aria-label="Tabler" onclick="entregar_producto(<?php echo $detalle['id_tabla_producto'] ?>)" title="Entregar producto">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/hand-click -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M8 13v-8.5a1.5 1.5 0 0 1 3 0v7.5" />
                                                <path d="M11 11.5v-2a1.5 1.5 0 0 1 3 0v2.5" />
                                                <path d="M14 10.5a1.5 1.5 0 0 1 3 0v1.5" />
                                                <path d="M17 11.5a1.5 1.5 0 0 1 3 0v4.5a6 6 0 0 1 -6 6h-2h.208a6 6 0 0 1 -5.012 -2.7l-.196 -.3c-.312 -.479 -1.407 -2.388 -3.286 -5.728a1.5 1.5 0 0 1 .536 -2.022a1.867 1.867 0 0 1 2.28 .28l1.47 1.47" />
                                                <path d="M5 3l-1 -1" />
                                                <path d="M4 7h-1" />
                                                <path d="M14 3l1 -1" />
                                                <path d="M15 6h1" />
                                            </svg>
                                        </a></button></td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>

                <div class="row align-items-start">

                    <div class="col-sm-6">
                        <span class="text-primary">Cantidad de items: <span id="cantidad_de_productos"><?php  echo $cantidad_items ?></span></span>
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
                            <input type="hidden" name="numero_pedido_imprimir_comanda" id="numero_pedido_imprimir_comanda" value="<?php echo $pedido?> ">

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
                                    <input type="hidden" name="id_mesa_facturacion" id="id_mesa_facturacion" value="<?php echo $fk_mesa?>">

                                    <button type="submit" title="Facturar pedido" class="btn btn-success w-100 btn-icon" id="valor_total">
                                        <?php  echo $valor_pedido?>
                                    </button>
                                </form>
                            <?php } ?>
                            <?php if ($detalle['idpermiso'] == 94) { ?>
                                <input type="hidden" name="id_mesa_facturacion" id="id_mesa_facturacion">
                            <?php } ?>

                        <?php } ?>
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