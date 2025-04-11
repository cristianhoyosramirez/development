<?php

namespace App\Controllers\caja;

use \DateTime;
use \DateTimeZone;

require APPPATH . "Controllers/mike42/autoload.php";

use App\Controllers\BaseController;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use App\Libraries\impresion;

class cajaController extends BaseController
{

    public function __construct(...$params)
    {
    }


    public function apertura()
    {
        $caja = model('cajaModel')->select('idcaja');
        $caja = model('cajaModel')->select('numerocaja')->find();

        return view('caja/apertura', [
            'caja' => $caja
        ]);
    }

    function generar_apertura()
    {

        if (!$this->validate([
            'fecha_apertura_caja' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'numero_caja' => [
                'rules' => 'required|is_unique[apertura_registro.idcaja]',
                'errors' => [
                    'required' => 'Dato necesario',
                    'is_unique' => 'Caja ya tiene apertura',
                ]
            ],
            'apertura_caja' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],

        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fecha = DateTime::createFromFormat('U.u', microtime(TRUE));
        $fecha->setTimeZone(new DateTimeZone('America/Bogota'));
        $fecha_y_hora = $fecha->format('Y-m-d H:i:s.u');

        $fecha_apertura = $_REQUEST['fecha_apertura_caja'];

        $data = [
            'fecha' => $_REQUEST['fecha_apertura_caja'],
            //'hora' => date('h:i:s-H'),
            'hora' => date("H:i:s"),
            'idcaja' => $_REQUEST['numero_caja'],
            'idturno' => 1,
            'idusuario' => $_REQUEST['usuario_apertura'],
            //'valor' => $numero = str_replace('.', '', $_REQUEST['apertura_caja']),
            'valor' => $numero = str_replace([',', '.'], '', $_REQUEST['apertura_caja']),
            'fecha_y_hora_apertura' => $fecha_y_hora
        ];

        $apertura = model('aperturaModel')->insert($data);
        $id_apertura = model('aperturaModel')->insertID();
        $apertura_registro = [
            'idcaja' => 1,
            'numero' => $id_apertura

        ];
        $apertura_registro = model('aperturaRegistroModel')->insert($apertura_registro);

        // $exite_fecha = model('consecutivoInformeModel')->select('fecha')->where('fecha', $_REQUEST['fecha_apertura_caja'])->first();


        //if (empty($exite_fecha['fecha'])) {

        //$id = model('consecutivoInformeModel')->selectMax('id')->find();

        //   $numero = model('consecutivoInformeModel')->select('numero')->where('id', $id[0]['id'])->first();

        /*    if (!empty($numero)) {

            $consecutivo = $numero['numero'] + 1;
        }
        if (empty($numero)) {
            $temp_consecutivo = model('cajaModel')->select('consecutivo')->first();
            $consecutivo = $temp_consecutivo['consecutivo'] + 1;
        } */

        $data = [
            'fecha' => $fecha_apertura,
            'idcaja' => 1,
            'id_apertura' => $id_apertura

        ];

        $insert = model('consecutivoInformeModel')->insert($data);
        //}

        $session = session();
        $session->setFlashdata('iconoMensaje', 'success');
        return redirect()->to(base_url('pedidos/mesas'))->with('mensaje', 'Apertura de caja éxitoso ');
    }

    function cierre()
    {
        $caja = model('cajaModel')->select('idcaja');
        $caja = model('cajaModel')->select('numerocaja')->find();
        return view('caja/cierre', [
            'caja' => $caja
        ]);
    }

    function generar_cierre()
    {
        $id_apertura = model('aperturaRegistroModel')->select('numero')->first();
        $caja = $_REQUEST['numero_caja'];
        $temp_efectivo = $_REQUEST['efectivoFormat'];
        $temp_transaccion = $_REQUEST['transaccionFormat'];
        $efectivo = "";

        if (empty($temp_efectivo)) {
            $efectivo = 0;
        } else  if (!empty($temp_efectivo)) {
            $efectivo = $temp_efectivo;
        }
        if (empty($temp_transaccion)) {
            $transaccion = 0;
        } else  if (!empty($temp_transaccion)) {
            $transaccion = $temp_transaccion;
        }




        $id_usuario = $_REQUEST['usuario_cierre'];

        /*   $efectivo = 50.000;
        $transaccion = 0;
        $caja = 1;
        $id_usuario = 8; */

        $fecha = DateTime::createFromFormat('U.u', microtime(TRUE));
        $fecha->setTimeZone(new DateTimeZone('America/Bogota'));
        $fecha_y_hora = $fecha->format('Y-m-d H:i:s.u');

        if (!empty($id_apertura['numero'])) {

            $data = [
                'idapertura' => $id_apertura['numero'],
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s'),
                'idcaja' => $caja,
                'idturno' => 1,
                'idusuario' => $id_usuario,
                'fecha_y_hora_cierre' => $fecha_y_hora
            ];

            $cierre = model('cierreModel')->insert($data);

            if ($cierre) {
                $borrar_registro_apertura = model('aperturaRegistroModel')->where('idcaja', 1);
                $borrar_registro_apertura->delete();

                $id_cierre = model('cierreModel')->insertID;
                if ($efectivo > 0 && $transaccion > 0) {
                    $cierre_forma_pago_efectivo = [
                        'idcierre' => $id_cierre,
                        'idpago' => 1,
                        //'valor' => $efectivo,
                        // 'valor' => $efectivo,
                        'valor' => $numero = str_replace('.', '', $efectivo)
                    ];
                    $cierre_efectivo = model('cierreFormaPagoModel')->insert($cierre_forma_pago_efectivo);

                    $cierre_forma_pago_transaccion = [
                        'idcierre' => $id_cierre,
                        'idpago' => 4,
                        //'valor' => $transaccion,
                        'valor' => $numero = str_replace('.', '', $transaccion)
                    ];
                    $cierre_transaccion = model('cierreFormaPagoModel')->insert($cierre_forma_pago_transaccion);
                    if ($cierre_efectivo and $cierre_transaccion) {
                        $returnData = array(
                            "id_cierre" => $id_cierre,
                            "resultado" => 1,

                        );
                        echo  json_encode($returnData);
                    }
                }

                if ($efectivo == 0 && $transaccion > 0) {
                    $cierre_forma_pago_efectivo = [
                        'idcierre' => $id_cierre,
                        'idpago' => 1,
                        //'valor' => $efectivo,
                        // 'valor' => $efectivo,
                        'valor' => 0
                    ];
                    $cierre_efectivo = model('cierreFormaPagoModel')->insert($cierre_forma_pago_efectivo);

                    $cierre_forma_pago_transaccion = [
                        'idcierre' => $id_cierre,
                        'idpago' => 4,
                        //'valor' => $transaccion,
                        'valor' => $numero = str_replace('.', '', $transaccion)
                    ];
                    $cierre_transaccion = model('cierreFormaPagoModel')->insert($cierre_forma_pago_transaccion);
                    if ($cierre_efectivo and $cierre_transaccion) {
                        $returnData = array(
                            "id_cierre" => $id_cierre,
                            "resultado" => 1,

                        );
                        echo  json_encode($returnData);
                    }
                }

                if ($efectivo > 0 && $transaccion == 0) {
                    $cierre_forma_pago_efectivo = [
                        'idcierre' => $id_cierre,
                        'idpago' => 1,
                        //'valor' => $efectivo,
                        // 'valor' => $efectivo,
                        'valor' => $numero = str_replace('.', '', $efectivo)
                    ];
                    $cierre_efectivo = model('cierreFormaPagoModel')->insert($cierre_forma_pago_efectivo);

                    $cierre_forma_pago_transaccion = [
                        'idcierre' => $id_cierre,
                        'idpago' => 4,
                        //'valor' => $transaccion,
                        'valor' => 0
                    ];
                    $cierre_transaccion = model('cierreFormaPagoModel')->insert($cierre_forma_pago_transaccion);
                    if ($cierre_efectivo and $cierre_transaccion) {
                        $returnData = array(
                            "id_cierre" => $id_cierre,
                            "resultado" => 1,

                        );
                        echo  json_encode($returnData);
                    }
                }
                if ($efectivo == 0 && $transaccion == 0) {
                    $cierre_forma_pago_efectivo = [
                        'idcierre' => $id_cierre,
                        'idpago' => 1,
                        //'valor' => $efectivo,
                        // 'valor' => $efectivo,
                        'valor' => 0
                    ];
                    $cierre_efectivo = model('cierreFormaPagoModel')->insert($cierre_forma_pago_efectivo);

                    $cierre_forma_pago_transaccion = [
                        'idcierre' => $id_cierre,
                        'idpago' => 4,
                        //'valor' => $transaccion,
                        'valor' => $numero = 0
                    ];
                    $cierre_transaccion = model('cierreFormaPagoModel')->insert($cierre_forma_pago_transaccion);
                    if ($cierre_efectivo and $cierre_transaccion) {
                        $returnData = array(
                            "id_cierre" => $id_cierre,
                            "resultado" => 1,
                            "id_apertura" => $id_apertura['numero']

                        );
                        echo  json_encode($returnData);
                    }
                }
            }
        } else if (empty($id_apertura['numero'])) {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        }
    }

    /*     function imprimir_cierre()
    {
        $id_cierre = $_REQUEST['id_cierre'];
        $existe_cierre = model('cierreModel')->select('id')->where('id', $id_cierre)->first();
        if (!empty($existe_cierre['id'])) {
            $id_apert = model('cierreModel')->select('idapertura')->where('id', $id_cierre)->first();

            $imp = new impresion();

            $impresion = $imp->imprimir_cuadre_caja($id_apert['idapertura']);

            $returnData = array(
                "resultado" => 1,
                "id_cierre"=>$id_cierre
            );
            echo  json_encode($returnData);
        }
    } */


    function imprimir_cierre()
    {
        $id_cierre = $_REQUEST['id_cierre'];
        $existe_cierre = model('cierreModel')->select('id')->where('id', $id_cierre)->first();
        if (!empty($existe_cierre['id'])) {
            $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();
            $datos_empresa = model('empresaModel')->datosEmpresa();

            $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

            $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
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
            $printer->text("COMPROBANTE DE CIERRE DE CAJA\n");

            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $cierre_efectivo = model('cierreFormaPagoModel')->cierre_efectivo($id_cierre);
            $cierre_transaccion = model('cierreFormaPagoModel')->cierre_transaccion($id_cierre);
            $fecha_cierre = model('cierreFormaPagoModel')->fecha_cierre($id_cierre);
            $hora_cierre = model('cierreFormaPagoModel')->hora_cierre($id_cierre);

            $printer->text("Fecha:  " . $fecha_cierre[0]['fecha']);
            $printer->text("\n");
            $printer->text("Hora:   " . $hora_cierre[0]['hora']);
            $printer->text("\n");

            $printer->text("Caja:   1 \n");
            $printer->text("Turno:  1 \n");
            $printer->text("\n");


            // $printer->text("Efectivo:     " . "$" . number_format($cierre_efectivo[0]['valor']));
            // $printer->text("\n");
            // $printer->text("Transaccion:  " . "$" . number_format($cierre_transaccion[0]['valor']));

            $formas_pago_cierre = model('cierreFormaPagoModel')->formas_pago_cierre($id_cierre);

            foreach ($formas_pago_cierre as $detalle) {

                $printer->text($detalle['nombreforma_pago'] . "          " . "$" . number_format($detalle['valor']) . "\n");
            }
            $total_cierre = model('cierreFormaPagoModel')->selectSum('valor')->where('idcierre', $id_cierre)->find();

            $printer->text("\n");
            $printer->text("\n");
            $printer->text("TOTAL CIERRE  " . "$" . number_format($total_cierre[0]['valor']));
            $printer->text("\n");
            $printer->text("\n");
            $printer->text("Firma:\n");
            $printer->text("\n");
            $printer->text("_______________________________________________\n");


            $printer->feed(1);
            $printer->cut();

            $printer->close();

            $returnData = array(

                "resultado" => 1,
                "id_cierre" => $id_cierre
            );
            echo  json_encode($returnData);
        }
    }

    function imprimir_movimiento_caja()
    {
        $ultimo_id_cierre = model('cierreModel')->selectMax('id')->first();

        $id_apert = model('cierreModel')->select('idapertura')->where('id', $ultimo_id_cierre['id'])->first();

        $id_apertura = $this->request->getPost('id_apertura');
        //$id_apertura = 26;

        $tiene_cierre = model('cierreModel')->select('id')->where('idapertura', $id_apertura)->first();
       

        if (!empty($tiene_cierre)) {
            $id_cierre = $tiene_cierre['id'];

            $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();
            $datos_empresa = model('empresaModel')->datosEmpresa();

            $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

            $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
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


            $fecha_cierre = model('cierreModel')->select('fecha')->where('id', $id_cierre)->first();
            $hora_cierre = model('cierreModel')->select('hora')->where('id', $id_cierre)->first();

            $id_apertura = model('cierreModel')->select('idapertura')->where('id', $id_cierre)->first();

            $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura['idapertura'])->first();
            $hora_apertura = model('aperturaModel')->select('hora')->where('id', $id_apertura['idapertura'])->first();

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("**CUADRE DE CAJA** \n");

            $printer->text("Fecha apertura:  " . $fecha_apertura['fecha'] . " " . date("g:i a", strtotime($hora_apertura['hora'])) . "\n");
            $printer->text("Fecha cierre:    " . $fecha_cierre['fecha'] . " " . date("g:i a", strtotime($hora_cierre['hora'])) . "\n");
            $printer->text("Caja N° : 1 \n");

            $printer->text("\n");

            $valor_apertura = model('aperturaModel')->select('valor')->where('id', $id_apertura['idapertura'])->first();
            $printer->text("VALOR APERTURA   : " . "$" . number_format($valor_apertura['valor'], 0, ",", ".") . "\n");

            $printer->text("\n");
            $printer->text("-----------------------------------------------\n ");
            $printer->text("INGRESOS \n ");
            $printer->text("-----------------------------------------------\n ");
            $printer->text("\n");
            $fecha_y_hora_apertura = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $id_apertura['idapertura'])->first();

            $fecha_y_hora_cierre = model('cierreModel')->select('fecha_y_hora_cierre')->where('id', $id_cierre)->first();

            //$ingresos_efectivo = model('facturaFormaPagoModel')->ingresos_efectivo($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            $ingresos_efectivo = model('pagosModel')->selectSum('efectivo')->where('id_apertura', $id_apertura)->findAll();
            //$ingresos_transaccion = model('facturaFormaPagoModel')->ingresos_transaccion($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            if (!empty($ingresos_efectivo[0]['efectivo'])) {
                $printer->text("Ingresos efectivo:      " . "$" . number_format($ingresos_efectivo[0]['efectivo'], 0, ",", ".") . "\n");
            }
            if (empty($ingresos_efectivo[0]['efectivo'])) {
                $printer->text("Ingresos efectivo:      " . "$0" . "\n");
            }
            if (!empty($ingresos_transaccion[0]['ingresos_transaccion'])) {
                $printer->text("Ingresos transacción: " . "$" . number_format($ingresos_transaccion[0]['ingresos_transaccion'], 0, ",", ".") . "\n");
            }
            if (empty($ingresos_transaccion[0]['ingresos_transaccion'])) {
                $printer->text("Ingresos transacción:   " . "$0" . "\n");
            }

            $total_ingresos = model('facturaFormaPagoModel')->total_ingresos($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);

            $printer->text("Total ingresos          " . "$" . number_format($total_ingresos[0]['total_ingresos'], 0, ",", ".") . "\n");


            $printer->text("\n");

            $printer->text("------------------------------------------------\n ");
            $printer->text("RETIROS \n");
            $printer->text("------------------------------------------------\n ");
            $retiros = model('retiroModel')->select('*')->where('id_apertura', $id_apertura['idapertura'])->findAll();

            $printer->text("\n");
            foreach ($retiros as $detalle) {
                $printer->text("FECHA: " . $detalle['fecha'] . " " . date("g:i a", strtotime($detalle['hora'])) . "\n");
                $concepto = model('retiroFormaPagoModel')->select('concepto')->where('idretiro', $detalle['id'])->first();
                $printer->text("CONCEPTO:" . $concepto['concepto'] . "\n");
                $valor = model('retiroFormaPagoModel')->select('valor')->where('idretiro', $detalle['id'])->first();
                $printer->text("VALOR:" . "$" . number_format($valor['valor'], 0, ",", ".") . "\n");
                $printer->text("\n");
            }



            $temp_retiros = 0;
            $total_retiros = 0;
            foreach ($retiros as $detalle) {
                $valor = model('retiroFormaPagoModel')->select('valor')->where('idretiro', $detalle['id'])->first();
                $temp_retiros = $temp_retiros + $valor['valor'];
                $total_retiros = $temp_retiros;
            }

            $printer->text("Total retiros: " . "$" . number_format($total_retiros, 0, ",", ".") . "\n");

            $printer->text("\n");

            $printer->text("------------------------------------------------\n ");
            $printer->text("DEVOLUCIONES \n");
            $printer->text("------------------------------------------------\n ");
            $printer->text("\n");

            $devolucion_venta = model('devolucionModel')->devoluciones($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);


            $temp_devoluciones = 0;
            $total_devoluciones = 0;


            foreach ($devolucion_venta as $detalle) {

                $printer->text("FECHA:" . $detalle['fecha'] . " " . date("g:i a", strtotime($detalle['hora'])) . "\n");
                $codigo_interno_producto = model('detalleDevolucionVentaModel')->select('codigo')->where('id_devolucion_venta', $detalle['id'])->first();
                $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigo'])->first();
                $printer->text("PRODUCTO: " . $nombre_producto['nombreproducto'] . "\n");
                $valor = model('devolucionVentaEfectivoModel')->select('valor')->where('iddevolucion', $detalle['id'])->first();
                $printer->text("VALOR " . "$" . number_format($valor['valor'], 0, ",", ".") . "\n");
                $printer->text("\n");


                $temp_devoluciones = $temp_devoluciones + $valor['valor'];
                $total_devoluciones = $temp_devoluciones;
            }

            $printer->text("Total devoluciones:" . "$" . number_format($total_devoluciones, 0, ",", ".") . "\n");

            $printer->text("\n");

            $printer->text("------------------------------------------------\n");
            $printer->text("Ingresos-retiros-devoluciones \n");
            $printer->text("------------------------------------------------\n");


            $printer->text("Ingresos+apertura " . "$" . number_format($ingresos_efectivo[0]['ingresos_efectivo'] + $valor_apertura['valor'], 0, ",", ".") . "\n");

            $printer->text("(-) Total retiros: " . "$" . number_format($total_retiros, 0, ",", ".") . "\n");
            $printer->text("(-) Total devoluciones:" . "$" . number_format($total_devoluciones, 0, ",", ".") . "\n");

            # $temp = $ingresos_efectivo[0]['ingresos_efectivo'] + $valor_apertura['valor'];
            // $tot_en_caja = $temp - $total_retiros;
            $tot_en_caja =  $total_retiros;
            $total_en_caja = $tot_en_caja - $total_devoluciones;

            $printer->text("(-) Efectivo en caja: " . number_format($total_en_caja, 0, ",", ".") . "\n");

            $printer->text("\n");

            $printer->text("-----------------------------------------------\n");
            $printer->text("Cierre de caja \n");
            $printer->text("-----------------------------------------------\n");
            $printer->text("Efectivo en caja   " . "$" . number_format($total_en_caja, 0, ",", ".") . " \n");
            $valor_cierre_efectivo_usuario = model('cierreFormaPagoModel')->valor_cierre_efectivo_usuario($id_cierre); //Este valor es que el digita el usuario 

            $printer->text("Valor cierre efectivo:  " . "$" . number_format($valor_cierre_efectivo_usuario[0]['valor'], 0, ",", ".") . "\n");
            $printer->text("Diferencia efectivo   " . "$" . number_format($valor_cierre_efectivo_usuario[0]['valor'] - $total_en_caja, 0, ",", ".")   . "\n");
            $printer->text("\n");
            //$ingresos_transaccion = model('facturaFormaPagoModel')->ingresos_efectivo($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            if (empty($ingresos_transaccion)) {
                $transaccion = 0;
            }
            if (!empty($ingresos_transaccion)) {
                $transaccion = $ingresos_transaccion[0]['ingresos_transaccion'];
            }

