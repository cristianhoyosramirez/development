<?php

namespace App\Controllers\login;

use App\Controllers\BaseController;

class loginController extends BaseController
{
    public function index()
    {
        return view('home/home');
    }

    public function login()
    {

        if (!$this->validate([
            'pin' => [
                'rules' => 'required|is_not_unique[usuario_sistema.pinusuario_sistema]|max_length[4]',
                'errors' => [
                    'required' => 'El pin es requerido',
                    'is_not_unique' => 'Pin inexistente',
                    'max_length' => 'Longitud máxima de 4 digitos'

                ]
            ],

        ])) {
            return redirect()->to(base_url('/'))->withInput()->with('errors', $this->validator->getErrors());
        }


        $pin = $this->request->getVar('pin');
        $usuario = model('usuariosModel')->usuario_valido($pin);
        #$usuario = model('usuariosModel')->select('*')->where('pinusuario_sistema', $pin)->first();


        if (!empty($usuario)) {
            $tipo_permiso = model('tipoPermisoModel')->select('*')->where('idusuario_sistema', $usuario[0]['idusuario_sistema'])->find();



            if ($usuario) {
                $datosSesion = [
                    'id_usuario' => $usuario[0]['idusuario_sistema'],
                    'usuario' => $usuario[0]['usuariousuario_sistema'],
                    'nombre_usuario' => $usuario[0]['nombresusuario_sistema'],
                    'logged_in' => TRUE,
                    'tipo' => $usuario[0]['idtipo'],
                    'tipo_permiso' => $tipo_permiso
                ];
                $sesion = session();
                $sesion->set($datosSesion);
                if ($usuario[0]['idtipo'] == 0 or $usuario[0]['idtipo'] == 1 or $usuario[0]['idtipo'] == 3) {
                    return redirect()->to(base_url('pedidos/mesas'));
                }

                if ($usuario[0]['idtipo'] == 2 ){
                    return redirect()->to(base_url('pedidos/gestion_pedidos'));
                }
            } else {
                $datosSesion = [
                    'id_usuario' => $usuario['idusuario_sistema'],
                    'usuario' => $usuario['usuariousuario_sistema'],
                    'nombre_usuario' => $usuario['nombresusuario_sistema'],
                    'logged_in' => FALSE,
                    'tipo' => $usuario['idtipo']
                ];
                $sesion = session();
                $sesion->set($datosSesion);
                return redirect()->to(base_url('/'))->withInput()->with('errors', $this->validator->getErrors());
            }
        } else if (empty($usuario)) {

            $session = session();
            $session->setFlashdata('iconoMensaje', 'Error');
            return redirect()->to(base_url())->with('mensaje', 'Usuario inactivo o no existe');
        }
    }
    public function closeSesion()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url() . '/');
    }
}
