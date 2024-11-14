<br>
<tbody>
    <?php foreach ($documentos as $detalle) : ?>

        <?php $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $detalle['nit_cliente'])->first(); ?>

        <tr>
            <td><?php echo $detalle['fecha'] ?></td>
            <td><?php echo $detalle['nit_cliente'] ?></th>
            <td><?php echo $nombre_cliente['nombrescliente'] ?></td>

            <td><?php echo $detalle['numero'] ?></td>
            <td><?php echo "$ " . number_format($detalle['total'], 0, ",", ".") ?></td>
            <td>
                <button type="button" class="btn btn-outline-success btn-icon w-10" onclick="sendInvoice(<?php echo $detalle['id'] ?>)"><!-- Download SVG icon from http://tabler-icons.io/i/arrows-down-up -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="17" y1="3" x2="17" y2="21" />
                        <path d="M10 18l-3 3l-3 -3" />
                        <line x1="7" y1="21" x2="7" y2="3" />
                        <path d="M20 6l-3 -3l-3 3" />
                    </svg></button>
                <button type="button" class="btn btn-outline-success btn-icon w-10" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tooltip on bottom" onclick="imprimir_electronica(<?php echo $detalle['id'] ?>)"><!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                        <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                        <rect x="7" y="13" width="10" height="8" rx="2" />
                    </svg> </button>
                <button type="button" class="btn btn-outline-secondary btn-icon w-10" onclick="detalle_f_e(<?php echo $detalle['id'] ?>)"><!-- Download SVG icon from http://tabler-icons.io/i/eyeglass -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 4h-2l-3 10" />
                        <path d="M16 4h2l3 10" />
                        <line x1="10" y1="16" x2="14" y2="16" />
                        <path d="M21 16.5a3.5 3.5 0 0 1 -7 0v-2.5h7v2.5" />
                        <path d="M10 16.5a3.5 3.5 0 0 1 -7 0v-2.5h7v2.5" />
                    </svg></button>

                <?php if ($detalle['pdf_url'] != "") {
                ?>

                    <a href="<?php echo $detalle['pdf_url'];  ?>" target="_blank" class="cursor-pointer">
                        <img title="Descargar pdf " src="<?php echo base_url() ?>/Assets/img/pdf.png" width="40" height="40" />
                    </a>

                <?php  } ?>
            </td>
        </tr>
    <?php endforeach ?>
</tbody>


</div>


<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/f_e.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/imprimir_electronica.js"></script>


<script>

</script>