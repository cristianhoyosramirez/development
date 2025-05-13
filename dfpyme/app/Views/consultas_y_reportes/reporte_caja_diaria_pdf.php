<style>
    table {
        width: 100%;
        margin: 30px auto;
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



    textarea {
        height: 10em;
        width: 45em;
        font-family: "oblique bold cursive";
        font-size: 80%;


    }
</style>
<div class="card container">
    <div class="card-body">

        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo $nombre_comercial ?></th>
                    <th style="text-align:left; font: oblique bold 80% cursive; border:none "></th>
                    <th style="text-align:left; font: oblique bold 80% cursive; border:none ">INFORME FISCAL DE VENTAS DIARIAS</th>
                </tr>
                <tr>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo $nombre_juridico ?></td>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none "></td>
                    <th style="text-align:left; font: oblique bold 80% cursive; border:none ">Caja N° 1</th>
                </tr>
                <tr>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">Nit: <?php echo $nit ?></td>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none "> </td>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">Fecha: <?php echo $fecha; ?></td>
                </tr>
                <tr>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo $nombre_regimen ?></td>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none "></td>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none "></td>
                </tr>
                <tr>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo $direccion . " " . $nombre_ciudad . " " . $nombre_departamento ?></td>
                </tr>
            </tbody>
        </table>



        <table class="table table-borderless">
            <tbody>

            </tbody>
        </table>
        <hr>
        <p style="text-align:left; font: oblique bold 80% cursive;"> BASE 0</p>
        <table class="table">
            <thead>
                <tr>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">Tarifa </th>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">Base grabable</th>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">Valor impuesto</th>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">Valor total </th>
                </tr>
            </thead>
            <tbody>
                <th style="text-align:left; font: oblique bold 80% cursive; border:none">0%</th> <!-- TARIFA IVA  -->
                <th style="text-align:left; font: oblique bold 80% cursive; border:none">0</th> <!-- TARIFA IVA  -->
                <th style="text-align:left; font: oblique bold 80% cursive; border:none">0</th> <!-- TARIFA IVA  -->
                <th style="text-align:left; font: oblique bold 80% cursive; border:none"><?php echo "$" . number_format($base_cero, 0, ",", ".")  ?></th> <!-- TARIFA IVA  -->
            </tbody>
        </table>
        <p style="text-align:left; font: oblique bold 80% cursive;"> IMPUESTO AL CONSUMO</p>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">Tarifa </th>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">Base grabable</th>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">Valor ICO</th>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">Valor total</th>
                </tr>
            </thead>
            <tbody>
                <th style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo "$" . $ico ?>%</th> <!-- TARIFA IVA  -->
                <th style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo "$" . $base_ico ?></th> <!-- TARIFA IVA  -->
                <th style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo "$" . $valor_ico ?></th> <!-- TARIFA IVA  -->
                <th style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo "$" . $total_ico ?></th> <!-- TARIFA IVA  -->
            </tbody>
        </table>

        <p style="text-align:left; font: oblique bold 80% cursive;">DETALLE DE VENTA</p>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">VENTAS CONTADO </th>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">
                        </th>
                    <td style="text-align:left; font: oblique bold 80% cursive; border:none ">TOTAL VENTAS</th>
                </tr>
            </thead>
            <tbody>
                <td style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo "$" . $total_venta ?></th>
                <td style="text-align:left; font: oblique bold 80% cursive; border:none "></td>
                <td style="text-align:left; font: oblique bold 80% cursive; border:none "><?php echo "$" . $total_venta ?></th>

            </tbody>
        </table>
        <p></p>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <p>____________________________</p>
        <p style="text-align:left; font: oblique bold 80% cursive; border:none ">Firma</p>
    </div>
</div>