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
        <div class="row cursor-pointer">

            <?php if ($id_tipo['fk_tipo_empresa'] == 1) : ?>

                <div class="col-3 col-md-12 col-lg-3">
                    <?php if ($codigo_pantalla['codigo_pantalla'] == "t"): ?>
                        <?php echo "Código " . $detalle['codigointernoproducto'] . "</br>";
                        echo $detalle['nombreproducto'] . "  "; ?>
                    <?php endif ?>
                    <?php if ($codigo_pantalla['codigo_pantalla'] == "f"): ?>
                        <?php echo $detalle['nombreproducto']; ?>
                    <?php endif ?>




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
                        <input type="number" class="form-control form-control-sm text-center custom-width" value="<?php echo $detalle['cantidad_producto'] ?>" oninput="actualizacion_cantidades(this.value, <?php echo $detalle['id_tabla_producto'] ?>)" id="input_cantidad<?php echo $detalle['id_tabla_producto'] ?>" onclick="resaltar_cantidad(<?php echo $detalle['id_tabla_producto'] ?>)">
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
                        <!--  <button type="button" class="btn btn-outline-primary btn-icon border-0" onclick="agregar_nota(<?php echo $detalle['id'] ?>,event)" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Acciones">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <circle cx="12" cy="12" r="1" />
                                <circle cx="12" cy="19" r="1" />
                                <circle cx="12" cy="5" r="1" />
                            </svg>
                        </button> -->

                        <div class="dropdown border-none ">
                            <button class="btn btn-outline-primary  btn-icon border-none    " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <circle cx="12" cy="12" r="1" />
                                    <circle cx="12" cy="19" r="1" />
                                    <circle cx="12" cy="5" r="1" />
                                </svg>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                <li><a class="dropdown-item" href="#" onclick="detener_propagacion(event); abrir_modal_editar_cantidad(<?php echo $detalle['id_producto']; ?>, <?php echo $detalle['id_tabla_producto']; ?>)">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/receipt -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2" />
                                        </svg>Atributos</a></li>

                                <li><a class="dropdown-item" href="#" onclick="addDescuento(<?php echo $detalle['id_tabla_producto']; ?>)"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                            <path d="M12 3v3m0 12v3" />
                                        </svg>Descuento</a></li>
                                <li><a class="dropdown-item" href="#" onclick="listOfPrice(<?php echo $detalle['id_tabla_producto']; ?>)"><!-- Download SVG icon from http://tabler-icons.io/i/list-check -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3.5 5.5l1.5 1.5l2.5 -2.5" />
                                            <path d="M3.5 11.5l1.5 1.5l2.5 -2.5" />
                                            <path d="M3.5 17.5l1.5 1.5l2.5 -2.5" />
                                            <line x1="11" y1="6" x2="20" y2="6" />
                                            <line x1="11" y1="12" x2="20" y2="12" />
                                            <line x1="11" y1="18" x2="20" y2="18" />
                                        </svg>Lista de precios </a></li>
                                <li><a class="dropdown-item" href="#" onclick="cortesia_1(<?php echo $detalle['id_tabla_producto']; ?>)">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/refresh -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                            <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                        </svg>Cortesia</a></li>


                            </ul>
                        </div>

                    </div>
                </div>

            <?php endif ?>

        </div>

        <div class="row">
            <div class="text-primary fw-bold" id="atributos<?php echo $detalle['id_tabla_producto'] ?>">

                <?php $atributos = model('atributosDeProductoModel')->getAtributos($detalle['id_tabla_producto']); ?>
                <?php if (!empty($atributos)): ?>

                    <?php
                    foreach ($atributos as $detalleAtributo) :
                        $nombreAtributo = model('atributosProductoModel')->select('nombre')->where('id', $detalleAtributo['id_atributo'])->first();
                        $componentes = model('atributosDeProductoModel')->getComponentes($detalle['id_tabla_producto'], $detalleAtributo['id_atributo']);
                    ?>

                        <p style="margin: 2px 0;">
                            <span style="color: green; font-weight: bold;"><?php echo $nombreAtributo['nombre']; ?></span>
                            <?php if (!empty($componentes)): ?>
                                (<span style="color: black;"><?php echo implode(', ', array_column($componentes, 'nombre')); ?></span>)
                            <?php else: ?>
                                (<span style="color: red;">No hay componentes disponibles</span>)
                            <?php endif; ?>
                        </p>

                    <?php endforeach; ?>


                <?php endif ?>

            </div>
        </div>
        <div class="row" id="notasDesdeAtributo<?php echo $detalle['id_tabla_producto'] ?>">
            <p class="text-primary fw-bold"><?php echo $detalle['nota_producto'] ?></p>
        </div>



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
                        <input type="number" class="form-control form-control-sm text-center custom-width" value="<?php echo $detalle['cantidad_producto'] ?>" oninput="actualizacion_cantidades(this.value, <?php echo $detalle['id_tabla_producto'] ?>)" id="input_cantidad<?php echo $detalle['id_tabla_producto'] ?>" onclick="resaltar_cantidad(<?php echo $detalle['id_tabla_producto'] ?>)">
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

    <?php $mostrarMesero = model('configuracionPedidoModel')->select('mostrarmesero')->first(); ?>

    <?php if ($mostrarMesero['mostrarmesero'] === "t"): ?>
        <div class="row text-primary">

            <?php
            $hora = model('productoPedidoModel')->select('hora')->where('id', $detalle['id'])->first();
            $usuario = model('productoPedidoModel')->select('idUsuario')->where('id', $detalle['id'])->first();

            $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $usuario['idUsuario'])->first();

            $hora_formateada = date('h:i A', strtotime($hora['hora'])); // Formato 12 horas con AM/PM
            ?>
            <div class="col-6"><?php echo $hora_formateada . " " . $nombre_usuario['nombresusuario_sistema'];  ?></div>

            <!--    <div class="col-3">Fecha:</div>
        <?php
        $fecha = date('Y-m-d');
        $dia_semana = date('l'); // Obtiene el día de la semana en inglés
        $dias = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];
        $dia_traducido = $dias[$dia_semana]; // Traducción del día
        ?>
        <div class="col-3"><?php echo "$fecha ($dia_traducido)"; ?></div> -->
        </div>
    <?php endif ?>


    </div>

    <div class="col-12">
        <hr class="my-2">
    </div>

