       <div class="container">
           <div class="row gx-1">

               <?php

                $dias = array('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
                $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

                $dia_inicio = $dias[(date('N', strtotime($fecha_apertura))) - 1];
                $mes_inicio = $meses[(date('m', strtotime($fecha_apertura))) - 1];

                $dia_final = $dias[(date('N', strtotime($fecha_cierre))) - 1];
                $mes_final = $meses[(date('m', strtotime($fecha_cierre))) - 1];

                ?>

               <div class="col">
                   <p class="h3">APERTURA: <?php echo $dia_inicio . ", " . date("d", strtotime($fecha_apertura)) . " " . $mes_inicio . " " . date("Y", strtotime($fecha_apertura)) . " " . $fecha_inicial_format;
                                            ?></p>
               </div>

               <?php if ($fecha_cierre != "Sin cierre") { ?>
                   <div class="col">
                       <p class="h3">CIERRE: <?php echo $dia_final . ", " . date("d", strtotime($fecha_cierre)) . " " . $mes_final . " " . date("Y", strtotime($fecha_cierre)) . " " . $fecha_final_format;
                                                ?></p>
                   </div>
               <?php } ?>
               <?php if ($fecha_cierre == "Sin cierre") { ?>
                   <div class="col">
                       <p class="h3">CIERRE: SIN CIERRE </p>
                   </div>
               <?php } ?>

               <div class="col-2 ">
                   <form action="<?= base_url('consultas_y_reportes/datos_consultar_producto_agrupado_pdf') ?>">
                       <?php
                        if (!empty($id_apertura)) {
                            $id_apertura = $id_apertura;
                        }
                        if (empty($id_apertura)) {
                            $id_apertura = "";
                        }
                        ?>
                       <input type="hidden" value="<?php echo $id_apertura ?>" id="id_apertura" name="id_apertura">
                       <input type="hidden" value="<?php echo $fecha_inicial ?>" id="fecha_inicial_reporte" name="fecha_inicial_agrupado">
                       <input type="hidden" value="<?php echo $fecha_final ?>" id="fecha_final_reporte" name="fecha_final_agrupado">
                       <input type="hidden" value="<?php echo $hora_inicial ?>" id="hora_inicial_reporte" name="hora_inicial_agrupado">
                       <input type="hidden" value="<?php echo $hora_final ?>" id="hora_final_reporte" name="hora_final_agrupado">
                       <div class="row">
                           <div class="col">
                               <button type="button" class="btn btn-outline-success btn-icon text-end" onclick="imprimir_reporteSinCantidades()">Imprimir</button>

                           </div>
                           <!-- <div class="col">

                               <button type="submit" class="btn btn-outline-danger btn-icon text-end">Pdf</button>
                           </div> -->
                       </div>
                   </form>
               </div>
               <!--  <div class="col-1 w-5">
                   <form action="<?= base_url('caja/exportar_a_excel_reporte_categorias') ?>" method="POST">
                       <input type="hidden" value="<?php echo $id_apertura ?>" id="id_apertura" name="id_apertura">
                       <input type="hidden" value="<?php echo $fecha_inicial ?>" id="fecha_inicial_reporte" name="fecha_inicial_agrupado">
                       <input type="hidden" value="<?php echo $fecha_final ?>" id="fecha_final_reporte" name="fecha_final_agrupado">
                       <input type="hidden" value="<?php echo $hora_inicial ?>" id="hora_inicial_reporte" name="hora_inicial_agrupado">
                       <input type="hidden" value="<?php echo $hora_final ?>" id="hora_final_reporte" name="hora_final_agrupado">
                       <button type="submit" class="btn btn-success btn-icon">Excel</button>
                   </form>
               </div> -->

           </div>




           <table class="table" id="consulta_producto_por_fecha">
               <thead>
                   <tr>
                       <td></td>

                   </tr>
               </thead>
               <tbody>
                   <table>
                       <tr>
                       </tr>
                   </table>
                   <tr>
                       <table class="table table-hover ">
                           <thead class="table-light">
                               <tr>
                                   <td scope="col">
                                       </th>
                                   <td scope="col">
                                       </th>
                                   <td scope="col">
                                       </th>
                                   <td scope="col">
                                       </th>

                               </tr>
                           </thead>



                           <tbody>
                               <?php foreach ($categorias as $detalle) {  //echo $detalle['id_categoria']."</br>";
                                    $nombre_categoria = model('categoriasModel')->select('nombrecategoria')->where('codigocategoria', $detalle['id_categoria'])->first();
                                ?>

                                   <tr class="table-primary">

                                       <td><?php echo $nombre_categoria['nombrecategoria']
                                            ?></td>
                                       <td></td>
                                       <td></td>
                                      
                                   </tr>

                                   <tr class="table-dark">
                                       <td>CÓDIGO</td>
                                       <td>PRODUCTO</td>
                                       <td>CANTIDAD</td>
                                       <!-- <td>VALOR UNIDAD</td>
                                       <td>TOTAL</td> -->
                                   </tr>


                                   <?php $productos = model('reporteProductoModel')->where('id_categoria', $detalle['id_categoria'])->orderBy('codigo_interno_producto', 'asc')->find();  ?>

                                   <?php foreach ($productos as $valor) : ?>
                                       <tr>
                                           <td><?php echo $valor['codigo_interno_producto']
                                                ?></td>
                                           <td><?php echo $valor['nombre_producto']
                                                ?></td>
                                           <td><?php echo $valor['cantidad']
                                                ?></td>
                                           <!-- <td><?php #echo "$" . number_format($valor['valor_unitario'], 0, ",", ".")
                                                ?></td>
                                           <td><?php #echo "$" . number_format($valor['valor_total'], 0, ",", ".")
                                                ?></td> -->
                                       </tr>

                                   <?php endforeach ?>
                                  
                               <?php } ?>
                           </tbody>
                       </table>
                   </tr>
               </tbody>
           </table>

          

           <?php if (!empty($devoluciones)) { ?>

               <div class="row">
                   <p class="text-primary fs-3">DEVOLUCIONES </p>

                   <table class="table" id="consulta_producto_por_fecha_devolucion">
                       <thead class="table-dark">
                           <tr>
                               <td scope="col">Código </th>
                               <td scope="col">Nombre producto</th>
                               <td scope="col">Cantidad</th>
                               <td scope="col">Valor unitario</th>
                               <td scope="col">Valor total</th>
                           </tr>
                       </thead>
                       <?php foreach ($devoluciones as $detalle) {

                            $detalle_devolucion = model('detalleDevolucionVentaModel')->detalle_devolucion($detalle['id_apertura']);

                        ?>

                           <tr>
                               <td><?php echo $detalle['codigo'] ?></td>
                               <td><?php echo $detalle_devolucion[0]['nombreproducto'] ?></td>
                               <td><?php echo $detalle['cantidad'] ?></td>
                               <td><?php echo  "$ " . number_format($detalle['valor_total_producto'] / $detalle['cantidad'], 0, ",", "."); ?></td>
                               <td><?php echo "$ " . number_format($detalle['valor_total_producto'], 0, ",", ".") ?></td>
                           </tr>

                       <?php } ?>
                   </table>
                   <?php $total_devoluciones = model('detalleDevolucionVentaModel')->selectSum('valor_total_producto')->where('id_apertura', $id_apertura)->findAll(); ?>


                   <p class="text-end text-dark h1">

                       TOTAL DEVOLUCIONES :<?php echo "$" . number_format($total_devoluciones[0]['valor_total_producto'], 0, ",", "."); ?>
                       <?php model('reporteProductoModel')->truncate(); ?>
                   </p>
               </div>
           <?php } ?>
       </div>



       <script>
           function imprimir_reporteSinCantidades() {

               let url = document.getElementById("url").value;
               let id_apertura = document.getElementById("id_apertura").value;

               $.ajax({
                   data: {

                       id_apertura,
                   },

                   url: url + "/" + "pedidos/reporte_ventasSinCantidades",
                   type: "POST",
                   success: function(resultado) {
                       var resultado = JSON.parse(resultado);
                       if (resultado.resultado == 1) {
                           sweet_alert_start('success', 'Impresión de comanda éxitoso')
                       }
                       if (resultado.resultado == 0) {

                           sweet_alert_start('warning', 'No hay productos para imprimir ')
                       }
                   },
               });
           }
       </script>