            $printer->text("Transacciones: " . "$" . number_format($transaccion, 0, ",", ".") . "\n");
            $valor_cierre_transaccion_usuari = model('cierreFormaPagoModel')->valor_cierre_transaccion_usuario($id_cierre);
            if (empty($valor_cierre_transaccion_usuari)) {
                $valor_cierre_transaccion_usuario = 0;
            }
            if (!empty($valor_cierre_transaccion_usuari)) {
                $valor_cierre_transaccion_usuario = $valor_cierre_transaccion_usuari[0]['valor'];
            }
            $printer->text("Valor cierre transacciones  " . "$" .  number_format($valor_cierre_transaccion_usuario, 0, ",", ".") .  "\n");
            $printer->text("Diferencia en transacciones  " . "$" . number_format($valor_cierre_transaccion_usuario - $transaccion, 0, ",", ".") . "\n");

            $printer->text("\n");


            $printer->text("TOTAL DIFERENCIAS  " . "$" . number_format(($valor_cierre_efectivo_usuario[0]['valor'] - $total_en_caja) + ($valor_cierre_transaccion_usuario - $transaccion), 0, ",", ".") . "\n");


            $printer->feed(1);
            $printer->cut();

            $printer->close();

            $returnData = array(
                "resultado" => 1
            );
            echo  json_encode($returnData);
        }
        if (empty($tiene_cierre)) {
            $this->imprimir_movimiento_caja_sin_cierre($id_apertura);
        }
    }
    function imprimir_movimiento_caja_sin_cierre($id_apertura)
    {
        $id_apertura = $id_apertura;

        $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();
        $datos_empresa = model('empresaModel')->datosEmpresa();

        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
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

        $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
        $hora_apertura = model('aperturaModel')->select('hora')->where('id', $id_apertura)->first();

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("**CUADRE DE CAJA** \n");

        $printer->text("Fecha apertura:  " . $fecha_apertura['fecha'] . " " . date("g:i a", strtotime($hora_apertura['hora'])) . "\n");
        $printer->text("Caja N° : 1 \n");

        $printer->text("\n");

        $valor_apertura = model('aperturaModel')->select('valor')->where('id', $id_apertura)->first();
        $printer->text("VALOR APERTURA   : " . "$" . number_format($valor_apertura['valor'], 0, ",", ".") . "\n");

        $printer->text("\n");

        $printer->text("-----------------------------------------------\n ");
        $printer->text("INGRESOS \n ");
        $printer->text("-----------------------------------------------\n ");
        $printer->text("\n");
        $fecha_y_hora_apertura = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $id_apertura)->first();

        $fecha = DateTime::createFromFormat('U.u', microtime(TRUE));
        $fecha->setTimeZone(new DateTimeZone('America/Bogota'));
        $fecha_y_hora_actual = $fecha->format('Y-m-d H:i:s.u');

        //$ingresos_efectivo = model('facturaFormaPagoModel')->ingresos_efectivo($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_actual);
        $ingresos_efectivo = model('pagosModel')->selectSum('efectivo')->where('id_apertura', $id_apertura)->findAll();


        $ingresos_transaccion = model('facturaFormaPagoModel')->ingresos_transaccion($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_actual);
        if (!empty($ingresos_efectivo[0]['efectivo'])) {
            $printer->text("Ingresos efectivo:      " . "$" . number_format($ingresos_efectivo[0]['efectivo'], 0, ",", ".") . "\n");
        }
        if (empty($ingresos_efectivo[0]['efectivo'])) {
            $printer->text("Ingresos efectivo: $0"  . "\n");
        }
        if (!empty($ingresos_transaccion[0]['ingresos_transaccion'])) {
            $printer->text("Ingresos transacción: " . "$" . number_format($ingresos_transaccion[0]['ingresos_transaccion'], 0, ",", ".") . "\n");
        }
        if (empty($ingresos_transaccion[0]['ingresos_transaccion'])) {
            $printer->text("Ingresos transacción:   $0 " . "\n");
        }

        $total_ingresos = model('facturaFormaPagoModel')->total_ingresos($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_actual);

        $printer->text("Total ingresos          " . "$" . number_format($total_ingresos[0]['total_ingresos'], 0, ",", ".") . "\n");


        $printer->text("\n");

        $printer->text("------------------------------------------------\n ");
        $printer->text("RETIROS \n");
        $printer->text("------------------------------------------------\n ");
        $retiros = model('retiroModel')->select('*')->where('id_apertura', $id_apertura)->findAll();

        $printer->text("\n");
        foreach ($retiros as $detalle) {
            $printer->text("FECHA: " . $detalle['fecha'] . " " . date("g:i a", strtotime($detalle['hora'])) . "\n");
            $concepto = model('retiroFormaPagoModel')->select('concepto')->where('idretiro', $detalle['id'])->first();
            $printer->text("CONCEPTO:" . $concepto['concepto'] . "\n");
            $valor = model('retiroFormaPagoModel')->select('valor')->where('idretiro', $detalle['id'])->first();
            $printer->text("VALOR:" . "$" . number_format($valor['valor'], 0, ",", ".") . "\n");
            $printer->text("\n");
        }



        $temp_retiros = 0;
        $total_retiros = 0;
        foreach ($retiros as $detalle) {
            $valor = model('retiroFormaPagoModel')->select('valor')->where('idretiro', $detalle['id'])->first();
            $temp_retiros = $temp_retiros + $valor['valor'];
            $total_retiros = $temp_retiros;
        }

        $printer->text("Total retiros: " . "$" . number_format($total_retiros, 0, ",", ".") . "\n");

        $printer->text("\n");

        $printer->text("------------------------------------------------\n ");
        $printer->text("DEVOLUCIONES \n");
        $printer->text("------------------------------------------------\n ");
        $printer->text("\n");

        $devolucion_venta = model('devolucionModel')->devoluciones($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_actual);


        $temp_devoluciones = 0;
        $total_devoluciones = 0;

        foreach ($devolucion_venta as $detalle) {

            $printer->text("FECHA:" . $detalle['fecha'] . " " . date("g:i a", strtotime($detalle['hora'])) . "\n");
            $codigo_interno_producto = model('detalleDevolucionVentaModel')->select('codigo')->where('id_devolucion_venta', $detalle['id'])->first();
            $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigo'])->first();
            $printer->text("PRODUCTO: " . $nombre_producto['nombreproducto'] . "\n");
            $valor = model('devolucionVentaEfectivoModel')->select('valor')->where('iddevolucion', $detalle['id'])->first();
            $printer->text("VALOR " . "$" . number_format($valor['valor'], 0, ",", ".") . "\n");
            $printer->text("\n");


            $temp_devoluciones = $temp_devoluciones + $valor['valor'];
            $total_devoluciones = $temp_devoluciones;
        }

        $printer->text("Total devoluciones:" . "$" . number_format($total_devoluciones, 0, ",", ".") . "\n");

        $printer->text("\n");
        $printer->text("------------------------------------------------\n");
        $printer->text("Ingresos-retiros-devoluciones \n");
        $printer->text("------------------------------------------------\n");

        $printer->text("Ingresos+apertura " . "$" . number_format($ingresos_efectivo[0]['efectivo'] + $valor_apertura['valor'], 0, ",", ".") . "\n");

        $printer->text("Total retiros: " . "$" . number_format($total_retiros, 0, ",", ".") . "\n");
        $printer->text("Total devoluciones:" . "$" . number_format($total_devoluciones, 0, ",", ".") . "\n");

        $temp = $ingresos_efectivo[0]['efectivo'] + $valor_apertura['valor'];
        $total_caja = $total_retiros + $total_devoluciones;
        $total_en_caja = $temp - $total_caja;

        $printer->text("(-) Efectivo en caja: " . number_format($total_en_caja, 0, ",", ".") . "\n");

        $printer->text("\n");
        $printer->text("-----------------------------------------------\n");
        $printer->text("Cierre de caja \n");
        $printer->text("-----------------------------------------------\n");
        $printer->text("Efectivo en caja del sistema  " . "$" . number_format($total_en_caja, 0, ",", ".") . " \n");
        $valor_cierre_efectivo_usuario = model('cierreFormaPagoModel')->valor_cierre_efectivo_usuario($id_apertura);

        $printer->text("\n");

        if (empty($ingresos_transaccion)) {
            $transaccion = 0;
        }
        if (!empty($ingresos_transaccion)) {
            $transaccion = $ingresos_transaccion[0]['ingresos_transaccion'];
        }

        $printer->text("Transacciones: " . "$" . number_format($transaccion, 0, ",", ".") . "\n");
        $valor_cierre_transaccion_usuari = model('cierreFormaPagoModel')->valor_cierre_transaccion_usuario($id_apertura);
        if (empty($valor_cierre_transaccion_usuari)) {
            $valor_cierre_transaccion_usuario = 0;
        }
        if (!empty($valor_cierre_transaccion_usuari)) {
            $valor_cierre_transaccion_usuario = $valor_cierre_transaccion_usuari[0]['valor'];
        }
        $printer->text("Valor cierre transacciones  " . "$" .  number_format($valor_cierre_transaccion_usuario, 0, ",", ".") .  "\n");
        $printer->text("Diferencia en transacciones" . number_format($valor_cierre_transaccion_usuario - $transaccion, 0, ",", ".") . "\n");

        $printer->text("\n");

        $printer->feed(1);
        $printer->cut();

        $printer->close();

        $returnData = array(
            "resultado" => 1
        );
        echo  json_encode($returnData);
    }

    function lista_precios()
    {
        $requiere_lista = model('cajaModel')->select('requiere_lista_de_precios')->first();

        return view('caja/lista_precios', [
            'lista_precios' => $requiere_lista['requiere_lista_de_precios']
        ]);
    }

    function actualizar_lista_precios()
    {
        $temp = $_REQUEST['list_precios'];

        if ($temp == 1) {
            $valor = 'true';
        }
        if ($temp == 0) {
            $valor = 'false';
        }

        $data = [
            'requiere_lista_de_precios' => $valor
        ];

        $model = model('cajaModel');
        $actualizar = $model->set($data);
        $actualizar = $model->where('numerocaja', 1);
        $actualizar = $model->update();
        if ($actualizar) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('home'))->with('mensaje', 'Activacion de lista de precios ');
        }
    }

    function listado_precios()
    {


        $id_producto = $_REQUEST['id_producto'];

        $lista_de_precios = model('productoModel')->listado_de_precios($id_producto);
        $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $id_producto)->first();

        $returnData = array(
            "resultado" => 1,
            'lista_precios' => view('producto/lista_precios', [
                'lista_precios' => $lista_de_precios
            ]),
            'nombre_producto' => $nombre_producto['nombreproducto'],
            'codigo_interno_producto' => $id_producto
        );
        echo  json_encode($returnData);
    }

    function actualizar_apertura_caja_sin_cierre()
    {

        $id_apertuta = $_REQUEST['id_apertura'];
        $valor_apertura = $_REQUEST['apertura'];

        $apertura = [
            'valor' => $_REQUEST['apertura'],
        ];
        $model = model('aperturaModel');
        $actualizar_apertura = $model->set($apertura);
        $actualizar_apertura = $model->where('id', $_REQUEST['id_apertura']);
        $actualizar_apertura = $model->update();

        if ($actualizar_apertura) {
            $valor_apertura = model('aperturaModel')->select('valor')->where('id', $_REQUEST['id_apertura'])->first();

            $returnData = [
                'resultado' => 1,
                'valor_apertura' => "Valor apertura $" . number_format($_REQUEST['apertura'], 0, ",", "."),
                'val_apertura' => number_format($_REQUEST['apertura'], 0, ",", "."),
            ];
            echo json_encode($returnData);
        }
    }

    function exportar_a_excel_reporte_categorias()
    {
        $datos_empresa = model('empresaModel')->find();
        $id_regimen = $datos_empresa[0]['idregimen'];
        $regimen = model('regimenModel')->select('nombreregimen')->where('idregimen', $id_regimen)->first();
        $nombre_ciudad = model('ciudadModel')->select('nombreciudad')->where('idciudad', $datos_empresa[0]['idciudad'])->first();
        $nombre_departamento = model('departamentoModel')->select('nombredepartamento')->where('iddepartamento', $datos_empresa[0]['iddepartamento'])->first();

        $id_apertura = $this->request->getPost('id_apertura');

        if (empty($id_apertura)) {
            $fecha_apertura = $this->request->getPost('fecha_inicial_agrupado');
            $hora_apertura = $this->request->getPost('hora_inicial_agrupado');
            $fecha_cierre = $this->request->getPost('fecha_final_agrupado');
            $hora_cierre = $this->request->getPost('hora_final_agrupado');

            if (empty($hora_inicial) and empty($hora_final)) {
                $resultado = model('productoFacturaVentaModel')->resutado_suma_entre_fechas($fecha_apertura, $fecha_cierre);

                $validar_tabla_reporte_producto = model('reporteProductoModel')->findAll();
                if (empty($validar_tabla_reporte_producto)) {
                    foreach ($resultado as $detalle) {

                        $productos_suma = model('productoFacturaVentaModel')->reporte_suma_cantidades($fecha_apertura, $fecha_cierre, $detalle['valor_total_producto'], $detalle['codigointernoproducto']);
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


                    $devoluciones = model('devolucionModel')->resutado_suma_entre_fechas($fecha_apertura, $fecha_cierre);

                    $total_devoluciones = model('devolucionModel')->total($fecha_apertura, $fecha_cierre);

                    $categorias = model('productoFacturaVentaModel')->categorias($fecha_apertura, $fecha_cierre);

                    return view('producto/datos_consultar_agrupado_excel', [
                        'datos_productos' => $resultado,
                        'fecha_inicial' => $fecha_apertura,
                        'fecha_final' => $fecha_cierre,
                        //'total' => "$" . number_format($total[0]['total'], 0, ",", "."),
                        'devoluciones' => $devoluciones,
                        'total_devoluciones' => "$" . number_format($total_devoluciones[0]['total'], 0, ",", "."),
                        'datos_empresa' => $datos_empresa,
                        'regimen' => $regimen['nombreregimen'],
                        'nombre_ciudad' => $nombre_ciudad['nombreciudad'],
                        'nombre_departamento' => $nombre_departamento['nombredepartamento'],
                        'categorias' => $categorias
                    ]);
                } else if (!empty($validar_tabla_reporte_producto)) {
                    $categorias = model('productoFacturaVentaModel')->categorias($fecha_apertura, $fecha_cierre);

                    $devoluciones = model('devolucionModel')->resutado_suma_entre_fechas($fecha_apertura, $fecha_cierre);

                    $total_devoluciones = model('devolucionModel')->total($fecha_apertura, $fecha_cierre);


                    return view('producto/datos_consultar_agrupado_excel', [
                        'datos_productos' => $resultado,
                        'fecha_inicial' => $fecha_apertura,
                        'fecha_final' => $fecha_cierre,
                        //'total' => "$" . number_format($total[0]['total'], 0, ",", "."),
                        'devoluciones' => $devoluciones,
                        'total_devoluciones' => "$" . number_format($total_devoluciones[0]['total'], 0, ",", "."),
                        'datos_empresa' => $datos_empresa,
                        'regimen' => $regimen['nombreregimen'],
                        'nombre_ciudad' => $nombre_ciudad['nombreciudad'],
                        'nombre_departamento' => $nombre_departamento['nombredepartamento'],
                        'categorias' => $categorias
                    ]);
                }
            }
        }
        if (!empty($id_apertura)) {
            $fecha_apertura = $this->request->getPost('fecha_inicial_agrupado');
            $hora_apertura = $this->request->getPost('hora_inicial_agrupado');
            $fecha_cierre = $this->request->getPost('fecha_final_agrupado');
            $hora_cierre = $this->request->getPost('hora_final_agrupado');

            if (empty($hora_inicial) and empty($hora_final)) {
                $resultado = model('productoFacturaVentaModel')->resutado_suma_entre_fechas($fecha_apertura, $fecha_cierre);

                $validar_tabla_reporte_producto = model('reporteProductoModel')->findAll();
                if (empty($validar_tabla_reporte_producto)) {
                    foreach ($resultado as $detalle) {

                        $productos_suma = model('productoFacturaVentaModel')->reporte_suma_cantidades($fecha_apertura, $fecha_cierre, $detalle['valor_total_producto'], $detalle['codigointernoproducto']);
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


                    $devoluciones = model('devolucionModel')->resutado_suma_entre_fechas($fecha_apertura, $fecha_cierre);

                    $total_devoluciones = model('devolucionModel')->total($fecha_apertura, $fecha_cierre);

                    $categorias = model('productoFacturaVentaModel')->categorias($fecha_apertura, $fecha_cierre);

                    return view('producto/datos_consultar_agrupado_excel', [
                        'datos_productos' => $resultado,
                        'fecha_inicial' => $fecha_apertura,
                        'fecha_final' => $fecha_cierre,
                        //'total' => "$" . number_format($total[0]['total'], 0, ",", "."),
                        'devoluciones' => $devoluciones,
                        'total_devoluciones' => "$" . number_format($total_devoluciones[0]['total'], 0, ",", "."),
                        'datos_empresa' => $datos_empresa,
                        'regimen' => $regimen['nombreregimen'],
                        'nombre_ciudad' => $nombre_ciudad['nombreciudad'],
                        'nombre_departamento' => $nombre_departamento['nombredepartamento'],
                        'categorias' => $categorias
                    ]);
                } else if (!empty($validar_tabla_reporte_producto)) {
                    $categorias = model('productoFacturaVentaModel')->categorias($fecha_apertura, $fecha_cierre);

                    $devoluciones = model('devolucionModel')->resutado_suma_entre_fechas($fecha_apertura, $fecha_cierre);

                    $total_devoluciones = model('devolucionModel')->total($fecha_apertura, $fecha_cierre);


                    return view('producto/datos_consultar_agrupado_excel', [
                        'datos_productos' => $resultado,
                        'fecha_inicial' => $fecha_apertura,
                        'fecha_final' => $fecha_cierre,
                        //'total' => "$" . number_format($total[0]['total'], 0, ",", "."),
                        'devoluciones' => $devoluciones,
                        'total_devoluciones' => "$" . number_format($total_devoluciones[0]['total'], 0, ",", "."),
                        'datos_empresa' => $datos_empresa,
                        'regimen' => $regimen['nombreregimen'],
                        'nombre_ciudad' => $nombre_ciudad['nombreciudad'],
                        'nombre_departamento' => $nombre_departamento['nombredepartamento'],
                        'categorias' => $categorias
                    ]);
                }
            }
        }
    }

  

    function imp_movimiento_caja()
    {
        $id_cierre = $_REQUEST['id_cierre'];
        $existe_cierre = model('cierreModel')->select('id')->where('id', $id_cierre)->first();
        if (!empty($existe_cierre['id'])) {
            $id_apert = model('cierreModel')->select('idapertura')->where('id', $id_cierre)->first();

            $imp = new impresion();

            $impresion = $imp->imprimir_cuadre_caja($id_apert['idapertura']);

            $returnData = array(
                "resultado" => 1,
                "id_cierre" => $id_cierre
            );
            echo  json_encode($returnData);
        }
    }
}
