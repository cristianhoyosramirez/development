<?php

namespace App\Controllers\pedido;

require APPPATH . "Controllers/mike42/autoload.php";

use App\Controllers\BaseController;

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use \DateTime;
use \DateTimeZone;

class pedidoController extends BaseController

{
    public function index()
    {

        return view('pedido/pedidos_para_facturar');
    }

    public function pedidos_para_facturacion()
    {
        $todos_los_pedidos = model('pedidoModel')->todosLospedidos();


        $test = view('pedido/pedidos_para_facturar_tbody', [
            "pedidos" => $todos_los_pedidos
        ]);


        $returnData = array(
            "pedidos" => $test,
            "Resultado" => 1
        );
        echo  json_encode($returnData);
    }
    /**
     * Controlador de eliminacion de pedido 
     * 1. borrar todos los productos del pedido de la tabla producto_pedio por numero de pedido 
     * 2.borrar el pedido por id($numero_pedido)
     * 2.Actualizar el estado de la mesa 
     */
    public function eliminacion_de_pedido()
    {
        $numero_pedido = $_POST['numero_pedido'];

        $id_usuario = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();

        // $permiso_eliminar = model('tipoPermisoModel')->tipo_permiso($id_usuario['fk_usuario']);

        // if (!empty($permiso_eliminar['idusuario_sistema'])) {

        $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();


        $valor_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();

        $fecha_creacion = model('pedidoModel')->select('fecha_creacion')->where('id', $numero_pedido)->first();
        $pedido_borrado = [
            'numero_pedido' => $numero_pedido,
            'valor_pedido' => $valor_pedido['valor_total'],
            'fecha_eliminacion' =>  date("Y-m-d"),
            'hora_eliminacion' => date('H:i:s'),
            'fecha_creacion' => $fecha_creacion['fecha_creacion'],
            'usuario_eliminacion' => $id_usuario['fk_usuario'],
            //'usuario_elimininacion' => $id_usuario['idusuario_sistema']
        ];

        $insert = model('eliminacionPedidosModel')->insert($pedido_borrado);


        $model = model('productoPedidoModel');
        $borrarPedido = $model->where('numero_de_pedido', $numero_pedido);
        $borrarPedido = $model->delete();

        $model = model('pedidoModel');
        $borrar = $model->where('id', $numero_pedido);
        $borrar = $model->delete();

        if ($borrarPedido && $borrar) {
            $data = [
                'estado' => 0,
                'valor_pedido' => 0,
                'fk_usuario' => $id_usuario['fk_usuario']
            ];

            $model = model('mesasModel');
            $numero_factura = $model->set($data);
            $numero_factura = $model->where('id', $id_mesa['fk_mesa']);
            $numero_factura = $model->update();
            echo 1;
        } else {
            echo 0;
        }
        //} 
        /* else {
            echo 0;
        } */
    }

