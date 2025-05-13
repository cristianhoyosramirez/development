<?php

namespace App\Controllers\Mesa;

use App\Controllers\BaseController;

class mesaController extends BaseController
{
    public function index()
    {
        $listado = model('mesasModel')->salonMesas();

        return view('mesa/listado', [
            'mesas' => $listado
        ]);
    }
    public function datos_iniciales()
    {
        $salones = model('salonesModel')->find();
        return view('mesa/datosIniciales', [
            'salones' => $salones
        ]);
    }

    public function save()
    {
        if (!$this->validate([
            'nombre' => [
                'rules' => 'required|is_unique[mesas.nombre]',
                'errors' => [
                    'required' => 'Dato necesario',
                    'is_unique' => 'Ya hay una mesa con ese nombre'

                ]
            ],
            'salon' => [
                'rules' => 'required|is_not_unique[salones.id]',
                'errors' => [
                    'required' => 'Dato necesario',
                    'is_unique' => 'Registro duplicado',
                    'is_not_unique' => 'Registro para el campo salon no válido'
                ]
            ],

        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'fk_salon' => $this->request->getVar('salon'),
            'nombre' => $this->request->getVar('nombre'),
        ];

        $insert = model('mesasModel')->insert($data);
        if ($insert) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');

            return redirect()->to(base_url('mesas/list'))->with('mensaje', 'Creación correcta');
        } else {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'error');
            return redirect()->to(base_url('mesas/list'))->with('mensaje', 'Hubo errores');
        }
    }

    public function MesaPedido()
    {
        $id_mesa = $_POST['id_mesa'];
        $tiene_pedido = model('pedidoModel')->select('fk_mesa')->where('fk_mesa', $id_mesa)->first();
        $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa)->first();


