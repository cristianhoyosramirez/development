<?php

namespace App\Controllers\reportes;

use App\Controllers\BaseController;
use App\Libraries\data_table;
use App\Libraries\tipo_consulta;

use \DateTime;
use \DateTimeZone;
use App\Libraries\estado_factura;

class ReportesController extends BaseController
{
    public $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    public function data_table_ventas()
    {
        //$valor_buscado = $_GET['search']['value'];
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

            $costo = model('kardexModel')->selectSum('costo')->where('id_factura', $detalle['id_factura'])->findAll();
            $iva = model('kardexModel')->selectSum('iva')->where('id_factura', $detalle['id_factura'])->findAll();
            $inc = model('kardexModel')->selectSum('ico')->where('id_factura', $detalle['id_factura'])->findAll();

            if ($detalle['id_factura'] == 8) {
                $temp_documento = model('facturaElectronicaModel')->select('numero')->where('id', $detalle['id_factura'])->first();
                $documento = $temp_documento['numero'];
            }

            if ($detalle['id_factura'] != 8) {
                $documento = $detalle['documento'];
            }

            $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $detalle['nit_cliente'])->first();
            $sub_array[] = $detalle['fecha'];
            $sub_array[] = $detalle['nit_cliente'];
            $sub_array[] =  $nombre_cliente['nombrescliente'];
            // $sub_array[] = $detalle['documento'];
            $sub_array[] = $documento;
            $tipo_documento = model('estadoModel')->select('descripcionestado')->where('idestado', $detalle['id_estado'])->first();

            $sub_array[] = $tipo_documento['descripcionestado'];
            $sub_array[] = number_format($detalle['total_documento'] - ($iva[0]['iva'] + $inc[0]['ico']), 0, ",", ".");
            $sub_array[] = "$ " . number_format($iva[0]['iva'], 0, ",", ".");
            // $sub_array[] = number_format($detalle['total_documento'] - ($iva[0]['iva'] + $inc[0]['ico']), 0, ",", ".");
            //$sub_array[] = number_format($iva[0]['iva'], 0, ",", ".");
            $sub_array[] = number_format($inc[0]['ico'], 0, ",", ".");
            $sub_array[] = number_format($detalle['total_documento'], 0, ",", ".");


