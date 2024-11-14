<?php

namespace App\Controllers\Salones;

use App\Controllers\BaseController;
use App\Libraries\Propina;

class SalonesController extends BaseController
{
    /**
     * Consulta los registros de la tablas salones , datos correspondientes a la informacion de los salones
     */
    public function index()
    {
        $salones = model('salonesModel')->orderBy('id', 'asc')->find();
        return view('salones/listado', [
            'salones' => $salones
        ]);
    }
    /**
     * Formulario para obtener informacion de un nuevo salon 
     */
    public function datos_iniciales()
    {
        return view('salones/datos_iniciales');
    }
    /**
     * Relacion de salones y mesas 
     */
    public function salones()
    {

        $salones = model('salonesModel')->orderBy('id', 'asc')->find();
        $categorias = model('categoriasModel')->where('permitir_categoria', true)->find();

        return view('salones/salones', [
            'salones' => $salones,
            'categorias' => $categorias
        ]);
    }

    /***
     * Consulta de los salones creados 
     * @param $id_salon{integer} 
     * devuelve la vista salones/mesas con las mesas correspondiente al $id_salon
     */
    public function salon_mesas()
    {

        $id_salon = $_POST['id_salon'];

        //$mesas_salon = model('mesasModel')->where('fk_salon', $id_salon)->orderBy('id', 'asc')->findAll();
        $mesas_salon = model('mesasModel')->mesas_salon($id_salon);

        if (!empty($mesas_salon)) {

            $mesas = view('salones/mesas', [
                'mesas' => $mesas_salon
            ]);
            $returnData = array(
                "mesas" => $mesas,
                "resultado" => 1,


            );
            echo  json_encode($returnData);
        } else {
            $returnData = array(
                "resultado" => 0
            );
            echo  json_encode($returnData);
        }
    }


    public function save()
    {
        if (!$this->validate([
            'nombre' => [
                'rules' => 'required|is_unique[salones.nombre]',
                'errors' => [
                    'required' => 'Dato necesario',
                    'is_unique' => 'Registro duplicado'
                ]
            ],

        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nombre' => $this->request->getVar('nombre'),
        ];
        $insert = model('salonesModel')->insert($data);
        if ($insert) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('salones/list'))->with('mensaje', 'Creación correcta');
        } else {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'error');
            return redirect()->to(base_url('salones/list'))->with('mensaje', 'Hubo errores');
        }
    }

    public function editar()
    {
        $id = $_POST['id'];
        $salon = model('salonesModel')->where('id', $id)->first();
        $nombre_salon = $salon['nombre'];
        $id_salon = $salon['id'];
        return view('salones/editar', [
            'id_salon' => $id_salon,
            'nombre_salon' => $nombre_salon,
        ]);
    }
    public function actualizar()
    {
        if (!$this->validate([
            'id' => [
                'rules' => 'required|is_not_unique[salones.id]',
                'errors' => [
                    'is_unique' => 'Registro no válido'
                ]
            ],
            'nombre' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],

        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $id = $_POST['id'];
        $nombre = model('salonesModel')->select('nombre')->where('id =', $id)->first();
        $existe_nombre = model('salonesModel')->select('nombre')->where('id !=', $id)->find();

        /*   foreach ($existe_nombre as $detalle) {
            if ($detalle['nombre'] == $nombre['nombre']) {
                $session = session();
                $session->setFlashdata('iconoMensaje', 'error');
                return redirect()->to(base_url('salones/list'))->with('mensaje', 'Nombre de salon ya existe');
            }
        } */
        $data = [
            'nombre' => $this->request->getVar('nombre'),
        ];
        $id = trim($this->request->getVar('id'));
        $model = model('salonesModel');
        $actualizar = $model->set($data);
        $actualizar = $model->where('id', $id);
        $actualizar = $model->update();
        if ($actualizar) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('salones/list'))->with('mensaje', 'Actualización correcta');
        } else {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'error');
            return redirect()->to(base_url('salones/listado'))->with('mensaje', 'HUBO ERRORES DURANTE LA ACTUALIZACIÓN');
        }
    }


    function consultar_mesa()
    {
        $id_mesa = $this->request->getPost('id_mesa');
        //$id_mesa = 1;

        $tiene_pedido = model('pedidoModel')->select('fk_mesa')->where('fk_mesa', $id_mesa)->first();


        if (!empty($tiene_pedido)) {
            $id_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();
            /*  $nombre = model('mesasModel')->select('nombre')->where('id', $id_mesa)->first();
            $valor_pedido = model('pedidoModel')->select('valor_total')->where('fk_mesa', $id_mesa)->first();
            $propina = model('pedidoModel')->select('propina')->where('fk_mesa', $id_mesa)->first();
            $id_usuario = model('pedidoModel')->select('fk_usuario')->where('fk_mesa', $id_mesa)->first();
            $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario['fk_usuario'])->first();
            $returnData = array(
                "resultado" => 1,
                "mesa" => view('gestion_mesas/mesas', [
                    'id_mesa' => $id_mesa,
                    'nombre' => $nombre['nombre'],
                    'valor_pedido' => $valor_pedido['valor_total'],
                    'propina' => $propina['propina'],
                    'usuario'=>$nombre_usuario['nombresusuario_sistema']
                ]),
                'id'=>$id_mesa
            ); */

            $productos_pedido = model('productoPedidoModel')->producto_pedido($id_pedido['id']);
            $total_pedido = model('pedidoModel')->select('valor_total')->where('id', $id_pedido['id'])->first();
            $propina = model('pedidoModel')->select('propina')->where('id', $id_pedido['id'])->first();

            $configuracion_propina = model('configuracionPedidoModel')->select('calculo_propina')->first();

            if ($configuracion_propina['calculo_propina'] == 't') {

                $temp_propina = new Propina();
                $propina = $temp_propina->calcularPropina($id_mesa);
                $sub_total = $total_pedido['valor_total'];

                $model = model('pedidoModel');
                $configuracion = $model->set('propina', $propina['propina']);
                $actualizar = $model->where('id', $id_pedido['id']);
                $configuracion = $model->update();

                $propina_final = $propina['propina'];
            }

            if ($configuracion_propina['calculo_propina'] == 'f') {

                $propina_final = 0;
            }

            $returnData = array(
                "resultado" => 1,
                "productos_pedido" => view('pedidos/productos_pedido', [
                    "productos" => $productos_pedido,
                ]),
                'id' => $id_mesa,
                "sub_total" => number_format($total_pedido['valor_total'] + $propina_final, 0, ',', '.'),
                "propina" => number_format($propina_final, 0, ',', '.'),
                "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),

            );
            echo  json_encode($returnData);
        }

        if (empty($tiene_pedido)) {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        }
    }
}
