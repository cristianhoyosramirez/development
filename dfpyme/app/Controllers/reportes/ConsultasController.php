<?php

namespace App\Controllers\reportes;

use App\Controllers\BaseController;

class ConsultasController extends BaseController
{
    public function index()
    {
        $meseros = model('usuariosModel')->select('idusuario_sistema,nombresusuario_sistema')->where('idtipo', 2)->findAll();

        $fechaInicial = date('Y-m-d');
        $fechaFinal = date('Y-m-d');

        $usuarios = model('pagosModel')->getUsuarioVenta($fechaInicial, $fechaFinal);


        return view('reportes/mesero', [
            'meseros' => $meseros,
            'fechaInicial' => $fechaInicial,
            'fechaFinal' => $fechaFinal,
            'usuarios' => $usuarios
        ]);
    }

    function reporteVentasUsuario()
    {
        $json = $this->request->getJSON();

        $fechaInicial = $json->fechaInicial;
        $fechaFinal = $json->fechaFinal;
        $idMesero = $json->idMesero;

        //$usuarios = model('pagosModel')->getUsuarioVenta($fechaInicial, $fechaFinal);

        return $this->response->setJSON([
            'response' => 'success',
            'ventas' => view('reportes/ventasMesero', [
                'usuario' => $idMesero,
                'fechaInicial' => $fechaInicial,
                'fechaFinal' => $fechaFinal
            ])
        ]);
    }
}
