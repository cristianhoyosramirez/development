  <!-- CSS files -->
  <link href="<?= base_url() ?>/public/css/tabler.min.css" rel="stylesheet" />
  <style>
      /* estilos.css */

      /* Estilo inicial del elemento */
      .elemento {
          /*background-color: #ccc;*/
          padding: 10px;
          transition: background-color 0.3s;
          /* Agrega una transición suave */
      }

      /* Estilo cuando el mouse está sobre el elemento */
      .elemento:hover {
          background-color: #f0f0f0;
          /* Cambia el color de fondo en hover */
      }
  </style>

  <?php foreach ($id_sub_categoria as $detalle) : ?>
      <div class="accordion " id="accordionPanelsStayOpenExample">


          <div class="accordion-item">
              <h2 class="accordion-header" id="panelsStayOpen-headingThree">

                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $detalle['id_sub_categoria'] ?>" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                      <p class="text-primary"> <?php $nombre_subcategoria = model('subCategoriaModel')->select('nombre')->where('id', $detalle['id_sub_categoria'])->first();
                                               
                                                echo $nombre_subcategoria['nombre'];
                                                ?></p>
                  </button>
                  
              </h2>  
              
              <div id="<?php echo $detalle['id_sub_categoria'] ?>" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
              
              <div class="accordion-body">
                      <?php $productos = model('subCategoriaModel')->get_productos_sub_categoria($detalle['id_sub_categoria']) ?>
                      
                      <?php foreach ($productos as $valor) : ?>

                          <div class="cursor-pointer  elemento " onclick="agregar_al_pedido(<?php echo $valor['codigointernoproducto'] ?>)">
                              <div class="row">

                                  <div class="col" title="<?php echo $valor['nombreproducto'] ?>">
                                      <div class="text-truncate">
                                          <strong><?php echo $valor['nombreproducto'] ?></strong>
                                      </div>
                                      <div class="text-muted"><?php echo "$" . number_format($valor['valorventaproducto'], 0, ",", ".") ?></div>
                                  </div>

                              </div>
                              <hr class="my-1"> <!-- Línea de separación -->

                              <br>
                          </div>

                      <?php endforeach ?>
                  </div>
              </div>
          </div>
          <div class="mb-2"></div> <!-- Empty div for space -->
      </div>
  <?php endforeach ?>