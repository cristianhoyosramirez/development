<?php

namespace App\Controllers\administracion_impresora;

use App\Controllers\BaseController;

class aperturaCajonMonederoController extends BaseController
{
    public function index()
    {
        return view('home/home');
    }

    public function cajon_monedero()
    {
        $impresoras = model('impresorasModel')->select('*')->find();
        $fk_impresora = model('aperturaCajonMonederoModel')->select('fk_impresora')->first();
        if (!empty($fk_impresora)) {
            return view('apertura_cajon_monedero/apertura_cajon_monedero', [
                'impresoras' => $impresoras,
                'fk_impresora' => $fk_impresora['fk_impresora']
            ]);
        }else{
            return view('apertura_cajon_monedero/asignar_impresora', [
                'impresoras' => $impresoras,
                'resultado'=>0
            ]);
            
        }
    }

    public function apertutra_cajon_monedero()
    {

        $id_impresora = $_POST['id_impresora'];

        $id_tabla_apertura_cajon = model('aperturaCajonMonederoModel')->select('id')->first();

        $fk_impresora = model('aperturaCajonMonederoModel')->select('fk_impresora')->first();

        if (!empty($fk_impresora['fk_impresora'])) {

            $data = [
                'fk_impresora' =>  $id_impresora
            ];


            $impresor = model('aperturaCajonMonederoModel');
            $impresora = $impresor->set($data);
            $impresora = $impresor->where('id', $id_tabla_apertura_cajon['id']);
            $impresora = $impresor->update();

            if ($impresora) {
                $session = session();
                $session->setFlashdata('iconoMensaje', 'success');
                return redirect()->to(base_url('administracion_impresora/cajon_monedero'))->with('mensaje', 'Asignacion de impresora para apertura de cajon correcta ');
            } else {
            }
        } else if (empty($fk_impresora['fk_impresora'])) {

            $data = [
                'fk_impresora' =>  $id_impresora
            ];

            $insertar = model('aperturaCajonMonederoModel')->insert($data);
            if ($insertar) {
                $session = session();
                $session->setFlashdata('iconoMensaje', 'success');
                return redirect()->to(base_url('administracion_impresora/cajon_monedero'))->with('mensaje', 'Asignacion de impresora para apertura de cajon correcta ');
            }
        }
    }
}
