<?php

namespace App\Controllers\consultas_y_reportes;

require APPPATH . "Controllers/mike42/autoload.php";

use App\Controllers\BaseController;
use \DateTime;
use \DateTimeZone;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use App\Libraries\data_table;
use App\Libraries\tipo_consulta;


class Documento extends BaseController
{
    public $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function documento()
    {
        $documento = $this->request->getGet('documento');
        //$documento = '49892';


        $datos = model('facturaVentaModel')->busqueda_documento($documento);
        $valor_factura = model('facturaVentaModel')->select('valor_factura')->where('numerofactura_venta', $documento)->first();
        $saldo_factura = model('facturaVentaModel')->select('saldo')->where('numerofactura_venta', $documento)->first();

        if (!empty($datos)) {
            $json_data = [
                'resultado' => 1,
                'documento' => view('consultas_y_reportes/documento', [
                    'datos' => $datos
                ]),
                'valor_factura' => "VALOR FACTURA " . "$" . number_format($valor_factura['valor_factura'], 0, ",", "."),
                'saldo_factura' => $saldo_factura['saldo'],
                'pagos' => "PAGOS " . "$" . number_format($valor_factura['valor_factura'] - $saldo_factura['saldo'], 0, ",", ".")

            ];

            echo  json_encode($json_data);
        }
        if (empty($datos)) {
            $json_data = [
                'resultado' => 0,

            ];
            echo  json_encode($json_data);
        }
    }