<?php } ?>


<script>
    const componentesSeleccionados = [];

    function seleccionarComponentes(nombreComponente, componenteId, numeroComponentes) {
        const productoId = document.getElementById('codigoInternoProd').value;

        if (!productoId) {
            console.warn('No se ha ingresado un código de producto');
            return;
        }

        if (componentesSeleccionados.length >= 2) {
            alert('Solo puedes agregar hasta 2 componentes.');
            return;
        }

        const yaExiste = componentesSeleccionados.some(comp => comp.componenteId === componenteId);
        if (yaExiste) {
            alert('Este componente ya ha sido agregado.');
            return;
        }

        const componente = {
            productoId: productoId,
            componenteId: componenteId,
            componenteNombre: nombreComponente // <- ¡esto es lo que faltaba!
        };

        componentesSeleccionados.push(componente);

        document.getElementById('inputComponentes').value = JSON.stringify(componentesSeleccionados);

        renderizarBotonesComponentes();
    }

    function eliminarComponente(index) {
        componentesSeleccionados.splice(index, 1);
        document.getElementById('inputComponentes').value = JSON.stringify(componentesSeleccionados);
        renderizarBotonesComponentes();
    }

    function renderizarBotonesComponentes() {
        const contenedor = document.getElementById('contenedorBotonesComponentes');
        contenedor.innerHTML = '';

        componentesSeleccionados.forEach((item, index) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'btn btn-success rounded-pill position-relative me-2 mb-2';
            button.id = `btnComponente${item.componenteId}`;

            button.innerHTML = `
        ${item.componenteNombre}
        <span class="badge rounded-pill bg-success" onclick="eliminarComponente(${index})" style="cursor:pointer;">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
        </span>
    `;

            contenedor.appendChild(button);
        });


    }
</script>







<script>
    async function finalizarAtributos(id) {

        try {
            let id_tabla_producto = document.getElementById('id_tabla_producto').value.trim(); // Elimina espacios en blanco
            let nota = document.getElementById('notaAtributo').value.trim(); // Elimina espacios en blanco

            let respuesta = await fetch('<?= base_url('producto/armarNota') ?>', { // Cambia por la ruta correcta
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id_tabla_producto: id_tabla_producto,
                    nota: nota

                })
            });

            if (!respuesta.ok) {
                throw new Error(`Error HTTP: ${respuesta.status}`);
            }

            let resultado = await respuesta.json(); // Convertir la respuesta a JSON
            if (resultado.response == "success") {
                // Actualiza el contenido del div
                $('#modalAtributos').modal('hide')
                document.getElementById('atributos' + resultado.id_tabla_producto).innerHTML = resultado.atributos; // Elimina el botón del componente
                document.getElementById('notasDesdeAtributo' + resultado.id_tabla_producto).innerHTML =
                    '<p class="text-primary fw-bold">' + resultado.nota + '</p>';
                // Elimina el botón del componente
            }
            if (resultado.response == "error") {
                sweet_alert_centrado("error", resultado.mensaje);
            }

            // Aquí puedes actualizar la interfaz si es necesario
        } catch (error) {
            console.error('Error al seleccionar componente:', error.message);
        }

    }
</script>

<script>
    async function notaAtributo(nota) {



        try {
            let id_tabla_producto = document.getElementById('id_tabla_producto').value.trim(); // Elimina espacios en blanco
            let respuesta = await fetch('<?= base_url('pedidos/actualizarNota') ?>', { // Cambia por la ruta correcta
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: id_tabla_producto,
                    nota: nota,

                })
            });

            if (!respuesta.ok) {
                throw new Error(`Error HTTP: ${respuesta.status}`);
            }

            let resultado = await respuesta.json(); // Convertir la respuesta a JSON
            if (resultado.response == "success") {
                // Actualiza el contenido del div
                $('#modalAtributos').modal('hide')
                document.getElementById('nota' + id_tabla_producto).innerHTML = resultado.nota; // Elimina el botón del componente
            }
            if (resultado.response == "error") {
                sweet_alert_centrado("error", resultado.mensaje);
            }

            // Aquí puedes actualizar la interfaz si es necesario
        } catch (error) {
            console.error('Error al seleccionar componente:', error.message);
        }

    }
