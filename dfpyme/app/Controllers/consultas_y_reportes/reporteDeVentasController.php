<?php

namespace App\Controllers\consultas_y_reportes;

require APPPATH . "Controllers/mike42/autoload.php";

use App\Controllers\BaseController;
use \DateTime;
use \DateTimeZone;
use Dompdf\Dompdf;
use Dompdf\Options;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;



class reporteDeVentasController extends BaseController
{

    public $db;
    public function __construct()
    {
        //require_once APPPATH . 'ThirdParty/ssp/ssp.php';
        //$this->db = db_connect();

        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $estado_facturas = model('estadoModel')->find();

        return view('consultas_y_reportes/reporte_de_ventas', [
            'estado' => $estado_facturas,
        ]);
    }
    public function producto()
    {
        return view('producto/consultar');
    }


    function consulta_de_ventas()
    {
        $valor_buscado = $_GET['search']['value'];
        // $valor_buscado = 'CLI';

        $tipo_documento = $_REQUEST['tipo_documento'];
        $fecha_ini = $_REQUEST['fecha_inicial'];

        $sql_count = '';
        $sql_data = '';


        $fecha_inicial = '';

        if (empty($fecha_ini) or $fecha_ini == '____-__-__') {
            $temp_fecha_inicial = model('facturaVentaModel')->selectMin('fecha_factura_venta')->first();
            $fecha_inicial = $temp_fecha_inicial['fecha_factura_venta'];
        } else if (!empty($fecha_ini)) {
            $fecha_inicial = $fecha_ini;
        }


        $fecha_fin = $_REQUEST['fecha_final'];
        $fecha_final = '';
        if (empty($fecha_fin) or $fecha_fin == '____-__-__') {
            $temp_fecha_final = model('facturaVentaModel')->selectMax('fecha_factura_venta')->first();
            $fecha_final = $temp_fecha_final['fecha_factura_venta'];
        } else if (!empty($fecha_fin)) {
            $fecha_final = $fecha_fin;
        }

        $cliente_formulario = $_REQUEST['cliente'];


        $table_map = [
            0 => 'id',
            1 => 'nitcliente',
            2 => 'nombrescliente',
            3 => 'descripcionestado',
            4 => 'fecha_factura_venta',
            5 => 'horafactura_venta',
            6 => 'fechalimitefactura_venta',
        ];
        if ($tipo_documento != 5) {

            if (empty($cliente_formulario)) {

                $temp_sql_count = "SELECT
                        COUNT(factura_venta.id) AS total
                    FROM
                        factura_venta
                    INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
                    INNER JOIN estado ON factura_venta.idestado = estado.idestado WHERE factura_venta.idestado=$tipo_documento AND fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' ";

                $temp_sql_sum = "SELECT
                        SUM(factura_venta.saldo) AS saldo
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
                            valor_factura
                        FROM
                            factura_venta
                        INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
                        INNER JOIN estado ON factura_venta.idestado = estado.idestado WHERE factura_venta.idestado=$tipo_documento AND fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' ";

                $condition = "";

                if (!empty($valor_buscado)) {

                    $condition .= " AND cliente.nitcliente ILIKE '%" . $valor_buscado . "%'";
                    $condition .= " OR descripcionestado ILIKE '%" . $valor_buscado . "%'";
                    $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";
                    $condition .= " OR factura_venta.nitcliente ILIKE '%" . $valor_buscado . "%'";
                    $condition .= " OR numerofactura_venta ILIKE '%" . $valor_buscado . "%'";
                    // $condition .= " OR fecha_factura_venta BETWEEN " . "'$valor_buscado'" . " AND " . "'$valor_buscado'";
                }
            }

            if (!empty($cliente_formulario)) {

                $temp_sql_count = "SELECT
                        COUNT(factura_venta.id) AS total
                    FROM
                        factura_venta
                    INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
                    INNER JOIN estado ON factura_venta.idestado = estado.idestado WHERE factura_venta.idestado=$tipo_documento AND fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' and  cliente.nitcliente='$cliente_formulario'  ";

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
                            valor_factura
                        FROM
                            factura_venta
                        INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
                        INNER JOIN estado ON factura_venta.idestado = estado.idestado WHERE factura_venta.idestado=$tipo_documento AND fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' and  cliente.nitcliente='$cliente_formulario' ";

                $condition = "";

                if (!empty($valor_buscado)) {
                    $condition .= " AND descripcionestado ILIKE '%" . $valor_buscado . "%'";
                    $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";
                    $condition .= " OR factura_venta.nitcliente ILIKE '%" . $valor_buscado . "%'";
                    $condition .= " OR numerofactura_venta ILIKE '%" . $valor_buscado . "%'";
                    // $condition .= " OR fecha_factura_venta BETWEEN " . "'$valor_buscado'" . " AND " . "'$valor_buscado'";
                }
            }

            $sql_count = $temp_sql_count;
            $sql_data = $temp_sql_data;
            $sql_sum = $temp_sql_sum;
            $sql_count = $sql_count . $condition;
            $sql_data = $sql_data . $condition;
            $sql_sum = $sql_sum . $condition;
            //dd($sql_data);
            $saldo = $this->db->query($sql_sum)->getRow();
            $total_count = $this->db->query($sql_count)->getRow();

            $sql_data .= " ORDER BY " . $table_map[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " " . "LIMIT " . $_GET['length'] . " OFFSET " . $_GET['start'];

            $datos = $this->db->query($sql_data)->getResultArray();

            if (!empty($datos) && !empty($total_count)) {

                foreach ($datos as $detalle) {
                    $sub_array = array();
                    $sub_array[] = number_format($detalle['nitcliente'], 0, ",", ".");
                    $sub_array[] = $detalle['nombrescliente'];
                    $sub_array[] = $detalle['descripcionestado'];
                    $sub_array[] = $detalle['numerofactura_venta'];
                    $sub_array[] = $detalle['fecha_factura_venta'];
                    $sub_array[] = date("g:i a", strtotime($detalle['horafactura_venta']));
                    $sub_array[] = $detalle['fechalimitefactura_venta'];
                    $sub_array[] = "$" . number_format($detalle['valor_factura'], 0, ",", ".");
                    $sub_array[] = '<a  class="btn btn-secondary btn-icon "  onclick="detalle_de_factura(' . $detalle['id'] . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg></a>   <a onclick="abono_credito(' . $detalle['id'] . ')"  class="btn btn-primary  btn-icon" ><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" /><path d="M12 3v3m0 12v3" /></svg> </a> ';
                    $data[] = $sub_array;
                }

                $json_data = [
                    //'draw' => intval($this->request->getGEt(index: 'draw')),
                    'draw' => intval($this->request->getGEt(index: 'draw')),
                    'recordsTotal' => $total_count->total,
                    'recordsFiltered' => $total_count->total,
                    'data' => $data,
                    'saldo' => 'SALDO CREDITO:  ' . number_format(($saldo->saldo), 0, ",", ".")

                ];

                echo  json_encode($json_data);
            } else {
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
            }
        } else if ($tipo_documento == 5) {

            $sql_count = "SELECT
                        COUNT(factura_venta.id) AS total
                        FROM
                    factura_venta
                        INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
                        INNER JOIN estado ON factura_venta.idestado = estado.idestado WHERE fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final' ";

            $sql_data = " SELECT
            factura_venta.id,
            factura_venta.nitcliente,
            cliente.nombrescliente,
            descripcionestado,
            fecha_factura_venta,
            horafactura_venta,
            fechalimitefactura_venta,
            numerofactura_venta,
            fechalimitefactura_venta,
            valor_factura
        FROM
            factura_venta
        INNER JOIN cliente ON factura_venta.nitcliente = cliente.nitcliente
        INNER JOIN estado ON factura_venta.idestado = estado.idestado WHERE fecha_factura_venta BETWEEN '$fecha_inicial' AND '$fecha_final'";
            $condition = "";

            if (!empty($valor_buscado)) {

                $condition .= " AND cliente.nitcliente ILIKE '%" . $valor_buscado . "%'";
                $condition .= " OR descripcionestado ILIKE '%" . $valor_buscado . "%'";
                $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";
                $condition .= " OR factura_venta.nitcliente ILIKE '%" . $valor_buscado . "%'";
                $condition .= " OR numerofactura_venta ILIKE '%" . $valor_buscado . "%'";
                // $condition .= " OR fecha_factura_venta BETWEEN " . "'$valor_buscado'" . " AND " . "'$valor_buscado'";
            }

            $sql_count = $sql_count . $condition;
            $sql_data = $sql_data . $condition;
            //dd($sql_data);
            $total_count = $this->db->query($sql_count)->getRow();

            $sql_data .= " ORDER BY " . $table_map[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " " . "LIMIT " . $_GET['length'] . " OFFSET " . $_GET['start'];

            $datos = $this->db->query($sql_data)->getResultArray();
            // dd($datos);
            if (!empty($datos) && !empty($total_count)) {

                foreach ($datos as $detalle) {
                    $sub_array = array();
                    $sub_array[] = number_format($detalle['nitcliente'], 0, ",", ".");
                    $sub_array[] = $detalle['nombrescliente'];
                    $sub_array[] = $detalle['descripcionestado'];
                    $sub_array[] = $detalle['numerofactura_venta'];
                    $sub_array[] = $detalle['fecha_factura_venta'];
                    $sub_array[] = date("g:i a", strtotime($detalle['horafactura_venta']));
                    $sub_array[] = $detalle['fechalimitefactura_venta'];
                    $sub_array[] = "$" . number_format($detalle['valor_factura'], 0, ",", ".");
                    $sub_array[] = '<a  class="btn btn-secondary btn-icon "  onclick="detalle_de_factura(' . $detalle['id'] . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg></a>   <a  onclick="abono_credito(' . $detalle['id'] . ')"  class="btn btn-primary  btn-icon" ><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" /><path d="M12 3v3m0 12v3" /></svg> </a> ';
                    $data[] = $sub_array;
                }

                $json_data = [
                    //'draw' => intval($this->request->getGEt(index: 'draw')),
                    'draw' => intval($this->request->getGEt(index: 'draw')),
                    'recordsTotal' => $total_count->total,
                    'recordsFiltered' => $total_count->total,
                    'data' => $data,

                ];
                echo  json_encode($json_data);
            } else {
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
            }
        }
    }


    function consultas_caja()
    {
        $caja = model('cajaModel')->find();
        return view('consultas_y_reportes/consultas_caja', [
            'caja' => $caja
        ]);
    }

    function datos_consultas_caja_por_fecha()
    {
        /* if (
            !$this->validate([
                'numero_caja' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Dato necesario',
                    ],
                ],
            ])
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        } */


        $ultimo_apertura = model('aperturaModel')->selectMax('id')->first();
        $ultimo_id = $ultimo_apertura['id'];




        $estado = "";
        $movimientos = "";
        $fecha_cierre = "";
        $ingresos_efectivo = "";
        $ingresos_transaccion = "";
        $efectivo_cierre = "";
        $transaccion_cierre = "";
        $devoluciones = "";
        $retiros = "";
        $saldo = "";
        $propinas = "";

        $tiene_cierre = model('cierreModel')->select('fecha')->where('idapertura', $ultimo_id)->first();
        $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $ultimo_id)->first();
        $valor_apertura = model('aperturaModel')->select('valor')->where('id', $ultimo_id)->first();
        $fecha_y_hora_apertura = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $ultimo_id)->first();

        $aperturas = model('aperturaModel')->findAll();



        if (!empty($aperturas)) {



            if (empty($tiene_cierre)) {
                $estado = "ABIERTA";
                $cierre = 'Sin cierre';



                $ingresos = model('pagosModel')->selectSum('efectivo')->where('id_apertura', $ultimo_apertura['id'])->findAll();
                $ingresos_efectivo = $ingresos[0]['efectivo'];

                $temp_propinas = model('pagosModel')->selectSum('propina')->where('id_apertura', $ultimo_apertura['id'])->findAll();
                $propinas = $temp_propinas[0]['propina'];


                $transaccion = model('pagosModel')->selectSum('transferencia')->where('id_apertura', $ultimo_apertura['id'])->findAll();
                $ingresos_transaccion = $transaccion[0]['transferencia'];


                $valor_cierre = 0;
                $devolucion_venta = model('detalleDevolucionVentaModel')->selectSum('valor_total_producto')->where('id_apertura', $ultimo_apertura['id'])->findAll();

                if (empty($devolucion_venta)) {
                    $devoluciones = 0;
                } else if (!empty($devolucion_venta)) {
                    $devoluciones = $devolucion_venta[0]['valor_total_producto'];
                }
                //$total_retiros = model('retiroFormaPagoModel')->total_retiros($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d H:i:s'));
                $total_retiros = model('retiroFormaPagoModel')->selectSum('valor')->where('id_apertura', $ultimo_apertura['id'])->findAll();


                if (empty($total_retiros[0]['valor'])) {
                    $retiros = 0;
                }
                if (!empty($total_retiros[0]['valor'])) {
                    $retiros = $total_retiros[0]['valor'];
                }

                $efectivo_cierre = 0;
                $transaccion_cierre = 0;
                $saldo = 0;

                //$diferencia = ($efectivo_cierre + $transaccion_cierre) - (($ingresos_transaccion + $ingresos_efectivo + $valor_apertura['valor']) - ($retiros + $devoluciones));
                //$diferencia =  (($ingresos_transaccion + $ingresos_efectivo + $valor_apertura['valor']) - ($retiros + $devoluciones)) - ($efectivo_cierre + $transaccion_cierre);
                $diferencia =  - (($ingresos_transaccion + $ingresos_efectivo + $valor_apertura['valor']) - ($retiros + $devoluciones));
            }
            if (!empty($tiene_cierre)) {

                $estado = 'CERRADA';
                $fecha_cierre = model('cierreModel')->select('fecha')->where('idapertura', $ultimo_id)->first();
                $cierre = $fecha_cierre['fecha'];

                $fecha_y_hora_cierre = model('cierreModel')->select('fecha_y_hora_cierre')->where('idapertura', $ultimo_id)->first();
                $efectivo = model('pagosModel')->selectSum('efectivo')->where('id_apertura', $ultimo_id)->findAll();
                if (empty($efectivo)) {
                    $ingresos_efectivo = 0;
                } else if (!empty($efectivo)) {
                    $ingresos_efectivo = $efectivo[0]['efectivo'];
                }

                $transaccion = model('pagosModel')->selectSum('transferencia')->where('id_apertura', $ultimo_id)->findAll();

                if (empty($transaccion)) {
                    $ingresos_transaccion = 0;
                } else if (!empty($transaccion)) {
                    $ingresos_transaccion = $transaccion[0]['transferencia'];
                }
                $valor_cierre = 0;
                $devolucion_venta = model('devolucionModel')->sumar_devoluciones($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);


                if (empty($devolucion_venta)) {
                    $devoluciones = 0;
                } else if (!empty($devolucion_venta)) {
                    $devoluciones = $devolucion_venta[0]['total_devoluciones'];
                }
                $id_cierre = model('cierreModel')->select('id')->where('idapertura', $ultimo_id)->first();
                $efectivo = model('cierreFormaPagoModel')->cierre_efectivo($id_cierre['id']);
                $transaccion = model('cierreFormaPagoModel')->cierre_transaccion($id_cierre['id']);

                if (!empty($efectivo)) {
                    $efectivo_cierre = $efectivo[0]['valor'];
                }
                if (empty($efectivo)) {
                    $efectivo_cierre = 0;
                }
                if (empty($transaccion)) {
                    $transaccion_cierre = 0;
                }
                if (!empty($transaccion)) {
                    $transaccion_cierre = $transaccion[0]['valor'];
                }

                $total_retiros = model('retiroFormaPagoModel')->total_retiros($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);

                if (empty($total_retiros[0]['total_retiros'])) {
                    $retiros = 0;
                }
                if (!empty($total_retiros[0]['total_retiros'])) {
                    $retiros = $total_retiros[0]['total_retiros'];
                }


                $temp_propinas = model('pagosModel')->selectSum('propina')->where('id_apertura', $ultimo_id)->findAll();
                $propinas = $temp_propinas[0]['propina'];

                $total_ingresos = $ingresos_transaccion + $ingresos_efectivo + $valor_apertura['valor'];
                $retiros_dinero = $retiros + $devoluciones;
                $cierre_usuario = $efectivo_cierre + $transaccion_cierre;


              /*   if ($cierre_usuario >= $total_ingresos) {

                    $diferencia =  (($total_ingresos) - ($retiros_dinero)) - ($cierre_usuario);
                }
                if ($cierre_usuario < $total_ingresos) {

                    $diferencia =  ($total_ingresos -($retiros_dinero)) - $cierre_usuario;
                } */

               $diferencia =  $cierre_usuario - ($total_ingresos - $retiros_dinero );
                
            }



            return view('consultas_y_reportes/datos_consultas_caja', [
                'estado' => $estado,
                'fecha_apertura' => $fecha_apertura['fecha'],
                'fecha_cierre' => $cierre,
                'valor_apertura' => "$" . number_format($valor_apertura['valor'], 0, ",", "."),
                'ingresos_efectivo' =>  "$" . number_format(($ingresos_efectivo + $ingresos_transaccion) - $propinas, 0, ",", "."),
                'ingresos_transaccion' =>  "$" . number_format($ingresos_transaccion, 0, ",", "."),
                'total_ingresos' =>  "$" . number_format(($ingresos_transaccion + $ingresos_efectivo) + $valor_apertura['valor'], 0, ",", "."),
                'efectivo_cierre' => "$" . number_format($efectivo_cierre, 0, ",", "."),
                'transaccion_cierre' => "$" . number_format($transaccion_cierre, 0, ",", "."),
                'total_cierre' => "$" . number_format($efectivo_cierre + $transaccion_cierre, 0, ",", "."),
                'devoluciones' => "$" . number_format($devoluciones, 0, ",", "."),
                'retiros' => "$" . number_format($retiros, 0, ",", "."),
                'propinas' => "$" . number_format($propinas, 0, ",", "."),
                'retirosmasdevoluciones' => "$" . number_format($retiros + $devoluciones, 0, ",", "."),
                'saldo_caja' => "$" . number_format(($valor_apertura['valor'] + $ingresos_efectivo + $ingresos_transaccion) - ($retiros + $devoluciones), 0, ",", "."),
                'diferencia' => "$" . number_format($diferencia, 0, ",", "."),
                'id_apertura' => $ultimo_id,
            ]);
        } else if (empty($aperturas)) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('consultas_y_reportes/consultas_caja'))->with('mensaje', 'No hay registros disponibles para consultar  ');
        }
    }
    function consultas_caja_por_fecha()
    {

        $movimientos_caja = model('aperturaModel')->movimiento_caja(
            $_REQUEST['fecha_inicial_caja'],
            $_REQUEST['fecha_final_caja']
        );

        if ($_REQUEST['fecha_inicial_caja'] <= $_REQUEST['fecha_final_caja']) {
            if (empty($movimientos_caja)) {
                $returnData = [
                    'resultado' => 0, //No hay resultados
                ];
                echo json_encode($returnData);
            }
            if (!empty($movimientos_caja)) {
                $returnData = [
                    'resultado' => 2, //Hay numero de pedido
                ];
                echo json_encode($returnData);
            }
        } elseif (
            $_REQUEST['fecha_inicial_caja'] > $_REQUEST['fecha_final_caja']
        ) {
            $returnData = [
                'resultado' => 1, //No hay resultados
            ];
            echo json_encode($returnData);
        }
    }

    function detalle_de_ventas()
    {
        /*Datos de la apertura*/
        $fecha_inicial = $_REQUEST['fecha_inicial'];
        $fecha_final = $_REQUEST['fecha_final'];
        $id_apertura = $_REQUEST['id_apertura'];
        $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
        $hora_apertura = model('aperturaModel')->select('hora')->where('id', $id_apertura)->first();
        $valor_apertura = model('aperturaModel')->select('valor')->where('id', $id_apertura)->first();
        /*Datos de la apertura*/

        /*Datos de la apertura*/
        $fecha_inicial = $_REQUEST['fecha_inicial'];
        $fecha_final = $_REQUEST['fecha_final'];
        $id_apertura = $_REQUEST['id_apertura'];
        $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
        $hora_apertura = model('aperturaModel')->select('hora')->where('id', $id_apertura)->first();
        $valor_apertura = model('aperturaModel')->select('valor')->where('id', $id_apertura)->first();
        /*Datos de la apertura*/

        /*Datos de la apertura*/
        $fecha_inicial = $_REQUEST['fecha_inicial'];
        $fecha_final = $_REQUEST['fecha_final'];
        $id_apertura = $_REQUEST['id_apertura'];
        $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
        $hora_apertura = model('aperturaModel')->select('hora')->where('id', $id_apertura)->first();
        $valor_apertura = model('aperturaModel')->select('valor')->where('id', $id_apertura)->first();
        /*Datos de la apertura*/


        /*Datos del cierre*/
        $fecha_cierre = model('cierreModel')->select('fecha')->where('idapertura', $id_apertura)->first();
        $hora_cierre = model('cierreModel')->select('hora')->where('idapertura', $id_apertura)->first();
        $id_cierre = model('cierreModel')->select('id')->where('idapertura', $id_apertura)->first();
        $valor_cierre_efectivo_usuario = model('cierreFormaPagoModel')->valor_cierre_efectivo_usuario($id_cierre['id']);
        $valor_close_transaccion_usuario = model('cierreFormaPagoModel')->valor_cierre_transaccion_usuario($id_cierre['id']);

        if (empty($valor_close_transaccion_usuario[0]['valor'])) {
            $valor_cierre_transaccion_usuario = 0;
        } else {
            $valor_cierre_transaccion_usuario = $valor_close_transaccion_usuario[0]['valor'];
        }

        /*Datos del cierre*/


        /*Movimientos de facturacion de sistema*/
        $movimientos = model('facturaVentaModel')->detalle_de_ventas($fecha_inicial, $fecha_final);
        $ingresos_efectivo = model('facturaFormaPagoModel')->ingresos_efectivo($fecha_inicial, $fecha_final);
        $ingresos_transaccion = model('facturaFormaPagoModel')->ingresos_transaccion($fecha_inicial, $fecha_final);
        $total_ingresos = model('facturaFormaPagoModel')->total_ingresos($fecha_inicial, $fecha_final);
        $devolucion_venta = model('devolucionModel')->devoluciones($fecha_inicial, $fecha_final);
        $temp_devoluciones = 0;
        $total_devoluciones = 0;

        foreach ($devolucion_venta as $detalle) {
            $codigo_interno_producto = model('detalleDevolucionVentaModel')->select('codigo')->where('id_devolucion_venta', $detalle['id'])->first();
            $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigo'])->first();
            $valor = model('devolucionVentaEfectivoModel')->select('valor')->where('iddevolucion', $detalle['id'])->first();
            $temp_devoluciones = $temp_devoluciones + $valor['valor'];
            $total_devoluciones = $temp_devoluciones;
        }
        $retiros = model('retiroModel')->select('*')->where('id_apertura', $id_apertura)->findAll();
        /*Movimientos de facturacion de sistema*/


        /**
         * CIERRE DE CAJA INICIO 
         */


        $temp_retiros = 0;
        $total_retiros = 0;

        if (!empty($retiros)) {
            foreach ($retiros as $detalle_retiro) {
                $valor = model('retiroFormaPagoModel')->select('valor')->where('idretiro', $detalle_retiro['id'])->first();
                $temp_retiros = $temp_retiros + $valor['valor'];
                $total_retiros = $temp_retiros;
            }
        }

        $ingresos_efectivo_mas_valor_apertura = $ingresos_efectivo[0]['ingresos_efectivo'] + $valor_apertura['valor'];
        $retiros_devoluciones = $total_devoluciones + $total_retiros;

        $efectivo_caja = $ingresos_efectivo_mas_valor_apertura - $retiros_devoluciones;

        $diferencia_efectivo = $valor_cierre_efectivo_usuario[0]['valor'] - $efectivo_caja;

        $diferencia_transacciones = $valor_cierre_transaccion_usuario - $ingresos_transaccion[0]['ingresos_transaccion'];

        $saldo = $diferencia_efectivo + $diferencia_transacciones;

        /**
         * CIERRE DE CAJA FIN 
         */


        $detalle_de_ventas = view('caja/detalle_de_ventas', [
            'movimientos' => $movimientos,
            'devoluciones' => $devolucion_venta,
            'fecha_apertura' => $fecha_apertura['fecha'] . " " . date("g:i a", strtotime($hora_apertura['hora'])),
            'id_apertura' => $id_apertura,
            'valor_apertura' => $valor_apertura['valor'],
            'fecha_cierre' => $fecha_cierre['fecha'] . " " . $hora_cierre['hora'],
            'ingresos_en_efectivo' => "$" . number_format($ingresos_efectivo[0]['ingresos_efectivo'], 0, ",", "."),
            'ingresos_por_transaccion' => "$" . number_format($ingresos_transaccion[0]['ingresos_transaccion'], 0, ",", "."),
            'total_ingresos' => "$" . number_format($total_ingresos[0]['total_ingresos'], 0, ",", "."),
            'total_devoluciones' => "$" . number_format($total_devoluciones, 0, ",", "."),
            'retiros' => $retiros,
            'valor_cierre_efectivo_usuario' => "$" . number_format($valor_cierre_efectivo_usuario[0]['valor'], 0, ",", "."),
            'valor_cierre_efectivo_usuario_modal' => number_format($valor_cierre_efectivo_usuario[0]['valor'], 0, ",", "."),
            'valor_cierre_transaccion_usuario' => "$" . number_format($valor_cierre_transaccion_usuario, 0, ",", "."),
            'saldo' => $saldo,
            'total_ingresos_usuario' => "$" . number_format(($valor_cierre_efectivo_usuario[0]['valor'] + $valor_cierre_transaccion_usuario), 0, ",", "."),
            'total_retiros' => $total_retiros,
            'retiros_mas_devoluciones' => "$" . number_format(($total_devoluciones + $total_retiros), 0, ",", "."),
            //'subtotal'=>($ingresos_efectivo[0]['ingresos_efectivo']+$ingresos_transaccion[0]['ingresos_transaccion'])-($total_devoluciones+$total_retiros)
            'subtotal' => ($valor_apertura['valor'] + ($ingresos_efectivo[0]['ingresos_efectivo'] + $ingresos_transaccion[0]['ingresos_transaccion'])) - ($total_devoluciones + $total_retiros)
        ]);


        $returnData = [
            'resultado' => 1, //No hay resultados
            'detalle_de_ventas' => $detalle_de_ventas,
        ];
        echo json_encode($returnData);
    }
    function detalle_de_ventas_sin_cierre()
    {

        $fecha_apertura = $_REQUEST['fecha_y_hora_apertura'];
        $id_apertura = $_REQUEST['id_apertura'];

        $valor_apertura = model('aperturaModel')->select('valor')->where('id', $id_apertura)->first();
        $hora_apertura = model('aperturaModel')->select('hora')->where('id', $id_apertura)->first();

        $fecha = DateTime::createFromFormat('U.u', microtime(TRUE));
        $fecha->setTimeZone(new DateTimeZone('America/Bogota'));
        $fecha_y_hora_actual = $fecha->format('Y-m-d H:i:s.u');
        $movimientos = model('facturaVentaModel')->detalle_de_ventas_sin_cierre($fecha_apertura, $fecha_y_hora_actual);

        $devolucion_venta = model('devolucionModel')->devoluciones($fecha_apertura, $fecha_y_hora_actual);

        $fecha_de_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();

        $ingresos_efectivo = model('facturaFormaPagoModel')->ingresos_efectivo($fecha_apertura, $fecha_y_hora_actual);
        $ingresos_transaccion = model('facturaFormaPagoModel')->ingresos_transaccion($fecha_apertura, $fecha_y_hora_actual);

        $total_ingresos = model('facturaFormaPagoModel')->total_ingresos($fecha_apertura, $fecha_y_hora_actual);

        $devolucion_venta = model('devolucionModel')->devoluciones($fecha_apertura, $fecha_y_hora_actual);

        $temp_devoluciones = 0;
        $total_devoluciones = 0;


        foreach ($devolucion_venta as $detalle) {

            // $printer->text("FECHA:" . $detalle['fecha'] . " " . date("g:i a", strtotime($detalle['hora'])) . "\n");
            $codigo_interno_producto = model('detalleDevolucionVentaModel')->select('codigo')->where('id_devolucion_venta', $detalle['id'])->first();

            $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigo'])->first();

            $valor = model('devolucionVentaEfectivoModel')->select('valor')->where('iddevolucion', $detalle['id'])->first();

            $temp_devoluciones = $temp_devoluciones + $valor['valor'];
            //exit('holla');
            $total_devoluciones = $temp_devoluciones;
        }

        $retiros = model('retiroModel')->select('*')->where('id_apertura', $id_apertura)->findAll();

        $temp_retiros = 0;
        $total_retiros = 0;

        if (!empty($retiros)) {
            foreach ($retiros as $detalle_retiro) {
                $valor = model('retiroFormaPagoModel')->select('valor')->where('idretiro', $detalle_retiro['id'])->first();
                $temp_retiros = $temp_retiros + $valor['valor'];
                $total_retiros = $temp_retiros;
            }
        }


        // $diferencia_efectivo = 0 - $ingresos_efectivo[0]['ingresos_efectivo'];
        //$diferencia_transacciones = 0 - $ingresos_transaccion[0]['ingresos_transaccion'];

        // $temp_saldo = $diferencia_efectivo + $diferencia_transacciones;
        //$temp_saldo_2=$temp_saldo-($total_devoluciones + $total_retiros);
        //$saldo=$valor_apertura['valor']-
        $saldo = ($total_devoluciones + $total_retiros) - ($valor_apertura['valor'] + $ingresos_efectivo[0]['ingresos_efectivo'] + $ingresos_transaccion[0]['ingresos_transaccion']);



        $detalle_de_ventas_sin_cierre = view('caja/detalle_de_ventas_sin_cierre', [
            'movimientos' => $movimientos,
            'devoluciones' => $devolucion_venta,
            'fecha_apertura' => $fecha_de_apertura['fecha'] . " " . date("g:i a", strtotime($hora_apertura['hora'])),
            'ingresos_en_efectivo' => "$" . number_format($ingresos_efectivo[0]['ingresos_efectivo'], 0, ",", "."),
            'ingresos_por_transaccion' => "$" . number_format($ingresos_transaccion[0]['ingresos_transaccion'], 0, ",", "."),
            'total_ingresos' => "$" . number_format($total_ingresos[0]['total_ingresos'], 0, ",", "."),
            'total_devoluciones' => "$" . number_format($total_devoluciones, 0, ",", "."),
            'retiros' => $retiros,
            'valor_apertura' => $valor_apertura['valor'],
            'id_apertura' => $id_apertura,
            'total_retiros' => $total_retiros,
            'retiros_mas_devoluciones' => "$" . number_format(($total_devoluciones + $total_retiros), 0, ",", "."),
            'subtotal' => ($valor_apertura['valor'] + ($ingresos_efectivo[0]['ingresos_efectivo'] + $ingresos_transaccion[0]['ingresos_transaccion'])) - ($total_devoluciones + $total_retiros),
            'saldo' => "$" . number_format($saldo, 0, ",", ".")

        ]);


        $returnData = [
            'resultado' => 1, //No hay resultados
            'detalle_de_ventas_sin_cierre' => $detalle_de_ventas_sin_cierre,
        ];
        echo json_encode($returnData);
    }

    function datos_consultar_producto()
    {

        $fecha_inicial = $_REQUEST['fecha_inicial'];
        $fecha_final = $_REQUEST['fecha_final'];

        $datos_producto = model('productoFacturaVentaModel')->datos_producto($fecha_inicial, $fecha_final);

        if ($datos_producto) {
            $returnData = [
                'resultado' => 1, //No hay resultados
                'fecha_inicial' => $fecha_inicial,

                'fecha_final' => $fecha_final,

            ];
            echo json_encode($returnData);
        } else {
            $returnData = [
                'resultado' => 0, //No hay resultados
            ];
            echo json_encode($returnData);
        }
    }

    function tabla_consultar_producto()
    {

        $fecha_inicial = $_REQUEST['fecha_inicial'];

        $fecha_final = $_REQUEST['fecha_final'];

        $datos_producto = model('productoFacturaVentaModel')->datos_consulta_producto($fecha_inicial, $fecha_final);
        $total_datos_producto = model('productoFacturaVentaModel')->total_datos_consulta_producto($fecha_inicial, $fecha_final);



        if ($datos_producto) {
            return view('producto/datos_consulta', [
                'fecha_inicial' => $fecha_inicial,
                'hora_inicial' => $_REQUEST['hora_inicial'],
                'fecha_final' => $fecha_final,
                'hora_final' => $_REQUEST['hora_final'],
                'datos_producto' => $datos_producto,
                'total' => $total_datos_producto[0]['total']
            ]);
        }
    }

    function producto_agrupados()
    {
        return view('producto/consultar_agrupado');
    }

    function consultar_producto_agrupado()
    {

        $fecha_inicial = $_REQUEST['fecha_inicial'];

        $hora_inicial = $_REQUEST['hora_inicial'];

        $fecha_final = $_REQUEST['fecha_final'];

        $hora_final = $_REQUEST['hora_final'];

        if (empty($hora_inicial) and empty($hora_final)) {
            $resultado = model('productoFacturaVentaModel')->resutado_entre_fechas($fecha_inicial, $fecha_final);
            if ($resultado) {
                $returnData = [
                    'resultado' => 1,
                ];
                echo json_encode($returnData);
            } else {
                $returnData = [
                    'resultado' => 0, //No hay resultados
                ];
                echo json_encode($returnData);
            }
        }
        if (empty($hora_inicial) and !empty($hora_final)) {
            $fecha_inicial;
            $temp_fecha_final = $fecha_final . " " . $hora_final;

            $resultado_fecha = model('productoFacturaVentaModel')->resutado_entre_fechas_sin_hora_inicial($fecha_inicial, $temp_fecha_final);
            if ($resultado_fecha) {
                $returnData = [
                    'resultado' => 1, //No hay resultados
                ];
                echo json_encode($returnData);
            } else {
                $returnData = [
                    'resultado' => 0, //No hay resultados
                ];
                echo json_encode($returnData);
            }
        }
        if (!empty($hora_inicial) and empty($hora_final)) {
            $returnData = [
                'resultado' => 2, //No hay resultados
            ];
            echo json_encode($returnData);
        }
        if (!empty($hora_inicial) and !empty($hora_final)) {
            $temp_fecha_inicial = $fecha_inicial . " " . $hora_inicial;
            $temp_fecha_final = $fecha_final . " " . $hora_final;
            $resultado_fechas = model('productoFacturaVentaModel')->resutado_entre_fechas_sin_hora_inicial($temp_fecha_inicial, $temp_fecha_final);
            if ($resultado_fechas) {
                $returnData = [
                    'resultado' => 1, //No hay resultados
                ];
                echo json_encode($returnData);
            } else {
                $returnData = [
                    'resultado' => 0, //No hay resultados
                ];
                echo json_encode($returnData);
            }
        }
    }
    function datos_consultar_producto_agrupado()
    {
        $fecha_inicial = $_REQUEST['fecha_inicial_agrupado'];

        $hora_inicial = $_REQUEST['hora_inicial_agrupado'];

        $fecha_final = $_REQUEST['fecha_final_agrupado'];

        $hora_final = $_REQUEST['hora_final_agrupado'];

        if (empty($hora_inicial) and empty($hora_final)) {

            $resultado = model('productoFacturaVentaModel')->resutado_suma_entre_fechas($fecha_inicial, $fecha_final);

            $validar_tabla_reporte_producto = model('reporteProductoModel')->findAll();

            if (empty($validar_tabla_reporte_producto)) {

                foreach ($resultado as $detalle) {

                    $productos_suma = model('productoFacturaVentaModel')->reporte_suma_cantidades($fecha_inicial, $fecha_final, $detalle['valor_total_producto'], $detalle['codigointernoproducto']);
                    $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                    $codigocategoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                    $data = [
                        'cantidad' => $productos_suma[0]['cantidad'],
                        'nombre_producto' => $nombre_producto['nombreproducto'],
                        'precio_venta' => $productos_suma[0]['valor_total_producto'],
                        'valor_total' => $productos_suma[0]['valor_total_producto'] * $productos_suma[0]['cantidad'],
                        'id_categoria' => $codigocategoria['codigocategoria'],
                        'codigo_interno_producto' => $detalle['codigointernoproducto']
                    ];
                    $insert = model('reporteProductoModel')->insert($data);
                }

                $categorias = model('productoFacturaVentaModel')->categorias($fecha_inicial, $fecha_final);

                $devoluciones = model('devolucionModel')->resutado_suma_entre_fechas($fecha_inicial, $fecha_final);

                $total_devoluciones = model('devolucionModel')->total($fecha_inicial, $fecha_final);


                return view('producto/datos_consultar_agrupado', [
                    //'datos_productos' => $resultado,
                    'categorias' => $categorias,
                    'fecha_inicial' => $fecha_inicial,
                    'fecha_final' => $fecha_final,
                    //'total' => "$" . number_format($total[0]['total'], 0, ",", "."),
                    'devoluciones' => $devoluciones,
                    'total_devoluciones' => "$" . number_format($total_devoluciones[0]['total'], 0, ",", "."),
                    'hora_inicial' => 0,
                    'hora_final' => 0
                ]);
            } else if (!empty($validar_tabla_reporte_producto)) {

                $categorias = model('productoFacturaVentaModel')->categorias($fecha_inicial, $fecha_final);


                $devoluciones = model('devolucionModel')->resutado_suma_entre_fechas($fecha_inicial, $fecha_final);

                $total_devoluciones = model('devolucionModel')->total($fecha_inicial, $fecha_final);

                return view('producto/datos_consultar_agrupado', [
                    //'datos_productos' => $resultado,
                    'categorias' => $categorias,
                    'fecha_inicial' => $fecha_inicial,
                    'fecha_final' => $fecha_final,
                    //'total' => "$" . number_format($total[0]['total'], 0, ",", "."),
                    'devoluciones' => $devoluciones,
                    'total_devoluciones' => "$" . number_format($total_devoluciones[0]['total'], 0, ",", "."),
                    'hora_inicial' => 0,
                    'hora_final' => 0
                ]);
            }
        }

        if (empty($hora_inicial) and !empty($hora_final)) {

            $temp_fecha_inicial = $fecha_inicial;
            $temp_fecha_final = $fecha_final . " " . $hora_final;
            $resultado_fecha = model('productoFacturaVentaModel')->consulta_entre_fechas_sin_hora_inicial($temp_fecha_inicial, $temp_fecha_final);

            // $total = model('productoFacturaVentaModel')->total_entre_fechas_sin_hora_inicial($fecha_inicial, $temp_fecha_final);
            $validar_tabla_reporte_producto = model('reporteProductoModel')->findAll();



            if (empty($validar_tabla_reporte_producto)) {

                foreach ($resultado_fecha as $detalle) {
                    $productos_suma = model('productoFacturaVentaModel')->reporte_suma_cantidades($fecha_inicial, $fecha_final, $detalle['valor_total_producto'], $detalle['codigointernoproducto']);
                    $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                    $codigocategoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();

                    $data = [
                        'cantidad' => $productos_suma[0]['cantidad'],
                        'nombre_producto' => $nombre_producto['nombreproducto'],
                        'precio_venta' => $productos_suma[0]['valor_total_producto'],
                        'valor_total' => $productos_suma[0]['valor_total_producto'] * $productos_suma[0]['cantidad'],
                        'id_categoria' => $codigocategoria['codigocategoria'],
                        'codigo_interno_producto' => $detalle['codigointernoproducto'],
                        'hora_inicial' => 0
                    ];
                    $insert = model('reporteProductoModel')->insert($data);
                }

                $devoluciones = model('devolucionModel')->resutado_suma_entre_fecha_y_hora_final($fecha_inicial, $temp_fecha_final);

                $total_devoluciones = model('devolucionModel')->total_con_hora_final($fecha_inicial, $temp_fecha_final);

                $categorias = model('productoFacturaVentaModel')->categorias($fecha_inicial, $fecha_final);

                return view('producto/datos_consultar_agrupado', [
                    'datos_productos' => $resultado_fecha,
                    'fecha_inicial' => $fecha_inicial,
                    'fecha_final' => $fecha_final,
                    //'total' => "$" . number_format($total[0]['total'], 0, ",", "."),
                    'devoluciones' => $devoluciones,
                    'total_devoluciones' => "$" . number_format($total_devoluciones[0]['total'], 0, ",", "."),
                    'hora_inicial' => 0,
                    'hora_final' => 0,
                    'categorias' => $categorias
                ]);
            }
            if (!empty($validar_tabla_reporte_producto)) {

                $devoluciones = model('devolucionModel')->resutado_suma_entre_fecha_y_hora_final($fecha_inicial, $temp_fecha_final);

                $total_devoluciones = model('devolucionModel')->total_con_hora_final($fecha_inicial, $temp_fecha_final);

                $categorias = model('productoFacturaVentaModel')->categorias($fecha_inicial, $fecha_final);

                return view('producto/datos_consultar_agrupado', [
                    'datos_productos' => $resultado_fecha,
                    'fecha_inicial' => $fecha_inicial,
                    'fecha_final' => $fecha_final,
                    'hora_inicial' => '',
                    'hora_final' => $hora_final,
                    'devoluciones' => $devoluciones,
                    'total_devoluciones' => "$" . number_format($total_devoluciones[0]['total'], 0, ",", "."),
                    'categorias' => $categorias
                ]);
            }
        }
        if (!empty($hora_inicial) and !empty($hora_final)) {

            $temp_fecha_inicial = $fecha_inicial . " " . $hora_inicial;
            $temp_fecha_final = $fecha_final . " " . $hora_final;

            $resultado_fechas = model('productoFacturaVentaModel')->consulta_entre_fechas_con_hora_inicial_y_final($temp_fecha_inicial, $temp_fecha_final);

            $validar_tabla_reporte_producto = model('reporteProductoModel')->findAll();

            if (empty($validar_tabla_reporte_producto)) {

                foreach ($resultado_fechas as $detalle) {
                    $productos_suma = model('productoFacturaVentaModel')->reporte_suma_cantidades_con_horas($temp_fecha_inicial, $temp_fecha_final, $detalle['valor_total_producto'], $detalle['codigointernoproducto']);
                    $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                    $codigocategoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();

                    $data = [
                        'cantidad' => $productos_suma[0]['cantidad'],
                        'nombre_producto' => $nombre_producto['nombreproducto'],
                        'precio_venta' => $productos_suma[0]['valor_total_producto'],
                        'valor_total' => $productos_suma[0]['valor_total_producto'] * $productos_suma[0]['cantidad'],
                        'id_categoria' => $codigocategoria['codigocategoria'],
                        'codigo_interno_producto' => $detalle['codigointernoproducto']
                    ];
                    $insert = model('reporteProductoModel')->insert($data);
                }

                $devoluciones = model('devolucionModel')->resutado_suma_entre_fecha_con_hora_final($temp_fecha_inicial, $temp_fecha_final);

                $total_devoluciones = model('devolucionModel')->total_con_hora_final_y_final($temp_fecha_inicial, $temp_fecha_final);
                $categorias = model('productoFacturaVentaModel')->categorias($fecha_inicial, $fecha_final);

                return view('producto/datos_consultar_agrupado', [
                    'datos_productos' => $resultado_fechas,
                    'fecha_inicial' => $temp_fecha_inicial,
                    'fecha_final' => $temp_fecha_final,
                    //'total' => "$" . number_format($total[0]['total'], 0, ",", "."),
                    'devoluciones' => $devoluciones,
                    'total_devoluciones' => "$" . number_format($total_devoluciones[0]['total'], 0, ",", "."),
                    'hora_inicial' => $hora_inicial,
                    'hora_final' => $hora_final,
                    'categorias' => $categorias
                ]);
            }
            if (!empty($validar_tabla_reporte_producto)) {

                $devoluciones = model('devolucionModel')->resutado_suma_entre_fecha_con_hora_final($temp_fecha_inicial, $temp_fecha_final);

                $total_devoluciones = model('devolucionModel')->total_con_hora_final_y_final($temp_fecha_inicial, $temp_fecha_final);
                $categorias = model('productoFacturaVentaModel')->categorias_con_horas($temp_fecha_inicial, $temp_fecha_final);

                return view('producto/datos_consultar_agrupado', [
                    'datos_productos' => $resultado_fechas,
                    'fecha_inicial' => $temp_fecha_inicial,
                    'fecha_final' => $temp_fecha_final,
                    //'total' => "$" . number_format($total[0]['total'], 0, ",", "."),
                    'devoluciones' => $devoluciones,
                    'total_devoluciones' => "$" . number_format($total_devoluciones[0]['total'], 0, ",", "."),
                    'hora_inicial' => $hora_inicial,
                    'hora_final' => $hora_final,
                    'categorias' => $categorias
                ]);
            }
        }

        //echo  $hora_consulta_inicial = $fecha_inicial . " " . $hora_inicial;
    }


    function valor_apertura()
    {
        $apertura = [
            'valor' => $_REQUEST['apertura'],
        ];
        $model = model('aperturaModel');
        $actualizar_apertura = $model->set($apertura);
        $actualizar_apertura = $model->where('id', $_REQUEST['id_apertura']);
        $actualizar_apertura = $model->update();

        if ($actualizar_apertura) {

            /**
             * Apertura
             */
            $valor_apertura = model('aperturaModel')->select('valor')->where('id', $_REQUEST['id_apertura'])->first();
            $feha_y_hora_apertura = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $_REQUEST['id_apertura'])->first();

            /**
             * Fin apertura
             */


            /**
             * Cierre
             */



            $id_cierre = model('cierreModel')->select('id')->where('idapertura', $_REQUEST['id_apertura'])->first();



            $fecha_y_hora_cierre = model('cierreModel')->select('fecha_y_hora_cierre')->where('idapertura', $_REQUEST['id_apertura'])->first();
            $valor_cierre_efectivo_usuario = model('cierreFormaPagoModel')->valor_cierre_efectivo_usuario($id_cierre['id']);
            $valor_cierre_transaccion_usuario = model('cierreFormaPagoModel')->valor_cierre_transaccion_usuario($id_cierre['id']);

            /**
             * Fin cierre
             */

            /*Movimientos de facturacion de sistema*/


            $movimientos = model('facturaVentaModel')->detalle_de_ventas($feha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            $ingresos_efectivo = model('facturaFormaPagoModel')->ingresos_efectivo($feha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            $ingresos_transaccion = model('facturaFormaPagoModel')->ingresos_transaccion($feha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            $total_ingresos = model('facturaFormaPagoModel')->total_ingresos($feha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            $devolucion_venta = model('devolucionModel')->devoluciones($feha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            $temp_devoluciones = 0;
            $total_devoluciones = 0;

            foreach ($devolucion_venta as $detalle) {
                $codigo_interno_producto = model('detalleDevolucionVentaModel')->select('codigo')->where('id_devolucion_venta', $detalle['id'])->first();
                $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigo'])->first();
                $valor = model('devolucionVentaEfectivoModel')->select('valor')->where('iddevolucion', $detalle['id'])->first();
                $temp_devoluciones = $temp_devoluciones + $valor['valor'];
                $total_devoluciones = $temp_devoluciones;
            }
            $retiros = model('retiroModel')->select('*')->where('id_apertura', $_REQUEST['id_apertura'])->findAll();

            $temp_retiros = 0;
            $total_retiros = 0;

            if (!empty($retiros)) {
                foreach ($retiros as $detalle_retiro) {
                    $valor = model('retiroFormaPagoModel')->select('valor')->where('idretiro', $detalle_retiro['id'])->first();
                    $temp_retiros = $temp_retiros + $valor['valor'];
                    $total_retiros = $temp_retiros;
                }
            }

            /*Movimientos de facturacion de sistema*/


            $ingresos_efectivo_mas_valor_apertura = $ingresos_efectivo[0]['ingresos_efectivo'] + $valor_apertura['valor'];
            $retiros_devoluciones = $total_devoluciones + $total_retiros;

            $efectivo_caja = $ingresos_efectivo_mas_valor_apertura - $retiros_devoluciones;

            $diferencia_efectivo = $valor_cierre_efectivo_usuario[0]['valor'] - $efectivo_caja;

            $diferencia_transacciones = $valor_cierre_transaccion_usuario[0]['valor'] - $ingresos_transaccion[0]['ingresos_transaccion'];

            $saldo = $diferencia_efectivo + $diferencia_transacciones;


            $returnData = [
                'resultado' => 1,
                'valor_apertura' => "Valor apertura $" . number_format($_REQUEST['apertura'], 0, ",", "."),
                'val_apertura' => number_format($_REQUEST['apertura'], 0, ",", "."),
                'saldo' => "SALDO $" . number_format($saldo, 0, ",", ".")
            ];
            echo json_encode($returnData);
        }
    }
    function actualizar_efectivo_usuario()
    {
        $id_cierre = model('cierreModel')->select('id')->where('idapertura', $_REQUEST['id_apertura'])->first();




        $efectivo = [
            'valor' => $_REQUEST['efectivo'],
        ];
        $model = model('cierreFormaPagoModel');
        $actualizar_efectivo = $model->set($efectivo);
        $actualizar_efectivo = $model->where('idcierre', $id_cierre['id']);
        $actualizar_efectivo = $model->where('idpago', '1');
        $actualizar_efectivo = $model->update();

        if ($actualizar_efectivo) {

            /**
             * Apertura
             */
            $valor_apertura = model('aperturaModel')->select('valor')->where('id', $_REQUEST['id_apertura'])->first();
            $feha_y_hora_apertura = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $_REQUEST['id_apertura'])->first();

            /**
             * Fin apertura
             */


            /**
             * Cierre
             */



            $fecha_y_hora_cierre = model('cierreModel')->select('fecha_y_hora_cierre')->where('idapertura', $_REQUEST['id_apertura'])->first();
            $valor_cierre_efectivo_usuario = model('cierreFormaPagoModel')->valor_cierre_efectivo_usuario($id_cierre['id']);
            $valor_cierre_transaccion_usuario = model('cierreFormaPagoModel')->valor_cierre_transaccion_usuario($id_cierre['id']);

            /**
             * Fin cierre
             */

            /*Movimientos de facturacion de sistema*/


            $movimientos = model('facturaVentaModel')->detalle_de_ventas($feha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            $ingresos_efectivo = model('facturaFormaPagoModel')->ingresos_efectivo($feha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            $ingresos_transaccion = model('facturaFormaPagoModel')->ingresos_transaccion($feha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            $total_ingresos = model('facturaFormaPagoModel')->total_ingresos($feha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            $devolucion_venta = model('devolucionModel')->devoluciones($feha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            $temp_devoluciones = 0;
            $total_devoluciones = 0;

            foreach ($devolucion_venta as $detalle) {
                $codigo_interno_producto = model('detalleDevolucionVentaModel')->select('codigo')->where('id_devolucion_venta', $detalle['id'])->first();
                $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigo'])->first();
                $valor = model('devolucionVentaEfectivoModel')->select('valor')->where('iddevolucion', $detalle['id'])->first();
                $temp_devoluciones = $temp_devoluciones + $valor['valor'];
                $total_devoluciones = $temp_devoluciones;
            }
            $retiros = model('retiroModel')->select('*')->where('id_apertura', $_REQUEST['id_apertura'])->findAll();

            $temp_retiros = 0;
            $total_retiros = 0;

            if (!empty($retiros)) {
                foreach ($retiros as $detalle_retiro) {
                    $valor = model('retiroFormaPagoModel')->select('valor')->where('idretiro', $detalle_retiro['id'])->first();
                    $temp_retiros = $temp_retiros + $valor['valor'];
                    $total_retiros = $temp_retiros;
                }
            }

            /*Movimientos de facturacion de sistema*/


            $ingresos_efectivo_mas_valor_apertura = $ingresos_efectivo[0]['ingresos_efectivo'] + $valor_apertura['valor'];
            $retiros_devoluciones = $total_devoluciones + $total_retiros;

            $efectivo_caja = $ingresos_efectivo_mas_valor_apertura - $retiros_devoluciones;

            $diferencia_efectivo = $valor_cierre_efectivo_usuario[0]['valor'] - $efectivo_caja;

            $diferencia_transacciones = $valor_cierre_transaccion_usuario[0]['valor'] - $ingresos_transaccion[0]['ingresos_transaccion'];

            $saldo = $diferencia_efectivo + $diferencia_transacciones;


            $returnData = [
                'resultado' => 1,
                'efectivo' => "Efectivo $" . number_format($_REQUEST['efectivo'], 0, ",", "."),
                'efecti' => number_format($_REQUEST['efectivo'], 0, ",", "."),
                'saldo' => "SALDO $" . number_format($saldo, 0, ",", ".")
            ];
            echo json_encode($returnData);
        }
    }
    function actualizar_transaccion_usuario()
    {
        $id_cierre = model('cierreModel')->select('id')->where('idapertura', $_REQUEST['id_apertura'])->first();

        $transaccion = [
            'valor' => $_REQUEST['transaccion'],
        ];
        $model = model('cierreFormaPagoModel');
        $actualizar_transaccion = $model->set($transaccion);
        $actualizar_transaccion = $model->where('idcierre', $id_cierre['id']);
        $actualizar_transaccion = $model->where('idpago', 4);
        $actualizar_transaccion = $model->update();




        if ($actualizar_transaccion) {

            /**
             * Apertura
             */
            $valor_apertura = model('aperturaModel')->select('valor')->where('id', $_REQUEST['id_apertura'])->first();
            $feha_y_hora_apertura = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $_REQUEST['id_apertura'])->first();

            /**
             * Fin apertura
             */


            /**
             * Cierre
             */



            $fecha_y_hora_cierre = model('cierreModel')->select('fecha_y_hora_cierre')->where('idapertura', $_REQUEST['id_apertura'])->first();
            $valor_cierre_efectivo_usuario = model('cierreFormaPagoModel')->valor_cierre_efectivo_usuario($id_cierre['id']);
            $valor_cierre_transaccion_usuario = model('cierreFormaPagoModel')->valor_cierre_transaccion_usuario($id_cierre['id']);

            /**
             * Fin cierre
             */

            /*Movimientos de facturacion de sistema*/


            $movimientos = model('facturaVentaModel')->detalle_de_ventas($feha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            $ingresos_efectivo = model('facturaFormaPagoModel')->ingresos_efectivo($feha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            $ingresos_transaccion = model('facturaFormaPagoModel')->ingresos_transaccion($feha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            $total_ingresos = model('facturaFormaPagoModel')->total_ingresos($feha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            $devolucion_venta = model('devolucionModel')->devoluciones($feha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            $temp_devoluciones = 0;
            $total_devoluciones = 0;

            foreach ($devolucion_venta as $detalle) {
                $codigo_interno_producto = model('detalleDevolucionVentaModel')->select('codigo')->where('id_devolucion_venta', $detalle['id'])->first();
                $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigo'])->first();
                $valor = model('devolucionVentaEfectivoModel')->select('valor')->where('iddevolucion', $detalle['id'])->first();
                $temp_devoluciones = $temp_devoluciones + $valor['valor'];
                $total_devoluciones = $temp_devoluciones;
            }
            $retiros = model('retiroModel')->select('*')->where('id_apertura', $_REQUEST['id_apertura'])->findAll();

            $temp_retiros = 0;
            $total_retiros = 0;

            if (!empty($retiros)) {
                foreach ($retiros as $detalle_retiro) {
                    $valor = model('retiroFormaPagoModel')->select('valor')->where('idretiro', $detalle_retiro['id'])->first();
                    $temp_retiros = $temp_retiros + $valor['valor'];
                    $total_retiros = $temp_retiros;
                }
            }

            /*Movimientos de facturacion de sistema*/


            $ingresos_efectivo_mas_valor_apertura = $ingresos_efectivo[0]['ingresos_efectivo'] + $valor_apertura['valor'];
            $retiros_devoluciones = $total_devoluciones + $total_retiros;

            $efectivo_caja = $ingresos_efectivo_mas_valor_apertura - $retiros_devoluciones;

            $diferencia_efectivo = $valor_cierre_efectivo_usuario[0]['valor'] - $efectivo_caja;

            $diferencia_transacciones = $valor_cierre_transaccion_usuario[0]['valor'] - $ingresos_transaccion[0]['ingresos_transaccion'];

            $saldo = $diferencia_efectivo + $diferencia_transacciones;


            $returnData = [
                'resultado' => 1,
                'transaccion' => "Transaccion $" . number_format($_REQUEST['transaccion'], 0, ",", "."),
                'transacc' => number_format($_REQUEST['transaccion'], 0, ",", "."),
                'saldo' => "SALDO $" . number_format($saldo, 0, ",", ".")
            ];
            echo json_encode($returnData);
        }
    }


    function datos_consultar_producto_agrupado_pdf()
    {



        $dompdf = new Dompdf();

        $options = $dompdf->getOptions();

        $options->set(array('isRemoteEnable' => true));
        $dompdf->setOptions($options);

        $datos_empresa = model('empresaModel')->find();
        $id_regimen = $datos_empresa[0]['idregimen'];
        $regimen = model('regimenModel')->select('nombreregimen')->where('idregimen', $id_regimen)->first();
        $nombre_ciudad = model('ciudadModel')->select('nombreciudad')->where('idciudad', $datos_empresa[0]['idciudad'])->first();
        $nombre_departamento = model('departamentoModel')->select('nombredepartamento')->where('iddepartamento', $datos_empresa[0]['iddepartamento'])->first();




        $id_apertura = $this->request->getGet('id_apertura');



        $fecha_cierre = "";
        $fecha_y_hora_cierre = "";
        $hora_cierre = "";
        $fecha_y_hora_apertura = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $id_apertura)->first();

        $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
        $hor_apertura = model('aperturaModel')->select('hora')->where('id', $id_apertura)->first();
        $hora_apertura = $hor_apertura['hora'];
        $fecha_cierre = model('cierreModel')->select('fecha_y_hora_cierre')->where('idapertura', $id_apertura)->first();
        $hora_cierre = model('cierreModel')->select('hora')->where('idapertura', $id_apertura)->first();
        if (empty($fecha_cierre) and empty($hora_cierre)) {
            $fecha_y_hora_cierre = date('Y-m-d H:i:s');
            $hora_cierre = date('H:i:s');
        } else if (!empty($fecha_cierre)) {
            $fecha_y_hora_cierre = $fecha_cierre['fecha_y_hora_cierre'];
            $hora_cierre = $hora_cierre['hora'];
        }


        $productos_distinct = model('kardexModel')->get_productos($id_apertura);
        $categorias = model('kardexModel')->get_categorias($id_apertura);



        foreach ($productos_distinct as $detalle) {

            $total = model('kardexModel')->get_total($id_apertura, $detalle['valor_unitario'], $detalle['codigo']);

            $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $detalle['codigo'])->first();
            $cantidad = $total[0]['cantidad'];

            $data = [
                'cantidad' => $cantidad,
                'nombre_producto' => $nombre_producto['nombreproducto'],
                'precio_venta' => $detalle['valor_unitario'],
                'valor_total' => $detalle['valor_unitario'] * $cantidad,
                'id_categoria' => $detalle['id_categoria'],
                'codigo_interno_producto' => $detalle['codigo'],
                'valor_unitario' => $detalle['valor_unitario']
            ];
            $insert = model('reporteProductoModel')->insert($data);
        }

        $devoluciones = model('detalleDevolucionVentaModel')->where('id_apertura', $id_apertura)->find();

        $dompdf->loadHtml(view('producto/datos_consultar_agrupado_pdf', [
            'datos_productos' => $productos_distinct,
            'fecha_inicial' => $fecha_y_hora_apertura['fecha_y_hora_apertura'],
            'fecha_final' => $fecha_y_hora_cierre,
            //'total' => "$" . number_format($total[0]['total'], 0, ",", "."),
            'devoluciones' => $devoluciones,
            //'total_devoluciones' => "$" . number_format($total_devoluciones[0]['total'], 0, ",", "."),
            'datos_empresa' => $datos_empresa,
            'regimen' => $regimen['nombreregimen'],
            'nombre_ciudad' => $nombre_ciudad['nombreciudad'],
            'nombre_departamento' => $nombre_departamento['nombredepartamento'],
            'categorias' => $categorias,
            'id_apertura' => $id_apertura
        ]));


        $options = $dompdf->getOptions();
        $dompdf->setPaper('letter');
        $dompdf->render();
        $dompdf->stream($fecha_apertura['fecha'] . ".pdf", array("Attachment" => true));



        //echo  $hora_consulta_inicial = $fecha_inicial . " " . $hora_inicial;
    }





    public function imprimir_reporte_fiscal()
    {

        $datos_empresa = model('empresaModel')->find();
        $id_regimen = $datos_empresa[0]['idregimen'];
        $regimen = model('regimenModel')->select('nombreregimen')->where('idregimen', $id_regimen)->first();
        $nombre_ciudad = model('municipiosModel')->select('nombreciudad')->where('idciudad', $datos_empresa[0]['idciudad'])->first();
        $nombre_departamento = model('departamentoModel')->select('nombredepartamento')->where('iddepartamento', $datos_empresa[0]['iddepartamento'])->first();

        $fecha_factura = $this->request->getVar('fecha');
        //$fecha_factura = '2023-03-24';

        $id_factura_primero = model('facturaVentaModel')->selectMin('id')->where('fecha_factura_venta', $fecha_factura)->findAll();
        $id_factura_final = model('facturaVentaModel')->selectMax('id')->where('fecha_factura_venta', $fecha_factura)->findAll();

        $registro_inicial = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura_primero[0]['id'])->first();
        $registro_final = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura_final[0]['id'])->first();



        $total_registros = model('facturaVentaModel')->selectCount('id')->where('fecha_factura_venta', $fecha_factura)->find();

        $porcentaje_iva = model('productoFacturaVentaModel')->iva($id_factura_primero[0]['id'], $id_factura_final[0]['id']);


        $valores_iva = array();
        foreach ($porcentaje_iva as $detalle) {

            $valor_iva = model('productoFacturaVentaModel')->valor_iva($detalle['valor_iva'], $id_factura_primero[0]['id'], $id_factura_final[0]['id']);

            $prueba = array();
            array_push($prueba, $valor_iva[0]['tarifa_iva']); //0 = tarifa de iva 
            array_push($prueba, $valor_iva[0]['base']);  // 1 = base (impuesto antes de iva )
            array_push($prueba, $valor_iva[0]['total_iva']); // 2 = total del iva 
            array_push($prueba, $valor_iva[0]['total']);  //3  total = Base + total
            array_push($valores_iva, $prueba);
        }

        $porcentaje_ico = model('productoFacturaVentaModel')->ico($id_factura_primero[0]['id'], $id_factura_final[0]['id']);

        $valores_ico = array();
        foreach ($porcentaje_ico as $detalle) {

            $valor_ico = model('productoFacturaVentaModel')->valor_ico($detalle['valor_ico'], $id_factura_primero[0]['id'], $id_factura_final[0]['id']);

            $prueba_ico = array();
            array_push($prueba_ico, $valor_ico[0]['tarifa_ico']); //0 = tarifa de iva 
            array_push($prueba_ico, $valor_ico[0]['base']);  // 1 = base (impuesto antes de iva )
            array_push($prueba_ico, $valor_ico[0]['total_ico']); // 2 = total del iva 
            array_push($prueba_ico, $valor_ico[0]['total']);  //3  total = Base + total
            array_push($valores_ico, $prueba_ico);
        }

        $vantas_de_contado = model('facturaVentaModel')->ventas_de_contado($fecha_factura);
        $ventas_de_credito = model('facturaVentaModel')->ventas_de_credito($fecha_factura);



        $vantas_contado = model('facturaVentaModel')->ventas_contado($vantas_de_contado[0]['id_inicial'], $vantas_de_contado[0]['id_final']);

        /**
         * Calculo para las devoluciones
         */
        $id_devoluciones = model('devolucionModel')->id($fecha_factura);

        if (!empty($id_devoluciones[0]['minimo'] && !empty($id_devoluciones[0]['maximo']))) {

            $tarifa_iva_devolucion = model('devolucionModel')->iva($id_devoluciones[0]['minimo'], $id_devoluciones[0]['maximo']);

            $iva_de_devolucion = array();
            foreach ($tarifa_iva_devolucion as $tarifa_iva) {
                $iva_devolucion = model('devolucionModel')->iva_devolucion($tarifa_iva['iva'], $id_devoluciones[0]['minimo'], $id_devoluciones[0]['maximo']);

                $temp_porcentaje = $tarifa_iva['iva'] / 100;

                $sub_total = $iva_devolucion[0]['base'] * $temp_porcentaje;

                $total = $iva_devolucion[0]['base'] + $sub_total;

                $impuesto = $total - $iva_devolucion[0]['base'];

                $total_iva = array();
                array_push($total_iva, $iva_devolucion[0]['base']); //Base
                array_push($total_iva, $tarifa_iva['iva']); //Tarifa
                array_push($total_iva, $impuesto); //Impuesto
                array_push($total_iva, $total); //total
                array_push($iva_de_devolucion, $total_iva);
            }
        } else {

            $iva_de_devolucion = array();
            $total_iva = array();
            array_push($total_iva, 0); //Base
            array_push($total_iva, 0); //Tarifa
            array_push($total_iva, 0); //Impuesto
            array_push($total_iva, 0); //total
            array_push($iva_de_devolucion, $total_iva);
        }


        $id_devoluciones_ico = model('devolucionModel')->id($fecha_factura);




        if (!empty($id_devoluciones_ico[0]['minimo'] && $id_devoluciones[0]['maximo'])) {



            $tarifa_ico_devolucion = model('devolucionModel')->ico($id_devoluciones[0]['minimo'], $id_devoluciones[0]['maximo']);

            if (!empty($tarifa_ico_devolucion)) {

                $ico_de_devolucion = array();
                foreach ($tarifa_ico_devolucion as $tarifa_ico) {

                    $ico_devolucion = model('devolucionModel')->ico_devolucion($tarifa_ico['ico'], $id_devoluciones[0]['minimo'], $id_devoluciones[0]['maximo']);
                    $temp_porcentaje = $tarifa_ico['ico'] / 100;

                    $sub_total = $ico_devolucion[0]['base'] * $temp_porcentaje;

                    $total = $ico_devolucion[0]['base'] + $sub_total;

                    $impuesto = $total - $ico_devolucion[0]['base'];

                    $total_ico = array();
                    array_push($total_ico, $ico_devolucion[0]['base']); //Base
                    array_push($total_ico, $tarifa_ico['ico']); //Tarifa
                    array_push($total_ico, $impuesto); //Impuesto
                    array_push($total_ico, $total); //total
                    array_push($ico_de_devolucion, $total_ico);

                    //echo number_format($iva_devolucion[0]['base'])."</br>";
                }
            } else {
                $ico_de_devolucion = array();
                $total_ico = array();
                array_push($total_ico, 0); //Base
                array_push($total_ico, 0); //Tarifa
                array_push($total_ico, 0); //Impuesto
                array_push($total_ico, 0); //total
                array_push($ico_de_devolucion, $total_ico);
            }
        } else {
            $ico_de_devolucion = array();
            $total_ico = array();
            array_push($total_ico, 0); //Base
            array_push($total_ico, 0); //Tarifa
            array_push($total_ico, 0); //Impuesto
            array_push($total_ico, 0); //total
            array_push($ico_de_devolucion, $total_ico);
        }


        $consecutivo_informe = model('consecutivoInformeModel')->select('numero')->where('fecha', $fecha_factura)->first();
        /* $dompdf->loadHtml(view('consultas_y_reportes/informe_fiscal_ventas_diarias_datos_pdf', [
                "nombre_comercial" => $datos_empresa[0]['nombrecomercialempresa'],
                "nombre_juridico" => $datos_empresa[0]['nombrejuridicoempresa'],
                "nit" => $datos_empresa[0]['nitempresa'],
                "nombre_regimen" => $regimen['nombreregimen'],
                "direccion" => $datos_empresa[0]['direccionempresa'],
                "nombre_ciudad" => $nombre_ciudad['nombreciudad'],
                "nombre_departamento" => $nombre_departamento['nombredepartamento'],
                "registro_inicial" => $registro_inicial['numerofactura_venta'],
                "registro_final" => $registro_final['numerofactura_venta'],
                "total_registros" => $total_registros[0]['id'],
                "iva" => $valores_iva,
                "ico" => $valores_ico,
                "vantas_contado" => $vantas_contado[0]['total_contado'],
                "iva_devolucion" => $iva_de_devolucion,
                "ico_devolucion" => $ico_de_devolucion,
                //"consecutivo" => $consecutivo_caja['consecutivo'],
                "consecutivo" => $consecutivo_informe['numero'],
                "fecha" => $fecha_factura

            ])); */


        $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();

        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
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
        $printer->text("INFORME FISCAL DE VENTAS" . "\n");
        $printer->text("\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);

        $printer->text("N°" . $consecutivo_informe['numero'] . "\n");
        $printer->text("CAJA N° 1" . "\n");
        $printer->text("FECHA " . $fecha_factura . "\n");
        $printer->text("RANGO INICIAL  " . $registro_inicial['numerofactura_venta'] . "\n");
        $printer->text("RANGO FINAL    " . $registro_final['numerofactura_venta'] . "\n");
        $printer->text("TOTAL REGISTROS    " . $total_registros[0]['id'] . "\n");

        $printer->text("-------------------------------------------" . "\n");
        $printer->text("TOTALES POR TARIFA IVA " . "\n");
        $printer->text("-------------------------------------------" . "\n");
        $printer->text("\n");

        if (empty($valores_iva)) {

            $printer->text("Tarifa:" . "\n");
            $printer->text("0%:" . "\n");
            $printer->text("\n");
            $printer->text("Base gravable :" . "\n");
            $printer->text("0" . "\n");
            $printer->text("\n");
            $printer->text("Valor iva  \n");
            $printer->text("0" . "\n");
            $printer->text("\n");
            $printer->text("Valor total  \n");
            $printer->text("0" . "\n");
            $printer->text("\n");
        }
        if (!empty($valores_iva)) {


            foreach ($valores_iva as $detalle) {

                $printer->text("Tarifa:" . $detalle[0] . "%" . "\n");

                $printer->text("\n");
                $printer->text("Base gravable :" . "$" . number_format($detalle[1], 0, ",", ".") . "\n");

                $printer->text("\n");
                $printer->text("Valor iva" . "$" . number_format($detalle[2], 0, ",", ".") .  "\n");

                $printer->text("\n");
                $printer->text("Valor total" . "$" . number_format($detalle[3], 0, ",", ".") .  "\n");
                $printer->text("\n");
            }

            $printer->text("-------------------------------------------" . "\n");
            $printer->text("IMPUESTO AL CONSUMO " . "\n");
            $printer->text("-------------------------------------------" . "\n");
            $printer->text("\n");

            $printer->text("-------------------------------------------" . "\n");
            $printer->text("DETALLE DE VENTA  " . "\n");
            $printer->text("-------------------------------------------" . "\n");
            $printer->text("\n");
            $printer->text("VENTAS CONTADO: " . "$" . number_format($vantas_contado[0]['total_contado'], 0, ",", ".") . "\n");
            $printer->text("VENTAS CREDITO:  0 \n");
            $printer->text("TOTAL VENTAS   " . "$" . number_format($vantas_contado[0]['total_contado'], 0, ",", ".") . "\n");
            $printer->text("\n");


            $printer->text("-------------------------------------------" . "\n");
            $printer->text("IVA EN DEVOLUCIONES  " . "\n");
            $printer->text("-------------------------------------------" . "\n");

            foreach ($iva_de_devolucion as $detalle) {
                $printer->text("Número factura: Factura General \n");
                $printer->text("Tafifa:   " . $detalle[1] . "%" . "\n");
                $printer->text("Base:     " . $detalle[0] . "\n");
                $printer->text("Impuesto: " . $detalle[2] . "\n");
                $printer->text("Subtotal: " . $detalle[3] . "\n");
            }


            $printer->text("-------------------------------------------" . "\n");
            $printer->text("IMPUESTO AL CONSUMO EN DEVOLUCIONES  " . "\n");
            $printer->text("-------------------------------------------" . "\n");

            foreach ($ico_de_devolucion as $detalle) {
                $printer->text("Número factura: Factura General \n");
                $printer->text("Tafifa:   " . $detalle[1] . "%" . "\n");
                $printer->text("Base:     " . $detalle[0] . "\n");
                $printer->text("Impuesto: " . $detalle[2] . "\n");
                $printer->text("Subtotal: " . $detalle[3] . "\n");
            }

            $printer->text("\n");
            $printer->text("_____________________________\n");
            $printer->text("FIRMA\n");
        }

        if (empty($valores_ico)) {

            $printer->text("Tarifa:         0%" . "\n");
            $printer->text("\n");
            $printer->text("Base gravable : 0" . "\n");
            $printer->text("\n");
            $printer->text("Valor ico:      0  \n");
            $printer->text("\n");
            $printer->text("Valor total:    0  \n");
            $printer->text("\n");
        }
        foreach ($valores_ico as $detalle) {

            $printer->text("Tarifa ICO:" . $detalle[0] . "%" . "\n");
            $printer->text("\n");
            $printer->text("Base gravable :" . "$" . number_format($detalle[1], 0, ",", ".") . "\n");
            $printer->text("\n");
            $printer->text("Valor ICO:" . "$" . number_format($detalle[2], 0, ",", ".") . "\n");
            $printer->text("\n");
            $printer->text("Valor total" . "$" . number_format($detalle[3], 0, ",", ".") .  "\n");
            $printer->text("\n");
        }

        $printer->feed(1);
        $printer->cut();
        $printer->close();

        $returnData = array(
            "resultado" => 1, //No hay pedido   
        );
        echo  json_encode($returnData);
    }

    function reporte_caja_diario()
    {
        $movimientos = model('fiscalModel')->select('*')->orderBY('id', 'DESC')->findAll();

        return view('caja/movimientos', [
            'movimientos' => $movimientos
        ]);

        /*  $returnData = array(
            "resultado" => 1, //No hay pedido 
            "movimientos" => view('caja/movimientos', [
                'movimientos' => $movimientos
            ])
        );
        echo  json_encode($returnData); */
    }


    function pdf_reporte_producto()
    {

        $fecha_inicial = $this->request->getPost('fecha_inicial_pdf');
        $fecha_final = $this->request->getPost('fecha_final_pdf');

        $datos_producto = model('productoFacturaVentaModel')->datos_consulta_producto($fecha_inicial, $fecha_final);
        $total_datos_producto = model('productoFacturaVentaModel')->total_datos_consulta_producto($fecha_inicial, $fecha_final);

        $dompdf = new Dompdf();

        $options = $dompdf->getOptions();

        $options->set(array('isRemoteEnable' => true));
        $dompdf->setOptions($options);

        $datos_empresa = model('empresaModel')->find();
        $id_regimen = $datos_empresa[0]['idregimen'];
        $regimen = model('regimenModel')->select('nombreregimen')->where('idregimen', $id_regimen)->first();
        $nombre_ciudad = model('ciudadModel')->select('nombreciudad')->where('idciudad', $datos_empresa[0]['idciudad'])->first();
        $nombre_departamento = model('departamentoModel')->select('nombredepartamento')->where('iddepartamento', $datos_empresa[0]['iddepartamento'])->first();

        $dompdf->loadHtml(view('consultas_y_reportes/consulta_producto', [
            "nombre_comercial" => $datos_empresa[0]['nombrecomercialempresa'],
            "nombre_juridico" => $datos_empresa[0]['nombrejuridicoempresa'],
            "nit" => $datos_empresa[0]['nitempresa'],
            "nombre_regimen" => $regimen['nombreregimen'],
            "direccion" => $datos_empresa[0]['direccionempresa'],
            "nombre_ciudad" => $nombre_ciudad['nombreciudad'],
            "nombre_departamento" => $nombre_departamento['nombredepartamento'],
            "fecha_inicial" => $fecha_inicial,
            "fecha_final" => $fecha_final,
            "datos_producto" => $datos_producto,
            "total" => "$" . number_format($total_datos_producto[0]['total'], 0, ",", ".")


        ]));

        $options = $dompdf->getOptions();
        $dompdf->setPaper('letter');
        $dompdf->render();
        $dompdf->stream("Reporte de ventas de producto del " . $fecha_inicial . " al " . $fecha_final . " .pdf", array("Attachment" => true));
    }
}
