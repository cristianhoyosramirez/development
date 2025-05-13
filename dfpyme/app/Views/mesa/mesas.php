<div class="container">
    <?php foreach ($mesas as $detalle): ?>
        <div class="row g-3"> <!-- Cada tarjeta en una fila nueva -->
            <div class="col"> <!-- Columna única por fila -->
                <a href="#" class="card card-link card-link-pop bg-red-lt text-white cursor-pointer" 
                onclick="pedido_mesa('<?php echo $detalle['id_mesa'] ?>','<?php echo $detalle['nombre'] ?>')">
                    <div class="card-body">
                        <?php
                            setlocale(LC_TIME, 'es_ES.UTF-8');

                            $datetime = new DateTime($detalle['fecha']);
                            $dias = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
                            $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

                            $diaSemana = $dias[$datetime->format('w')];
                            $dia = $datetime->format('j');
                            $mes = $meses[$datetime->format('n') - 1];
                            $hora = $datetime->format('g:i');
                            $meridiano = $datetime->format('a') == 'am' ? 'a.m.' : 'p.m.';
                            ?>
                        
                        <tr class="table table-borderless text-white "
                         
                         >
                            
                            <td class="text-secondary"><span class="text-dark"><?php echo $detalle['nombre'] . "  " . "$diaSemana $dia de $mes $hora $meridiano"; ?></td>
                            
                            <td class="text-success"><span class="text-dark">Valor Total:</td>
                            <td class="text-warning"><?php echo number_format($detalle['valor_total'], 0, ',', '.'); ?></td>
                            <td class="text-info"><span class="text-dark">Mesero</td>
                            <td class="text-danger"><span class="text-dark"><?php echo $detalle['usuario']; ?></td>
                            <td class="text-muted"><span class="text-dark">Nota</td>
                            <td class="text-light"><span class="text-dark"><?php echo $detalle['nota_pedido']; ?></td>
                        </tr>

                    </div>
                </a>
                <div class="mb-3"></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
