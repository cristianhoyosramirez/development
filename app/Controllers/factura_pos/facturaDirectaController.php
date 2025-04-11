<?php

namespace App\Controllers\factura_pos;

use App\Controllers\BaseController;
use \DateTime;
use \DateTimeZone;


require APPPATH . "Controllers/mike42/autoload.php";

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Cell\DataType;


class facturaDirectaController extends BaseController
{
    public function index()
    {
        return view('home/home');
    }

    public function facturacion()
    {

        $efectivo = $_POST['efectivo'];
        $transaccion = $_POST['transaccion'];
        $valor_venta = $_POST['valor_venta'];
        $nit_cliente = $_POST['nit_cliente'];
        $id_usuario = $_POST['id_usuario'];
        $estado = $_POST['estado'];
        $total_pagado = $_POST['total_pagado'];
        $descuento = $_POST['descuento'];
        $propina = $_POST['propina'];



        /*       $efectivo = 20.000;
        $transaccion = '';
        $valor_venta = 60.000;
        $nit_cliente = 22222222;
        $id_usuario = 6;
        $estado = 1;
        $total_pagado = 70.000;
        $descuento = 10000;
        $propina = 10000;   */


        if (empty($transaccion)) {
            $transaccion = 0;
        }

        $valor_factura = model('pedidoPosModel')->select('valor_total')->where('fk_usuario', $id_usuario)->first();


        if (empty($efectivo)) {
            $efectivo = 0;
        }

        if ($estado == 4) {
            $saldo = $valor_factura['valor_total'];
        } else if ($estado != 4) {
            $saldo = 0;
        }




        $efectivo_sin_punto = str_replace(".", "", $efectivo);
        $transaccion_sin_punto = str_replace(".", "", $transaccion);
        $valor_venta_sin_punto = str_replace(".", "", $valor_venta);
        $total_pagado_sin_punto = str_replace(".", "", $total_pagado);


        $fecha = DateTime::createFromFormat('U.u', microtime(TRUE));
        $fecha->setTimeZone(new DateTimeZone('America/Bogota'));
        $fecha_y_hora = $fecha->format('Y-m-d H:i:s.u');
        if ($valor_venta_sin_punto <= $total_pagado_sin_punto) {
            $id_regimen = model('empresaModel')->select('idregimen')->first();

            $fecha = date("Y-m-d ");
            $hora = date("H:i:s");

            $numero_factura = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', '8')->first();
            $id_resolucion_dian = model('consecutivosModel')->select('numeroconsecutivo')->Where('idconsecutivos', 6)->first();
            $prefijo_factura = model('dianModel')->select('inicialestatica')->where('iddian', $id_resolucion_dian['numeroconsecutivo'])->first();

            $serie = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', '14')->first();

            $observaciones_genereles = model('pedidoPosModel')->select('nota_general')->where('fk_usuario', $id_usuario)->first();
            $valor_total = model('pedidoPosModel')->select('valor_total')->where('fk_usuario', $id_usuario)->first();


            $numero_de_factura = '';

            if ($estado == 6 or $estado == 7) {  //Esto es es cuando es remision bien sea de contado o crédito 
                $consectivo_remision = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', '11')->first();
                $numero_de_factura = $consectivo_remision['numeroconsecutivo'];

                $incremento = [
                    'numeroconsecutivo' => $consectivo_remision['numeroconsecutivo'] + 1
                ];

                $num_consecutivo = model('consecutivosModel');
                $num_consecutivo = $num_consecutivo->set($incremento);
                $num_consecutivo = $num_consecutivo->where('idconsecutivos', 11);
                $num_consecutivo = $num_consecutivo->update();
            }

            if ($estado == 1 or $estado == 2 or $estado == 3) { // Esto es cuando la factura es credito contado o pendiente 

                $numero_de_factura = $prefijo_factura['inicialestatica'] . "-" . $numero_factura['numeroconsecutivo'];
            }

            $data = [
                'numerofactura_venta' => $numero_de_factura,
                'nitcliente' => $nit_cliente,
                'idusuario_sistema' => $id_usuario,
                'idcaja' => 1,
                'idestado' => $estado,
                'fecha_factura_venta' => $fecha,
                'horafactura_venta' => $hora,
                'descuentofactura_venta' => 0,
                'fechalimitefactura_venta' => $fecha,
                'aplica_descuento' => false,
                'estado' => true,
                'serie' => $serie['numeroconsecutivo'],
                'id_resolucion_dian' => $id_resolucion_dian['numeroconsecutivo'],
                'observaciones_generales' => $observaciones_genereles['nota_general'],
                'fk_usuario_mesero' => $id_usuario,
                'valor_factura' => $valor_total['valor_total'],
                'saldo' => 0,
                'fecha_y_hora_factura_venta' => $fecha_y_hora,
                'descuento' => $descuento,
                'propina' => $propina
            ];

            $serie_update  = $serie['numeroconsecutivo'] + 1;

            $incremento = model('consecutivosModel')->update_serie($serie_update);
            //Guardado en la tabla factura Venta 

            $factuta_venta = model('facturaVentaModel')->insert($data);

            $numero_facturaUpdate = [
                'numeroconsecutivo' => $numero_factura['numeroconsecutivo'] + 1
            ];

            if ($prefijo_factura == 0) {
                $update_numero_factura = [
                    'numero_factura' => $prefijo_factura['inicialestatica'] . $numero_factura['numeroconsecutivo']
                ];
            }
            if ($prefijo_factura != 0) {
                $update_numero_factura = [
                    'numero_factura' => $numero_factura['numeroconsecutivo']
                ];
            }

            //Actualizacion en la tabla pedido columna numero_factura
            //$num_fact = model('pedidosPosModel');

            $num_fact = model('pedidoPosModel');
            $numero_factura = $num_fact->set($update_numero_factura);
            $numero_factura = $num_fact->where('fk_usuario', $id_usuario);
            $numero_factura = $num_fact->update();


            //Actualizacion de la tabla consecutivos 
            $model = model('consecutivosModel');
            $numero_factura = $model->set($numero_facturaUpdate);
            $numero_factura = $model->where('idconsecutivos', 8);
            $numero_factura = $model->update();
            //Registros de los productos que estan la tabla producto_pedido y que vamos a facturar 
            $pk_pedido_pos = model('pedidoPosModel')->select('id')->where('fk_usuario', $id_usuario)->first();

            $productos = model('productoPedidoPosModel')->where('pk_pedido_pos', $pk_pedido_pos['id'])->find();

            foreach ($productos as $detalle) {
                $valor_venta_producto = model('productoModel')->select('valorventaproducto')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                $aplica_ico = model('productoModel')->select('aplica_ico')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                $codigo_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();

                if ($aplica_ico['aplica_ico'] == 't') {

                    $id_ico_producto = model('productoModel')->select('id_ico_producto')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();

                    $valor_imco = model('icoConsumoModel')->select('valor_ico')->where('id_ico', $id_ico_producto)->first();
                    $valor_ico = ($valor_imco['valor_ico'] / 100) + 1;
                    $valor_antes_de_ico = $detalle['valor_unitario'] / $valor_ico;
                    $valor_venta_real = $valor_antes_de_ico;

                    $impuesto_al_consumo = $detalle['valor_unitario'] - $valor_venta_real;


                    if ($valor_imco['valor_ico'] == 0) {
                        $valor_unitario = $valor_venta_producto['valorventaproducto'];
                    }

                    if ($valor_imco['valor_ico'] != 0) {
                        $val_uni = $valor_venta_producto['valorventaproducto'] / $valor_ico;
                        $valor_unitario = $valor_venta_producto['valorventaproducto'] - $val_uni;
                    }

                    $id_medida = model('productoMedidaModel')->select('idvalor_unidad_medida')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                    $total = model('pedidoPosModel')->select('valor_total')->where('fk_usuario', $id_usuario)->first();

                    $precio_costo = model('productoModel')->select('precio_costo')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                    $id_factura = model('facturaVentaModel')->where('idusuario_sistema', $id_usuario)->insertID;

                    $numero_factura = model('pedidoPosModel')->select('numero_factura')->where('fk_usuario', $id_usuario)->first();



                    $producto_factura_venta = [
                        'numerofactura_venta' =>  $prefijo_factura['inicialestatica'] . "-" . $numero_factura['numero_factura'],
                        'codigointernoproducto' => $detalle['codigointernoproducto'],
                        'cantidadproducto_factura_venta' => $detalle['cantidad_producto'],
                        'valorunitarioproducto_factura_venta' => $valor_unitario,
                        'idmedida' => $id_medida['idvalor_unidad_medida'],
                        'idcolor' => 0,
                        'valor_descuento' => 0, //pendiente de ajuste
                        'valor_recargo' => 0,
                        'valor_iva' => 0,
                        'retorno' => false,
                        'valor' => 0,
                        'costo' => $precio_costo['precio_costo'],
                        'id_factura' => $id_factura,
                        'valor_venta_real' =>  $valor_venta_real,
                        'impoconsumo' => 0,
                        'total' => $detalle['valor_total'],
                        'valor_ico' => $valor_imco,  //Deberia de ser el 8 
                        'impuesto_al_consumo' => $impuesto_al_consumo,
                        'iva' => 0,
                        'id_iva' => 1,
                        'aplica_ico' => true,
                        'valor_total_producto' => $detalle['valor_unitario'],
                        'fecha_y_hora_venta' => $fecha_y_hora,
                        'fecha_venta' => date('Y-m-d'),
                        'id_categoria' => $codigo_categoria['codigocategoria']
                    ];

                    $insertar = model('productoFacturaVentaModel')->insert($producto_factura_venta);


                    /**
                     * Consultar el tipo de inventario y descontarlo
                     */
                    $id_tipo_inventario = model('productoModel')->select('id_tipo_inventario')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                    if ($id_tipo_inventario['id_tipo_inventario'] == 1) {
                        $cantidad_inventario = model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                        $inventario_final = $cantidad_inventario['cantidad_inventario'] - $detalle['cantidad_producto'];

                        $data = [
                            'cantidad_inventario' => $inventario_final,

                        ];
                        $model = model('inventarioModel');
                        $actualizar = $model->set($data);
                        $actualizar = $model->where('codigointernoproducto', $detalle['codigointernoproducto']);
                        $actualizar = $model->update();
                    } elseif ($id_tipo_inventario['id_tipo_inventario'] == 3) {

                        $producto_fabricado = model('productoFabricadoModel')->select('*')->where('prod_fabricado', $detalle['codigointernoproducto'])->find();


                        foreach ($producto_fabricado as $detall) {
                            $descontar_de_inventario = $detalle['cantidad_producto'] * $detall['cantidad'];

                            //echo $descontar_de_inventario."</br>"; ok

                            $cantidad_inventario = model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto', $detall['prod_proceso'])->first();

                            $data = [
                                'cantidad_inventario' => $cantidad_inventario['cantidad_inventario'] - $descontar_de_inventario,

                            ];

                            $model = model('inventarioModel');
                            $actualizar = $model->set($data);
                            $actualizar = $model->where('codigointernoproducto', $detall['prod_proceso']);
                            $actualizar = $model->update();
                        }
                    }
                } else if ($aplica_ico['aplica_ico'] == 'f') {

                    //Valor del producto de la tabla producto 
                    $valor_venta_producto = model('productoModel')->select('valorventaproducto')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                    $id_iva = model('productoModel')->select('idiva')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                    $porcentaje_iva = model('ivaModel')->select('valoriva')->where('idiva ', $id_iva['idiva'])->first();

                    if ($porcentaje_iva['valoriva'] == 0) {
                        $valor_unitario = $valor_venta_producto['valorventaproducto'];
                        $valor_venta_real = $detalle['valor_unitario'];
                        $iva = 0;
                    }
                    if ($porcentaje_iva['valoriva'] != 0) {
                        $valor_porcentaje_iva = ($porcentaje_iva['valoriva'] / 100) + 1;
                        $valor_unitario = $valor_venta_producto['valorventaproducto'] / $valor_porcentaje_iva;
                        $valor_venta_real = $detalle['valor_unitario'] / $valor_porcentaje_iva;
                        $iva = $detalle['valor_unitario'] - $valor_venta_real;
                    }
                    $valor_imco = 0;

                    $id_medida = model('productoMedidaModel')->select('idvalor_unidad_medida')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();

                    $total = model('PedidoPosModel')->select('valor_total')->where('fk_usuario', $id_usuario)->first();

                    $precio_costo = model('productoModel')->select('precio_costo')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                    $id_factura = model('facturaVentaModel')->where('idusuario_sistema', $id_usuario)->insertID;
                    $numero_factura = model('pedidoPosModel')->select('numero_factura')->where('fk_usuario', $id_usuario)->first();

                    $id_iva = model('productoModel')->select('idiva')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                    $producto_factura_venta = [
                        'numerofactura_venta' => $prefijo_factura['inicialestatica'] . "-" . $numero_factura['numero_factura'],
                        'codigointernoproducto' => $detalle['codigointernoproducto'],
                        'cantidadproducto_factura_venta' => $detalle['cantidad_producto'],
                        'valorunitarioproducto_factura_venta' => $valor_unitario,
                        'idmedida' => $id_medida['idvalor_unidad_medida'],
                        'idcolor' => 0,
                        'valor_descuento' => 0, //pendiente de ajuste
                        'valor_recargo' => 0,
                        'valor_iva' => $porcentaje_iva['valoriva'],
                        'retorno' => false,
                        'valor' => 0,
                        'costo' => $precio_costo['precio_costo'],
                        'id_factura' => $id_factura,
                        'valor_venta_real' =>  $valor_venta_real,
                        'impoconsumo' => 0,
                        'total' => $detalle['valor_total'],
                        'valor_ico' => $valor_imco, //
                        'impuesto_al_consumo' => 0,
                        'iva' => $iva,
                        'id_iva' => $id_iva['idiva'],
                        'aplica_ico' => false,
                        'valor_total_producto' => $detalle['valor_unitario'],
                        'fecha_y_hora_venta' => $fecha_y_hora,
                        'fecha_venta' => date('Y-m-d'),
                        'id_categoria' => $codigo_categoria['codigocategoria']

                    ];

                    $insertar = model('productoFacturaVentaModel')->insert($producto_factura_venta);
                    /**
                     * Consultar el tipo de inventario y descontarlo
                     */
                    $id_tipo_inventario = model('productoModel')->select('id_tipo_inventario')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();

                    if ($id_tipo_inventario['id_tipo_inventario'] == 1) {
                        $cantidad_inventario = model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                        $inventario_final = $cantidad_inventario['cantidad_inventario'] - $detalle['cantidad_producto'];

                        $data = [
                            'cantidad_inventario' => $inventario_final,

                        ];
                        $model = model('inventarioModel');
                        $actualizar = $model->set($data);
                        $actualizar = $model->where('codigointernoproducto', $detalle['codigointernoproducto']);
                        $actualizar = $model->update();
                    } elseif ($id_tipo_inventario['id_tipo_inventario'] == 3) {

                        $producto_fabricado = model('productoFabricadoModel')->select('*')->where('prod_fabricado', $detalle['codigointernoproducto'])->find();


                        foreach ($producto_fabricado as $detall) {
                            $descontar_de_inventario = $detalle['cantidad_producto'] * $detall['cantidad'];

                            $cantidad_inventario = model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto', $detall['prod_proceso'])->first();



                            $resultado = $cantidad_inventario['cantidad_inventario'] - $descontar_de_inventario;

                            $data = [
                                'cantidad_inventario' => $resultado,

                            ];


                            $model = model('inventarioModel');
                            $actualizar = $model->set($data);
                            $actualizar = $model->where('codigointernoproducto', $detall['prod_proceso']);
                            $actualizar = $model->update();
                        }
                    }
                }
            }

            $numero_factura = model('pedidoPosModel')->select('numero_factura')->where('fk_usuario', $id_usuario)->first();

            if ($efectivo_sin_punto == 0 and $transaccion_sin_punto > 0) {
                $pago_transaccion = $valor_venta_sin_punto;
                $pago_efectivo = 0;
            }
            if ($efectivo_sin_punto > 0 and $transaccion_sin_punto == 0) {
                $pago_transaccion = 0;
                $pago_efectivo = $valor_venta_sin_punto;
            }

            if ($efectivo_sin_punto > 0 and $transaccion_sin_punto > 0) {
                $temp = $valor_venta_sin_punto - $efectivo_sin_punto;
                $pago_transaccion = $temp;
                $pago_efectivo = $efectivo_sin_punto;
            }

            $factura_forma_pago_efectivo = [

                'numerofactura_venta' =>  $prefijo_factura['inicialestatica'] . "-" . $numero_factura['numero_factura'],
                'idusuario' => $id_usuario,
                'idcaja' => 1,
                'idforma_pago' => 1,
                'fechafactura_forma_pago' => $fecha,
                'hora' => $hora,
                //'valorfactura_forma_pago' => $efectivo_sin_punto,
                'valorfactura_forma_pago' =>  $pago_efectivo,
                'idturno' => 1,
                'valor_pago' => $efectivo_sin_punto,
                'id_factura' => $id_factura,
                'fecha_y_hora_forma_pago' => $fecha_y_hora
            ];


            $factura_forma_pago_transaccion = [

                'numerofactura_venta' =>  $prefijo_factura['inicialestatica'] . "-" . $numero_factura['numero_factura'],
                'idusuario' => $id_usuario,
                'idcaja' => 1,
                'idforma_pago' => 4,
                'fechafactura_forma_pago' => $fecha,
                'hora' => $hora,
                //'valorfactura_forma_pago' => $transaccion_sin_punto,
                'valorfactura_forma_pago' => $pago_transaccion,
                'idturno' => 1,
                'valor_pago' => $transaccion_sin_punto,
                'id_factura' => $id_factura,
                'fecha_y_hora_forma_pago' => $fecha_y_hora
            ];


            $valorfactura_forma_pago = model('pedidoPosModel')->select('valor_total')->where('fk_usuario', $id_usuario)->first();

            $id_facturacion = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', '6')->first();
            $factura = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', '8')->first();
            $alerta = model('dianModel')->select('alerta_facturacion')->where('iddian', $id_facturacion['numeroconsecutivo'])->first();
            $rango_final = model('dianModel')->select('rangofinaldian')->where('iddian', $id_facturacion['numeroconsecutivo'])->first();

            $resultado = "";
            $num_facturas = "";
            $result = $rango_final['rangofinaldian'] - $factura['numeroconsecutivo'];

            if ($result <= $alerta['alerta_facturacion']) {
                $resultado = 0;
            }
            if ($result > $alerta['alerta_facturacion']) {
                $resultado = 1;
            }

            if ($efectivo_sin_punto > 0 and $transaccion_sin_punto == 0) {
                $insert_efectivo = model('facturaFormaPagoModel')->insert($factura_forma_pago_efectivo);

                $pk_pedido_pos = model('pedidoPosModel')->select('id')->where('fk_usuario', $id_usuario)->first();
                $borrar_producto_pedido = model('productoPedidoPosModel')->where('pk_pedido_pos', $pk_pedido_pos['id']);
                $borrar_producto_pedido->delete();


                //$iva = model('productoFacturaVentaModel')->selectSum('iva')->where('id_factura', $id_factura)->find();
                //$ico = model('productoFacturaVentaModel')->selectSum('i')->where('id_factura', $id_factura)->find();
                $cantidad_iva = model('productoFacturaVentaModel')->impuestos($id_factura);
                $iva_temp = 0;
                $ico_temp = 0;
                $venta_real_temp = 0;
                foreach ($cantidad_iva  as $detalle) {
                    $iva = $detalle['cantidadproducto_factura_venta'] * $detalle['iva'];
                    $impuesto_al_consumo = $detalle['cantidadproducto_factura_venta'] * $detalle['impuesto_al_consumo'];
                    $total_iva = $iva + $iva_temp;
                    $iva_temp = $total_iva;

                    $total_ico = $impuesto_al_consumo + $ico_temp;
                    $ico_temp = $total_ico;

                    $sub_total = $detalle['valor_venta_real'] * $detalle['cantidadproducto_factura_venta'];
                    $sub_totales = $sub_total + $venta_real_temp;
                    $venta_real_temp = $sub_totales;
                }


                $total = model('productoFacturaVentaModel')->selectSum('total')->where('id_factura', $id_factura)->find();

                $returnData = array(
                    "id_factura" => $id_factura,
                    "resultado" => 1,
                    "efectivo" => number_format($efectivo_sin_punto, 0, ",", "."),
                    "cambio" => number_format($efectivo_sin_punto - (($valorfactura_forma_pago['valor_total'] - $descuento) + $propina), 0, ",", "."),
                    "total" =>  number_format(($valorfactura_forma_pago['valor_total'] - $descuento) + $propina, 0, ",", "."),
                    "id_regimen" => 1,
                    "Sub_total" => number_format($sub_totales, 0, ",", "."),
                    "iva" => number_format($total_iva, 0, ",", "."),
                    "impuesto_al_consumo" => number_format($total_ico, 0, ",", "."),
                    //"total" => $total[0]['total'],
                    "result" => $resultado,
                    "facturas_restantes" => number_format($result, 0, ",", "."),
                    "pr" => 1
                );
                $borrar_pedido = model('pedidoPosModel')->where('fk_usuario', $id_usuario);
                $borrar_pedido->delete();
                echo  json_encode($returnData);

                $id_impresora = model('aperturaCajonMonederoModel')->select('fk_impresora')->first();
                $nombre = model('impresorasModel')->select('nombre')->where('id', $id_impresora['fk_impresora'])->first();
                $nombre_impresora = $nombre['nombre'];
                $connector = new WindowsPrintConnector($nombre_impresora);
                $printer = new Printer($connector);
                $printer->pulse();
                $printer->close();
            }
            if ($efectivo_sin_punto > 0 and $transaccion_sin_punto > 0) {
                $insert_efectivo = model('facturaFormaPagoModel')->insert($factura_forma_pago_efectivo);
                $insert_transaccion = model('facturaFormaPagoModel')->insert($factura_forma_pago_transaccion);

                $pk_pedido_pos = model('pedidoPosModel')->select('id')->where('fk_usuario', $id_usuario)->first();
                $borrar_producto_pedido = model('productoPedidoPosModel')->where('pk_pedido_pos', $pk_pedido_pos['id']);
                $borrar_producto_pedido->delete();

                $cantidad_iva = model('productoFacturaVentaModel')->impuestos($id_factura);
                $iva_temp = 0;
                $ico_temp = 0;
                $venta_real_temp = 0;
                foreach ($cantidad_iva  as $detalle) {
                    $iva = $detalle['cantidadproducto_factura_venta'] * $detalle['iva'];
                    $impuesto_al_consumo = $detalle['cantidadproducto_factura_venta'] * $detalle['impuesto_al_consumo'];
                    $total_iva = $iva + $iva_temp;
                    $iva_temp = $total_iva;

                    $total_ico = $impuesto_al_consumo + $ico_temp;
                    $ico_temp = $total_ico;

                    $sub_total = $detalle['valor_venta_real'] * $detalle['cantidadproducto_factura_venta'];
                    $sub_totales = $sub_total + $venta_real_temp;
                    $venta_real_temp = $sub_totales;
                }


                $total = model('productoFacturaVentaModel')->selectSum('total')->where('id_factura', $id_factura)->find();

                $returnData = array(
                    "id_factura" => $id_factura,
                    "efectivo" => number_format($efectivo_sin_punto + $transaccion_sin_punto, 0, ",", "."),
                    "cambio" => number_format(($efectivo_sin_punto + $transaccion_sin_punto) - (($valorfactura_forma_pago['valor_total'] - $descuento) + $propina), 0, ",", "."),
                    //"total" =>  number_format($valorfactura_forma_pago['valor_total'], 0, ",", "."),
                    //"total" =>  50000,
                    "id_regimen" => 1,
                    "Sub_total" => number_format($sub_totales, 0, ",", "."),
                    "iva" => number_format($total_iva, 0, ",", "."),
                    "impuesto_al_consumo" => number_format($total_ico, 0, ",", "."),
                    "total" => number_format(($total[0]['total'] - $descuento) + $propina, 0, ",", "."),
                    "result" => $resultado,
                    "facturas_restantes" => number_format($result, 0, ",", ".")
                );
                $borrar_pedido = model('pedidoPosModel')->where('fk_usuario', $id_usuario);
                $borrar_pedido->delete();
                echo  json_encode($returnData);
            }

            if ($efectivo_sin_punto == 0 and $transaccion_sin_punto > 0) {



                $insert_efectivo = model('facturaFormaPagoModel')->insert($factura_forma_pago_transaccion);

                $pk_pedido_pos = model('pedidoPosModel')->select('id')->where('fk_usuario', $id_usuario)->first();
                $borrar_producto_pedido = model('productoPedidoPosModel')->where('pk_pedido_pos', $pk_pedido_pos['id']);
                $borrar_producto_pedido->delete();


                //$iva = model('productoFacturaVentaModel')->selectSum('iva')->where('id_factura', $id_factura)->find();
                //$ico = model('productoFacturaVentaModel')->selectSum('i')->where('id_factura', $id_factura)->find();
                $cantidad_iva = model('productoFacturaVentaModel')->impuestos($id_factura);
                $iva_temp = 0;
                $ico_temp = 0;
                $venta_real_temp = 0;
                foreach ($cantidad_iva  as $detalle) {
                    $iva = $detalle['cantidadproducto_factura_venta'] * $detalle['iva'];
                    $impuesto_al_consumo = $detalle['cantidadproducto_factura_venta'] * $detalle['impuesto_al_consumo'];
                    $total_iva = $iva + $iva_temp;
                    $iva_temp = $total_iva;

                    $total_ico = $impuesto_al_consumo + $ico_temp;
                    $ico_temp = $total_ico;

                    $sub_total = $detalle['valor_venta_real'] * $detalle['cantidadproducto_factura_venta'];
                    $sub_totales = $sub_total + $venta_real_temp;
                    $venta_real_temp = $sub_totales;
                }


                $total = model('productoFacturaVentaModel')->selectSum('total')->where('id_factura', $id_factura)->find();

                $returnData = array(
                    "id_factura" => $id_factura,
                    "resultado" => 1,
                    "efectivo" => number_format($efectivo_sin_punto, 0, ",", "."),
                    "cambio" => number_format($transaccion_sin_punto - (($valorfactura_forma_pago['valor_total'] - $descuento) + $propina), 0, ",", "."),
                    //"total" =>  number_format($valorfactura_forma_pago['valor_total'], 0, ",", "."),
                    "id_regimen" => 1,
                    "Sub_total" => number_format($sub_totales, 0, ",", "."),
                    "iva" => number_format($total_iva, 0, ",", "."),
                    "impuesto_al_consumo" => number_format($total_ico, 0, ",", "."),
                    "total" => ($total[0]['total'] - $descuento) + $propina,
                    "result" => $resultado,
                    "facturas_restantes" => number_format($result, 0, ",", ".")
                );
                $borrar_pedido = model('pedidoPosModel')->where('fk_usuario', $id_usuario);
                $borrar_pedido->delete();
                echo  json_encode($returnData);
            }
        } else if ($valor_venta > $total_pagado) {

            $returnData = array(
                "resultado" => 0, //Falta plata  
            );
            echo  json_encode($returnData);
        }
    }


