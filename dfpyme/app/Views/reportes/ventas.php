<style type="text/css">
    thead tr td {
        position: sticky;
        top: 0;
        background-color: #ffffff;
    }

    .table-responsive {
        height: 350px;
        overflow: scroll;
    }
</style>


<div class="row">
    <div class="col-10">

    </div>
    <div class="col-2 text-end">
        <form action="<?php echo base_url() ?>/reportes/exportar_excel" method="post">
            <input type="hidden" value="<?php echo $id_apertura ?>" name="id_apertura" id="id_apertura">
            <button type="submit" class="btn btn-outline-success  btn-icon">
                Excel
            </button>
        </form>
    </div>
</div>

<br>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <td scope="col">FECHA</th>
                <td scope="col">HORA</th>
                <td scope="col">DOCUMENTO</th>
                <td scope="col">VALOR VENTA</th>
                <td scope="col">PROPINA</th>
                <td scope="col">(V VENTA + PROPINA) </th>
                <td scope="col">EFECTIVO</th>
                <td scope="col">TRANSFERENCIA</th>
                <td scope="col">TOTAL MEDIO DE  PAGO</th>
                <!-- <td scope="col">CAMBIO </th> -->
                <td scope="col">USUARIO </th>
                <td scope="col">Accion </th>
            </tr>
        </thead>
        <tbody class="table-scroll">
            <?php foreach ($movimientos as $valor) :

                $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $valor['id_mesero'])->first();

            ?>


                <tr>
                    <td><?php echo $valor['fecha'] ?></td>

                    <td><?php echo date("h:i A", strtotime($valor['hora'])) ?></td>
                    <td><?php echo $valor['documento'] ?></td>
                    <td><?php echo "$ " . number_format($valor['valor'], 0, ",", ".") ?></td>
                    <td><?php echo "$ " . number_format($valor['propina'], 0, ",", ".") ?></td>
                    <td><?php echo "$ " . number_format($valor['total_documento'], 0, ",", ".") ?></td>
                    <td><?php echo "$ " . number_format($valor['recibido_efectivo'], 0, ",", ".") ?></td>
                    <td><?php echo "$ " . number_format($valor['recibido_transferencia'], 0, ",", ".") ?></td>
                    <td><?php echo "$ " . number_format($valor['total_pago'], 0, ",", ".") ?></td>
                    <!-- <td><?php #echo "$ " . number_format($valor['cambio'], 0, ",", ".") ?></td> -->
                    <td><?php echo $nombre_usuario['nombresusuario_sistema'] ?></td>
                    <td><a href="#" class="btn btn-outline-success  btn-icon" onclick="editar_pago(<?php echo $valor['id'] ?>)">
                            <!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                            </svg>
                        </a></td>
                </tr>
            <?php endforeach ?>
        </tbody>

    </table>
</div>

<script>
    function editar_pago(id) {

        $("#modal_propinas").modal("hide");
        

        var url = document.getElementById("url").value;

        $.ajax({
            data:{id},
            url: url +
                "/" +
                "reportes/datos_pagos",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    
                 $('#efectivo_factura').val(resultado.efectivo)
                 $('#transferencia_factura').val(resultado.transferencia)
                 $('#id_factura').val(resultado.id)
                 $("#editar_pagos").modal("show");



                }
            },
        });
    }
</script>