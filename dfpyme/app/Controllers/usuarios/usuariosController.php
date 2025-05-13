<?php

namespace App\Controllers\usuarios;

use App\Controllers\BaseController;

class usuariosController extends BaseController
{
    public function index()
    {
        //  $usuarios = model('usuariosModel')->orderBy('idusuario_sistema','asc')->findAll();
        $usuarios = model('usuariosModel')->get_listado_usuarios();
        $usuarios_eliminados = model('usuariosModel')->select('*')->where('estadousuario_sistema', 'false')->find();
        $tipos_usuario=model('tipoUsuarioModel')->findAll();

        $roles = model('tipoUsuarioModel')->findAll();
        return view('usuarios/listado', [
            'usuarios' => $usuarios,
            'tipo_usarios'=>$tipos_usuario,
            'rol' => $roles,
            'usuarios_eliminados'=>$usuarios_eliminados
        ]);
    }

    public function editar()
    {
        $id_usuario = $_POST['id'];
        $modelo = model('usuariosModel');
        $datos = $modelo->select('idusuario_sistema');
        $datos = $modelo->select('cedulausuario_sistema');
        $datos = $modelo->select('nombresusuario_sistema');
        $datos = $modelo->select('usuariousuario_sistema');
        $datos = $modelo->select('pinusuario_sistema');
        $datos = $modelo->select('idtipo');
        $datos = $modelo->where('idusuario_sistema', $id_usuario)->first();

        $tipos_usuario=model('tipoUsuarioModel')->findAll();

        return view('usuarios/editar', [
            'identificacion' => $datos['cedulausuario_sistema'],
            'nombres' => $datos['nombresusuario_sistema'],
            'usuario' => $datos['usuariousuario_sistema'],
            'idusuario_sistema' => $datos['idusuario_sistema'],
            'pin_de_usuario' => $datos['pinusuario_sistema'],
            'id_tipo' => $datos['idtipo'],
            'tipos_usuario'=> $tipos_usuario
        ]);
    }

    public function actualizar()
    {
        if (!$this->validate([
            'id_usuario' => [
                'rules' => 'required|is_not_unique[idusuario_sistema.usuariousuario_sistema]',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El usuario es requerido',
                    'is_not_unique' => 'Usuario no existe'
                ]
            ],
           
        ])) {
            $id_usuario = $_POST['id_usuario'];
            $pin_usuario = model('usuariosModel')->select('pinusuario_sistema')->where('idusuario_sistema', $id_usuario)->first();
            $pin = $_POST['pin'];
            $modelo = model('usuariosModel');
            $datos = $modelo->select('idusuario_sistema');
            $datos = $modelo->select('cedulausuario_sistema');
            $datos = $modelo->select('nombresusuario_sistema');
            $datos = $modelo->select('usuariousuario_sistema');
            $datos = $modelo->select('idtipo');
            $datos = $modelo->where('idusuario_sistema', $id_usuario)->first();

            return view('usuarios/editar', [
                'identificacion' => $datos['cedulausuario_sistema'],
                'nombres' => $datos['nombresusuario_sistema'],
                'usuario' => $datos['usuariousuario_sistema'],
                'idusuario_sistema' => $datos['idusuario_sistema'],
                'pin' => $pin,
                'errors' => $this->validator->getErrors(),
                'pin_de_usuario' => $pin_usuario['pinusuario_sistema'],
                'id_tipo' => $datos['idtipo']
            ]);
        }
        $id_usuario = $_POST['id_usuario'];
        # $hash = password_hash($this->request->getVar('pin'), PASSWORD_DEFAULT);

