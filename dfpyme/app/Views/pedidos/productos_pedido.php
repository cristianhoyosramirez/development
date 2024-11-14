<style>
    .input-group-append {
        display: inline-flex;
        vertical-align: middle;
        /* Añadir esta propiedad para alinear verticalmente */
    }

    .btn {
        flex: 1;
        /* Distribuir espacio equitativamente entre los botones */
    }
</style>

<style>
    .custom-width {
        width: 100px;
        /* Ajusta el ancho según tus necesidades */
    }
</style>

<?php $id_tipo = model('empresaModel')->select('fk_tipo_empresa')->first() ?>
<?php $codigo_pantalla = model('configuracionPedidoModel')->select('codigo_pantalla')->first(); ?>

<?php foreach ($productos as $detalle) {  ?>

    <div class="row">



        <?php if ($id_tipo['fk_tipo_empresa'] == 1) : ?>

            <div class="col-3 col-md-12 col-lg-3">
                <?php if ($codigo_pantalla['codigo_pantalla'] == "t"): ?>
                    <?php echo "Código " . $detalle['codigointernoproducto'] . "</br>";
                    echo $detalle['nombreproducto'] . "  "; ?>
                <?php endif ?>
                <?php if ($codigo_pantalla['codigo_pantalla'] == "f"): ?>
                    <?php echo $detalle['nombreproducto']; ?>
                <?php endif ?>

                <?php if (!empty($detalle['nota_producto'])) { ?>
                    <p class="text-primary fw-bold"><?php echo $detalle['nota_producto'] ?></p>
                <?php } ?>
            </div>

            <div class="col col-md-3 col-lg-2">
                <span id="val_uni<?php echo $detalle['id_tabla_producto'] ?>"><?php echo "$ " . number_format($detalle['valor_unitario'], 0, ",", "."); ?></span>
            </div>

            <div class="col col-md-4 col-lg-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <a href="#" class="btn bg-muted-lt btn-icon" onclick="eliminar_cantidades(event,'<?php echo $detalle['id_tabla_producto'] ?>')">
                            <!-- Download SVG icon from http://tabler-icons.io/i/minus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                        </a>
                    </div>
                    <input type="hidden" class="form-control" value="<?php echo $detalle['cantidad_producto'] ?>">
                    <!-- <input type="number" class="form-control form-control-sm text-center custom-width" value="<?php echo $detalle['cantidad_producto'] ?>" onclick="detener_propagacion(event),abrir_modal_editar_cantidad(<?php echo $detalle['id_tabla_producto'] ?>)" onkeypress="return valideKey(event)" > -->
                    <input type="number" class="form-control form-control-sm text-center custom-width" value="<?php echo $detalle['cantidad_producto'] ?>" oninput="actualizacion_cantidades(this.value, <?php echo $detalle['id_tabla_producto'] ?>)"  id="input_cantidad<?php echo $detalle['id_tabla_producto'] ?>" onclick="resaltar_cantidad(<?php echo $detalle['id_tabla_producto'] ?>)">
                    <div class="input-group-append">
                        <a href="#" class="btn bg-muted-lt btn-icon" onclick="actualizar_cantidades(event,'<?php echo $detalle['id_tabla_producto'] ?>')" title="Agregar producto">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col">
                <span id="total_producto<?php echo $detalle['id'] ?>"> <?php echo "$" . number_format($valor = $detalle['valor_total'], 0, ",", "."); ?> </span>
            </div>

            <div class="col">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-danger btn-icon border-0" onclick="eliminar_producto(event,'<?php echo $detalle['id_tabla_producto'] ?>')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <line x1="4" y1="7" x2="20" y2="7" />
                            <line x1="10" y1="11" x2="10" y2="17" />
                            <line x1="14" y1="11" x2="14" y2="17" />
                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                        </svg>
                    </button> &nbsp;
                    <button type="button" class="btn btn-outline-primary btn-icon border-0" onclick="agregar_nota(<?php echo $detalle['id'] ?>,event)" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Acciones">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <circle cx="12" cy="12" r="1" />
                            <circle cx="12" cy="19" r="1" />
                            <circle cx="12" cy="5" r="1" />
                        </svg>
                    </button>
                </div>
            </div>

        <?php endif ?>





        <?php if ($id_tipo['fk_tipo_empresa'] == 2) : ?>

            <div class="row">
                <div class="col-12 col-sm-5 col-md-4 col-lg-6">

                    <?php if ($codigo_pantalla['codigo_pantalla'] == "t"): ?>
                        <?php echo "Código " . $detalle['codigointernoproducto'] . "</br>";
                        echo $detalle['nombreproducto'] . "  "; ?>
                    <?php endif ?>
                    <?php if ($codigo_pantalla['codigo_pantalla'] == "f"): ?>
                        <?php echo $detalle['nombreproducto']; ?>
                    <?php endif ?>


                    <?php if (!empty($detalle['nota_producto'])) { ?>
                        <p class="text-primary fw-bold"><?php echo $detalle['nota_producto'] ?></p>
                    <?php } ?>
                </div>

                <div class="col-12 col-sm-2 col-md-6 col-lg-3">

                    <div class="input-icon mb-3">
                        <input type="text" class="form-control text-center" inputmode="numeric" value="<?php echo number_format($detalle['valor_unitario'], 0, ",", ".") ?>" placeholder="Valor unitario" onkeyup="cambiar_precio(this.value,<?php echo $detalle['id'] ?>)" id="input<?php echo $detalle['id'] ?>" onclick="resaltar(<?php echo $detalle['id'] ?>)">
                        <span class="input-icon-addon">
                            <!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                            </svg>
                        </span>
                    </div>

                </div>
                <div class="col col-lg-2 col-md-2 text-start">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-danger btn-icon border-0" onclick="eliminar_producto(event,'<?php echo $detalle['id_tabla_producto'] ?>')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <line x1="4" y1="7" x2="20" y2="7" />
                                <line x1="10" y1="11" x2="10" y2="17" />
                                <line x1="14" y1="11" x2="14" y2="17" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg>
                        </button> &nbsp;
                        <button type="button" class="btn btn-outline-primary btn-icon border-0" onclick="agregar_nota(<?php echo $detalle['id'] ?>,event)" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Acciones">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <circle cx="12" cy="12" r="1" />
                                <circle cx="12" cy="19" r="1" />
                                <circle cx="12" cy="5" r="1" />
                            </svg>
                        </button>
                    </div>

                </div>


            </div>

        <?php endif ?>

        <?php if ($id_tipo['fk_tipo_empresa'] == 3) : ?>

            <div class="row">
                <div class="col-12 col-sm-5 col-md-4 col-lg-4">


                    <?php if ($codigo_pantalla['codigo_pantalla'] == "t"): ?>
                        <?php echo "Código " . $detalle['codigointernoproducto'] . "</br>";
                        echo $detalle['nombreproducto'] . "  "; ?>
                    <?php endif ?>
                    <?php if ($codigo_pantalla['codigo_pantalla'] == "f"): ?>
                        <?php echo $detalle['nombreproducto']; ?>
                    <?php endif ?>


                    <?php if (!empty($detalle['nota_producto'])) { ?>
                        <p class="text-primary fw-bold"><?php echo $detalle['nota_producto'] ?></p>
                    <?php } ?>
                </div>

                <div class="col col-md-3 col-lg-2">
                    <div class="input-icon mb-3">
                        <input type="text" class="form-control text-center" inputmode="numeric" value="<?php echo number_format($detalle['valor_unitario'], 0, ",", ".") ?>" placeholder="Valor unitario" onkeyup="precio_manual(this.value,<?php echo $detalle['id'] ?>); cambiar_valor_precio(<?php echo $detalle['id'] ?>)" onclick="resaltar(<?php echo $detalle['id'] ?>)" id="input<?php echo $detalle['id'] ?>">
                        <!-- <span class="input-icon-addon">
                           
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                            </svg>
                        </span> -->
                    </div>
                </div>

                <div class="col col-md-4 col-lg-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <a href="#" class="btn bg-muted-lt btn-icon" onclick="eliminar_cantidades(event,'<?php echo $detalle['id_tabla_producto'] ?>')">
                                <!-- Download SVG icon from http://tabler-icons.io/i/minus -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                            </a>
                        </div>
                        <input type="hidden" class="form-control" value="<?php echo $detalle['cantidad_producto'] ?>" id="cant_prod<?php echo $detalle['id_tabla_producto'] ?>" required>
                        <input type="number" class="form-control form-control-sm text-center custom-width" value="<?php echo $detalle['cantidad_producto'] ?>" oninput="actualizacion_cantidades(this.value, <?php echo $detalle['id_tabla_producto'] ?>)"  id="input_cantidad<?php echo $detalle['id_tabla_producto'] ?>" onclick="resaltar_cantidad(<?php echo $detalle['id_tabla_producto'] ?>)">
                        <!--                        <input type="number" class="form-control form-control-sm text-center custom-width" value="<?php echo $detalle['cantidad_producto'] ?>" onclick="detener_propagacion(event),abrir_modal_editar_cantidad(<?php echo $detalle['id_tabla_producto'] ?>)" onkeypress="return valideKey(event)" >
 -->
                        <div class="input-group-append">
                            <a href="#" class="btn bg-muted-lt btn-icon" onclick="actualizar_cantidades(event,'<?php echo $detalle['id_tabla_producto'] ?>')" title="Agregar producto">
                                <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-2 col-md-6 col-lg-2">
                    <span id="valor_total_producto<?php echo $detalle['id_tabla_producto'] ?>"><?php echo "$ " . number_format($detalle['valor_total'], 0, ",", "."); ?></span>

                </div>

                <div class="col col-lg-1 col-md-2 text-start">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-danger btn-icon border-0" onclick="eliminar_producto(event,'<?php echo $detalle['id_tabla_producto'] ?>')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <line x1="4" y1="7" x2="20" y2="7" />
                                <line x1="10" y1="11" x2="10" y2="17" />
                                <line x1="14" y1="11" x2="14" y2="17" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

        <?php endif ?>


    </div>




    </div>

    <div class="col-12">
        <hr class="my-2">
    </div>

<?php } ?>