    public function factura_pos()
    {
        return redirect()->to(base_url('factura_pos/factura_pos'))->with('mensaje', 'Creación correcta');
    }

    public function eliminar_producto()
    {
        $id_tabla_producto = $_REQUEST['id_tabla_producto'];
        $codigo_interno_producto = model('productoPedidoPosModel')->select('codigointernoproducto')->where('id', $id_tabla_producto)->first();
        $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigointernoproducto'])->first();
        $returnData = array(

            "resultado" => 1,
            "nombre_producto" => $nombre_producto['nombreproducto'],
        );
        echo  json_encode($returnData);
    }

    public function eliminacion_de_producto()
    {
        $id_tabla_producto = $_REQUEST['id_tabla_producto'];
        $numero_pedido = model('productoPedidoPosModel')->select('pk_pedido_pos')->where('id', $id_tabla_producto)->first();

        $borrar_producto_pedido = model('productoPedidoPosModel')->where('id', $id_tabla_producto);
        $borrar_producto_pedido->delete();

        $valor_total = model('productoPedidoPosModel')->selectSum('valor_total')->where('pk_pedido_pos', $numero_pedido['pk_pedido_pos'])->find();

        //echo $valor_total[0]['valor_total'] ; exit('valor_total');

        $data = [
            'valor_total' => $valor_total[0]['valor_total']
        ];

        $pedido_pos = model('pedidoPosModel');
        $numero_factura = $pedido_pos->set($data);
        $numero_factura = $pedido_pos->where('id', $numero_pedido['pk_pedido_pos']);
        $numero_factura = $pedido_pos->update();


        $productos_pedido_pos = model('productoPedidoPosModel')->producto_pedido_pos($numero_pedido['pk_pedido_pos']);
        $productos_del_pedido = view('productos_pedido_pos/productos_pedido', [
            "productos" => $productos_pedido_pos
        ]);
        $returnData = array(
            "resultado" => 1,
            "productos" => $productos_del_pedido,
            "total" => number_format($valor_total[0]['valor_total'], 0, ",", "."),
        );
        echo  json_encode($returnData);
    }