        if (empty($tiene_pedido['fk_mesa'])) {
            $returnData = array(
                "id_mesa" => $id_mesa,
                "resultado" => 0,
                "nombre_mesa" => $nombre_mesa['nombre']
            );
            echo  json_encode($returnData);
        } else if (!empty($tiene_pedido['fk_mesa'])) {
            $numero_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();
            $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['id']);
            $productos_del_pedido = view('productos_pedido/productos_pedido', [
                "productos" => $productos_pedido,
                "pedido" => $numero_pedido['id']
            ]);
            $total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();
            $cantidad_de_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido)->first();
            $nota_pedido = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido['id'])->first();

            $returnData = array(
                "id_mesa" => $id_mesa,
                "resultado" => 1,
                "numero_pedido" => $numero_pedido['id'],
                "productos_pedido" => $productos_del_pedido,
                "nombre_mesa" => $nombre_mesa['nombre'],
                "total" => "$" . number_format($total['valor_total'], 0, ',', '.'),
                "cantidad_de_productos" => $cantidad_de_productos['cantidad_de_productos'],
                "nota_pedido" => $nota_pedido['nota_pedido']
            );
            echo  json_encode($returnData);
        }
    }
    public function editar()
    {
        $id = $_POST['id'];
        $mesa = model('mesasModel')->where('id', $id)->first();
        $nombre_mesa = $mesa['nombre'];
        $id_mesa = $mesa['id'];
        $salones = model('salonesModel')->find();
        return view('mesa/editar', [
            'id_mesa' => $id_mesa,
            'nombre_mesa' => $nombre_mesa,
            'salones' => $salones,
            'fk_salon' => $mesa['fk_salon']
        ]);
    }

    public function cambiar_de_mesa()
    {
        $id_mesa = $_POST['id_mesa'];
        $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa)->first();
        $tiene_pedido = model('pedidoModel')->select('fk_mesa')->where('fk_mesa', $id_mesa)->first();
        $mesas = model('mesasModel')->select('*')->where('estado',0)->orderBy('id', 'asc')->find();
        $cambiar_mesa = view('cambiar_de_mesa/cambiar_de_mesa', [
            'mesas' => $mesas,
            'nombre_mesa' => $nombre_mesa['nombre'],
            'id_mesa_origen' => $id_mesa
        ]);
        if (!empty($tiene_pedido['fk_mesa'])) {

            $returnData = array(
                "resultado" => 1,
                "mesas" => $cambiar_mesa,
                "id_mesa_origen" => $id_mesa


            );
            echo  json_encode($returnData);
        } else {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        }
    }

    public function todas_las_mesas()
    {
        $mesas = model('mesasModel')->todas_las_mesas();
        $categorias = model('categoriasModel')->find();
        return view('salones/todas_las_mesas', [
            'mesas' => $mesas,
            'categorias' => $categorias
        ]);
    }

    public function actualizar()
    {
        $salon = $_POST['salon'];
        $nombre = $_POST['nombre'];
        $id_mesa = $_POST['id'];

        $nombre_mesa_salon = model('mesasModel')->nombre_mesa_salon($salon, $nombre);

        if (empty($nombre_mesa_salon)) {
            $data = [
                'fk_salon' => $salon,
                'nombre' => $nombre

            ];

            $model = model('mesasModel');
            $mesa = $model->set($data);
            $mesa = $model->where('id', $id_mesa);
            $mesa = $model->update();
            if ($mesa) {
                $session = session();
                $session->setFlashdata('iconoMensaje', 'success');
                return redirect()->to(base_url('mesas/list'))->with('mensaje', 'Cambio éxitoso');
            }
        }
        if (!empty($nombre_mesa_salon)) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'error');
            return redirect()->to(base_url('mesas/list'))->with('mensaje', 'Nombre de mesa ya ha sido usuado ');
        }
    }

    public function intercambio_mesa()
    {

        $id_mesa_origen = $_POST['id_mesa_origen'];
        //$id_mesa_origen = 300;
        // $id_mesa_destino = 301;
        $id_mesa_destino = $_POST['id_mesa_destino'];

        $tiene_pedido = model('pedidoModel')->select('fk_mesa')->where('fk_mesa', $id_mesa_destino)->first();

        if (!empty($tiene_pedido['fk_mesa'])) {

            $numero_pedido_mesa_destino = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa_destino)->first();
            $valor_mesa_destino = model('pedidoModel')->select('valor_total')->where('fk_mesa', $id_mesa_destino)->first();
            $numero_pedido_mesa_origen = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa_origen)->first();
            $valor_mesa_origen = model('pedidoModel')->select('valor_total')->where('fk_mesa', $id_mesa_origen)->first();

            $data = [
                'numero_de_pedido' =>  $numero_pedido_mesa_destino['id'],

            ];

            $model = model('productoPedidoModel');
            $mesas = $model->set($data);
            $mesas = $model->where('numero_de_pedido', $numero_pedido_mesa_origen['id']);
            $mesas = $model->update();

            $model = model('pedidoModel');
            $borrar = $model->where('fk_mesa', $id_mesa_origen);
            $borrar = $model->delete();

            $cantidad_de_producto = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido_mesa_destino['id'])->find();

            $data_pedido = [
                //'valor_total' => $valor_total,
                'cantidad_de_productos' => $cantidad_de_producto[0]['cantidad_producto']
            ];


            $total = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido_mesa_destino['id'])->findAll();
            
            $model = model('pedidoModel');
            $mesas = $model->set('valor_total', $total[0]['valor_total']);
            $mesas = $model->where('fk_mesa', $id_mesa_destino);
            $mesas = $model->update();

            $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa_destino)->first();
            $observaciones_generales = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido_mesa_destino['id'])->first();

            if (empty($observaciones_generales['nota_pedido'])) {
                $observacion_general = "";
            } else if (!empty($observaciones_generales['nota_pedido'])) {
                $observacion_general = $observaciones_generales['nota_pedido'];
            }

            $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido_mesa_destino['id']);
            $productos_del_pedido = view('pedidos/productos_pedido', [
                "productos" => $productos_pedido,
                "pedido" => $numero_pedido_mesa_destino['id']
            ]);

            $returnData = array(
                "resultado" => 1,
                "id_mesa" => $id_mesa_destino,
                "nombre_mesa" => $nombre_mesa['nombre'],
                "pedido" => $numero_pedido_mesa_destino['id'],
                "valor_total" => number_format($total[0]['valor_total'], 0, ',', '.'),
                "cantidad_productos" => $cantidad_de_producto[0]['cantidad_producto'],
                "observaciones_generales" => $observacion_general,
                "productos_pedido" => $productos_del_pedido,
            );
            echo  json_encode($returnData);
        }
        if (empty($tiene_pedido['fk_mesa'])) {

            $numero_pedido_mesa_origen = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa_origen)->first();


            $data = [
                'fk_mesa' => $id_mesa_destino,

            ];

            $model = model('pedidoModel');
            $mesas = $model->set($data);
            $mesas = $model->where('id', $numero_pedido_mesa_origen['id']);
            $mesas = $model->update();

            $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa_destino)->first();
            $observaciones_generales = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido_mesa_origen['id'])->first();

            if (empty($observaciones_generales['nota_pedido'])) {
                $observacion_general = "";
            } else if (!empty($observaciones_generales['nota_pedido'])) {
                $observacion_general = $observaciones_generales['nota_pedido'];
            }

            $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido_mesa_origen['id']);
            $productos_del_pedido = view('pedidos/productos_pedido', [
                "productos" => $productos_pedido,
                "pedido" => $numero_pedido_mesa_origen['id']
            ]);



            $cantidad_de_producto = model('pedidoModel')->select('cantidad_de_productos')->where('fk_mesa', $id_mesa_destino)->first();

            $returnData = array(
                "resultado" => 0,
                "id_mesa" => $id_mesa_destino,
                "nombre_mesa" => $nombre_mesa['nombre'],
                "pedido" => $numero_pedido_mesa_origen['id'],
                //"valor_total" => number_format($valor_total['valor_total'], 0, ',', '.'),
                "cantidad_productos" => $cantidad_de_producto['cantidad_de_productos'],
                "observaciones_generales" => $observacion_general,
                "productos_pedido" => $productos_del_pedido,
            );
            echo  json_encode($returnData);
        }
    }

    public function eliminacion_de_pedido()
    {
        $numero_pedido = $_POST['numero_pedido'];

        $id_usuario = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();

        // $permiso_eliminar = model('tipoPermisoModel')->tipo_permiso($id_usuario['fk_usuario']);

        // if (!empty($permiso_eliminar['idusuario_sistema'])) {

        $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();


        $valor_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();

        $fecha_creacion = model('pedidoModel')->select('fecha_creacion')->where('id', $numero_pedido)->first();
        $pedido_borrado = [
            'numero_pedido' => $numero_pedido,
            'valor_pedido' => $valor_pedido['valor_total'],
            'fecha_eliminacion' =>  date("Y-m-d"),
            'hora_eliminacion' => date('H:i:s'),
            'fecha_creacion' => $fecha_creacion['fecha_creacion'],
            'usuario_eliminacion' => $id_usuario['fk_usuario'],
            //'usuario_elimininacion' => $id_usuario['idusuario_sistema']
        ];

        $insert = model('eliminacionPedidosModel')->insert($pedido_borrado);


        $model = model('productoPedidoModel');
        $borrarPedido = $model->where('numero_de_pedido', $numero_pedido);
        $borrarPedido = $model->delete();

        $model = model('pedidoModel');
        $borrar = $model->where('id', $numero_pedido);
        $borrar = $model->delete();

        if ($borrarPedido && $borrar) {
            $data = [
                'estado' => 0,
                'valor_pedido' => 0,
                'fk_usuario' => $id_usuario['fk_usuario']
            ];

            $model = model('mesasModel');
            $numero_factura = $model->set($data);
            $numero_factura = $model->where('id', $id_mesa['fk_mesa']);
            $numero_factura = $model->update();
            echo 1;
        } else {
            echo 0;
        }
        //} 
        /* else {
            echo 0;
        } */
    }
}