<!-- <script>
    function actualizar_cantidades(cantidad, id) {

        let url = document.getElementById("url").value;

        $.ajax({
            data: {
                precio: valor,
                id,
                cantidad
            },
            url: url + "/" + "eventos/editar_precio",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {
                    // Aquí puedes agregar lo que necesitas hacer si resultado.resultado es 1
                    $('#valor_total_producto' + resultado.id).html('$ ' + resultado.total_producto)
                    $('#valor_pedido').html(resultado.total_producto)
                    $('#valor_pedido').html(resultado.total_pedido)


                }
            },
        });
     

    }
</script> -->


<script>
    /* function precio_manual(precio, id) {

        let url = document.getElementById("url").value;
        let cantidad = document.getElementById("cant_prod").value;
        valor = precio.trim() === '' ? 0 : parseInt(precio.replace(/\./g, ''));

        $.ajax({
            data: {
                precio: valor,
                id,
                cantidad
            },
            url: url + "/" + "eventos/editar_precio",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {
                    // Aquí puedes agregar lo que necesitas hacer si resultado.resultado es 1
                    $('#valor_total_producto' + resultado.id).html('$ ' + resultado.total_producto)
                    $('#valor_pedido').html(resultado.total_producto)
                    $('#valor_pedido').html(resultado.total_pedido)


                }
            },
        });
    } */

    function precio_manual(precio, id) {

        let url = document.getElementById("url").value;
        let cantidad = $('#input_cantidad' + id).val();
        valor = precio.trim() === '' ? 0 : parseInt(precio.replace(/\./g, ''));

        $.ajax({
            data: {
                precio: valor,
                id,
                cantidad
            },
            url: url + "/" + "eventos/editar_precio",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {
                    // Update the total product price using the dynamic id
                    $('#valor_total_producto' + resultado.id).html('$ ' + resultado.total_producto);

                    // Update the total order price
                    $('#valor_pedido').html(resultado.total_pedido);
                }
            },
        });
    }
</script>



<script>
    function resaltar(id) {

        $('#input' + id).select()

    }
</script>
<script>
    function resaltar_cantidad(id) {

        $('#input_cantidad' + id).select()

    }
</script>