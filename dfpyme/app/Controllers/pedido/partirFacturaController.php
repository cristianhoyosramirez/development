<?php

namespace App\Controllers\pedido;

use App\Controllers\BaseController;
use \DateTime;
use \DateTimeZone;

class partirFacturaController extends BaseController
{
    public function index()
    {
        return view('home/home');
    }

    public function partir_factura()
    {
        $idRegistros = $_REQUEST['ids_array'];
        $numero_pedido = $_REQUEST['numero_pedido'];
        $id_usuario = $_REQUEST['id_usuario'];
        $existe_registros = model('partirFacturaModel')->select('id')->where('numero_de_pedido', $numero_pedido)->first();
        if (empty($existe_registros)) {


            foreach ($idRegistros as $detalle) {

                $producto = model('pedidoModel')->partir_factura($detalle);

                $data = [
                    'numero_de_pedido' => $numero_pedido,
                    'cantidad_producto' => $producto[0]['cantidad_producto'],
                    'valor_unitario' => $producto[0]['valor_unitario'],
                    'valor_total' => $producto[0]['valor_total'],
                    'codigointernoproducto' => $producto[0]['codigointernoproducto'],
                    'nombre_producto' => $producto[0]['nombreproducto'],
                    'id_tabla_producto' => $detalle
                ];
                $insert = model('partirFacturaModel')->insert($data);
            }

            $producto_partir_factura = model('partirFacturaModel')->productos($numero_pedido);
            $fk_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
            $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $fk_mesa['fk_mesa'])->first();
            $total = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
            $productos = view('partir_factura/partir_factura', [
                'productos' => $producto_partir_factura
            ]);
            if (!empty($producto_partir_factura)) {

                $returnData = array(
                    "resultado" => 1, //Falta plata
                    "nombre_mesa" => $nombre_mesa['nombre'],
                    "productos" => $productos,
                    "valor_total" => $total[0]['valor_total'],
                    "valor_total_formato" => "$" . number_format($total[0]['valor_total'], 0, ",", "."),
                    "numero_pedido" => $numero_pedido
                );
                echo  json_encode($returnData);
            }
        }
        if (!empty($existe_registros)) {
            $returnData = array(
                "resultado" => 0, //Falta plata  
            );
            echo  json_encode($returnData);
        }
    }
    public function consultar_total()
    {
        $numero_pedido = $_POST['numero_pedido'];
        $valor_total = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();

        $returnData = array(
            "resultado" => 1,
            "valor_total" => $valor_total[0]['valor_total'],
            "valor_total_formato" =>  number_format($valor_total[0]['valor_total'], 0, ",", "."),

        );
        echo  json_encode($returnData);
    }

    public function facturar()
    {

        $numero_de_pedido = $_POST['numero_de_pedido'];
        $efectivo = $_POST['efectivo'];
        $transaccion = $_POST['transaccion'];
        $valor_venta = $_POST['valor_venta'];
        $nit_cliente = $_POST['nit_cliente'];
        $id_usuario = $_POST['id_usuario'];
        $estado = $_POST['estado'];
        $total_pagado = $_POST['total_pagado'];

        /*  $numero_de_pedido =14634;
        $efectivo = 10000;
        $transaccion = 0;
        $valor_venta = 5000;
        $nit_cliente = 22222222;
        $id_usuario = 8;
        $estado = 1;
        $total_pagado =10000; */


        if ($valor_venta <= $total_pagado) {
            $id_regimen = model('empresaModel')->select('idregimen')->first();

            $total_pagado = $efectivo + $transaccion;
            $fecha = DateTime::createFromFormat('U.u', microtime(TRUE));
            $fecha->setTimeZone(new DateTimeZone('America/Bogota'));
            $fecha_y_hora = $fecha->format('Y-m-d H:i:s.u');

            switch ($id_regimen['idregimen']) {
                case 1:
                    $this->facturacion_con_impuestos($fecha_y_hora, $numero_de_pedido, $efectivo, $transaccion, $valor_venta, $nit_cliente, $id_usuario, $estado, $total_pagado);
                    break;
                case 2:
                    $this->facturacion_sin_impuestos($fecha_y_hora, $numero_de_pedido, $efectivo, $transaccion, $valor_venta, $nit_cliente, $id_usuario, $estado, $total_pagado);
                    break;
            }
        } else if ($valor_venta > $total_pagado) {

            $returnData = array(
                "resultado" => 0, //Falta plata  
            );
            echo  json_encode($returnData);
        }
    }

    public function facturacion_sin_impuestos($fecha_y_hora, $numero_pedido, $efectivo, $transaccion, $valor_venta, $nit_cliente, $id_usuario, $estado, $total_pagado)
    {

        $fecha = date("Y-m-d ");
        $hora = date("H:i:s");
        $numero_factura = model('consecutivosModel')->select('numeroconsecutivo')->where('conceptoconsecutivo =', 'Factura')->first();
        $id_registro_dian = model('consecutivosModel')->select('numeroconsecutivo')->Where('idconsecutivos', 6)->first();
        $prefijo_factura = model('dianModel')->select('inicialestatica')->where('iddian', $id_registro_dian['numeroconsecutivo'])->first();
        $id_resolucion_dian = model('consecutivosModel')->select('numeroconsecutivo')->where('conceptoconsecutivo =', 'IdRegistroDian')->first();
        $serie = model('consecutivosModel')->select('numeroconsecutivo')->where('conceptoconsecutivo =', 'Serie')->first();
        $fk_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();

        $observaciones_genereles = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido)->first();
        $fk_usuario_mesero = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();

        $valor_total = model('partirFacturaModel')->select('valor_total')->where('numero_de_pedido', $numero_pedido)->first();

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
                'observaciones_generales' => $observaciones_genereles['nota_pedido'],
                'fk_usuario_mesero' => $fk_usuario_mesero['fk_usuario'],
                'valor_factura' => $valor_total['valor_total'],
                'fk_mesa' => $fk_mesa['fk_mesa'],
                'numero_pedido' => $numero_pedido,
                'fecha_y_hora_factura_venta' => $fecha_y_hora

            ];
        }

        if ($prefijo_factura == 0) {
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
                'observaciones_generales' => $observaciones_genereles['nota_pedido'],
                'fk_usuario_mesero' => $fk_usuario_mesero['fk_usuario'],
                'valor_factura' => $valor_total['valor_total'],
                'fk_mesa' => $fk_mesa['fk_mesa'],
                'numero_pedido' => $numero_pedido,
                'fecha_y_hora_factura_venta' => $fecha_y_hora

            ];
        }

        $serie_update  = $serie['numeroconsecutivo'] + 1;

        $incremento = model('consecutivosModel')->update_serie($serie_update);

        $factuta_venta = model('facturaVentaModel')->insert($data);

        $numero_facturaUpdate = [
            'numeroconsecutivo' => $numero_factura['numeroconsecutivo'] + 1
        ];


        $model = model('consecutivosModel');
        $numero_factura = $model->set($numero_facturaUpdate);
        $numero_factura = $model->where('idconsecutivos', 8);
        $numero_factura = $model->update();

        $productos = model('partirFacturaModel')->where('numero_de_pedido', $numero_pedido)->find();
        $ultimo_id_factuta_venta = model('facturaVentaModel')->insertID;
        $numero_de_factura = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $ultimo_id_factuta_venta)->first();

        foreach ($productos as $detalle) {
            $id_medida = model('productoMedidaModel')->select('idvalor_unidad_medida')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
            $numero_factura = $numero_de_factura['numerofactura_venta'];
            $codigo_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();

            $total = model('pedidoModel')->select('valor_total')->where('id', $detalle['id'])->first();

            $precio_costo = model('productoModel')->select('precio_costo')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
            $id_factura = model('facturaVentaModel')->where('idusuario_sistema', $id_usuario)->insertID;
            $aplica_ico = model('productoModel')->select('aplica_ico')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
            $id_iva = model('productoModel')->select('idiva')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
            $producto_factura_venta = [
                'numerofactura_venta' => $numero_factura,
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
                'aplica_ico' => false,
                'valor_total_producto' => $detalle['valor_unitario'],
                'fecha_y_hora_venta' => $fecha_y_hora,
                'fecha_venta' => date('Y-m-d'),
                'id_categoria' => $codigo_categoria['codigocategoria']

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

        $ultimo_id_factuta_venta = model('facturaVentaModel')->insertID;

        $numero_factura = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $ultimo_id_factuta_venta)->first();

        $valorfactura_forma_pago = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();

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


        $factura_forma_pago_efectivo = [

            'numerofactura_venta' => $prefijo_factura['inicialestatica'] . "-" . $numero_factura['numerofactura_venta'],
            'idusuario' => $id_usuario,
            'idcaja' => 1,
            'idforma_pago' => 1,
            'fechafactura_forma_pago' => $fecha,
            'hora' => $hora,
            'valorfactura_forma_pago' => $pago_efectivo,
            'idturno' => 1,
            'valor_pago' => $efectivo,
            'id_factura' => $ultimo_id_factuta_venta,
            'fecha_y_hora_forma_pago' => $fecha_y_hora,
        ];

        $factura_forma_pago_transaccion = [
            'numerofactura_venta' => $prefijo_factura['inicialestatica'] . "-" . $numero_factura['numerofactura_venta'],
            'idusuario' => $id_usuario,
            'idcaja' => 1,
            'idforma_pago' => 4,
            'fechafactura_forma_pago' => $fecha,
            'hora' => $hora,
            'valorfactura_forma_pago' => $pago_transaccion,
            'idturno' => 1,
            'valor_pago' => $transaccion,
            'id_factura' => $ultimo_id_factuta_venta,
            'fecha_y_hora_forma_pago' => $fecha_y_hora,
        ];

        if ($efectivo > 0 and $transaccion == 0) {
            $insert_efectivo = model('facturaFormaPagoModel')->insert($factura_forma_pago_efectivo);
            $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();



            $valor_total_partir_factura = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
            $valor_pedido_mesa = model('mesasModel')->select('valor_pedido')->where('id', $id_mesa['fk_mesa'])->first();
            $actualizar_valor_pedido_mesa = $valor_pedido_mesa['valor_pedido'] - $valor_total_partir_factura[0]['valor_total'];
            if ($insert_efectivo) {

                $data = [
                    'valor_pedido' => $actualizar_valor_pedido_mesa,
                ];
                $model = model('mesasModel');
                $actualizar = $model->set($data);
                $actualizar = $model->where('id', $id_mesa['fk_mesa']);
                $actualizar = $model->update();

                $id_tabla_producto_pedido = model('partirFacturaModel')->select('id_tabla_producto')->where('numero_de_pedido', $numero_pedido)->find();


                foreach ($id_tabla_producto_pedido as $detalle) {

                    $cantidad_partir_factura = model('partirFacturaModel')->select('cantidad_producto')->where('id_tabla_producto', $detalle['id_tabla_producto'])->first();
                    $cantidad_producto_pedido = model('productoPedidoModel')->select('cantidad_producto')->where('id', $detalle['id_tabla_producto'])->first();
                    if ($cantidad_partir_factura['cantidad_producto'] < $cantidad_producto_pedido['cantidad_producto']) {

                        $valor_unitario = model('productoPedidoModel')->select('valor_unitario')->where('id', $detalle['id_tabla_producto'])->first();
                        $nueva_cantidad_producto_pedido = $cantidad_producto_pedido['cantidad_producto'] - $cantidad_partir_factura['cantidad_producto'];

                        $valor_total = $valor_unitario['valor_unitario'] * $nueva_cantidad_producto_pedido;

                        $producto_pedido = [
                            'cantidad_producto' => $cantidad_producto_pedido['cantidad_producto'] - $cantidad_partir_factura['cantidad_producto'],
                            'valor_total' => $valor_total
                        ];
                        $model = model('productoPedidoModel');
                        $actualizar = $model->set($producto_pedido);
                        $actualizar = $model->where('id', $detalle['id_tabla_producto']);
                        $actualizar = $model->update();
                    } else {

                        $borrar_pedido = model('productoPedidoModel')->where('id', $detalle['id_tabla_producto']);
                        $borrar_pedido->delete();
                    }
                }

                $borrar_producto_pedido = model('partirFacturaModel')->where('numero_de_pedido', $numero_pedido);
                $borrar_producto_pedido->delete();

                $valor_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
                $cantidad_producto = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido)->find();

                $pedido = [
                    'valor_total' => $valor_pedido[0]['valor_total'],
                    'cantidad_de_productos' => $cantidad_producto[0]['cantidad_producto']

                ];
                $model = model('pedidoModel');
                $actualizar = $model->set($pedido);
                $actualizar = $model->where('id', $numero_pedido);
                $actualizar = $model->update();

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


                $total = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
                $returnData = array(
                    "id_factura" => $id_factura,
                    "resultado" => 1,
                    "efectivo" => number_format($efectivo, 0, ",", "."),
                    "cambio" => number_format($efectivo - $sub_totales, 0, ",", "."),

                    //"total" =>  number_format($valorfactura_forma_pago['valor_total'], 0, ",", "."),
                    "id_regimen" => 2,
                    "total" => number_format($sub_totales, 0, ",", "."),

                    //"total" => $total[0]['valor_total']
                    "numero_de_pedido" => $numero_pedido
                );

                echo  json_encode($returnData);
                // $borrar_pedido = model('partirFacturaModel')->where('numero_de_pedido', $numero_pedido);
                //$borrar_pedido->delete();
            } else {
                $returnData = array(
                    "id_factura" => $id_factura,
                    "mensaje" => 0

                );
                echo  json_encode($returnData);
            }
        }

        if ($efectivo > 0 and $transaccion > 0) {
            $insert_efectivo = model('facturaFormaPagoModel')->insert($factura_forma_pago_efectivo);
            $insert_transaccion = model('facturaFormaPagoModel')->insert($factura_forma_pago_transaccion);

            $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();



            $valor_total_partir_factura = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
            $valor_pedido_mesa = model('mesasModel')->select('valor_pedido')->where('id', $id_mesa['fk_mesa'])->first();
            $actualizar_valor_pedido_mesa = $valor_pedido_mesa['valor_pedido'] - $valor_total_partir_factura[0]['valor_total'];
            if ($insert_efectivo and  $insert_transaccion) {

                $data = [
                    'valor_pedido' => $actualizar_valor_pedido_mesa,
                ];
                $model = model('mesasModel');
                $actualizar = $model->set($data);
                $actualizar = $model->where('id', $id_mesa['fk_mesa']);
                $actualizar = $model->update();

                $id_tabla_producto_pedido = model('partirFacturaModel')->select('id_tabla_producto')->where('numero_de_pedido', $numero_pedido)->find();


                foreach ($id_tabla_producto_pedido as $detalle) {

                    $cantidad_partir_factura = model('partirFacturaModel')->select('cantidad_producto')->where('id_tabla_producto', $detalle['id_tabla_producto'])->first();
                    $cantidad_producto_pedido = model('productoPedidoModel')->select('cantidad_producto')->where('id', $detalle['id_tabla_producto'])->first();
                    if ($cantidad_partir_factura['cantidad_producto'] < $cantidad_producto_pedido['cantidad_producto']) {

                        $valor_unitario = model('productoPedidoModel')->select('valor_unitario')->where('id', $detalle['id_tabla_producto'])->first();
                        $nueva_cantidad_producto_pedido = $cantidad_producto_pedido['cantidad_producto'] - $cantidad_partir_factura['cantidad_producto'];

                        $valor_total = $valor_unitario['valor_unitario'] * $nueva_cantidad_producto_pedido;

                        $producto_pedido = [
                            'cantidad_producto' => $cantidad_producto_pedido['cantidad_producto'] - $cantidad_partir_factura['cantidad_producto'],
                            'valor_total' => $valor_total
                        ];
                        $model = model('productoPedidoModel');
                        $actualizar = $model->set($producto_pedido);
                        $actualizar = $model->where('id', $detalle['id_tabla_producto']);
                        $actualizar = $model->update();
                    } else {

                        $borrar_pedido = model('productoPedidoModel')->where('id', $detalle['id_tabla_producto']);
                        $borrar_pedido->delete();
                    }
                }

                $borrar_producto_pedido = model('partirFacturaModel')->where('numero_de_pedido', $numero_pedido);
                $borrar_producto_pedido->delete();

                $valor_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
                $cantidad_producto = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido)->find();

                $pedido = [
                    'valor_total' => $valor_pedido[0]['valor_total'],
                    'cantidad_de_productos' => $cantidad_producto[0]['cantidad_producto']

                ];
                $model = model('pedidoModel');
                $actualizar = $model->set($pedido);
                $actualizar = $model->where('id', $numero_pedido);
                $actualizar = $model->update();

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


                $total = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
                $returnData = array(
                    "id_factura" => $id_factura,
                    "resultado" => 1,
                    "efectivo" => number_format(($efectivo + $transaccion), 0, ",", "."),
                    "cambio" => number_format(($efectivo + $transaccion) - $sub_totales, 0, ",", "."),

                    //"total" =>  number_format($valorfactura_forma_pago['valor_total'], 0, ",", "."),
                    "id_regimen" => 2,
                    "total" => number_format($sub_totales, 0, ",", "."),

                    //"total" => $total[0]['valor_total']
                    "numero_de_pedido" => $numero_pedido
                );

                echo  json_encode($returnData);
                // $borrar_pedido = model('partirFacturaModel')->where('numero_de_pedido', $numero_pedido);
                //$borrar_pedido->delete();
            } else {
                $returnData = array(
                    "id_factura" => $id_factura,
                    "mensaje" => 0

                );
                echo  json_encode($returnData);
            }
        }

        if ($efectivo == 0 and $transaccion > 0) {
            $insert_transaccion = model('facturaFormaPagoModel')->insert($factura_forma_pago_transaccion);

            $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();



            $valor_total_partir_factura = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
            $valor_pedido_mesa = model('mesasModel')->select('valor_pedido')->where('id', $id_mesa['fk_mesa'])->first();
            $actualizar_valor_pedido_mesa = $valor_pedido_mesa['valor_pedido'] - $valor_total_partir_factura[0]['valor_total'];
            if ($insert_transaccion) {

                $data = [
                    'valor_pedido' => $actualizar_valor_pedido_mesa,
                ];
                $model = model('mesasModel');
                $actualizar = $model->set($data);
                $actualizar = $model->where('id', $id_mesa['fk_mesa']);
                $actualizar = $model->update();

                $id_tabla_producto_pedido = model('partirFacturaModel')->select('id_tabla_producto')->where('numero_de_pedido', $numero_pedido)->find();


                foreach ($id_tabla_producto_pedido as $detalle) {

                    $cantidad_partir_factura = model('partirFacturaModel')->select('cantidad_producto')->where('id_tabla_producto', $detalle['id_tabla_producto'])->first();
                    $cantidad_producto_pedido = model('productoPedidoModel')->select('cantidad_producto')->where('id', $detalle['id_tabla_producto'])->first();
                    if ($cantidad_partir_factura['cantidad_producto'] < $cantidad_producto_pedido['cantidad_producto']) {

                        $valor_unitario = model('productoPedidoModel')->select('valor_unitario')->where('id', $detalle['id_tabla_producto'])->first();
                        $nueva_cantidad_producto_pedido = $cantidad_producto_pedido['cantidad_producto'] - $cantidad_partir_factura['cantidad_producto'];

                        $valor_total = $valor_unitario['valor_unitario'] * $nueva_cantidad_producto_pedido;

                        $producto_pedido = [
                            'cantidad_producto' => $cantidad_producto_pedido['cantidad_producto'] - $cantidad_partir_factura['cantidad_producto'],
                            'valor_total' => $valor_total
                        ];
                        $model = model('productoPedidoModel');
                        $actualizar = $model->set($producto_pedido);
                        $actualizar = $model->where('id', $detalle['id_tabla_producto']);
                        $actualizar = $model->update();
                    } else {

                        $borrar_pedido = model('productoPedidoModel')->where('id', $detalle['id_tabla_producto']);
                        $borrar_pedido->delete();
                    }
                }

                $borrar_producto_pedido = model('partirFacturaModel')->where('numero_de_pedido', $numero_pedido);
                $borrar_producto_pedido->delete();

                $valor_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
                $cantidad_producto = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido)->find();

                $pedido = [
                    'valor_total' => $valor_pedido[0]['valor_total'],
                    'cantidad_de_productos' => $cantidad_producto[0]['cantidad_producto']

                ];
                $model = model('pedidoModel');
                $actualizar = $model->set($pedido);
                $actualizar = $model->where('id', $numero_pedido);
                $actualizar = $model->update();

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


                $total = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
                $returnData = array(
                    "id_factura" => $id_factura,
                    "resultado" => 1,
                    "efectivo" => number_format(($efectivo + $transaccion), 0, ",", "."),
                    "cambio" => number_format(($efectivo + $transaccion) - $sub_totales, 0, ",", "."),

                    //"total" =>  number_format($valorfactura_forma_pago['valor_total'], 0, ",", "."),
                    "id_regimen" => 2,
                    "total" => number_format($sub_totales, 0, ",", "."),

                    //"total" => $total[0]['valor_total']
                    "numero_de_pedido" => $numero_pedido
                );

                echo  json_encode($returnData);
                // $borrar_pedido = model('partirFacturaModel')->where('numero_de_pedido', $numero_pedido);
                //$borrar_pedido->delete();
            } else {
                $returnData = array(
                    "id_factura" => $id_factura,
                    "mensaje" => 0

                );
                echo  json_encode($returnData);
            }
        }
    }

    public function facturacion_con_impuestos($fecha_y_hora, $numero_pedido, $efectivo, $transaccion, $valor_venta, $nit_cliente, $id_usuario, $estado, $total_pagado)
    {

        $fecha = date("Y-m-d ");
        $hora = date("H:i:s");
        $numero_factura = model('consecutivosModel')->select('numeroconsecutivo')->where('conceptoconsecutivo =', 'Factura')->first();
        //$prefijo_factura = model('consecutivosModel')->select('numeroconsecutivo')->where('conceptoconsecutivo =', 'FacturaPrefijo')->first();
        //$id_resolucion_dian = model('consecutivosModel')->select('numeroconsecutivo')->where('conceptoconsecutivo =', 'IdRegistroDian')->first();


        $id_resolucion_dian = model('consecutivosModel')->select('numeroconsecutivo')->Where('idconsecutivos', 6)->first();
        $prefijo_factura = model('dianModel')->select('inicialestatica')->where('iddian', $id_resolucion_dian['numeroconsecutivo'])->first();


        $serie = model('consecutivosModel')->select('numeroconsecutivo')->where('conceptoconsecutivo =', 'Serie')->first();
        $fk_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();

        $observaciones_genereles = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido)->first();
        $fk_usuario_mesero = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();
        $valor_total = model('partirFacturaModel')->select('valor_total')->where('numero_de_pedido', $numero_pedido)->first();
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
                'observaciones_generales' => $observaciones_genereles['nota_pedido'],
                'fk_usuario_mesero' => $fk_usuario_mesero['fk_usuario'],
                'valor_factura' => $valor_total['valor_total'],
                'fk_mesa' => $fk_mesa['fk_mesa'],
                'numero_pedido' => $numero_pedido,
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
                'observaciones_generales' => $observaciones_genereles['nota_pedido'],
                'fk_usuario_mesero' => $fk_usuario_mesero['fk_usuario'],
                'valor_factura' => $valor_total['valor_total'],
                'fk_mesa' => $fk_mesa['fk_mesa'],
                'numero_pedido' => $numero_pedido,
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
        $num_fact = model('pedidosModel');

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
        $productos = model('partirFacturaModel')->where('numero_de_pedido', $numero_pedido)->find();


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
                $total = model('partirFacturaModel')->select('valor_total')->where('id', $detalle['id'])->first();

                $precio_costo = model('productoModel')->select('precio_costo')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                $id_iva = model('productoModel')->select('idiva')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                $id_factura = model('facturaVentaModel')->where('idusuario_sistema', $id_usuario)->insertID;

                $numero_factura = model('pedidoModel')->select('numero_factura')->where('id', $numero_pedido)->first();

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
                    'total' => $total['valor_total'],
                    'valor_ico' => $valor_imco,  //Deberia de ser el 8 
                    'impuesto_al_consumo' => $impuesto_al_consumo,
                    'iva' => 0,
                    //'id_iva' => $id_iva['idiva'],
                    'aplica_ico' => true,
                    'valor_total_producto' => $detalle['valor_unitario'],
                    //'fecha_y_hora_venta' => date("Y-m-d H:i:s"),
                    'saldo' => 0,
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

                $total = model('productoPedidoModel')->select('valor_total')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();

                $precio_costo = model('productoModel')->select('precio_costo')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                $id_factura = model('facturaVentaModel')->where('idusuario_sistema', $id_usuario)->insertID;
                $numero_factura = model('pedidoModel')->select('numero_factura')->where('id', $numero_pedido)->first();

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
                    'total' => $total['valor_total'],
                    'valor_ico' => $valor_imco, //
                    'impuesto_al_consumo' => 0,
                    'iva' => $iva,
                    'aplica_ico' => false,
                    'valor_total_producto' => $detalle['valor_unitario'],
                    //'fecha_y_hora_venta' => date("Y-m-d H:i:s"),
                    'saldo' => 0,
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

        $factura_forma_pago_efectivo = [

            'numerofactura_venta' =>  $prefijo_factura['inicialestatica'] . "-" . $numero_factura['numero_factura'],
            'idusuario' => $id_usuario,
            'idcaja' => 1,
            'idforma_pago' => 1,
            'fechafactura_forma_pago' => $fecha,
            'hora' => $hora,
            'valorfactura_forma_pago' => $pago_efectivo,
            'idturno' => 1,
            'valor_pago' => $efectivo,
            'id_factura' => $id_factura,
            'fecha_y_hora_forma_pago' => $fecha_y_hora,
        ];


        $factura_forma_pago_transaccion = [

            'numerofactura_venta' =>  $prefijo_factura['inicialestatica'] . "-" . $numero_factura['numero_factura'],
            'idusuario' => $id_usuario,
            'idcaja' => 1,
            'idforma_pago' => 4,
            'fechafactura_forma_pago' => $fecha,
            'hora' => $hora,
            'valorfactura_forma_pago' => $transaccion,
            'idturno' => 1,
            'valor_pago' => $pago_transaccion,
            'id_factura' => $id_factura,
            'fecha_y_hora_forma_pago' => $fecha_y_hora,
        ];


        $valorfactura_forma_pago = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();

        if ($efectivo > 0 and $transaccion == 0) {

            $insert_efectivo = model('facturaFormaPagoModel')->insert($factura_forma_pago_efectivo);
            $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();



            $valor_total_partir_factura = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
            $valor_pedido_mesa = model('mesasModel')->select('valor_pedido')->where('id', $id_mesa['fk_mesa'])->first();
            $actualizar_valor_pedido_mesa = $valor_pedido_mesa['valor_pedido'] - $valor_total_partir_factura[0]['valor_total'];
            if ($insert_efectivo) {

                $data = [
                    'valor_pedido' => $actualizar_valor_pedido_mesa,
                ];
                $model = model('mesasModel');
                $actualizar = $model->set($data);
                $actualizar = $model->where('id', $id_mesa['fk_mesa']);
                $actualizar = $model->update();

                $id_tabla_producto_pedido = model('partirFacturaModel')->select('id_tabla_producto')->where('numero_de_pedido', $numero_pedido)->find();


                foreach ($id_tabla_producto_pedido as $detalle) {

                    $cantidad_partir_factura = model('partirFacturaModel')->select('cantidad_producto')->where('id_tabla_producto', $detalle['id_tabla_producto'])->first();
                    $cantidad_producto_pedido = model('productoPedidoModel')->select('cantidad_producto')->where('id', $detalle['id_tabla_producto'])->first();
                    if ($cantidad_partir_factura['cantidad_producto'] < $cantidad_producto_pedido['cantidad_producto']) {

                        $valor_unitario = model('productoPedidoModel')->select('valor_unitario')->where('id', $detalle['id_tabla_producto'])->first();
                        $nueva_cantidad_producto_pedido = $cantidad_producto_pedido['cantidad_producto'] - $cantidad_partir_factura['cantidad_producto'];

                        $valor_total = $valor_unitario['valor_unitario'] * $nueva_cantidad_producto_pedido;

                        $producto_pedido = [
                            'cantidad_producto' => $cantidad_producto_pedido['cantidad_producto'] - $cantidad_partir_factura['cantidad_producto'],
                            'valor_total' => $valor_total
                        ];
                        $model = model('productoPedidoModel');
                        $actualizar = $model->set($producto_pedido);
                        $actualizar = $model->where('id', $detalle['id_tabla_producto']);
                        $actualizar = $model->update();
                    } else {

                        $borrar_pedido = model('productoPedidoModel')->where('id', $detalle['id_tabla_producto']);
                        $borrar_pedido->delete();
                    }
                }

                // $borrar_producto_pedido = model('partirFacturaModel')->where('numero_de_pedido', $numero_pedido);
                //$borrar_producto_pedido->delete();

                $valor_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
                $cantidad_producto = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido)->find();

                $pedido = [
                    'valor_total' => $valor_pedido[0]['valor_total'],
                    'cantidad_de_productos' => $cantidad_producto[0]['cantidad_producto']

                ];
                $model = model('pedidoModel');
                $actualizar = $model->set($pedido);
                $actualizar = $model->where('id', $numero_pedido);
                $actualizar = $model->update();

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


                $total = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();


                $returnData = array(
                    "id_factura" => $id_factura,
                    "resultado" => 1,
                    "efectivo" => number_format($efectivo, 0, ",", "."),
                    "cambio" => number_format($efectivo - $total[0]['valor_total'], 0, ",", "."),

                    //"total" =>  number_format($valorfactura_forma_pago['valor_total'], 0, ",", "."),
                    "id_regimen" => 1,
                    "total" => number_format($total[0]['valor_total'], 0, ",", "."),

                    //"total" => $total[0]['valor_total']
                    "numero_pedido" => $numero_pedido,
                );

                echo  json_encode($returnData);
                $borrar_pedido = model('partirFacturaModel')->where('numero_de_pedido', $numero_pedido);
                $borrar_pedido->delete();
            } else {
                $returnData = array(
                    "id_factura" => $id_factura,
                    "mensaje" => 0

                );
                echo  json_encode($returnData);
            }
        }
        if ($efectivo > 0 and $transaccion > 0) {

            $insert_efectivo = model('facturaFormaPagoModel')->insert($factura_forma_pago_efectivo);
            $insert_transaccion = model('facturaFormaPagoModel')->insert($factura_forma_pago_transaccion);
            if ($insert_efectivo and $insert_transaccion) {
                $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
                $valor_total_partir_factura = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
                $valor_pedido_mesa = model('mesasModel')->select('valor_pedido')->where('id', $id_mesa['fk_mesa'])->first();
                $actualizar_valor_pedido_mesa = $valor_pedido_mesa['valor_pedido'] - $valor_total_partir_factura[0]['valor_total'];

                $data = [
                    'valor_pedido' => $actualizar_valor_pedido_mesa,
                ];
                $model = model('mesasModel');
                $actualizar = $model->set($data);
                $actualizar = $model->where('id', $id_mesa['fk_mesa']);
                $actualizar = $model->update();

                $id_tabla_producto_pedido = model('partirFacturaModel')->select('id_tabla_producto')->where('numero_de_pedido', $numero_pedido)->find();

                foreach ($id_tabla_producto_pedido as $detalle) {

                    $cantidad_partir_factura = model('partirFacturaModel')->select('cantidad_producto')->where('id_tabla_producto', $detalle['id_tabla_producto'])->first();
                    $cantidad_producto_pedido = model('productoPedidoModel')->select('cantidad_producto')->where('id', $detalle['id_tabla_producto'])->first();
                    if ($cantidad_partir_factura['cantidad_producto'] < $cantidad_producto_pedido['cantidad_producto']) {

                        $valor_unitario = model('productoPedidoModel')->select('valor_unitario')->where('id', $detalle['id_tabla_producto'])->first();
                        $nueva_cantidad_producto_pedido = $cantidad_producto_pedido['cantidad_producto'] - $cantidad_partir_factura['cantidad_producto'];

                        $valor_total = $valor_unitario['valor_unitario'] * $nueva_cantidad_producto_pedido;

                        $producto_pedido = [
                            'cantidad_producto' => $cantidad_producto_pedido['cantidad_producto'] - $cantidad_partir_factura['cantidad_producto'],
                            'valor_total' => $valor_total
                        ];
                        $model = model('productoPedidoModel');
                        $actualizar = $model->set($producto_pedido);
                        $actualizar = $model->where('id', $detalle['id_tabla_producto']);
                        $actualizar = $model->update();
                    } else {

                        $borrar_pedido = model('productoPedidoModel')->where('id', $detalle['id_tabla_producto']);
                        $borrar_pedido->delete();
                    }
                }

                $valor_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
                $cantidad_producto = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido)->find();


                $pedido = [
                    'valor_total' => $valor_pedido[0]['valor_total'],
                    'cantidad_de_productos' => $cantidad_producto[0]['cantidad_producto']

                ];
                $model = model('pedidoModel');
                $actualizar = $model->set($pedido);
                $actualizar = $model->where('id', $numero_pedido);
                $actualizar = $model->update();

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

                $total = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();


                $returnData = array(
                    "id_factura" => $id_factura,
                    "resultado" => 1,
                    "efectivo" => number_format(($efectivo + $transaccion), 0, ",", "."),
                    "cambio" => number_format(($efectivo + $transaccion) - $total[0]['valor_total'], 0, ",", "."),

                    //"total" =>  number_format($valorfactura_forma_pago['valor_total'], 0, ",", "."),
                    "id_regimen" => 1,
                    "total" => number_format($total[0]['valor_total'], 0, ",", "."),

                    //"total" => $total[0]['valor_total']
                    "numero_pedido" => $numero_pedido,
                );

                echo  json_encode($returnData);
                $borrar_pedido = model('partirFacturaModel')->where('numero_de_pedido', $numero_pedido);
                $borrar_pedido->delete();
            } else {
                $returnData = array(
                    "id_factura" => $id_factura,
                    "mensaje" => 0

                );
                echo  json_encode($returnData);
            }
        }
        if ($efectivo == 0 and $transaccion > 0) {

            $insert_transaccion = model('facturaFormaPagoModel')->insert($factura_forma_pago_transaccion);
            if ($insert_transaccion) {
                $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
                $valor_total_partir_factura = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
                $valor_pedido_mesa = model('mesasModel')->select('valor_pedido')->where('id', $id_mesa['fk_mesa'])->first();
                $actualizar_valor_pedido_mesa = $valor_pedido_mesa['valor_pedido'] - $valor_total_partir_factura[0]['valor_total'];

                $data = [
                    'valor_pedido' => $actualizar_valor_pedido_mesa,
                ];
                $model = model('mesasModel');
                $actualizar = $model->set($data);
                $actualizar = $model->where('id', $id_mesa['fk_mesa']);
                $actualizar = $model->update();

                $id_tabla_producto_pedido = model('partirFacturaModel')->select('id_tabla_producto')->where('numero_de_pedido', $numero_pedido)->find();

                foreach ($id_tabla_producto_pedido as $detalle) {

                    $cantidad_partir_factura = model('partirFacturaModel')->select('cantidad_producto')->where('id_tabla_producto', $detalle['id_tabla_producto'])->first();
                    $cantidad_producto_pedido = model('productoPedidoModel')->select('cantidad_producto')->where('id', $detalle['id_tabla_producto'])->first();
                    if ($cantidad_partir_factura['cantidad_producto'] < $cantidad_producto_pedido['cantidad_producto']) {

                        $valor_unitario = model('productoPedidoModel')->select('valor_unitario')->where('id', $detalle['id_tabla_producto'])->first();
                        $nueva_cantidad_producto_pedido = $cantidad_producto_pedido['cantidad_producto'] - $cantidad_partir_factura['cantidad_producto'];

                        $valor_total = $valor_unitario['valor_unitario'] * $nueva_cantidad_producto_pedido;

                        $producto_pedido = [
                            'cantidad_producto' => $cantidad_producto_pedido['cantidad_producto'] - $cantidad_partir_factura['cantidad_producto'],
                            'valor_total' => $valor_total
                        ];
                        $model = model('productoPedidoModel');
                        $actualizar = $model->set($producto_pedido);
                        $actualizar = $model->where('id', $detalle['id_tabla_producto']);
                        $actualizar = $model->update();
                    } else {

                        $borrar_pedido = model('productoPedidoModel')->where('id', $detalle['id_tabla_producto']);
                        $borrar_pedido->delete();
                    }
                }

                $valor_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
                $cantidad_producto = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido)->find();


                $pedido = [
                    'valor_total' => $valor_pedido[0]['valor_total'],
                    'cantidad_de_productos' => $cantidad_producto[0]['cantidad_producto']

                ];
                $model = model('pedidoModel');
                $actualizar = $model->set($pedido);
                $actualizar = $model->where('id', $numero_pedido);
                $actualizar = $model->update();

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

                $total = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();


                $returnData = array(
                    "id_factura" => $id_factura,
                    "resultado" => 1,
                    "efectivo" => number_format(($efectivo + $transaccion), 0, ",", "."),
                    "cambio" => number_format(($efectivo + $transaccion) - $total[0]['valor_total'], 0, ",", "."),

                    //"total" =>  number_format($valorfactura_forma_pago['valor_total'], 0, ",", "."),
                    "id_regimen" => 1,
                    "total" => number_format($total[0]['valor_total'], 0, ",", "."),

                    //"total" => $total[0]['valor_total']
                    "numero_pedido" => $numero_pedido,
                );

                echo  json_encode($returnData);
                $borrar_pedido = model('partirFacturaModel')->where('numero_de_pedido', $numero_pedido);
                $borrar_pedido->delete();
            } else {
                $returnData = array(
                    "id_factura" => $id_factura,
                    "mensaje" => 0

                );
                echo  json_encode($returnData);
            }
        }
    }

    public function cancelar_partir_factura()
    {
        $numero_pedido = $_POST['numero_de_pedido'];

        $borrar_producto_pedido = model('partirFacturaModel')->where('numero_de_pedido', $numero_pedido);
        $borrar_producto_pedido->delete();

        if ($borrar_producto_pedido) {
            $returnData = array(

                "resultado" => 0

            );
            echo  json_encode($returnData);
        }
    }
    public function actualizar_cantidad_tabla_partir_factura()
    {
        $id_tabla_partir_factura = $_REQUEST['id'];
        $cantidad = $_REQUEST['cantidad'];

        $valor_unitario = model('partirFacturaModel')->select('valor_unitario')->where('id', $id_tabla_partir_factura)->first();

        $data = [
            'cantidad_producto' => $cantidad,
            'valor_total' => $cantidad * $valor_unitario['valor_unitario']

        ];

        $model = model('partirFacturaModel');
        $actualizar_cantidad = $model->set($data);
        $actualizar_cantidad = $model->where('id', $id_tabla_partir_factura);
        $actualizar_cantidad = $model->update();

        if ($actualizar_cantidad) {

            $numero_pedido = $_REQUEST['numero_de_pedido'];
            $producto_partir_factura = model('partirFacturaModel')->productos($numero_pedido);

            $productos = view('partir_factura/partir_factura', [
                'productos' => $producto_partir_factura
            ]);

            $valor_total = model('partirFacturaModel')->select('valor_total')->where('id', $id_tabla_partir_factura)->first();
            $numero_pedido = model('partirFacturaModel')->select('numero_de_pedido')->where('id', $id_tabla_partir_factura)->first();

            $valor_total_pedido = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();

            $returnData = array(
                "resultado" => 1,
                // "cantidad" => $cantidad,
                //"valor_total_producto" => "$" . number_format($valor_total['valor_total'], 0, ",", "."),
                "valor_total_pedido" => "$" . number_format($valor_total_pedido[0]['valor_total'], 0, ",", "."),
                "productos" => $productos
            );
            echo  json_encode($returnData);
        }
    }
}
