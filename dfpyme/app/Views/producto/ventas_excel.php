<?php
header("Pragma: public");
header("Expires: 0");
$filename = "Reporte de producto .xls";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

?>


<style>
    table {
        width: 100%;
        margin: 10px auto;
        border-collapse: collapse;
    }

    thead {
        background-color: lightgray;
        color: black;
    }

    th,
    td {

        border: 1px solid #666666;
    }
</style>





<table>
    
    <tbody>
    <tr>Total de ventas <?php  echo $total ?></tr>
        <?php foreach ($id as $detalle) : ?>
            <?php

            $numero_factura = model('pagosModel')->select('documento')->where('id', $detalle['id'])->first();
            $valor_factura = model('pagosModel')->select('total_documento')->where('id', $detalle['id'])->first();

            ?>
            <tr>
                

            </tr>

            <?php
            $id_factura = model('pagosModel')->select('id_factura')->where('id', $detalle['id'])->first();
            $productos = model('kardexModel')->where('id_factura', $id_factura['id_factura'])->findAll();
            $total_productos = model('kardexModel')->selectSum('total')->where('id_factura', $id_factura['id_factura'])->findAll();
            ?>

            <!-- Nueva fila para imprimir los códigos -->
            <tr>
                <td> <!-- Usa colspan="2" para que el <td> ocupe dos columnas -->
                    <!--  <?php #foreach ($productos as $valor) { 
                            ?>
                        <?php  #$nombre_producto= model('productoModel')->select('nombreproducto')->where('codigointernoproducto',$valor['codigo'])->first(); 
                        ?>
                        <?php #echo "Codigo ".$valor['codigo']."    ".$nombre_producto['nombreproducto']."    ".$valor['cantidad'] 
                        ?><br>
                    <?php #} 
                    ?> -->

                    <?php echo  $total_productos[0]['total']; ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>