            $data[] = $sub_array;
        }
        $total_venta = model('pagosModel')->selectSum('valor')->where('id_apertura', $apertura)->findAll();


        $iva = model('kardexModel')->get_iva_reportes($apertura);
        $inc = model('kardexModel')->get_inc_reportes($apertura);

        $costo = model('pagosModel')->total_costo($apertura);
        $json_data = [
            'draw' => intval($this->request->getGEt(index: 'draw')),
            'recordsTotal' => $total_count->total,
            'recordsFiltered' => $total_count->total,
            'data' => $data,
            'total_venta' => "$ " . number_format($total_venta[0]['valor'], 0, ",", "."),
            'impuestos' => view('impuestos/impuestos', [
                'iva' => $iva,
                'inc' => $inc,
                'apertura' => $apertura,
                'venta_total' => $total_venta[0]['valor']
            ])

        ];

        echo  json_encode($json_data);
    }

    public function reporte_de_ventas_fecha()
    {
        $fecha_inicial = $this->request->getGet('fecha_inicial');
        $fecha_final = $this->request->getGet('fecha_final');

        if (empty($fecha_inicial) and empty($fecha_final)) { //DEsde el inicio 
            $temp_fecha_inicial = model('pagosModel')->selectMin('fecha')->first();
            $temp_fecha_final = model('pagosModel')->selectMax('fecha')->first();
            $fecha_inicial = $temp_fecha_inicial['fecha'];
            $fecha_final = $temp_fecha_final['fecha'];
        }

        if (!empty($fecha_inicial) and empty($fecha_final)) {
            $fecha_final = $fecha_inicial;
        }
        if (!empty($fecha_inicial) and !empty($fecha_final)) {
            $fecha_inicial = $fecha_inicial;
            $fecha_final = $fecha_final;
        }



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
                    pagos where  fecha between '$fecha_inicial' and ' $fecha_final'";

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
                    pagos  where fecha between '$fecha_inicial' and ' $fecha_final'";

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

            $costo = model('kardexModel')->selectSum('costo')->where('id_factura', $detalle['id_factura'])->findAll();
            $iva = model('kardexModel')->selectSum('iva')->where('id_factura', $detalle['id_factura'])->findAll();
            $inc = model('kardexModel')->selectSum('ico')->where('id_factura', $detalle['id_factura'])->findAll();

            if ($detalle['id_factura'] == 8) {
                $temp_documento = model('facturaElectronicaModel')->select('numero')->where('id', $detalle['id_factura'])->first();
                $documento = $temp_documento['numero'];
            }

            if ($detalle['id_factura'] != 8) {
                $documento = $detalle['documento'];
            }

            $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $detalle['nit_cliente'])->first();
            $sub_array[] = $detalle['fecha'];
            $sub_array[] = $detalle['nit_cliente'];
            $sub_array[] =  $nombre_cliente['nombrescliente'];
            // $sub_array[] = $detalle['documento'];
            $sub_array[] = $documento;
            $tipo_documento = model('estadoModel')->select('descripcionestado')->where('idestado', $detalle['id_estado'])->first();

            $sub_array[] = $tipo_documento['descripcionestado'];
            $sub_array[] = number_format($detalle['total_documento'] - ($iva[0]['iva'] + $inc[0]['ico']), 0, ",", ".");
            $sub_array[] = number_format($iva[0]['iva'], 0, ",", ".");
            // $sub_array[] = number_format($detalle['total_documento'] - ($iva[0]['iva'] + $inc[0]['ico']), 0, ",", ".");
            //$sub_array[] = number_format($iva[0]['iva'], 0, ",", ".");
            $sub_array[] = number_format($inc[0]['ico'], 0, ",", ".");
            $sub_array[] = number_format($detalle['total_documento'], 0, ",", ".");


            $data[] = $sub_array;
        }
        $total_venta = model('pagosModel')->total_venta_costo($fecha_inicial, $fecha_final);


        $iva = model('kardexModel')->get_iva_reportes_fecha($fecha_inicial, $fecha_final);
        $inc = model('kardexModel')->get_ico_reportes_fecha($fecha_inicial, $fecha_final);
        $venta_total = model('pagosModel')->total_venta_costo($fecha_inicial, $fecha_final);

        //costo = model('pagosModel')->total_costo($apertura);
        $json_data = [
            'draw' => intval($this->request->getGEt(index: 'draw')),
            'recordsTotal' => $total_count->total,
            'recordsFiltered' => $total_count->total,
            'data' => $data,
            'total_venta' => "$ " . number_format($total_venta[0]['total'], 0, ",", "."),
            'fecha_inicial' => $fecha_inicial,
            'fecha_final' => $fecha_final,
            'impuestos' => view('impuestos/impuestos_fecha', [
                'iva' => $iva,
                'inc' => $inc,
                'fecha_inicial' => $fecha_inicial,
                'fecha_final' => $fecha_final,
                'venta_total' => $venta_total[0]['total']
            ])
        ];

        echo  json_encode($json_data);
    }


    function sendDian()
    {
        $id_factura = $this->request->getPost('id_factura');
        //$id_factura = 951;

        $nit_cliente = model('facturaElectronicaModel')->select('nit_cliente')->where('id', $id_factura)->first();

        $email = model('clientesModel')->select('emailcliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();
        $transaccion_id = model('facturaElectronicaModel')->select('transaccion_id')->where('id', $id_factura)->first();

        $auto_token = model('credencialesWebServerModel')->select('auth_token')->first();

        $request_body = array(
            "send_dian" => true,
            "send_email" => true,
            "email" => $email['emailcliente']
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.dataico.com/direct/dataico_api/v2/invoices/' . $transaccion_id['transaccion_id'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($request_body),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'auth-token: ' . $auto_token['auth_token']
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }


    function retrasmistir()
    {

        $id_factura = $this->request->getPost('id_fact');
        //$id_factura = 745;

        //Token 
        $temp_token = model('credencialesWebServerModel')->select('auth_token')->first();
        $auth_token = $temp_token['auth_token'];

        // UUID
        $temp_uui = model('facturaElectronicaModel')->select('transaccion_id')->where('id', $id_factura)->first();
        $uuid = $temp_uui['transaccion_id'];


        //UUID

        //Email 
        $temp_nit_cliente = model('facturaElectronicaModel')->select('nit_cliente')->where('id', $id_factura)->first();
        $nit_cliente = $temp_nit_cliente['nit_cliente'];

        $temp_email = model('clientesModel')->select('emailcliente')->where('nitcliente', $nit_cliente)->first();
        $email = $temp_email['emailcliente'];



        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.dataico.com/direct/dataico_api/v2/invoices/" . $uuid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => '{

   "actions": {

       "send_dian": true,

       "send_email": true,

       "email": "$email"

}

}',
            CURLOPT_HTTPHEADER => array(
                'Content-type: application/json',
                "Auth-token: $auth_token",
                'Cookie: AWSALBAPP-0=_remove_; AWSALBAPP-1=_remove_; AWSALBAPP-2=_remove_; AWSALBAPP-3=_remove_; AWSALBTG=uMZLBK0WU1PwqbynnHdko4gx+9nQg8DtBGFWnLxkXe8Pp1rojcX4mD0eZkSXIhO4c8Xu/HkZIVSMldORtc+68wrPva0RmlTFv04i8Esll/36I4e2Hem/XxBVX9gP9wCk6c0saPYN7WisNLXasHRjbMAS4CvGWJpYuFlTELKDwUb/0Pjn280=; AWSALBTGCORS=uMZLBK0WU1PwqbynnHdko4gx+9nQg8DtBGFWnLxkXe8Pp1rojcX4mD0eZkSXIhO4c8Xu/HkZIVSMldORtc+68wrPva0RmlTFv04i8Esll/36I4e2Hem/XxBVX9gP9wCk6c0saPYN7WisNLXasHRjbMAS4CvGWJpYuFlTELKDwUb/0Pjn280='
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {

            $returnData = array(
                "resultado" => 2,  // Se actulizo el registro 

            );
            echo  json_encode($returnData);
        } else {
            // Decodifica el JSON a un array asociativo
            $responseData = json_decode($response, true);


            $id_status = "";

            $responseData = json_decode($response, true);
            $pattern = '/QRCode=([^?]+)\?documentkey=([^\s]+)/';
            $matches = array();

            if (preg_match($pattern, $responseData['qrcode'], $matches)) {

                $baseUrl = $matches[1];
                $documentKey = $matches[2];

                $completeUrl = $baseUrl  . $documentKey;
            } else {
                echo "QRCode no encontrado.";
            }


            //exit();

            //dd($responseData);

            if ($responseData['dian_status'] == 'DIAN_ACEPTADO') {
                $id_status = 2;

                $data = [
                    'id_status' => $id_status,
                    'transaccion_id' => $responseData['uuid'],
                    'qrcode' => $baseUrl . "?documentkey=" . $documentKey,
                    //'qrcode' => $responseData['qrcode'],
                    'cufe' => $responseData['cufe'],
                    'pdf_url' => $responseData['pdf_url']
                ];

                $model = model('facturaElectronicaModel');
                $factura = $model->set($data);
                $factura = $model->where('id', $id_factura);
                $factura = $model->update();
            }



            $returnData = array(
                "resultado" => 1,  // Se actulizo el registro 

            );
            echo  json_encode($returnData);
        }
    }




    function estado_dian()
    {
        $valor_buscado = $_GET['search']['value'];
        $estado_dian = $this->request->getGet('estado_dian');
        //$estado_dian = 1;

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
                        COUNT(documento_electronico.id) AS total
                        FROM
                        documento_electronico 
                        INNER JOIN cliente ON documento_electronico.nit_cliente = cliente.nitcliente
                        where id_status= $estado_dian";

        $sql_data = "SELECT documento_electronico.id,
        fecha,
        nit_cliente,
        cliente.nombrescliente,
        numero AS documento,
        neto AS total_documento
        FROM documento_electronico
        INNER JOIN cliente ON documento_electronico.nit_cliente = cliente.nitcliente
        WHERE id_status= $estado_dian";



        $condition = "";

        if (!empty($valor_buscado)) {
            $condition .= " AND cliente.nitcliente ILIKE '%" . $valor_buscado . "%'";

            $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";

            $condition .= " OR numero ILIKE '%" . $valor_buscado . "%'";
        }

        $sql_count .= $condition;
        $sql_data .= $condition;

        $total_count = $this->db->query($sql_count)->getRow();

        $sql_data .= " ORDER BY " . $table_map[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " " . "LIMIT " . $_GET['length'] . " OFFSET " . $_GET['start'];

        $datos = $this->db->query($sql_data)->getResultArray();

        $data = [];

        $accion = new data_table();



        foreach ($datos as $detalle) {
            $sub_array = array();



            $sub_array[] = $detalle['fecha'];
            $sub_array[] = $detalle['nit_cliente'];
            $sub_array[] = $detalle['nombrescliente'];
            $sub_array[] = $detalle['documento'];
            $sub_array[] = number_format($detalle['total_documento'], 0, ",", ".");

            $saldo = model('pagosModel')->getSaldo($detalle['id']);
            $sub_array[] = $saldo[0]['saldo'];

            $sub_array[] = "FACTURA ELECTRONICA";
            $acciones = $accion->row_data_table(8, $detalle['id']);

            $sub_array[] = $acciones;


            $data[] = $sub_array;
        }


        $total_venta = model('facturaElectronicaModel')->selectSum('neto')->where('id_status', $estado_dian)->findAll();


        $dian_aceptado = model('facturaElectronicaModel')->selectCount('id')->where('id_status', 2)->findAll();
        $dian_no_enviado = model('facturaElectronicaModel')->selectCount('id')->where('id_status', 1)->findAll();
        $dian_rechazado = model('facturaElectronicaModel')->selectCount('id')->where('id_status', 3)->findAll();
        $dian_error = model('facturaElectronicaModel')->selectCount('id')->where('id_status', 4)->findAll();

        $json_data = [
            'draw' => intval($this->request->getGEt(index: 'draw')),
            'recordsTotal' => $total_count->total,
            'recordsFiltered' => $total_count->total,
            'data' => $data,
            'total' => "$ " . number_format($total_venta[0]['neto'], 0, ",", "."),
            'dian_aceptado' => $dian_aceptado[0]['id'],
            'dian_no_enviado' => $dian_no_enviado[0]['id'],
            'dian_rechazado' => $dian_rechazado[0]['id'],
            'dian_error' => $dian_error[0]['id'],

        ];

        echo  json_encode($json_data);
    }

    function actualizar_pagos()
    {

        $efectivo = $this->request->getPost('efectivo_factura');
        $transferencia = $this->request->getPost('transferencia_factura');

        $efectivo = str_replace('.', '', $efectivo);
        $transferencia = str_replace('.', '', $transferencia);

        $id = $this->request->getPost('id');

        $pagos = [
            'efectivo' => $efectivo,
            'transferencia' => $transferencia,
            'total_pago' => $efectivo + $transferencia,
            'recibido_efectivo' => $efectivo,
            'recibido_transferencia' => $transferencia,
        ];
        $pagos = model('pagosModel')->set($pagos)->where('id', $id)->update();

        if ($pagos) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('consultas_y_reportes/consultas_caja'))->with('mensaje', 'Actualización correcta ');
        }
    }

    function datos_pagos()
    {
        $id = $this->request->getPost('id');
        $pagos = model('pagosModel')->pagos($id);

        $returnData = array(
            "resultado" => 1,
            "efectivo" => number_format($pagos[0]['efectivo'], 0, ",", "."),
            "transferencia" => number_format($pagos[0]['transferencia'], 0, ",", "."),
            "id" => $id,

        );
        echo  json_encode($returnData);
    }

    function ver_productos_eliminanados()
    {
        $productos_eliminados = model('productoModel')->get_productos_borrados();

        $returnData = array(
            "resultado" => 1, //Falta plata  
            "productos" => view('producto/eliminados', [
                'productos' => $productos_eliminados
            ]), //Falta plata  
        );
        echo  json_encode($returnData);
    }

    function activar_producto()
    {
        $codigo_interno = $this->request->getPost('codigo');

        $actualizar = model('productoModel')->set('estadoproducto', 'true')->where('codigointernoproducto', $codigo_interno)->update();

        if ($actualizar) {
            $returnData = array(
                "resultado" => 1, //Falta plata  
            );
            echo  json_encode($returnData);
        }
    }

    function total_ventas_electronicas()
    {

        $id_apertura = model('aperturaRegistroModel')->select('numero')->first();


        $total_ventas_electronicas = model('pagosModel')->get_total_ventas_electronicas($id_apertura['numero']);

        $returnData = array(
            "resultado" => 1, //Falta plata 
            'ventas_electronicas' => "$ " . number_format($total_ventas_electronicas[0]['total_electronicas'], 0, ",", ".")
        );
        echo  json_encode($returnData);
    }

    function comprobar_fechas()
    {
        $id_doc = $this->request->getPost('iddoc');
        //$id_doc = 5727;
        $temp_fecha = model('facturaElectronicaModel')->select('fecha')->where('id', $id_doc)->first();
        $documento = model('facturaElectronicaModel')->select('numero')->where('id', $id_doc)->first();


        $fecha = $temp_fecha['fecha'];
        // Obtener la fecha actual
        $fecha_actual = date('Y-m-d');
        // Comparar la fecha recuperada con la fecha actual
        if ($fecha === $fecha_actual) {
            // La fecha es igual a la fecha actual  y se trasmite sin problema 
            $transaccion_id = new estado_factura();
            $uudi = $transaccion_id->getUuid($id_doc);
            if (empty($uudi)) {
                $returnData = array(  //La factura no tiene uuid y le fecha es la del dia y no esta en el proveedor tecnológico lo cual indica que se puede retrasmitr desde el pc local 
                    "resultado" => 0,
                    "id_doc" => $id_doc,
                    "numero" => $documento['numero']

                );
                echo  json_encode($returnData);
            }

            if (!empty($uudi)) {

                $returnData = array(  //La factura tiene uuid y le fecha es la del dia y esta en el proveedor tecnológico lo cual indica que se puede retrasmitr desde dataico 
                    "resultado" => 1,
                    "id_doc" => $id_doc,
                    "numero" => $documento['numero']

                );
                echo  json_encode($returnData);
            }
        }
        if ($fecha < $fecha_actual) {

            $transaccion_id = new estado_factura();
            $uudi = $transaccion_id->getUuid($id_doc);

            $numero = $documento['numero'];


            if (!empty($uudi)) {  //Tiene id transaccion y se debe actualizar la fecha a la actual 
                $returnData = array(
                    "resultado" => 2,
                    'numero' => $numero,
                    'id_doc' => $id_doc
                );
                echo  json_encode($returnData);
            }
            if (empty($uudi)) {  //No tiene id transaccion y se debe actualizar la fecha a la actual para generar la trasmision a la DIAN 
                $returnData = array(
                    "resultado" => 3,
                    'numero' => $numero,
                    'id_doc' => $id_doc
                );
                echo  json_encode($returnData);
            }
        }
    }

    function actualizar_fechas()
    {
        $id_doc = $this->request->getPost('id_doc');
        $data = [
            'fecha' => date('Y-m-d'),
            'fecha_limite' => date('Y-m-d'),
            'fecha_pago' => date('Y-m-d'),

        ];

        $update = model('facturaElectronicaModel')
            ->set($data)
            ->where('id', $id_doc)
            ->update();

        if ($update) {
            $pagos = [
                'fecha' => date('Y-m-d')

            ];

            $actualizar = model('pagosModel')
                ->set($pagos)
                ->where('id_factura', $id_doc)
                ->update();

            $temp_uui = model('facturaElectronicaModel')->select('transaccion_id')->where('id', $id_doc)->first();
            $uuid = $temp_uui['transaccion_id'];

            if (!empty($uuid)) {
                $returnData = array(
                    "resultado" => 1,
                    "id_doc" => $id_doc

                );
                echo  json_encode($returnData);
            }
            if (empty($uuid)) {
                $returnData = array(
                    "resultado" => 2,
                    "id_doc" => $id_doc

                );
                echo  json_encode($returnData);
            }
        }
    }

    function  actualizacion_estado_mesas()
    {
        $mesas = model('pedidoModel')->update_mesa();


        $returnData = array(
            "resultado" => 1,
            "mesas" => $mesas
        );

        echo  json_encode($returnData);
    }

    function productos_pedido()
    {
        $id_mesa = $this->request->getPost('id_mesa');
        $pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();
        $total_pedido = model('pedidoModel')->select('valor_total')->where('fk_mesa', $id_mesa)->first();
        $propina = model('pedidoModel')->select('propina')->where('fk_mesa', $id_mesa)->first();

        $productos_pedido = model('productoPedidoModel')->producto_pedido($pedido['id']);

        $returnData = array(
            "resultado" => 1,
            "productos_pedido" => view('pedidos/productos_pedido', [
                "productos" => $productos_pedido,
            ]),
            "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),
            "sub_total" => number_format($total_pedido['valor_total'] + $propina['propina'], 0, ',', '.'),
            "propina" => number_format($propina['propina'], 0, ',', '.'),

        );
        echo  json_encode($returnData);
    }

    function reporte_movimiento()
    {

        $truncate = model('TempMovModel')->truncate();

        /*  $codigo_producto = $this->request->getPost('producto');
        $movimiento = $this->request->getPost('movimiento');
        $fecha_inicial = $this->request->getPost('fecha_inicial');
        $fecha_final = $this->request->getPost('fecha_final');
        $usuario_consulta = $this->request->getPost('id_usuario'); */




        $codigo_producto = 111;
        $movimiento = 2;
        $fecha_inicial = '2024-11-01';
        $fecha_final = '2024-11-21';
        $usuario_consulta = 6;

        $id_producto = model('productoModel')->getIdProducto($codigo_producto);
        $tipo_inventario = model('productoModel')->getTipoInventario($codigo_producto);

        // Mapeo de movimientos
        $operaciones = [
            1 => 1, // Entradas
            2 => 2, // Salidas
            3 => null // Todas las operaciones
        ];
        //Determinar la operación según el movimiento
        $id_operacion = isset($operaciones[$movimiento]) ? $operaciones[$movimiento] : null;

        // Obtener los movimientos
        if ($id_operacion !== null) {
            //$movimientos = model('EntradasSalidasModel')->where('id_operacion', $id_operacion)->findAll();
            $movimientos = model('EntradasSalidasModel')->getMovimientos($movimiento, $fecha_inicial, $fecha_final);
        } else {
            $movimientos = model('EntradasSalidasModel')->getMovimientosAll($fecha_inicial, $fecha_final);
        }

        $datosParaInsertar = [];



        foreach ($movimientos as $detalle) {

            switch ($detalle['tabla']) {
                case $detalle['tabla'] == 'factura_proveedor':
                    $fecha = $detalle['fecha'];
                    $movimiento = 'Compra proveedor';
                    $usuario = model('FacturaCompraModel')->getUsuario($detalle['id_documento']);
                    $documento = model('FacturaCompraModel')->select('numerofactura_proveedor')->where('numeroconsecutivofactura_proveedor', $detalle['id_documento'])->first();
                    $nota = model('FacturaCompraModel')->select('nota')->where('numeroconsecutivofactura_proveedor', $detalle['id_documento'])->first();
                    $datos = model('ComprasModel')->getProductosCompra($detalle['id_documento'], $codigo_producto);

                    foreach ($datos  as $key) {


                        $data_temp = [

                            //'movimiento' => $movimiento,
                            'movimiento' => 'Factura proveedor',
                            'producto' =>  $codigo_producto . "/" . $key['nombreproducto'],
                            'cantidad_inicial' => $key['inventario_anterior'],
                            'cantidad_final' => $key['inventario_actual'],
                            'usuario' => $usuario[0]['nombresusuario_sistema'],
                            'id_usuario' => 6,
                            'cantidad_movi' => $key['cantidad_movimiento'],
                            'fecha' => $fecha,
                            'documento' => $documento['numerofactura_proveedor'],
                            'nota' => $nota['nota'],
                            'hora' => date("g:i A", strtotime($key['hora']))
                        ];
                        $insert_temp = model('TempMovModel')->insert($data_temp);
                    }
                    break;
                case $detalle['tabla'] == 'entradas_salidas_manuales':


                    $fecha = $detalle['fecha'];
                    $datos = model('EntradasSalidasModel')->Entradas_salidas($detalle['id_documento'], $id_producto[0]['id']);

                    if (!empty($datos)) {
                        foreach ($datos  as $keyDatos) {

                            $data_temp = [

                                'movimiento' => $keyDatos['concepto_kardex'],
                                'producto' =>  $codigo_producto . "/" . $keyDatos['nombreproducto'],
                                'cantidad_inicial' => $keyDatos['inventario_anterior'],
                                'cantidad_final' => $keyDatos['inventario_actual'],
                                'usuario' => $keyDatos['usuario'],
                                'id_usuario' => 6,
                                'cantidad_movi' => $keyDatos['cantidad'],
                                'fecha' => $keyDatos['fecha'],
                                'documento' => $keyDatos['id'],
                                'nota' => $keyDatos['nota'],
                                //'hora'=>$keyDatos['hora']
                                'hora' => date("g:i A", strtotime($keyDatos['hora']))
                            ];
                            $insert_temp = model('TempMovModel')->insert($data_temp);
                        }
                    }
                    break;
                case $detalle['tabla'] == 'documento_electronico':

                    if ($tipo_inventario[0]['id_tipo_inventario'] == 1 or $tipo_inventario[0]['id_tipo_inventario'] == 3) {  // En el caso de que el producto sea solo para la venta

                        $movimientos_electronicos = model('EntradasSalidasModel')->getDatosVentas($codigo_producto, $detalle['id_documento']);

                        $nombre_usuario = model('MovimientoInsumosModel')->idUsuario($detalle['id_documento']);



                        foreach ($movimientos_electronicos as $keyMov) {
                            $data_temp = [

                                'movimiento' => "Factuta venta electrónica ",
                                'producto' =>   $codigo_producto . "/" . $keyMov['nombreproducto'],
                                'cantidad_inicial' => $keyMov['inventario_anterior'],
                                'cantidad_final' => $keyMov['inventario_actual'],
                                //'usuario' => $nombre_usuario[0]['nombresusuario_sistema'],
                                'usuario' => 'Usuario facturacion',
                                'id_usuario' => $usuario_consulta,
                                'cantidad_movi' =>  $keyMov['inventario_anterior'] - $keyMov['inventario_actual'],
                                'fecha' => $keyMov['fecha'],
                                'documento' => $keyMov['numero'],
                                'nota' => 'Producto venta',
                                'hora' => date("g:i A", strtotime($keyMov['hora']))
                            ];
                            $insert_temp = model('TempMovModel')->insert($data_temp);
                        }
                    }

                    if ($tipo_inventario[0]['id_tipo_inventario'] == 4) {

                        $item = model('MovimientoInsumosModel')->producto($detalle['id_documento'], $id_producto[0]['id']);
                        $nombre_usuario = model('MovimientoInsumosModel')->idUsuario($detalle['id_documento']);

                        if (!empty($item)) {
                            foreach ($item as $keyProducto) {

                                $componente = model('productoModel')->select('nombreproducto')->where('id', $keyProducto['id_pro_prin'])->first();

                                if (!empty($componente['nota'])) {
                                    $nota = 'INSUMO DE: ' . $componente['nombreproducto'];
                                } else if (empty($componente['nota'])) {
                                    $nota = "Producto insumo";
                                }

                                $data_temp = [

                                    'movimiento' => "Venta",
                                    'producto' =>  $codigo_producto . "/" . $keyProducto['nombreproducto'],
                                    'cantidad_inicial' => $keyProducto['inventario_anterior'],
                                    'cantidad_final' => $keyProducto['inventario_actual'],
                                    'usuario' => $nombre_usuario[0]['nombresusuario_sistema'],
                                    'id_usuario' => 6,
                                    'cantidad_movi' =>  $keyProducto['inventario_anterior'] - $keyProducto['inventario_actual'],
                                    'fecha' => $keyProducto['fecha'],
                                    'documento' => $keyProducto['numero'],
                                    'nota' => $nota,
                                    'hora' => date("g:i A", strtotime($keyProducto['hora']))
                                ];
                                $insert_temp = model('TempMovModel')->insert($data_temp);
                            }
                        }
                    }

                    $id_usuario = model('pagosModel')->select('id_usuario_facturacion')->where('id', $detalle['id_documento'])->where('id_estado', 8)->first();
                    break;
            }
        }

        $datos_finales = model('TempMovModel')->get_productos($usuario_consulta);

        if (!empty($datos_finales)) {

            $returnData = array(
                "resultado" => 1,
                "datos" => $datos_finales
            );

            echo  json_encode($returnData);
        }
        if (empty($datos_finales)) {

            $returnData = array(
                "resultado" => 0,

            );

            echo  json_encode($returnData);
        }
    }


    public function reporte_impuestos()
    {
        $borrado = model('ReporteImpuestosModel')->truncate();

        //$valor_buscado = $_GET['search']['value'];
        $fecha_inicial = $this->request->getPost('fecha_inicial');
        //$fecha_inicial = '2024-11-01';
        $fecha_final = $this->request->getPost('fecha_final');
        //$fecha_final = '2024-11-18';

        $ids = model('facturaElectronicaModel')->id_inicial_final($fecha_inicial, $fecha_final);

        $primer_factura = model('facturaElectronicaModel')->select('numero')->where('id', $ids[0]['id_minimo'])->first();
        $ultima_factura = model('facturaElectronicaModel')->select('numero')->where('id', $ids[0]['id_maximo'])->first();

        $fechas = model('pagosModel')->fechas_impuestos($fecha_inicial, $fecha_final);

        /*   $data = [
            'dia' => "",
            'fecha' => "",
            'base_inc' => "",
            'inc' => "",
            'iva_0' => "",
            'base_iva_5' => "",
            'iva_5' => "",
            'base_iva_19' => "",
            'iva_19' => "",
        ]; */

        $inc = model('pagosModel')->fechas_inc($fecha_inicial, $fecha_final);
        $iva = model('pagosModel')->fechas_iva($fecha_inicial, $fecha_final);


        $data = []; // Array acumulativo para almacenar todos los resultados
        $contador_dia = 1; // Iniciar el contador en 1


        foreach ($fechas as $keyFechas) {  //Todos los dias comprendidos entre la fecha inicial y la fecha final 



            $data_fecha = [
                'base_inc_0' => 0,
                'inc_0' => 0,
                'base_iva_0' => 0,
                'iva_0' => 0,
                'base_iva_5' => 0,
                'iva_5' => 0,
                'base_iva_19' => 0,
                'iva_19' => 0,
                'fecha' => $keyFechas['fecha'],
                'total_inc' => 0,
                'total_iva' => 0,
                'total_venta' => 0,
                'dia_proceso' => $contador_dia,
                'base_inc_8' => 0,
                'inc_8' => 0,
            ];

            $insert = model('ReporteImpuestosModel')->insert($data_fecha);
            /*   foreach ($inc as $detalleInc) {  //la variable $inc contiene los valores de  la tarifa del impuesto al consumo 



                if (!empty($detalleInc['valor_ico'])) {
                    $inc = model('kardexModel')->get_valor_inc($keyFechas['fecha'], $detalleInc['valor_ico']);   // Impuesto al consumo 
                    $total_inc = model('kardexModel')->get_base_inc($keyFechas['fecha'], $detalleInc['valor_ico']);   // Total de ventas com impuesto al consumo
                }

                if (!empty($inc[0]['inc']) and !empty($detalleInc['valor_ico']) and $detalleInc['valor_ico'] == 0) {

                    $data = [
                        'base_inc_0' => $total_inc[0]['total'] - $inc[0]['inc'],
                        'inc_0' => $inc[0]['inc'],
                    ];

                    $actualizar = model('ReporteImpuestosModel')->actualizar_tabla($data, $keyFechas['fecha'], $contador_dia);
                }
                if (!empty($inc[0]['inc']) and !empty($detalleInc['valor_ico']) and $detalleInc['valor_ico'] == 8) {



                    $data = [
                        'base_inc_8' => number_format($total_inc[0]['total'] - $inc[0]['inc'], 0, ",", "."),
                        'inc_8' => number_format($inc[0]['inc'], 0, ",", "."),
                    ];
                   

                    $actualizar = model('ReporteImpuestosModel')->actualizar_tabla($data, $keyFechas['fecha'], $contador_dia);
                }
            }  */

            /*  if (!empty($iva)) {
                foreach ($iva as $detalleIva) {

                    if (!empty($detalleIva['valor_iva'])) {

                        $temp_iva = model('kardexModel')->get_valor_iva($keyFechas['fecha'], $detalleIva['valor_iva']); //Valor del IVA 
                        $total_iva = model('kardexModel')->get_tot_iva($keyFechas['fecha'], $detalleIva['valor_iva']);


                        if ($detalleIva['valor_iva'] == 0) {
                            if (!empty($temp_iva[0]['iva'] > 0)) {

                                $data = [
                                    'base_iva_0' => number_format($total_iva[0]['total_iva'], 0, ",", "."),
                                    'iva_0' => number_format($temp_iva[0]['iva'], 0, ",", "."),
                                ];

                                $actualizar = model('ReporteImpuestosModel')->actualizar_tabla($data, $keyFechas['fecha'], $contador_dia);
                            }
                        }
                        if ($detalleIva['valor_iva'] == 5) {
                            if (!empty($temp_iva[0]['iva'] > 0)) {

                                $data = [
                                    'base_iva_5' => number_format($total_iva[0]['total_iva'] - $temp_iva[0]['iva'], 0, ",", "."),
                                    'iva_5' => number_format($temp_iva[0]['iva'], 0, ",", "."),
                                ];

                                $actualizar = model('ReporteImpuestosModel')->actualizar_tabla($data, $keyFechas['fecha'], $contador_dia);
                            }
                        }
                        if ($detalleIva['valor_iva'] == 19) {
                            if (!empty($temp_iva[0]['iva'] > 0)) {

                                $data = [
                                    'base_iva_19' => number_format($total_iva[0]['total_iva'], 0, ",", "."),
                                    'iva_19' => number_format($temp_iva[0]['iva'], 0, ",", "."),
                                ];

                                $actualizar = model('ReporteImpuestosModel')->actualizar_tabla($data, $keyFechas['fecha'], $contador_dia);
                            }
                        }
                    }
                }
            } */

            /**
             * Impuesto al conusmo 0 
             */
            $inc_0 = model('kardexModel')->get_valor_inc($keyFechas['fecha'], 0);   // Impuesto al consumo 
            $total_inc_0 = model('kardexModel')->get_base_inc($keyFechas['fecha'], 0);

            $data_0 = [
                'base_inc_0' => number_format($total_inc_0[0]['total'] - $inc_0[0]['inc'], 0, ",", "."),
                'inc_0' => number_format($inc_0[0]['inc'], 0, ",", "."),
            ];

            $actualizar = model('ReporteImpuestosModel')->actualizar_tabla($data_0, $keyFechas['fecha'], $contador_dia);
            /**
             * Impuesto al conusmo 0 
             */

            /**
             * Impuesto al conusmo 8 
             */
            $inc_8 = model('kardexModel')->get_valor_inc($keyFechas['fecha'], 8);   // Impuesto al consumo 
            $total_inc_8 = model('kardexModel')->get_base_inc($keyFechas['fecha'], 8);

            $data_8 = [
                'base_inc_8' => number_format($total_inc_8[0]['total'] - $inc_8[0]['inc'], 0, ",", "."),
                'inc_8' => number_format($inc_8[0]['inc'], 0, ",", "."),
            ];

            $actualizar = model('ReporteImpuestosModel')->actualizar_tabla($data_8, $keyFechas['fecha'], $contador_dia);
            /**
             * Impuesto al conusmo 8 
             */


            $temp_iva_0 = model('kardexModel')->get_valor_iva($keyFechas['fecha'], 0); //Valor del IVA 
            $total_iva_0 = model('kardexModel')->get_tot_iva($keyFechas['fecha'], 0);

            if (!empty($temp_iva_0[0]['iva'] > 0)) {

                $data_iva_0 = [
                    'base_iva_0' => number_format($total_iva_0[0]['total_iva'], 0, ",", "."),
                    'iva_0' => number_format($temp_iva_0[0]['iva'], 0, ",", "."),
                ];

                $actualizar = model('ReporteImpuestosModel')->actualizar_tabla($data_iva_0, $keyFechas['fecha'], $contador_dia);
            }
            $temp_iva_5 = model('kardexModel')->get_valor_iva($keyFechas['fecha'], 5); //Valor del IVA 
            $total_iva_5 = model('kardexModel')->get_tot_iva($keyFechas['fecha'], 5);

            if (!empty($temp_iva_5[0]['iva'] > 0)) {

                $data_iva_5 = [
                    'base_iva_5' => number_format($total_iva_5[0]['total_iva'], 0, ",", "."),
                    'iva_5' => number_format($temp_iva_5[0]['iva'], 0, ",", "."),
                ];

                $actualizar = model('ReporteImpuestosModel')->actualizar_tabla($data_iva_5, $keyFechas['fecha'], $contador_dia);
            }

            $temp_iva_19 = model('kardexModel')->get_valor_iva($keyFechas['fecha'], 19); //Valor del IVA 
            $total_iva_19 = model('kardexModel')->get_tot_iva($keyFechas['fecha'], 19);

            if (!empty($temp_iva_19[0]['iva'] > 0)) {

                $data_iva_19 = [
                    'base_iva_19' => number_format($total_iva_19[0]['total_iva'], 0, ",", "."),
                    'iva_19' => number_format($temp_iva_19[0]['iva'], 0, ",", "."),
                ];

                $actualizar = model('ReporteImpuestosModel')->actualizar_tabla($data_iva_19, $keyFechas['fecha'], $contador_dia);
            }


            $total_inc = model('kardexModel')->get_total_inc_fecha($keyFechas['fecha']);
            $total_iva = model('kardexModel')->get_total_iva_fecha($keyFechas['fecha']);
            $total_venta = model('kardexModel')->get_total_fecha($keyFechas['fecha']);

            $data = [
                'total_inc' => number_format($total_inc[0]['inc'], 0, ",", "."),
                'total_iva' => number_format($total_iva[0]['iva'], 0, ",", "."),
                'total_venta' => number_format($total_venta[0]['total'], 0, ",", "."),
            ];

            $actualizar = model('ReporteImpuestosModel')->actualizar_tabla($data, $keyFechas['fecha'], $contador_dia);

            $contador_dia++; // Incrementar el contador después de cada fecha
        }

        $datos = model('ReporteImpuestosModel')->getValores();

        if (!empty($datos)) {
            $total_venta = model('facturaElectronicaModel')->total_venta($fecha_inicial, $fecha_final);
            $total_IVA = model('kardexModel')->get_iva_reporte($fecha_inicial, $fecha_final);
            $total_INC = model('kardexModel')->get_ico_reporte($fecha_inicial, $fecha_final);

            $returnData = array(
                "resultado" => 1,
                "datos" => $datos,
                'primer_factura' => "Primer factura " . $primer_factura['numero'],
                'ultima_factura' => "Última factura " . $ultima_factura['numero'],
                'total_venta' => "Total venta  $ " . number_format($total_venta[0]['total_venta'], 0, ",", "."),
                'total_iva' => "Total IVA $ " . number_format($total_IVA[0]['iva'], 0, ",", "."),
                'total_inc' => "Total INC " . number_format($total_INC[0]['ico'], 0, ",", "."),
            );

            echo  json_encode($returnData);
        }
        if (empty($datos)) {
            $returnData = array(
                "resultado" => 0
            );

            echo  json_encode($returnData);
        }
    }
}
