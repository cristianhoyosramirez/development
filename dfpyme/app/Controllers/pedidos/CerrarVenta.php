<?php

namespace App\Controllers\pedidos;

use App\Controllers\BaseController;
use \DateTime;
use \DateTimeZone;

require APPPATH . "Controllers/mike42/autoload.php";

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use App\Libraries\Inventario;
use App\Libraries\Propina;

class CerrarVenta extends BaseController
{
    public function index()
    {
        return view('home/home');
    }

    public function cerrar_venta()
    {

        $id_mesa = $this->request->getPost('id_mesa');
        //$id_mesa = 1;
        $pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();
        $numero_pedido = $pedido['id'];

        $validar_pedido = model('productoPedidoModel')->validar_pedido($numero_pedido);


        $suma_pedido = model('productoPedidoModel')->total_pedido($numero_pedido);
        $total_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();


        if ($validar_pedido[0]['total'] == 0 and $suma_pedido[0]['total'] == $total_pedido['valor_total']) {


            $items = model('productoPedidoModel')->where('numero_de_pedido', $numero_pedido)->find();

            $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();

            //$datos_empresa = model('empresaModel')->datosEmpresa();

            $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

            $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
            $printer = new Printer($connector);


            $printer->pulse();
            $printer->close();



            /*      $id_mesa = 2; */
            $pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();
            $numero_pedido = $pedido['id'];
            //$numero_pedido = 13;
            /*    $efectivo = 3000;
            $transaccion = 0;
            $valor_venta = 3000;
            $nit_cliente = '222222222222';
            $id_usuario = 6;
            $estado = 1;
            $propina = 0;
            $descuento = 0;
            $tipo_pago = 1; */

            // var_dump($this->request->getPost()); exit();


            if ($_POST['estado'] != 6) {
                $efectivo = $_POST['efectivo'];
                $transaccion = $_POST['transaccion'];
            }
            if ($_POST['estado'] == 6) {
                $efectivo = 0;
                $transaccion = 0;
            }
            $valor_venta = $_POST['valor_venta'];
            $nit_cliente = $_POST['nit_cliente'];
            $id_usuario = $_POST['id_usuario'];
            $estado = $_POST['estado'];
            $propina = $_POST['propina_Format'];
            $descuento = 0;
            $tipo_pago = $_POST['tipo_pago'];



            $rol = model('usuariosModel')->select('idtipo')->where('idusuario_sistema', $id_usuario)->first();
            if ($rol['idtipo'] == 1 or $rol['idtipo'] == 0) {

                $alerta_facturacion = "";
                /**
                 * Datos dian
                 */
                $id_dian = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', '6')->first();
                $numero_facturas = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', '8')->first();
                $alerta = model('dianModel')->select('alerta_facturacion')->where('iddian', $id_dian['numeroconsecutivo'])->first();
                $rango_final = model('dianModel')->select('rangofinaldian')->where('iddian', $id_dian['numeroconsecutivo'])->first();
                $fecha_dian  = model('dianModel')->select('vigencia')->where('iddian', $id_dian['numeroconsecutivo'])->first();
                $prefijo_factura = model('dianModel')->select('inicialestatica')->where('iddian', $id_dian['numeroconsecutivo'])->first();
                $serie = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', '14')->first();

                if ($estado == 1    or $estado == 2) {
                    $serie_update  = $serie['numeroconsecutivo'] + 1;
                    $incremento = model('consecutivosModel')->update_serie($serie_update);
                    $numeracion_factura = $prefijo_factura['inicialestatica'] . "-" . $numero_facturas['numeroconsecutivo'];
                }
                if ($estado == 7) {

                    $consectivo_remision = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 11)->first();
                    $serie_remision  = $consectivo_remision['numeroconsecutivo'] + 1;
                    $incremento_remision = model('consecutivosModel')->actualizar_consecutivos($serie_remision);
                    $numeracion_factura = $consectivo_remision['numeroconsecutivo'];
                }
                if ($estado == 6) {

                    $consectivo_cortesia = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 103)->first();
                    $serie_cortesia  = $consectivo_cortesia['numeroconsecutivo'] + 1;
                    $incremento_cortesia = model('consecutivosModel')->actualizar_consecutivos($serie_cortesia);
                    $numeracion_factura = "CORT-" . $consectivo_cortesia['numeroconsecutivo'];

                    $updateConse=model('consecutivosModel')->set('numeroconsecutivo',$serie_cortesia)->where('idconsecutivos', 103)->update();
                }



                $id_apertura = model('aperturaRegistroModel')->select('numero')->first();
                /**
                 * Calcular la vigencia de la resolucion por fechas 
                 */

                $fecha_actual = new DateTime(date('Y-m-d'));
                $dian = new DateTime($fecha_dian['vigencia']);
                $diferencia_fecha = $fecha_actual->diff($dian);

                /**
                 * En caso de que la alerta de facturas faltante este vacia por defecto asignamos 200 
                 */
                if (!empty($alerta)) {
                    $alerta_facturacion = $alerta['alerta_facturacion'];
                }
                if (empty($alerta)) {
                    $alerta_facturacion = 200;
                }
                /**
                 * Calcualar cuantas se pueden facturar 
                 */
                $facturas_sin_alerta = $rango_final['rangofinaldian'] - $alerta_facturacion;

                /**
                 * Fecha y hora actual 
                 */
                $fecha = date("Y-m-d ");
                $hora = date("H:i:s");


                /**
                 * Datos del pedido
                 */

                $valor_total = model('pedidoModel')->select('valor_total')->where('fk_mesa', $id_mesa)->first();
                $observaciones_genereles = model('pedidoModel')->select('nota_pedido')->where('fk_mesa', $id_mesa)->first();
                $fk_usuario_mesero = model('pedidoModel')->select('fk_usuario')->where('fk_mesa', $id_mesa)->first();

                $saldo = '';
                if ($estado == 1 or $estado == 7 or $estado == 6) {
                    $saldo = 0;
                }
                if ($estado == 2 ) {
                    $saldo = $valor_total['valor_total'];
                }

                $fech = DateTime::createFromFormat('U.u', microtime(TRUE));
                $fech->setTimeZone(new DateTimeZone('America/Bogota'));
                $fecha_y_hora = $fech->format('Y-m-d H:i:s.u');


                $facturas_restantes = $rango_final['rangofinaldian'] - $numero_facturas['numeroconsecutivo'];

                if ($numero_facturas['numeroconsecutivo'] <= $rango_final['rangofinaldian'] and $diferencia_fecha->format('%R%a') >= 0) {  // Se puede facturar esta bien la fecha y la numeracion

                    $factura_venta = model('facturaVentaModel')->factura_venta(
                        $numeracion_factura,
                        $nit_cliente,
                        $id_usuario,
                        $estado,
                        $hora,
                        $fecha,
                        $serie['numeroconsecutivo'],
                        $id_dian['numeroconsecutivo'],
                        $observaciones_genereles['nota_pedido'],
                        $fk_usuario_mesero['fk_usuario'],
                        $saldo,
                        $valor_total['valor_total'],
                        $id_mesa,
                        $numero_pedido,
                        $fecha_y_hora,
                        $descuento,
                        $propina,
                        $id_apertura['numero']

                    );


                    $apertura = model('aperturaRegistroModel')->select('numero')->where('idcaja', 1)->first();
                    //$id_mesero = model('mesasModel')->select('id_mesero')->where('id', $id_mesa)->first();
                    $id_mesero = model('pedidoModel')->select('fk_usuario')->where('fk_mesa', $id_mesa)->first();

                    //Guardar la propina 
                    $data = [
                        'estado' => $estado,
                        'valor_propina' => $propina,
                        'id_factura' => $factura_venta,
                        'id_apertura' => $apertura['numero'],
                        'fecha_y_hora_factura_venta' => $fecha_y_hora,
                        'fecha' => $fecha,
                        'hora' => $hora,
                        'id_mesero' => $id_mesero['fk_usuario'],
                        'id_mesa' => $id_mesa
                    ];

                    $propina_factura = model('FacturaPropinaModel')->insert($data);

                    $consecutivo = ['numeroconsecutivo' => $numero_facturas['numeroconsecutivo'] + 1];
                    //$numero_factura = ['numero_factura' => $prefijo_factura['inicialestatica'] . "-" . $numero_facturas['numeroconsecutivo']];
                    $numero_factura = ['numero_factura' => $prefijo_factura['inicialestatica'] . "-" . $numero_facturas['numeroconsecutivo']];

                    $actualiar_pedido_consecutivos =   model('cerrarVentaModel')->actualiar_pedido_consecutivos($numero_pedido, $numero_factura, $consecutivo);





                    if ($tipo_pago == 1) {
                        $productos = model('productoPedidoModel')->where('numero_de_pedido', $numero_pedido)->find();
                        //Insertar en la tabla producto factura_venta 
                        $insertar_productos = model('cerrarVentaModel')->producto_pedido($productos, $factura_venta, $numero_pedido, $prefijo_factura['inicialestatica'] . "-" . $numero_facturas['numeroconsecutivo'], $fecha_y_hora, $tipo_pago, $id_usuario, $apertura['numero'], $estado);
                    }


                    if ($tipo_pago == 0) {

                        $productos = model('partirFacturaModel')->get_productos($numero_pedido);
                        $insertar_productos = model('cerrarVentaModel')->producto_pedido($productos, $factura_venta, $numero_pedido, $prefijo_factura['inicialestatica'] . "-" . $numero_facturas['numeroconsecutivo'], $fecha_y_hora, $tipo_pago, $id_usuario, $apertura['numero'], $estado);
                    }

                    if ($efectivo > 1) {
                        //Insertar en la tabla factura venta 
                        $forma_pago_efectivo = model('facturaFormaPagoModel')->factura_forma_pago(
                            $numero_factura['numero_factura'], //Numero de factura 
                            $id_usuario, // id de l usuario
                            1, // id_forma_pago 1 = efectivo 
                            $fecha,
                            $hora,
                            $valor_venta,
                            $efectivo, // con cuanto pagan en efectivo la factura 
                            $factura_venta, // id de la factura 
                            $fecha_y_hora,
                            $propina
                        );
                    }

                    if ($transaccion > 1) {
                        $forma_pago_transaccion = model('facturaFormaPagoModel')->factura_forma_pago(
                            $numero_factura['numero_factura'], //Numero de factura 
                            $id_usuario, // id de l usuario
                            4, // id_forma_pago 1 = efectivo 
                            $fecha,
                            $hora,
                            $valor_venta,
                            $transaccion, // con cuanto pagan en efectivo la factura 
                            $factura_venta, // id de la factura 
                            $fecha_y_hora,
                            $propina
                        );
                    }


                    $suma_pagos = $efectivo + $transaccion;

                    if ($suma_pagos == $valor_venta) {

                        if ($efectivo == $transaccion) {
                            $valor_pago_efectivo = $efectivo;
                            $valor_pago_transferencia = $transaccion;
                            $cambio = 0;
                            $recibido_efectivo = $efectivo;
                            $recibido_transaccion = $transaccion;
                        } else if ($transaccion == $valor_venta) {
                            $valor_pago_efectivo = 0;
                            $recibido_efectivo = 0;
                            $recibido_transaccion = $transaccion;
                            $valor_pago_transferencia = $transaccion;
                            $cambio = 0;
                        } else {

                            $valor_pago_efectivo = $efectivo;
                            $recibido_efectivo = $efectivo;
                            $recibido_transaccion = $transaccion;
                            $valor_pago_transferencia = $transaccion;
                            $cambio = 0;
                        }
                    }


                    if ($suma_pagos > $valor_venta) {
                        // Caso 1: Pago en efectivo sin transacción
                        if ($efectivo > 0 && $transaccion == 0) {
                            $valor_pago_transferencia = 0;
                            $valor_pago_efectivo = $valor_venta;
                            $cambio = $efectivo - $valor_venta;
                            $recibido_transaccion = 0;
                            $recibido_efectivo = $efectivo;
                        }
                        // Caso 2: Pago mediante transacción sin efectivo
                        elseif ($efectivo == 0 && $transaccion > 0) {
                            $valor_pago_transferencia = $valor_venta;
                            $valor_pago_efectivo = 0;
                            $cambio = $transaccion - $valor_venta;
                            $recibido_transaccion = $transaccion;
                            $recibido_efectivo = 0;
                        }
                        // Caso 3: Ambos efectivo y transacción están involucrados
                        elseif ($efectivo > 0 && $transaccion > 0) {
                            // Caso 3.1: Mayor transacción que efectivo
                            if ($transaccion > $efectivo) {
                                $valor_pago_transferencia = $valor_venta;
                                $valor_pago_efectivo = 0;
                                $cambio = $transaccion + $efectivo - $valor_venta;
                                $recibido_transaccion = $transaccion;
                                $recibido_efectivo = $efectivo;
                            }
                            // Caso 3.2: Mayor efectivo que transacción
                            elseif ($efectivo > $transaccion) {
                                $valor_pago_transferencia = $transaccion;
                                $valor_pago_efectivo = $valor_venta - $transaccion;
                                $cambio = $transaccion + $efectivo - $valor_venta;
                                $recibido_transaccion = $transaccion;
                                $recibido_efectivo = $efectivo;
                            }
                        }
                    }

                    if ($efectivo == 0 and $transaccion == 0) {

                        $valor_pago_transferencia = 0;
                        $valor_pago_efectivo = 0;
                        $cambio = 0;
                        $recibido_transaccion = 0;
                        $recibido_efectivo = 0;
                    }

                    if ($estado == 2) {
                        $valor_pago_transferencia = 0;
                        $valor_pago_efectivo = 0;
                        $cambio = 0;
                        $recibido_transaccion = 0;
                        $recibido_efectivo = 0;
                    }



                    $numero_pedido = $pedido['id'];
                    $id_mesero = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();

                    //$id_pedido = model('pagosModel')->select('id_pedido')->where('id_pedido', $numero_pedido)->first();

                    //if (empty($id_pedido['id_pedido'])) {

                    $pagos = [

                        'fecha' => date('Y-m-d'),
                        'hora' => date("H:i:s"),
                        'documento' => $numeracion_factura,
                        'valor' => $valor_venta - $propina,
                        'propina' => $propina,
                        'total_documento' => $valor_venta,
                        'efectivo' => $valor_pago_efectivo,
                        'transferencia' => $valor_pago_transferencia,
                        'total_pago' => $efectivo + $transaccion,
                        'id_usuario_facturacion' => $id_usuario,
                        'id_mesero' => $id_mesero['fk_usuario'],
                        'id_estado' => $estado,
                        'id_apertura' => $id_apertura['numero'],
                        'cambio' => $cambio,
                        'recibido_efectivo' => $recibido_efectivo,
                        'recibido_transferencia' => $recibido_transaccion,
                        'id_factura' => $factura_venta,
                        'saldo' => $saldo,
                        'nit_cliente' => $nit_cliente,
                        'id_pedido' => $numero_pedido

                    ];

                    $pagos = model('pagosModel')->insert($pagos);
                    //}

                    if ($tipo_pago == 1) {  // si el tipo de pago es 1 quiere decir que se factura el pedido completo 
                        // borrar productos del pedido 
                        $borrar_producto_pedido = model('productoPedidoModel')->where('numero_de_pedido', $numero_pedido);
                        $borrar_producto_pedido->delete();

                        //Borrar el pedido 
                        $borrar_producto_pedido = model('pedidoModel')->where('id', $numero_pedido);
                        $borrar_producto_pedido->delete();
                    }
                    if ($tipo_pago == 0) {  // Si el tipo de pago es 0 es un pago parcial se debe borrar la tabla partir factura y actualizar la tabla pedido 
                        /*   $borrar_partir_factura = model('partirFacturaModel')->where('numero_de_pedido', $numero_pedido);
                $borrar_partir_factura->delete(); */
                        $model = model('partirFacturaModel');
                        $borrar = $model->truncate();


                        $total = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->findAll();

                        if (empty($total[0]['valor_total'])) {

                            $borrar_producto_pedido = model('pedidoModel')->where('id', $numero_pedido);
                            $borrar_producto_pedido->delete();
                        }

                        if (!empty($total[0]['valor_total'])) {
                            $model = model('pedidoModel');
                            $actualizar = $model->set('valor_total', $total[0]['valor_total']);
                            $actualizar = $model->where('id', $numero_pedido);
                            $actualizar = $model->update();
                        }
                    }
                    $mensaje = "";





                    if ($numero_facturas['numeroconsecutivo'] >= $facturas_sin_alerta and $diferencia_fecha->format('%R%a') > 0) {
                        $mensaje = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Le quedan ' . $facturas_restantes . ' facturas restantes.</strong> 
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
                    }
                    if ($numero_facturas['numeroconsecutivo'] >= $facturas_sin_alerta and $diferencia_fecha->format('%R%a') == 0) {
                        $mensaje = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Le quedan ' . $facturas_restantes . ' facturas y hoy se vence por fecha.</strong> 
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
                    }
                    if ($numero_facturas['numeroconsecutivo'] < $facturas_sin_alerta) {
                        $mensaje = "";
                    }

                    $mesas = model('mesasModel')->where('estado', 0)->orderBy('id', 'ASC')->findAll();

                    $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido);
                    $valor_pedido = "";
                    $val_pedido = model('pedidoModel')->select('valor_total')->where('fk_mesa', $id_mesa)->first();
                    if (empty($val_pedido)) {
                        $valor_pedido = 0;
                    }
                    if (!empty($val_pedido)) {
                        $valor_pedido = $val_pedido['valor_total'];
                    }
                    $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa)->first();


