<?php

namespace App\Controllers\configuracion;

use App\Controllers\BaseController;

require APPPATH . "Controllers/mike42/autoload.php";

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

require APPPATH . "Controllers/phpqrcode/qrlib.php";

use QRcode;
use SimpleSoftwareIO\QrCode\Generator;


class Configuracion extends BaseController
{

    public $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    public function mesero()
    {
        $configuracion_mesero = model('configuracionPedidoModel')->select('mesero_pedido')->first();

        return view('configuracion/meseros', [
            'requiere_mesero' => $configuracion_mesero['mesero_pedido']
        ]);
    }

    function actualizar_mesero()
    {
        $valor = $this->request->getPost('valor');

        $model = model('configuracionPedidoModel');
        $configuracion = $model->set('mesero_pedido', $valor);
        $configuracion = $model->update();

        if ($configuracion) {
            $returnData = array(
                "resultado" => 1,

            );
            echo  json_encode($returnData);
        }
    }

    function propina()
    {
        $porcentaje = model('configuracionPedidoModel')->select('valor_defecto_propina')->first();
        $propina = model('configuracionPedidoModel')->select('calculo_propina')->first();
        return view('configuracion/propina', [
            'porcetaje_propina' => $porcentaje['valor_defecto_propina'],
            'propina' => $propina['calculo_propina']
        ]);
    }