</script>

<script>
    async function eliminacionComponente(id) {

        try {
            let respuesta = await fetch('<?= base_url('producto/deleteComponenteProducto') ?>', { // Cambia por la ruta correcta
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: id,

                })
            });

            if (!respuesta.ok) {
                throw new Error(`Error HTTP: ${respuesta.status}`);
            }

            let resultado = await respuesta.json(); // Convertir la respuesta a JSON
            if (resultado.response == "success") {
                // Actualiza el contenido del div
                document.getElementById('btnComponente' + id).remove(); // Elimina el botón del componente
            }
            if (resultado.response == "error") {
                sweet_alert_centrado("error", resultado.mensaje);
            }

            // Aquí puedes actualizar la interfaz si es necesario
        } catch (error) {
            console.error('Error al seleccionar componente:', error.message);
        }
    }
</script>
<script>
    async function seleccionarComponente(idAtributo, idComponente, idProducto) {



        let id_tabla_producto = document.getElementById('id_tabla_producto').value.trim(); // Elimina espacios en blanco
        try {
            let respuesta = await fetch('<?= base_url('producto/getAtributosProducto') ?>', { // Cambia por la ruta correcta
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id_atributo: idAtributo,
                    id_tabla_producto: id_tabla_producto,
                    id_componente: idComponente,
                    id_producto: idProducto
                })
            });

            if (!respuesta.ok) {
                throw new Error(`Error HTTP: ${respuesta.status}`);
            }

            let resultado = await respuesta.json(); // Convertir la respuesta a JSON
            if (resultado.response == "success") {
                document.getElementById('componentesDeProducto' + resultado.id_atributo).innerHTML = resultado.componetes; // Actualiza el contenido del div
            }
            if (resultado.response == "error") {
                sweet_alert_centrado("error", resultado.mensaje);
            }

            // Aquí puedes actualizar la interfaz si es necesario
        } catch (error) {
            console.error('Error al seleccionar componente:', error.message);
        }
    }
</script>

<script>
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

<script>
    async function abrir_modal_editar_cantidad(id_producto, id_tabla_producto) {
        try {
            let response = await fetch('<?= base_url('producto/getAtributos') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id_producto: id_producto,
                    id_tabla_producto: id_tabla_producto,
                })
            });

            if (!response.ok) {
                throw new Error(`Error en la respuesta: ${response.status} - ${response.statusText}`);
            }

            let data = await response.json(); // Aquí no debería dar error


            if (data.response == "success") {
                //document.getElementById('tbodyInsumos').innerHTML = data.productos;
                let boton = document.getElementById('btnAtri');

                // Remueve cualquier onclick en el HTML
                boton.removeAttribute('onclick');

                // Asigna uno nuevo
                boton.setAttribute('onclick', 'finalizarAtributos()');

                document.getElementById('asigCompo').innerHTML = data.atributos;
                document.getElementById('productoAddAtri').innerHTML = data.nombreProducto;
                document.getElementById('notaAtributo').value = data.nota;
                document.getElementById('inputCantidad').innerHTML = data.inputCantidad;

                document.getElementById("inputCantidad").style.display = "block";
                document.getElementById("divInput").style.display = "none";

                $("#modalAtributos").modal("show");
                document.getElementById('id_tabla_producto').value = data.id_tabla_producto; // Asignar el valor al input oculto

            } else if (data.response == "error") {
                sweet_alert_centrado("error", data.mensaje);
                alert(data.mensaje);
            }

        } catch (error) {
            console.error("Error en la petición:", error);
        }
    }

    function detener_propagacion(event) {
        event.stopPropagation(); // Detiene la propagación del evento al padre
    }
</script>


<script>
    function addDescuento(id) {

        document.getElementById('id_producto_pedido').value = id; // Asignar el valor al input oculto
        agregar_nota(id, event);
        $('#agregar_nota').modal('show');
        document.getElementById('operaciones').style.display = 'none';
        document.getElementById('nota').style.display = 'none';
        document.getElementById('navegacion').style.display = 'none';
        document.getElementById('descuento').style.display = 'block';



    }
</script>

<script>
    function listOfPrice(id) {

        document.getElementById('id_producto_pedido').value = id; // Asignar el valor al input oculto
        agregar_nota(id, event);
        $('#agregar_nota').modal('show');
        document.getElementById('operaciones').style.display = 'none';
        document.getElementById('nota').style.display = 'none';
        document.getElementById('navegacion').style.display = 'none';
        document.getElementById('descuento').style.display = 'none';
        document.getElementById('lista_precios').style.display = 'block';

        document.getElementById('id_producto_pedido').value = id; // Asignar el valor al input oculto

        mostrar_lista_precios()

    }
</script>