    function comanda_directa()
    {
        $id_usuario = $_REQUEST['id_usuario'];

        $hay_pedido = model('pedidoPosModel')->select('id')->where('fk_usuario', $id_usuario)->first();

        if (!empty($hay_pedido)) {

            $id_pedido = model('pedidoPosModel')->select('id')->where('fk_usuario', $id_usuario)->first();

            $items = model('productoPedidoModel')->productos_pedido_pos_sin_pedido($id_pedido['id']);


            if (!empty($items)) {

                $codigo_categoria = model('productoPedidoPosModel')->id_categoria($hay_pedido['id']);

                foreach ($codigo_categoria as $valor) {
                    $nombre_categoria = model('categoriasModel')->select('nombrecategoria')->where('codigocategoria', $valor['id_categoria'])->first();
                    $impresora = model('categoriasModel')->select('impresora')->where('codigocategoria', $valor['id_categoria'])->first();
                    $this->generar_comanda($hay_pedido['id'], $nombre_categoria['nombrecategoria'], $impresora['impresora'], $valor['id_categoria']);
                }
                $returnData = array(
                    "resultado" => 1, //Hay numero de pedido
                );
                echo  json_encode($returnData);
            } else if (empty($items)) {
                $returnData = array(
                    "resultado" => 0, //Hay numero de pedido
                );
                echo  json_encode($returnData);
            }
        } else  if (empty($hay_pedido)) {
            $returnData = array(
                "resultado" => 0, //Hay numero de pedido
            );
            echo  json_encode($returnData);
        }
    }
    public function generar_comanda($numero_pedido, $nombre_categoria, $id_impresora, $id_categoria)
    {



        $productos = model('productoPedidoPosModel')->imprimir_productos_pedido_pos($numero_pedido, $id_categoria);

        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora)->first();

