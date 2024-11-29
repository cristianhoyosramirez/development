<?php

namespace App\Controllers\actualizaciones;

use App\Controllers\BaseController;

class ParametrizacionController extends BaseController
{
    public function parametrizacion()
    {

        $codigo_pantalla=model('configuracionPedidoModel')->select('codigo_pantalla')->first();
        $altura=model('configuracionPedidoModel')->select('altura')->first();
        return view('parametrizacion/parametrizacion',[
            'codigo_pantalla'=>$codigo_pantalla['codigo_pantalla'],
            'altura'=>$altura['altura']
        ]);
    }


    function actualizar_codigo()
    {
        $opcion = $this->request->getPost('opcion');

        $update = model('configuracionPedidoModel')->set('codigo_pantalla', $opcion)->update();

        if ($update) {
            $returnData = array(
                "resultado" => 1,

            );
            echo  json_encode($returnData);
        }
    }
    function actualizar_altura()
    {
        $altura = $this->request->getPost('altura');

        $update = model('configuracionPedidoModel')->set('altura', $altura)->update();

        if ($update) {
            $returnData = array(
                "resultado" => 1,

            );
            echo  json_encode($returnData);
        }
    }
}