        if ($this->request->getVar('pin') == "") {
            $pin_usuario = model('usuariosModel')->select('pinusuario_sistema')->where('idusuario_sistema', $id_usuario)->first();

            $data = [
                'cedulausuario_sistema' => $this->request->getVar('identificacion_usuario'),
                'nombresusuario_sistema' => $this->request->getVar('nombre_usuario'),
                'usuariousuario_sistema' => $this->request->getVar('usuario_usuario'),
                'pinusuario_sistema' => $pin_usuario['pinusuario_sistema'],
                'idtipo' => $this->request->getVar('tipo_usuario')
            ];
            $model = model('usuariosModel');

            $actualizar = $model->set($data);

            $actualizar = $model->where('idusuario_sistema', $id_usuario);
            $actualizar = $model->update();
            if ($actualizar) {
                $session = session();
                $session->setFlashdata('iconoMensaje', 'success');
                return redirect()->to(base_url('usuarios/list'))->with('mensaje', 'Actualización correcta');
            }
        } else {

            $data = [
                'cedulausuario_sistema' => $this->request->getVar('identificacion_usuario'),
                'nombresusuario_sistema' => $this->request->getVar('nombre_usuario'),
                'usuariousuario_sistema' => $this->request->getVar('usuario_usuario'),
                'pinusuario_sistema' => $this->request->getVar('pin'),
                'idtipo' => $this->request->getVar('tipo_usuario')
            ];

            $model = model('usuariosModel');

            $actualizar = $model->set($data);

            $actualizar = $model->where('idusuario_sistema', $id_usuario);
            $actualizar = $model->update();
            if ($actualizar) {
                $session = session();
                $session->setFlashdata('iconoMensaje', 'success');
                return redirect()->to(base_url('usuarios/list'))->with('mensaje', 'actualizacion correcta');
            } else {
                $session = session();
                $session->setFlashdata('iconoMensaje', 'error');
                return redirect()->to(base_url('usuarios/list'))->with('mensaje', 'HUBO ERRORES DURANTE LA ACTUALIZACIÓN');
            }
        }
    }
    public function eliminar()
    {
        $id_usuario = $_POST['id_usuario'];

        $data = [
            'estadousuario_sistema' => false,
        ];

        $model = model('usuariosModel');

        $actualizar = $model->set($data);

        $actualizar = $model->where('idusuario_sistema', $id_usuario);
        $actualizar = $model->update();
        if ($actualizar) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('usuarios/list'))->with('mensaje', 'Eliminación correcta');
        } else {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'error');
            return redirect()->to(base_url('usuarios/list'))->with('mensaje', 'HUBO ERRORES DURANTE LA ACTUALIZACIÓN');
        }
    }

    function crear()
    {
        if (!$this->validate([

            'nombre_usuario' => [
                'rules' => 'required|is_unique[usuario_sistema.nombresusuario_sistema]',
                'errors' => [
                    'required' => 'Dato necesario',
                    'is_unique' => 'Nombre de usuario ya existe'
                ]
            ],
            'usuario' => [
                'rules' => 'required|is_unique[usuario_sistema.usuariousuario_sistema]',
                'errors' => [
                    'required' => 'Dato necesario',
                    'is_unique' => 'Usuario ya existe'
                ]
            ],
            'pin' => [
                'rules' => 'required|is_unique[usuario_sistema.pinusuario_sistema]|max_length[4]|min_length[4]',
                'errors' => [
                    'required' => 'Dato necesario',
                    'is_unique' => 'Pin ya existe ',
                    'max_length' => 'Máximo 4 digitos',
                    'min_length' => 'Mínimo 4 digitos'
                ]
            ],
            'id_rol' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],

        ])) {
            $errors = $this->validator->getErrors();
            echo json_encode([
                'code' => 0,
                'error' => $errors
            ]);
        } else {
            $data = [
                'idtipo' => $this->request->getPost('id_rol'),
                'cedulausuario_sistema' => '1234',
                'nombresusuario_sistema' => $this->request->getPost('nombre_usuario'),
                'usuariousuario_sistema' => $this->request->getPost('usuario'),
                'contraseniausuario_sistema' => $this->request->getPost('pin'),
                'estadousuario_sistema' => true,
                'telefonousuario_sistema' => '',
                'direccion_sistema' => '',
                'pinusuario_sistema' => $this->request->getPost('pin'),
            ];

           
            $insert = model('UsuariosModel')->insert($data);
            if ($insert) {
                echo json_encode(['code' => 1, 'msg' => 'Usuario creado']);
            } else {
                echo json_encode(['code' => 0, 'msg' => 'No se pudo crear ']);
            }
        }
    }
}
