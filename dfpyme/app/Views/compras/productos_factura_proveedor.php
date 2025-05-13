<div class="row">
    <div class="col-2 ">
        <p class="text-dark">Fecha:</p>
    </div>
    <div class="col"><?php echo $fecha ?></div>
</div>

<div class="row">
    <div class="col-2 text-dark">Proveedor:</div>
    <div class="col"><?php echo $proveedor ?></div>
</div>

<div class="mb-3"></div>
<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <td scope="col">Código</td>
                    <td scope="col">Producto </td>
                    <td scope="col">Cantidad</td>
                    <td scope="col">Valor unitario </td>
                    <td scope="col">Sub total </td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $detalle): ?>
                    <tr>

                        <td><?php echo $detalle['codigointernoproducto'] ?></td>
                        <td><?php echo $detalle['nombreproducto'] ?></td>
                        <td><?php echo $detalle['cantidad'] ?></td>
                        <td><?php echo number_format($detalle['valor_unitario'], 0, ',', '.') ?></td>
                        <td><?php echo number_format($detalle['valor_unitario'] * $detalle['cantidad'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <p class="text-primary h3 text-end ">Total: <?php echo $total ?></p>
    </div>
    
</div>
