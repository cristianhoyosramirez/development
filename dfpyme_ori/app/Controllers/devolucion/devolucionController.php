<?php

namespace App\Controllers\devolucion;

require APPPATH . "Controllers/mike42/autoload.php";

use App\Controllers\BaseController;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Dompdf\Dompdf;

use \DateTime;
use \DateTimeZone;

class devolucionController extends BaseController
{
    public function index()
    {
    }

    public function guardar_devolucion()
    {
        $usuario = $_POST['usuario'];
        //$nit_cliente = $_POST['nit_cliente'];
        $nit_cliente = 222222222222;
        $codigo_producto_devolucion = $_POST['codigo_producto_devolucion'];
        $cantidad_devolucion = $_POST['cantidad_devolucion'];
        $precio_devo = $_POST['precio_devolucion'];
        $precio_devolucion =  str_replace('.', '', $precio_devo);


        $id_apertura = model('aperturaRegistroModel')->select('numero')->first();

        if (!empty($id_apertura)) {

            /*   $usuario = 6;
        $nit_cliente = 22222222;
        $codigo_producto_devolucion = '5660';
        $cantidad_devolucion = 1;
        $precio_devo = 4.100;
        $precio_devolucion =  str_replace('.', '', $precio_devo); */

            $valor_total_producto = $precio_devolucion * $cantidad_devolucion;

            if ($cantidad_devolucion > 0 && $cantidad_devolucion != 0) {


                $id_tipo_inventario = model('productoModel')->select('id_tipo_inventario')->where('codigointernoproducto', $codigo_producto_devolucion)->first();

                if ($id_tipo_inventario['id_tipo_inventario'] == 1) {

                    $cantidad_inventario = model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto', $codigo_producto_devolucion)->first();
                    $inventario_final = $cantidad_inventario['cantidad_inventario'] + $cantidad_devolucion;

                    $data = [
                        'cantidad_inventario' => $inventario_final,

                    ];
                    $model = model('inventarioModel');
                    $actualizar = $model->set($data);
                    $actualizar = $model->where('codigointernoproducto', $codigo_producto_devolucion);
                    $actualizar = $model->update();
                    
                } elseif ($id_tipo_inventario['id_tipo_inventario'] == 3) {

                    $producto_fabricado = model('productoFabricadoModel')->select('*')->where('prod_fabricado', $codigo_producto_devolucion)->find();


                    foreach ($producto_fabricado as $detalle) {
                        $sumar_inventario = $cantidad_devolucion * $detalle['cantidad'];

                        //echo $descontar_de_inventario."</br>"; ok

                        $cantidad_inventario = model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto', $detalle['prod_proceso'])->first();

                        $data = [
                            'cantidad_inventario' => $cantidad_inventario['cantidad_inventario'] + $sumar_inventario,

                        ];

                        $model = model('inventarioModel');
                        $actualizar = $model->set($data);
                        $actualizar = $model->where('codigointernoproducto', $detalle['prod_proceso']);
                        $actualizar = $model->update();
                    }
                }


                $numero_consecutivo = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 12)->first();


                $fecha = DateTime::createFromFormat('U.u', microtime(TRUE));
                $fecha->setTimeZone(new DateTimeZone('America/Bogota'));
                $fecha_y_hora = $fecha->format('Y-m-d H:i:s.u');

                $devolucion_venta = [
                    'numero' => $numero_consecutivo['numeroconsecutivo'],
                    'numerofactura' => "",
                    'nitcliente' => $nit_cliente,
                    'fecha' => date('Y-m-d'),
                    'idusuario' => $usuario,
                    'idcaja' => 1,
                    'idturno' => 1,
                    'hora' =>  date("H:i:s"),
                    'id_apertura' => $id_apertura['numero'],
                    'fecha_y_hora_devolucion' => $fecha_y_hora
                ];
                $insert = model('devolucionModel')->insert($devolucion_venta);

                $actualizar_consecutivo = [
                    'numeroconsecutivo' => $numero_consecutivo['numeroconsecutivo'] + 1
                ];

                $actualizar = model('consecutivosModel')->set($actualizar_consecutivo);
                $actualizar = model('consecutivosModel')->where('idconsecutivos', 12);
                $actualizar = model('consecutivosModel')->update();
                //var_dump($codigo_producto_devolucion);
                $aplica_ico = model('productoModel')->select('aplica_ico')->where('codigointernoproducto', $codigo_producto_devolucion)->first();

                if ($aplica_ico['aplica_ico'] == 't') {
                    //echo "APLICA IMPUESTO AL CONSUMO";
                    $id_ico = model('productoModel')->select('id_ico_producto')->where('codigointernoproducto', $codigo_producto_devolucion)->first();
                    $ico = model('icoConsumoModel')->select('valor_ico')->where('id_ico', $id_ico['id_ico_producto'])->first();


                    $temp = $ico['valor_ico'] / 100;
                    $imp_cons = $temp + 1;
                    $ipo_consumo = $precio_devolucion / $imp_cons;

                    $id_devolucion = model('devolucionModel')->where('idusuario', $usuario)->insertID;
                    $detalle_devolucion_venta = [
                        'id_devolucion_venta' => $id_devolucion,
                        'codigo' => $codigo_producto_devolucion,
                        'idmedida' => 3,
                        'idcolor' => 0,
                        'valor' => $ipo_consumo,
                        'descuento' => 0,
                        'iva' => 0,
                        'cantidad' => $cantidad_devolucion,
                        'impoconsumo' => $precio_devolucion - $ipo_consumo,
                        'ico' => $ico,
                        'valor_total_producto' => $valor_total_producto,
                        'fecha_y_hora_venta' => $fecha_y_hora,
                        'fecha_venta' => date('Y-m-d'),
                        'id_apertura' => $id_apertura['numero']
                    ];

                    $insert = model('detalleDevolucionVentaModel')->insert($detalle_devolucion_venta);

                    $devolucion_venta_efectivo = [
                        'iddevolucion' => $id_devolucion,
                        'valor' => $precio_devolucion * $cantidad_devolucion
                    ];

                    $devolucion_venta = model('devolucionVentaEfectivoModel')->insert($devolucion_venta_efectivo);

                    if ($insert) {
                        $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_producto_devolucion)->first();
                        $returnData = array(
                            "resultado" => 1,
                            "nombre_producto" => $nombre_producto['nombreproducto']
                        );
                        echo  json_encode($returnData);
                    }
                } else if ($aplica_ico['aplica_ico'] == 'f') {

                    $id_iva = model('productoModel')->select('idiva')->where('codigointernoproducto', $codigo_producto_devolucion)->first();

                    $iva = model('ivaModel')->select('valoriva')->where('idiva', $id_iva['idiva'])->first();
                    $temp_iva = $iva['valoriva'] / 100;
                    $imp_iva = $temp_iva + 1;
                    $base_iva = $precio_devolucion / $imp_iva;
                    $id_devolucion = model('devolucionModel')->where('idusuario', $usuario)->insertID;

                    $detalle_devolucion_venta = [
                        'id_devolucion_venta' => $id_devolucion,
                        'codigo' => $codigo_producto_devolucion,
                        'idmedida' => 3,
                        'idcolor' => 0,
                        'valor' => $base_iva,
                        'descuento' => 0,
                        'iva' => $iva['valoriva'],
                        'cantidad' => $cantidad_devolucion,
                        'impoconsumo' => 0,
                        'ico' => 0,
                        'valor_total_producto' => $valor_total_producto,
                        'fecha_y_hora_venta' => $fecha_y_hora,
                        'fecha_venta' => date('Y-m-d'),
                    ];


                    $insert = model('detalleDevolucionVentaModel')->insert($detalle_devolucion_venta);

                    $devolucion_venta_efectivo = [
                        'iddevolucion' => $id_devolucion,
                        'valor' => $precio_devolucion * $cantidad_devolucion
                    ];

                    $devolucion_venta = model('devolucionVentaEfectivoModel')->insert($devolucion_venta_efectivo);

                    if ($insert) {
                        $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_producto_devolucion)->first();
                        $returnData = array(
                            "resultado" => 1,
                            "nombre_producto" => $nombre_producto['nombreproducto']
                        );
                        echo  json_encode($returnData);
                    }
                }
            } else {
                $returnData = array(
                    "resultado" => 0,
                );
                echo  json_encode($returnData);
            }
        }
        if (empty($id_apertura)) {
            $returnData = array(
                "resultado" => 2,
            );
            echo  json_encode($returnData);
        }
    }

    public function retiro()
    {
        $usuario = $_POST['usuario'];
        $valor_retiro = $_POST['valor_retiro'];
        $concepto_retiro = $_POST['concepto_retiro'];
        $retiro_de_caja =  str_replace('.', '', $valor_retiro);
        $id_apertura = model('aperturaRegistroModel')->select('numero')->first();



        /* $usuario = 6;
        $valor_retiro = 50.000;
        $concepto_retiro = 'CXC';
        $retiro_de_caja =  str_replace('.', '', $valor_retiro); */

        $id_apertura = model('aperturaRegistroModel')->select('numero')->first();


        if (!empty($id_apertura)) {


            //$rubro = 1;
            $rubro = $_POST['id_cuenta_rubro'];

            $retiro = [
                'fecha' => date('Y-m-d'),
                'hora' => date("H:i:s"),
                'idcaja' => 1,
                'idturno' => 1,
                'idusuario' => $usuario,
                'id_apertura' => $id_apertura['numero'],
                'id_rubro_cuenta_retiro' => $rubro
            ];


            $insert = model('retiroModel')->insert($retiro);

            if ($insert) {
                $id_retiro = model('retiroModel')->where('idusuario', $usuario)->insertID;

                $cuenta_retiro = model('rubrosModel')->select('id_cuenta_retiro')->where('id', $_POST['id_cuenta_rubro'])->first();

                $fecha = DateTime::createFromFormat('U.u', microtime(TRUE));
                $fecha->setTimeZone(new DateTimeZone('America/Bogota'));
                $fecha_y_hora = $fecha->format('Y-m-d H:i:s.u');

                $retiro_forma_pago = [
                    'idretiro' => $id_retiro,
                    'idpago' => 1,
                    'valor' => $retiro_de_caja,
                    'concepto' => $concepto_retiro,
                    'id_cuenta_retiro' => $cuenta_retiro['id_cuenta_retiro'],
                    'fecha' => date('Y-m-d'),
                    'fecha_y_hora_retiro_forma_pago' => $fecha_y_hora,
                    'id_apertura' => $id_apertura['numero']
                ];

                $insertar = model('retiroFormaPagoModel')->insert($retiro_forma_pago);
                if ($insertar) {
                    $returnData = array(
                        "resultado" => 1,
                        "retiro" => $valor_retiro,
                        "id_retiro" => $id_retiro,
                        'id_usuario' => $usuario

                    );
                    echo  json_encode($returnData);
                }
            }
        }

        if (empty($id_apertura)) {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        }
    }

    public function imprimir_retiro()
    {
        $id_retiro = $_REQUEST['id_retiro'];
        $id_usuario = $_REQUEST['id_usuario'];
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
        $datos_retiro = model('retiroModel')->where('id', $id_retiro)->find();

        $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario)->first();

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setTextSize(1, 1);
        $printer->text("FECHA  :" . $datos_retiro[0]['fecha'] . "\n");
        $printer->text("CAJA   :" . " 1 \n");

        $detalle_forma_pago = model('retiroFormaPagoModel')->select('valor')->where('idretiro', $datos_retiro[0]['id'])->first();
        $detalle_concepto = model('retiroFormaPagoModel')->select('concepto')->where('idretiro', $datos_retiro[0]['id'])->first();

        $printer->text("USUARIO:" . $nombre_usuario['nombresusuario_sistema'] . "\n\n");
        $printer->text("VALOR :" .  "$" . number_format($detalle_forma_pago['valor'], 0, ",", ".") . "\n");
        $printer->text("CONCEPTO:" . $detalle_concepto['concepto'] . "\n\n\n");
        $printer->text("___________________________\n");
        $printer->text("FIRMA QUIEN ENTREGA\n\n\n");
        $printer->text("___________________________\n");
        $printer->text("FIRMA QUIEN RECIBE\n\n\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setTextSize(1, 1);
        $printer->text("________________________________________________\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->cut();
        $printer->pulse();
        $printer->close();
        $returnData = array(
            "resultado" => 1

        );
        echo  json_encode($returnData);
    }
    public function re_imprimir_retiro()
    {
        $id_retiro = $_REQUEST['id_retiro'];

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
        $datos_retiro = model('retiroFormaPagoModel')->where('id', $id_retiro)->find();

        $datos_retiro[0]['idretiro'];

        $id_retiro = $datos_retiro[0]['idretiro'];
        $usuario = model('retiroModel')->select('idusuario')->where('id', $id_retiro)->first();
        $id_usuario = $usuario['idusuario'];
        $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario)->first();

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setTextSize(1, 1);
        $printer->text("FECHA  :" . $datos_retiro[0]['fecha'] . "\n");
        $printer->text("CAJA   :" . " 1 \n");

        $detalle_forma_pago = model('retiroFormaPagoModel')->select('valor')->where('id', $datos_retiro[0]['id'])->first();

        $detalle_concepto = model('retiroFormaPagoModel')->select('concepto')->where('id', $datos_retiro[0]['id'])->first();

        $printer->text("USUARIO:" . $nombre_usuario['nombresusuario_sistema'] . "\n\n");

        $printer->text("CONCEPTO:" . $detalle_concepto['concepto'] . "\n\n\n");


        $printer->text("___________________________\n");
        $printer->text("FIRMA QUIEN ENTREGA\n\n\n");
        $printer->text("___________________________\n");
        $printer->text("FIRMA QUIEN RECIBE\n\n\n");
        $printer->setTextSize(2, 2);
        $printer->text("VALOR :" .  "$" . number_format($detalle_forma_pago['valor'], 0, ",", ".") . "\n\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);

        $printer->cut();
        $printer->pulse();
        $printer->close();
        $returnData = array(
            "resultado" => 1

        );
        echo  json_encode($returnData);
    }

    public function no_imprimir_retiro()
    {

        $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();

        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);

        $printer->pulse();
        $printer->close();
        $returnData = array(
            "resultado" => 1

        );
        echo  json_encode($returnData);
    }

    function edicion_retiro_de_dinero()
    {
        $id_retiro = $_REQUEST['id_retiro'];

        $valor_retiro = model('retiroFormaPagoModel')->select('valor')->where('id', $id_retiro)->first();
        $concepto_retiro = model('retiroFormaPagoModel')->select('concepto')->where('id', $id_retiro)->first();
        $returnData = array(
            "resultado" => 1,
            "valor_retiro" => number_format($valor_retiro['valor'], 0, ",", "."),
            "id_retiro" => $id_retiro,
            "concepto" => $concepto_retiro['concepto']
        );
        echo  json_encode($returnData);
    }

    function actualizar_retiro_de_dinero()
    {
        //echo "el id del retiro es " .  $_REQUEST['concepto_retiro']; exit();
        $actualizar_retiro = [
            'valor' => str_replace(".", "", $_REQUEST['valor_retiro']),
            'concepto' => $_REQUEST['concepto_retiro']
        ];

        $model = model('retiroFormaPagoModel');
        $actualizar = $model->set($actualizar_retiro);
        $actualizar = $model->where('id', $_REQUEST['id_retiro']);
        $actualizar = $model->update();
        if ($actualizar) {

            $id_retiro = model('retiroFormaPagoModel')->select('idretiro')->where('id', $_REQUEST['id_retiro'])->first();
            $apertura = model('retiroModel')->select('id_apertura')->where('id', $id_retiro['idretiro'])->first();
            $id_apertura = $apertura['id_apertura'];

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
                $efectivo = model('facturaFormaPagoModel')->ingresos_efectivo($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d') . " " . date('H:m:s'));
                if (empty($efectivo)) {
                    $ingresos_efectivo = 0;
                } else if (!empty($efectivo)) {
                    $ingresos_efectivo = $efectivo[0]['ingresos_efectivo'];
                }

                $transaccion = model('facturaFormaPagoModel')->ingresos_transaccion($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d') . " " . date('H:m:s'));
                if (empty($transaccion)) {
                    $ingresos_transaccion = 0;
                } else if (!empty($transaccion)) {
                    $ingresos_transaccion = $transaccion[0]['ingresos_transaccion'];
                }
                $valor_cierre = 0;
                $devolucion_venta = model('devolucionModel')->sumar_devoluciones($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d') . " " . date('H:m:s'));

                if (empty($devolucion_venta)) {
                    $devoluciones = 0;
                } else if (!empty($devolucion_venta)) {
                    $devoluciones = $devolucion_venta[0]['total_devoluciones'];
                }


                $total_retiros = model('retiroFormaPagoModel')->total_retiros($fecha_y_hora_apertura['fecha_y_hora_apertura'], date('Y-m-d') . " " . date('H:m:s'));

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

    function editar_retiro()
    {

        $id = $this->request->getPost('id_retiro');
        //$id= 32;

        $datos_retiro = model('retiroFormaPagoModel')->retiros_forma_pago($id);


        $returnData = array(
            "resultado" => 1,
            'valor' => number_format($datos_retiro[0]['valor'], 0, ",", "."),
            'concepto' => $datos_retiro[0]['concepto'],
            'id' => $id
        );
        echo  json_encode($returnData);
    }

    function actualizar_retiro()
    {

        $id = $this->request->getPost('id');
        $concepto = $this->request->getPost('concepto');
        $valor = $this->request->getPost('valor');
        $valor = str_replace('.', '', $valor);

        $data = [
            'concepto' => $concepto,
            'valor' => $valor
        ];

        $update = model('retiroFormaPagoModel')->set($data)->where('idretiro', $id)->update();

        if ($update) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('consultas_y_reportes/consultas_caja'))->with('mensaje', 'Actualizacion exitosa  ');
        }
    }
}
