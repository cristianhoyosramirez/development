<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <td scope="col">Producto</th>
            <td scope="col">Cantidad</th>
            <td scope="col">Fecha</th>
            <td scope="col">Valor</th>
           
        </tr>
    </thead>
    <tbody>

        <?php foreach ($devoluciones as $valor) :  $nombre_producto=model('productoModel')->select('nombreproducto')->where('codigointernoproducto',$valor['codigo'])->first(); ?>
            <tr>
                <td><?php echo $nombre_producto['nombreproducto'] ?></th>
                <td><?php echo $valor['cantidad'] ?></th>
                <td><?php echo $valor['fecha_venta'] ?></th>
                <td><?php echo "$ ".number_format($valor['valor_total_producto'], 0, ",", ".") ?></th>
               
            </tr>
        <?php endforeach ?>

    </tbody>
</table>


<p class="text-end h3"> Total devoluciones: <?php echo  "$ ".number_format($total_devoluciones, 0, ",", ".") ?> </p>