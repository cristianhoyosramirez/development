<?php

namespace App\Controllers\factura_electronica;

use App\Controllers\BaseController;
use App\Libraries\Impuestos;
use App\Libraries\Inventario;
use \DateTime;
use \DateTimeZone;
use App\Libraries\impresion;

require APPPATH . "Controllers/mike42/autoload.php";

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class FacturaElectronica extends BaseController
{
    public function index()
    {
        return view('home/home');
    }

    function pre_factura()
    {
        //var_dump($this->request->getPost());
        $id_mesa = $this->request->getPost('id_mesa');
        // $id_mesa = 6;

        //var_dump($this->request->getPost());

        $pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();
        $numero_pedido = $pedido['id'];
        $imprime_boucher = model('cajaModel')->select('imp_comprobante_transferencia')->where('numerocaja', 1)->first();
        $validar_pedido = model('productoPedidoModel')->validar_pedido($numero_pedido);

        $suma_pedido = model('productoPedidoModel')->total_pedido($numero_pedido);
        $total_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();

        //if ($validar_pedido[0]['total'] == 0 and $suma_pedido[0]['total'] == $total_pedido['valor_total']) {
        if ($validar_pedido[0]['total'] == 0) {
            $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();
            $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();
            $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
            $printer = new Printer($connector);


            $printer->pulse();
            $printer->close();
            $datos_empresa = model('empresaModel')->datosEmpresa();
            $impuestos = new Impuestos();
            $inventario = new Inventario();

            $tipo_pago = $this->request->getPost('tipo_pago');        // Tipo de pago 1 = pago completo; 0 pago parcial
            $id_usuario = $this->request->getPost('id_usuario');      // Tipo de pago 1 = pago completo; 0 pago parcial
            $efectivo = $this->request->getPost('efectivo');         // Tipo de pago 1 = pago completo; 0 pago parcial
            $transaccion = $this->request->getPost('transaccion');  // Tipo de pago 1 = pago completo; 0 pago parcial
            $valor_venta = $this->request->getPost('valor_venta'); // Tipo de pago 1 = pago completo; 0 pago parcial
            $nit_cliente = $this->request->getPost('nit_cliente');
            $estado = $this->request->getPost('estado');
            $pago_total = $this->request->getPost('pago_total');
            $propina = $this->request->getPost('propina_format');
            $medio_de_pago = strval($this->request->getPost('medio_de_pago'));
            $nota = model('pedidoModel')->select('nota_pedido')->where('fk_mesa', $id_mesa)->first();


            /* 
            $id_mesa = 1;
            $tipo_pago = 1;         // Tipo de pago 1 = pago completo; 0 pago parcial
            $id_usuario = 6;      // Tipo de pago 1 = pago completo; 0 pago parcial
            $efectivo = 0;         // Tipo de pago 1 = pago completo; 0 pago parcial
            $transaccion = 100000;  // Tipo de pago 1 = pago completo; 0 pago parcial
            $valor_venta = 100000; // Tipo de pago 1 = pago completo; 0 pago parcial
            $nit_cliente = 222222222222;
            $estado = 8;
            $pago_total = 200000;
            $propina = 0;
            $nota = model('pedidoModel')->select('nota_pedido')->where('fk_mesa', $id_mesa)->first();
            $medio_de_pago = '10'; */



            if ($estado == 11) {
                $saldo = $valor_venta;
            }
            if ($estado != 11) {
                $saldo = 0;
            }


            $id_regimen = model('empresaModel')->select('idregimen')->first();



            if ($tipo_pago == 1) {
                $tem_valor_total = model('pedidoModel')->select('valor_total')->where('fk_mesa', $id_mesa)->first();
                $valor_total = $tem_valor_total['valor_total'];
            }
            if ($tipo_pago == 0) {
                //$tem_valor_parcial = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->first();
                $tem_valor_parcial = model('partirFacturaModel')->propina_partida($numero_pedido);
                $valor_total = $tem_valor_parcial[0]['valor_total'];
            }



            $id_resolucion = model('resolucionElectronicaModel')->select('id')->first();

            $fecha = DateTime::createFromFormat('U.u', microtime(TRUE));
            $fecha->setTimeZone(new DateTimeZone('America/Bogota'));
            $fecha_y_hora = $fecha->format('Y-m-d H:i:s.u');

            $apertura = model('aperturaRegistroModel')->select('numero')->where('idcaja', 1)->first();





            $data = [
                'nit_cliente' => $nit_cliente,
                //'nit_cliente' => '222222222222',
                'estado' => false,
                'tipo' => 'INVOIC',
                'tipo_factura' => '01',
                'tipo_operacion' => '10',
                'tipo_ambiente' => '2',
                'id_status' => 1,
                //'numero' => $numero,
                'fecha' => date('Y-m-d'),
                'hora' => date("H:i:s"),
                'fecha_limite' => date('Y-m-d'),
                'numero_items' => 0,
                //'total' => $valor_total['valor_total'],
                'total' => $valor_total,
                //'neto' => $valor_total['valor_total'],
                'neto' => $valor_total,
                'moneda' => 'COP',
                'id_resolucion' => 0,
                'metodo_pago' => 1,
                //'medio_pago' => '10',
                'medio_pago' => $medio_de_pago,
                'fecha_pago' => date('Y-m-d'),
                'version_ubl' => 'UBL 2.1',
                'version_dian' => 'DIAN 2.1',
                'transaccion_id' => '',
                'id_caja' => 1,
                'cancelled' => true,
                'fecha_y_hora_factura_venta' => $fecha_y_hora,
                'id_apertura' => $apertura['numero'],
                'propina' => $propina,
                'nota' => $nota['nota_pedido']

            ];


            $insert = model('facturaElectronicaModel')->insertar($data);





            $id_fact = model('facturaElectronicaModel')->selectMax('id')->first();

            $id_factura = $id_fact['id'];

            $data = [

                'id_documento' => $id_factura,
                'id_operacion' => 2,
                'fecha' => date('Y-m-d'),
                //'inventario_anterior' => $cantidad_inventario['cantidad_inventario']
                'tabla' => 'documento_electronico'
            ];

            $insert = model('EntradasSalidasModel')->insert($data);


            $id_mesero = model('pedidoModel')->select('fk_usuario')->where('id', $id_mesa)->first();



            $mesero = "";

            if (empty($id_mesero)) {
                $mesero = 0;
            }
            if (!empty($id_mesero)) {
                $mesero = $id_mesero['fk_usuario'];
            }


            $data = [
                'estado' => $estado,
                'valor_propina' => $propina,
                'id_factura' => $id_fact['id'],
                'id_apertura' => $apertura['numero'],
                'fecha_y_hora_factura_venta' => $fecha_y_hora,
                'fecha' => date('Y-m-d'),
                'hora' => date("H:i:s"),
                'id_mesero' => $mesero,
                'id_mesa' => $id_mesa

            ];

            $propina_factura = model('FacturaPropinaModel')->insert($data);

            $numero_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();

            $item = array();
            $valor_antes_de_ico = "";
            $impuesto_al_consumo = "";
            $ico = "";
            $iva = "";
            $precio_unitario = "";
            $productos = array();
            if ($tipo_pago == 1) {
                $productos = model('productoPedidoModel')->where('numero_de_pedido', $numero_pedido['id'])->find(); // Tipo de pago 1 = pago completo; los productos salen de la tabla productoPedido 
            }

            if ($tipo_pago == 0) {
                $productos = model('partirFacturaModel')->get_productos_pago_parcial($numero_pedido['id']); // Tipo de pago 0 = pago parcial; los productos salen de la tabla partirFactura 
            }

            if ($id_regimen['idregimen'] == 1) {  // Empresa con impuestos 


                if ($insert) {

                    foreach ($productos as $detalle) {
                        /**
                         * Datos del producto y se traen desde la tabla producto 
                         */

                        $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                        $costo = model('productoModel')->select('precio_costo')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                        $aplica_ico = model('productoModel')->select('aplica_ico')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();

                        if ($aplica_ico['aplica_ico'] == 't') { // El producto tiene IMPUESTO DE BARES Y RESTAURANTES   


                            $id_ico_producto = model('productoModel')->select('id_ico_producto')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                            $porcentaje_ico = model('icoConsumoModel')->select('valor_ico')->where('id_ico', $id_ico_producto)->first();
                            $valor_ico = ($porcentaje_ico['valor_ico'] / 100) + 1;
                            $valor_antes_de_ico = $detalle['valor_unitario'] / $valor_ico;
                            $precio_unitario = $valor_antes_de_ico;
                            $ico = $porcentaje_ico['valor_ico'];
                            $iva = 0;

                            $insertar = model('itemFacturaElectronicaModel')->set_item_factura($id_factura, $detalle['codigointernoproducto'], $nombre_producto, $detalle['cantidad_producto'], $costo, $iva, $ico, $precio_unitario, $detalle['valor_unitario'], $numero_pedido);
                            //$id_factura, $codigo_interno, $nombre_producto, $cantidad, $costo, $iva, $ico, $precio_unitario, $total


                        } else if ($aplica_ico['aplica_ico'] == 'f') {  // El producto no tiene INC pero hay que calcularle el IVA 

                            $id_iva = model('productoModel')->select('idiva')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                            $porcentaje_iva = model('ivaModel')->select('valoriva')->where('idiva ', $id_iva['idiva'])->first();

                            $valor_porcentaje_iva = ($porcentaje_iva['valoriva'] / 100) + 1;
                            $valor_antes_de_iva  = $detalle['valor_unitario'] / $valor_porcentaje_iva;
                            $precio_unitario = $detalle['valor_unitario'] - $valor_antes_de_iva;
                            $ico = 0;
                            $iva = $porcentaje_iva['valoriva'];
                            $insertar = model('itemFacturaElectronicaModel')->set_item_factura($id_factura, $detalle['codigointernoproducto'], $nombre_producto, $detalle['cantidad_producto'], $costo, $iva, $ico, $valor_antes_de_iva, $detalle['valor_unitario'], $numero_pedido);
                        }



                        $calculo = $impuestos->calcular_impuestos($detalle['codigointernoproducto'], $detalle['valor_total'], $detalle['valor_unitario'], $detalle['cantidad_producto']);

                        $codigo_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                        $id_pedido = model('kardexModel')->select('id_pedido')->where('id_pedido', $detalle['id'])->first();
                        $id_tipo_inventario = model('productoModel')->select('id_tipo_inventario')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                        $actualizar_inventario = $inventario->actualizar_inventario($detalle['codigointernoproducto'], $id_tipo_inventario['id_tipo_inventario'], $detalle['cantidad_producto'], 'documento_electronico', $id_fact);
                        //if (empty($id_pedido['id_pedido'])) {

                        $cantidad_inventario = model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                        $inventario_final = $cantidad_inventario['cantidad_inventario'] - $detalle['cantidad_producto'];
                        $data = [
                            'idcompra' => 0,
                            'codigo' => $detalle['codigointernoproducto'],
                            'idusuario' => $id_usuario,
                            'idconcepto' => 10,
                            'numerodocumento' => $id_factura,
                            'fecha' => date('Y-m-d'),
                            'hora' => date('H:i:s'),
                            'cantidad' => $detalle['cantidad_producto'],
                            'valor' => $detalle['valor_unitario'],
                            'total' => $detalle['valor_total'],
                            'fecha_y_hora_factura_venta' => $fecha_y_hora,
                            'id_categoria' => $codigo_categoria['codigocategoria'],
                            'id_apertura' => $apertura['numero'],
                            'valor_unitario' => $detalle['valor_unitario'],
                            'id_factura' => $id_factura,
                            'costo' => round($costo['precio_costo'] * $detalle['cantidad_producto']),
                            'ico' => $calculo[0]['ico'],
                            'iva' => $calculo[0]['iva'],
                            'id_estado' => 8,
                            'valor_ico' => $calculo[0]['valor_ico'],
                            'valor_iva' => $calculo[0]['valor_iva'],
                            'aplica_ico' => $calculo[0]['aplica_ico'],
                            //'id_pedido'=>$numero_pedido
                            'saldo_anterior' => $cantidad_inventario,
                            'nuevo_saldo' => $inventario_final
                            //'nuevo_saldo' => 12
                        ];

                        $insertar = model('kardexModel')->insert($data);
                    }
                }
            } else if (($id_regimen['idregimen'] == 2)) {  //Empresa no responsabel de impuestos 

                $valor_antes_de_ico = 0;
                $impuesto_al_consumo = 0;
                $ico = 0;
                $iva = 0;

                foreach ($productos as $detalle) {
                    $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                    $codigo_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                    $costo = model('productoModel')->select('precio_costo')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();


                    $id_pedido = model('kardexModel')->select('id_pedido')->where('id_pedido', $detalle['id'])->first();

                    $id_tipo_inventario = model('productoModel')->select('id_tipo_inventario')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();

                    $actualizar_inventario = $inventario->actualizar_inventario($detalle['codigointernoproducto'], $id_tipo_inventario['id_tipo_inventario'], $detalle['cantidad_producto'], 'documento_electronico', $id_fact);

                    $insertar = model('itemFacturaElectronicaModel')->set_item_factura($id_factura, $detalle['codigointernoproducto'], $nombre_producto, $detalle['cantidad_producto'], $costo, $iva, $ico, $detalle['valor_unitario'], $detalle['valor_unitario']);

                    $cantidad_inventario = model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                    $inventario_final = $cantidad_inventario['cantidad_inventario'] - $detalle['cantidad_producto'];

                    $data = [
                        'idcompra' => 0,
                        'codigo' => $detalle['codigointernoproducto'],
                        'idusuario' => $id_usuario,
                        'idconcepto' => 10,
                        'numerodocumento' => $id_factura,
                        'fecha' => date('Y-m-d'),
                        'hora' => date('H:i:s'),
                        'cantidad' => $detalle['cantidad_producto'],
                        'valor' => $detalle['valor_unitario'],
                        'total' => $detalle['valor_total'],
                        'fecha_y_hora_factura_venta' => $fecha_y_hora,
                        'id_categoria' => $codigo_categoria['codigocategoria'],
                        'id_apertura' => $apertura['numero'],
                        'valor_unitario' => $detalle['valor_unitario'],
                        'id_factura' => $id_factura,
                        'costo' => $costo['precio_costo'] * $detalle['cantidad_producto'],
                        'ico' => 0,
                        'iva' => 0,
                        'id_estado' => 8,
                        'valor_ico' => 0,
                        'valor_iva' => 0,
                        'aplica_ico' => 'false',
                        'saldo_anterior' => $cantidad_inventario,
                        'nuevo_saldo' => $inventario_final

                    ];


                    $insertar = model('kardexModel')->insert($data);
                }
            }
            if ($insert) {
                /**
                 * Borrar los productos del pedido 
                 */


                $id_apertura = model('aperturaRegistroModel')->select('numero')->first();


                $valor_pago_efectivo = 0; // Inicializa el valor de pago en efectivo en 0
                $valor_pago_transferencia = 0; // Inicializa el valor de pago en transferencia en 0

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

                //$id_pedidos = model('pagosModel')->select('id_pedido')->where('id_pedido', $numero_pedido)->first();

                // if (empty($id_pedidos['id_pedido'])) {

                $pagos = [

                    'fecha' => date('Y-m-d'),
                    'hora' => date("H:i:s"),
                    'documento' => $id_factura,
                    //'valor' => $valor_total['valor_total']-$propina,
                    'valor' => $valor_total,
                    'propina' => $propina,
                    'total_documento' => $valor_venta,
                    //'total_documento' => $valor_total['valor_total'],
                    'efectivo' => $valor_pago_efectivo,
                    'transferencia' => $valor_pago_transferencia,
                    'total_pago' => $efectivo + $transaccion,
                    'id_usuario_facturacion' => $id_usuario,
                    'id_mesero' => $id_usuario,
                    'id_estado' => $estado,
                    'id_apertura' => $id_apertura['numero'],
                    'cambio' => $cambio,
                    'recibido_efectivo' => $recibido_efectivo,
                    'recibido_transferencia' => $recibido_transaccion,
                    'id_factura' => $id_factura,
                    'saldo' => $saldo,
                    'nit_cliente' => $nit_cliente,
                    'id_pedido' => $numero_pedido
                ];

                $pagos = model('pagosModel')->insert($pagos);
                //}

                if ($tipo_pago == 1) {

                    $forma_pago_efectivo = [
                        'id_de' => $id_factura,
                        'id_user' => $id_usuario,
                        'id_caja' => 1,
                        'code_payment' => 10,
                        'fecha' => date('Y-m-d'),
                        'hora' => date('H:i:s'),
                        'valor' =>  $efectivo,
                        'pago' => $valor_venta
                    ];

                    $forma_pago_transaccion = [
                        'id_de' => $id_factura,
                        'id_user' => $id_usuario,
                        'id_caja' => 1,
                        'code_payment' => 31,
                        'fecha' => date('Y-m-d'),
                        'hora' => date('H:i:s'),
                        'valor' => $transaccion,
                        'pago' => $valor_venta
                    ];

                    if ($efectivo > 0) {
                        $insert_efectivo = model('FacturaElectronicaformaPago')->insert($forma_pago_efectivo);
                    }
                    if ($transaccion > 0) {
                        $insert_transaccion = model('FacturaElectronicaformaPago')->insert($forma_pago_transaccion);
                    }
                    $borrar_producto_pedido = model('productoPedidoModel')->where('numero_de_pedido', $numero_pedido['id']);
                    $borrar_producto_pedido->delete();

                    $borrar_producto_pedido = model('pedidoModel')->where('id', $numero_pedido['id']);
                    $borrar_producto_pedido->delete();

                    /**
                     * Borrar el pedido 
                     */


                    $mesas = model('mesasModel')->where('estado', 0)->orderBy('id', 'ASC')->findAll();
                    $categorias = model('categoriasModel')->where('permitir_categoria', 'true')->findAll();

                    $total_ventas_electronicas = model('pagosModel')->get_total_ventas_electronicas($id_apertura['numero']);


                    if ($imprime_boucher['imp_comprobante_transferencia'] == 1) {

                        $idUlt = model('pagosModel')->insertID;

                        $movimientos_transaccion = model('pagosModel')->pago_transferencia($idUlt);
                        $movimientos_efectivo = model('pagosModel')->pago_efectivo($idUlt);

                        if (empty($movimientos_efectivo[0]['recibido_efectivo'])) {
                            $efectivo = 0;
                            $total_efectivo = 0;
                        } else if (empty($movimientos_efectivo[0]['recibido_efectivo'])) {
                            $efectivo = $movimientos_efectivo[0]['recibido_efectivo'];
                            $total_efectivo = $movimientos_efectivo[0]['recibido_efectivo'];
                        }



                        /*    if (!empty($movimientos_transaccion)) {
                            if ($movimientos_transaccion[0]['recibido_transferencia'] > 0) {
                                $imp = new impresion();

                                if (empty($movimientos_efectivo[0]['total_pago'])) {
                                    $total_efectivo = 0;
                                    $efectivo = 0;
                                } else if (empty($movimientos_efectivo[0]['total_pago'])) {
                                    $total_efectivo = $movimientos_efectivo[0]['total_pago'];
                                    $efectivo = $total_efectivo;
                                }

                                $imprimir = $imp->imprimir_comprobnate_transferencia($idUlt, $movimientos_transaccion[0]['recibido_transferencia'], $efectivo, $total_efectivo);
                            }
                        } */

                        if (!empty($movimientos_transaccion)) {
                            if ($movimientos_transaccion[0]['recibido_transferencia'] > 0) {
                                $imp = new impresion();

                                // Inicializamos variables para evitar errores
                                $total_efectivo = 0;
                                $efectivo = 0;

                                // Verificar si existe 'total_pago' en 'movimientos_efectivo'
                                if (!empty($movimientos_efectivo[0]['total_pago'])) {
                                    $total_efectivo = $movimientos_efectivo[0]['total_pago'];
                                    $efectivo = $total_efectivo;
                                }

                                // Llamada al método imprimir
                                $imprimir = $imp->imprimir_comprobnate_transferencia(
                                    $idUlt,
                                    $movimientos_transaccion[0]['recibido_transferencia'],
                                    $efectivo,
                                    $total_efectivo
                                );
                            }
                        }
                    }


                    $returnData = array(
                        "resultado" => 1, //Falta plata
                        "total" => "$ " . number_format($valor_venta, 0, ",", "."),
                        "valor_pago" => "$ " . number_format($pago_total, 0, ",", "."),
                        "cambio" => "$ " . number_format($pago_total - $valor_venta, 0, ",", "."),
                        "mensaje" => "",
                        "mesas" => view('pedidos/todas_las_mesas_lista', [
                            "mesas" => $mesas,
                        ]),
                        "categorias" => view('pedidos/categorias', [
                            'categorias' => $categorias
                        ]),
                        "id_factura" => $id_factura,
                        "documentos" => view('pedidos/documento'),
                        'ventas_electronicas' => "$ " . number_format($total_ventas_electronicas[0]['total_electronicas'], 0, ",", ".")
                    );
                    echo  json_encode($returnData);
                }

                if ($tipo_pago == 0) {  //pagos parcial 


                    $borrar_producto_pedido = model('partirFacturaModel')->where('cantidad_producto', 0);
                    $borrar_producto_pedido->delete();




                    foreach ($productos as $detalle) {


                        $cantidad_producto_pedido = model('productoPedidoModel')->select('cantidad_producto')->where('id', $detalle['id_tabla_producto'])->first();


                        $cantidad_producto_pago_parcial = model('partirFacturaModel')->select('cantidad_producto')->where('id_tabla_producto', $detalle['id_tabla_producto'])->first();


                        $cantidad_final = $cantidad_producto_pedido['cantidad_producto'] - $cantidad_producto_pago_parcial['cantidad_producto'];

                        $cantidad_producto_pedido['cantidad_producto'] . "</br>";
                        $cantidad_producto_pago_parcial['cantidad_producto'] . "</br>";



                        if ($cantidad_final == 0) {
                            $borrar_producto_pedido = model('productoPedidoModel')->where('id', $detalle['id_tabla_producto']);
                            $borrar_producto_pedido->delete();
                        }
                        if ($cantidad_final > 0) {

                            $valor_unitario = model('productoPedidoModel')->select('valor_unitario')->where('id', $detalle['id_tabla_producto'])->first();
                            $data = [
                                'valor_total' => $valor_unitario['valor_unitario'] * $cantidad_final,
                                'cantidad_producto' => $cantidad_final
                            ];

                            $model = model('productoPedidoModel');
                            $actualizar_cantidad = $model->set($data);
                            $actualizar_cantidad = $model->where('id', $detalle['id_tabla_producto']);
                            $actualizar_cantidad = $model->update();
                        }
                    }

                    $producto_pedido = model('productoPedidoModel')->select('id')->where('numero_de_pedido', $numero_pedido['id'])->findAll();
                    if (empty($producto_pedido)) {
                        $borrar_pedido = model('pedidoModel')->where('id', $numero_pedido['id']);
                        $borrar_pedido->delete();
                    }

                    $borrar_producto_pedido = model('partirFacturaModel')->where('numero_de_pedido', $numero_pedido['id']);
                    $borrar_producto_pedido->delete();

                    $valor_total = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['id'])->findAll();
                    $valor_pedido = [
                        'valor_total' => $valor_total[0]['valor_total']
                    ];
                    $model = model('pedidoModel');
                    $actualizar_pedio = $model->set($valor_pedido);
                    $actualizar_pedio = $model->where('id', $numero_pedido['id']);
                    $actualizar_pedio = $model->update();

                    $forma_pago_efectivo = [
                        'id_de' => $id_factura,
                        'id_user' => $id_usuario,
                        'id_caja' => 1,
                        'code_payment' => 10,
                        'fecha' => date('Y-m-d'),
                        'hora' => date('H:i:s'),
                        'valor' => $valor_venta,
                        'pago' => $efectivo
                    ];

                    $forma_pago_transaccion = [
                        'id_de' => $id_factura,
                        'id_user' => $id_usuario,
                        'id_caja' => 1,
                        'code_payment' => 31,
                        'fecha' => date('Y-m-d'),
                        'hora' => date('H:i:s'),
                        'valor' => $valor_venta,
                        'pago' => $transaccion
                    ];

                    if ($efectivo > 0) {
                        $insert_efectivo = model('FacturaElectronicaformaPago')->insert($forma_pago_efectivo);
                    }

                    if ($transaccion > 0) {
                        $insert_transaccion = model('FacturaElectronicaformaPago')->insert($forma_pago_transaccion);
                    }
                    $mesas = model('mesasModel')->orderBy('id', 'ASC')->findAll();
                    $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['id']);


                    $valor_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido['id'])->first();
                    $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa)->first();

                    $model = model('partirFacturaModel');
                    $borrar = $model->truncate();

                    $total_ventas_electronicas = model('pagosModel')->get_total_ventas_electronicas($id_apertura['numero']);

                    if ($imprime_boucher['imp_comprobante_transferencia'] == 1) {

                        $idUlt = model('pagosModel')->insertID;

                        $movimientos_transaccion = model('pagosModel')->pago_transferencia($idUlt);
                        $movimientos_efectivo = model('pagosModel')->pago_efectivo($idUlt);


                        if (!empty($movimientos_transaccion)) {
                            $imp = new impresion();
                            $imprimir = $imp->imprimir_comprobnate_transferencia($idUlt, $movimientos_transaccion[0]['recibido_transferencia'], $movimientos_efectivo[0]['recibido_efectivo'], $movimientos_efectivo[0]['total_pago']);
                        }
                    }


                    $returnData = array(
                        "id_factura" => $id_factura,
                        "resultado" => 1,
                        "total" => "$ " . number_format($valor_venta, 0, ",", "."),
                        "valor_pago" => "$ " . number_format($efectivo + $transaccion, 0, ",", "."),
                        "cambio" => "$ " . number_format(($efectivo + $transaccion) - $valor_venta, 0, ",", "."),
                        "mesas" => view('pedidos/todas_las_mesas_lista', [
                            "mesas" => $mesas,
                        ]),
                        "mensaje" => "",
                        "productos" => view('pedidos/productos_pedido', [
                            "productos" => $productos_pedido,
                            "pedido" => $numero_pedido
                        ]),
                        "tipo_pago" => 0,
                        "valor_pedio" => "$ " . number_format($valor_pedido['valor_total'], 0, ",", "."),
                        "id_mesa" => $id_mesa,
                        "pedido" => $numero_pedido['id'],
                        "nombre_mesa" => $nombre_mesa['nombre'],
                        "documentos" => view('pedidos/documento'),
                        'ventas_electronicas' => "$ " . number_format($total_ventas_electronicas[0]['total_electronicas'], 0, ",", ".")
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
}
