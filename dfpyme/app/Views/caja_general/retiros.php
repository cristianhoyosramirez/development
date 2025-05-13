<table class="table table-hover table-striped">
    <thead class="table-dark">
        <tr>
            <td scope="col">Concepto</td>
            <td scope="col">Valor</td>
            
        </tr>
    </thead>
    <tbody>
        <?php foreach ($retiros as $detalle) { ?>
            <tr>
                <?php  
                $valor=model('retiroFormaPagoModel')->select('valor')->where('idretiro',$detalle['id'])->first();        
                $concepto=model('retiroFormaPagoModel')->select('concepto')->where('idretiro',$detalle['id'])->first();        
                ?>
                <td><?php  echo  $concepto['concepto'] ?></td>
                <td><?php  echo  "$".number_format($valor['valor'], 0, ",", ".") ?></td>
                
            </tr>
        <?php } ?>
    </tbody>
</table>