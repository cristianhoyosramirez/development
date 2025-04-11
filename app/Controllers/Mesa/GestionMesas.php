<?php

namespace App\Controllers\Mesa;
use App\Controllers\BaseController;

class GestionMesas extends BaseController
{
    public function index()
    {
        $mesas=model('mesasModel')->mesas();

        
        //return view('home/home');
        return $this->response->setJSON([
            'response' => 'success',

            'mesas' =>  view('mesa/mesas', [
                'mesas' => $mesas
            ]),

        ]);
    }
}