    public function tipo_documento()
    {
        //$valor_buscado = $_GET['search']['value'];

        // $tipo_documento = 5;
        $tipo_documento = $_REQUEST['documento'];

        $fecha_ini = $_REQUEST['fecha_inicial'];
        //$fecha_ini = '2023-01-01';


        $sql_count = '';
        $sql_data = '';

        $fecha_inicial = '';

        $temp_sql_data = "";
        $saldo = "";

        if (empty($fecha_ini)) {
            $temp_fecha_inicial = model('facturaVentaModel')->selectMin('fecha_factura_venta')->first();
            $fecha_inicial = $temp_fecha_inicial['fecha_factura_venta'];
        } else if (!empty($fecha_ini)) {
            $fecha_inicial = $fecha_ini;
        }

        $fecha_fin = $_REQUEST['fecha_final'];
        //$fecha_fin = '2023-06-07';


        $fecha_final = '';
        if (empty($fecha_fin)) {
            $temp_fecha_final = model('facturaVentaModel')->selectMax('fecha_factura_venta')->first();
            $fecha_final = $temp_fecha_final['fecha_factura_venta'];
        } else if (!empty($fecha_fin)) {
            $fecha_final = $fecha_fin;
        }

        $table_map = [
            0 => 'id',
            1 => 'nitcliente',
            2 => 'nombrescliente',
            3 => 'descripcionestado',
            4 => 'fecha_factura_venta',
            5 => 'horafactura_venta',
            6 => 'fechalimitefactura_venta',
        ];
        if ($tipo_documento == 1 or $tipo_documento == 7) {

            $saldo = model('facturaVentaModel')->saldo_factura($fecha_inicial, $fecha_final, $tipo_documento);

            $total_saldo = model('facturaVentaModel')->total_saldo_factura($fecha_inicial, $fecha_final, $tipo_documento);

            $temp_sql_count = "SELECT
                        COUNT(factura_venta.id) AS total
                    FROM
                        factura_venta
                    INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
                    INNER JOIN estado ON factura_venta.idestado = estado.idestado WHERE factura_venta.idestado=$tipo_documento AND fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' ";


            /* $temp_sql_sum = "SELECT
                        SUM(factura_venta.saldo) AS saldo
                    FROM
                        factura_venta
                    INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
                    INNER JOIN estado ON factura_venta.idestado = estado.idestado WHERE factura_venta.idestado=$tipo_documento AND fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' "; */


            $temp_sql_data = " SELECT
                            factura_venta.id,
                            factura_venta.nitcliente,
                            cliente.nombrescliente,
                            descripcionestado,
                            fecha_factura_venta,
                            horafactura_venta,
                            fechalimitefactura_venta,
                            numerofactura_venta,
                            fechalimitefactura_venta,
                            valor_factura,saldo,
                            factura_venta.idestado
                        FROM
                            factura_venta
                        INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
                        INNER JOIN estado ON factura_venta.idestado = estado.idestado WHERE factura_venta.idestado=$tipo_documento AND fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' ";
        }

        if ($tipo_documento == 2 or $tipo_documento == 6) {

            $saldo = model('facturaVentaModel')->saldo($fecha_inicial, $fecha_final, $tipo_documento);
            $total_saldo = model('facturaVentaModel')->total_saldo($fecha_inicial, $fecha_final, $tipo_documento);


            $temp_sql_count = "SELECT
            COUNT(factura_venta.id) AS total
        FROM
            factura_venta
        INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
        INNER JOIN estado ON factura_venta.idestado = estado.idestado WHERE factura_venta.idestado=$tipo_documento AND fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' ";

            $temp_sql_data = " SELECT
                            factura_venta.id,
                            factura_venta.nitcliente,
                            cliente.nombrescliente,
                            descripcionestado,
                            fecha_factura_venta,
                            horafactura_venta,
                            fechalimitefactura_venta,
                            numerofactura_venta,
                            fechalimitefactura_venta,
                            valor_factura,
                            saldo,
                            factura_venta.idestado
                        FROM
                            factura_venta
                        INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
                        INNER JOIN estado ON factura_venta.idestado = estado.idestado WHERE factura_venta.idestado=$tipo_documento AND fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' ";
        }

        if ($tipo_documento == 5) {



            $saldo = model('facturaVentaModel')->saldo_todos_documentos($fecha_inicial, $fecha_final);
            $total_saldo = model('facturaVentaModel')->total_saldo_documentos($fecha_inicial, $fecha_final);


            $temp_sql_count = "SELECT
            COUNT(factura_venta.id) AS total
        FROM
            factura_venta
        INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
        INNER JOIN estado ON factura_venta.idestado = estado.idestado WHERE  fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' ";

            $temp_sql_data = " SELECT
                            factura_venta.id,
                            factura_venta.nitcliente,
                            cliente.nombrescliente,
                            descripcionestado,
                            fecha_factura_venta,
                            horafactura_venta,
                            fechalimitefactura_venta,
                            numerofactura_venta,
                            fechalimitefactura_venta,
                            valor_factura,saldo,
                            factura_venta.idestado
                        FROM
                            factura_venta
                        INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
                        INNER JOIN estado ON factura_venta.idestado = estado.idestado WHERE  fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' ";
        }



        $condition = "";

        if (!empty($valor_buscado)) {

            $condition .= " AND cliente.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR descripcionestado ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR factura_venta.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR numerofactura_venta ILIKE '%" . $valor_buscado . "%'";
        }

        $sql_count = $temp_sql_count;
        $sql_data = $temp_sql_data;
        //$sql_sum = $temp_sql_sum;
        $sql_count = $sql_count . $condition;
        $sql_data = $sql_data . $condition;
        //$sql_sum = $sql_sum . $condition;
        //$saldo = $this->db->query($sql_sum)->getRow();
        $total_count = $this->db->query($sql_count)->getRow();

        $sql_data .= " ORDER BY " . $table_map[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " " . "LIMIT " . $_GET['length'] . " OFFSET " . $_GET['start'];

        $datos = $this->db->query($sql_data)->getResultArray();



        if (!empty($datos)) {

            foreach ($datos as $detalle) {
                $sub_array = array();
                $sub_array[] = number_format($detalle['nitcliente'], 0, ",", ".");
                $sub_array[] = $detalle['nombrescliente'];
                $sub_array[] = $detalle['descripcionestado'];
                $sub_array[] = $detalle['numerofactura_venta'];
                $sub_array[] = $detalle['fecha_factura_venta'];
                // $sub_array[] = date("g:i a", strtotime($detalle['horafactura_venta']));
                $sub_array[] = $detalle['fechalimitefactura_venta'];
                $sub_array[] = "$" . number_format($detalle['valor_factura'], 0, ",", ".");
                $sub_array[] = "$" . number_format($detalle['saldo'], 0, ",", ".");
                $sub_array[] = '<a  class="btn btn-primary btn-icon " title="Imprimir copia " onclick="imprimir_duplicado_factura(' . $detalle['id'] . ')" >
                <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg></a>  
            <a  class="btn bg-muted-lt btn-icon " title="Ver detalle" onclick="detalle_de_factura(' . $detalle['id'] . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg></a>
            <a  class="btn bg-green-lt btn-icon " title="Realizar pago general " onclick="abonos_a_cartera(' . $detalle['nitcliente'] . ',' . $detalle['idestado'] . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" /><path d="M12 3v3m0 12v3" /></svg></a>
            <a onclick="abono_credito(' . $detalle['id'] . ')"  title="Realizar pago documento " class="btn btn-primary  btn-icon" ><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" /><path d="M12 3v3m0 12v3" /></svg> </a> ';
                $data[] = $sub_array;
            }



            $json_data = [
                'draw' => intval($this->request->getGEt(index: 'draw')),
                'recordsTotal' => $total_count->total,
                'recordsFiltered' => $total_count->total,
                'data' => $data,
                'saldo' => 'SALDO CARTERA:  ' . "$" . number_format($saldo[0]['saldo'], 0, ",", "."),
                'total' => 'TOTAL:  ' . "$" . number_format($total_saldo[0]['saldo'], 0, ",", "."),
                'pagos' => "PAGOS " . "$" . number_format($total_saldo[0]['saldo'] - $saldo[0]['saldo'], 0, ",", "."),

            ];

            echo  json_encode($json_data);
        }
    }

    public function cliente()
    {
        $valor_buscado = $_GET['search']['value'];
        //$tipo_documento = 1;
        $tipo_documento = $_REQUEST['documento'];
        $fecha_ini = $_REQUEST['fecha_inicial'];

        //$cliente = 22222222;
        $cliente = $_REQUEST['cliente'];

        $sql_count = '';
        $sql_data = '';

        $fecha_inicial = '';

        if (empty($fecha_ini)) {
            $temp_fecha_inicial = model('facturaVentaModel')->selectMin('fecha_factura_venta')->first();
            $fecha_inicial = $temp_fecha_inicial['fecha_factura_venta'];
        } else if (!empty($fecha_ini)) {
            $fecha_inicial = $fecha_ini;
        }

        //$fecha_fin = '';
        $fecha_fin = $_REQUEST['fecha_final'];

        $fecha_final = '';
        if (empty($fecha_fin)) {
            $temp_fecha_final = model('facturaVentaModel')->selectMax('fecha_factura_venta')->first();
            $fecha_final = $temp_fecha_final['fecha_factura_venta'];
        } else if (!empty($fecha_fin)) {
            $fecha_final = $fecha_fin;
        }

        $table_map = [
            0 => 'id',
            1 => 'nitcliente',
            2 => 'nombrescliente',
            3 => 'descripcionestado',
            4 => 'fecha_factura_venta',
            5 => 'horafactura_venta',
            6 => 'fechalimitefactura_venta',
        ];

        $temp_sql_count = "SELECT
                    COUNT(factura_venta.id) AS total
                FROM
                    factura_venta
                INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
                INNER JOIN estado ON factura_venta.idestado = estado.idestado WHERE factura_venta.idestado=$tipo_documento AND fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' and  cliente.nitcliente='$cliente'  ";

        $temp_sql_data = " SELECT
                        factura_venta.id,
                        factura_venta.nitcliente,
                        cliente.nombrescliente,
                        descripcionestado,
                        fecha_factura_venta,
                        horafactura_venta,
                        fechalimitefactura_venta,
                        numerofactura_venta,
                        fechalimitefactura_venta,
                        valor_factura,saldo,
                        factura_venta.idestado
                    FROM
                        factura_venta
                    INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
                    INNER JOIN estado ON factura_venta.idestado = estado.idestado WHERE factura_venta.idestado=$tipo_documento AND fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' and  cliente.nitcliente='$cliente' ";

        $condition = "";

        if (!empty($valor_buscado)) {
            $condition .= " AND descripcionestado ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR factura_venta.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR numerofactura_venta ILIKE '%" . $valor_buscado . "%'";
            // $condition .= " OR fecha_factura_venta BETWEEN " . "'$valor_buscado'" . " AND " . "'$valor_buscado'";
        }

        $sql_count = $temp_sql_count;
        $sql_data = $temp_sql_data;
        //$sql_sum = $temp_sql_sum;
        $sql_count = $sql_count . $condition;
        $sql_data = $sql_data . $condition;
        // $sql_sum = $sql_sum . $condition;
        //dd($sql_data);
        //$saldo = $this->db->query($sql_sum)->getRow();
        $total_count = $this->db->query($sql_count)->getRow();

        // $sql_data .= " ORDER BY " . $table_map[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " " . "LIMIT " . $_GET['length'] . " OFFSET " . $_GET['start'];

        $datos = $this->db->query($sql_data)->getResultArray();
        if (!empty($datos) && !empty($total_count->total)) {

            foreach ($datos as $detalle) {
                $sub_array = array();
                $sub_array[] = number_format($detalle['nitcliente'], 0, ",", ".");
                $sub_array[] = $detalle['nombrescliente'];
                $sub_array[] = $detalle['descripcionestado'];
                $sub_array[] = $detalle['numerofactura_venta'];
                $sub_array[] = $detalle['fecha_factura_venta'];
                $sub_array[] = $detalle['fechalimitefactura_venta'];
                $sub_array[] = "$" . number_format($detalle['valor_factura'], 0, ",", ".");
                $sub_array[] = "$" . number_format($detalle['saldo'], 0, ",", ".");
                //$sub_array[] = date("g:i a", strtotime($detalle['horafactura_venta']));

                $accion = new data_table();

                $acciones = $accion->row_data_table($detalle['id_estado'], $detalle['id_factura']);

                $sub_array[] = $acciones;

                $data[] = $sub_array;
            }

            $saldo = model('facturaVentaModel')->saldo($fecha_inicial, $fecha_final, $tipo_documento);

            $json_data = [
                //'draw' => intval($this->request->getGEt(index: 'draw')),
                'draw' => intval($this->request->getGEt(index: 'draw')),
                'recordsTotal' => $total_count->total,
                'recordsFiltered' => $total_count->total,
                'saldo' => 'SALDO:  ' . number_format($saldo[0]['saldo'], 0, ",", "."),
                'data' => $data,


            ];

            echo  json_encode($json_data);
        }
    }
    public function por_defecto()
    {

        $sql_count = '';
        $sql_data = '';

        $fecha_inicial = date('Y-m-d');
        $fecha_final = date('Y-m-d');




        $table_map = [
            0 => 'id',
            1 => 'nitcliente',
            2 => 'nombrescliente',
            3 => 'descripcionestado',
            4 => 'fecha_factura_venta',
            5 => 'horafactura_venta',
            6 => 'fechalimitefactura_venta',
        ];

        $temp_sql_count = "SELECT
                    COUNT(factura_venta.id) AS total
                FROM
                    factura_venta
                INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
                INNER JOIN estado ON factura_venta.idestado = estado.idestado WHERE  fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' ";

        $temp_sql_data = " SELECT
                        factura_venta.id,
                        factura_venta.nitcliente,
                        cliente.nombrescliente,
                        descripcionestado,
                        fecha_factura_venta,
                        horafactura_venta,
                        fechalimitefactura_venta,
                        numerofactura_venta,
                        fechalimitefactura_venta,
                        valor_factura,saldo
                    FROM
                        factura_venta
                    INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
                    INNER JOIN estado ON factura_venta.idestado = estado.idestado WHERE fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' ";

        $condition = "";

        if (!empty($valor_buscado)) {
            $condition .= " AND descripcionestado ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR factura_venta.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR numerofactura_venta ILIKE '%" . $valor_buscado . "%'";
            // $condition .= " OR fecha_factura_venta BETWEEN " . "'$valor_buscado'" . " AND " . "'$valor_buscado'";
        }

        $sql_count = $temp_sql_count;
        $sql_data = $temp_sql_data;
        //$sql_sum = $temp_sql_sum;
        $sql_count = $sql_count . $condition;
        $sql_data = $sql_data . $condition;
        // $sql_sum = $sql_sum . $condition;
        //dd($sql_data);
        //$saldo = $this->db->query($sql_sum)->getRow();
        $total_count = $this->db->query($sql_count)->getRow();
        $sql_data .= " ORDER BY " . $table_map[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " " . "LIMIT " . $_GET['length'] . " OFFSET " . $_GET['start'];


        $datos = $this->db->query($sql_data)->getResultArray();

        if (!empty($datos) && !empty($total_count->total)) {

            foreach ($datos as $detalle) {
                $sub_array = array();
                $sub_array[] = number_format($detalle['nitcliente'], 0, ",", ".");
                $sub_array[] = $detalle['nombrescliente'];
                $sub_array[] = $detalle['descripcionestado'];
                $sub_array[] = $detalle['numerofactura_venta'];
                $sub_array[] = $detalle['fecha_factura_venta'];
                $sub_array[] = $detalle['fechalimitefactura_venta'];
                $sub_array[] = "$" . number_format($detalle['valor_factura'], 0, ",", ".");
                $sub_array[] = "$" . number_format($detalle['saldo'], 0, ",", ".");
                //$sub_array[] = date("g:i a", strtotime($detalle['horafactura_venta']));
                $sub_array[] = '<a  class="btn btn-primary btn-icon " title="Imprimir copia " onclick="imprimir_duplicado_factura(' . $detalle['id'] . ')" >
                <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg></a>  
            <a  class="btn bg-muted-lt btn-icon " title="Ver detalle" onclick="detalle_de_factura(' . $detalle['id'] . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg></a>
            <a  class="btn bg-green-lt btn-icon " title="Realizar pago " onclick="abonos_a_cartera(' . $detalle['nitcliente'] . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" /><path d="M12 3v3m0 12v3" /></svg></a>
            <a  class="btn bg-muted-lt-lt btn-icon " title="Ver pago " onclick="detalle_de_factura(' . $detalle['id'] . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eyeglass-2 -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 4h-2l-3 10v2.5" /><path d="M16 4h2l3 10v2.5" /><line x1="10" y1="16" x2="14" y2="16" /><circle cx="17.5" cy="16.5" r="3.5" /><circle cx="6.5" cy="16.5" r="3.5" /></svg></a>
            <a  class="btn bg-green-lt btn-icon "  onclick="detalle_de_factura(' . $detalle['id'] . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" /><line x1="13.5" y1="6.5" x2="17.5" y2="10.5" /></svg></a>
            <a onclick="abono_credito(' . $detalle['id'] . ')"  class="btn btn-primary  btn-icon" ><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" /><path d="M12 3v3m0 12v3" /></svg> </a> ';
                $data[] = $sub_array;
            }

            //$saldo = model('facturaVentaModel')->saldo($fecha_inicial, $fecha_final);

            $json_data = [
                //'draw' => intval($this->request->getGEt(index: 'draw')),
                'draw' => intval($this->request->getGEt(index: 'draw')),
                'recordsTotal' => $total_count->total,
                'recordsFiltered' => $total_count->total,
                //'saldo' => 'SALDO:  ' . number_format($saldo[0]['saldo'], 0, ",", "."),
                'data' => $data,


            ];

            echo  json_encode($json_data);
        } /* else {
            $sub_array = array();
            $sub_array[] = 'NO HAY DATOS ';
            $sub_array[] = 'NO HAY DATOS ';
            $sub_array[] = 'NO HAY DATOS ';
            $sub_array[] = 'NO HAY DATOS ';
            $sub_array[] = 'NO HAY DATOS ';
            $sub_array[] = 'NO HAY DATOS ';
            $sub_array[] = 'NO HAY DATOS ';
            $sub_array[] = 'NO HAY DATOS ';
            $sub_array[] = 'NO HAY DATOS ';
            $data[] = $sub_array;
            $json_data = [
                //'draw' => intval($this->request->getGEt(index: 'draw')),
                'draw' => intval($this->request->getGEt(index: 'draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => $data,

            ];
            echo  json_encode($json_data);
        } */
    }

    public function cartera_cliente()
    {

        $nit_cliente = $this->request->getPost('nit_cliente');
        $fecha_ini = $this->request->getPost('fecha_inicial');
        $documento = $this->request->getPost('documento');



        $fecha_inicial = '';
        $fecha_final = '';
        if (empty($fecha_ini)) {
            $temp_fecha_inicial = model('facturaVentaModel')->selectMin('fecha_factura_venta')->first();
            $fecha_inicial = $temp_fecha_inicial['fecha_factura_venta'];
        } else if (!empty($fecha_ini)) {
            $fecha_inicial = $fecha_ini;
        }

        $fecha_fin = $this->request->getPost('fecha_final');
        if (empty($fecha_fin)) {
            $temp_fecha_final = model('facturaVentaModel')->selectMax('fecha_factura_venta')->first();
            $fecha_final = $temp_fecha_final['fecha_factura_venta'];
        } else if (!empty($fecha_fin)) {
            $fecha_final = $fecha_fin;
        }

        $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $nit_cliente)->first();


        $saldo_cartera = model('facturaVentaModel')->saldo_cartera($nit_cliente, $fecha_inicial, $fecha_final, $documento);


        if (!empty($saldo_cartera[0]['saldo'])) {
            $json_data = [
                'resultado' => 1,
                /*   'saldo_cartera' => view('consultas_y_reportes/saldo_cartera', [
                    'saldo' => "$" . number_format($saldo_cartera[0]['saldo'], 0, ",", "."),
                    'cliente' => $nombre_cliente['nombrescliente'],
                    'nit' => $nit_cliente

                ]) */
                'saldo' => number_format($saldo_cartera[0]['saldo'], 0, ",", "."),
                'cliente' => $nombre_cliente['nombrescliente'],
                'nit' => $nit_cliente,
                'fecha_inicial' => $fecha_inicial,
                'fecha_final' => $fecha_final,

            ];
            echo  json_encode($json_data);
        } else if (empty($saldo_cartera[0]['saldo'])) {
            $json_data = [
                'resultado' => 0,

            ];
            echo  json_encode($json_data);
        }
    }

    function pagar_cartera_cliente()
    {
        $efectivo = "";

        $transaccion = "";

        $dibuja_DataTable = $this->request->getPost('dibuja_DataTable');


        $nit_cliente = $this->request->getPost('nit_cliente');
        //$nit_cliente = '1088537435';

        //$efect = str_replace(".", "", 100.000);
        $efect = str_replace(".", "", $this->request->getPost('efectivo'));

        $transac = str_replace(".", "", $this->request->getPost('transaccion'));
        //$transac = str_replace(".", "", 100.000);


        $fecha_inicial = $this->request->getPost('fecha_inicial');
        //$fecha_inicial = '2023-04-25';

        $fecha_final = $this->request->getPost('fecha_final');
        //$fecha_final = '2023-04-25';

        $id_usuario = $this->request->getPost('id_usuario');
        //$id_usuario = 8;

        $abono = str_replace(".", "", $this->request->getPost('abono'));
        //$abono = str_replace(".", "", 70.200);

        $fecha = DateTime::createFromFormat('U.u', microtime(TRUE));
        $fecha->setTimeZone(new DateTimeZone('America/Bogota'));
        $fecha_y_hora = $fecha->format('Y-m-d H:i:s.u');

        $disponible = "";


        if (empty($efect)) {
            $efectivo = 0;
            $ingreso_efectivo = 0;
        } elseif (!empty($efect)) {
            $efectivo = $efect;
            $ingreso_efectivo = $efect;
        }

        if (empty($transac)) {
            $transaccion = 0;
            $ingreso_transaccion = 0;
        } elseif (!empty($efect)) {
            $transaccion = $transac;
            $ingreso_transaccion = $transac;
        }


        $facturas = model('facturaVentaModel')->factura_credito_cliente($nit_cliente, $fecha_inicial, $fecha_final);

        $temp_disponible = 0;
        $total_abono = 0;
        $saldo = 0;
        $forma_pago = 0;
        $consulta = [];
        foreach ($facturas as $factura) {
            array_push($consulta, $factura['numerofactura_venta']);
            $temp_forma_pago = $forma_pago;

            $disponible = $abono;


            $saldo_factura = $factura['saldo'];

            $temp_efectivo = $efectivo;
            $temp_transaccion = $transaccion;


            if ($saldo_factura >= $disponible and $disponible > 0) {
                if ($saldo_factura > $disponible) {
                    //echo "LO DISPONIBLE ES 1 " . $temp_disponible = $disponible . "</br>";
                    $temp_disponible =  $disponible;
                    $total_abono = $saldo_factura - $temp_disponible;
                    $temp_disponible = 0;
                    $data = [
                        'saldo' => $saldo_factura - $total_abono
                    ];

                    $num_fact = model('facturaVentaModel');
                    $numero_factura = $num_fact->set($data);
                    $numero_factura = $num_fact->where('id', $factura['id']);
                    $numero_factura = $num_fact->update();
                }
                if ($saldo_factura == $disponible and $disponible > 0) {
                    //echo "LO DISPONIBLE ES 2 " . $temp_disponible = $disponible . "</br>";
                    $temp_disponible = $disponible;
                    $total_abono = $saldo_factura;
                    $temp_disponible = 0;

                    $data = [
                        'saldo' => $saldo_factura - $total_abono
                    ];

                    $num_fact = model('facturaVentaModel');
                    $numero_factura = $num_fact->set($data);
                    $numero_factura = $num_fact->where('id', $factura['id']);
                    $numero_factura = $num_fact->update();
                }
            } else if ($saldo_factura < $disponible and $disponible > 0) {
                $temp_disponible = $disponible - $saldo_factura;
                $total_abono = $saldo_factura;

                $data = [
                    'saldo' => $saldo_factura - $total_abono
                ];

                $num_fact = model('facturaVentaModel');
                $numero_factura = $num_fact->set($data);
                $numero_factura = $num_fact->where('id', $factura['id']);
                $numero_factura = $num_fact->update();
            }

            $abono = $temp_disponible;

            $forma_pago = $temp_forma_pago + $abono;

            if ($temp_efectivo > 0) {

                switch ($abono_efectivo = $temp_efectivo - $saldo_factura) { //Si es mayor o igual a cero sobra plata y no se toca la transaccion, si es menor que cero quedo faltando y se completa con la transaccion si la hay es decir si la transaccion es mayor a cero 

                    case ($abono_efectivo < 0):

                        $valor_pago = $saldo_factura;
                        $valor_pagado = $temp_efectivo;

                        $forma_pago = model('facturaVentaModel')->insertar_forma_pago($factura['numerofactura_venta'], $valor_pago, $valor_pagado, $factura['id'], $fecha_y_hora, 1, $id_usuario);
                        $efectivo = 0;

                        $temp_saldo_factura = $saldo_factura - $temp_efectivo;



                        if ($temp_transaccion > 0) {
                            $abono_transaccion = $temp_transaccion + $abono_efectivo;
                            if ($abono_transaccion >= 0) {
                                $valor_pago = $temp_transaccion - $abono_transaccion;
                                $valor_pagado = $valor_pago;
                                $transaccion = $abono_transaccion;
                                $forma_pago = model('facturaVentaModel')->insertar_forma_pago($factura['numerofactura_venta'], $valor_pago, $valor_pagado, $factura['id'], $fecha_y_hora, 4, $id_usuario);
                            } else if ($abono_transaccion < 0) {

                                $valor_pago = $temp_saldo_factura;
                                $valor_pagado = $temp_transaccion;
                                $forma_pago = model('facturaVentaModel')->insertar_forma_pago($factura['numerofactura_venta'], $valor_pago, $valor_pagado, $factura['id'], $fecha_y_hora, 4, $id_usuario);
                                $transaccion = 0;
                            }
                        }
                        break;
                    case ($abono_efectivo >= 0):
                        $valor_pago = $temp_efectivo;
                        $valor_pagado = $saldo_factura;
                        $forma_pago = model('facturaVentaModel')->insertar_forma_pago($factura['numerofactura_venta'], $valor_pago, $valor_pagado, $factura['id'], $fecha_y_hora, 1, $id_usuario);
                        $efectivo = $abono_efectivo;
                        break;
                }
            }

            if ($temp_efectivo == 0 and $temp_transaccion > 0) {
                switch ($abono_transaccion = $temp_transaccion - $saldo_factura) {
                    case ($abono_transaccion < 0):
                        $valor_pago = $saldo_factura;
                        $valor_pagado = $saldo_factura - $temp_transaccion;
                        $transaccion = 0;
                        $forma_pago = model('facturaVentaModel')->insertar_forma_pago($factura['numerofactura_venta'], $valor_pago, $valor_pagado, $factura['id'], $fecha_y_hora, 4, $id_usuario);
                        break;
                }
            }
        }

        $consecutivo_ingreso = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 32)->first();
        $consulta = implode(", ", $consulta);
        $saldo = model('facturaVentaModel')->saldo_cartera_cliente($fecha_inicial, $fecha_final);

        $ingreso = [
            'numero' => $consecutivo_ingreso['numeroconsecutivo'],
            'concepto' => 'ABONO A FACTURAS  ' . $consulta,
            'tipo' => 1,
            'id_relacion' => 0,
            'fecha' => date('Y-m-d'),
            'valor' => $ingreso_transaccion + $ingreso_efectivo,
            'estado' => 'TRUE',
            'saldo' => $saldo[0]['saldo'],
            'nitcliente' => $nit_cliente,
            'idcaja' => 1,
            'idusuario' => $id_usuario

        ];

        $insert_ingreso = model('ingresoModel')->insert($ingreso);
        $id_ingreso = model('ingresoModel')->where('idusuario', $id_usuario)->insertID;
        if ($ingreso_efectivo != 0) {
            $ingreso_efectivo = [
                'idingreso' => $id_ingreso,
                'idformapago' => 1,
                'valor' => $ingreso_efectivo,
            ];


            $insertar_forma_pago = model('ingresoFormaPagoModel')->insert($ingreso_efectivo);
        }
        if ($ingreso_transaccion != 0) {
            $ingreso_transaccion = [
                'idingreso' => $id_ingreso,
                'idformapago' => 4,
                'valor' => $ingreso_transaccion,
            ];


            $insertar_forma_pago = model('ingresoFormaPagoModel')->insert($ingreso_transaccion);
        }
        $data = [
            'numeroconsecutivo' => $consecutivo_ingreso['numeroconsecutivo'] + 1,
        ];
        $model = model('consecutivosModel');
        $actualizar = $model->set($data);
        $actualizar = $model->where('idconsecutivos', 32);
        $actualizar = $model->update();


        $consulta_cliente = model('facturaVentaModel')->consultar_cartera($nit_cliente);
        $saldo = model('facturaVentaModel')->consultar_saldo($nit_cliente);
        $valor_facturas = model('facturaVentaModel')->valor_facturas($nit_cliente);



        if ($dibuja_DataTable == "no") {
            $returnData = array(

                "resultado" => 5,
                "id_ingreso" => $id_ingreso,
                "datos" => view('consultas_y_reportes/datos_consulta_cartera', [
                    'datos' => $consulta_cliente,
                    'cartera' => "$" . number_format($saldo[0]['saldo_cartera'], 0, ",", "."),
                    'valor_facturas' => "$" . number_format($valor_facturas[0]['valor_facturas'], 0, ",", ".")
                ]),
                'cartera' => "CARTERA $" . number_format($saldo[0]['saldo_cartera'], 0, ",", "."),
                'valor_facturas' => "VALOR FACTURAS $" . number_format($valor_facturas[0]['valor_facturas'], 0, ",", "."),
                'pagos' => "PAGOS $" . number_format($valor_facturas[0]['valor_facturas'] - $saldo[0]['saldo_cartera'], 0, ",", "."),

            );
            echo  json_encode($returnData);
        }
        if ($dibuja_DataTable == "si") {
            $returnData = array(

                "resultado" => 1,
                "id_ingreso" => $id_ingreso,
            );
            echo  json_encode($returnData);
        }
    }

