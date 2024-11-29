<?php

namespace App\Controllers\consultas_y_reportes;

require APPPATH . "Controllers/mike42/autoload.php";

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class cajaDiariaController extends BaseController
{
    public function index()
    {
        return view('home/home');
    }

    public function reporte_caja_diaria()
    {
        return view('consultas_y_reportes/reporte_caja_diaria');
    }

    public function informe_caja()
    {
        $datos_empresa = model('empresaModel')->find();
        $id_regimen = $datos_empresa[0]['idregimen'];
        $regimen = model('regimenModel')->select('nombreregimen')->where('idregimen', $id_regimen)->first();
        $nombre_ciudad = model('municipiosModel')->select('nombreciudad')->where('idciudad', $datos_empresa[0]['idciudad'])->first();
        $nombre_departamento = model('departamentoModel')->select('nombredepartamento')->where('iddepartamento', $datos_empresa[0]['iddepartamento'])->first();

        $fecha_factura = $_POST['fecha'];

        if (!empty($fecha_factura)) {

            // $fecha_factura = '2022-07-29';
            $hay_venta = model('FacturaVentaModel')->select('*')->where('fecha_factura_venta', $fecha_factura)->findAll();

            if (!empty($hay_venta)) {

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

                $porcentaje_ico = model('productoFacturaVentaModel')->ico($id_factura_primero[0]['id'],  $id_factura_final[0]['id']);

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



                //$vantas_contado = model('facturaVentaModel')->ventas_contado($vantas_de_contado[0]['id_inicial'], $vantas_de_contado[0]['id_final']);
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

                        //echo number_format($iva_devolucion[0]['base'])."</br>";
                    }
                } else {
                    $iva_de_devolucion[] = 0;
                    $iva_de_devolucion[] = 0;
                    $iva_de_devolucion[] = 0;
                    $iva_de_devolucion[] = 0;
                }


                $id_devoluciones_ico = model('devolucionModel')->id($fecha_factura);



                if (!empty($id_devoluciones_ico[0]['minimo'] && $id_devoluciones[0]['maximo'])) {


                    $tarifa_ico_devolucion = model('devolucionModel')->ico($id_devoluciones[0]['minimo'], $id_devoluciones[0]['maximo']);

                    if (!empty($tarifa_ico_devolucion[0]['ico'])) {
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

                $consecutivo_caja = model('cajaModel')->select('consecutivo')->where('numerocaja', 1)->first();

                $existe_fecha_informe = model('consecutivoInformeModel')->select('fecha')->where('fecha', $fecha_factura)->first();
                if (empty($existe_fecha_informe['fecha'])) { // la fecha no esta en la tabla consecutivo_informe entonces inserto los datos e incremento 
                    //  El consecutivo de la caja 
                    $consecutivo_informe = [
                        'fecha' => $fecha_factura,
                        'idcaja' => 1,
                        'numero' => $consecutivo_caja['consecutivo']
                    ];
                    $insertar = model('consecutivoInformeModel')->insert($consecutivo_informe);
                    if ($insertar) {
                        $consecutivo_caja = [
                            'consecutivo' => $consecutivo_caja['consecutivo'] + 1
                        ];
                        $model = model('cajaModel');
                        $actualizar = $model->set($consecutivo_caja);
                        $actualizar = $model->where('numerocaja', 1);
                        $actualizar = $model->update();
                    }
                } else if (!empty($existe_fecha_informe['fecha'])) {


                    $consecutivo = model('consecutivoInformeModel')->select('numero')->where('fecha', $existe_fecha_informe['fecha'])->first();

                    $informe = view('consultas_y_reportes/reporte_caja_diaria_datos', [
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
                        "consecutivo" => $consecutivo['numero'],
                        "fecha" => $fecha_factura

                    ]);

                    $returnData = array(
                        "resultado" => 0,
                        "reporte" => $informe,
                    );
                    echo  json_encode($returnData);
                }
            } else if (empty($hay_venta)) {

                $returnData = array(
                    "resultado" => 1, //Falta plata ,
                    "fecha" => $fecha_factura
                );
                echo  json_encode($returnData);
            }
        } else {
            $returnData = array(
                "resultado" => 2, //Falta plata ,
            );
            echo  json_encode($returnData);
        }
    }

    public function reporte_caja_diaria_datos()
    {

        $base = $_REQUEST['baseFormat'];
        $cuadre_caja_format = $_REQUEST['cuadre_caja_format'];
        $cuadre_caja = $base + $cuadre_caja_format;

        $returnData = array(
            "resultado" => 1, //Falta plata ,
            "valor_impuesto" => 0,
            "total_base_cero" => number_format($base, 0, ",", "."),
            "cuadre_caja" => number_format($cuadre_caja, 0, ",", ".")
        );
        echo  json_encode($returnData);
    }
    public function reporte_caja_diaria_datos_ico()
    {
        $base = $_REQUEST['baseFormat'];
        $cuadre_caja_format = $_REQUEST['cuadre_caja_format'];
        $total_ico = $base * 1.08;
        $result = $total_ico - $base;
        $cuadre_caja = $total_ico + $cuadre_caja_format;

        $returnData = array(
            "resultado" => 1, //Falta plata ,
            "valor_impuesto" =>  number_format($result, 0, ",", "."),
            "total_ico" => number_format($total_ico, 0, ",", "."),
            "cuadre_caja" => number_format($cuadre_caja, 0, ",", ".")
        );
        echo  json_encode($returnData);
    }

    public function guardar_reporte_caja_diaria()
    {
        $fecha = $_REQUEST['fecha'];
        if (!empty($_REQUEST['total_venta_cero'])) {
            $base_0 = str_replace(".", "", $_REQUEST['total_venta_cero']);
        }


        if (empty($_REQUEST['total_venta_cero'])) {
            $base_0 = 0;
        }



        if (!empty($_REQUEST['base_ico'])) {
            $base_ico = str_replace(".", "", $_REQUEST['base_ico']);
        }

        if (empty($_REQUEST['base_ico'])) {
            $base_ico = 0;
        }



        $existe_fecha = model('fiscalModel')->select('fecha')->where('fecha', $fecha)->first();
        if (empty($existe_fecha)) {

            $data = [
                'fecha' => $_REQUEST['fecha'],
                'base_0' => $base_0,
                'base_ico' => $base_ico,
                'ico' => 8,
                'caja' => 1
            ];

            $insert = model('fiscalModel')->insert($data);


            if ($insert) {
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
                $consecutivo_informe = model('consecutivoInformeModel')->select('numero')->where('fecha', $fecha)->first();
                $printer->text("N°" . $consecutivo_informe['numero'] . "\n");
                $printer->text("CAJA: N° 1" . "\n");
                $printer->text("FECHA " . $fecha . "\n");


                $id_factura_primero = model('facturaVentaModel')->selectMin('id')->where('fecha_factura_venta', $fecha)->findAll();
                $id_factura_final = model('facturaVentaModel')->selectMax('id')->where('fecha_factura_venta', $fecha)->findAll();
                $registro_inicial = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura_primero[0]['id'])->first();
                $registro_final = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura_final[0]['id'])->first();
                $total_registros = model('facturaVentaModel')->selectCount('id')->where('fecha_factura_venta', $fecha)->find();

                $printer->text("RANGO INICIAL  " . $registro_inicial['numerofactura_venta'] . "\n");
                $printer->text("RANGO FINAL    " . $registro_final['numerofactura_venta'] . "\n");
                $printer->text("TOTAL REGISTROS    " . $total_registros[0]['id'] . "\n");


                $base_cero = model('fiscalModel')->select('base_0')->where('fecha', $fecha)->first();
                $ico = model('fiscalModel')->select('ico')->where('fecha', $fecha)->first();
                $base_ico = model('fiscalModel')->select('base_ico')->where('fecha', $fecha)->first();

                $total_ico = $base_ico['base_ico'] * 1.08;

                $valor_ico = $total_ico - $base_ico['base_ico'];

                $total_venta = $total_ico + $base_cero['base_0'];


                $printer->setJustification(Printer::JUSTIFY_LEFT);

                $printer->text("BASE 0" . "\n");
                $printer->text("TARIFA   BASE GRABABLE  VAL IMPUESTO   VAL TOTAL" . "\n");
                $printer->text("0%       " . "$" . number_format($base_cero['base_0']) . "       $0              " . "$" . number_format($base_cero['base_0']) . "\n");
                $printer->text("_______________________________________________" . "\n");
                $printer->text("\n");
                $printer->text("IMPUESTO AL CONSUMO" . "\n");
                $printer->text("TARIFA   BASE GRABABLE  VAL IMPUESTO   VAL TOTAL" . "\n");
                $printer->text($ico['ico'] . "%        " . "$" . number_format($base_ico['base_ico']) . "      " . "$" . number_format($valor_ico) . "       "       . "$" . number_format($total_ico) . "\n");
                $printer->text("_______________________________________________" . "\n");
                $printer->text("\n");
                $printer->text("TOTAL DE VENTAS" . "   $" . number_format($total_venta) . "\n");
                $printer->text("\n");
                $printer->feed(1);
                $printer->cut();
                $printer->close();
                $session = session();
                $session->setFlashdata('iconoMensaje', 'success');
                return redirect()->to(base_url('consultas_y_reportes/reporte_caja_diaria'))->with('mensaje', 'Generación de reporte exitoso ');
            }
        }
        if (!empty($existe_fecha)) {
            $data = [
                'base_0' => $base_0,
                'base_ico' => $base_ico,
                'ico' => 8
            ];
            $model = model('fiscalModel');
            $actualizar = $model->set($data);
            $actualizar = $model->where('fecha', $fecha);
            $actualizar = $model->update();

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


            $consecutivo_informe = model('consecutivoInformeModel')->select('numero')->where('fecha', $fecha)->first();
            $printer->text("N°" . $consecutivo_informe['numero'] . "\n");
            $printer->text("CAJA: N° 1" . "\n");
            $printer->text("FECHA " . $fecha . "\n");


            $id_factura_primero = model('facturaVentaModel')->selectMin('id')->where('fecha_factura_venta', $fecha)->findAll();
            $id_factura_final = model('facturaVentaModel')->selectMax('id')->where('fecha_factura_venta', $fecha)->findAll();
            $registro_inicial = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura_primero[0]['id'])->first();
            $registro_final = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura_final[0]['id'])->first();
            $total_registros = model('facturaVentaModel')->selectCount('id')->where('fecha_factura_venta', $fecha)->find();

            $printer->text("RANGO INICIAL  " . $registro_inicial['numerofactura_venta'] . "\n");
            $printer->text("RANGO FINAL    " . $registro_final['numerofactura_venta'] . "\n");
            $printer->text("TOTAL REGISTROS    " . $total_registros[0]['id'] . "\n");


            $base_cero = model('fiscalModel')->select('base_0')->where('fecha', $fecha)->first();
            $ico = model('fiscalModel')->select('ico')->where('fecha', $fecha)->first();
            $base_ico = model('fiscalModel')->select('base_ico')->where('fecha', $fecha)->first();

            $total_ico = $base_ico['base_ico'] * 1.08;

            $valor_ico = $total_ico - $base_ico['base_ico'];

            $total_venta = $total_ico + $base_cero['base_0'];


            $printer->setJustification(Printer::JUSTIFY_LEFT);

            $printer->text("BASE 0" . "\n");
            $printer->text("TARIFA   BASE GRABABLE  VAL IMPUESTO   VAL TOTAL" . "\n");
            $printer->text("0%       " . "$" . number_format($base_cero['base_0']) . "       $0              " . "$" . number_format($base_cero['base_0']) . "\n");
            $printer->text("_______________________________________________" . "\n");
            $printer->text("\n");
            $printer->text("IMPUESTO AL CONSUMO" . "\n");
            $printer->text("TARIFA   BASE GRABABLE  VAL IMPUESTO   VAL TOTAL" . "\n");
            $printer->text($ico['ico'] . "%        " . "$" . number_format($base_ico['base_ico']) . "      " . "$" . number_format($valor_ico) . "       "       . "$" . number_format($total_ico) . "\n");
            $printer->text("_______________________________________________" . "\n");
            $printer->text("\n");
            $printer->text("TOTAL DE VENTAS" . "   $" . number_format($total_venta) . "\n");
            $printer->text("\n");
            $printer->feed(1);
            $printer->cut();
            $printer->close();


            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('consultas_y_reportes/reporte_caja_diaria'))->with('mensaje', 'La fecha ya tiene registro y se actualizo ');
        }
    }

    public function imprimir_reporte_caja_diaria()
    {
        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->set(array('isRemoteEnable' => true));
        $dompdf->setOptions($options);

        $fecha = $_REQUEST['fecha_reporte_caja'];

        $datos_empresa = model('empresaModel')->find();
        $id_regimen = $datos_empresa[0]['idregimen'];
        $regimen = model('regimenModel')->select('nombreregimen')->where('idregimen', $id_regimen)->first();
        $nombre_ciudad = model('municipiosModel')->select('nombreciudad')->where('idciudad', $datos_empresa[0]['idciudad'])->first();
        $nombre_departamento = model('departamentoModel')->select('nombredepartamento')->where('iddepartamento', $datos_empresa[0]['iddepartamento'])->first();

        if (empty($fecha)) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'warning');
            return redirect()->to(base_url('consultas_y_reportes/reporte_caja_diaria'))->with('mensaje', 'No ha definido fecha válida ');
        } else {
            $existe_fecha_informe = model('fiscalModel')->select('*')->where('fecha', $fecha)->first();
            if (!empty($existe_fecha_informe)) {

                $base_cero = model('fiscalModel')->select('base_0')->where('fecha', $fecha)->first();
                $ico = model('fiscalModel')->select('ico')->where('fecha', $fecha)->first();
                $base_ico = model('fiscalModel')->select('base_ico')->where('fecha', $fecha)->first();

                $total_ico = $base_ico['base_ico'] * 1.08;

                $valor_ico = $total_ico - $base_ico['base_ico'];

                $total_venta = $total_ico + $base_cero['base_0'];

                $dompdf->loadHtml(view('consultas_y_reportes/reporte_caja_diaria_pdf', [
                    "nombre_comercial" => $datos_empresa[0]['nombrecomercialempresa'],
                    "nombre_juridico" => $datos_empresa[0]['nombrejuridicoempresa'],
                    "nit" => $datos_empresa[0]['nitempresa'],
                    "nombre_regimen" => $regimen['nombreregimen'],
                    "direccion" => $datos_empresa[0]['direccionempresa'],
                    "nombre_ciudad" => $nombre_ciudad['nombreciudad'],
                    "nombre_departamento" => $nombre_departamento['nombredepartamento'],
                    "fecha" => $fecha,
                    "base_cero" => $base_cero['base_0'],
                    "ico" => $ico['ico'],
                    "base_ico" => number_format($base_ico['base_ico'], 0, ",", "."),
                    "total_ico" => number_format($total_ico, 0, ",", "."),
                    "valor_ico" => number_format($valor_ico, 0, ",", "."),
                    "total_venta" => number_format($total_venta, 0, ",", ".")

                ]));

                $options = $dompdf->getOptions();
                $dompdf->setPaper('letter');
                $dompdf->render();
                $dompdf->stream("Informe fiscal $fecha.pdf", array("Attachment" => true));
                //} else if (empty($existe_fecha_informe)){

                //  return redirect()->to(base_url('pedido/pedidos_para_facturar'))->with('mensaje', 'No hay apertura de caja ');


                // }
            } else {
                $session = session();
                $session->setFlashdata('iconoMensaje', 'warning');
                return redirect()->to(base_url('consultas_y_reportes/reporte_caja_diaria'))->with('mensaje', 'No hay datos para esa fecha ');
            }
        }
    }


    public function solo_guardar_reporte_caja_diaria()
    {
        $fecha = $_REQUEST['fecha_reporte'];

        if (!empty($_REQUEST['total_venta_cero'])) {
            $base_0 = str_replace(".", "", $_REQUEST['total_venta_cero']);
        }


        if (empty($_REQUEST['total_venta_cero'])) {
            $base_0 = 0;
        }



        if (!empty($_REQUEST['base_ico'])) {
            $base_ico = str_replace(".", "", $_REQUEST['base_ico']);
        }

        if (empty($_REQUEST['base_ico'])) {
            $base_ico = 0;
        }



        $existe_fecha = model('fiscalModel')->select('fecha')->where('fecha', $fecha)->first();
        if (empty($existe_fecha)) {

            $data = [
                'fecha' => $_REQUEST['fecha_reporte'],
                'base_0' => $base_0,
                'base_ico' => $base_ico,
                'ico' => 8,
                'caja' => 1
            ];

            $insert = model('fiscalModel')->insert($data);


            if ($insert) {


                $returnData = array(
                    "resultado" => 1, //No hay pedido   
                );
                echo  json_encode($returnData);
            }
        }
        if (!empty($existe_fecha)) {
            $data = [
                'base_0' => $base_0,
                'base_ico' => $base_ico,
                'ico' => 8
            ];
            $model = model('fiscalModel');
            $actualizar = $model->set($data);
            $actualizar = $model->where('fecha', $fecha);
            $actualizar = $model->update();

            $returnData = array(
                "resultado" => 0, //No hay pedido   
            );
            echo  json_encode($returnData);
        }
    }

    function imprimir_reporte_de_caja_id()
    {

        $id = 213;
        //$id = $_REQUEST['id']; 
        $fecha_reporte = model('fiscalModel')->select('fecha')->where('id', $id)->first();

        $fecha = $fecha_reporte['fecha'];
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
        $consecutivo_informe = model('consecutivoInformeModel')->select('numero')->where('fecha', $fecha)->first();

        if (!empty($consecutivo_informe)) {
            $printer->text("N°" . $consecutivo_informe['numero'] . "\n");
        }

        $printer->text("CAJA: N° 1" . "\n");
        $printer->text("FECHA " . $fecha . "\n");


        $id_factura_primero = model('facturaVentaModel')->selectMin('id')->where('fecha_factura_venta', $fecha)->findAll();
        $id_factura_final = model('facturaVentaModel')->selectMax('id')->where('fecha_factura_venta', $fecha)->findAll();
        $registro_inicial = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura_primero[0]['id'])->first();
        $registro_final = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura_final[0]['id'])->first();
        $total_registros = model('facturaVentaModel')->selectCount('id')->where('fecha_factura_venta', $fecha)->find();

        //$printer->text("RANGO INICIAL  " . $registro_inicial['numerofactura_venta'] . "\n");
        // $printer->text("RANGO FINAL    " . $registro_final['numerofactura_venta'] . "\n");
        $printer->text("TOTAL REGISTROS    " . $total_registros[0]['id'] . "\n");


        $base_cero = model('fiscalModel')->select('base_0')->where('fecha', $fecha)->first();
        $ico = model('fiscalModel')->select('ico')->where('fecha', $fecha)->first();
        $base_ico = model('fiscalModel')->select('base_ico')->where('fecha', $fecha)->first();

        $total_ico = $base_ico['base_ico'] * 1.08;

        $valor_ico = $total_ico - $base_ico['base_ico'];

        $total_venta = $total_ico + $base_cero['base_0'];


        $printer->setJustification(Printer::JUSTIFY_LEFT);

        $printer->text("BASE 0" . "\n");
        $printer->text("TARIFA   BASE GRABABLE  VAL IMPUESTO   VAL TOTAL" . "\n");
        $printer->text("0%       " . "$" . number_format($base_cero['base_0']) . "       $0              " . "$" . number_format($base_cero['base_0']) . "\n");
        $printer->text("_______________________________________________" . "\n");
        $printer->text("\n");
        $printer->text("IMPUESTO AL CONSUMO" . "\n");
        $printer->text("TARIFA   BASE GRABABLE  VAL IMPUESTO   VAL TOTAL" . "\n");
        $printer->text($ico['ico'] . "%        " . "$" . number_format($base_ico['base_ico']) . "      " . "$" . number_format($valor_ico) . "       "       . "$" . number_format($total_ico) . "\n");
        $printer->text("_______________________________________________" . "\n");
        $printer->text("\n");
        $printer->text("TOTAL DE VENTAS" . "   $" . number_format($total_venta) . "\n");
        $printer->text("\n");
        $printer->text("\n");


        $printer->text("------------------------------------------------\n");

        $printer->text("FIRMA \n");
        $printer->text("\n");

        $printer->feed(1);
        $printer->cut();
        $printer->close();
        $session = session();
        $session->setFlashdata('iconoMensaje', 'success');
        return redirect()->to(base_url('consultas_y_reportes/reporte_caja_diaria'))->with('mensaje', 'Generación de reporte exitoso ');
    }

    function editar_valor_apertura()
    {
        $valor_apertura = model('aperturaModel')->select('valor')->where('id', $this->request->getPost('id_apertura'))->first();
        $returnData = [
            'resultado' => 1,
            'valor_apertura' => number_format($valor_apertura['valor'], 0, ",", "."),
            'id_apertura' => $this->request->getPost('id_apertura')
        ];
        echo json_encode($returnData);
    }

    function cambiar_valor_apertura()
    {
        $id_apertura = $this->request->getPost('id_apertura');

        $data = [
            'valor' => str_replace(".", "", $this->request->getPost('valor_apertura'))
        ];

        $model = model('aperturaModel');
        $actualizar = $model->set($data);
        $actualizar = $model->where('id', $id_apertura);
        $actualizar = $model->update();
        if ($actualizar) {

            $id_apertura = $id_apertura;

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

            $tiene_cierre = model('cierreModel')->select('fecha')->where('idapertura', $id_apertura)->first();
            $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
            $valor_apertura = model('aperturaModel')->select('valor')->where('id', $id_apertura)->first();
            $fecha_y_hora_apertura = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $id_apertura)->first();


            if (empty($tiene_cierre)) {
                $estado = "ABIERTA";
                $cierre = 'POR DEFINIR';
                $efectivo = model('facturaFormaPagoModel')->ingresos_efectivo($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d H:i:s'));
                if (empty($efectivo)) {
                    $ingresos_efectivo = 0;
                } else if (!empty($efectivo)) {
                    $ingresos_efectivo = $efectivo[0]['ingresos_efectivo'];
                }

                $transaccion = model('facturaFormaPagoModel')->ingresos_transaccion($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d H:i:s'));
                if (empty($transaccion)) {
                    $ingresos_transaccion = 0;
                } else if (!empty($transaccion)) {
                    $ingresos_transaccion = $transaccion[0]['ingresos_transaccion'];
                }
                $valor_cierre = 0;
                $devolucion_venta = model('devolucionModel')->sumar_devoluciones($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d H:i:s'));

                if (empty($devolucion_venta)) {
                    $devoluciones = 0;
                } else if (!empty($devolucion_venta)) {
                    $devoluciones = $devolucion_venta[0]['total_devoluciones'];
                }


                $total_retiros = model('retiroFormaPagoModel')->total_retiros($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d H:i:s'));

                if (empty($total_retiros[0]['total_retiros'])) {
                    $retiros = 0;
                }
                if (!empty($total_retiros[0]['total_retiros'])) {
                    $retiros = $total_retiros[0]['total_retiros'];
                }

                $efectivo_cierre = 0;
                $transaccion_cierre = 0;
                $saldo = 0;

                $diferencia = ($efectivo_cierre + $transaccion_cierre) - (($ingresos_transaccion + $ingresos_efectivo + $valor_apertura['valor']) - ($retiros + $devoluciones));
            }
            if (!empty($tiene_cierre)) {
                $estado = 'CERRADA';
                $fecha_cierre = model('cierreModel')->select('fecha')->where('idapertura', $id_apertura)->first();
                $cierre = $fecha_cierre['fecha'];

                $fecha_y_hora_cierre = model('cierreModel')->select('fecha_y_hora_cierre')->where('idapertura', $id_apertura)->first();
                $efectivo = model('facturaFormaPagoModel')->ingresos_efectivo($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
                if (empty($efectivo)) {
                    $ingresos_efectivo = 0;
                } else if (!empty($efectivo)) {
                    $ingresos_efectivo = $efectivo[0]['ingresos_efectivo'];
                }

                $transaccion = model('facturaFormaPagoModel')->ingresos_transaccion($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
                if (empty($transaccion)) {
                    $ingresos_transaccion = 0;
                } else if (!empty($transaccion)) {
                    $ingresos_transaccion = $transaccion[0]['ingresos_transaccion'];
                }
                $valor_cierre = 0;
                $devolucion_venta = model('devolucionModel')->sumar_devoluciones($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);

                if (empty($devolucion_venta)) {
                    $devoluciones = 0;
                } else if (!empty($devolucion_venta)) {
                    $devoluciones = $devolucion_venta[0]['total_devoluciones'];
                }
                $id_cierre = model('cierreModel')->select('id')->where('idapertura', $id_apertura)->first();
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

                $diferencia = ($efectivo_cierre + $transaccion_cierre) - (($ingresos_transaccion + $ingresos_efectivo + $valor_apertura['valor']) - ($retiros + $devoluciones));
            }



            $returnData = array(
                "resultado" => 1,
                "datos" =>  view('consultas_y_reportes/edicion_consultas_caja', [
                    'estado' => $estado,
                    'fecha_apertura' => $fecha_apertura['fecha'],
                    'fecha_cierre' => $cierre,
                    'valor_apertura' => "$" . number_format($valor_apertura['valor'], 0, ",", "."),
                    'ingresos_efectivo' =>  "$" . number_format($ingresos_efectivo, 0, ",", "."),
                    'ingresos_transaccion' =>  "$" . number_format($ingresos_transaccion, 0, ",", "."),
                    'total_ingresos' =>  "$" . number_format($ingresos_transaccion + $ingresos_efectivo, 0, ",", "."),
                    'efectivo_cierre' => "$" . number_format($efectivo_cierre, 0, ",", "."),
                    'transaccion_cierre' => "$" . number_format($transaccion_cierre, 0, ",", "."),
                    'total_cierre' => "$" . number_format($efectivo_cierre + $transaccion_cierre, 0, ",", "."),
                    'devoluciones' => "$" . number_format($devoluciones, 0, ",", "."),
                    'retiros' => "$" . number_format($retiros, 0, ",", "."),
                    'retirosmasdevoluciones' => "$" . number_format($retiros + $devoluciones, 0, ",", "."),
                    'saldo_caja' => "$" . number_format(($valor_apertura['valor'] + $ingresos_transaccion + $ingresos_efectivo) - ($retiros + $devoluciones), 0, ",", "."),
                    'diferencia' => "$" . number_format($diferencia, 0, ",", "."),
                    // 'diferencia' => "$" . number_format(($efectivo_cierre + $transaccion_cierre) - (($ingresos_transaccion + $ingresos_efectivo) - ($retiros + $devoluciones)), 0, ",", "."),
                    'id_apertura' => $id_apertura
                ])
            );
            echo  json_encode($returnData);
        }
    }

    function total_ingresos_efectivo()
    {
        $id_apertura = $this->request->getPost('id_apertura');
        $fecha_y_hora = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $id_apertura)->first();
        $fecha_y_hora_apertura = $fecha_y_hora['fecha_y_hora_apertura'];

        $fecha_y_hora_cierre = "";

        $tiene_cierre = model('cierreModel')->select('fecha')->where('idapertura', $id_apertura)->first();
        if (!empty($tiene_cierre)) {
            $fecha_cierre = model('cierreModel')->select('fecha_y_hora_cierre')->where('idapertura', $id_apertura)->first();
            $fecha_y_hora_cierre = $fecha_cierre['fecha_y_hora_cierre'];
        } else if (empty($tiene_cierre)) {
            $fecha_y_hora_cierre = date('Y-m-d H:i:s');
        }

        //$efectivo = model('facturaFormaPagoModel')->ingresos_efectivo($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

        $efectivo = model('facturaFormaPagoModel')->movimientos_efectivo($fecha_y_hora_apertura, $fecha_y_hora_cierre);
        $total_efectivo = model('facturaFormaPagoModel')->total_efectivo($fecha_y_hora_apertura, $fecha_y_hora_cierre);

        $returnData = array(
            "resultado" => 1,
            "efectivo" => view('consultas_y_reportes/movimiento_efectivo', [
                'efectivo' => $efectivo,
                "total" => $total_efectivo[0]['total']
            ])
        );
        echo  json_encode($returnData);
    }

    function total_ingresos_transaccion()
    {
        $id_apertura = $this->request->getPost('id_apertura');
        $fecha_y_hora = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $id_apertura)->first();
        $fecha_y_hora_apertura = $fecha_y_hora['fecha_y_hora_apertura'];

        $fecha_y_hora_cierre = "";

        $tiene_cierre = model('cierreModel')->select('fecha')->where('idapertura', $id_apertura)->first();
        if (!empty($tiene_cierre)) {
            $fecha_cierre = model('cierreModel')->select('fecha_y_hora_cierre')->where('idapertura', $id_apertura)->first();
            $fecha_y_hora_cierre = $fecha_cierre['fecha_y_hora_cierre'];
        } else if (empty($tiene_cierre)) {
            $fecha_y_hora_cierre = date('Y-m-d H:i:s');
        }

        //$efectivo = model('facturaFormaPagoModel')->ingresos_efectivo($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);
        $transaccion = model('facturaFormaPagoModel')->movimientos_transaccion($fecha_y_hora_apertura, $fecha_y_hora_cierre);
        $total_transaccion = model('facturaFormaPagoModel')->total_transaccion($fecha_y_hora_apertura, $fecha_y_hora_cierre);

        $returnData = array(
            "resultado" => 1,
            "transaccion" => view('consultas_y_reportes/movimiento_transaccion', [
                'transaccion' => $transaccion,
                "total" => $total_transaccion[0]['total']
            ])
        );
        echo  json_encode($returnData);
    }
    function retiros()
    {
        $id_apertura = $this->request->getPost('id_apertura');
        $fecha_y_hora = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $id_apertura)->first();
        $fecha_y_hora_apertura = $fecha_y_hora['fecha_y_hora_apertura'];

        $fecha_y_hora_cierre = "";

        $tiene_cierre = model('cierreModel')->select('fecha')->where('idapertura', $id_apertura)->first();
        if (!empty($tiene_cierre)) {
            $fecha_cierre = model('cierreModel')->select('fecha_y_hora_cierre')->where('idapertura', $id_apertura)->first();
            $fecha_y_hora_cierre = $fecha_cierre['fecha_y_hora_cierre'];
        } else if (empty($tiene_cierre)) {
            $fecha_y_hora_cierre = date('Y-m-d H:i:s');
        }

        //$efectivo = model('facturaFormaPagoModel')->ingresos_efectivo($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);
        $transaccion = model('facturaFormaPagoModel')->movimientos_transaccion($fecha_y_hora_apertura, $fecha_y_hora_cierre);
        $total_transaccion = model('facturaFormaPagoModel')->total_transaccion($fecha_y_hora_apertura, $fecha_y_hora_cierre);

        $returnData = array(
            "resultado" => 1,
            "transaccion" => view('consultas_y_reportes/movimiento_transaccion', [
                'transaccion' => $transaccion,
                "total" => $total_transaccion[0]['total']
            ])
        );
        echo  json_encode($returnData);
    }

    function movimientos_de_caja()
    {
        $fecha_inicial = $this->request->getPost('fecha_inicial');
        $fecha_final = $this->request->getPost('fecha_final');

        $aperturas = model('aperturaModel')->aperturas($fecha_inicial, $fecha_final);

        $returnData = array(
            "resultado" => 1,
            "aperturas" => view('consultas_y_reportes/aperturas', [
                'aperturas' => $aperturas
            ]),
        );
        echo  json_encode($returnData);
    }


    function detalle_movimiento_de_caja()
    {

        //$ultimo_apertura = 37;
        $ultimo_apertura = $this->request->getPost('id');

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
        $hora_apertura = "";
        $hora_cierre = "";

        $tiene_cierre = model('cierreModel')->select('fecha')->where('idapertura', $ultimo_apertura)->first();
        $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $ultimo_apertura)->first();
        $valor_apertura = model('aperturaModel')->select('valor')->where('id', $ultimo_apertura)->first();
        $fecha_y_hora_apertura = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $ultimo_apertura)->first();


        if (empty($tiene_cierre)) {
            $estado = "ABIERTA";
            $fecha_cierre = 'POR DEFINIR';
            //$efectivo = model('pagosModel')->ingresos_efectivo($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d H:i:s'));
            $efectivo = model('pagosModel')->selectSum('efectivo')->where('id_apertura', $ultimo_apertura)->findAll();
            if (empty($efectivo)) {
                $ingresos_efectivo = 0;
            } else if (!empty($efectivo)) {
                $ingresos_efectivo = $efectivo[0]['efectivo'];
            }

            //$transaccion = model('facturaFormaPagoModel')->ingresos_transaccion($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d H:i:s'));
            $transaccion = model('pagosModel')->selectSum('transferencia')->where('id_apertura', $ultimo_apertura)->findAll();
            if (empty($transaccion)) {
                $ingresos_transaccion = 0;
            } else if (!empty($transaccion)) {
                $ingresos_transaccion = $transaccion[0]['transferencia'];
            }
            $valor_cierre = 0;
            // $devolucion_venta = model('devolucionModel')->sumar_devoluciones($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d H:i:s'));
            $devolucion_venta = model('detalleDevolucionVentaModel')->selectSum('valor_total_producto')->where('id_apertura', $ultimo_apertura)->findAll();

            if (empty($devolucion_venta)) {
                $devoluciones = 0;
            } else if (!empty($devolucion_venta)) {
                $devoluciones = $devolucion_venta[0]['valor_total_producto'];
            }

            /*   $retiros = model('retiroModel')->findAll();

            foreach ($retiros as $detalle) {
                $data = [
                    'fecha_y_hora_retiro_forma_pago' => $detalle['fecha'] . " " . $detalle['hora']
                ];
                $model = model('retiroFormaPagoModel');
                $actualizar_retiro = $model->set($data);
                $actualizar_retiro = $model->where('idretiro', $detalle['id']);
                $actualizar_retiro = $model->update();
            } */

            //$total_retiros = model('retiroFormaPagoModel')->total_retiros($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d H:i:s'));
            $total_retiros = model('retiroFormaPagoModel')->selectSum('valor')->where('id_apertura', $ultimo_apertura)->findAll();

            if (empty($total_retiros[0]['valor'])) {
                $retiros = 0;
            }
            if (!empty($total_retiros[0]['valor'])) {
                $retiros = $total_retiros[0]['valor'];
            }

            $efectivo_cierre = 0;
            $transaccion_cierre = 0;
            $saldo = 0;

            $diferencia = ($efectivo_cierre + $transaccion_cierre) - (($ingresos_transaccion + $ingresos_efectivo + $valor_apertura['valor']) - ($retiros + $devoluciones));
        }
        if (!empty($tiene_cierre)) {
            $estado = 'CERRADA';
            $fecha_cierr = model('cierreModel')->select('fecha')->where('idapertura', $ultimo_apertura)->first();
            $fecha_cierre = $fecha_cierr['fecha'];

            $fecha_y_hora_cierre = model('cierreModel')->select('fecha_y_hora_cierre')->where('idapertura', $ultimo_apertura)->first();
            //$efectivo = model('pagoModel')->ingresos_efectivo($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
            //$efectivo = model('pagoModel')->ingresos_efectivo($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);

            $efectivo = model('pagosModel')->selectSum('efectivo')->where('id_apertura', $ultimo_apertura)->findAll();

            if (empty($efectivo)) {
                $ingresos_efectivo = 0;
            } else if (!empty($efectivo)) {
                $ingresos_efectivo = $efectivo[0]['efectivo'];
            }

            //$transaccion = model('facturaFormaPagoModel')->ingresos_transaccion($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);

            $transaccion = model('pagosModel')->selectSum('transferencia')->where('id_apertura', $ultimo_apertura)->findAll();

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
            $id_cierre = model('cierreModel')->select('id')->where('idapertura', $ultimo_apertura)->first();
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

            //$diferencia =  (($ingresos_transaccion + $ingresos_efectivo + $valor_apertura['valor']) - ($retiros + $devoluciones)) - ($efectivo_cierre + $transaccion_cierre);
            $cierre = $efectivo_cierre + $transaccion_cierre;
            $ingresos = $ingresos_transaccion + $ingresos_efectivo + $valor_apertura['valor'];
            $egresos = $retiros + $devoluciones;
            $saldo_caja = $ingresos - $egresos;
            $diferencia = $cierre - $saldo_caja;
            /* if ($cierre >= $saldo_caja ){
            $diferencia = $saldo_caja - $cierre  ;
           }
           if ($cierre < $saldo_caja ){
            $diferencia = $cierre- $saldo_caja;
           } */
        }
        $temp_propinas = model('pagosModel')->selectSum('propina')->where('id_apertura', $ultimo_apertura)->findAll();
        $propinas = $temp_propinas[0]['propina'];
        $returnData = array(
            "resultado" => 1,
            'estado' => $fecha_cierre,
            'fecha_apertura' => "Fecha apertura: " . $fecha_apertura['fecha'],
            'fecha_cierre' => "Fecha cierre: " . $fecha_cierre,
            'valor_apertura' => "$" . number_format($valor_apertura['valor'], 0, ",", "."),
            'ingresos_efectivo' =>  "$" . number_format(($ingresos_efectivo + $ingresos_transaccion) - $propinas, 0, ",", "."),
            'ingresos_transaccion' =>  "$" . number_format($propinas, 0, ",", "."),
            'total_ingresos' =>  "$" . number_format(($ingresos_transaccion + $ingresos_efectivo) + $valor_apertura['valor'], 0, ",", "."),
            'efectivo_cierre' => "$" . number_format($efectivo_cierre, 0, ",", "."),
            'transaccion_cierre' => "$" . number_format($transaccion_cierre, 0, ",", "."),
            'total_cierre' => "$" . number_format($efectivo_cierre + $transaccion_cierre, 0, ",", "."),
            'devoluciones' => "$" . number_format($devoluciones, 0, ",", "."),
            'retiros' => "$" . number_format($retiros, 0, ",", "."),
            'retirosmasdevoluciones' => "$" . number_format($retiros + $devoluciones, 0, ",", "."),
            'saldo_caja' => "$" . number_format(($valor_apertura['valor'] + $ingresos_transaccion + $ingresos_efectivo) - ($retiros + $devoluciones), 0, ",", "."),
            'diferencia' => "$" . number_format($diferencia, 0, ",", "."),
            // 'diferencia' => "$" . number_format(($efectivo_cierre + $transaccion_cierre) - (($ingresos_transaccion + $ingresos_efectivo) - ($retiros + $devoluciones)), 0, ",", "."),
            'id_apertura' => $ultimo_apertura
        );
        echo  json_encode($returnData);
    }

    function reporte_de_ventas()
    {

        $truncate = model('reporteProductoModel')->truncate();
        // $id_apertura = 851;
        $id_apertura = $this->request->getPost('id_apertura');



        $fecha_cierre = "";
        $fecha_y_hora_cierre = "";
        $hora_cierre = "";
        $fecha_y_hora_apertura = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $id_apertura)->first();

        $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
        $hor_apertura = model('aperturaModel')->select('hora')->where('id', $id_apertura)->first();
        $hora_apertura = $hor_apertura['hora'];
        $fecha_cierr = model('cierreModel')->select('fecha_y_hora_cierre')->where('idapertura', $id_apertura)->first();
        $hora_cierre = model('cierreModel')->select('hora')->where('idapertura', $id_apertura)->first();
        if (empty($fecha_cierr) and empty($hora_cierre)) {
            $fecha_y_hora_cierre = date('Y-m-d H:i:s');
            $hora_cierre = date('H:i:s');
            $fecha_cierre = date('Y-m-d');
        } else if (!empty($fecha_cierr)) {

            $fecha_y_hora_cierre = $fecha_cierr['fecha_y_hora_cierre'];
            $hora_cierre = $hora_cierre['hora'];
            $fecha_cierr = model('cierreModel')->select('fecha')->where('idapertura', $id_apertura)->first();
            $fecha_cierre = $fecha_cierr['fecha'];
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



        $returnData = [
            'resultado' => 1,
            'datos' =>  view('consultas_y_reportes/reporte_ventas_producto', [
                'productos_distinct' => $productos_distinct,
                'fecha_inicial' => $fecha_y_hora_apertura['fecha_y_hora_apertura'],
                'fecha_apertura' => $fecha_apertura['fecha'],
                'fecha_inicial_format' =>  date("g:i a", strtotime($fecha_y_hora_apertura['fecha_y_hora_apertura'])),
                'fecha_final' => $fecha_y_hora_cierre,
                'fecha_final_format' =>  date("g:i a", strtotime($fecha_y_hora_cierre)),
                'fecha_cierre' => $fecha_cierre,
                'devoluciones' => $devoluciones,
                //'total_devoluciones' => "$" . number_format($total_devoluciones[0]['total'], 0, ",", "."),
                'hora_inicial' => $hora_apertura,
                'hora_final' => $hora_cierre,
                //'categorias' => $categorias,
                'id_apertura' => $id_apertura,
                'categorias' => $categorias,
                'devoluciones' => $devoluciones
            ])
        ];
        echo json_encode($returnData);
    }
    function detalle_retiros()
    {
        $id_apertura = $this->request->getPost('id_apertura');

        $fecha_y_hora = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $id_apertura)->first();
        $fecha_y_hora_apertura = $fecha_y_hora['fecha_y_hora_apertura'];

        $fecha_y_hora_cierre = "";
        $retiros = "";
        $total_retiros = "";

        $tiene_cierre = model('cierreModel')->select('fecha')->where('idapertura', $id_apertura)->first();
        if (!empty($tiene_cierre)) {
            $fecha_cierre = model('cierreModel')->select('fecha_y_hora_cierre')->where('idapertura', $id_apertura)->first();
            $fecha_y_hora_cierre = $fecha_cierre['fecha_y_hora_cierre'];
        } else if (empty($tiene_cierre)) {
            $fecha_y_hora_cierre = date('Y-m-d H:i:s');
        }

        $temp_retiros = model('retiroFormaPagoModel')->retiros($fecha_y_hora_apertura, $fecha_y_hora_cierre);
        $temp_total_retiros = model('retiroFormaPagoModel')->total_retiros($fecha_y_hora_apertura, $fecha_y_hora_cierre);

        if (empty($temp_retiros)) {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        } else if (!empty($temp_retiros)) {

            $retiros = $temp_retiros;

            $returnData = array(
                "resultado" => 1,
                "retiros" => view('consultas_y_reportes/retiros', [
                    "total_retiros" => $total_retiros,
                    "retiros" => $retiros,
                    "total_retiros" => "TOTAL RETIROS  " . "$" . number_format($temp_total_retiros[0]['total_retiros'], 0, ",", ".")
                ])
            );
            echo  json_encode($returnData);
        }
    }

    function editar_valor_cierre()
    {
        //$id_apertura = 770;
        $id_apertura = $this->request->getPost('id_apertura');

        $efectivo_cierre = "";
        $transaccion_cierre = "";
        $id_cierre = model('cierreModel')->select('id')->where('idapertura', $id_apertura)->first();


        if (empty($id_cierre)) {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        } else if (!empty($id_cierre)) {
            $valor_cierre_efectivo = model('cierreFormaPagoModel')->cierre_efectivo($id_cierre['id']);

            $returnData = array(
                "resultado" => 1,
                "valor_cierre" => number_format($valor_cierre_efectivo[0]['valor'], 0, ",", "."),
                "id_apertura" => $id_apertura
            );
            echo  json_encode($returnData);
        }
    }

    function actualizar_valor_cierre()
    {
        $id_apertura = $this->request->getPost('id_apertura');
        // $id_apertura = 757;
        $id_cierre = model('cierreModel')->select('id')->where('idapertura', $id_apertura)->first();
        //$valor_cierre = 50.000;
        $valor_cierre = $this->request->getPost('valor_cierre');
        $data = [
            'valor' => str_replace(".", "", $valor_cierre)
        ];
        //echo $id_cierre['id']; exit();
        $model = model('cierreFormaPagoModel');
        $actualizar = $model->set($data);
        $actualizar = $model->where('idcierre', $id_cierre['id']);
        $actualizar = $model->where('idpago', 1);
        $actualizar = $model->update();

        if ($actualizar) {

            $id_apertura = $id_apertura;

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

            $tiene_cierre = model('cierreModel')->select('fecha')->where('idapertura', $id_apertura)->first();
            $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
            $valor_apertura = model('aperturaModel')->select('valor')->where('id', $id_apertura)->first();
            $fecha_y_hora_apertura = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $id_apertura)->first();


            if (empty($tiene_cierre)) {
                $estado = "ABIERTA";
                $cierre = 'POR DEFINIR';
                $efectivo = model('facturaFormaPagoModel')->ingresos_efectivo($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d H:i:s'));
                if (empty($efectivo)) {
                    $ingresos_efectivo = 0;
                } else if (!empty($efectivo)) {
                    $ingresos_efectivo = $efectivo[0]['ingresos_efectivo'];
                }

                $transaccion = model('facturaFormaPagoModel')->ingresos_transaccion($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d H:i:s'));
                if (empty($transaccion)) {
                    $ingresos_transaccion = 0;
                } else if (!empty($transaccion)) {
                    $ingresos_transaccion = $transaccion[0]['ingresos_transaccion'];
                }
                $valor_cierre = 0;
                $devolucion_venta = model('devolucionModel')->sumar_devoluciones($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d H:i:s'));

                if (empty($devolucion_venta)) {
                    $devoluciones = 0;
                } else if (!empty($devolucion_venta)) {
                    $devoluciones = $devolucion_venta[0]['total_devoluciones'];
                }


                $total_retiros = model('retiroFormaPagoModel')->total_retiros($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d H:i:s'));

                if (empty($total_retiros[0]['total_retiros'])) {
                    $retiros = 0;
                }
                if (!empty($total_retiros[0]['total_retiros'])) {
                    $retiros = $total_retiros[0]['total_retiros'];
                }

                $efectivo_cierre = 0;
                $transaccion_cierre = 0;
                $saldo = 0;

                $diferencia = ($efectivo_cierre + $transaccion_cierre) - (($ingresos_transaccion + $ingresos_efectivo + $valor_apertura['valor']) - ($retiros + $devoluciones));
            }
            if (!empty($tiene_cierre)) {
                $estado = 'CERRADA';
                $fecha_cierre = model('cierreModel')->select('fecha')->where('idapertura', $id_apertura)->first();
                $cierre = $fecha_cierre['fecha'];

                $fecha_y_hora_cierre = model('cierreModel')->select('fecha_y_hora_cierre')->where('idapertura', $id_apertura)->first();
                $efectivo = model('facturaFormaPagoModel')->ingresos_efectivo($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
                if (empty($efectivo)) {
                    $ingresos_efectivo = 0;
                } else if (!empty($efectivo)) {
                    $ingresos_efectivo = $efectivo[0]['ingresos_efectivo'];
                }

                $transaccion = model('facturaFormaPagoModel')->ingresos_transaccion($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
                if (empty($transaccion)) {
                    $ingresos_transaccion = 0;
                } else if (!empty($transaccion)) {
                    $ingresos_transaccion = $transaccion[0]['ingresos_transaccion'];
                }
                $valor_cierre = 0;
                $devolucion_venta = model('devolucionModel')->sumar_devoluciones($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);

                if (empty($devolucion_venta)) {
                    $devoluciones = 0;
                } else if (!empty($devolucion_venta)) {
                    $devoluciones = $devolucion_venta[0]['total_devoluciones'];
                }
                $id_cierre = model('cierreModel')->select('id')->where('idapertura', $id_apertura)->first();
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

                $diferencia = ($efectivo_cierre + $transaccion_cierre) - (($ingresos_transaccion + $ingresos_efectivo + $valor_apertura['valor']) - ($retiros + $devoluciones));
            }



            $returnData = array(
                "resultado" => 1,
                "datos" =>  view('consultas_y_reportes/edicion_consultas_caja', [
                    'estado' => $estado,
                    'fecha_apertura' => $fecha_apertura['fecha'],
                    'fecha_cierre' => $cierre,
                    'valor_apertura' => "$" . number_format($valor_apertura['valor'], 0, ",", "."),
                    'ingresos_efectivo' =>  "$" . number_format($ingresos_efectivo, 0, ",", "."),
                    'ingresos_transaccion' =>  "$" . number_format($ingresos_transaccion, 0, ",", "."),
                    'total_ingresos' =>  "$" . number_format($ingresos_transaccion + $ingresos_efectivo, 0, ",", "."),
                    'efectivo_cierre' => "$" . number_format($efectivo_cierre, 0, ",", "."),
                    'transaccion_cierre' => "$" . number_format($transaccion_cierre, 0, ",", "."),
                    'total_cierre' => "$" . number_format($efectivo_cierre + $transaccion_cierre, 0, ",", "."),
                    'devoluciones' => "$" . number_format($devoluciones, 0, ",", "."),
                    'retiros' => "$" . number_format($retiros, 0, ",", "."),
                    'retirosmasdevoluciones' => "$" . number_format($retiros + $devoluciones, 0, ",", "."),
                    'saldo_caja' => "$" . number_format(($valor_apertura['valor'] + $ingresos_transaccion + $ingresos_efectivo) - ($retiros + $devoluciones), 0, ",", "."),
                    'diferencia' => "$" . number_format($diferencia, 0, ",", "."),
                    // 'diferencia' => "$" . number_format(($efectivo_cierre + $transaccion_cierre) - (($ingresos_transaccion + $ingresos_efectivo) - ($retiros + $devoluciones)), 0, ",", "."),
                    'id_apertura' => $id_apertura
                ])
            );
            echo  json_encode($returnData);
        }
    }
    function editar_valor_cierre_transferencias()
    {
        //$id_apertura = 770;
        $id_apertura = $this->request->getPost('id_apertura');

        //$efectivo_cierre = "";
        $transaccion_cierre = "";
        $id_cierre = model('cierreModel')->select('id')->where('idapertura', $id_apertura)->first();


        if (empty($id_cierre)) {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        } else if (!empty($id_cierre)) {
            $valor_cierre_transaccion = model('cierreFormaPagoModel')->cierre_transaccion($id_cierre['id']);

            if (empty($valor_cierre_transaccion)) {
                $transaccion_cierre = 0;
            } else {
                $transaccion_cierre = $valor_cierre_transaccion[0]['valor'];
            }

            $returnData = array(
                "resultado" => 1,
                "valor_cierre" => number_format($transaccion_cierre, 0, ",", "."),
                "id_apertura" => $id_apertura
            );
            echo  json_encode($returnData);
        }
    }

    function actualizar_valor_cierre_transferencias()
    {
        $id_apertura = $this->request->getPost('id_apertura');

        //$id_apertura = 770;
        $id_cierre = model('cierreModel')->select('id')->where('idapertura', $id_apertura)->first();
        //$valor_cierre = 50.000;

        $valor_cierre = $this->request->getPost('valor_cierre_transferencia');

        $data = [
            'valor' => str_replace(".", "", $valor_cierre)
        ];

        $model = model('cierreFormaPagoModel');
        $actualizar = $model->set($data);
        $actualizar = $model->where('idcierre', $id_cierre['id']);
        $actualizar = $model->where('idpago', 4);
        $actualizar = $model->update();

        if ($actualizar) {

            $id_apertura = $id_apertura;

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

            $tiene_cierre = model('cierreModel')->select('fecha')->where('idapertura', $id_apertura)->first();
            $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
            $valor_apertura = model('aperturaModel')->select('valor')->where('id', $id_apertura)->first();
            $fecha_y_hora_apertura = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $id_apertura)->first();


            if (empty($tiene_cierre)) {
                $estado = "ABIERTA";
                $cierre = 'POR DEFINIR';
                $efectivo = model('facturaFormaPagoModel')->ingresos_efectivo($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d H:i:s'));
                if (empty($efectivo)) {
                    $ingresos_efectivo = 0;
                } else if (!empty($efectivo)) {
                    $ingresos_efectivo = $efectivo[0]['ingresos_efectivo'];
                }

                $transaccion = model('facturaFormaPagoModel')->ingresos_transaccion($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d H:i:s'));
                if (empty($transaccion)) {
                    $ingresos_transaccion = 0;
                } else if (!empty($transaccion)) {
                    $ingresos_transaccion = $transaccion[0]['ingresos_transaccion'];
                }
                $valor_cierre = 0;
                $devolucion_venta = model('devolucionModel')->sumar_devoluciones($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d H:i:s'));

                if (empty($devolucion_venta)) {
                    $devoluciones = 0;
                } else if (!empty($devolucion_venta)) {
                    $devoluciones = $devolucion_venta[0]['total_devoluciones'];
                }


                $total_retiros = model('retiroFormaPagoModel')->total_retiros($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d H:i:s'));

                if (empty($total_retiros[0]['total_retiros'])) {
                    $retiros = 0;
                }
                if (!empty($total_retiros[0]['total_retiros'])) {
                    $retiros = $total_retiros[0]['total_retiros'];
                }

                $efectivo_cierre = 0;
                $transaccion_cierre = 0;
                $saldo = 0;

                $diferencia = ($efectivo_cierre + $transaccion_cierre) - (($ingresos_transaccion + $ingresos_efectivo + $valor_apertura['valor']) - ($retiros + $devoluciones));
            }
            if (!empty($tiene_cierre)) {
                $estado = 'CERRADA';
                $fecha_cierre = model('cierreModel')->select('fecha')->where('idapertura', $id_apertura)->first();
                $cierre = $fecha_cierre['fecha'];

                $fecha_y_hora_cierre = model('cierreModel')->select('fecha_y_hora_cierre')->where('idapertura', $id_apertura)->first();
                $efectivo = model('facturaFormaPagoModel')->ingresos_efectivo($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
                if (empty($efectivo)) {
                    $ingresos_efectivo = 0;
                } else if (!empty($efectivo)) {
                    $ingresos_efectivo = $efectivo[0]['ingresos_efectivo'];
                }

                $transaccion = model('facturaFormaPagoModel')->ingresos_transaccion($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);
                if (empty($transaccion)) {
                    $ingresos_transaccion = 0;
                } else if (!empty($transaccion)) {
                    $ingresos_transaccion = $transaccion[0]['ingresos_transaccion'];
                }
                $valor_cierre = 0;
                $devolucion_venta = model('devolucionModel')->sumar_devoluciones($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre['fecha_y_hora_cierre']);

                if (empty($devolucion_venta)) {
                    $devoluciones = 0;
                } else if (!empty($devolucion_venta)) {
                    $devoluciones = $devolucion_venta[0]['total_devoluciones'];
                }
                $id_cierre = model('cierreModel')->select('id')->where('idapertura', $id_apertura)->first();
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

                $diferencia = ($efectivo_cierre + $transaccion_cierre) - (($ingresos_transaccion + $ingresos_efectivo + $valor_apertura['valor']) - ($retiros + $devoluciones));
            }



            $returnData = array(
                "resultado" => 1,
                "datos" =>  view('consultas_y_reportes/edicion_consultas_caja', [
                    'estado' => $estado,
                    'fecha_apertura' => $fecha_apertura['fecha'],
                    'fecha_cierre' => $cierre,
                    'valor_apertura' => "$" . number_format($valor_apertura['valor'], 0, ",", "."),
                    'ingresos_efectivo' =>  "$" . number_format($ingresos_efectivo, 0, ",", "."),
                    'ingresos_transaccion' =>  "$" . number_format($ingresos_transaccion, 0, ",", "."),
                    'total_ingresos' =>  "$" . number_format($ingresos_transaccion + $ingresos_efectivo, 0, ",", "."),
                    'efectivo_cierre' => "$" . number_format($efectivo_cierre, 0, ",", "."),
                    'transaccion_cierre' => "$" . number_format($transaccion_cierre, 0, ",", "."),
                    'total_cierre' => "$" . number_format($efectivo_cierre + $transaccion_cierre, 0, ",", "."),
                    'devoluciones' => "$" . number_format($devoluciones, 0, ",", "."),
                    'retiros' => "$" . number_format($retiros, 0, ",", "."),
                    'retirosmasdevoluciones' => "$" . number_format($retiros + $devoluciones, 0, ",", "."),
                    'saldo_caja' => "$" . number_format(($valor_apertura['valor'] + $ingresos_transaccion + $ingresos_efectivo) - ($retiros + $devoluciones), 0, ",", "."),
                    'diferencia' => "$" . number_format($diferencia, 0, ",", "."),
                    // 'diferencia' => "$" . number_format(($efectivo_cierre + $transaccion_cierre) - (($ingresos_transaccion + $ingresos_efectivo) - ($retiros + $devoluciones)), 0, ",", "."),
                    'id_apertura' => $id_apertura
                ])
            );
            echo  json_encode($returnData);
        }
    }


    function buscar_pedidos_borrados()
    {

        return view('consultas_y_reportes/buscar_pedidos_borrados');
    }

    function pedidos_borrados()
    {
        $fecha_inicial = $this->request->getPost('fecha_inicial');
        $fecha_final = $this->request->getPost('fecha_final');

        $pedidos_borrados = model('eliminacionPedidosModel')->where('fecha_eliminacion', $fecha_inicial)->findAll();
        $pedidos_borrados = model('eliminacionPedidosModel')->where('fecha_eliminacion', $fecha_final)->findAll();



        return view('consultas_y_reportes/pedidos_borrados', [
            'pedidos_borrados' => $pedidos_borrados
        ]);
    }

    function informe_fiscal_desde_caja()
    {



        $id_apertura = $this->request->getPost('id_apertura');
        // $id_apertura = 1174;

        //$id_apertura = 41;
        $fecha_y_hora_cierre = "";
        $ventas_credito = "";

        /**
         * Datos empresa 
         */

        $datos_empresa = model('empresaModel')->find();
        $id_regimen = $datos_empresa[0]['idregimen'];
        $regimen = model('regimenModel')->select('nombreregimen')->where('idregimen', $id_regimen)->first();
        $nombre_ciudad = model('ciudadModel')->select('nombreciudad')->where('idciudad', $datos_empresa[0]['idciudad'])->first();
        $nombre_departamento = model('departamentoModel')->select('nombredepartamento')->where('iddepartamento', $datos_empresa[0]['iddepartamento'])->first();

        /**
         * Datos de hora y aperura de la caja que se esta consultando 
         * 1. Si la caja no esta cerrada se le asigna la fecha y hora actual  
         */
        $fecha_y_hora_apertura = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $id_apertura)->first();
        $fecha_cierre = model('cierreModel')->select('fecha_y_hora_cierre')->where('idapertura', $id_apertura)->first();

        if (empty($fecha_cierre['fecha_y_hora_cierre'])) {
            $fecha_y_hora_cierre = date('Y-m-d H:i:s');
        } else if (!empty($fecha_cierre['fecha_y_hora_cierre'])) {
            $fecha_y_hora_cierre = $fecha_cierre['fecha_y_hora_cierre'];
        }
        /**
         * Registro inicial es la primer factura que se realiza de esa aperturta 
         * Registro final es la primer factura que se realiza de esa aperturta  
         */


        // $registro_inicial = model('pagosModel')->get_min_id($id_apertura);
        $id_inicial = model('pagosModel')->get_min_id($id_apertura);
        // $registro_final = model('pagosModel')->get_max_id($id_apertura);
        $id_final = model('pagosModel')->get_max_id($id_apertura);
        $total_registros = model('pagosModel')->get_total_registros($id_apertura);

        $reg_inicial = model('pagosModel')->select('documento')->where('id', $id_inicial[0]['id'])->first();
        $reg_final = model('pagosModel')->select('documento')->where('id', $id_final[0]['id'])->first();

        if (empty($reg_inicial)) {
            $registro_inicial = "";
        }
        if (!empty($reg_inicial)) {
            $registro_inicial = $reg_inicial['documento'];
        }
        if (empty($reg_final)) {
            $registro_final = "";
        }
        if (!empty($reg_final)) {
            $registro_final = $reg_final['documento'];
        }



        /**
         * Discriminación de las bases tributarias tanto iva como impuesto al consumo 
         */

        $iva = model('productoFacturaVentaModel')->fiscal_iva($id_apertura);
        $array_iva = array();



        if (!empty($iva)) {
            foreach ($iva as $detalle) {

                //$iva = model('kardexModel')->selectSum('iva')->where('id_apertura', $id_apertura)->find();
                // $iva = model('kardexModel')->selectSum('iva')->where('id_estado', 1)->find();
                //$iva = model('kardexModel')->selectSum('iva')->where('valor_iva', $detalle['valor_iva'])->find();

                $iva = model('kardexModel')->get_iva_fiscales($id_apertura, $detalle['valor_iva']);

                $total = model('kardexModel')->get_iva_fiscal($id_apertura, $detalle['valor_iva']);



                $data_iva['tarifa_iva'] =  $detalle['valor_iva'];
                $data_iva['base'] = $total[0]['total'] - $iva[0]['iva'];
                $data_iva['total_iva'] = $iva[0]['iva'];
                $data_iva['valor_venta'] = $total[0]['total'];
                array_push($array_iva, $data_iva);
            }
        } else {
            $data_iva['tarifa_iva'] =  0;
            $data_iva['base'] = 0;
            $data_iva['total_iva'] = 0;
            $data_iva['valor_venta'] = 0;
            array_push($array_iva, $data_iva);
        }



        $ico = model('productoFacturaVentaModel')->fiscal_ico($id_apertura);
        $array_ico = array();

        if (!empty($ico)) {


            foreach ($ico as $detalle) {
                $inc = model('kardexModel')->get_inc($id_apertura);

                $total = model('kardexModel')->total_inc($id_apertura);

                $data_ico['tarifa_ico'] =  $detalle['valor_ico'];          //ok
                $data_ico['base'] = $total[0]['total'] - $inc[0]['total'];
                $data_ico['total_ico'] = $inc[0]['total'];
                $data_ico['valor_venta'] = $total[0]['total'];
                array_push($array_ico, $data_ico);
            }
        } else {
            $data_ico['tarifa_ico'] =  0;
            $data_ico['base'] = 0;
            $data_ico['total_ico'] = 0;
            $data_ico['valor_venta'] = 0;
            array_push($array_ico, $data_ico);
        }


        /**
         * Total de ventas crédito y de contado 
         */


        //$vantas_contado = model('kardexModel')->ventas_contado($id_apertura);
        $vantas_contado = model('pagosModel')->ventas_contado($id_apertura);




        $venta_credito = model('facturaVentaModel')->venta_credito($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

        if (empty($venta_credito[0]['total_ventas_credito'])) {
            $ventas_credito = 0;
        } else if (!empty($venta_credito[0]['total_ventas_credito'])) {
            $ventas_credito = $venta_credito[0]['total_ventas_credito'];
        }

        /**
         *Devoluciones 
         */
        $iva_devolucion = model('devolucionModel')->tarifa_iva($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

        $array_devoluciones_iva = array();
        if (!empty($iva_devolucion)) {

            foreach ($iva_devolucion as $detalle) {

                $iva_devolucion = model('devolucionModel')->devolucion_iva($detalle['iva'], $fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

                $temp_porcentaje = $detalle['iva'] / 100;
                $sub_total = $iva_devolucion[0]['base'] * $temp_porcentaje;
                $total = $iva_devolucion[0]['base'] + $sub_total;
                $impuesto = $total - $iva_devolucion[0]['base'];

                $data_devo_iva['tarifa'] = $detalle['iva'];
                $data_devo_iva['base'] =  $iva_devolucion[0]['base'];
                $data_devo_iva['impuesto'] = $impuesto;
                $data_devo_iva['total'] = $total;
                array_push($array_devoluciones_iva, $data_devo_iva);
            }
        } else if (empty($iva_devolucion)) {

            $data_devo_iva['base'] =  0;
            $data_devo_iva['tarifa'] = 0;
            $data_devo_iva['impuesto'] = 0;
            $data_devo_iva['total'] = 0;
            array_push($array_devoluciones_iva, $data_devo_iva);
        }

        $ico_devolucion = model('devolucionModel')->tarifa_ico($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

        $array_devoluciones_ico = array();
        if (!empty($ico_devolucion)) {

            foreach ($ico_devolucion as $detalle) {

                $ico_devolucion = model('devolucionModel')->devolucion_ico($detalle['ico'], $fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

                $temp_porcentaje = $detalle['ico'] / 100;
                $sub_total = $ico_devolucion[0]['base'] * $temp_porcentaje;
                $total = $ico_devolucion[0]['base'] + $sub_total;
                $impuesto = $total - $ico_devolucion[0]['base'];

                $data_devo_ico['tarifa'] = $detalle['ico'];
                $data_devo_ico['base'] =  $ico_devolucion[0]['base'];
                $data_devo_ico['impuesto'] = $impuesto;
                $data_devo_ico['total'] = $total;
                array_push($array_devoluciones_ico, $data_devo_ico);
            }
        } else if (empty($ico_devolucion)) {

            $data_devo_ico['base'] =  0;
            $data_devo_ico['tarifa'] = 0;
            $data_devo_ico['impuesto'] = 0;
            $data_devo_ico['total'] = 0;
            array_push($array_devoluciones_ico, $data_devo_ico);
        }

        //$consecutivo_caja = model('cajaModel')->select('consecutivo')->where('numerocaja', 1)->first();
        $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
        //$existe_fecha_informe = model('consecutivoInformeModel')->select('fecha')->where('fecha', $fecha_apertura['fecha'])->first();

        $consecutivo_fiscal = model('consecutivoInformeModel')->select('numero')->where('id_apertura', $id_apertura)->first();

        $returnData = array(
            "resultado" => 1, //Falta plata
            "datos" => view('consultas_y_reportes/informe_fiscal_ventas', [
                "nombre_comercial" => $datos_empresa[0]['nombrecomercialempresa'],
                "nombre_juridico" => $datos_empresa[0]['nombrejuridicoempresa'],
                "nit" => $datos_empresa[0]['nitempresa'],
                "nombre_regimen" => $regimen['nombreregimen'],
                "direccion" => $datos_empresa[0]['direccionempresa'],
                "nombre_ciudad" => $nombre_ciudad['nombreciudad'],
                "nombre_departamento" => $nombre_departamento['nombredepartamento'],

                //"registro_inicial" => $registro_inicial[0]['id'],
                "registro_inicial" => $registro_inicial,
                // "registro_final" => $registro_final[0]['id'],
                "registro_final" => $registro_final,
                "total_registros" => $total_registros[0]['total_registros'],
                "iva" => $array_iva,
                "ico" => $array_ico,
                "vantas_contado" => $vantas_contado[0]['total'],

                "iva_devolucion" => $array_devoluciones_iva,
                "ico_devolucion" => $array_devoluciones_ico,

                "consecutivo" => $consecutivo_fiscal['numero'],
                "fecha_apertura" => $fecha_apertura['fecha'],
                "id_apertura" => $id_apertura,
                "action_url" => base_url('consultas_y_reportes/expotar_informe_ventas_pdf'),
                "titulo" => "INFORME FISCAL DE VENTAS DIARIAS",
            ])
        );
        echo  json_encode($returnData);
    }

    function informe_fiscal_electronico()
    {



        $id_apertura = $this->request->getPost('id_apertura');
        //$id_apertura = 68;

        //$id_apertura = 41;
        $fecha_y_hora_cierre = "";
        $ventas_credito = "";

        /**
         * Datos empresa 
         */

        $datos_empresa = model('empresaModel')->find();
        $id_regimen = $datos_empresa[0]['idregimen'];
        $regimen = model('regimenModel')->select('nombreregimen')->where('idregimen', $id_regimen)->first();
        $nombre_ciudad = model('ciudadModel')->select('nombreciudad')->where('idciudad', $datos_empresa[0]['idciudad'])->first();
        $nombre_departamento = model('departamentoModel')->select('nombredepartamento')->where('iddepartamento', $datos_empresa[0]['iddepartamento'])->first();

        /**
         * Datos de hora y aperura de la caja que se esta consultando 
         * 1. Si la caja no esta cerrada se le asigna la fecha y hora actual  
         */
        $fecha_y_hora_apertura = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $id_apertura)->first();
        $fecha_cierre = model('cierreModel')->select('fecha_y_hora_cierre')->where('idapertura', $id_apertura)->first();

        if (empty($fecha_cierre['fecha_y_hora_cierre'])) {
            $fecha_y_hora_cierre = date('Y-m-d H:i:s');
        } else if (!empty($fecha_cierre['fecha_y_hora_cierre'])) {
            $fecha_y_hora_cierre = $fecha_cierre['fecha_y_hora_cierre'];
        }
        /**
         * Registro inicial es la primer factura que se realiza de esa aperturta 
         * Registro final es la primer factura que se realiza de esa aperturta  
         */


        // $registro_inicial = model('pagosModel')->get_min_id($id_apertura);
        $id_inicial = model('pagosModel')->get_min_id_electronico($id_apertura);
        // $registro_final = model('pagosModel')->get_max_id($id_apertura);
        $id_final = model('pagosModel')->get_max_id_electronico($id_apertura);
        $total_registros = model('pagosModel')->get_total_registros_electronicos($id_apertura);

        // $reg_inicial = model('pagosModel')->select('documento')->where('id', $id_inicial[0]['id'])->first();

        $id_factura_min = model('pagosModel')->select('id_factura')->where('id', $id_inicial[0]['id'])->first();
        $id_factura_max = model('pagosModel')->select('id_factura')->where('id', $id_final[0]['id'])->first();




        $reg_inicial = model('facturaElectronicaModel')->select('numero')->where('id', $id_factura_min['id_factura'])->first();
        // $reg_final = model('pagosModel')->select('documento')->where('id', $id_final[0]['id'])->first();
        $reg_final = model('facturaElectronicaModel')->select('numero')->where('id', $id_factura_max['id_factura'])->first();

        //$registro_ini_final = model('facturaElectronicaModel')->inicial_final($id_factura_min['id_factura'], $id_factura_max['id_factura']);



        //dd($registro_ini_final);



       /*  if (empty($registro_ini_final[0]['primer_registro'])) {
            $registro_inicial = "";
        }
        if (!empty($registro_ini_final[0]['primer_registro'])) {
            //$registro_inicial = $reg_inicial['numero'];
            $registro_inicial = $registro_ini_final[0]['primer_registro'];
        }
        if (empty($registro_ini_final[0]['ultimo_registro'])) {
            $registro_final = "";
        }
        if (!empty($registro_ini_final[0]['ultimo_registro'])) {
            //$registro_final = $reg_final['numero'];
            $registro_final = $registro_ini_final[0]['ultimo_registro'];
        } */



        //dd($registro_inicial);


        /**
         * Discriminación de las bases tributarias tanto iva como impuesto al consumo 
         */

        $iva = model('pagosModel')->fiscal_iva($id_apertura);
        $array_iva = array();



        if (!empty($iva)) {
            foreach ($iva as $detalle) {

                //$iva = model('kardexModel')->selectSum('iva')->where('id_apertura', $id_apertura)->find();
                // $iva = model('kardexModel')->selectSum('iva')->where('id_estado', 1)->find();
                //$iva = model('kardexModel')->selectSum('iva')->where('valor_iva', $detalle['valor_iva'])->find();

                $iva = model('kardexModel')->get_iva_electronico($id_apertura, $detalle['valor_iva']);

                $total = model('kardexModel')->total_iva_electronico($id_apertura, $detalle['valor_iva']);



                $data_iva['tarifa_iva'] =  $detalle['valor_iva'];
                $data_iva['base'] = $total[0]['total'] - $iva[0]['iva'];
                $data_iva['total_iva'] = $iva[0]['iva'];
                $data_iva['valor_venta'] = $total[0]['total'];
                array_push($array_iva, $data_iva);
            }
        } else {
            $data_iva['tarifa_iva'] =  0;
            $data_iva['base'] = 0;
            $data_iva['total_iva'] = 0;
            $data_iva['valor_venta'] = 0;
            array_push($array_iva, $data_iva);
        }



        $ico = model('kardexModel')->fiscal_ico($id_apertura);
        $array_ico = array();

        if (!empty($ico)) {


            foreach ($ico as $detalle) {
                $inc = model('kardexModel')->get_inc_electronico($id_apertura);

                $total = model('kardexModel')->total_inc_electronico($id_apertura);

                $data_ico['tarifa_ico'] =  $detalle['valor_ico'];          //ok
                $data_ico['base'] = $total[0]['total'] - $inc[0]['total'];
                $data_ico['total_ico'] = $inc[0]['total'];
                $data_ico['valor_venta'] = $total[0]['total'];
                array_push($array_ico, $data_ico);
            }
        } else {
            $data_ico['tarifa_ico'] =  0;
            $data_ico['base'] = 0;
            $data_ico['total_ico'] = 0;
            $data_ico['valor_venta'] = 0;
            array_push($array_ico, $data_ico);
        }


        /**
         * Total de ventas crédito y de contado 
         */

        //$vantas_contado = model('facturaVentaModel')->venta_contado($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);
        // $vantas_contado = model('productoFacturaVentaModel')->get_total_venta($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);
        $vantas_contado = model('kardexModel')->ventas_contado_electronicas($id_apertura);




        $venta_credito = model('facturaVentaModel')->venta_credito($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

        if (empty($venta_credito[0]['total_ventas_credito'])) {
            $ventas_credito = 0;
        } else if (!empty($venta_credito[0]['total_ventas_credito'])) {
            $ventas_credito = $venta_credito[0]['total_ventas_credito'];
        }

        /**
         *Devoluciones 
         */
        //$iva_devolucion = model('devolucionModel')->tarifa_iva($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

        $iva_devolucion = model('devolucionModel')->tarifa_iva($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

        $array_devoluciones_iva = array();
        // if (!empty($iva_devolucion)) {

        foreach ($iva_devolucion as $detalle) {

            $aplica_ico = model('productoModel')->select('aplica_ico')->where('codigointernoproducto', $detalle['codigo'])->first();

            if ($aplica_ico['aplica_ico'] == 't') {
                $iva_devolucion = model('devolucionModel')->devolucion_iva($detalle['iva'], $fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre, $detalle['codigo']);

                $temp_porcentaje = $detalle['iva'] / 100;
                $sub_total = $iva_devolucion[0]['base'] * $temp_porcentaje;
                $total = $iva_devolucion[0]['base'] + $sub_total;
                $impuesto = $total - $iva_devolucion[0]['base'];

                $data_devo_iva['tarifa'] = $detalle['iva'];
                $data_devo_iva['base'] =  $iva_devolucion[0]['base'];
                $data_devo_iva['impuesto'] = $impuesto;
                $data_devo_iva['total'] = $total;
                array_push($array_devoluciones_iva, $data_devo_iva);
            }
        }
        /* } else if (empty($iva_devolucion)) {

            $data_devo_iva['base'] =  0;
            $data_devo_iva['tarifa'] = 0;
            $data_devo_iva['impuesto'] = 0;
            $data_devo_iva['total'] = 0;
            array_push($array_devoluciones_iva, $data_devo_iva);
        } */

        $ico_devolucion = model('devolucionModel')->tarifa_ico($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

        $array_devoluciones_ico = array();
        if (!empty($ico_devolucion)) {

            foreach ($ico_devolucion as $detalle) {

                $ico_devolucion = model('devolucionModel')->devolucion_ico($detalle['ico'], $fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

                $temp_porcentaje = $detalle['ico'] / 100;
                $sub_total = $ico_devolucion[0]['base'] * $temp_porcentaje;
                $total = $ico_devolucion[0]['base'] + $sub_total;
                $impuesto = $total - $ico_devolucion[0]['base'];

                $data_devo_ico['tarifa'] = $detalle['ico'];
                $data_devo_ico['base'] =  $ico_devolucion[0]['base'];
                $data_devo_ico['impuesto'] = $impuesto;
                $data_devo_ico['total'] = $total;
                array_push($array_devoluciones_ico, $data_devo_ico);
            }
        } else if (empty($ico_devolucion)) {

            $data_devo_ico['base'] =  0;
            $data_devo_ico['tarifa'] = 0;
            $data_devo_ico['impuesto'] = 0;
            $data_devo_ico['total'] = 0;
            array_push($array_devoluciones_ico, $data_devo_ico);
        }

        //$consecutivo_caja = model('cajaModel')->select('consecutivo')->where('numerocaja', 1)->first();
        $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
        //$existe_fecha_informe = model('consecutivoInformeModel')->select('fecha')->where('fecha', $fecha_apertura['fecha'])->first();

        $consecutivo_fiscal = model('consecutivoInformeModel')->select('numero')->where('id_apertura', $id_apertura)->first();

        $returnData = array(
            "resultado" => 1, //Falta plata
            "datos" => view('consultas_y_reportes/informe_fiscal_ventas', [
                "nombre_comercial" => $datos_empresa[0]['nombrecomercialempresa'],
                "nombre_juridico" => $datos_empresa[0]['nombrejuridicoempresa'],
                "nit" => $datos_empresa[0]['nitempresa'],
                "nombre_regimen" => $regimen['nombreregimen'],
                "direccion" => $datos_empresa[0]['direccionempresa'],
                "nombre_ciudad" => $nombre_ciudad['nombreciudad'],
                "nombre_departamento" => $nombre_departamento['nombredepartamento'],

                //"registro_inicial" => $registro_inicial[0]['id'],
                "registro_inicial" => $reg_inicial['numero'],
                // "registro_final" => $registro_final[0]['id'],
                "registro_final" => $reg_final['numero'],
                "total_registros" => $total_registros[0]['total_registros'],
                "iva" => $array_iva,
                "ico" => $array_ico,
                "vantas_contado" => $vantas_contado[0]['total'],
                //"vantas_contado" => 0,

                "iva_devolucion" => $array_devoluciones_iva,
                "ico_devolucion" => $array_devoluciones_ico,

                "consecutivo" => $consecutivo_fiscal['numero'],
                "fecha_apertura" => $fecha_apertura['fecha'],
                "id_apertura" => $id_apertura,
                "titulo" => "INFORME FISCAL DE VENTAS ELECTRÓNICAS",
                //"action"=>base_url('consultas_y_reportes/expotar_informe_ventas_pdf')
                "action_url" => base_url('consultas_y_reportes/expotar_informe_electronico_pdf')
            ])
        );

        echo  json_encode($returnData);
    }
}
