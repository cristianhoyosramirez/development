  <!-- Offcanvas de Mesas -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="mesasOffcanvas" style="width: 100%; height: 100vh;">
      <div class="offcanvas-header">
          <h5 class="offcanvas-title">Mesas</h5>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
          <!-- Aquí puedes agregar tu contenido de mesas, como las tarjetas de mesa -->
          <!-- Ejemplo de una tarjeta de mesa: -->

          <div class="row">
              <div class="col-lg-6 col-6 col-md-6">
                  <div class="input-icon">
                      <input type="text" value="" class="form-control form-control-rounded" placeholder="Buscar mesa" onkeyup="buscar_mesas(this.value)">
                      <span class="input-icon-addon">

                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                              <path d="M21 21l-6 -6" />
                          </svg>
                      </span>
                  </div>
              </div>
              <div class="col-lg-6 col-6 col-md-6">
                  <div class="input-icon">
                      <input type="text" value="" class="form-control form-control-rounded" placeholder="Buscar mesero " onkeyup="buscar_meseros(this.value)">
                      <span class="input-icon-addon">

                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                              <path d="M21 21l-6 -6" />
                          </svg>
                      </span>
                  </div>
              </div>
          </div>

          <div class="my-2"></div>
          <div id="resultado_mesa">
        
              <div class="row gx-3 gy-2">
                  <?php foreach ($mesas as $detalle) : ?>

                      <?php $tiene_pedido = model('pedidoModel')->pedido_mesa($detalle['id']);  ?>
                      <div class="col-6 col-sm-4 col-lg-3 col-xl-2 col-xxl-3">
                          <?php if (empty($tiene_pedido)) : ?>



                              <div class="card card_mesas text-white bg-green-lt cursor-pointer" onclick="pedido('<?php echo $detalle['id'] ?>','<?php echo $detalle['nombre'] ?>')">
                                  <div class="row ">
                                      <div class="col-3">
                                          <span class="avatar">
                                              <img src="<?php echo base_url(); ?>/Assets/img/ocupada.png" width="110" height="32" alt="Macondo" class="navbar-brand-image">
                                          </span>
                                      </div>
                                      <div class="col-9">
                                          <div class="text-truncate text-center">
                                              <strong class="text-truncate text-center small"><?php echo $detalle['nombre'] ?></strong><br>

                                          </div>
                                      </div>
                                  </div>
                              </div>





                          <?php endif ?>


                          <?php if (!empty($tiene_pedido)) : ?>


                              <div class="card card_mesas text-white bg-red-lt cursor-pointer" onclick="pedido_mesa('<?php echo $detalle['id'] ?>','<?php echo $detalle['nombre'] ?>')">

                                  <div class="row">

                                      <div class="col-3">
                                          <span class="avatar">
                                              <img src="<?php echo base_url(); ?>/Assets/img/ocupada.png" width="110" height="32" alt="Macondo" class="navbar-brand-image">
                                          </span>
                                      </div>
                                      <div class="col-9">
                                          <div class="text-truncate text-center">

                                              <strong class="text-truncate text-center small"><?php echo $detalle['nombre']
                                                                                                ?></strong><br>
                                              <strong class="text-truncate text-center small"><?php echo "$ " . number_format($tiene_pedido[0]['valor_total']+$tiene_pedido[0]['propina'], 0, ",", ".")
                                                                                                ?></strong><br>
                                              <strong class="text-truncate text-center small"><?php echo $tiene_pedido[0]['nombresusuario_sistema']
                                                                                                ?></strong>
                                          </div>
                                      </div>
                                  </div>

                              </div>

                          <?php endif ?>


                      </div>



                  <?php endforeach ?>
              </div>
          </div>

          <!-- Repite la estructura de la tarjeta para cada mesa -->
      </div>

      <div class="offcanvas-footer">
          <!-- Contenido del footer del offcanvas 
                <?php  #$total=model('pedidoModel')->selectSum('valor_total')->findAll(); 
                ?> 
            <p class="text-end">Total:</p>-->

      </div>
  </div>