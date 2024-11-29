<?php

namespace App\Controllers\caja_general;

require APPPATH . "Controllers/mike42/autoload.php";

use App\Controllers\BaseController;

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class cajaGeneralController extends BaseController
{
    public function apertura()
    {


        return view('caja_general/apertura', [
            'caja_general' => model('cajaGeneralModel')->findAll()
        ]);
    }

    function generar_apertura()
    {
        $ultimo_id = model('movimientosCajaGeneralModel')->selectMax('id')->first();


        if (empty($ultimo_id['id'])) {

            $data = [
                'fecha_apertura' => $this->request->getPost('fecha_apertura_caja'),
                'valor_apertura' => str_replace('.', '', $this->request->getPost('apertura_caja')),
                'hora_apertura' => date('h:i:s A')
            ];
            $insert = model('movimientosCajaGeneralModel')->insert($data);

            if ($insert) {

                $clientes = model('clientesModel')->orderBy('id', 'asc')->findAll();
                $estado = model('estadoModel')->findAll();
                $regimen = model('regimenModel')->select('*')->find();
                $tipo_cliente = model('tipoClienteModel')->select('*')->find();
                $clasificacion_cliente = model('clasificacionClienteModel')->select('*')->find();
                $departamento = model('departamentoModel')->select('*')->where('idpais', 49)->find();
                $id_departamento_empresa = model('empresaModel')->select('iddepartamento')->first();
                $id_ciudad_empresa = model('empresaModel')->select('idciudad')->first();
                $ciudad = model('municipiosModel')->select('nombreciudad')->where('idciudad', $id_ciudad_empresa['idciudad'])->first();
                //$id_regimen_no_responsable_iva = model('regimenModel')->select('idregimen')->where('nombreregimen', 'NO RESPONSABLE DE IVA')->first();
                $lista_precios = model('cajaModel')->select('requiere_lista_de_precios')->first();
                return view('factura_pos/factura_pos_sin_productos', [
                    "clientes" => $clientes,
                    "estado" => $estado,
                    "regimen" => $regimen,
                    "tipo_cliente" => $tipo_cliente,
                    "clasificacion_cliente" => $clasificacion_cliente,
                    "departamentos" => $departamento,
                    "tiene_producto" => 0,
                    "valor_total" => 0,
                    "id_departamento" => $id_departamento_empresa['iddepartamento'],
                    "id_ciudad" => $id_ciudad_empresa['idciudad'],
                    "ciudad" => $ciudad['nombreciudad'],
                    "id_regimen" => 2,
                    "apertura" => 0,
                    "lista_precios" => $lista_precios['requiere_lista_de_precios'],
                    "caja_general" => 1
                ]);
            }
        } else if (!empty($ultimo_id['id'])) {



            $get_fechacierre_valorcierre = model('cajaGeneralModel')->get_fechacierre_valorcierre($ultimo_id['id']);

        
            if (empty($get_fechacierre_valorcierre[0]['fecha_cierre']) && empty($get_fechacierre_valorcierre[0]['valor_cierre'])) {
                $session = session();
                $session->setFlashdata('iconoMensaje', 'error');
                return redirect()->to(base_url('caja_general/cierre_general'))->with('mensaje', 'Caja general presenta apertura activa , primero debe cerrar la caja general ');
            }


            if (!empty($get_fechacierre_valorcierre[0]['fecha_cierre']) && !empty($get_fechacierre_valorcierre[0]['valor_cierre'])) {

                $data = [
                    'fecha_apertura' => $this->request->getPost('fecha_apertura_caja'),
                    'valor_apertura' => str_replace('.', '', $this->request->getPost('apertura_caja')),
                    'hora_apertura' => date('h:i:s A')
                ];

                $insert = model('movimientosCajaGeneralModel')->insert($data);

                if ($insert) {
                    $clientes = model('clientesModel')->orderBy('id', 'asc')->findAll();
                    $estado = model('estadoModel')->findAll();
                    $regimen = model('regimenModel')->select('*')->find();
                    $tipo_cliente = model('tipoClienteModel')->select('*')->find();
                    $clasificacion_cliente = model('clasificacionClienteModel')->select('*')->find();
                    $departamento = model('departamentoModel')->select('*')->where('idpais', 49)->find();
                    $id_departamento_empresa = model('empresaModel')->select('iddepartamento')->first();
                    $id_ciudad_empresa = model('empresaModel')->select('idciudad')->first();
                    $ciudad = model('municipiosModel')->select('nombreciudad')->where('idciudad', $id_ciudad_empresa['idciudad'])->first();
                    $id_regimen_no_responsable_iva = model('regimenModel')->select('idregimen')->where('nombreregimen', 'NO RESPONSABLE DE IVA')->first();
                    $lista_precios = model('cajaModel')->select('requiere_lista_de_precios')->first();
                    return view('factura_pos/factura_pos_sin_productos', [
                        "clientes" => $clientes,
                        "estado" => $estado,
                        "regimen" => $regimen,
                        "tipo_cliente" => $tipo_cliente,
                        "clasificacion_cliente" => $clasificacion_cliente,
                        "departamentos" => $departamento,
                        "tiene_producto" => 0,
                        "valor_total" => 0,
                        "id_departamento" => $id_departamento_empresa['iddepartamento'],
                        "id_ciudad" => $id_ciudad_empresa['idciudad'],
                        "ciudad" => $ciudad['nombreciudad'],
                        "id_regimen" => 2,
                        "apertura" => 0,
                        "lista_precios" => $lista_precios['requiere_lista_de_precios'],
                        "caja_general" => 1
                    ]);
                }
            }
        }
    }

    function cierre()
    {
        $cajas = model('cajaModel')->findAll();
        return view('caja_general/cierre', [
            "caja" => $cajas
        ]);
    }

    function generar_cierre()
    {
        $ultimo_id = model('cajaGeneralModel')->selectMax('id')->first();

        $get_fechacierre_valorcierre = model('cajaGeneralModel')->get_fechacierre_valorcierre($ultimo_id['id']);

        if (!empty($get_fechacierre_valorcierre[0]['fecha_cierre']) && !empty($get_fechacierre_valorcierre[0]['valor_cierre'])) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'error');
            return redirect()->to(base_url('caja_general/apertura_general'))->with('mensaje', 'Cierre pendiente de caja general ');
        }
        if (empty($get_fechacierre_valorcierre[0]['fecha_cierre']) && empty($get_fechacierre_valorcierre[0]['valor_cierre'])) {
            $data = [
                'fecha_cierre' => $this->request->getPost('fecha_cierre'),
                'valor_cierre' => str_replace('.', '', $this->request->getPost('efectivo_de_cierre')),
                'hora_cierre' => date('h:i:s A')
            ];

            $model = model('movimientosCajaGeneralModel');
            $actualizar = $model->set($data);
            $actualizar = $model->where('id', $ultimo_id['id']);
            $actualizar = $model->update();
            if ($actualizar) {
                $clientes = model('clientesModel')->orderBy('id', 'asc')->findAll();
                $estado = model('estadoModel')->findAll();
                $regimen = model('regimenModel')->select('*')->find();
                $tipo_cliente = model('tipoClienteModel')->select('*')->find();
                $clasificacion_cliente = model('clasificacionClienteModel')->select('*')->find();
                $departamento = model('departamentoModel')->select('*')->where('idpais', 49)->find();
                $id_departamento_empresa = model('empresaModel')->select('iddepartamento')->first();
                $id_ciudad_empresa = model('empresaModel')->select('idciudad')->first();
                $ciudad = model('municipiosModel')->select('nombreciudad')->where('idciudad', $id_ciudad_empresa['idciudad'])->first();
                $id_regimen_no_responsable_iva = model('regimenModel')->select('idregimen')->where('nombreregimen', 'NO RESPONSABLE DE IVA')->first();
                $lista_precios = model('cajaModel')->select('requiere_lista_de_precios')->first();
                return view('factura_pos/factura_pos_sin_productos', [
                    "clientes" => $clientes,
                    "estado" => $estado,
                    "regimen" => $regimen,
                    "tipo_cliente" => $tipo_cliente,
                    "clasificacion_cliente" => $clasificacion_cliente,
                    "departamentos" => $departamento,
                    "tiene_producto" => 0,
                    "valor_total" => 0,
                    "id_departamento" => $id_departamento_empresa['iddepartamento'],
                    "id_ciudad" => $id_ciudad_empresa['idciudad'],
                    "ciudad" => $ciudad['nombreciudad'],
                    "id_regimen" => 2,
                    "apertura" => 0,
                    "lista_precios" => $lista_precios['requiere_lista_de_precios'],
                    "caja_general" => 2
                ]);
            }
        }
    }

    function consulta_general()
    {
        $estado_caja = "";
        $ingresos_efectivo = "";
        $ingresos_electronicos = "";
        $total_ingresos = "";
        $cierre = "";
        $devoluciones = "";
        $diferencia = "";
        $fecha_cierre = "";

        $ultimo_id = model('movimientosCajaGeneralModel')->selectMax('id')->first();
        $fecha_apertura = model('movimientosCajaGeneralModel')->select('fecha_apertura')->where('id', $ultimo_id['id'])->first();
        $hora_apertura = model('movimientosCajaGeneralModel')->select('hora_apertura')->where('id', $ultimo_id['id'])->first();

        if (empty($ultimo_id['id'])) {

            $session = session();
            $session->setFlashdata('iconoMensaje', 'info');
            return redirect()->to(base_url('caja_general/apertura_general'))->with('mensaje', 'No hay movientos asociados a la caja general');
        } else if (!empty($ultimo_id['id'])) {
            $get_fechacierre_valorcierre = model('cajaGeneralModel')->get_fechacierre_valorcierre($ultimo_id['id']);

            if (empty($get_fechacierre_valorcierre[0]['fecha_cierre']) && empty($get_fechacierre_valorcierre[0]['valor_cierre'])) {

                $estado = 'ABIERTA';
                $ingresos_efectivo = model('movimientosCajaGeneralModel')->efectivo($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d'));
                $ingresos_transaccion = model('movimientosCajaGeneralModel')->transaccion($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d'));

                $total_ingresos = model('movimientosCajaGeneralModel')->total_ingresos($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d'));
                $cierre = 0;

                $devoluciones = model('devolucionModel')->devoluciones_total($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d') . " " . "23:59:00");

                $retiros = model('retiroModel')->total_retiros($fecha_apertura['fecha_apertura'], date('Y-m-d'));
                $diferencia = 0;
                $fecha_cierre = "POR DEFINIR";
            }
            if (!empty($get_fechacierre_valorcierre[0]['fecha_cierre']) && !empty($get_fechacierre_valorcierre[0]['valor_cierre'])) {
                $estado = 'CERRADA';
                $fecha_cierre = model('movimientosCajaGeneralModel')->select('fecha_cierre')->where('id', $ultimo_id['id'])->first();
                $ingresos_efectivo = model('movimientosCajaGeneralModel')->efectivo_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
                $ingresos_transaccion = model('movimientosCajaGeneralModel')->transaccion_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
                $total_ingresos = model('movimientosCajaGeneralModel')->total_ingresos_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
                $devoluciones = model('devolucionModel')->devoluciones_total_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
                $retiros = model('retiroModel')->total_retiros($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
                $cierr = model('movimientosCajaGeneralModel')->select('valor_cierre')->where('id', $ultimo_id['id'])->first();
                $cierre = $cierr['valor_cierre'];
                $diferencia = $cierre - ($ingresos_efectivo[0]['efectivo'] + $ingresos_transaccion[0]['transaccion']);
                $fecha_cierre = $fecha_cierre['fecha_cierre'];
            }


            $valor_apertura = model('movimientosCajaGeneralModel')->select('valor_apertura')->where('id', $ultimo_id['id'])->first();

            return view('caja_general/consultas_caja_general', [
                'estado' => $estado,
                'fecha_apertura' => $fecha_apertura['fecha_apertura'],
                'valor_apertura' => number_format($valor_apertura['valor_apertura'], 0, ",", "."),
                'ingresos_efectivo' => number_format($ingresos_efectivo[0]['efectivo'], 0, ",", "."),
                'ingresos_transaccion' => number_format($ingresos_transaccion[0]['transaccion'], 0, ",", "."),
                'total_ingresos' => number_format($ingresos_efectivo[0]['efectivo'] + $ingresos_transaccion[0]['transaccion'], 0, ",", "."),
                'cierre' => number_format($cierre, 0, ",", "."),
                'devoluciones' => number_format($devoluciones[0]['total_devoluciones'], 0, ",", "."),
                'retiros' => number_format($retiros[0]['total'], 0, ",", "."),
                'retiros_devoluciones' => number_format($devoluciones[0]['total_devoluciones'] + $retiros[0]['total'], 0, ",", "."),
                'saldo_caja' => number_format(($ingresos_efectivo[0]['efectivo'] + $ingresos_transaccion[0]['transaccion'] + $valor_apertura['valor_apertura']) - ($devoluciones[0]['total_devoluciones'] + $retiros[0]['total']), 0, ",", "."),
                'diferencia' => number_format($diferencia, 0, ",", "."),
                'id_apertura' => $ultimo_id['id'],
                'fecha_cierre' => $fecha_cierre
            ]);
        }
    }

    function total_ingresos()
    {

        //$id_apertura = 1;
        $id_apertura = $this->request->getPost('id_apertura');
        $fecha_de_cierre = "";
        $fecha_apertura = model('movimientosCajaGeneralModel')->select('fecha_apertura')->where('id', $id_apertura)->first();

        $fecha_cierre = model('movimientosCajaGeneralModel')->select('fecha_cierre')->where('id', $id_apertura)->first();
        if (empty($fecha_cierre['fecha_cierre'])) {
            $fecha_de_cierre = date('Y-m-d');
        }
        if (!empty($fecha_cierre['fecha_cierre'])) {
            $fecha_de_cierre = $fecha_cierre['fecha_cierre'];
        }


        $movimientos = model('movimientosCajaGeneralModel')->consultar_total_ingresos_cierre($fecha_apertura['fecha_apertura'], $fecha_de_cierre);
        $total = model('movimientosCajaGeneralModel')->total_cierre($fecha_apertura['fecha_apertura'], $fecha_de_cierre);

        $returnData = array(
            "resultado" => 1,
            "movimientos" => view('caja_general/total_ingresos', [
                'movimientos' => $movimientos,
            ]),
            "periodo" => "PERIODO: DEL " . $fecha_apertura['fecha_apertura'] . " AL " . $fecha_de_cierre,
            "total" => "TOTAL " . "$" . number_format($total[0]['total'], 0, ",", ".")
        );
        echo  json_encode($returnData);
    }
    public function imprimir_movimiento_caja_general()
    {

        $datos_empresa = model('empresaModel')->datosEmpresa();
        $connector = new WindowsPrintConnector('FACTURACION');
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
        $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento'] . "\n");
        $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
        $printer->text("\n");

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text("** APERTURA Y CIERRE DE CAJA GENERAL **\n");
        $printer->text("\n");


        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setTextSize(1, 1);

        $id_apertura = $this->request->getPost('id_apertura');
        $fecha_de_cierre = "";
        $fecha_apertura = model('movimientosCajaGeneralModel')->select('fecha_apertura')->where('id', $id_apertura)->first();

        $fecha_cierre = model('movimientosCajaGeneralModel')->select('fecha_cierre')->where('id', $id_apertura)->first();
        if (empty($fecha_cierre['fecha_cierre'])) {
            $fecha_de_cierre = date('Y-m-d');
        }
        if (!empty($fecha_cierre['fecha_cierre'])) {
            $fecha_de_cierre = $fecha_cierre['fecha_cierre'];
        }


        $printer->setJustification(Printer::JUSTIFY_LEFT);

        $valor_apertura = model('movimientosCajaGeneralModel')->select('valor_apertura')->where('id', $id_apertura)->first();
        $ingresos_efectivo = model('movimientosCajaGeneralModel')->imprimir_mov_efectivo($fecha_apertura['fecha_apertura'], $fecha_de_cierre);
        $ingresos_transaccion = model('movimientosCajaGeneralModel')->imprimir_mov_transaccion($fecha_apertura['fecha_apertura'], $fecha_de_cierre);
        $cierre_efectivo = model('movimientosCajaGeneralModel')->select('valor_cierre')->where('id', $id_apertura)->first();
        $retiros = model('retiroModel')->total_retiros($fecha_apertura['fecha_apertura'], $fecha_de_cierre);
        $devoluciones = model('devolucionModel')->devoluciones_total_cierre($fecha_apertura['fecha_apertura'], $fecha_de_cierre);
        $total_ingresos = model('movimientosCajaGeneralModel')->total_ingresos_cierre($fecha_apertura['fecha_apertura'], $fecha_de_cierre);
        $cierr = model('movimientosCajaGeneralModel')->select('valor_cierre')->where('id', $id_apertura)->first();
        $cierre = $cierr['valor_cierre'];
        $saldo = number_format(($ingresos_transaccion[0]['transaccion'] + $ingresos_efectivo[0]['efectivo'] + $valor_apertura['valor_apertura']) - ($devoluciones[0]['total_devoluciones'] + $retiros[0]['total']), 0, ",", ".");
        $diferencia = $cierre - $total_ingresos[0]['total'];
        $printer->text("APERTURA:             " . "$" . number_format($valor_apertura['valor_apertura'], 0, ",", ".") . "\n");
        $printer->text("INGRESOS EFECTIVO:    " . "$" . number_format($ingresos_efectivo[0]['efectivo'], 0, ",", ".") . "\n");
        $printer->text("INGRESOS TRANSACCION: " . "$" . number_format($ingresos_transaccion[0]['transaccion'], 0, ",", ".") . "\n");
        $printer->text("TOTAL INGRESOS:       " . "$" . number_format(($ingresos_transaccion[0]['transaccion'] + $ingresos_efectivo[0]['efectivo']), 0, ",", ".") . "\n");
        $printer->text("CIERRE EFECTIVO:      " . "$" . number_format($cierre_efectivo['valor_cierre'], 0, ",", ".") . "\n");
        $printer->text("RETIROS:              " . "$" . number_format($retiros[0]['total'], 0, ",", ".") . "\n");
        $printer->text("DEVOLUCIONES:         " . "$" . number_format($devoluciones[0]['total_devoluciones'], 0, ",", ".") . "\n");
        $printer->text("RETIROS+DEVOLUCIONES: " . "$" . number_format(($devoluciones[0]['total_devoluciones'] + $retiros[0]['total']), 0, ",", ".") . "\n");
        $printer->text("DEBE TENER EN CAJA :  " . "$" . $saldo . "\n");
        $printer->text("DIFERENCIA :          " . "$" . number_format($diferencia, 0, ",", ".") . "\n");

        $printer->setJustification(Printer::JUSTIFY_RIGHT);

        $printer->text("-----------------------------------------------" . "\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text("SOFTWARE DFPYME-INTREDETE" . "\n");
        $printer->text("INTREDETE" . "\n");
        $printer->text("-----------------------------------------------" . "\n");


        $printer->feed(1);
        $printer->cut();

        $printer->close();

        $returnData = array(
            "resultado" => 1, //Falta plata 
            "tabla" => view('factura_pos/tabla_reset_factura')
        );
        echo  json_encode($returnData);
    }

    function ver_retiros()
    {

        $id_apertura = $this->request->getPost('id_apertura');
        //$id_apertura = 1;
        $fecha_de_cierre = "";
        $fecha_apertura = model('movimientosCajaGeneralModel')->select('fecha_apertura')->where('id', $id_apertura)->first();

        $fecha_cierre = model('movimientosCajaGeneralModel')->select('fecha_cierre')->where('id', $id_apertura)->first();
        if (empty($fecha_cierre['fecha_cierre'])) {
            $fecha_de_cierre = date('Y-m-d');
        }
        if (!empty($fecha_cierre['fecha_cierre'])) {
            $fecha_de_cierre = $fecha_cierre['fecha_cierre'];
        }


        $retiros = model('retiroModel')->retiros_general($fecha_apertura['fecha_apertura'], $fecha_de_cierre);
        $total = model('retiroFormaPagoModel')->retiros_general($fecha_apertura['fecha_apertura'], $fecha_de_cierre);

        $returnData = array(
            "resultado" => 1,
            "retiros" => view('caja_general/retiros', [
                'retiros' => $retiros,
            ]),
            "periodo" => "RETIROS DE DINERO EN EL PERIODO: DEL " . $fecha_apertura['fecha_apertura'] . " AL " . $fecha_de_cierre,
            "total" => "TOTAL " . "$" . number_format($total[0]['total'], 0, ",", ".")
        );
        echo  json_encode($returnData);
    }

    function editar_apertura()
    {
        $id_apertura = $this->request->getPost('id_apertura');
        $valor_apertura = model('movimientosCajaGeneralModel')->select('valor_apertura')->where('id', $id_apertura)->first();
        $returnData = array(
            "resultado" => 1,
            "valor_apertura" => number_format($valor_apertura['valor_apertura'], 0, ",", "."),
            "id_apertura" => $id_apertura
        );
        echo  json_encode($returnData);
    }

    function actualizar_valor_apertura()
    {

        $id_apertura = $this->request->getPost('id_apertura');
        $valor_apertura = $this->request->getPost('valor_apertura');

        $data = [
            'valor_apertura' => str_replace(".", "", $valor_apertura)
        ];

        $model = model('movimientosCajaGeneralModel');
        $mesas = $model->set($data);
        $mesas = $model->where('id', $id_apertura);
        $mesas = $model->update();

        $estado_caja = "";
        $ingresos_efectivo = "";
        $ingresos_electronicos = "";
        $total_ingresos = "";
        $cierre = "";
        $devoluciones = "";
        $diferencia = "";
        $fecha_cierre = "";

        $ultimo_id = $id_apertura;
        $fecha_apertura = model('movimientosCajaGeneralModel')->select('fecha_apertura')->where('id', $ultimo_id)->first();
        $hora_apertura = model('movimientosCajaGeneralModel')->select('hora_apertura')->where('id', $ultimo_id)->first();


        $get_fechacierre_valorcierre = model('cajaGeneralModel')->get_fechacierre_valorcierre($ultimo_id);
        if (empty($get_fechacierre_valorcierre[0]['fecha_cierre']) && empty($get_fechacierre_valorcierre[0]['valor_cierre'])) {
            $estado = 'ABIERTA';
            $ingresos_efectivo = model('movimientosCajaGeneralModel')->efectivo($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d'));
            $ingresos_transaccion = model('movimientosCajaGeneralModel')->transaccion($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d'));
            $total_ingresos = model('movimientosCajaGeneralModel')->total_ingresos($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d'));
            $cierre = 0;
            $devoluciones = model('devolucionModel')->devoluciones_total($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d') . " " . "23:59:00");
            $retiros = model('retiroModel')->total_retiros($fecha_apertura['fecha_apertura'], date('Y-m-d'));
            $diferencia = 0;
            $fecha_cierre = "POR DEFINIR";
        }
        if (!empty($get_fechacierre_valorcierre[0]['fecha_cierre']) && !empty($get_fechacierre_valorcierre[0]['valor_cierre'])) {
            $estado = 'CERRADA';
            $fecha_cierre = model('movimientosCajaGeneralModel')->select('fecha_cierre')->where('id', $ultimo_id)->first();
            $ingresos_efectivo = model('movimientosCajaGeneralModel')->efectivo_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $ingresos_transaccion = model('movimientosCajaGeneralModel')->transaccion_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $total_ingresos = model('movimientosCajaGeneralModel')->total_ingresos_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $devoluciones = model('devolucionModel')->devoluciones_total_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $retiros = model('retiroModel')->total_retiros($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $cierr = model('movimientosCajaGeneralModel')->select('valor_cierre')->where('id', $ultimo_id)->first();
            $cierre = $cierr['valor_cierre'];
            $diferencia = $cierre - $total_ingresos[0]['total'];
            $fecha_cierre = $fecha_cierre['fecha_cierre'];
        }

        $valor_apertura = model('movimientosCajaGeneralModel')->select('valor_apertura')->where('id', $ultimo_id)->first();


        $returnData = array(
            "resultado" => 1,
            "datos" =>  view('caja_general/datos_consultas_caja_general', [
                'estado' => $estado,
                'fecha_apertura' => $fecha_apertura['fecha_apertura'],
                'valor_apertura' => number_format($valor_apertura['valor_apertura'], 0, ",", "."),
                'ingresos_efectivo' => number_format($ingresos_efectivo[0]['efectivo'], 0, ",", "."),
                'ingresos_transaccion' => number_format($ingresos_transaccion[0]['transaccion'], 0, ",", "."),
                'total_ingresos' => number_format($ingresos_efectivo[0]['efectivo'] + $ingresos_transaccion[0]['transaccion'], 0, ",", "."),
                'cierre' => number_format($cierre, 0, ",", "."),
                'devoluciones' => number_format($devoluciones[0]['total_devoluciones'], 0, ",", "."),
                'retiros' => number_format($retiros[0]['total'], 0, ",", "."),
                'retiros_devoluciones' => number_format($devoluciones[0]['total_devoluciones'] + $retiros[0]['total'], 0, ",", "."),
                'saldo_caja' => number_format(($ingresos_efectivo[0]['efectivo'] + $ingresos_transaccion[0]['transaccion'] + $valor_apertura['valor_apertura']) - ($devoluciones[0]['total_devoluciones'] + $retiros[0]['total']), 0, ",", "."),
                'diferencia' => number_format($diferencia, 0, ",", "."),
                'id_apertura' => $ultimo_id,
                'fecha_cierre' => $fecha_cierre
            ])
        );
        echo  json_encode($returnData);
    }


    function validar_cierre()
    {
        $id_apertura = $this->request->getPost('id_apertura');
        $fecha_cierre = model('movimientosCajaGeneralModel')->select('fecha_cierre')->where('id', $id_apertura)->first();
        $valor_cierre = model('movimientosCajaGeneralModel')->select('valor_cierre')->where('id', $id_apertura)->first();

        if (empty($fecha_cierre) && empty($valor_cierre)) {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        } else if (!empty($fecha_cierre) && !empty($valor_cierre)) {

            $returnData = array(
                "resultado" => 1,
                "valor_cierre" => number_format($valor_cierre['valor_cierre'], 0, ",", "."),
                "id_apertura" => $id_apertura
            );
            echo  json_encode($returnData);
        }
    }


    function actualizar_valor_cierre()
    {

        $id_apertura = $this->request->getPost('id_apertura');
        $valor_cierre = $this->request->getPost('valor_cierre');

        $data = [
            'valor_cierre' => str_replace(".", "", $valor_cierre)
        ];

        $model = model('movimientosCajaGeneralModel');
        $mesas = $model->set($data);
        $mesas = $model->where('id', $id_apertura);
        $mesas = $model->update();

        $estado_caja = "";
        $ingresos_efectivo = "";
        $ingresos_electronicos = "";
        $total_ingresos = "";
        $cierre = "";
        $devoluciones = "";
        $diferencia = "";
        $fecha_cierre = "";

        $ultimo_id = $id_apertura;
        $fecha_apertura = model('movimientosCajaGeneralModel')->select('fecha_apertura')->where('id', $ultimo_id)->first();
        $hora_apertura = model('movimientosCajaGeneralModel')->select('hora_apertura')->where('id', $ultimo_id)->first();


        $get_fechacierre_valorcierre = model('cajaGeneralModel')->get_fechacierre_valorcierre($ultimo_id);
        if (empty($get_fechacierre_valorcierre[0]['fecha_cierre']) && empty($get_fechacierre_valorcierre[0]['valor_cierre'])) {
            $estado = 'ABIERTA';
            $ingresos_efectivo = model('movimientosCajaGeneralModel')->efectivo($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d'));
            $ingresos_transaccion = model('movimientosCajaGeneralModel')->transaccion($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d'));
            $total_ingresos = model('movimientosCajaGeneralModel')->total_ingresos($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d'));
            $cierre = 0;
            $devoluciones = model('devolucionModel')->devoluciones_total($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d') . " " . "23:59:00");
            $retiros = model('retiroModel')->total_retiros($fecha_apertura['fecha_apertura'], date('Y-m-d'));
            $diferencia = 0;
            $fecha_cierre = "POR DEFINIR";
        }
        if (!empty($get_fechacierre_valorcierre[0]['fecha_cierre']) && !empty($get_fechacierre_valorcierre[0]['valor_cierre'])) {
            $estado = 'CERRADA';
            $fecha_cierre = model('movimientosCajaGeneralModel')->select('fecha_cierre')->where('id', $ultimo_id)->first();
            $ingresos_efectivo = model('movimientosCajaGeneralModel')->efectivo_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $ingresos_transaccion = model('movimientosCajaGeneralModel')->transaccion_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $total_ingresos = model('movimientosCajaGeneralModel')->total_ingresos_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $devoluciones = model('devolucionModel')->devoluciones_total_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $retiros = model('retiroModel')->total_retiros($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $cierr = model('movimientosCajaGeneralModel')->select('valor_cierre')->where('id', $ultimo_id)->first();
            $cierre = $cierr['valor_cierre'];
            $diferencia = $cierre - $total_ingresos[0]['total'];
            $fecha_cierre = $fecha_cierre['fecha_cierre'];
        }

        $valor_apertura = model('movimientosCajaGeneralModel')->select('valor_apertura')->where('id', $ultimo_id)->first();


        $returnData = array(
            "resultado" => 1,
            "datos" =>  view('caja_general/datos_consultas_caja_general', [
                'estado' => $estado,
                'fecha_apertura' => $fecha_apertura['fecha_apertura'],
                'valor_apertura' => number_format($valor_apertura['valor_apertura'], 0, ",", "."),
                'ingresos_efectivo' => number_format($ingresos_efectivo[0]['efectivo'], 0, ",", "."),
                'ingresos_transaccion' => number_format($ingresos_transaccion[0]['transaccion'], 0, ",", "."),
                'total_ingresos' => number_format($ingresos_efectivo[0]['efectivo'] + $ingresos_transaccion[0]['transaccion'], 0, ",", "."),
                'cierre' => number_format($cierre, 0, ",", "."),
                'devoluciones' => number_format($devoluciones[0]['total_devoluciones'], 0, ",", "."),
                'retiros' => number_format($retiros[0]['total'], 0, ",", "."),
                'retiros_devoluciones' => number_format($devoluciones[0]['total_devoluciones'] + $retiros[0]['total'], 0, ",", "."),
                'saldo_caja' => number_format(($ingresos_efectivo[0]['efectivo'] + $ingresos_transaccion[0]['transaccion'] + $valor_apertura['valor_apertura']) - ($devoluciones[0]['total_devoluciones'] + $retiros[0]['total']), 0, ",", "."),
                'diferencia' => number_format($diferencia, 0, ",", "."),
                'id_apertura' => $ultimo_id,
                'fecha_cierre' => $fecha_cierre
            ])
        );
        echo  json_encode($returnData);
    }

    function todos_los_cierres_caja_general()
    {

        $estado_caja = "";
        $ingresos_efectivo = "";
        $ingresos_electronicos = "";
        $total_ingresos = "";
        $cierre = "";
        $devoluciones = "";
        $diferencia = "";
        $fecha_cierre = "";

        $ultimo_id = model('movimientosCajaGeneralModel')->selectMax('id')->first();
        $fecha_apertura = model('movimientosCajaGeneralModel')->select('fecha_apertura')->where('id', $ultimo_id['id'])->first();
        $hora_apertura = model('movimientosCajaGeneralModel')->select('hora_apertura')->where('id', $ultimo_id['id'])->first();


        $get_fechacierre_valorcierre = model('cajaGeneralModel')->get_fechacierre_valorcierre($ultimo_id['id']);
        if (empty($get_fechacierre_valorcierre[0]['fecha_cierre']) && empty($get_fechacierre_valorcierre[0]['valor_cierre'])) {
            $estado = 'ABIERTA';
            $ingresos_efectivo = model('movimientosCajaGeneralModel')->efectivo($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d'));
            $ingresos_transaccion = model('movimientosCajaGeneralModel')->transaccion($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d'));
            $total_ingresos = model('movimientosCajaGeneralModel')->total_ingresos($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d'));
            $cierre = 0;
            $devoluciones = model('devolucionModel')->devoluciones_total($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d') . " " . "23:59:00");
            $retiros = model('retiroModel')->total_retiros($fecha_apertura['fecha_apertura'], date('Y-m-d'));
            $diferencia = 0;
            $fecha_cierre = "POR DEFINIR";
        }
        if (!empty($get_fechacierre_valorcierre[0]['fecha_cierre']) && !empty($get_fechacierre_valorcierre[0]['valor_cierre'])) {
            $estado = 'CERRADA';
            $fecha_cierre = model('movimientosCajaGeneralModel')->select('fecha_cierre')->where('id', $ultimo_id['id'])->first();
            $ingresos_efectivo = model('movimientosCajaGeneralModel')->efectivo_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $ingresos_transaccion = model('movimientosCajaGeneralModel')->transaccion_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $total_ingresos = model('movimientosCajaGeneralModel')->total_ingresos_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $devoluciones = model('devolucionModel')->devoluciones_total_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $retiros = model('retiroModel')->total_retiros($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $cierr = model('movimientosCajaGeneralModel')->select('valor_cierre')->where('id', $ultimo_id['id'])->first();
            $cierre = $cierr['valor_cierre'];
            $diferencia = $cierre - ($ingresos_efectivo[0]['efectivo'] + $ingresos_transaccion[0]['transaccion']);
            $fecha_cierre = $fecha_cierre['fecha_cierre'];
        }
        $valor_apertura = model('movimientosCajaGeneralModel')->select('valor_apertura')->where('id', $ultimo_id['id'])->first();

        $cierres = model('movimientosCajaGeneralModel')->orderBy('id', 'desc')->findAll();
        return view('caja_general/listado', [
            'cierres' => $cierres,
            'estado' => $estado,
            'fecha_apertura' => $fecha_apertura['fecha_apertura'],
            'valor_apertura' => number_format($valor_apertura['valor_apertura'], 0, ",", "."),
            'ingresos_efectivo' => number_format($ingresos_efectivo[0]['efectivo'], 0, ",", "."),
            'ingresos_transaccion' => number_format($ingresos_transaccion[0]['transaccion'], 0, ",", "."),
            'total_ingresos' => number_format($ingresos_efectivo[0]['efectivo'] + $ingresos_transaccion[0]['transaccion'], 0, ",", "."),
            'cierre' => number_format($cierre, 0, ",", "."),
            'devoluciones' => number_format($devoluciones[0]['total_devoluciones'], 0, ",", "."),
            'retiros' => number_format($retiros[0]['total'], 0, ",", "."),
            'retiros_devoluciones' => number_format($devoluciones[0]['total_devoluciones'] + $retiros[0]['total'], 0, ",", "."),
            'saldo_caja' => number_format(($ingresos_efectivo[0]['efectivo'] + $ingresos_transaccion[0]['transaccion'] + $valor_apertura['valor_apertura']) - ($devoluciones[0]['total_devoluciones'] + $retiros[0]['total']), 0, ",", "."),
            'diferencia' => number_format($diferencia, 0, ",", "."),
            'id_apertura' => $ultimo_id['id'],
            'fecha_cierre' => $fecha_cierre
        ]);
    }

    function consultar_movimiento()
    {
        $id_apertura = $this->request->getPost('id_apertura');

        $estado_caja = "";
        $ingresos_efectivo = "";
        $ingresos_electronicos = "";
        $total_ingresos = "";
        $cierre = "";
        $devoluciones = "";
        $diferencia = "";
        $fecha_cierre = "";

        // $ultimo_id = model('movimientosCajaGeneralModel')->selectMax('id')->first();
        $fecha_apertura = model('movimientosCajaGeneralModel')->select('fecha_apertura')->where('id', $id_apertura)->first();
        $hora_apertura = model('movimientosCajaGeneralModel')->select('hora_apertura')->where('id', $id_apertura)->first();


        $get_fechacierre_valorcierre = model('cajaGeneralModel')->get_fechacierre_valorcierre($id_apertura);
        if (empty($get_fechacierre_valorcierre[0]['fecha_cierre']) && empty($get_fechacierre_valorcierre[0]['valor_cierre'])) {
            $estado = 'ABIERTA';
            $ingresos_efectivo = model('movimientosCajaGeneralModel')->efectivo($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d'));
            $ingresos_transaccion = model('movimientosCajaGeneralModel')->transaccion($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d'));
            $total_ingresos = model('movimientosCajaGeneralModel')->total_ingresos($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d'));
            $cierre = 0;
            $devoluciones = model('devolucionModel')->devoluciones_total($fecha_apertura['fecha_apertura'] . " " . $hora_apertura['hora_apertura'], date('Y-m-d') . " " . "23:59:00");
            $retiros = model('retiroModel')->total_retiros($fecha_apertura['fecha_apertura'], date('Y-m-d'));
            $diferencia = 0;
            $fecha_cierre = "POR DEFINIR";
        }
        if (!empty($get_fechacierre_valorcierre[0]['fecha_cierre']) && !empty($get_fechacierre_valorcierre[0]['valor_cierre'])) {
            $estado = 'CERRADA';
            $fecha_cierre = model('movimientosCajaGeneralModel')->select('fecha_cierre')->where('id', $id_apertura)->first();
            $ingresos_efectivo = model('movimientosCajaGeneralModel')->efectivo_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $ingresos_transaccion = model('movimientosCajaGeneralModel')->transaccion_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $total_ingresos = model('movimientosCajaGeneralModel')->total_ingresos_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $devoluciones = model('devolucionModel')->devoluciones_total_cierre($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $retiros = model('retiroModel')->total_retiros($fecha_apertura['fecha_apertura'], $fecha_cierre['fecha_cierre']);
            $cierr = model('movimientosCajaGeneralModel')->select('valor_cierre')->where('id', $id_apertura)->first();
            $cierre = $cierr['valor_cierre'];
            $diferencia = $cierre - ($ingresos_efectivo[0]['efectivo'] + $ingresos_transaccion[0]['transaccion']);
            $fecha_cierre = $fecha_cierre['fecha_cierre'];
        }


        $valor_apertura = model('movimientosCajaGeneralModel')->select('valor_apertura')->where('id', $id_apertura)->first();

        $datos = view('caja_general/consultas_caja_fecha', [
            'estado' => $estado,
            'fecha_apertura' => $fecha_apertura['fecha_apertura'],
            'valor_apertura' => number_format($valor_apertura['valor_apertura'], 0, ",", "."),
            'ingresos_efectivo' => number_format($ingresos_efectivo[0]['efectivo'], 0, ",", "."),
            'ingresos_transaccion' => number_format($ingresos_transaccion[0]['transaccion'], 0, ",", "."),
            'total_ingresos' => number_format($ingresos_efectivo[0]['efectivo'] + $ingresos_transaccion[0]['transaccion'], 0, ",", "."),
            'cierre' => number_format($cierre, 0, ",", "."),
            'devoluciones' => number_format($devoluciones[0]['total_devoluciones'], 0, ",", "."),
            'retiros' => number_format($retiros[0]['total'], 0, ",", "."),
            'retiros_devoluciones' => number_format($devoluciones[0]['total_devoluciones'] + $retiros[0]['total'], 0, ",", "."),
            'saldo_caja' => number_format(($ingresos_efectivo[0]['efectivo'] + $ingresos_transaccion[0]['transaccion'] + $valor_apertura['valor_apertura']) - ($devoluciones[0]['total_devoluciones'] + $retiros[0]['total']), 0, ",", "."),
            'diferencia' => number_format($diferencia, 0, ",", "."),
            'id_apertura' => $id_apertura,
            'fecha_cierre' => $fecha_cierre
        ]);

        $returnData = array(
            "resultado" => 1,
            "datos" => $datos,
            "id_apertura" => $id_apertura
        );
        echo  json_encode($returnData);
    }
}
