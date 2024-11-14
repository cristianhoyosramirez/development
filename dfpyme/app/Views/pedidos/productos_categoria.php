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
 <?php foreach ($productos as $valor) : ?>
    
     <div class="cursor-pointer mb-1 elemento " onclick="agregar_al_pedido(<?php echo $valor['codigointernoproducto'] ?>)">
         <div class="row">
          
             <div class="col">
                 <div class="text-truncate" title="<?php echo $valor['nombreproducto'] ?>">
                     <strong><?php echo $valor['nombreproducto'] ?></strong>
                 </div>
                 <div class="text-muted"><?php echo "$" . number_format($valor['valorventaproducto'], 0, ",", ".") ?></div>
             </div>

         </div>
         <hr class="my-1"> 
     </div>
 <?php endforeach ?>