    public function agregar_nota_al_pedido()
    {
        //$nota_pedido = $_POST['nota_pedido'];
        $numero_pedido = $_POST['numero_pedido'];
        if ($numero_pedido == "") {
            $returnData = array(
                "resultado" => 0, //No hay pedido   
            );
            echo  json_encode($returnData);
        } else {
            $tiene_nota_pedido = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido)->first();



            if ($tiene_nota_pedido['nota_pedido'] == null) {
                $returnData = array(
                    "resultado" => 1, //Hay numero de pedido  
                );
                echo  json_encode($returnData);
            }
            if (!empty($tiene_nota_pedido['nota_pedido'])) {
                $returnData = array(
                    "resultado" => 2, //Hay numero de pedido
                    "nota_pedido" => $tiene_nota_pedido['nota_pedido']
                );
                echo  json_encode($returnData);
            }
        }
    }

    public function facturar_pedido()
    {
        $apertura = model('aperturaRegistroModel')->select('idcaja')->first();

        if (!empty($apertura['idcaja'])) {
            $numero_pedido = $_POST['numero_de_pedido_facturar'];

            $estado = model('estadoModel')->orderBy('idestado', 'ASC')->findAll();
            $regimen = model('regimenModel')->select('*')->find();
            $productos_del_pedido_para_facturar = model('productoPedidoModel')->productos_del_pedido_para_facturar($numero_pedido);
            $total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();
            $totalizado = $total['valor_total'];

            $tipo_cliente = model('tipoClienteModel')->select('*')->find();
            $clasificacion_cliente = model('clasificacionClienteModel')->select('*')->find();
            $departamento = model('departamentoModel')->select('*')->where('idpais', 49)->find();
            $id_cliente_general = model('clientesModel')->select('id')->where('nitcliente', '22222222')->first();
            $id_regimen_no_responsable_iva = model('empresaModel')->select('idregimen')->first();
            $id_departamento_empresa = model('empresaModel')->select('iddepartamento')->first();
            $id_ciudad_empresa = model('empresaModel')->select('idciudad')->first();
            $ciudad = model('municipiosModel')->select('nombreciudad')->where('idciudad', $id_ciudad_empresa['idciudad'])->first();

            return view('facturar_pedido/facturar_pedido', [
                "id_cliente_general" => $id_cliente_general['id'],
                "estado" => $estado,
                "regimen" => $regimen,
                "pedido" => $numero_pedido,
                "productos" => $productos_del_pedido_para_facturar,
                "total_hidden" => $totalizado,
                "total" => $totalizado,
                "numero_de_pedido" => $numero_pedido,
                "tipo_cliente" => $tipo_cliente,
                "clasificacion_cliente" => $clasificacion_cliente,
                "departamentos" => $departamento,
                "id_regimen" => $id_regimen_no_responsable_iva['idregimen'],
                "id_ciudad" => $id_ciudad_empresa['idciudad'],
                "ciudad" => $ciudad['nombreciudad'],
                "id_departamento" => $id_departamento_empresa['iddepartamento']
            ]);
        } elseif (empty($apertura['idcaja'])) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'error');
            return redirect()->to(base_url('pedido/pedidos_para_facturar'))->with('mensaje', 'No hay apertura de caja ');
        }
    }

    public function cerrar_venta()
    {


        /*  $numero_pedido = 3;
        $efectivo = 200000;
        $transaccion = 200000;
        $valor_venta = 400000;
        $nit_cliente = 22222222;
        $id_usuario = 6;
        $estado = 1;
        $descuento = 0;
        $propina = 0;  */
        //$total_pagado = 300000;


        /**
         * Datos de formulario cerrar venta 
         */
        $numero_pedido = $_POST['numero_de_pedido'];
        $efectivo = $_POST['efectivo'];
        $transaccion = $_POST['transaccion'];
        $valor_venta = $_POST['valor_venta'];
        $nit_cliente = $_POST['nit_cliente'];
        $id_usuario = $_POST['id_usuario'];
        $estado = $_POST['estado'];
        $descuento = $_POST['descuento'];
        $propina = $_POST['propina'];
        $producto_factura_venta = array();

        /**
         * Datos de encabezado de factura venta 
         */
        $id_dian = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', '6')->first();
        $factura = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', '8')->first();
        $alerta = model('dianModel')->select('alerta_facturacion')->where('iddian', $id_dian['numeroconsecutivo'])->first();
        $rango_final = model('dianModel')->select('rangofinaldian')->where('iddian', $id_dian['numeroconsecutivo'])->first();
        $prefijo_factura = model('dianModel')->select('inicialestatica')->where('iddian', $id_dian['numeroconsecutivo'])->first();

        $serie = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', '14')->first();

        $resultado = "";
        $num_facturas = "";
        $result = $rango_final['rangofinaldian'] - $factura['numeroconsecutivo'];

        if ($result <= $alerta['alerta_facturacion']) {
            $resultado = 0;
        }
        if ($result > $alerta['alerta_facturacion']) {
            $resultado = 1;
        }

        $total_pagado = $efectivo + $transaccion;
        $fecha = DateTime::createFromFormat('U.u', microtime(TRUE));
        $fecha->setTimeZone(new DateTimeZone('America/Bogota'));
        $fecha_y_hora = $fecha->format('Y-m-d H:i:s.u');

        if ($total_pagado >= $valor_venta) {

            $saldo = '';
            $fecha = date("Y-m-d ");
            $hora = date("H:i:s");
            /**
             * Datos del pedido
             */
            $fk_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
            $valor_total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();
            $observaciones_genereles = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido)->first();
            $fk_usuario_mesero = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();

            if ($estado == 1 or $estado == 7) {
                $saldo = 0;
            }
            if ($estado == 2 or $estado == 6) {
                $saldo = $valor_total['valor_total'];
            }

            $serie_update  = $serie['numeroconsecutivo'] + 1;
            $incremento = model('consecutivosModel')->update_serie($serie_update);

            /**
             * Guardar datos en la tabla factura venta 
             */
            $factura_venta = model('facturaVentaModel')->factura_venta(
                $prefijo_factura['inicialestatica'] . "-" . $factura['numeroconsecutivo'],
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
                $fk_mesa,
                $numero_pedido,
                $fecha_y_hora,
                $descuento,
                $propina
            );

            $numero_facturaUpdate = [
                'numeroconsecutivo' => $factura['numeroconsecutivo'] + 1
            ];

            $update_numero_factura = [
                'numero_factura' => $prefijo_factura['inicialestatica'] . "-" . $factura['numeroconsecutivo']
            ];

            $num_fact = model('pedidoModel');
            $numero_factura = $num_fact->set($update_numero_factura);
            $numero_factura = $num_fact->where('id', $numero_pedido);
            $numero_factura = $num_fact->update();
            //Actualizacion de la tabla consecutivos 
            $model = model('consecutivosModel');
            $numero_factura = $model->set($numero_facturaUpdate);
            $numero_factura = $model->where('idconsecutivos', 8);
            $numero_factura = $model->update();
            //Registros de los productos que estan la tabla producto_pedido y que vamos a facturar 
            $productos = model('productoPedidoModel')->where('numero_de_pedido', $numero_pedido)->find();

            foreach ($productos as $detalle) {
                /* Datos del producto */
                $valor_venta_producto = model('productoModel')->select('valorventaproducto')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                $codigo_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                $id_ico_producto = model('productoModel')->select('id_ico_producto')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                $id_tipo_inventario = model('productoModel')->select('id_tipo_inventario')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                $aplica_ico = model('productoModel')->select('aplica_ico')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();


                /* Calcular el impuesto al consumo*/
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
                $total = model('productoPedidoModel')->select('valor_total')->where('id', $detalle['id'])->first();

                $precio_costo = model('productoModel')->select('precio_costo')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                $id_factura = $factura_venta;

                $numero_factura = model('pedidoModel')->select('numero_factura')->where('id', $numero_pedido)->first();

                /**
                 * Consultar el tipo de inventario y descontarlo
                 */

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

                        $data = [
                            'cantidad_inventario' => $cantidad_inventario['cantidad_inventario'] - $descontar_de_inventario,

                        ];

                        $model = model('inventarioModel');
                        $actualizar = $model->set($data);
                        $actualizar = $model->where('codigointernoproducto', $detall['prod_proceso']);
                        $actualizar = $model->update();
                    }
                }
                /* Impuesto iva */
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

                $numero_factura = model('pedidoModel')->select('numero_factura')->where('id', $numero_pedido)->first();

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

                        $data = [
                            'cantidad_inventario' => $cantidad_inventario['cantidad_inventario'] - $descontar_de_inventario,

                        ];

                        $model = model('inventarioModel');
                        $actualizar = $model->set($data);
                        $actualizar = $model->where('codigointernoproducto', $detall['prod_proceso']);
                        $actualizar = $model->update();
                    }
                }
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
                    'id_factura' => $factura_venta,
                    'valor_venta_real' =>  $valor_venta_real,
                    'impoconsumo' => 0,
                    'total' => $total['valor_total'],
                    'valor_ico' => $valor_imco, //
                    'impuesto_al_consumo' => $impuesto_al_consumo,
                    'iva' => $iva,
                    'id_iva' => $id_iva['idiva'],
                    'aplica_ico' => $aplica_ico['aplica_ico'],
                    'valor_total_producto' => $detalle['valor_unitario'],
                    'fecha_y_hora_venta' => date("Y-m-d H:i:s"),
                    'saldo' => 0,
                    'fecha_y_hora_venta' => $fecha_y_hora,
                    'fecha_venta' => date('Y-m-d'),
                    'id_categoria' => $codigo_categoria['codigocategoria']
                ];

                $insertar = model('productoFacturaVentaModel')->insert($producto_factura_venta);
            }

            $numero_factura = model('pedidoModel')->select('numero_factura')->where('id', $numero_pedido)->first();

            if ($efectivo == 0 and $transaccion > 0) {
                $pago_transaccion = $valor_venta;
                $pago_efectivo = 0;
            }
            if ($efectivo > 0 and $transaccion == 0) {
                $pago_transaccion = 0;
                $pago_efectivo = $valor_venta;
            }

            if ($efectivo > 0 and $transaccion > 0) {
                $temp = $valor_venta - $efectivo;
                $pago_transaccion = $temp;
                $pago_efectivo = $efectivo;
            }

            $forma_pago_efectivo = model('facturaFormaPagoModel')->factura_forma_pago(
                $prefijo_factura['inicialestatica'] . "-" . $numero_factura['numero_factura'], //Numero de factura 
                $id_usuario, // id de l usuario
                1, // id_forma_pago 1 = efectivo 
                $fecha,
                $hora,
                $pago_efectivo,
                $efectivo, // con cuanto pagan en efectivo la factura 
                $factura_venta, // id de la factura 
                $fecha_y_hora
            );

            $forma_pago_transaccion = model('facturaFormaPagoModel')->factura_forma_pago(
                $prefijo_factura['inicialestatica'] . "-" . $numero_factura['numero_factura'], //Numero de factura 
                $id_usuario, // id de l usuario
                4, // id_forma_pago 1 = efectivo 
                $fecha,
                $hora,
                $pago_transaccion,
                $transaccion, // con cuanto pagan en efectivo la factura 
                $factura_venta, // id de la factura 
                $fecha_y_hora
            );


            $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
            $id_usuario = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();




            $borrar_producto_pedido = model('productoPedidoModel')->where('numero_de_pedido', $numero_pedido);
            $borrar_producto_pedido->delete();


            $cantidad_iva = model('productoFacturaVentaModel')->impuestos($factura_venta);

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


            $total = model('productoFacturaVentaModel')->selectSum('total')->where('id_factura', $factura_venta)->find();
            $valorfactura_forma_pago = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();

            $id_regimen = model('regimenModel')->select('idregimen')->first();

            $returnData = array(
                "id_factura" => $factura_venta,
                "resultado" => 1,
                "efectivo" => number_format($efectivo, 0, ",", "."),
                "cambio" => number_format($efectivo - (($valorfactura_forma_pago['valor_total'] - $descuento) + $propina), 0, ",", "."),
                "total" =>  number_format($valorfactura_forma_pago['valor_total'], 0, ",", "."),
                "id_regimen" =>  $id_regimen['idregimen'],
                "Sub_total" => number_format($sub_totales, 0, ",", "."),
                "iva" => number_format($total_iva, 0, ",", "."),
                "impuesto_al_consumo" => number_format($total_ico, 0, ",", "."),
                "total" => ($total[0]['total'] - $descuento) + $propina,
                "result" => $resultado,
                "facturas_restantes" => number_format($result, 0, ",", "."),
                "descuento" => $descuento,
                "propina" => $propina
            );
            $borrar_pedido = model('pedidoModel')->where('id', $numero_pedido);
            $borrar_pedido->delete();
            echo  json_encode($returnData);
            $connector = new WindowsPrintConnector('FACTURACION');
            $printer = new Printer($connector);
            $printer->pulse();
            $printer->close();
        } else if ($valor_venta > $total_pagado) {

            $returnData = array(
                "resultado" => 0, //Falta plata  
            );
            echo  json_encode($returnData);
        }
    }


    public function nota_de_pedido()
    {
        $numero_pedido = $_POST['numero_pedido'];
        $nota_pedido = $_POST['nota_de_pedido'];
        $data = [
            "nota_pedido" => $nota_pedido
        ];

        $num_fact = model('pedidoModel');
        $numero_factura = $num_fact->set($data);
        $numero_factura = $num_fact->where('id', $numero_pedido);
        $numero_factura = $num_fact->update();

        if ($numero_factura) {
            $returnData = array(
                "resultado" => 1,
                "nota" => $nota_pedido
            );
            echo  json_encode($returnData);
        }
    }

    public function valor_pedido()
    {
        $apertura_caja = model('aperturaRegistroModel')->select('numero')->first();

        if (!empty($apertura_caja)) {
            $usuario = $_POST['usuario'];
            $valor_total = model('pedidoPosModel')->select('valor_total')->where('fk_usuario', $usuario)->first();
            if (empty($valor_total['valor_total'])) {
                $returnData = array(
                    "resultado" => 3,

                );
                echo  json_encode($returnData);
            } else {
                $pk_pedido_pos = model('pedidoPosModel')->select('id')->where('fk_usuario', $usuario)->first();



                $regimen = model('empresaModel')->select('idregimen')->first();
                if ($regimen['idregimen'] == 1) { //Responsable de iva 


                    $codigo_interno_producto = model('productoPedidoPosModel')->codigo_id($pk_pedido_pos['id']);
                    foreach ($codigo_interno_producto as $codigointernoproducto) {
                        $aplica_ico = model('productoModel')->select('aplica_ico')->where('codigointernoproducto', $codigointernoproducto['codigointernoproducto'])->first();

                        if ($aplica_ico['aplica_ico'] == 't') {

                            $base_impo_consumo = model('pedidoPosModel')->select('base_ico')->where('id', $pk_pedido_pos['id'])->first();

                            $id_ico_producto = model('productoModel')->select('id_ico_producto')->where('codigointernoproducto', $codigointernoproducto['codigointernoproducto'])->first();
                            $valo_ico = model('icoConsumoModel')->select('valor_ico')->where('id_ico', $id_ico_producto['id_ico_producto'])->first();



                            $ico_temp = $valo_ico['valor_ico'] / 100;
                            $ico_temporal = $ico_temp + 1;

                            $valor_total_producto = model('productoPedidoPosModel')->valor_total_producto($pk_pedido_pos['id'], $codigointernoproducto['id']);
                            $base = $valor_total_producto[0]['valor_total'] / $ico_temporal;

                            //$base_ico = $valor_total[0]['cantidad_producto'] * $base;
                            $impuesto_al_consumo = model('pedidoPosModel')->select('impuesto_ico')->where('id', $pk_pedido_pos['id'])->first();

                            $ico = $valor_total_producto[0]['valor_total'] - $base;

                            $base_impo = [
                                'base_ico' => $base + $base_impo_consumo['base_ico'],
                                'impuesto_ico' =>  $impuesto_al_consumo['impuesto_ico'] + $ico

                            ];

                            $model = model('pedidoPosModel');
                            $actualizar = $model->set($base_impo);
                            $actualizar = $model->where('id', $pk_pedido_pos['id']);
                            $actualizar = $model->update();
                        } else if ($aplica_ico['aplica_ico'] == 'f') {
                            $base_iva = model('pedidoPosModel')->select('base_iva')->where('id', $pk_pedido_pos['id'])->first();


                            $id_iva_producto = model('productoModel')->select('idiva')->where('codigointernoproducto', $codigointernoproducto['codigointernoproducto'])->first();

                            $valor_iva = model('ivaModel')->select('valoriva')->where('idiva', $id_iva_producto['idiva'])->first();

                            $iva_temp = $valor_iva['valoriva'] / 100;
                            $iva_temporal = $iva_temp + 1;

                            $valor_total_producto = model('productoPedidoPosModel')->valor_total_producto($pk_pedido_pos['id'], $codigointernoproducto['id']);


                            $base_iv = $valor_total_producto[0]['valor_total'] / $iva_temporal;


                            $impuesto_iva = model('pedidoPosModel')->select('impuesto_iva')->where('id', $pk_pedido_pos['id'])->first();
                            //$iva = $valor_total_producto[0]['valor_total'] - $impuesto_iva['impuesto_iva'];
                            $iva = $valor_total_producto[0]['valor_total'] - $base_iv;

                            $base_impuesto_al_valoragregado = [
                                'base_iva' => $base_iv + $base_iva['base_iva'],
                                'impuesto_iva' =>  $impuesto_iva['impuesto_iva'] + $iva

                            ];

                            $model = model('pedidoPosModel');
                            $actualizar = $model->set($base_impuesto_al_valoragregado);
                            $actualizar = $model->where('id', $pk_pedido_pos['id']);
                            $actualizar = $model->update();
                        }
                    } //Fin foreach 
                    $base_iva = model('pedidoPosModel')->select('base_iva')->where('id', $pk_pedido_pos['id'])->first();
                    $base_ico = model('pedidoPosModel')->select('base_ico')->where('id', $pk_pedido_pos['id'])->first();
                    $impuesto_iva = model('pedidoPosModel')->select('impuesto_iva')->where('id', $pk_pedido_pos['id'])->first();
                    $impuestos_ico = model('pedidoPosModel')->select('impuesto_ico')->where('id', $pk_pedido_pos['id'])->first();
                    $returnData = array(
                        "resultado" => 1,
                        "valor_total" => number_format($valor_total['valor_total'], 0, ",", "."),
                        "base" => number_format($base_iva['base_iva'] + $base_ico['base_ico'], 0, ",", "."),
                        "ico" => number_format($impuestos_ico['impuesto_ico'], 0, ",", "."),
                        "iva" => number_format($impuesto_iva['impuesto_iva'], 0, ",", ".")
                    );
                    echo  json_encode($returnData);
                    $base_impo = [
                        'base_iva' => 0,
                        'impuesto_iva' => 0,
                        'base_ico' => 0,
                        'impuesto_ico' =>  0,


                    ];

                    $model = model('pedidoPosModel');
                    $actualizar = $model->set($base_impo);
                    $actualizar = $model->where('id', $pk_pedido_pos['id']);
                    $actualizar = $model->update();
                } else if ($regimen['idregimen'] == 2) {
                    $returnData = array(
                        "resultado" => 2,
                        "valor_total" => number_format($valor_total['valor_total'], 0, ",", "."),
                    );
                    echo  json_encode($returnData);
                }
            }
        } else {
            $returnData = array(
                "resultado" => 4,
            );
            echo  json_encode($returnData);
        }
    }

    public function total_pedido()
    {
        $numero_pedido = $_POST['numero_pedido'];

        //$numero_pedido = 14569;

        $valor_total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();

        $regimen = model('empresaModel')->select('idregimen')->first();

        if ($regimen['idregimen'] == 1) { //Responsable de iva 

            $codigo_interno_producto = model('productoPedidoModel')->producto_pedido_imp($numero_pedido);


            foreach ($codigo_interno_producto as $codigointernoproducto) {

                $aplica_ico = model('productoModel')->select('aplica_ico')->where('codigointernoproducto', $codigointernoproducto['codigointernoproducto'])->first();



                if ($aplica_ico['aplica_ico'] == 't') {



                    $base_impo_consumo = model('pedidoModel')->select('base_ico')->where('id', $numero_pedido)->first();

                    $id_ico_producto = model('productoModel')->select('id_ico_producto')->where('codigointernoproducto', $codigointernoproducto['codigointernoproducto'])->first();
                    $valo_ico = model('icoConsumoModel')->select('valor_ico')->where('id_ico', $id_ico_producto['id_ico_producto'])->first();



                    $ico_temp = $valo_ico['valor_ico'] / 100;
                    $ico_temporal = $ico_temp + 1;

                    $valor_total_producto = model('productoPedidoModel')->valor_total($numero_pedido, $codigointernoproducto['id']);
                    $base = $valor_total_producto[0]['valor_total'] / $ico_temporal;

                    // $base_ico = $valor_total[0]['cantidad_producto'] * $base;
                    $impuesto_al_consumo = model('pedidoModel')->select('impuesto_ico')->where('id', $numero_pedido)->first();

                    $ico = $valor_total_producto[0]['valor_total'] - $base;

                    $base_impo = [
                        'base_ico' => $base + $base_impo_consumo['base_ico'],
                        'impuesto_ico' =>  $impuesto_al_consumo['impuesto_ico'] + $ico

                    ];

                    $model = model('pedidoModel');
                    $actualizar = $model->set($base_impo);
                    $actualizar = $model->where('id', $numero_pedido);
                    $actualizar = $model->update();
                } else if ($aplica_ico['aplica_ico'] == 'f') {
                    $base_iva = model('pedidoModel')->select('base_iva')->where('id', $numero_pedido)->first();

                    $id_iva_producto = model('productoModel')->select('idiva')->where('codigointernoproducto', $codigointernoproducto['codigointernoproducto'])->first();
                    $valor_iva = model('ivaModel')->select('valoriva')->where('idiva', $id_iva_producto['idiva'])->first();



                    $iva_temp = $valor_iva['valoriva'] / 100;
                    $iva_temporal = $iva_temp + 1;

                    $valor_total_producto = model('productoPedidoModel')->valor_total($numero_pedido, $codigointernoproducto['id']);
                    $base_iv = $valor_total_producto[0]['valor_total'] / $iva_temporal;


                    $impuesto_iva = model('pedidoModel')->select('impuesto_iva')->where('id', $numero_pedido)->first();
                    $iva = $valor_total_producto[0]['valor_total'] - $impuesto_iva['impuesto_iva'];
                    $iva = $valor_total_producto[0]['valor_total'] - $base_iv;

                    $base_impuesto_al_valoragregado = [
                        'base_iva' => $base_iv + $base_iva['base_iva'],
                        'impuesto_iva' =>  $impuesto_iva['impuesto_iva'] + $iva

                    ];

                    $model = model('pedidoModel');
                    $actualizar = $model->set($base_impuesto_al_valoragregado);
                    $actualizar = $model->where('id', $numero_pedido);
                    $actualizar = $model->update();
                }
            }

            $base_iva = model('pedidoModel')->select('base_iva')->where('id', $numero_pedido)->first();
            $base_ico = model('pedidoModel')->select('base_ico')->where('id', $numero_pedido)->first();
            $impuesto_iva = model('pedidoModel')->select('impuesto_iva')->where('id', $numero_pedido)->first();
            $impuestos_ico = model('pedidoModel')->select('impuesto_ico')->where('id', $numero_pedido)->first();


            $returnData = array(
                "resultado" => 1,
                "valor_total" => number_format($valor_total['valor_total'], 0, ",", "."),
                "id_regimen" => 1,
                "base" => number_format($base_iva['base_iva'] + $base_ico['base_ico'], 0, ",", "."),
                "ico" => number_format($impuestos_ico['impuesto_ico'], 0, ",", "."),
                "iva" => number_format($impuesto_iva['impuesto_iva'], 0, ",", ".")
            );
            echo  json_encode($returnData);

            $base_impo = [
                'base_iva' => 0,
                'impuesto_iva' => 0,
                'base_ico' => 0,
                'impuesto_ico' =>  0,


            ];

            $model = model('pedidoModel');
            $actualizar = $model->set($base_impo);
            $actualizar = $model->where('id', $numero_pedido);
            $actualizar = $model->update();
        } else if ($regimen['idregimen'] == 2) { //No responsables de iva 
            $returnData = array(
                "resultado" => 2,
                "valor_total" => number_format($valor_total['valor_total'], 0, ",", "."),
                "id_regimen" => 2
            );
            echo  json_encode($returnData);
        }
    }

    function forma_pago()
    {
        $id_usuario = $_REQUEST['usuario'];
        $estado = $_REQUEST['estado'];
        $valor_total = model('pedidoPosModel')->select('valor_total')->where('fk_usuario', $id_usuario)->first();
        $estado = model('estadoModel')->select('descripcionestado')->where('idestado', $estado)->first();
        $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $_REQUEST['nit_cliente'])->first();

        $returnData = array(
            "resultado" => 1,
            "valor_total" => "$" . number_format($valor_total['valor_total'], 0, ",", "."),
            "estado" => $estado['descripcionestado'],
            "nombres_clientes" => $nombre_cliente['nombrescliente']

        );
        echo  json_encode($returnData);
    }

    function facturar_credito()
    {

        $nit_cliente = $_POST['nit_cliente'];
        $id_usuario = $_POST['usuario'];
        $estado = $_POST['estado'];

        $efectivo_sin_punto = 0;
        $transaccion_sin_punto = 0;
        $valor_venta_sin_punto = 0;
        $total_pagado_sin_punto = 0;
        $valor_factura = model('pedidoPosModel')->select('valor_total')->where('fk_usuario', $id_usuario)->first();
        $saldo = $valor_factura['valor_total'];

        $id_regimen = model('empresaModel')->select('idregimen')->first();

        $fecha = DateTime::createFromFormat('U.u', microtime(TRUE));
        $fecha->setTimeZone(new DateTimeZone('America/Bogota'));
        $fecha_y_hora = $fecha->format('Y-m-d H:i:s.u');

        switch ($id_regimen['idregimen']) {
            case 1:
                $this->facturacion_con_impuestos_credito($fecha_y_hora, $saldo, $efectivo_sin_punto, $transaccion_sin_punto, $valor_venta_sin_punto, $nit_cliente, $id_usuario, $estado, $total_pagado_sin_punto);
                break;
            case 2:
                $this->facturacion_sin_impuestos_credito($fecha_y_hora, $saldo, $efectivo_sin_punto, $transaccion_sin_punto, $valor_venta_sin_punto, $nit_cliente, $id_usuario, $estado, $total_pagado_sin_punto);
                break;
        }
    }

    public function facturacion_sin_impuestos_credito($fecha_y_hora, $saldo, $efectivo_sin_punto, $transaccion_sin_punto, $valor_venta_sin_punto, $nit_cliente, $id_usuario, $estado, $total_pagado_sin_punto)
    {

        $fecha = date("Y-m-d ");
        $hora = date("H:i:s");
        $numero_factura = model('consecutivosModel')->select('numeroconsecutivo')->where('conceptoconsecutivo =', 'Factura')->first();
        $prefijo_factura = model('consecutivosModel')->select('numeroconsecutivo')->where('conceptoconsecutivo =', 'FacturaPrefijo')->first();
        $id_resolucion_dian = model('consecutivosModel')->select('numeroconsecutivo')->where('conceptoconsecutivo =', 'IdRegistroDian')->first();
        $serie = model('consecutivosModel')->select('numeroconsecutivo')->where('conceptoconsecutivo =', 'Serie')->first();

        $observaciones_genereles = model('pedidoPosModel')->select('nota_general')->where('fk_usuario', $id_usuario)->first();

        if ($prefijo_factura != 0) {
            $data = [
                'numerofactura_venta' => $numero_factura['numeroconsecutivo'],
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
                'valor_factura' => $saldo,
                'saldo' => $saldo,
                'fecha_y_hora_factura_venta' => $fecha_y_hora
            ];
        }

        if ($prefijo_factura == 0) {
            $data = [
                'numerofactura_venta' => $prefijo_factura['inicialestatica'] . $numero_factura['numeroconsecutivo'],
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
                'observaciones_generales' => $observaciones_genereles['nota_pedido'],
                'fk_usuario_mesero' => $id_usuario,
                'saldo' => $saldo,
                'valor_factura' => $saldo,
                'fecha_y_hora_factura_venta' => $fecha_y_hora
            ];
        }

        $serie_update  = $serie['numeroconsecutivo'] + 1;

        $incremento = model('consecutivosModel')->update_serie($serie_update);

        $factuta_venta = model('facturaVentaModel')->insert($data);

        $numero_facturaUpdate = [
            'numeroconsecutivo' => $numero_factura['numeroconsecutivo'] + 1
        ];

        $update_numero_factura = [
            'numero_factura' =>  $numero_factura['numeroconsecutivo']
        ];

        $num_fact = model('pedidoPosModel');
        $numero_factura = $num_fact->set($update_numero_factura);
        $numero_factura = $num_fact->where('fk_usuario', $id_usuario);
        $numero_factura = $num_fact->update();

        $model = model('consecutivosModel');
        $numero_factura = $model->set($numero_facturaUpdate);
        $numero_factura = $model->where('idconsecutivos', 8);
        $numero_factura = $model->update();

        $pk_pedido_pos = model('pedidoPosModel')->select('id')->where('fk_usuario', $id_usuario)->first();

        $productos = model('productoPedidoPosModel')->where('pk_pedido_pos', $pk_pedido_pos['id'])->find();

        foreach ($productos as $detalle) {

            $id_medida = model('productoMedidaModel')->select('idvalor_unidad_medida')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
            $numero_factura = model('pedidoPosModel')->select('numero_factura')->where('fk_usuario', $id_usuario)->first();
            $total = model('pedidoPosModel')->select('valor_total')->where('fk_usuario', $id_usuario)->first();

            $precio_costo = model('productoModel')->select('precio_costo')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
            $id_factura = model('facturaVentaModel')->where('idusuario_sistema', $id_usuario)->insertID;

            $aplica_ico = model('productoModel')->select('aplica_ico')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
            $id_iva = model('productoModel')->select('idiva')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
            $producto_factura_venta = [
                'numerofactura_venta' => $numero_factura['numero_factura'],
                'codigointernoproducto' => $detalle['codigointernoproducto'],
                'cantidadproducto_factura_venta' => $detalle['cantidad_producto'],
                'valorunitarioproducto_factura_venta' => $detalle['valor_unitario'],
                'idmedida' => $id_medida['idvalor_unidad_medida'],
                'idcolor' => 0,
                'valor_descuento' => 0, //pendiente de ajuste
                'valor_recargo' => 0,
                'valor_iva' => 0,
                'retorno' => false,
                'valor' => 0,
                'costo' => $precio_costo['precio_costo'],
                'id_factura' => $id_factura,
                'valor_venta_real' =>  $detalle['valor_total'],
                'impoconsumo' => 0,
                'total' => $detalle['valor_total'],
                'valor_ico' => 0,
                'impuesto_al_consumo' => 0,
                'iva' => 0,
                'id_iva' => 0,
                'aplica_ico' => false,
                'valor_total_producto' => $detalle['valor_unitario'],
                //'fecha_y_hora_venta' => date("Y-m-d H:i:s"),
                //'fecha_y_hora_venta' => $fecha_y_hora,
                'fecha_venta' => date('Y-m-d'),

            ];

            $insertar = model('productoFacturaVentaModel')->insert($producto_factura_venta);
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

                    $data = [
                        'cantidad_inventario' => $cantidad_inventario['cantidad_inventario'] - $descontar_de_inventario,
                    ];

                    $model = model('inventarioModel');
                    $actualizar = $model->set($data);
                    $actualizar = $model->where('codigointernoproducto', $detall['prod_proceso']);
                    $actualizar = $model->update();
                }
            }
        }

        $numero_factura = model('pedidoPosModel')->select('numero_factura')->where('fk_usuario', $id_usuario)->first();
        $valorfactura_forma_pago = model('pedidoPosModel')->select('valor_total')->where('fk_usuario', $id_usuario)->first();
        $id_factura = model('facturaVentaModel')->where('idusuario_sistema', $id_usuario)->insertID;

        $pk_pedido_pos = model('pedidoPosModel')->select('id')->where('fk_usuario', $id_usuario)->first();

        $borrar_producto_pedido = model('productoPedidoPosModel')->where('pk_pedido_pos', $pk_pedido_pos['id']);
        $borrar_producto_pedido->delete();

        $total = model('pedidoPosModel')->select('valor_total')->where('fk_usuario', $id_usuario)->first();

        $borrar_pedido = model('pedidoPosModel')->where('fk_usuario', $id_usuario);
        $borrar_pedido->delete();

        $returnData = array(
            "id_factura" => $id_factura,
            "resultado" => 1,
            // "efectivo" => number_format($efectivo_sin_punto, 0, ",", "."),
            // "cambio" => number_format($transaccion_sin_punto - $valorfactura_forma_pago['valor_total'], 0, ",", "."),
            // "total" =>  number_format($valorfactura_forma_pago['valor_total'], 0, ",", "."),
            // "id_regimen" => 1,
            // "Sub_total" => number_format($sub_totales, 0, ",", "."),
            // "iva" => number_format($total_iva, 0, ",", "."),
            // "impuesto_al_consumo" => number_format($total_ico, 0, ",", "."),
            // "total" => $total[0]['total']
            "tabla" => view('factura_pos/tabla_reset_factura')
        );
        $borrar_pedido = model('pedidoPosModel')->where('fk_usuario', $id_usuario);
        $borrar_pedido->delete();
        echo  json_encode($returnData);
    }


    public function facturacion_con_impuestos_credito($fecha_y_hora, $saldo, $efectivo_sin_punto, $transaccion_sin_punto, $valor_venta_sin_punto, $nit_cliente, $id_usuario, $estado, $total_pagado_sin_punto)
    {



        $fecha = date("Y-m-d ");
        $hora = date("H:i:s");

        $numero_factura = model('consecutivosModel')->select('numeroconsecutivo')->where('conceptoconsecutivo =', 'Factura')->first();

        $id_consecutivo = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 6)->first();
        $prefijo_factura = model('dianModel')->select('inicialestatica')->where('iddian', $id_consecutivo['numeroconsecutivo'])->first();
        $id_resolucion_dian = model('consecutivosModel')->select('numeroconsecutivo')->where('conceptoconsecutivo =', 'IdRegistroDian')->first();
        $serie = model('consecutivosModel')->select('numeroconsecutivo')->where('conceptoconsecutivo =', 'Serie')->first();

        $observaciones_genereles = model('pedidoPosModel')->select('nota_general')->where('fk_usuario', $id_usuario)->first();
        if ($prefijo_factura != 0) {

            $data = [
                'numerofactura_venta' => $prefijo_factura['inicialestatica'] . "-" . $numero_factura['numeroconsecutivo'],
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
                //'observaciones_generales' => $observaciones_genereles['nota_pedido'],
                'fk_usuario_mesero' => $id_usuario,
                //'fk_mesa' => $fk_mesa['fk_mesa']
                'valor_factura' => $saldo,
                'saldo' => $saldo,
                'fecha_y_hora_factura_venta' => $fecha_y_hora
            ];
        }

        if ($prefijo_factura == 0) {
            $data = [
                'numerofactura_venta' => $prefijo_factura['inicialestatica'] . "-" . $numero_factura['numeroconsecutivo'],
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
                //'observaciones_generales' => $observaciones_genereles['nota_pedido'],
                'fk_usuario_mesero' => $id_usuario,
                'valor_factura' => $saldo,
                'saldo' => $saldo,
                'fecha_y_hora_factura_venta' => $fecha_y_hora
            ];
        }
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
            $id_iva = model('productoModel')->select('idiva')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();

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
                    'id_iva' => $id_iva['idiva'],
                    'aplica_ico' => true,

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
                    'id_iva' => $id_iva['id_iva'],
                    'aplica_ico' => false
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


        $valorfactura_forma_pago = model('pedidoPosModel')->select('valor_total')->where('fk_usuario', $id_usuario)->first();

        $borrar_pedido = model('pedidoPosModel')->where('fk_usuario', $id_usuario);
        $borrar_pedido->delete();

        $returnData = array(
            "id_factura" => $id_factura,
            "resultado" => 1,

            "tabla" => view('factura_pos/tabla_reset_factura')
        );
        $borrar_pedido = model('pedidoPosModel')->where('fk_usuario', $id_usuario);
        $borrar_pedido->delete();
        echo  json_encode($returnData);
    }

    function borrar_pedido()
    {
        $id_pedido = $_REQUEST['id_borrar_pedido'];

        $pin = $_REQUEST['pin'];
        $id_usuario = model('usuariosModel')->select('idusuario_sistema')->where('pinusuario_sistema', $pin)->first();
        $permiso_eliminar = model('tipoPermisoModel')->tipo_permiso($id_usuario['idusuario_sistema']);

        if (!empty($permiso_eliminar)) {
            $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $id_pedido)->first();

            $borrar_producto_pedido = model('productoPedidoModel')->where('numero_de_pedido', $id_pedido);
            $borrar_producto_pedido->delete();

            $borrar_pedido = model('pedidoModel')->where('id', $id_pedido);
            $borrar_pedido->delete();

            $data = [
                'estado' => 0,
                'valor_pedido' => 0,
                'fk_usuario' => $id_usuario['idusuario_sistema']
            ];

            $model = model('mesasModel');
            $numero_factura = $model->set($data);
            $numero_factura = $model->where('id', $id_mesa['fk_mesa']);
            $numero_factura = $model->update();

            if ($borrar_producto_pedido and  $borrar_pedido) {
                $session = session();
                $session->setFlashdata('iconoMensaje', 'success');
                return redirect()->to(base_url('pedido/pedidos_para_facturar'))->with('mensaje', 'Borrado éxitoso ');
            }
        } else {
        }
    }

    function actualizar_cantidades()
    {
        $id_tabla_producto = $this->request->getPost('id_tabla_prducto');
        $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_tabla_producto)->first();
        $valor_unitario_producto = model('productoPedidoModel')->select('valor_unitario')->where('id', $id_tabla_producto)->first();

        if ($cantidad_producto) {
            $data = [
                'cantidad_producto' => $cantidad_producto['cantidad_producto'] + 1,
                'valor_total' => $valor_unitario_producto['valor_unitario'] * ($cantidad_producto['cantidad_producto'] + 1)

            ];

            $model = model('productoPedidoModel');
            $actualizar_cantidad = $model->set($data);
            $actualizar_cantidad = $model->where('id', $id_tabla_producto);
            $actualizar_cantidad = $model->update();





            if ($actualizar_cantidad) {
                $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_tabla_producto)->first();
                $codigo_interno_producto = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_tabla_producto)->first();
                $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigointernoproducto'])->first();
                $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_tabla_producto)->first();
                $valor_total = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();
                $cantidad_productos = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();

                $data = [
                    'valor_total' => $valor_total[0]['valor_total'],
                    'cantidad_de_productos' => $cantidad_productos[0]['cantidad_producto']
                ];

                $model = model('pedidoModel');
                $actualizar_cantidad = $model->set($data);
                $actualizar_cantidad = $model->where('id', $numero_pedido['numero_de_pedido']);
                $actualizar_cantidad = $model->update();

                $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);

                $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido['numero_de_pedido'])->first();
                $valor_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido['numero_de_pedido'])->first();





                $returnData = array(
                    "resultado" => 1,
                    "cantidad" => $cantidad_producto['cantidad_producto'],
                    "id" => $id_tabla_producto,
                    "nombre_producto" => $nombre_producto['nombreproducto'],
                    "productos" => view('productos_pedido/productos_pedido', [
                        "productos" => $productos_pedido,
                        "pedido" => $numero_pedido['numero_de_pedido']
                    ]),
                    "cantidad_de_productos" => $cantidad_productos[0]['cantidad_producto'],
                    "total" => number_format($valor_pedido['valor_total'], 0, ",", ".")
                );
                echo  json_encode($returnData);
            }
        }
    }

    function eliminar_cantidades()
    {
        $id_tabla_producto = $this->request->getPost('id_tabla_prducto');
        $id_usuario = $this->request->getPost('id_usuario');

        //$impresion_comanda = model('productoPedidoModel')->select('impresion_en_comanda')->where('id', $id_tabla_producto)->first();
        $tipo_usuario = model('usuariosModel')->select('idtipo')->where('idusuario_sistema', $id_usuario)->first();
        $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_tabla_producto)->first();
        $cantidades_impresas = model('productoPedidoModel')->select('numero_productos_impresos_en_comanda')->where('id', $id_tabla_producto)->first();

        if ($cantidad_producto['cantidad_producto'] > $cantidades_impresas['numero_productos_impresos_en_comanda']) {

            if ($cantidad_producto['cantidad_producto'] - 1 > 0) {
                $data = [
                    'cantidad_producto' => $cantidad_producto['cantidad_producto'] - 1,

                ];

                $model = model('productoPedidoModel');
                $actualizar_cantidad = $model->set($data);
                $actualizar_cantidad = $model->where('id', $id_tabla_producto);
                $actualizar_cantidad = $model->update();
                if ($actualizar_cantidad) {

                    $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_tabla_producto)->first();
                    $codigo_interno_producto = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_tabla_producto)->first();
                    $valor_unitario_producto = model('productoPedidoModel')->select('valor_unitario')->where('id', $id_tabla_producto)->first();
                    $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigointernoproducto'])->first();
                    $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_tabla_producto)->first();

                    $valor_total_producto = [
                        'valor_total' => $cantidad_producto['cantidad_producto'] * $valor_unitario_producto['valor_unitario']
                    ];

                    $model = model('productoPedidoModel');
                    $actualizar_cantidad = $model->set($valor_total_producto);
                    $actualizar_cantidad = $model->where('id', $id_tabla_producto);
                    $actualizar_cantidad = $model->update();

                    $producto_borrado = [
                        'codigointernoproducto' => $codigo_interno_producto['codigointernoproducto'],
                        'cantidad' => 1,
                        'fecha_eliminacion' => date('Y-m-d'),
                        'hora_eliminacion' => date('H:i:s'),
                        'pedido' => $numero_pedido['numero_de_pedido']

                    ];

                    $insert = model('borrar_productosModel')->insert($producto_borrado);

                    $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);

                    $valor_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();

                    $valor_total_pedido = [
                        'valor_total' => $valor_pedido[0]['valor_total'],

                    ];

                    $model = model('pedidoModel');
                    $actualizar_cantidad = $model->set($valor_total_pedido);
                    $actualizar_cantidad = $model->where('id', $numero_pedido['numero_de_pedido']);
                    $actualizar_cantidad = $model->update();

                    $mesas = [
                        'valor_pedido' => $valor_pedido[0]['valor_total']
                    ];

                    $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido['numero_de_pedido'])->first();

                    $model = model('mesasModel');
                    $actualizar_cantidad = $model->set($mesas);
                    $actualizar_cantidad = $model->where('id', $id_mesa['fk_mesa']);
                    $actualizar_cantidad = $model->update();


                    $returnData = array(
                        "resultado" => 1,
                        "cantidad" => $cantidad_producto['cantidad_producto'],
                        "id" => $id_tabla_producto,
                        "nombre_producto" => $nombre_producto['nombreproducto'],
                        "productos" => view('productos_pedido/productos_pedido', [
                            "productos" => $productos_pedido,
                            "pedido" => $numero_pedido['numero_de_pedido'],
                        ]),
                        "total" => number_format($valor_pedido[0]['valor_total'], 0, ",", ".")
                    );
                    echo  json_encode($returnData);
                }
            }
        }

        if (($cantidad_producto['cantidad_producto'] == $cantidades_impresas['numero_productos_impresos_en_comanda']) and $tipo_usuario['idtipo'] == 1) {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        }

        if ($cantidad_producto['cantidad_producto'] < $cantidades_impresas['numero_productos_impresos_en_comanda'] and $tipo_usuario['idtipo'] == 0) {
            if ($cantidad_producto['cantidad_producto'] - 1 > 0) {
                $data = [
                    'cantidad_producto' => $cantidad_producto['cantidad_producto'] - 1,

                ];

                $model = model('productoPedidoModel');
                $actualizar_cantidad = $model->set($data);
                $actualizar_cantidad = $model->where('id', $id_tabla_producto);
                $actualizar_cantidad = $model->update();
                if ($actualizar_cantidad) {

                    $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_tabla_producto)->first();
                    $codigo_interno_producto = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_tabla_producto)->first();
                    $valor_unitario_producto = model('productoPedidoModel')->select('valor_unitario')->where('id', $id_tabla_producto)->first();
                    $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigointernoproducto'])->first();
                    $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_tabla_producto)->first();

                    $valor_total_producto = [
                        'valor_total' => $cantidad_producto['cantidad_producto'] * $valor_unitario_producto['valor_unitario']
                    ];

                    $model = model('productoPedidoModel');
                    $actualizar_cantidad = $model->set($valor_total_producto);
                    $actualizar_cantidad = $model->where('id', $id_tabla_producto);
                    $actualizar_cantidad = $model->update();

                    $producto_borrado = [
                        'codigointernoproducto' => $codigo_interno_producto['codigointernoproducto'],
                        'cantidad' => 1,
                        'fecha_eliminacion' => date('Y-m-d'),
                        'hora_eliminacion' => date('H:i:s'),
                        'pedido' => $numero_pedido['numero_de_pedido']

                    ];

                    $insert = model('borrar_productosModel')->insert($producto_borrado);

                    $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);

                    $valor_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();

                    $valor_total_pedido = [
                        'valor_total' => $valor_pedido[0]['valor_total'],

                    ];

                    $model = model('pedidoModel');
                    $actualizar_cantidad = $model->set($valor_total_pedido);
                    $actualizar_cantidad = $model->where('id', $numero_pedido['numero_de_pedido']);
                    $actualizar_cantidad = $model->update();

                    $mesas = [
                        'valor_pedido' => $valor_pedido[0]['valor_total']
                    ];

                    $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido['numero_de_pedido'])->first();

                    $model = model('mesasModel');
                    $actualizar_cantidad = $model->set($mesas);
                    $actualizar_cantidad = $model->where('id', $id_mesa['fk_mesa']);
                    $actualizar_cantidad = $model->update();


                    $returnData = array(
                        "resultado" => 1,
                        "cantidad" => $cantidad_producto['cantidad_producto'],
                        "id" => $id_tabla_producto,
                        "nombre_producto" => $nombre_producto['nombreproducto'],
                        "productos" => view('productos_pedido/productos_pedido', [
                            "productos" => $productos_pedido,
                            "pedido" => $numero_pedido['numero_de_pedido'],
                        ]),
                        "total" => number_format($valor_pedido[0]['valor_total'], 0, ",", ".")
                    );
                    echo  json_encode($returnData);
                }
            }
        }


        if ($cantidad_producto['cantidad_producto'] == $cantidades_impresas['numero_productos_impresos_en_comanda'] and $tipo_usuario['idtipo'] == 0) {
            if ($cantidad_producto['cantidad_producto'] - 1 > 0) {
                $data = [
                    'cantidad_producto' => $cantidad_producto['cantidad_producto'] - 1,

                ];

                $model = model('productoPedidoModel');
                $actualizar_cantidad = $model->set($data);
                $actualizar_cantidad = $model->where('id', $id_tabla_producto);
                $actualizar_cantidad = $model->update();
                if ($actualizar_cantidad) {

                    $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_tabla_producto)->first();
                    $codigo_interno_producto = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_tabla_producto)->first();
                    $valor_unitario_producto = model('productoPedidoModel')->select('valor_unitario')->where('id', $id_tabla_producto)->first();
                    $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigointernoproducto'])->first();
                    $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_tabla_producto)->first();

                    $valor_total_producto = [
                        'valor_total' => $cantidad_producto['cantidad_producto'] * $valor_unitario_producto['valor_unitario']
                    ];

                    $model = model('productoPedidoModel');
                    $actualizar_cantidad = $model->set($valor_total_producto);
                    $actualizar_cantidad = $model->where('id', $id_tabla_producto);
                    $actualizar_cantidad = $model->update();

                    $producto_borrado = [
                        'codigointernoproducto' => $codigo_interno_producto['codigointernoproducto'],
                        'cantidad' => 1,
                        'fecha_eliminacion' => date('Y-m-d'),
                        'hora_eliminacion' => date('H:i:s'),
                        'pedido' => $numero_pedido['numero_de_pedido']

                    ];

                    $insert = model('borrar_productosModel')->insert($producto_borrado);

                    $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);

                    $valor_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();

                    $valor_total_pedido = [
                        'valor_total' => $valor_pedido[0]['valor_total'],

                    ];

                    $model = model('pedidoModel');
                    $actualizar_cantidad = $model->set($valor_total_pedido);
                    $actualizar_cantidad = $model->where('id', $numero_pedido['numero_de_pedido']);
                    $actualizar_cantidad = $model->update();

                    $mesas = [
                        'valor_pedido' => $valor_pedido[0]['valor_total']
                    ];

                    $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido['numero_de_pedido'])->first();

                    $model = model('mesasModel');
                    $actualizar_cantidad = $model->set($mesas);
                    $actualizar_cantidad = $model->where('id', $id_mesa['fk_mesa']);
                    $actualizar_cantidad = $model->update();


                    $returnData = array(
                        "resultado" => 1,
                        "cantidad" => $cantidad_producto['cantidad_producto'],
                        "id" => $id_tabla_producto,
                        "nombre_producto" => $nombre_producto['nombreproducto'],
                        "productos" => view('productos_pedido/productos_pedido', [
                            "productos" => $productos_pedido,
                            "pedido" => $numero_pedido['numero_de_pedido'],
                        ]),
                        "total" => number_format($valor_pedido[0]['valor_total'], 0, ",", ".")
                    );
                    echo  json_encode($returnData);
                }
            }
        }

        if ($cantidad_producto['cantidad_producto'] < $cantidades_impresas['numero_productos_impresos_en_comanda'] and $tipo_usuario['idtipo'] == 1) {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        }
    }
    function pedido()
    {
        $id_pedido = $_POST['numero_de_pedido'];
        $fk_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $id_pedido)->first();
        $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $fk_mesa['fk_mesa'])->first();
        $categorias = model('categoriasModel')->findAll();
        $cantidad_items = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $id_pedido)->findAll();
        $valor_pedido = model('pedidoModel')->select('valor_total')->where('id', $id_pedido)->first();

        $productos_pedido = model('productoPedidoModel')->producto_pedido($id_pedido);
        return view('pedido/pedido', [
            'categorias' => $categorias,
            'fk_mesa' => $fk_mesa['fk_mesa'],
            'nombre_mesa' => $nombre_mesa['nombre'],
            'pedido' => $id_pedido,
            'productos' => $productos_pedido,
            'cantidad_items' => $cantidad_items[0]['cantidad_producto'],
            'valor_pedido' => number_format($valor_pedido['valor_total'], 0, ",", ".")
        ]);
    }
}
