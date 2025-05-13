<?php $user_session = session(); ?>
<!-- Modal -->
<div class="modal fade" id="agregar_nota" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Opciones producto </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="cerrar_modal()"></button>
            </div>
            <div class="modal-body">

                <input type="hidden" id="id_producto_pedido">

                <div class="container">
                    <div id="informacion_producto"></div>
                </div>
                <hr>

                <div>
                    <div class="col">
                        <p class="text-start"> </p>
                    </div>
                </div>

                <nav aria-label="breadcrumb" id="navegacion">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item cursor-pointer" onclick="flecha()"><a type="button"><!-- Download SVG icon from http://tabler-icons.io/i/arrow-narrow-left -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                    <line x1="5" y1="12" x2="9" y2="16" />
                                    <line x1="5" y1="12" x2="9" y2="8" />
                                </svg></a></li>

                    </ol>
                </nav>
                <?php if ($user_session->tipo == 1 || $user_session->tipo == 0) { ?>
                    <div class="row" id="operaciones">
                        <div class="col-6 col-md-4 col-lg-2 col-2 d-none d-md-none d-lg-block ">

                        </div>
                        <div class="col-6 col-md-6 col-lg-2 mb-2" onclick="adicionar_nota()">
                            <label class="form-selectgroup-item">
                                <input type="radio" name="icons" value="home" class="form-selectgroup-input">
                                <span class="form-selectgroup-label">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/notes -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <rect x="5" y="3" width="14" height="18" rx="2" />
                                        <line x1="9" y1="7" x2="15" y2="7" />
                                        <line x1="9" y1="11" x2="15" y2="11" />
                                        <line x1="9" y1="15" x2="13" y2="15" />
                                    </svg>
                                    Nota </span>
                            </label>
                        </div>

                        <div class="col-6 col-md-6 col-lg-2 mb-2" onclick="descuento()">
                            <label class="form-selectgroup-item">
                                <input type="radio" name="icons" value="home" class="form-selectgroup-input">
                                <span class="form-selectgroup-label">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                        <path d="M12 3v3m0 12v3" />
                                    </svg>
                                    Descuento</span>
                            </label>
                        </div>

                        <div class="col-6 col-md-6 col-lg-2 mb-2">
                            <label class="form-selectgroup-item" onclick="mostrar_lista_precios()">
                                <input type="radio" name="icons" value="home" class="form-selectgroup-input">
                                <span class="form-selectgroup-label"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                    <!-- Download SVG icon from http://tabler-icons.io/i/list-numbers -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M11 6h9" />
                                        <path d="M11 12h9" />
                                        <path d="M12 18h8" />
                                        <path d="M4 16a2 2 0 1 1 4 0c0 .591 -.5 1 -1 1.5l-3 2.5h4" />
                                        <path d="M6 10v-6l-2 2" />
                                    </svg>
                                    Lista precios </span>
                            </label>
                        </div>
                        <div class="col-6 col-md-6 col-lg-2 mb-2">
                            <label class="form-selectgroup-item" onclick="cortesia()">
                                <input type="radio" name="icons" value="home" class="form-selectgroup-input">
                                <span class="form-selectgroup-label">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/gift -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <rect x="3" y="8" width="18" height="4" rx="1" />
                                        <line x1="12" y1="8" x2="12" y2="21" />
                                        <path d="M19 12v7a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-7" />
                                        <path d="M7.5 8a2.5 2.5 0 0 1 0 -5a4.8 8 0 0 1 4.5 5a4.8 8 0 0 1 4.5 -5a2.5 2.5 0 0 1 0 5" />
                                    </svg>
                                    Cortesia</span>
                            </label>
                        </div>
                        <div class="col-6 col-md-6 col-lg-2">

                        </div>

                    </div>
                <?php } ?>

                <?php if ($user_session->tipo == 2) { ?>
                    <div class="row" id="operaciones">
                        <div class="col-6 col-md-4 col-lg-2 col-2 d-none d-md-none d-lg-block ">

                        </div>
                        <div class="col-6 col-md-6 col-lg-2 mb-2" onclick="adicionar_nota()">
                            <label class="form-selectgroup-item">
                                <input type="radio" name="icons" value="home" class="form-selectgroup-input">
                                <span class="form-selectgroup-label">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/notes -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <rect x="5" y="3" width="14" height="18" rx="2" />
                                        <line x1="9" y1="7" x2="15" y2="7" />
                                        <line x1="9" y1="11" x2="15" y2="11" />
                                        <line x1="9" y1="15" x2="13" y2="15" />
                                    </svg>
                                    Nota </span>
                            </label>
                        </div>



                        <div class="col-6 col-md-6 col-lg-2">

                        </div>

                    </div>
                <?php } ?>



                <br>
                <div class="container" id="nota" style="display:none">
                    <label for="">Nota producto </label>
                    <div class="row">
                        <div class="col-12">
                            <textarea class="form-control " id="nota_producto_pedido" rows="3" placeholder="Escriba la nota del producto ejemplo: Hamburgesa sin cebolla "></textarea>
                        </div>
                    </div>
                    <br>



                    <div class="row">
                        <div class="d-none d-md-none  d-lg-block col-lg-6 "></div>
                        <div class="col-6 col-md-6 col-lg-3">
                            <button type="button" class="btn btn-outline-success btn-block w-100" onclick="actualizar_nota()">Guardar</button>
                        </div>
                        <div class="col-6 col-md-6 col-lg-3">
                            <button type="button" class="btn btn-outline-danger btn-block w-100" onclick="cerrar_modal()">Cancelar</button>
                        </div>
                    </div>






                </div>





                <div id="descuento" style="display:none ">

                    <p class="text-center text-primary h3">Tipo de descuento </p>
                    <div class="row" id="tipos_descuentos">
                        <div class="col-6 col-lg-4">
                            <a href="#" class="card card-link card-link-pop" onclick="descuento_porcentaje()">
                                <div class="card-body text-center">Descuento en porcentaje <!-- Download SVG icon from http://tabler-icons.io/i/percentage -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <circle cx="17" cy="17" r="1" />
                                        <circle cx="7" cy="7" r="1" />
                                        <line x1="6" y1="18" x2="18" y2="6" />
                                    </svg>
                                </div>
                            </a>
                        </div>

                        <div class="col-6  col-lg-4" style="margin-top: 2px;">
                            <a href="#" class="card card-link card-link-pop" onclick="abrir_descuento_manual()">
                                <div class="card-body text-center">Descuento en dinero <!-- Download SVG icon from http://tabler-icons.io/i/minus -->
                                    (<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <line x1="5" y1="12" x2="19" y2="12" />
                                    </svg>)
                                </div>
                            </a>
                        </div>

                        <div class="col-6  col-lg-4">
                            <a href="#" class="card card-link card-link-pop" onclick="editar_precio()">
                                <div class="card-body text-center">Editar precio <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                        <path d="M12 3v3m0 12v3" />
                                    </svg>
                                </div>
                            </a>
                        </div>


                    </div>
                </div>

                <div class="row" id="descuento_porcentaje" style="display:none">
                    <p class="text-center text-primary h3">Descuento en porcentaje </p>
                    <div class="row">

                        <div class="col-lg-block col-lg-3 "></div>

                        <div class="col-md-12 col-12 col-lg-6 ">
                            <div class="input-group">
                                <span class="input-group-text">Descuento en porcentaje </span>
                                <input type="number" aria-label="First name" class="form-control" placeholder="%" onkeyup="calcular_porcentaje(this.value)">
                                <input type="text" id="precio_producto" class="form-control" disabled>
                            </div>
                        </div>

                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-4 col-d-none"></div>
                        <div class="col-md-2 mb-2 mb-md-0 col-6">
                            <a href="#" class="btn btn-outline-success w-100" onclick="cerrar_modal()">
                                Confirmar
                            </a>
                        </div>
                        <div class="col-md-2 col-6">
                            <a href="#" class="btn btn-outline-danger w-100" onclick="cancelar_descuento()">
                                Cancelar
                            </a>
                        </div>
                        <div class="col-md-4"></div>
                    </div>

                </div>

                <div class="row" id="edicion_precio" style="display:none">
                    <p class="text-center text-primary h3">Cambio de precio manual</p>
                    <div class="row">
                        <div class="col-md-4 col-md-d"></div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">Cambiar precio</span>
                                <input type="number" aria-label="First name" class="form-control" id="cambio_manual" onkeyup="cambio_manual_precio(this.value)">
                                <input type="text" class="form-control" id="descuento_manual" disabled>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-d-none"></div>
                        <div class="col-md-2 mb-2 mb-md-0 col-6">
                            <a href="#" class="btn btn-outline-success w-100" onclick="cerrar_modal()">
                                Confirmar
                            </a>
                        </div>
                        <div class="col-md-2 col-6">
                            <a href="#" class="btn btn-outline-danger w-100" onclick="cancelar_descuento()">
                                Cancelar
                            </a>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>


                <div class="row" id="descuentos_manuales" style="display:none">

                    <p class="text-center text-primary h3">Descontar dinero del valor unitario </p>

                    <div class="row">
                        <div class="col-lg-4"></div>

                        <div class="col-lg-4">
                            <div class="input-group">
                                <span class="input-group-text">Descontar dinero </span>
                                <input type="number" aria-label="First name" class="form-control" id="descontar_dinero" onkeyup="descontar_dinero(this.value)">
                                <input type="text" class="form-control" id="restar_plata" disabled>
                            </div>
                        </div>

                    </div>

                    <br>

                    <div class="row">
                        <div class="col-lg-4 col-md-2 col-d-none"></div>

                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="#" class="btn btn-outline-success w-100" onclick="cerrar_modal()">
                                Confirmar
                            </a>
                        </div>

                        <div class="col-lg-2 col-md-4  mt-md-0 col-6">
                            <a href="#" class="btn btn-outline-danger w-100" onclick="cancelar_descuento()">
                                Cancelar
                            </a>
                        </div>

                        <div class="col-lg-4 col-md-2"></div>
                    </div>



                </div>


                <div id="lista_precios" style="display:none">

                    <p class="text-center text-primary h3">Lista de precios </p>
                    <div class="row">
                        
                        <div class="col-4 col-md-4  cursor-pointer" onclick="asignar_p1(1)" id="1">
                           <!--  <div class="card card-inactive ">
                                <div class="card-body">
                                    <p class="text-center h4 text-dark">Precio 1 </p>
                                    <p id="precio_1" class="text-center"></p>
                                </div>
                            </div> -->
                            <label for="inputEmail4" class="form-label">Precio 1 </label>
                            <a href="#" class="btn btn-outline-primary w-100" id="precio_1">
                                
                            </a> 

                        </div>
                        <div class="col-4 col-md-4 cursor-pointer" onclick="asignar_p1(2)" id="2">
                            <!-- <div class="card card-inactive">
                                <div class="card-body">
                                    <p class="text-center h4 text-dark">Precio 2 </p>
                                    <p id="precio_2" class="text-center"></p>
                                </div>
                            </div> -->

                            <label for="inputEmail4" class="form-label">Precio 2 </label>
                            <a href="#" class="btn btn-outline-primary w-100" id="precio_2">
                                
                            </a> 

                        </div>
                        <div class="col-4 col-md-4 cursor-pointer" onclick="asignar_p1(3)"  id="3">
                            <!-- <div class="card card-inactive">
                                <div class="card-body">
                                    <p class="text-center h4 text-dark">Precio 2 </p>
                                    <p id="precio_2" class="text-center"></p>
                                </div>
                            </div> -->

                            <label for="inputEmail4" class="form-label">Precio 3 </label>
                            <a href="#" class="btn btn-outline-primary w-100" id="precio_3" >
                                $ 0
                            </a> 

                        </div>

                    </div>
                    <br>
                </div>


            </div>

        </div>
    </div>
</div>