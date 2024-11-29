<table class="table">
    <thead class="table-dark">
        <tr>
            <td scope="col">codigo intero </th>
            <td scope="col">Producto </th>
            <td scope="col">Accion </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $detalle) : ?>
            <tr>
                <td><?php echo $detalle['codigointernoproducto'] ?> </th>
                <td><?php echo $detalle['nombreproducto'] ?></td>
                <td>

                    <a href="#" class="btn btn-outline-success  btn-icon" title="Activar " onclick="activar(<?php echo $detalle['codigointernoproducto'] ?> )">
                        <!-- Download SVG icon from http://tabler-icons.io/i/arrow-big-top -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M9 20v-8h-3.586a1 1 0 0 1 -.707 -1.707l6.586 -6.586a1 1 0 0 1 1.414 0l6.586 6.586a1 1 0 0 1 -.707 1.707h-3.586v8a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                        </svg>
                    </a>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<script>
    function activar(codigo) {
        var url = document.getElementById("url").value;


        $.ajax({
            data: {
                codigo,
            },
            url: url + "/" + "reportes/activar_producto",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    table = $('#example').DataTable();
                    table.draw();

                    sweet_alert_start('success', 'Propina actualizada  ');


                }
            },
        });
    }
</script>