        $id_usuario = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();
        $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario)->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);

        if (!empty($productos)) {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 1);
            $printer->text("**" . $nombre_categoria . "**" . "\n");


            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $printer->text("Pedido N°" . $numero_pedido . "\n");
            $printer->text("Fecha :" . "   " . date('d/m/Y ') . "\n");
            $printer->text("Hora  :" . "   " . date('h:i:s a', time()) . "\n");



            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("----------------------------------------------- \n");
            $printer->text("CÓDIGO   PRODUCTO      CANTIDAD     NOTAS  \n");
            $printer->text("----------------------------------------------- \n");


            $items = model('productoPedidoPosModel')->productos_pedido_pos($numero_pedido, $id_categoria);

            foreach ($productos as $productos) {

                $data = [
                    'impreso_en_comanda' => true,
                ];

                $actualizar = model('productoPedidoPosModel')->set($data);
                $actualizar = model('productoPedidoPosModel')->where('id', $productos['id']);
                $actualizar = model('productoPedidoPosModel')->update();
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(2, 1);
                $printer->text($productos['nombreproducto'] . "\n");
                $printer->text("Cant. " . $productos['cantidad_producto'] . "\n");
                if (!empty($productos['nota_producto'])) {
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text("NOTAS:\n");
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text($productos['nota_producto'] . "\n");
                }
                $printer->text("-----------------------\n");
                $printer->text("\n");
            }


            $observaciones_genereles = model('pedidoPosModel')->select('nota_general')->where('id', $numero_pedido)->first();

            if (!empty($observaciones_genereles['nota_general'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(1, 2);
                $printer->text("** OBSERVACIONES GENERALES **\n");
                $printer->text("\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(2, 1);
                $printer->text($observaciones_genereles['nota_general'] . "\n");
            }

            /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
            */
            $printer->cut();
        }
        /*
            Por medio de la impresora mandamos un pulso.
            Esto es útil cuando la tenemos conectada
            por ejemplo a un cajón
            */
        //$printer->pulse();
        $printer->close();
        # $printer = new Printer($connector);

        //$milibreria = new Ejemplolibreria();
        //$data = $milibreria->getRegistros();

    }

    function reporteCosto()
    {
        $fechaInicial = date('Y-m-d');
        $fechaFinal = date('Y-m-d');

        $productos = model('ReporteImpuestosModel')->getProductos($fechaInicial, $fechaFinal);


        $total = model('ReporteImpuestosModel')->getTotal($fechaInicial, $fechaFinal);


        return view('reportes/costo_producto', [
            'productos' => $productos,
            'total' => $total
        ]);
    }

    function reporteCostoExcel() {}

    function BuscarReporteCosto()
    {

        $json = $this->request->getJSON();
        $fecha_inicial = $json->fecha_inicial;
        $fecha_final = $json->fecha_final;

        $productos = model('ReporteImpuestosModel')->getProductos($fecha_inicial, $fecha_final);
        $total = model('ReporteImpuestosModel')->getTotal($fecha_inicial, $fecha_final);


        return $this->response->setJSON([
            'response' => 'success',
            'productos' => view('reportes/costoProducto', [
                'productos' => $productos
            ]),
            'total' => "Total  " . number_format($total[0]['total'], 0, '.', '.'),
            'iva' => "Iva  " . number_format($total[0]['iva'], 0, '.', '.'),
            'inc' => "Impoconsumo:  " . number_format($total[0]['inc'], 0, '.', '.')

        ]);
    }

    function exportCostoExcel()
    {
        $fecha_inicial = $this->request->getPost('fecha_inicial');
        $fecha_final = $this->request->getPost('fecha_final');
        $datos_empresa = model('empresaModel')->datosEmpresa();

        //$invoices = model('pagosModel')->getDocumentosCosto($fecha_inicial, $fecha_final);

        $productos = model('ReporteImpuestosModel')->getProductos($fecha_inicial, $fecha_final);
        $total = model('ReporteImpuestosModel')->getTotal($fecha_inicial, $fecha_final);


        $file_name = 'Reporte de costos de ' . $fecha_inicial . ' al ' . $fecha_final . '.xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Aplicar la fuente Aptos Narrow de tamaño 11 a todas las celdas
        $spreadsheet->getDefaultStyle()->getFont()->setName('Aptos Narrow')->setSize(11);

        // Establecer el estilo de las celdas del encabezado
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => '000000'], // Fuente en color negro
                'name' => 'Aptos Narrow',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'F2F2F2'], // Fondo gris más claro
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'], // Bordes negros
                ],
            ],
        ];



        $sheet->setCellValue('A1', $datos_empresa[0]['nombrejuridicoempresa']);
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->setCellValue('A2', $datos_empresa[0]['nombrecomercialempresa']);
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2:G2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:G2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->setCellValue('A3', 'NIT: ' . $datos_empresa[0]['nitempresa']);
        $sheet->mergeCells('A3:G3');
        $sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:G3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->setCellValue(
            'A4',
            'Dirección: ' . $datos_empresa[0]['direccionempresa'] . " " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento']
        );
        $sheet->mergeCells('A4:G4');
        $sheet->getStyle('A4:G4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:G4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->setCellValue('A5', 'REPORTE DE COSTO DE VENTA');
        $sheet->mergeCells('A5:G5');

        // Aplicar alineación centrada
        $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->setCellValue('A6', "Fecha inicial");
        $sheet->setCellValue('B6', $fecha_inicial);

        $sheet->setCellValue('D6', "Fecha final");
        $sheet->setCellValue('E6', $fecha_final);

        $sheet->setCellValue('F6', "Fecha generacion");
        $sheet->setCellValue('E6', date('Y-m-d'));




        $sheet->getStyle('A8:l8')->applyFromArray($headerStyle);
        $sheet->setCellValue('A8', 'Fecha ');
        $sheet->setCellValue('B8', 'Documento');
        $sheet->setCellValue('C8', 'Tipo documento');
        $sheet->setCellValue('D8', 'Código');
        $sheet->setCellValue('E8', 'Producto');
        $sheet->setCellValue('F8', 'Cantidad');
        $sheet->setCellValue('G8', 'Costo unidad  ');
        $sheet->setCellValue('H8', 'Valor unidad  ');
        $sheet->setCellValue('I8', 'Base ');
        $sheet->setCellValue('J8', 'IVA  ');
        $sheet->setCellValue('k8', 'INC  ');
        $sheet->setCellValue('l8', 'Total  ');

        $row = 9;

        $totalCosto = 0;
        $totalIVA = 0;
        $totalICO = 0;

        //dd($invoices);

        /*  foreach ($invoices as $detalle) {
            //$costo = model('kardexModel')->selectSum('costo')->where('id_factura', $detalle['id_factura'])->findAll();
            $costo = model('kardexModel')->getCosto($detalle['id_factura'], $detalle['id_estado']);
            //$iva = model('kardexModel')->selectSum('iva')->where('id_factura', $detalle['id_factura'])->findAll();
            $iva = model('kardexModel')->getIva($detalle['id_factura'], $detalle['id_estado']);
            //$inc = model('kardexModel')->selectSum('ico')->where('id_factura', $detalle['id_factura'])->findAll();
            $inc = model('kardexModel')->getInc($detalle['id_factura'], $detalle['id_estado']);
            $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $detalle['nit_cliente'])->first();

            if ($detalle['id_estado'] == 8) {
                $temp_documento = model('facturaElectronicaModel')->select('numero')->where('id', $detalle['id_factura'])->first();
                $documento = $temp_documento['numero'];
            } else if ($detalle['id_estado'] != 8) {
                //$temp_doc=model('estadoModel')->select('descripcionestado')->where('idestado',$detalle['id_estado'])->first();
                $documento = $detalle['documento'];
            }

            $tipo_documento = model('estadoModel')->select('descripcionestado')->where('idestado', $detalle['id_estado'])->first();

            $sheet->setCellValue("A$row", $detalle['fecha']);
            //$sheet->setCellValueExplicit("B$row", $detalle['nit_cliente'], DataType::TYPE_STRING);
            $sheet->setCellValue("B$row", "'" . $detalle['nit_cliente']);
            $sheet->setCellValue("C$row", $nombre_cliente['nombrescliente']);
            $sheet->setCellValue("D$row", $tipo_documento['descripcionestado']);
            //$sheet->setCellValue("E$row", $documento['numero']);
            $sheet->setCellValue("E$row", $documento);
            $sheet->setCellValue("F$row", $costo[0]['costo']);
            $sheet->setCellValue("G$row", round($detalle['total_documento'] - ($iva[0]['iva'] + $inc[0]['ico']), 0));
            $sheet->setCellValue("H$row", round($iva[0]['iva'], 0));
            $sheet->setCellValue("I$row", round($inc[0]['ico'], 0));
            $sheet->setCellValue("J$row", round($detalle['total_documento'], 0));

            $totalCosto += $costo[0]['costo'];
            $totalIVA += $iva[0]['iva'];
            $totalICO += $inc[0]['ico'];
            $row++;
        } 

        $row++;
        //$sheet->setCellValue("A$row", "TOTAL VENTA");
        $sheet->setCellValue("A$row", "TOTAL COSTO");
        $sheet->setCellValue("B$row", "TOTAL IVA ");
        $sheet->setCellValue("C$row", "TOTAL INC");
        $sheet->setCellValue("D$row", "TOTAL VENTA");

        $total = model('pagosModel')->getTotalVenta($fecha_inicial, $fecha_final);
        $row++;
ss
        //$sheet->setCellValue("A$row", $total[0]['total']);
        $sheet->setCellValue("A$row", round($totalCosto, 0));
        $sheet->setCellValue("B$row", round($totalIVA, 0));
        $sheet->setCellValue("C$row", round($totalICO, 0));
        $sheet->setCellValue("D$row", $total[0]['total']);*/

        $row = 9;
        foreach ($productos as $detalle) {
            $sheet->setCellValue("A$row", $detalle['fecha']);
            $sheet->setCellValue("B$row", $detalle['numerodocumento']);
            $sheet->setCellValue("C$row", $detalle['descripcionestado']);
            $sheet->setCellValue("D$row", $detalle['codigo']);
            $sheet->setCellValue("E$row", $detalle['nombreproducto']);
            $sheet->setCellValue("F$row", $detalle['cantidad']);
            $sheet->setCellValue("G$row", round($detalle['costo'] / $detalle['cantidad'], 0));
            $sheet->setCellValue("H$row", round($detalle['valor_unitario'], 0));
            $sheet->setCellValue("I$row", round($detalle['total'] - ($detalle['ico'] + $detalle['iva']), 0));
            $sheet->setCellValue("J$row", round($detalle['iva'], 0));
            $sheet->setCellValue("K$row", round($detalle['ico'], 0));
            $sheet->setCellValue("L$row", round($detalle['total'], 0));


            $row++;
        }

        $row++;

        $sheet->setCellValue("A$row", "Total:");
        $sheet->setCellValue("B$row", round($total[0]['total'], 0));

        $row++; // Avanza otra fila para el IVA

        $sheet->setCellValue("A$row", "IVA:");
        $sheet->setCellValue("B$row", round($total[0]['iva'], 0));

        $row++; // Avanza otra fila para el Impoconsumo

        $sheet->setCellValue("A$row", "Impoconsumo:");
        $sheet->setCellValue("B$row", round($total[0]['inc'], 0));

        $writer = new Xlsx($spreadsheet);
        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length:' . filesize($file_name));
        flush();
        readfile($file_name);
        exit;
    }
}
