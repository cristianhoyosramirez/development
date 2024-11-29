<?php

namespace App\Controllers\consultas_y_reportes;

require APPPATH . "Controllers/mike42/autoload.php";

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;


class informeFiscalVentasController extends BaseController
{
    public function index()
    {
        return view('home/home');
    }

    public function informe_fiscal_ventas()
    {
        return view('consultas_y_reportes/informe_fiscal_ventas_diarias');
    }
    public function informe_fiscal_ventas_datos()
    {
        $datos_empresa = model('empresaModel')->find();
        $id_regimen = $datos_empresa[0]['idregimen'];
        $regimen = model('regimenModel')->select('nombreregimen')->where('idregimen', $id_regimen)->first();
        $nombre_ciudad = model('ciudadModel')->select('nombreciudad')->where('idciudad', $datos_empresa[0]['idciudad'])->first();
        $nombre_departamento = model('departamentoModel')->select('nombredepartamento')->where('iddepartamento', $datos_empresa[0]['iddepartamento'])->first();

        $fecha_factura = $_POST['fecha'];

        //$fecha_factura = '2023-03-22';
        if (!empty($fecha_factura)) {


            $hay_venta = model('FacturaVentaModel')->select('*')->where('fecha_factura_venta', $fecha_factura)->findAll();

            if (!empty($hay_venta)) {

                $id_factura_primero = model('facturaVentaModel')->selectMin('id')->where('fecha_factura_venta', $fecha_factura)->findAll();
                $id_factura_final = model('facturaVentaModel')->selectMax('id')->where('fecha_factura_venta', $fecha_factura)->findAll();


                $registro_inicial = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura_primero[0]['id'])->first();
                $registro_final = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura_final[0]['id'])->first();

                $total_registros = model('facturaVentaModel')->selectCount('id')->where('fecha_factura_venta', $fecha_factura)->find();

                // $porcentaje_iva = model('productoFacturaVentaModel')->iva($registro_inicial['numerofactura_venta'], $registro_final['numerofactura_venta']);
                $porcentaje_iva = model('productoFacturaVentaModel')->iva($id_factura_primero[0]['id'], $id_factura_final[0]['id']);


                $valores_iva = array();

                foreach ($porcentaje_iva as $detalle) {

                    $valor_iva = model('productoFacturaVentaModel')->valor_iva($detalle['valor_iva'], $id_factura_primero[0]['id'], $id_factura_final[0]['id']);
                    if (!empty($valor_iva)) {
                        $prueba = array();
                        array_push($prueba, $valor_iva[0]['tarifa_iva']); //0 = tarifa de iva 
                        array_push($prueba, $valor_iva[0]['base']);  // 1 = base (impuesto antes de iva )
                        array_push($prueba, $valor_iva[0]['total_iva']); // 2 = total del iva 
                        array_push($prueba, $valor_iva[0]['total']);  //3  total = Base + total
                        array_push($valores_iva, $prueba);
                    } else if (empty($valor_iva)) {
                        $prueba = array();
                        $valores_iva = array();
                        array_push($prueba, 0); //Base
                        array_push($prueba, 0); //Tarifa
                        array_push($prueba, 0); //Impuesto
                        array_push($prueba, 0); //total
                        array_push($valores_iva, $prueba);
                    }
                }



                $porcentaje_ico = model('productoFacturaVentaModel')->ico($id_factura_primero[0]['id'], $id_factura_final[0]['id']);


                //dd($porcentaje_ico);

                $valores_ico = array();
                //echo $id_factura_primero[0]['id']; 
                //echo $id_factura_final[0]['id'];
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

                    //var_dump($tarifa_iva_devolucion); exit();

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
                    /*  $iva_de_devolucion[] = 0;
                    $iva_de_devolucion[] = 0;
                    $iva_de_devolucion[] = 0;
                    $iva_de_devolucion[] = 0; */
                    // $iva_de_devolucion=1;
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

                    //dd($ico_de_devolucion);
                    $consecutivo_informe = model('consecutivoInformeModel')->select('numero')->where('fecha', $fecha_factura)->first();
                    $impuesto_saludable = model('impuestoSaludableModel')->findAll();


                    $informe = view('consultas_y_reportes/informe_fiscal_ventas_diarias_datos', [
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
                        "fecha" => $fecha_factura,
                        "impuesto_saludable" => $impuesto_saludable

                    ]);

                    $returnData = array(
                        "resultado" => 0, //Falta plata ,
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




    public function informe_fiscal_ventas_pdf()
    {
        $fecha_factura = $_REQUEST['fecha_reporte'];

        $prueba = $this->generar_informe_fiscal_ventas_pdf($fecha_factura);
    }
    public function generar_informe_fiscal_ventas_pdf($fecha_factura)
    {

        $dompdf = new Dompdf();

        $options = $dompdf->getOptions();

        $options->set(array('isRemoteEnable' => true));
        $dompdf->setOptions($options);


        $datos_empresa = model('empresaModel')->find();
        $id_regimen = $datos_empresa[0]['idregimen'];
        $regimen = model('regimenModel')->select('nombreregimen')->where('idregimen', $id_regimen)->first();
        $nombre_ciudad = model('municipiosModel')->select('nombreciudad')->where('idciudad', $datos_empresa[0]['idciudad'])->first();
        $nombre_departamento = model('departamentoModel')->select('nombredepartamento')->where('iddepartamento', $datos_empresa[0]['iddepartamento'])->first();

        $fecha_factura = $fecha_factura;

        $id_factura_primero = model('facturaVentaModel')->selectMin('id')->where('fecha_factura_venta', $fecha_factura)->findAll();
        $id_factura_final = model('facturaVentaModel')->selectMax('id')->where('fecha_factura_venta', $fecha_factura)->findAll();

        $registro_inicial = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura_primero[0]['id'])->first();
        $registro_final = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura_final[0]['id'])->first();


        $total_registros = model('facturaVentaModel')->selectCount('id')->where('fecha_factura_venta', $fecha_factura)->find();

        $porcentaje_iva = model('productoFacturaVentaModel')->iva($id_factura_primero[0]['id'], $id_factura_final[0]['id']);

        //dd($porcentaje_iva);

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

                //echo number_format($iva_devolucion[0]['base'])."</br>";
            }
        } else {
            /*   $iva_de_devolucion[] = 0;
            $iva_de_devolucion[] = 0;
            $iva_de_devolucion[] = 0;
            $iva_de_devolucion[] = 0; */

            /*  array_push($total_iva, 0); //Base
            array_push($total_iva, 0); //Tarifa
            array_push($total_iva, 0); //Impuesto
            array_push($total_iva, 0); //total
            array_push($iva_de_devolucion, $total_iva); */
            // $iva_de_devolucion = 0;
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

            $consecutivo_informe = model('consecutivoInformeModel')->select('numero')->where('fecha', $fecha_factura)->first();
            $dompdf->loadHtml(view('consultas_y_reportes/informe_fiscal_ventas_diarias_datos_pdf', [
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

            ]));

            $options = $dompdf->getOptions();
            $dompdf->setPaper('letter');
            $dompdf->render();
            $dompdf->stream("Informe fiscal $fecha_factura.pdf", array("Attachment" => true));
        }
    }


    function expotar_informe_ventas_pdf()
    {

        $dompdf = new Dompdf();

        $options = $dompdf->getOptions();

        $options->set(array('isRemoteEnable' => true));
        $dompdf->setOptions($options);

        $id_apertura = $this->request->getPost('id_apertura');
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

        /*  $registro_inicial = model('facturaVentaModel')->registro_inicial($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);
        $registro_final = model('facturaVentaModel')->registro_final($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);
        $total_registros = model('facturaVentaModel')->total_registros($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre); */


        //$registro_inicial = model('pagosModel')->get_min_id($id_apertura);
        //$registro_final = model('pagosModel')->get_max_id($id_apertura);

        $id_inicial = model('pagosModel')->get_min_id($id_apertura);
        $id_final = model('pagosModel')->get_max_id($id_apertura);

        $total_registros = model('pagosModel')->get_total_registros($id_apertura);

        $registro_inicial = model('pagosModel')->select('documento')->where('id', $id_inicial[0]['id'])->first();
        $registro_final = model('pagosModel')->select('documento')->where('id', $id_final[0]['id'])->first();


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

                /*  $iva = model('kardexModel')->selectSum('iva')->where('id_apertura', $id_apertura)->find();
                $iva = model('kardexModel')->selectSum('iva')->where('id_estado', 1)->find();
                $iva = model('kardexModel')->selectSum('iva')->where('valor_iva', $detalle['valor_iva'])->find();

                $total = model('kardexModel')->selectSum('total')->where('id_apertura', $id_apertura)->find();
                $total = model('kardexModel')->selectSum('total')->where('id_estado', 1)->find();
                $data_iva['tarifa_iva'] =  $detalle['valor_iva'];
                $data_iva['base'] = $total[0]['total'] - $iva[0]['iva'];
                $data_iva['total_iva'] = $iva[0]['iva'];
                $data_iva['valor_venta'] = $total[0]['total']; */

                /*     $iva = model('kardexModel')->selectSum('iva')->where('id_apertura', $id_apertura)->find();
                $iva = model('kardexModel')->selectSum('iva')->where('id_estado', 1)->find();
                $iva = model('kardexModel')->selectSum('iva')->where('valor_iva', $detalle['valor_iva'])->find(); */

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
                /*   $valor_ico = ($detalle['valor_ico'] / 100) + 1;
                $datos_ico = model('productoFacturaVentaModel')->datos_ico($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre, $detalle['valor_ico']);
                $data_ico['tarifa_ico'] =  $datos_ico[0]['tarifa_ico'];
                $data_ico['base'] = $datos_ico[0]['base']/ $valor_ico;
                $data_ico['total_ico'] = $datos_ico[0]['total_ico'];
                $data_ico['valor_venta'] = $datos_ico[0]['total']; */
                $inc = model('kardexModel')->get_inc($id_apertura);

                $total = model('kardexModel')->total_inc($id_apertura);

                $data_ico['tarifa_ico'] =  $detalle['valor_ico'];
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
        $venta_credito = model('facturaVentaModel')->venta_credito($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

        //$vantas_contado = model('productoFacturaVentaModel')->get_total_venta($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

        //$vantas_contado = model('kardexModel')->ventas_contado($id_apertura);

        $vantas_contado = model('pagosModel')->ventas_contado($id_apertura);

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

        $consecutivo_caja = model('cajaModel')->select('consecutivo')->where('numerocaja', 1)->first();
        $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
        $existe_fecha_informe = model('consecutivoInformeModel')->select('fecha')->where('fecha', $fecha_apertura['fecha'])->first();
        $consecutivo_fiscal = model('consecutivoInformeModel')->select('numero')->where('id_apertura', $id_apertura)->first();
        $dompdf->loadHtml(view('consultas_y_reportes/informe_fiscal_ventas_pdf', [
            "nombre_comercial" => $datos_empresa[0]['nombrecomercialempresa'],
            "nombre_juridico" => $datos_empresa[0]['nombrejuridicoempresa'],
            "nit" => $datos_empresa[0]['nitempresa'],
            "nombre_regimen" => $regimen['nombreregimen'],
            "direccion" => $datos_empresa[0]['direccionempresa'],
            "nombre_ciudad" => $nombre_ciudad['nombreciudad'],
            "nombre_departamento" => $nombre_departamento['nombredepartamento'],
            "registro_inicial" => $registro_inicial,
            //"registro_inicial" => $registro_inicial[0]['id'],
            "registro_final" => $registro_final,
            //"registro_final" => $registro_final[0]['id'],
            //"total_registros" => $total_registros[0]['total_registros'],
            "total_registros" => $total_registros[0]['total_registros'],
            "iva" => $array_iva,
            "ico" => $array_ico,
            "vantas_contado" => $vantas_contado[0]['total'],
            "iva_devolucion" => $array_devoluciones_iva,
            "ico_devolucion" => $array_devoluciones_ico,
            "consecutivo" => $consecutivo_fiscal['numero'],
            //"consecutivo" => $consecutivo_caja['consecutivo'],
            "fecha_apertura" => $fecha_apertura['fecha'],
            "id_apertura" => $id_apertura,
            "fecha" => $fecha_apertura['fecha'],
            "titulo" => "INFORME DE VENTAS DIARIAS "

        ]));

        $fecha = $fecha_apertura['fecha'];

        $options = $dompdf->getOptions();
        $dompdf->setPaper('letter');
        $dompdf->render();
        $dompdf->stream("Informe fiscal $fecha.pdf", array("Attachment" => true));
    }

    function expotar_informe_electronico_pdf()
    {

        $dompdf = new Dompdf();

        $options = $dompdf->getOptions();

        $options->set(array('isRemoteEnable' => true));
        $dompdf->setOptions($options);

        $id_apertura = $this->request->getPost('id_apertura');
        //$id_apertura = 34; 
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

        /*  $registro_inicial = model('facturaVentaModel')->registro_inicial($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);
        $registro_final = model('facturaVentaModel')->registro_final($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);
        $total_registros = model('facturaVentaModel')->total_registros($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre); */


        //$registro_inicial = model('pagosModel')->get_min_id($id_apertura);
        //$registro_final = model('pagosModel')->get_max_id($id_apertura);
        

        $id_inicial = model('pagosModel')->get_min_id_electronico($id_apertura);
        
        // $registro_final = model('pagosModel')->get_max_id($id_apertura);
        $id_final = model('pagosModel')->get_max_id_electronico($id_apertura);


        $id_factura_min = model('pagosModel')->select('id_factura')->where('id', $id_inicial[0]['id'])->first();
        $id_factura_max = model('pagosModel')->select('id_factura')->where('id', $id_final[0]['id'])->first();




        // $reg_inicial = model('facturaElectronicaModel')->select('numero')->where('id', $id_factura_min['id_factura'])->first();
        // $reg_final = model('pagosModel')->select('documento')->where('id', $id_final[0]['id'])->first();
        // $reg_final = model('facturaElectronicaModel')->select('numero')->where('id', $id_factura_max['id_factura'])->first();

        $total_registros = model('pagosModel')->get_total_registros_electronicos($id_apertura);

        $registro_ini_final = model('facturaElectronicaModel')->inicial_final($id_factura_min['id_factura'], $id_factura_max['id_factura']);

        //echo  $registro_ini_final[0]['primer_registro'];

        //dd($registro_ini_final);



        if (empty($registro_ini_final[0]['primer_registro'])) {
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
        }



        $reg_inicial = model('facturaElectronicaModel')->select('numero')->where('id', $id_factura_min['id_factura'])->first();
        // $reg_final = model('pagosModel')->select('documento')->where('id', $id_final[0]['id'])->first();
        $reg_final = model('facturaElectronicaModel')->select('numero')->where('id', $id_factura_max['id_factura'])->first();

        if (empty($reg_inicial)) {
            $registro_inicial = "";
        }
        if (!empty($reg_inicial)) {
            $registro_inicial = $reg_inicial['numero'];
        }
        if (empty($reg_final)) {
            $registro_final = "";
        }
        if (!empty($reg_final)) {
            $registro_final = $reg_final['numero'];
        }



        /**
         * Discriminación de las bases tributarias tanto iva como impuesto al consumo 
         */

        $iva = model('pagosModel')->fiscal_iva($id_apertura);
        $array_iva = array();


        if (!empty($iva)) {
            foreach ($iva as $detalle) {

                /*  $iva = model('kardexModel')->selectSum('iva')->where('id_apertura', $id_apertura)->find();
                $iva = model('kardexModel')->selectSum('iva')->where('id_estado', 1)->find();
                $iva = model('kardexModel')->selectSum('iva')->where('valor_iva', $detalle['valor_iva'])->find();

                $total = model('kardexModel')->selectSum('total')->where('id_apertura', $id_apertura)->find();
                $total = model('kardexModel')->selectSum('total')->where('id_estado', 1)->find();
                $data_iva['tarifa_iva'] =  $detalle['valor_iva'];
                $data_iva['base'] = $total[0]['total'] - $iva[0]['iva'];
                $data_iva['total_iva'] = $iva[0]['iva'];
                $data_iva['valor_venta'] = $total[0]['total']; */

                /*     $iva = model('kardexModel')->selectSum('iva')->where('id_apertura', $id_apertura)->find();
                $iva = model('kardexModel')->selectSum('iva')->where('id_estado', 1)->find();
                $iva = model('kardexModel')->selectSum('iva')->where('valor_iva', $detalle['valor_iva'])->find(); 

                $iva = model('kardexModel')->get_iva_fiscales($id_apertura, $detalle['valor_iva']);

                $total = model('kardexModel')->get_iva_fiscal($id_apertura, $detalle['valor_iva']);*/


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

        $ico = model('productoFacturaVentaModel')->fiscal_ico($id_apertura);
        $array_ico = array();

        if (!empty($ico)) {
            foreach ($ico as $detalle) {
                /*   $valor_ico = ($detalle['valor_ico'] / 100) + 1;
                $datos_ico = model('productoFacturaVentaModel')->datos_ico($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre, $detalle['valor_ico']);
                $data_ico['tarifa_ico'] =  $datos_ico[0]['tarifa_ico'];
                $data_ico['base'] = $datos_ico[0]['base']/ $valor_ico;
                $data_ico['total_ico'] = $datos_ico[0]['total_ico'];
                $data_ico['valor_venta'] = $datos_ico[0]['total']; 
                $inc = model('kardexModel')->get_inc($id_apertura);

                $total = model('kardexModel')->total_inc($id_apertura);*/


                $inc = model('kardexModel')->get_inc_electronico($id_apertura);

                $total = model('kardexModel')->total_inc_electronico($id_apertura);

                $data_ico['tarifa_ico'] =  $detalle['valor_ico'];
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
        $venta_credito = model('facturaVentaModel')->venta_credito($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

        //$vantas_contado = model('productoFacturaVentaModel')->get_total_venta($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

        // $vantas_contado = model('kardexModel')->ventas_contado($id_apertura);

        $vantas_contado = model('kardexModel')->ventas_contado_electronicas($id_apertura);

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

                /*   $iva_devolucion = model('devolucionModel')->devolucion_iva($detalle['iva'], $fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

                $temp_porcentaje = $detalle['iva'] / 100;
                $sub_total = $iva_devolucion[0]['base'] * $temp_porcentaje;
                $total = $iva_devolucion[0]['base'] + $sub_total;
                $impuesto = $total - $iva_devolucion[0]['base'];

                $data_devo_iva['tarifa'] = $detalle['iva'];
                $data_devo_iva['base'] =  $iva_devolucion[0]['base'];
                $data_devo_iva['impuesto'] = $impuesto;
                $data_devo_iva['total'] = $total;
                array_push($array_devoluciones_iva, $data_devo_iva); */

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
        } /* else if (empty($iva_devolucion)) {

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

        $consecutivo_caja = model('cajaModel')->select('consecutivo')->where('numerocaja', 1)->first();
        $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
        $existe_fecha_informe = model('consecutivoInformeModel')->select('fecha')->where('fecha', $fecha_apertura['fecha'])->first();
        $consecutivo_fiscal = model('consecutivoInformeModel')->select('numero')->where('id_apertura', $id_apertura)->first();
        $dompdf->loadHtml(view('consultas_y_reportes/informe_fiscal_ventas_pdf', [
            "nombre_comercial" => $datos_empresa[0]['nombrecomercialempresa'],
            "nombre_juridico" => $datos_empresa[0]['nombrejuridicoempresa'],
            "nit" => $datos_empresa[0]['nitempresa'],
            "nombre_regimen" => $regimen['nombreregimen'],
            "direccion" => $datos_empresa[0]['direccionempresa'],
            "nombre_ciudad" => $nombre_ciudad['nombreciudad'],
            "nombre_departamento" => $nombre_departamento['nombredepartamento'],
            "registro_inicial" => $registro_inicial,
            //"registro_inicial" => $registro_inicial[0]['id'],
            "registro_final" => $registro_final,
            //"registro_final" => $registro_final[0]['id'],
            //"total_registros" => $total_registros[0]['total_registros'],
            "total_registros" => $total_registros[0]['total_registros'],
            "iva" => $array_iva,
            "ico" => $array_ico,
            "vantas_contado" => $vantas_contado[0]['total'],
            "iva_devolucion" => $array_devoluciones_iva,
            "ico_devolucion" => $array_devoluciones_ico,
            "consecutivo" => $consecutivo_fiscal['numero'],
            //"consecutivo" => $consecutivo_caja['consecutivo'],
            "fecha_apertura" => $fecha_apertura['fecha'],
            "id_apertura" => $id_apertura,
            "fecha" => $fecha_apertura['fecha'],
            "titulo" => "INFORME FISCAL DE VENTAS ELECTRÓNICAS"

        ]));

        $fecha = $fecha_apertura['fecha'];

        $options = $dompdf->getOptions();
        $dompdf->setPaper('letter');
        $dompdf->render();
        $dompdf->stream("Informe fiscal $fecha.pdf", array("Attachment" => true));
    }

    function fiscal_manual_pdf()
    {
        $fecha = $this->request->getVar('fecha_manual');

        $consecutivo = $this->request->getVar('consecutivo');
        $registro_inicial = $this->request->getVar('registro_inicial');
        $registro_final = $this->request->getVar('registro_final');
        $total_registros = $this->request->getVar('total_registros');
        $base_ico = str_replace(".", "", $this->request->getVar('base_ico_manual'));
        $valor_impuesto = str_replace(".", "", $this->request->getVar('valor_impuesto_8_manual'));
        $total = str_replace(".", "", $this->request->getVar('total_venta_8_manual'));

        $data = [
            'fecha' => $fecha,
            'base_0' => '0',
            'base_ico' => str_replace(",", ".", $base_ico),
            'ico' => str_replace(",", ".", $valor_impuesto),
            'caja' => 1,
            'registro_inicial' => $registro_inicial,
            'registro_final' => $registro_final,
            'total_registros' => $total_registros,
            'consecutivo' => $consecutivo,
            'total' => $total
        ];

        $fecha_existe = model('fiscalModel')->where('fecha', $fecha)->first();

        if (empty($fecha_existe['id'])) {
            $insert = model('fiscalModel')->insert($data);
        } else if (!empty($fecha_existe['id'])) {
            $fiscal = model('fiscalModel');
            $actualizar = $fiscal->set($data);
            $actualizar = $fiscal->where('id', $fecha_existe['id']);
            $actualizar = $fiscal->update();
        }

        $movimientos = model('fiscalModel')->select('*')->where('fecha', $fecha)->first();

        $dompdf = new Dompdf();

        $options = $dompdf->getOptions();

        $options->set(array('isRemoteEnable' => true));
        $dompdf->setOptions($options);

        $datos_empresa = model('empresaModel')->find();
        $id_regimen = $datos_empresa[0]['idregimen'];
        $regimen = model('regimenModel')->select('nombreregimen')->where('idregimen', $id_regimen)->first();
        $nombre_ciudad = model('ciudadModel')->select('nombreciudad')->where('idciudad', $datos_empresa[0]['idciudad'])->first();
        $nombre_departamento = model('departamentoModel')->select('nombredepartamento')->where('iddepartamento', $datos_empresa[0]['iddepartamento'])->first();

        $array_iva = array();
        $data_iva['tarifa_iva'] =  0;
        $data_iva['base'] = 0;
        $data_iva['total_iva'] = 0;
        $data_iva['valor_venta'] = 0;
        array_push($array_iva, $data_iva);

        $array_ico = array();
        $data_ico['tarifa_ico'] = 8;
        $data_ico['base'] = $movimientos['base_ico'];
        $data_ico['total_ico'] = $movimientos['ico'];
        $data_ico['valor_venta'] = $movimientos['total'];
        array_push($array_ico, $data_ico);


        $array_devoluciones_iva = array();
        $data_devo_iva['base'] =  0;
        $data_devo_iva['tarifa'] = 0;
        $data_devo_iva['impuesto'] = 0;
        $data_devo_iva['total'] = 0;
        array_push($array_devoluciones_iva, $data_devo_iva);

        $array_devoluciones_ico = array();
        $data_devo_ico['base'] =  0;
        $data_devo_ico['tarifa'] = 0;
        $data_devo_ico['impuesto'] = 0;
        $data_devo_ico['total'] = 0;
        array_push($array_devoluciones_ico, $data_devo_ico);


        $dompdf->loadHtml(view('consultas_y_reportes/informe_fiscal_ventas_pdf', [
            "nombre_comercial" => $datos_empresa[0]['nombrecomercialempresa'],
            "nombre_juridico" => $datos_empresa[0]['nombrejuridicoempresa'],
            "nit" => $datos_empresa[0]['nitempresa'],
            "nombre_regimen" => $regimen['nombreregimen'],
            "direccion" => $datos_empresa[0]['direccionempresa'],
            "nombre_ciudad" => $nombre_ciudad['nombreciudad'],
            "nombre_departamento" => $nombre_departamento['nombredepartamento'],
            "registro_inicial" => $movimientos['registro_inicial'],
            "registro_final" => $movimientos['registro_final'],
            "total_registros" => $movimientos['total_registros'],
            "iva" => $array_iva,
            "ico" => $array_ico,
            "vantas_contado" =>  $movimientos['total'],
            "iva_devolucion" => $array_devoluciones_iva,
            "ico_devolucion" => $array_devoluciones_ico,
            "consecutivo" => $movimientos['consecutivo'],
            //"consecutivo" => $consecutivo_informe['numero'],
            "fecha" => $fecha,
            "titulo" => "Informe fiscal de ventas electrónicas "

        ]));

        $options = $dompdf->getOptions();
        $dompdf->setPaper('letter');
        $dompdf->render();
        $dompdf->stream("Informe fiscal 2023-03-25.pdf", array("Attachment" => true));
    }

    function reporte_de_ventas_excel()
    {
        $id_apertura = $this->request->getPost('id_apertura');

        $datos_empresa = model('empresaModel')->find();
        $id_regimen = $datos_empresa[0]['idregimen'];
        $regimen = model('regimenModel')->select('nombreregimen')->where('idregimen', $id_regimen)->first();
        $nombre_ciudad = model('ciudadModel')->select('nombreciudad')->where('idciudad', $datos_empresa[0]['idciudad'])->first();
        $nombre_departamento = model('departamentoModel')->select('nombredepartamento')->where('iddepartamento', $datos_empresa[0]['iddepartamento'])->first();

        $id = model('pagosModel')->select('id')->where('id_apertura', $id_apertura)->findAll();

        $total_ventas = model('pagosModel')->selectSum('total_documento')->where('id_apertura', $id_apertura)->findAll();

        return view('producto/ventas_excel', [
            'datos_empresa' => $datos_empresa,
            'regimen' => $regimen['nombreregimen'],
            'nombre_ciudad' => $nombre_ciudad['nombreciudad'],
            'nombre_departamento' => $nombre_departamento['nombredepartamento'],
            'id' => $id,
            'total' => $total_ventas[0]['total_documento']
        ]);
    }
}
