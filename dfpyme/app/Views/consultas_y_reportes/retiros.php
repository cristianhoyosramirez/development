<table class="table">
    <thead class="table-dark">
        <tr>
            <td scope="col">Fecha</th>
            <td scope="col">Concepto</th>
            <td scope="col">Valor</th>
            <td scope="col">Accion</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($retiros as $detalle) { ?>
            <tr>
                <td><?php echo $detalle['fecha']; ?></th>
                <td><?php echo $detalle['concepto']; ?></td>
                <td><?php echo "$" . number_format($detalle['valor'], 0, ",", "."); ?> </td>
                <td>
                    <div class="row gx-5">
                        <div class="col-3"><button type="button" class="btn bg-green-lt btn-icon" title="Reimprimir retiro" onclick="imprimir_duplicado_retiro(<?php echo $detalle['id'] ?>)"><!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                                    <rect x="7" y="13" width="10" height="8" rx="2" />
                                </svg></button></div>

                        <div class="col-1"><button type="button" class="btn bg-orange-lt btn-icon" title="Editar retiro" onclick="edicion_retiro_de_dinero(<?php echo $detalle['id'] ?>)"><!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                                <!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                    <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                </svg></button></div>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<p class="text-primary h3 text-end"><?php echo $total_retiros; ?></p>