<?php

namespace App\Controllers\consultas_y_reportes;

use App\Controllers\BaseController;

class FlujoEfectivoController extends BaseController
{
    public function reporte_flujo_efectivo()
    {
        return view('consultas_y_reportes/flujo_efectivo');
    }

    function datos_reporte_flujo_efectivo()
    {

        $empresa = model('empresaModel')->findAll();


        $returnData = array(
            "resultado" => 1,
            "retiros" => view('consultas_y_reportes/datos_flujo_efectivo', [
                'fecha_inicial' => $_POST['fecha_inicial'],
                'fecha_final' => $_POST['fecha_final']
            ])
        );
        echo  json_encode($returnData);
    }

    function excel_reporte_flujo_efectivo()
    {
        $datos_empresa=model('empresaModel')->findAll();
        
        return view('consultas_y_reportes/excel_flujo_efectivo',[
            'fecha_inicial'=>$this->request->getPost('fecha_inicial'),
            'fecha_final'=>$this->request->getPost('fecha_final'),
            'datos_empresa'=>$datos_empresa
        ]);
    }
}