                    if ($tipo_pago == 0) {
                        $returnData = array(
                            "id_factura" => $factura_venta,
                            "resultado" => 1,
                            "total" => "$ " . number_format($valor_venta, 0, ",", "."),
                            "valor_pago" => "$ " . number_format($efectivo + $transaccion, 0, ",", "."),
                            "cambio" => "$ " . number_format(($efectivo + $transaccion) - $valor_venta, 0, ",", "."),
                            "mensaje" => $mensaje,
                            "mesas" => view('pedidos/todas_las_mesas_lista', [
                                "mesas" => $mesas,
                            ]),
                            "productos" => view('pedidos/productos_pedido', [
                                "productos" => $productos_pedido,
                                "pedido" => $numero_pedido
                            ]),
                            "id_mesa" => $id_mesa,
                            "valor_pedio" => "$ " . number_format($valor_pedido, 0, ",", "."),
                            "nombre_mesa" => $nombre_mesa['nombre'],
                            "pedido" => $pedido['id'],
                            "tipo_pago" => $tipo_pago,
                            "documentos" => view('pedidos/documento')


                        );

                        echo  json_encode($returnData);
                    }


                    if ($tipo_pago == 1) {

                        $returnData = array(
                            "id_factura" => $factura_venta,
                            "resultado" => 1,
                            "total" => "$ " . number_format($valor_venta, 0, ",", "."),
                            "valor_pago" => "$ " . number_format($efectivo + $transaccion, 0, ",", "."),
                            "cambio" => "$ " . number_format(($efectivo + $transaccion) - $valor_venta, 0, ",", "."),
                            "mensaje" => $mensaje,
                            "mesas" => view('pedidos/todas_las_mesas_lista', [
                                "mesas" => $mesas,
                            ]),
                            "tipo_pago" => $tipo_pago,
                            "documentos" => view('pedidos/documento')
                        );

                        echo  json_encode($returnData);
                    }
                } else if ($numero_facturas['numeroconsecutivo'] > $rango_final['rangofinaldian'] and $diferencia_fecha->format('%R%a') >= 0) {  // No se puede facturar por que la numeracion esta vencida 

                    $returnData = array(

                        "resultado" => 0,
                        "mensaje" => "No es posible facturar, resolucion pos vencida por numeracion"

                    );
                    echo  json_encode($returnData);
                } else if ($numero_facturas['numeroconsecutivo'] < $rango_final['rangofinaldian'] and $diferencia_fecha->format('%R%a') < 0) { // No se puede facturar por fecha vencida 

                    $returnData = array(

                        "resultado" => 0,
                        "mensaje" => "No es posible facturar resolucion pos, vencidad por fecha"

                    );
                    echo  json_encode($returnData);
                } else if ($numero_facturas['numeroconsecutivo'] > $rango_final['rangofinaldian'] and $diferencia_fecha->format('%R%a') < 0) { // No se puede facturar por fecha y numeracion 

                    $returnData = array(

                        "resultado" => 0,
                        "mensaje" => "No es posible facturar resolucion pos, vencidad por fecha y numeracion"

                    );
                    echo  json_encode($returnData);
                }
            }
        } else if ($validar_pedido[0]['total'] > 0) {
            $returnData = array(

                "resultado" => 0,
                "mensaje" => "Hay productos con cantidad nulas "

            );
            echo  json_encode($returnData);
        }
    }


    function propinas()
    {

        $id_mesa = $this->request->getPost('id_mesa');
        //$id_mesa = 1;
        $temp_propina = new Propina();
        $propina = $temp_propina->calcularPropina($id_mesa);


        $returnData = array(
            "resultado" => 1,
            "propina" => number_format($propina['propina'], 0, ",", "."),
            "total_pedido" => number_format($propina['propina'] + $propina['valor_pedido'], 0, ",", "."),
            "valor_pedido" => $propina['valor_pedido'],
            "val_pedido" => $propina['valor_pedido'] + $propina['propina'],
        );
        echo  json_encode($returnData);
    }

    function actualizar_mesero()
    {


        // $id_mesero = $this->request->getPost('id_mesero');
        $id_mesero = $this->request->getPost('id_mesero');
        $tipo_usuario = $this->request->getPost('tipo_usuario');



        if ($tipo_usuario == 1 || $tipo_usuario == 0 || $tipo_usuario == 3) {
            $model = model('pedidoModel');
            $actualizar = $model->set('fk_usuario', $id_mesero);
            $actualizar = $model->where('fk_mesa', $this->request->getPost('id_mesa'));
            $nombre_mesero = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_mesero)->first();
            $actualizar = $model->update();
            if ($actualizar) {
                $returnData = array(
                    "resultado" => 1,
                    "nombre_mesero" => $nombre_mesero['nombresusuario_sistema']

                );
                echo  json_encode($returnData);
            }
        }
        if ($tipo_usuario == 2) {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        }
    }
}
