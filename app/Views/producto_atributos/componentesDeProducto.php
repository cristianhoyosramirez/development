
<?php

foreach ($atributos as $keyAtributo):
    //$componente = model('componentesAtributosProductoModel')->select('nombre,id')->where('id', $keyAtributo['id_componente'])->first();
    $componente = model('configuracionAtributosProductoModel')->geterIdComponentes($id_tabla_producto, $keyAtributo['id_componente']);
    //echo $id_tabla_producto."</br>";
    //echo $keyAtributo['id_componente']."</br>";
    
?>

    <button type="button" class="btn btn-success rounded-pill position-relative" id="btnComponente<?php echo $componente[0]['id'] ?>">
        <?php echo ($componente[0]['nombre']); 
        ?>
        <span class="badge rounded-pill bg-success">

            <span class="badge rounded-pill bg-success" onclick="eliminacionComponente(<?php echo $componente[0]['id'] 
                                                                                        ?>)">

                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </span>

        </span>
    </button>



<?php endforeach ?>