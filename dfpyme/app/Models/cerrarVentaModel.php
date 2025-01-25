<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Impuestos;
use App\Libraries\Inventario;

class cerrarVentaModel extends Model
{
    protected $table      = 'bancos';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['nombre'];

    function producto_pedido($productos, $factura_venta, $numero_pedido, $numero_factura, $fecha_y_hora, $tipo_pago, $id_usuario, $id_apertura, $estado)
    {

        $impuestos = new Impuestos();
        $inventario = new Inventario();
        $valor_unidad = "";

    

        foreach ($productos as $detalle) {

            // pago parcial
            if ($tipo_pago == 0) {
                $valor_unitario = model('productoPedidoModel')->select('valor_unitario')->where('id', $detalle['id_tabla_producto'])->first();
                $valor_unidad = $valor_unitario['valor_unitario'];

                $catidad_pedido = model('productoPedidoModel')->select('cantidad_producto')->where('id', $detalle['id_tabla_producto'])->first();

                $cantidad = $catidad_pedido['cantidad_producto'] - $detalle['cantidad_producto'];

                if ($cantidad == 0) {
                    $borrar_producto_pedido = model('productoPedidoModel')->where('id', $detalle['id_tabla_producto']);
                    $borrar_producto_pedido->delete();
                }

                if ($cantidad >= 1) {

                    $model = model('productoPedidoModel');
                    $actualizar = $model->set('cantidad_producto', $cantidad);
                    $actualizar = $model->set('valor_total', $cantidad * $valor_unidad);
                    $actualizar = $model->where('id', $detalle['id_tabla_producto']);
                    $actualizar = $model->update();
                }
            }

            //Pago completo 


            if ($tipo_pago == 1) {
                $valor_unitario = model('productoPedidoModel')->select('valor_unitario')->where('id', $detalle['id'])->first();
                $valor_unidad = $valor_unitario['valor_unitario'];
            }



            //Datos del producto 
            $codigo_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
            $id_tipo_inventario = model('productoModel')->select('id_tipo_inventario')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
            $id_medida = model('productoMedidaModel')->select('idvalor_unidad_medida')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();

            if (empty($id_medida)) {
                $medida = 3;
            }
            if (!empty($id_medida)) {
                $medida = $id_medida['idvalor_unidad_medida'];
            }

            $precio_costo = model('productoModel')->select('precio_costo')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();



            // Calcular los impuestos del producto 
            $calculo = $impuestos->calcular_impuestos($detalle['codigointernoproducto'], $detalle['valor_total'], $detalle['valor_unitario'], $detalle['cantidad_producto']);

            $numero_factura = model('pedidoModel')->select('numero_factura')->where('id', $numero_pedido)->first();

            /**
             * Consultar el tipo de inventario y descontarlo y actualizar el inventario 
             */



            $actualizar_inventario = $inventario->actualizar_inventario($detalle['codigointernoproducto'], $id_tipo_inventario['id_tipo_inventario'], $detalle['cantidad_producto'],'CORTESIAS',$factura_venta);

            $impuesto_saludable = model('productoModel')->select('valor_impuesto_saludable')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
            $id_saludable = model('productoModel')->select('id_impuesto_saludable')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();

            $producto_factura_venta = [
                'numerofactura_venta' => $numero_factura,
                'codigointernoproducto' => $detalle['codigointernoproducto'],
                'cantidadproducto_factura_venta' => $detalle['cantidad_producto'],
                'valorunitarioproducto_factura_venta' => $valor_unitario['valor_unitario'],
                //'idmedida' => $id_medida['idvalor_unidad_medida'],
                'idmedida' => $medida,
                'idcolor' => 0,
                'valor_descuento' => 0, //pendiente de ajuste
                'valor_recargo' => 0,
                'valor_iva' => $calculo[0]['base_iva'],
                'retorno' => false,
                'valor' => 0,
                'costo' => $precio_costo['precio_costo'],
                'id_factura' => $factura_venta,
                'valor_venta_real' =>  $valor_unidad,
                'impoconsumo' => 0,
                'total' => $valor_unidad * $detalle['cantidad_producto'],
                'valor_ico' => $calculo[0]['valor_ico'], //
                'impuesto_al_consumo' => $calculo[0]['ico'] / $detalle['cantidad_producto'],
                'iva' => $calculo[0]['iva'],
                'id_iva' => $calculo[0]['id_iva'],
                'aplica_ico' => $calculo[0]['aplica_ico'],
                'valor_total_producto' => $detalle['valor_unitario'],
                'fecha_y_hora_venta' => date("Y-m-d H:i:s"),
                'saldo' => 0,
                'fecha_y_hora_venta' => $fecha_y_hora,
                'fecha_venta' => date('Y-m-d'),
                'id_categoria' => $codigo_categoria['codigocategoria'],
                'id_impuesto_saludable' => $id_saludable['id_impuesto_saludable'],
                'valor_impuesto_saludable' => $impuesto_saludable['valor_impuesto_saludable']
            ];

            $insertar = model('productoFacturaVentaModel')->insert($producto_factura_venta);

            $costo = model('productoModel')->select('precio_costo')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();


            $id_pedido = model('kardexModel')->select('id_pedido')->where('id_pedido', $detalle['id'])->first();

            //if (empty($id_pedido['id_pedido'])) {

                $data_kardex = [
                    'idcompra' => 0,
                    'codigo' => $detalle['codigointernoproducto'],
                    'idusuario' => $id_usuario,
                    'idconcepto' => 10,
                    'numerodocumento' => $numero_factura,
                    'fecha' => date('Y-m-d'),
                    'hora' => date("H:i:s"),
                    'cantidad' => $detalle['cantidad_producto'],
                    'valor' => $valor_unidad,
                    'total' => $valor_unidad * $detalle['cantidad_producto'],
                    'fecha_y_hora_factura_venta' => $fecha_y_hora,
                    'id_categoria' => $codigo_categoria['codigocategoria'],
                    'id_apertura' => $id_apertura,
                    'valor_unitario' => $detalle['valor_unitario'],
                    'id_factura' => $factura_venta,
                    //'costo' => $costo['precio_costo'] * $detalle['cantidad_producto'],
                    'costo' => $costo['precio_costo'] * $detalle['cantidad_producto'],
                    'ico' => $calculo[0]['ico'],
                    'iva' => $calculo[0]['iva'],
                    'id_estado' => $estado,
                    'valor_ico' => $calculo[0]['valor_ico'],
                    'valor_iva' => $calculo[0]['valor_iva'],
                    'aplica_ico' => $calculo[0]['aplica_ico'],
                    //'id_pedido'=>$detalle['id']
                ];

                $insertar = model('kardexModel')->insert($data_kardex);
           // }

          /*   if ($tipo_pago==0){

                $data_kardex = [
                    'idcompra' => 0,
                    'codigo' => $detalle['codigointernoproducto'],
                    'idusuario' => $id_usuario,
                    'idconcepto' => 10,
                    'numerodocumento' => $numero_factura,
                    'fecha' => date('Y-m-d'),
                    'hora' => date("H:i:s"),
                    'cantidad' => $detalle['cantidad_producto'],
                    'valor' => $valor_unidad,
                    'total' => $valor_unidad * $detalle['cantidad_producto'],
                    'fecha_y_hora_factura_venta' => $fecha_y_hora,
                    'id_categoria' => $codigo_categoria['codigocategoria'],
                    'id_apertura' => $id_apertura,
                    'valor_unitario' => $detalle['valor_unitario'],
                    'id_factura' => $factura_venta,
                    //'costo' => $costo['precio_costo'] * $detalle['cantidad_producto'],
                    'costo' => $costo['precio_costo'] * $detalle['cantidad_producto'],
                    'ico' => $calculo[0]['ico'],
                    'iva' => $calculo[0]['iva'],
                    'id_estado' => $estado,
                    'valor_ico' => $calculo[0]['valor_ico'],
                    'valor_iva' => $calculo[0]['valor_iva'],
                    'aplica_ico' => $calculo[0]['aplica_ico'],
                    //'id_pedido'=>$numero_pedido
                ];

                $insertar = model('kardexModel')->insert($data_kardex);
                
            } */
        }
    }


    function actualiar_pedido_consecutivos($numero_pedido, $numero_factura, $consecutivo)
    {
        $num_fact = model('pedidoModel');
        $numero_factura = $num_fact->set($numero_factura);
        $numero_factura = $num_fact->where('id', $numero_pedido);
        $numero_factura = $num_fact->update();

        $model = model('consecutivosModel');
        $numero_factura = $model->set($consecutivo);
        $numero_factura = $model->where('idconsecutivos', 8);
        $numero_factura = $model->update();
    }
}
