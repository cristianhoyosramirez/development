<style>
    /* Establecer el ancho del toast */
    .swal2-toast {
        width: 200px;
        /* Ajusta el ancho según tus preferencias */
    }
</style>

<!-- Agrega esta clase CSS -->
<style>
    .producto-seleccionado {
        background-color: #fff;
        /* Cambia el color de fondo al que prefieras */
    }
</style>


<style>
    .visually-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        margin: -1px;
        padding: 0;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }
</style>



<?php $alturaCalc = "37rem + 10px"; // Calcula la altura 
?>

<!-- <div class="offcanvas offcanvas-start" style="width: 100%; height: 100vh;" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel"> -->
<div class="offcanvas offcanvas-start " style="width: 100%; height: 100vh;" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" data-bs-scroll="true">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Productos</h5>
        <button type="button" data-bs-dismiss="offcanvas" aria-label="Close" onclick="mesas_actualizadas()" style="background-color: transparent; border: none; cursor: pointer;">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <line x1="5" y1="12" x2="19" y2="12" />
                <line x1="5" y1="12" x2="9" y2="16" />
                <line x1="5" y1="12" x2="9" y2="8" />
            </svg>
            <span class="visually-hidden">Cerrar</span> <!-- Oculta el texto del botón para accesibilidad -->
        </button>

    </div>



    <div class="offcanvas-body">
        <ul class="horizontal-list">
            <?php foreach ($categorias as $detalle) : ?>

                <li><button type="button" class="btn btn-outline-indigo btn-pill btn-sm" id="categoria_<?php echo $detalle['codigocategoria'] ?>" onclick="productos_categoria(<?php echo $detalle['codigocategoria'] ?>)"><?php echo $detalle['nombrecategoria'] ?></button></li>

            <?php endforeach ?>
        </ul>
        <div id="sub_categorias"></div>
        <div>
            <div class="card" style="height: calc(<?php echo $alturaCalc; ?>)">
                <div class="card-header border-0" style="margin-bottom: -10px; padding-bottom: 0;" id="pedido">
                    <div class="card-title">
                        <div class="mb-3">
                            <div class="input-group input-group-flat">
                                <input type="text" class="form-control " id="producto" autocomplete="off" placeholder="Buscar por nombre o código " onkeyup="buscar_productos(this.value)">
                                <span class="input-group-text">
                                    <a href="#" class="link-secondary" title="Limpiar campo" data-bs-toggle="tooltip" onclick="limpiarCampo()"><!-- Download SVG icon from http://tabler-icons.io/i/x -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M18 6l-12 12" />
                                            <path d="M6 6l12 12" />
                                        </svg>
                                    </a>
                                </span>
                            </div>
                        </div>
                        <span id="error_producto" style="color: red;"></span>
                    </div>
                </div>
                <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                    <div id="productos_categoria"></div>


                    <div class="row " id="canva_producto">
                        <?php $productos = model('productoModel')->orderBy('nombreproducto', 'asc')->findAll();
                        foreach ($productos as $valor) : ?>
                            <div class="col-12 col-sm-6 col-lg-4 col-xl-4 ">



                                <div class="card card-sm bg-azure-lt">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <div class="chart-sparkline chart-sparkline-square" id="sparkline-orders"></div>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-bold text-center">
                                                    <?php echo $valor['nombreproducto'] ?>
                                                </div>
                                                <div class="text-muted text-center">
                                                    <span id="saldo"><?php echo "$ " . number_format($valor['valorventaproducto'], 0, ",", ".") ?></span>
                                                    <div class="row">
                                                        <div class="col-md">
                                                            <div class="input-group">
                                                                <button class="btn bg-muted-lt btn-icon" onclick="decrementarCantidad(event,<?php echo $valor['id'] ?>)">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                        <line x1="5" y1="12" x2="19" y2="12" />
                                                                    </svg>
                                                                </button>
                                                                <input type="number" class="form-control form-control-sm text-center cantidad-input" value="1" id="<?php echo $valor['id']; ?>"  inputmode="numeric">
                                                                <button class="btn bg-muted-lt btn-icon" onclick="incrementarCantidad(event,<?php echo $valor['id'] ?>)">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                        <line x1="12" y1="5" x2="12" y2="19" />
                                                                        <line x1="5" y1="12" x2="19" y2="12" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-auto mt-3 mt-md-0">
                                                            <div class="input-group">
                                                                <button class="btn btn-outline-success btn-icon" onclick="agregar_al_pedido_celular(<?php echo $valor['codigointernoproducto'] ?>,<?php echo $valor['id'] ?>)">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-bag">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                        <path d="M6.331 8h11.339a2 2 0 0 1 1.977 2.304l-1.255 8.152a3 3 0 0 1 -2.966 2.544h-6.852a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304z" />
                                                                        <path d="M9 11v-5a3 3 0 0 1 6 0v5" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <br>


                                <!--  <div class="card bg-azure-lt">
                                    <div class="row g-0">
                                        <div class="col-auto">
                                            <div class="card-body"></div>
                                        </div>
                                        <div class="col">
                                            <div class="card-body ps-0">
                                                <div class="row align-items-center">
                                                    <div class="col-md">
                                                        <h3 class="mb-0">fff<a href="#"><?php echo $valor['nombreproducto'] ?></a></h3>
                                                        <div class="mt-3 list-inline list-inline-dots mb-0 text-muted">
                                                            <?php echo "$ " . number_format($valor['valorventaproducto'], 0, ",", ".") ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md">
                                                        <div class="input-group">
                                                            <button class="btn bg-muted-lt btn-icon" onclick="decrementarCantidad(event,<?php echo $valor['id'] ?>)">
                                                                
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <line x1="5" y1="12" x2="19" y2="12" />
                                                                </svg>
                                                            </button>
                                                            
                                                            <input type="number" class="form-control form-control-sm text-center cantidad-input" value="1" id="<?php echo $valor['id']; ?>" onclick="resaltarInput(this)" inputmode="numeric">

                                                            <button class="btn bg-muted-lt btn-icon" onclick="incrementarCantidad(event,<?php echo $valor['id'] ?>)">
                                                               
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <line x1="12" y1="5" x2="12" y2="19" />
                                                                    <line x1="5" y1="12" x2="19" y2="12" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-auto mt-3 mt-md-0">
                                                        <div class="input-group">
                                                            <button class="btn btn-outline-success btn-icon" onclick="agregar_al_pedido_celular(<?php echo $valor['codigointernoproducto'] ?>,<?php echo $valor['id'] ?>)">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-bag">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M6.331 8h11.339a2 2 0 0 1 1.977 2.304l-1.255 8.152a3 3 0 0 1 -2.966 2.544h-6.852a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304z" />
                                                                    <path d="M9 11v-5a3 3 0 0 1 6 0v5" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                                <br>

                            </div>

                        <?php endforeach ?>

                    </div>

                </div>
            </div>
        </div>

    </div>

</div>

<script>
    var offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasExample'));
</script>