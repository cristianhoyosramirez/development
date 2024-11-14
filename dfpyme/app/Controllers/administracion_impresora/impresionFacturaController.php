<?php

namespace App\Controllers\administracion_impresora;

use App\Controllers\BaseController;

class impresionFacturaController extends BaseController
{
    public function index()
    {
        return view('home/home');
    }

    public function impresion_factura()
    {
        $impresoras = model('impresorasModel')->select('*')->find();
        $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();

        if (!empty($id_impresora)) {
            return view('impresion_factura/impresion_factura', [
                'impresoras' => $impresoras,
                'id_impresora' => $id_impresora['id_impresora']
            ]);
        } else {
            return view('impresion_factura/asignar_impresora', [
                'impresoras' => $impresoras,
                'resultado' => 0
            ]);
        }
    }

    public function asignar_impresora_facturacion()
    {
        $id_impresora = $_POST['id_impresora'];

        $fk_impresora = model('impresionFacturaModel')->select('id_impresora')->first();

        if (!empty($fk_impresora['id_impresora'])) {
            $data = [
                'id_impresora' =>  $id_impresora
            ];


            $impresor = model('impresionFacturaModel');
            $impresora = $impresor->set($data);
            $impresora = $impresor->where('id', 1);
            $impresora = $impresor->update();

            if ($impresora) {
                $session = session();
                $session->setFlashdata('iconoMensaje', 'success');
                return redirect()->to(base_url('administracion_impresora/impresion_factura'))->with('mensaje', 'Asignacion de impresora para apertura de cajon correcta ');
            } else {
            }
        }
        if (empty($fk_impresora['id_impresora'])) {
            $data = [
                'id_impresora' => $id_impresora
            ];
            $insert = model('impresionFacturaModel')->insert($data);
            if ($insert) {
                $session = session();
                $session->setFlashdata('iconoMensaje', 'success');
                return redirect()->to(base_url('administracion_impresora/impresion_factura'))->with('mensaje', 'Asignacion de impresora para apertura de cajon correcta ');
            }
        }
    }

    function configuracion_pedido()
    {
        $configuracion_pedido = model('configuracionPedidoModel')->select('agregar_item')->first();
        return view('caja/configuracion_pedido', [
            'configuracion' => $configuracion_pedido['agregar_item']
        ]);
    }

    function actualizar_configuracion_pedido()
    {
        $estado = $this->request->getPost('actualizar_pedido');

        $pedidos = model('pedidoModel')->findAll();

        if (empty($pedidos)) {

            $data = [
                'agregar_item' => $estado
            ];

            $num_fact = model('configuracionPedidoModel');
            $numero_factura = $num_fact->set($data);
            $numero_factura = $num_fact->where('id', 1);
            $numero_factura = $num_fact->update();

            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('pedidos/mesas'))->with('mensaje', 'Actualizacion correcta ');
        } else if (!empty($pedidos)) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'info');
            return redirect()->to(base_url('pedidos/mesas'))->with('mensaje', 'Antes de realizar esta accion primero se deben de cerrar los pedidos actuales ');
        }
    }

    function proveedor()
    {


        $proveedores = model('ProveedorModel')->select('id, nombrecomercialproveedor, nitproveedor, direccionproveedor, telefonoproveedor')->orderBy('id', 'desc')->findAll();

        return view('proveedor/proveedor', [
            'proveedores' => $proveedores
        ]);
    }

    function crear_proveedor()
    {


        $json = file_get_contents('php://input');

        // Decodifica el JSON a un array asociativo
        $data = json_decode($json, true);

        $nit = $data['nit'];
        $nombre = $data['nombre'];
        $direccion = $data['direccion'];
        $telefono = $data['telefono'];


        $consecutivo = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 1)->first();

        $data = [
            'codigointernoproveedor' => $consecutivo['numeroconsecutivo'],
            //'codigointernoproveedor' => '2',
            'nitproveedor' => $nit,
            'idregimen' => 1,
            'razonsocialproveedor' => $nombre,
            'nombrecomercialproveedor' => $nombre,
            'descripcionproveedor' => $nombre,
            'direccionproveedor' => $direccion,
            'idciudad' => 319,
            'telefonoproveedor' => $telefono,
            'celularproveedor' => $telefono,
            'faxproveedor' => '00',
            'emailproveedor' => "",
            'webproveedor' => "",
            'estadoproveedor' => true,

        ];
        $insert = model('ProveedorModel')->insert($data);
        $proveedores = model('ProveedorModel')->select('id, nombrecomercialproveedor, nitproveedor, direccionproveedor, telefonoproveedor')->orderBy('id', 'desc')->findAll();

        if ($insert) {

            $nuevo_consecutivo = model('consecutivosModel')
                ->set('numeroconsecutivo', $consecutivo['numeroconsecutivo'] + 1)
                ->where('idconsecutivos', 1)
                ->update();


            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Datos guardados exitosamente',
                'proveedores' => $proveedores
            ]);
        }
    }

    function editar_proveedor()
    {
        $id = $this->request->getPost('id');

        $datos = $proveedores = model('ProveedorModel')->select('id, nombrecomercialproveedor, nitproveedor, direccionproveedor, telefonoproveedor')->where('id', $id)->first();

        $returnData = array(
            "resultado" => 1,
            "id" =>  $id,
            "nombre" => $datos['nombrecomercialproveedor'],
            "nit" => $datos['nitproveedor'],
            "direccion" => $datos['direccionproveedor'],
            "telefono" => $datos['telefonoproveedor']
        );

        echo  json_encode($returnData);
    }

    function actualizar_proveedor()
    {


        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre');
        $nit = $this->request->getPost('nit');
        $direccion = $this->request->getPost('direccion');
        $telefono = $this->request->getPost('telefono');

        $data = [

            'nitproveedor' => $nit,
            'razonsocialproveedor' => $nombre,
            'nombrecomercialproveedor' => $nombre,
            'direccionproveedor' => $direccion,
            'telefonoproveedor' => $telefono,
            'celularproveedor' => $telefono,

        ];

        $update = model('ProveedorModel')->set($data)->where('id', $id)->update();

        if ($update) {
            $datos = $proveedores = model('ProveedorModel')->select('id, nombrecomercialproveedor, nitproveedor, direccionproveedor, telefonoproveedor')->where('id', $id)->first();
            $returnData = array(
                "resultado" => 1,
                "id" =>  $id,
                "nombre" => $datos['nombrecomercialproveedor'],
                "nit" => $datos['nitproveedor'],
                "direccion" => $datos['direccionproveedor'],
                "telefono" => $datos['telefonoproveedor']
            );

            echo  json_encode($returnData);
        }
    }
}