    function imprimir_comprobante_ingreso()
    {
        $id_ingreso = $this->request->getPost('id_ingreso');
        $connector = new WindowsPrintConnector('FACTURACION');
        $printer = new Printer($connector);
        $datos_empresa = model('empresaModel')->datosEmpresa();
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
        $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento'] . "\n");
        $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
        $printer->text("\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("FECHA:" . date('Y-m-d') . "\n");
        $id_usuario = model('ingresoModel')->select('idusuario')->where('id', $id_ingreso)->first();
        $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario)->first();
        $printer->text("CAJERO(A):" . $nombre_usuario['nombresusuario_sistema'] . "\n");
        $numero_ingreso = model('ingresoModel')->select('numero')->where('id', $id_ingreso)->first();
        $printer->text("COMPROBANTE DE INGRESO N°" . $numero_ingreso['numero'] . "\n");
        $printer->text("--------------------------------------------\n");
        $printer->text("\n");
        $nit_tercero = model('ingresoModel')->select('nitcliente')->where('id', $id_ingreso)->first();
        $nombre_tercero = model('clientesModel')->select('nombrescliente')->where('nitcliente', $nit_tercero['nitcliente'])->first();
        $printer->text("RECIBIDO DE :" . $nombre_tercero['nombrescliente'] . "\n");
        $printer->text("NIT O CC :" . number_format($nit_tercero['nitcliente'], 0, ",", ".") . "\n");

        $printer->text("\n");
        $printer->text("\n");
        $concepto_ingreso = model('ingresoModel')->select('concepto')->where('id', $id_ingreso)->first();
        $printer->text($concepto_ingreso['concepto'] . "\n");


        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $total = model('ingresoModel')->select('valor')->where('id', $id_ingreso)->first();
        $printer->text("TOTAL:     " . "$" . number_format($total['valor'], 0, ",", ".") . "\n");
        $efectivo = model('ingresoFormaPagoModel')->select('valor')->where('idingreso', $id_ingreso)->first();
        $printer->text("EFECTIVO:  " . "$" . number_format($efectivo['valor'], 0, ",", ".") . "\n");
        $saldo = model('ingresoModel')->select('saldo')->where('id', $id_ingreso)->first();

        $printer->text("SALDO:     " . "$" . number_format($saldo['saldo'], 0, ",", ".") . "\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("FIRMA      ----------------------------------- \n\n");
        $printer->text("C.C O NIT: ----------------------------------- \n\n");
        $printer->text("FECHA:     ----------------------------------- \n\n");

        $printer->feed(1);
        $printer->cut();

        $printer->close();

        $returnData = array(
            "resultado" => 1, //No hay pedido   
        );
        echo  json_encode($returnData);
    }

    function consulta_cartera()
    {
        return view('consultas_y_reportes/consulta_cartera');
    }
    function datos_consulta_cartera()
    {
        $nit = $this->request->getPost('nit_cliente');
        //$nit = '254654541';

        $consulta_cliente = model('facturaVentaModel')->consultar_cartera($nit);
        $saldo = model('facturaVentaModel')->consultar_saldo($nit);
        $valor_facturas = model('facturaVentaModel')->valor_facturas($nit);



        if (!empty($consulta_cliente)) {


            $returnData = array(
                "resultado" => 1,
                "datos" => view('consultas_y_reportes/datos_consulta_cartera', [
                    'datos' => $consulta_cliente,
                    'cartera' => "$" . number_format($saldo[0]['saldo_cartera'], 0, ",", "."),
                    'valor_facturas' => "$" . number_format($valor_facturas[0]['valor_facturas'], 0, ",", ".")
                ]),
                'cartera' => "CARTERA $" . number_format($saldo[0]['saldo_cartera'], 0, ",", "."),
                'valor_facturas' => "VALOR FACTURAS $" . number_format($valor_facturas[0]['valor_facturas'], 0, ",", "."),
                'pagos' => "PAGOS $" . number_format($valor_facturas[0]['valor_facturas'] - $saldo[0]['saldo_cartera'], 0, ",", "."),
            );
            echo  json_encode($returnData);
        } else if (empty($consulta_cliente)) {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        }
    }

    function consultar_por_documento()
    {

        $fecha_inicial = "";
        $fecha_final = "";

        /**
         * Fecha inicial 
         */
        if (!empty($this->request->getPost('fecha_inicial'))) {
            $fecha_inicial = $this->request->getPost('fecha_inicial');
        }
        if (empty($this->request->getPost('fecha_inicial'))) {
            $temp_fecha_inicial = model('facturaVentaModel')->selectMin('fecha_factura_venta')->first();
            $fecha_inicial = $temp_fecha_inicial['fecha_factura_venta'];
        }

        /**
         * Fecha final 
         */
        if (!empty($this->request->getPost('fecha_final'))) {
            $fecha_final = $this->request->getPost('fecha_final');
        }
        if (empty($this->request->getPost('fecha_final'))) {
            $fecha_final = date('Y-m-d');
        }

        if ($this->request->getPost('documento') != 5) {

            $datos = model('facturaVentaModel')->consultar_por_fecha($fecha_inicial, $fecha_final, $this->request->getPost('documento'));

            if (empty($datos)) {
                $returnData = array(
                    "resultado" => 0,

                );
                echo  json_encode($returnData);
            }
            if (!empty($datos)) {

                $returnData = array(
                    "resultado" => 1,

                );
                echo  json_encode($returnData);
            }
        }
        if ($this->request->getPost('documento') == 5) {

            $datos = model('facturaVentaModel')->todos_los_criterios($fecha_inicial, $fecha_final);

            if (empty($datos)) {
                $returnData = array(
                    "resultado" => 0,

                );
                echo  json_encode($returnData);
            }
            if (!empty($datos)) {

                $returnData = array(
                    "resultado" => 1,

                );
                echo  json_encode($returnData);
            }
        }
    }

    function consultar_por_cliente()
    {

        $fecha_inicial = "";
        $fecha_final = "";

        /**
         * Fecha inicial 
         */
        if (!empty($this->request->getPost('fecha_inicial'))) {
            $fecha_inicial = $this->request->getPost('fecha_inicial');
        }
        if (empty($this->request->getPost('fecha_inicial'))) {
            $temp_fecha_inicial = model('facturaVentaModel')->selectMin('fecha_factura_venta')->first();
            $fecha_inicial = $temp_fecha_inicial['fecha_factura_venta'];
        }

        /**
         * Fecha final 
         */
        if (!empty($this->request->getPost('fecha_final'))) {
            $fecha_final = $this->request->getPost('fecha_final');
        }
        if (empty($this->request->getPost('fecha_final'))) {
            $fecha_final = date('Y-m-d');
        }

        $datos = model('facturaVentaModel')->consultar_por_fecha($fecha_inicial, $fecha_final, $this->request->getPost('documento'), $this->request->getPost('cliente'));

        if (empty($datos)) {
            $returnData = array(
                "resultado" => 0,

            );
            echo  json_encode($returnData);
        }
        if (!empty($datos)) {

            $returnData = array(
                "resultado" => 1,

            );
            echo  json_encode($returnData);
        }
    }

    function consulta_de_cartera()
    {

        $clientes = model('facturaVentaModel')->cartera_cliente();

        //$datos_cartera = array();


        /* dd($datos_cartera); */
        // exit();

        if (!empty($clientes)) {
            $returnData = array(
                "resultado" => 1,
                "cartera" => view('cartera/cartera_clientes', [
                    //"datos_cartera" => $datos_cartera,
                    "clientes" => $clientes
                ])

            );
            echo  json_encode($returnData);
        }
        if (empty($clientes)) {
            $returnData = array(
                "resultado" => 0,

            );
            echo  json_encode($returnData);
        }
    }


    /*  function aperturas()
    {

        //$valor_buscado = $_POST['search']['value'];;
        $valor_buscado = '';

        $table_map = [
            0 => 'id',
            1 => 'fecha'
        ];

        $sql_count = "SELECT count(id) as total from apertura";

        $sql_data = "SELECT id,fecha FROM apertura  ";
        $condition = "";


        if (!empty($valor_buscado)) {

            $condition .= "'%" . $valor_buscado . "%'";
        }

        $sql_count = $sql_count . $condition;
        $sql_data = $sql_data . $condition;

        $total_count = $this->db->query($sql_count)->getRow();

        $sql_data .= " ORDER BY " . $table_map[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " " . "LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];

        $datos = $this->db->query($sql_data)->getResultArray();

        foreach ($datos as $detalle) {

            $sub_array = array();

            $fecha_cierre = model('cierreModel')->select('fecha')->where('idapertura', $detalle['id'])->first();
            if (!empty($fecha_cierre)) {

                // $sub_array[] = $detalle['fecha'];
                $sub_array[] =  '<p onclick="detalle_apertura(' . $detalle['id'] . ')" class="cursor-pointer" >' . $detalle['fecha'] . ''; //Fecha apertura
                $sub_array[] =  '<p onclick="detalle_apertura(' . $detalle['id'] . ')" class="cursor-pointer" >' . $fecha_cierre['fecha'] . '';//Fecha cierre

                $data[] = $sub_array;
            }
            if (empty($fecha_cierre)) {

                $sub_array[] = '<p onclick="detalle_apertura(' . $detalle['id'] . ')" class="cursor-pointer" >' . $detalle['fecha'] . '';
                $sub_array[] = '<p onclick="detalle_apertura(' . $detalle['id'] . ')" class="cursor-pointer" > Sin cierre';

                $data[] = $sub_array;
            }
        }


        $json_data = [
            'draw' => intval($this->request->getPost(index: 'draw')),
            'recordsTotal' => $total_count->total,
            'recordsFiltered' => $total_count->total,
            'data' => $data,

        ];
        echo  json_encode($json_data);
    } */

    function aperturas()
    {

        $id_apertura = model('aperturaModel')->apertura();

        foreach ($id_apertura as $detalle) { // Recorro todas las aperturas 

            $sub_array = array();

            $fecha_cierre = model('cierreModel')->select('fecha')->where('idapertura', $detalle['id'])->first();

            if (!empty($fecha_cierre['fecha'])) {    // Validamos si hay fecha de cierre 
                $hora_cierre = model('cierreModel')->select('hora')->where('idapertura', $detalle['id'])->first();
                // $sub_array[] = $detalle['fecha'];
                $sub_array[] =  $detalle['id']; //id apertura             0
                $sub_array[] =  $detalle['fecha']; //Fecha apertura       1     
                $sub_array[] =  $detalle['hora']; //Hora apertura         2     
                $sub_array[] =   $fecha_cierre['fecha']; //Fecha cierre   3
                $sub_array[] =   $hora_cierre['hora']; //hora  cierre     4

                $data[] = $sub_array;
            }
            if (empty($fecha_cierre['fecha'])) {

                $sub_array[] =  $detalle['id'];  //id apertura             0
                $sub_array[] =  $detalle['fecha']; //Fecha apertura        1
                $sub_array[] =  $detalle['hora']; //hora apertura          2
                $sub_array[] = 'Sin cierre'; //Fecha cierre                3
                $sub_array[] =  ''; //hora cierre                4

                $data[] = $sub_array;
            }
        }

        $returnData = array(
            "resultado" => 1,
            "aperturas" => view('caja/historial_aperturas', [
                'datos' => $data
            ])

        );
        echo  json_encode($returnData);
    }

    function ventas_de_mesero()
    {
        //$ventas=model('pagosModel')->get_ventas_mesero(date('Y-m-d'));
        $id_meseros = model('pagosModel')->get_id_mesero(date('Y-m-d'));
        $meseros = model('usuariosModel')->get_usuarios();

        return view('consultas/ventas_de_mesero', [
            'id_meseros' => $id_meseros,
            'meseros' => $meseros
        ]);
    }
}