    function configuracion_propina()
    {

        if (!$this->validate([
            'porcentaje' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Dato necesario',
                    'numeric' => 'Debe ser un registro numerico '

                ]
            ],

        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $valor = $this->request->getPost('propina');

        $model = model('configuracionPedidoModel');
        $configuracion = $model->set('propina', $valor);
        $configuracion = $model->set('valor_defecto_propina', $this->request->getPost('porcentaje'));
        $configuracion = $model->set('calculo_propina', $this->request->getPost('calculo_automatico'));
        $configuracion = $model->update();

        $session = session();
        $session->setFlashdata('iconoMensaje', 'success');
        return redirect()->to(base_url('pedidos/mesas'))->with('mensaje', 'Configuración de propina correcta ');
    }

    function estacion_trabajo()
    {

        $cajas = model('cajaModel')->findAll();
        $impresoras = model('impresorasModel')->findAll();

        return view('configuracion/estacion_trabajo', [
            'cajas' => $cajas,
            'impresoras' => $impresoras
        ]);
    }

    function actualizar_caja()
    {
        $data = [
            'id_impresora' => $this->request->getPost('id_impresora')
        ];

        // $num_fact = model('pedidoModel');
        $caja =  model('cajaModel')->set($data)->where('idcaja', $this->request->getPost('id_caja'))->update();

        $session = session();
        $session->setFlashdata('iconoMensaje', 'success');
        return redirect()->to(base_url('pedidos/mesas'))->with('mensaje', 'Asignación de impresora correcto  ');
    }

    function sub_categoria()
    {
        $subcategoria = model('configuracionPedidoModel')->select('sub_categoria')->first();
        return view('configuracion/subcategoria', [
            'sub_categoria' => $subcategoria['sub_categoria']
        ]);
    }

    /*   function actualizar_sub_categoria()
    {


        $valor = $this->request->getPost('valor');

        $model = model('configuracionPedidoModel');
        $configuracion = $model->set('sub_categoria', $valor);
        $configuracion = $model->update();

        if ($configuracion) {
            $returnData = array(
                "resultado" => 1,

            );
            echo  json_encode($returnData);
        }
    } */

    function crear_sub_categoria()
    {
        $sub_categorias = model('subCategoriaModel')->findAll();


        $categorias = model('categoriasModel')->where('permitir_categoria', 'true')->orderBy('nombrecategoria', 'asc')->findAll();


        $id_categorias = model('categoriasModel')->sub_categorias();


        //dd($id_categorias);


        return view('configuracion/sub_categoria', [
            'id_categorias' => $id_categorias,
            'categorias' => $categorias
        ]);
    }

    function editar_sub_categoria()
    {
        $id_categoria = $this->request->getPost('valor');

        $subcategoria = model('subCategoriaModel')->where('id', $id_categoria)->first();

        $categorias = model('categoriasModel')->where('permitir_categoria', 'true')->orderBy('nombrecategoria', 'asc')->findAll();


        $returnData = array(
            "resultado" => 1,
            "subcategoria" => view('configuracion/editar_sub_categoria', [
                'subcategoria' => $subcategoria,
                'categorias' => $categorias,
                'id_categoria' => $id_categoria
            ])

        );
        echo  json_encode($returnData);
    }


    function actualizar_sub_categoria()
    {
        $id_categoria = $this->request->getPost('valor');
        $categoria = $this->request->getPost('categoria');
        //$id_categoria = 'true';
        $nombre = $this->request->getPost('nombre');

        $model = model('subCategoriaModel');
        $actualizar = $model->set('nombre', $nombre);
        $actualizar = $model->set('id_categoria', $categoria);
        $actualizar = $model->where('id', $id_categoria);
        $actualizar = $model->update();


        $subcategoria = model('subCategoriaModel')->find();

        $returnData = array(
            "resultado" => 1,
            "subcategorias" => view('configuracion/lista_sub_categorias', [
                'sub_categorias' => $subcategoria
            ])

        );
        echo  json_encode($returnData);
    }
    function eliminar_sub_categoria()
    {
        $id_categoria = $this->request->getPost('valor');

        $model = model('subCategoriaModel');
        $actualizar = $model->where('id', $id_categoria);
        $actualizar = $model->delete();

        $subcategoria = model('subCategoriaModel')->find();

        $returnData = array(
            "resultado" => 1,
            "subcategorias" => view('configuracion/lista_sub_categorias', [
                'sub_categorias' => $subcategoria
            ])

        );
        echo  json_encode($returnData);
    }

    function actualizar_estado_sub_categoria()
    {

        $valor = $this->request->getPost('valor');

        $model = model('configuracionPedidoModel');
        $actualizar = $model->set('sub_categoria', $valor);
        $actualizar = $model->update();

        $subcategoria = model('subCategoriaModel')->find();

        $returnData = array(
            "resultado" => 1,
        );
        echo  json_encode($returnData);
    }

    function tipos_de_factura()
    {

        // $facturas=model('estadoModel')->find()->orderBy('orden','asc');
        $facturas = model('estadoModel')->orderBy('idestado', 'asc')->find();
        return view('tipos_de_factura/facturas', [
            'tipo_factura' => $facturas
        ]);
    }

    function actualizar_estado()
    {
        $id_estado = $this->request->getPost('id_estado');
        $descripcion = $this->request->getPost('descripcion');
        $estado = $this->request->getPost('estado');
        $orden = $this->request->getPost('orden');
        $consulta = $this->request->getPost('consulta');

        $data = [
            'descripcionestado' => $descripcion,
            'estado' => $estado,
            'orden' => $orden,
            'consulta' => $consulta
        ];

        //var_dump($data);  exit();
        /*   $data = [
            'descripcionestado' => $descripcion,
            'estado' => $estado,
            'orden' => $orden
        ]; */


        $model = model('estadoModel')->set($data)->where('idestado', $id_estado)->update();


        if ($model) {
            $returnData = array(
                "resultado" => 1,
            );
            echo  json_encode($returnData);
        }
    }


    public function consulta_factura_electronica()
    {
        $valor_buscado = $_GET['search']['value'];
        $id_apertura = model('aperturaModel')->selectMax('id')->findAll();
        $apertura = $id_apertura[0]['id'];

        $sql_count = '';
        $sql_data = '';

        $table_map = [
            0 => 'id',
            1 => 'fecha',
            2 => 'nit_cliente',
            3 => 'nombrescliente',
            4 => 'documento',
            5 => 'total_documento',

        ];

        $sql_count = "SELECT 
                            COUNT(pagos.id) AS total
                    FROM
                    pagos where id_apertura=$apertura";

        $sql_data = "SELECT
                    id,
                    fecha,
                    documento,
                    total_documento,
                    id_factura,
                    id_estado,
                    nit_cliente,
                    id_estado,
                    id_factura
                FROM
                    pagos where id_apertura=$apertura";

        $condition = "";

        if (!empty($valor_buscado)) {
            $condition .= " AND cliente.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR descripcionestado ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR factura_venta.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR numerofactura_venta ILIKE '%" . $valor_buscado . "%'";
        }

        $sql_count .= $condition;
        $sql_data .= $condition;

        $total_count = $this->db->query($sql_count)->getRow();

        $sql_data .= " ORDER BY " . $table_map[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " " . "LIMIT " . $_GET['length'] . " OFFSET " . $_GET['start'];



        $datos = $this->db->query($sql_data)->getResultArray();
        $data = [];

        foreach ($datos as $detalle) {
            $sub_array = array();

            $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $detalle['nit_cliente'])->first();
            $sub_array[] = $detalle['fecha'];
            $sub_array[] = $detalle['nit_cliente'];
            $sub_array[] =  $nombre_cliente['nombrescliente'];
            $sub_array[] = $detalle['documento'];
            $sub_array[] = "$ " . number_format($detalle['total_documento'], 0, ",", ".");
            $tipo_documento = model('estadoModel')->select('descripcionestado')->where('idestado', $detalle['id_estado'])->first();

            $sub_array[] = $tipo_documento['descripcionestado'];
            if ($detalle['id_estado'] == 8) {
                $pdf = model('facturaElectronicaModel')->select('transaccion_id')->where('id', $detalle['id_factura'])->first();

                if (empty($pdf['transaccion_id'])) {
                    $sub_array[] = '<a  class="btn btn-outline-success btn-icon " title="Trasmitir " onclick="sendInvoice(' . $detalle['id_factura'] . ')" >
            <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="17" y1="3" x2="17" y2="21" />
                                    <path d="M10 18l-3 3l-3 -3" />
                                    <line x1="7" y1="21" x2="7" y2="3" />
                                    <path d="M20 6l-3 -3l-3 3" />
                                </svg></a> 

            <a  class="btn btn-outline-success btn-icon " title="Imprimir copia " onclick="imprimir_electronica(' . $detalle['id_factura'] . ')" >
            <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg></a>
            

        <a  class="btn bg-muted-lt btn-icon " title="Ver detalle" onclick="detalle_f_e(' . $detalle['id_factura'] . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg></a>';
                }
                if (!empty($pdf['transaccion_id'])) {

                    $pdf_url = model('facturaElectronicaModel')->select('pdf_url')->where('id', $detalle['id_factura'])->first();

                    $sub_array[] = '<a  class="btn btn-outline-success btn-icon " title="Trasmitir " onclick="sendInvoice(' . $detalle['id_factura'] . ')" >
            <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="17" y1="3" x2="17" y2="21" />
                                    <path d="M10 18l-3 3l-3 -3" />
                                    <line x1="7" y1="21" x2="7" y2="3" />
                                    <path d="M20 6l-3 -3l-3 3" />
                                </svg></a> 
            

            <a  class="btn btn-outline-success btn-icon " title="Imprimir copia " onclick="imprimir_electronica(' . $detalle['id_factura'] . ')" >
            <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg></a>
            

        <a  class="btn bg-muted-lt btn-icon " title="Ver detalle" onclick="detalle_f_e(' . $detalle['id_factura'] . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg></a>
        
        
<a href="' . $pdf_url['pdf_url'] . '" target="_blank" class="cursor-pointer">
    <img title="Descargar pdf" src="' . base_url() . '/Assets/img/pdf.png" width="40" height="40" />
</a>';
                }
            }
            if ($detalle['id_estado'] != 8) {

                $sub_array[] = '<a  class="btn btn-outline-success btn-icon " title="Imprimir copia " onclick="imprimir_duplicado_factura(' . $detalle['id_factura'] . ')" >
            <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg></a>  
        <a  class="btn bg-muted-lt btn-icon " title="Ver detalle" onclick="detalle_de_factura(' . $detalle['id'] . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg></a>
        
         ';
            }
            $data[] = $sub_array;
        }

        $json_data = [
            'draw' => intval($this->request->getGEt(index: 'draw')),
            'recordsTotal' => $total_count->total,
            'recordsFiltered' => $total_count->total,
            'data' => $data,
        ];

        echo  json_encode($json_data);
    }


    public function consulta_document()
    {
        $valor_buscado = $_GET['search']['value'];
        $id_apertura = model('aperturaModel')->selectMax('id')->findAll();
        $apertura = $id_apertura[0]['id'];

        $sql_count = '';
        $sql_data = '';

        $table_map = [
            0 => 'id',
            1 => 'fecha',
            2 => 'nit_cliente',
            3 => 'nombrescliente',
            4 => 'documento',
            5 => 'total_documento',

        ];

        $sql_count = "SELECT 
                            COUNT(pagos.id) AS total
                    FROM
                    pagos where id_apertura=$apertura";

        $sql_data = "SELECT
                    id,
                    fecha,
                    documento,
                    total_documento,
                    id_factura,
                    id_estado,
                    nit_cliente,
                    id_estado,
                    id_factura
                FROM
                    pagos where id_apertura=$apertura";

        $condition = "";

        if (!empty($valor_buscado)) {
            $condition .= " AND cliente.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR descripcionestado ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR factura_venta.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR numerofactura_venta ILIKE '%" . $valor_buscado . "%'";
        }

        $sql_count .= $condition;
        $sql_data .= $condition;

        $total_count = $this->db->query($sql_count)->getRow();

        $sql_data .= " ORDER BY " . $table_map[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " " . "LIMIT " . $_GET['length'] . " OFFSET " . $_GET['start'];

        $datos = $this->db->query($sql_data)->getResultArray();
        $data = [];

        foreach ($datos as $detalle) {
            $sub_array = array();

            $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $detalle['nit_cliente'])->first();
            $sub_array[] = $detalle['fecha'];
            $sub_array[] = $detalle['nit_cliente'];
            $sub_array[] =  $nombre_cliente['nombrescliente'];
            $sub_array[] = $detalle['documento'];
            $sub_array[] = "$ " . number_format($detalle['total_documento'], 0, ",", ".");
            $tipo_documento = model('estadoModel')->select('descripcionestado')->where('idestado', $detalle['id_estado'])->first();

            $sub_array[] = $tipo_documento['descripcionestado'];
            if ($detalle['id_estado'] == 8) {
                $pdf = model('facturaElectronicaModel')->select('transaccion_id')->where('id', $detalle['id_factura'])->first();

                if (empty($pdf['transaccion_id'])) {
                    $sub_array[] = '<a  class="btn btn-outline-success btn-icon " title="Trasmitir " onclick="sendInvoice(' . $detalle['id_factura'] . ')" >
            <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="17" y1="3" x2="17" y2="21" />
                                    <path d="M10 18l-3 3l-3 -3" />
                                    <line x1="7" y1="21" x2="7" y2="3" />
                                    <path d="M20 6l-3 -3l-3 3" />
                                </svg></a> 

            <a  class="btn btn-outline-success btn-icon " title="Imprimir copia " onclick="imprimir_electronica(' . $detalle['id_factura'] . ')" >
            <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg></a>
            

        <a  class="btn bg-muted-lt btn-icon " title="Ver detalle" onclick="detalle_f_e(' . $detalle['id_factura'] . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg></a>';
                }
                if (!empty($pdf['transaccion_id'])) {

                    $pdf_url = model('facturaElectronicaModel')->select('pdf_url')->where('id', $detalle['id_factura'])->first();

                    $sub_array[] = '<a  class="btn btn-outline-success btn-icon " title="Trasmitir " onclick="sendInvoice(' . $detalle['id_factura'] . ')" >
            <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="17" y1="3" x2="17" y2="21" />
                                    <path d="M10 18l-3 3l-3 -3" />
                                    <line x1="7" y1="21" x2="7" y2="3" />
                                    <path d="M20 6l-3 -3l-3 3" />
                                </svg></a> 
            

            <a  class="btn btn-outline-success btn-icon " title="Imprimir copia " onclick="imprimir_electronica(' . $detalle['id_factura'] . ')" >
            <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg></a>
            

        <a  class="btn bg-muted-lt btn-icon " title="Ver detalle" onclick="detalle_f_e(' . $detalle['id_factura'] . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg></a>
        
        
<a href="' . $pdf_url['pdf_url'] . '" target="_blank" class="cursor-pointer">
    <img title="Descargar pdf" src="' . base_url() . '/Assets/img/pdf.png" width="40" height="40" />
</a>';
                }
            }
            if ($detalle['id_estado'] != 8) {

                $sub_array[] = '<a  class="btn btn-outline-success btn-icon " title="Imprimir copia " onclick="imprimir_duplicado_factura(' . $detalle['id_factura'] . ')" >
            <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg></a>  
        <a  class="btn bg-muted-lt btn-icon " title="Ver detalle" onclick="detalle_de_factura(' . $detalle['id'] . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg></a>
        
         ';
            }
            $data[] = $sub_array;
        }

        $json_data = [
            'draw' => intval($this->request->getGEt(index: 'draw')),
            'recordsTotal' => $total_count->total,
            'recordsFiltered' => $total_count->total,
            'data' => $data,
        ];

        echo  json_encode($json_data);
    }

    function borrar_remisiones()
    {
        $remsiones = model('configuracionPedidoModel')->select('borrar_remisiones')->first();

        return view('configuracion/remisiones', [
            'remisiones' => $remsiones['borrar_remisiones']
        ]);
    }

    function actualizar_remisiones()
    {

        $valor = $this->request->getPost('valor');

        if ($valor == 1) {
            $permitir = true;
        }
        if ($valor == 0) {
            $permitir = false;
        }

        $actualizar = model('configuracionPedidoModel')->set('borrar_remisiones', $permitir)->update();

        $returnData = array(

            "resultado" => 1
        );
        echo  json_encode($returnData);
    }

    function borrado_de_remisiones()
    {
        $id_apertura = $this->request->getPost('id_apertura');

        $borrar_remisiones = model('pagosModel')->borrar_remisiones($id_apertura);


        if ($borrar_remisiones) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('consultas_y_reportes/consultas_caja'))->with('mensaje', 'Borrado éxitoso ');
        }
    }

    function abrir_cajon()
    {
        $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();
        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();
        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);

        //$printer->feed(1);
        //$printer->cut();
        $printer->pulse();
        $printer->close();

        if ($printer) {
            $returnData = array(
                "resultado" => 1
            );
            echo  json_encode($returnData);
        }
    }

    function admin_imp()
    {
        return view('configuracion/impresora');
    }

    function comanda()
    {

        $temp_comanda = model('configuracionPedidoModel')->select('partir_comanda')->first();

        if ($temp_comanda['partir_comanda'] == 'f') {
            $comanda = "false";
        } else if ($temp_comanda['partir_comanda'] == 't') {
            $comanda = "true";
        }

        return view('configuracion/comanda', [
            'comanda' => $comanda
        ]);
    }

    function actualizar_comanda()
    {
        $valor = $this->request->getPost('valor');

        $actualizar = model('configuracionPedidoModel')->set('partir_comanda', $valor)->update();


        if ($actualizar) {
            $returnData = array(
                "resultado" => 1, //Falta plata  
            );
            echo  json_encode($returnData);
        }
    }

    function productos_favoritos()
    {

        $favoritos = model('configuracionPedidoModel')->select('producto_favoroitos')->first();
        return view('configuracion/favoritos', [
            'favorito' => $favoritos['producto_favoroitos']
        ]);
    }

    function encabezado()
    {
        $encabezado = model('configuracionPedidoModel')->select('encabezado_factura')->first();
        $pie = model('configuracionPedidoModel')->select('pie_factura')->first();
        return view('configuracion/encabezado_pie', [
            'encabezado' => $encabezado['encabezado_factura'],
            'pie' => $pie['pie_factura'],
        ]);
    }

    function actualizar_encabezado()
    {

        $encabezado = $this->request->getPost('valor');
        $actualizar = model('configuracionPedidoModel')->set('encabezado_factura', $encabezado)->update();

        if ($actualizar) {
            $returnData = array(
                "resultado" => 1, //Falta plata  
            );
            echo  json_encode($returnData);
        }
    }

    function actualizar_pie()
    {
        $pie = $this->request->getPost('valor');
        $actualizar = model('configuracionPedidoModel')->set('pie_factura', $pie)->update();

        if ($actualizar) {
            $returnData = array(
                "resultado" => 1, //Falta plata  
            );
            echo  json_encode($returnData);
        }
    }

    function actualizar_favorito()
    {
        $valor = $this->request->getPost('valor');

        $actualizar = model('configuracionPedidoModel')->set('producto_favoroitos', $valor)->update();


        if ($actualizar) {
            $returnData = array(
                "resultado" => 1, //Falta plata  
            );
            echo  json_encode($returnData);
        }
    }

    function borrado_masivo()
    {

        return view('configuracion/borrado_masivo');
    }

    function productos_impuestos()
    {
        return view('configuracion/impuestos');
    }


    public function select_impuestos()
    {
        $opcion = $this->request->getGet('opcion'); // Obtener el valor de 'opcion' desde la solicitud

        // Verificar si la opción es válida
        if ($opcion == 1) {
            $inc = model('icoConsumoModel')->findAll();  // Obtener los datos del modelo
            $tipo_impuesto = "INC";

            // Generar la vista parcial con los datos del modelo
            $impuesto = view('impuestos/inc', ['inc' => $inc]);

            // Preparar los datos para retornar
            $returnData = array(
                "resultado" => 1,
                "impuesto" => $impuesto,
                "tipo_impuesto" => $tipo_impuesto
            );

            // Retornar la respuesta en formato JSON utilizando json_encode()
            echo json_encode($returnData);
        } elseif ($opcion == 2) {
            $iva = model('ivaModel')->where('conceptoiva', 'GENERAL')->findAll();  // Obtener los datos del modelo
            $tipo_impuesto = "IVA";

            // Generar la vista parcial con los datos del modelo
            $impuesto = view('impuestos/iva', ['iva' => $iva]);

            // Preparar los datos para retornar
            $returnData = array(
                "resultado" => 1,
                "impuesto" => $impuesto,
                "tipo_impuesto" => $tipo_impuesto
            );

            // Retornar la respuesta en formato JSON utilizando json_encode()
            echo json_encode($returnData);
        } else {
            // Si no se recibe una opción válida, retornar un mensaje de error
            $returnData = array(
                "resultado" => 0,
                "error" => "Opción no válida"
            );

            // Retornar la respuesta de error en formato JSON
            echo json_encode($returnData);
            return; // Asegurarse de finalizar la ejecución
        }
    }

    function actualizar_impuestos()
    {
        $opcion = $this->request->getGet('opcion');

        if ($opcion == 1) {

            $model = model('configuracionPedidoModel');
            $actualizar = $model->set('impuesto', false);
            $actualizar = $model->update();
        }
        if ($opcion == 0) {
            $model = model('configuracionPedidoModel');
            $actualizar = $model->set('impuesto', true);
            $actualizar = $model->update();
        }

        $returnData = array(
            "resultado" => 1,
            "error" => "Opción no válida"
        );

        // Retornar la respuesta de error en formato JSON
        echo json_encode($returnData);
    }

    function reset_producto()
    {


        $returnData = array(
            "resultado" => 1,
            "favorito" => view('configuracion/configuracion_favoritos'),
            "select_info_tri" => view('configuracion/select_info_tri'),
            "tipo_impuesto" => view('configuracion/tipo_impuesto'),
            "categorias" => view('configuracion/categorias'),

        );

        // Retornar la respuesta de error en formato JSON
        echo json_encode($returnData);
    }

    function validar_pin()
    {

        $pin = $this->request->getPost('pin');

        $pin_confi = model('configuracionPedidoModel')->select('eliminar_factura_electronica')->first();


        if ($pin == $pin_confi['eliminar_factura_electronica']) {
            $returnData = array(
                "resultado" => 1,
            );
            echo  json_encode($returnData);
        }
        if ($pin != $pin_confi['eliminar_factura_electronica']) {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        }
    }

    function eliminacion_masiva()
    {

        $borrar_f_e = model('facturaElectronicaModel')->select('id')->where('id_status', 1)->findAll();

        foreach ($borrar_f_e as $detalle) {
            model('facturaElectronicaModel')->where('id', $detalle['id'])->delete();
            model('pagosModel')->where('id_factura', $detalle['id'])->delete();
            model('kardexModel')->where('id_factura', $detalle['id'])->delete();
        }

        /*  $session = session();
        $session->setFlashdata('iconoMensaje', 'success');
        return redirect()->to(base_url('pedidos/mesas'))->with('mensaje', 'Gestion éxitosa '); */

        $returnData = array(
            "resultado" => 1,
        );
        echo  json_encode($returnData);
    }

    function propina_parcial()
    {

        $id_mesa = $this->request->getPost('id_mesa');
        //$id_mesa = 3; 

        $numero_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();


        $valor_pedido = model('partirFacturaModel')->propina_partida($numero_pedido['id']);

        //dd($valor_pedido);

        // Obtener la configuración de la propina
        $tipo_propina = model('configuracionPedidoModel')->select('propina')->first();
        $temp_porcentaje_propina = model('configuracionPedidoModel')->select('valor_defecto_propina')->first();

        // Calcular el porcentaje de propina
        $porcentaje_propina = $temp_porcentaje_propina['valor_defecto_propina'] / 100;

        // Calcular la propina según el tipo configurado
        if ($tipo_propina['propina'] == 1) {
            $temp_propina = $valor_pedido[0]['valor_total'] * $porcentaje_propina;
            // Redondear la propina al valor más cercano a mil
            $propina = round($temp_propina);
        } else {
            $propina = $valor_pedido[0]['valor_total'] * $porcentaje_propina;
        }

   



        $returnData = array(
            "resultado" => 1,
            "valor_pedido" => number_format($valor_pedido[0]['valor_total'], 0, ",", "."),
            "propina" => number_format($propina, 0, ",", "."),
            "valor_total" => number_format($propina + $valor_pedido[0]['valor_total'], 0, ",", "."),
            "total" => $propina + $valor_pedido[0]['valor_total']
        );
        echo  json_encode($returnData);
    }

    function sincronizar()
    {
        $url = model('configuracionPedidoModel')->select('url')->first();

    

        if (!empty($url['url'])) {
            $qrcode = new Generator;
            $qrCodes = [];
            $qrCodes['simple'] = $qrcode->size(120)->generate($url['url']);
     
            return view('qr/qr', $qrCodes);
        }
        if (empty($url['url'])) {
            $qrCodes = [];
            $qrCodes['simple'] = "No se ha configurado la url para conectar dispositivos móviles";return view('qr/qr', $qrCodes);

        }
    }

    function asignar(){

        $url=model('configuracionPedidoModel')->select('url')->first();
        return view('qr/asignar',[
            'url'=>$url['url']
        ]);
    }

    function actualizar_url(){
        $url=$this->request->getPost('url_conexion');

        $confi = model('configuracionPedidoModel')->set('url',$url)->update();

        $returnData = array(
            "resultado" => 1, //Falta plata  
        );
        echo  json_encode($returnData);
        
    }

    function AddDocument(){
        
    }
}
