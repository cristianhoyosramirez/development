    <style>
        .line-divider {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            /* Línea sutil */
            margin-top: 5px;
            /* Espacio adicional arriba */
            margin-bottom: 0;
            /* Evita duplicar el margen */
            padding-bottom: 0;
            /* Evita que el contenido dentro afecte el espaciado */
        }
    </style>




    <?php foreach ($productos as $detalle): ?>

        <div class="row_pro">
            <div class="row " id="producto_compra<?php echo $detalle['id'] ?>">
                <div class="col-3">
                    <?php echo "Código " . $detalle['codigointernoproducto'] . " " . $detalle['nombreproducto'] . "  "; ?>
                </div>

                <div class="col-3">



                    <div class="input-icon">

                        <span class="input-icon-addon">
                            <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                <path d="M12 3v3m0 12v3" />
                            </svg>
                        </span>
                        <input type="text"
                            title="valor unitario"
                            data-bs-toggle="tooltip"
                            data-bs-placement="bottom"
                            class="form-control"
                            value="<?php echo number_format($detalle['valor'], 0, ',', '.'); ?>"
                            id="val_uni<?php echo $detalle['id']; ?>"
                            onkeyup="actualizar_precio(this.value,<?php echo $detalle['id']; ?>)" onclick="seleccionar(<?php echo $detalle['id']; ?>)">
                    </div>

                </div>

                <div class="col-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <a href="#" class="btn bg-muted-lt btn-icon" onclick="eliminar_cantidades('<?php echo $detalle['id'] ?>')">
                                <!-- Download SVG icon from http://tabler-icons.io/i/minus -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                            </a>
                        </div>

                        <input type="number" class="form-control form-control-sm text-center custom-width" value="<?php echo $detalle['cantidad'] ?>" oninput="actualizacion_cantidades(this.value, <?php echo $detalle['id'] ?>)" id="input_cantidad<?php echo $detalle['id'] ?>" onclick="resaltar_cantidad(<?php echo $detalle['id'] ?>)" title="cantidad">
                        <div class="input-group-append">
                            <a href="#" class="btn bg-muted-lt btn-icon" onclick="actualizar_cantidades('<?php echo $detalle['id'] ?>')" title="Agregar producto">
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
                    <div class="input-icon">
                        <!--<span class="input-icon-addon">
                        Download SVG icon from http://tabler-icons.io/i/currency-dollar 
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                            <path d="M12 3v3m0 12v3" />
                        </svg>
                    </span>-->
                        <!-- <input type="text" title ="valor total" class="form-control" id="total_producto<?php echo $detalle['id'] ?>" value="<?php echo  number_format($valor = $detalle['valor'] * $detalle['cantidad'], 0, ",", "."); ?>" readonly> -->
                        <span id="total_producto<?php echo $detalle['id'] ?>"><?php echo  number_format($valor = $detalle['valor'] * $detalle['cantidad'], 0, ",", "."); ?> </span>
                    </div>
                </div>

                <div class="col">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-danger btn-icon border-0" onclick="eliminar_producto(event,'<?php echo $detalle['id'] ?>')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar">
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
        </div>
        <div class="mb-3 line-divider" id="linea<?php echo $detalle['id'] ?>"></div>
    <?php endforeach ?>

    <script>
        function eliminar_cantidades(id) {

            var url = document.getElementById("url").value;


            $.ajax({
                data: {
                    id,
                },
                url: url + "/inventario/eliminacion_producto_compra",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        $('#input_cantidad' + resultado.id).val(resultado.cantidad)
                        $('#total_producto' + resultado.id).html(resultado.total_producto)
                        $('#valor_factura').html(resultado.total_compra)
                        $('#numero_articulos').html(resultado.total_articulos)

                    }
                },
            });

        }
    </script>

    <script>
        function actualizar_cantidades(id) {

            var url = document.getElementById("url").value;


            $.ajax({
                data: {
                    id,
                },
                url: url + "/inventario/actualizacion_producto_compra",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        $('#input_cantidad' + resultado.id).val(resultado.cantidad)
                        $('#total_producto' + resultado.id).html(resultado.total_producto)
                        $('#valor_factura').html(resultado.total_compra)
                        $('#numero_articulos').html(resultado.total_articulos)

                    }
                },
            });

        }
    </script>

    <script>
        /* function actualizar_cantidades(id) {
            var url = document.getElementById("url").value;


           $.ajax({
                data: {
                    id: id,          // Se asigna el valor de id
                    
                },
                url: url + "/inventario/actualizar_producto_compra",
                type: "POST",
                success: function(resultado) {
                    // Parsea el resultado recibido
                    var resultadoParsed = JSON.parse(resultado);
                    if (resultadoParsed.resultado == 1) {
                        // Actualiza los valores correspondientes en el DOM
                        $('#val_uni' + resultadoParsed.id).val(resultadoParsed.precio);
                        $('#total_producto' + resultadoParsed.id).val(resultadoParsed.total_producto);
                        $('#valor_factura').html(resultadoParsed.total);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", error);
                }
            }); 


        }*/
    </script>


    <script>
        function resaltar_cantidad(id) {
            $('#input_cantidad' + id).select()
        }
    </script>

    <script>
        function seleccionar(id) {
            $('#val_uni' + id).select()
        }
    </script>

    <script>
        function actualizar_precio(precio, id) {

            var url = document.getElementById("url").value;
            var id_usuario = document.getElementById("id_usuario").value;

            $.ajax({
                data: {
                    id,
                    precio,
                    id_usuario
                },
                url: url + "/inventario/actualizar_producto_compra",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        $('#val_uni' + resultado.id).val(resultado.precio)
                        $('#total_producto' + resultado.id).html(resultado.total_producto)
                        $('#valor_factura').html(resultado.total)

                    }
                },
            });

        }
    </script>