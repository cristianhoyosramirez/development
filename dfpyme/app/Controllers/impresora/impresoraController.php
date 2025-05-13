<?php

namespace App\Controllers\impresora;

use App\Controllers\BaseController;

class impresoraController extends BaseController
{
    public function index()
    {
        $impresoras = model('impresorasModel')->select('*')->orderBy('id')->findAll();
        return view('impresora/listado', [
            "impresoras" => $impresoras
        ]);
    }

    public function datos_iniciales()
    {
        return view('impresora/datos_iniciales');
    }

    public function salvar()
    {
        if (!$this->validate([
            'nombre_impresora' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],

        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = [
            'nombre' => $this->request->getVar('nombre_impresora'),

        ];
        $insert = model('impresorasModel')->insert($data);
        if ($insert) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('impresora/listado'))->with('mensaje', 'Creación correcta');
        } else {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'error');
            return redirect()->to(base_url('impresora/listado'))->with('mensaje', 'Hubo errores');
        }
    }

    public function editar()
    {
        $id_impresora = $_POST['id_impresora'];

        $impresora = model('impresorasModel')->select('*')->where('id', $id_impresora)->first();

        return view('impresora/editar', [
            'id_impresora' => $impresora['id'],
            'nombre_impresora' => $impresora['nombre'],

        ]);
    }
    public function actualizar()
    {
        $id_impresora = $_POST['id_impresora'];

        $data = [
            'nombre' => $this->request->getPost('nombre_impresora'),
        ];

        $model = model('impresorasModel');
        $actualizar = $model->set($data);
        $actualizar = $model->where('id', $id_impresora);
        $actualizar = $model->update();

        if ($actualizar) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('impresora/listado'))->with('mensaje', 'actualizacion correcta');
        }
    }

    public function administracion()
    {
        $impresoras = model('impresorasModel')->orderBy('id','desc')->find();
        return view('impresora/administrar_impresoras',[
            'impresoras'=>$impresoras
        ]);
    }
}
