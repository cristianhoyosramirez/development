 <?php $estados = model('estadoModel')->estados();
    ?>
 
     <select class="form-select" id="documento" name="documento" onchange="habilitarBotonPago()">
         <?php foreach ($estados as $detalle) {
            ?>
             <option value="<?php echo $detalle['idestado']
                            ?>"><?php echo $detalle['descripcionestado']
                                ?></option>
         <?php }
            ?>
     </select>
     <p class="text-danger h3" id="error_documento"></p>
 