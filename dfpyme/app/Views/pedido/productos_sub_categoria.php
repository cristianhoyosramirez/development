<div class="row">
    <?php foreach ($productos as $valor) : ?>

        
      <!--   <div class="col-12 col-md-4 col-lg-4 mb-4"> -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-4">
            <br>
            <div class="card card-sm bg-azure-lt">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="chart-sparkline chart-sparkline-square" id="sparkline-orders"></div>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium text-center">
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
                </div> 
            </div>
        </div>
    
    <?php endforeach; ?>
</div>