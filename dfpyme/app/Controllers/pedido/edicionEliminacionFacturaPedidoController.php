<?php

namespace App\Controllers\pedido;

use App\Controllers\BaseController;

class edicionEliminacionFacturaPedidoController extends BaseController
{
    public function index()
    {
        return view('home/home');
    }

    public function edicion()
    {
        $id_tabla_producto = $_POST['id_tabla_producto'];
        $codigointernoproducto = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_tabla_producto)->first();
        $aplica_descuento = model('productoModel')->select('aplica_descuento')->where('codigointernoproducto', $codigointernoproducto['codigointernoproducto'])->first();

        if ($aplica_descuento['aplica_descuento'] == "f") {
            $producto = model('productoPedidoModel')->editar_y_eliminar_producto_pedido($id_tabla_producto);
            $returnData = array(
                "resultado" => 0,
                "codigo_interno" => $producto[0]['codigointernoproducto'],
                "descripcion" => $producto[0]['nombreproducto'],
                "valor_unitario" => $producto[0]['valor_unitario'],
                "valor_unitario_formato" => "$" . number_format($producto[0]['valor_unitario'], 0, ',', '.'),
                "cantidad" => $producto[0]['cantidad_producto'],
                "total" => $producto[0]['valor_total'],
                "notas" => $producto[0]['nota_producto'],
                "id_tabla_producto_pedido" => $id_tabla_producto
            );
            echo  json_encode($returnData);
        }
        if ($aplica_descuento['aplica_descuento'] == "t") {
            $producto = model('productoPedidoModel')->editar_y_eliminar_producto_pedido($id_tabla_producto);
            $returnData = array(
                "resultado" => 1,
                "codigo_interno" => $producto[0]['codigointernoproducto'],
                "descripcion" => $producto[0]['nombreproducto'],
                "valor_unitario" => $producto[0]['valor_unitario'],
                "valor_unitario_formato" => number_format($producto[0]['valor_unitario'], 0, ',', '.'),
                "cantidad" => $producto[0]['cantidad_producto'],
                "total" => $producto[0]['valor_total'],
                "notas" => $producto[0]['nota_producto'],
                "id_tabla_producto_pedido" => $id_tabla_producto
            );
            echo  json_encode($returnData);
        }
    }
    public function edicion_pos()
    {
        $id_tabla_producto = $_POST['id_tabla_producto'];

        $codigointernoproducto = model('productoPedidoPosModel')->select('codigointernoproducto')->where('id', $id_tabla_producto)->first();

        $aplica_descuento = model('productoModel')->select('aplica_descuento')->where('codigointernoproducto', $codigointernoproducto['codigointernoproducto'])->first();

        if ($aplica_descuento['aplica_descuento'] == "f") {
            $producto = model('productoPedidoModel')->editar_y_eliminar_producto_pedido_pos($id_tabla_producto);
            $returnData = array(
                "resultado" => 0,
                "codigo_interno" => $producto[0]['codigointernoproducto'],
                "descripcion" => $producto[0]['nombreproducto'],
                "valor_unitario" => $producto[0]['valor_unitario'],
                "valor_unitario_formato" => "$" . number_format($producto[0]['valor_unitario'], 0, ',', '.'),
                "cantidad" => $producto[0]['cantidad_producto'],
                "total" => number_format($producto[0]['valor_total'], 0, ',', '.'),
                "notas" => $producto[0]['nota_producto'],
                "id_tabla_producto_pedido" => $id_tabla_producto
            );
            echo  json_encode($returnData);
        }
        if ($aplica_descuento['aplica_descuento'] == "t") {
            $producto = model('productoPedidoModel')->editar_y_eliminar_producto_pedido_pos($id_tabla_producto);

            $returnData = array(
                "resultado" => 1,
                "codigo_interno" => $producto[0]['codigointernoproducto'],
                "descripcion" => $producto[0]['nombreproducto'],
                "valor_unitario" => $producto[0]['valor_unitario'],
                "valor_unitario_formato" => number_format($producto[0]['valor_unitario'], 0, ',', '.'),
                "cantidad" => $producto[0]['cantidad_producto'],
                "total" => $producto[0]['valor_total'],
                "notas" => $producto[0]['nota_producto'],
                "id_tabla_producto_pedido" => $id_tabla_producto
            );
            echo  json_encode($returnData);
        }
    }

    public function actualizar_producto_pedido()
    {
        $numero_pedido = $_POST['numero_pedido'];
        $id_tabla_producto = $_POST['id_tabla_producto'];
        $cantidad = $_POST['cantidad'];
        $valor_unitario = $_POST['valor_unitario'];
        $valor_total = $cantidad * $valor_unitario;

        $data = [
            'cantidad_producto' => $cantidad,
            'valor_total' => $valor_total
        ];

        $model = model('productoPedidoModel');
        $actualizar = $model->set($data);
        $actualizar = $model->where('id', $id_tabla_producto);
        $actualizar = $model->update();

        if ($actualizar) {
            $valor_total = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
            $cantidad_productos = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido)->find();
            $fk_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
            $actualizar_mesa = [
                'valor_pedido' => $valor_total[0]['valor_total']
            ];
            $model = model('mesasModel');
            $actualizar = $model->set($actualizar_mesa);
            $actualizar = $model->where('id', $fk_mesa['fk_mesa']);
            $actualizar = $model->update();

            $actualizar_pedido = [
                'valor_total' => $valor_total[0]['valor_total'],
                'cantidad_de_productos' => $cantidad_productos[0]['cantidad_producto']
            ];
            $modelo = model('pedidoModel');
            $actualizacion = $modelo->set($actualizar_pedido);
            $actualizacion = $modelo->where('id', $numero_pedido);
            $actualizacion = $modelo->update();

            $productos_del_pedido_para_facturar = model('productoPedidoModel')->productos_del_pedido_para_facturar($numero_pedido);
            $productos = view('facturar_pedido/facturar_pedido_pedido_tbody', [
                'productos' => $productos_del_pedido_para_facturar
            ]);

            $valor_total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();

            $returnData = array(
                "resultado" => 1,
                "productos" => $productos,
                "valor_total" => number_format($valor_total['valor_total'], 0, ",", "."),
                "valor_total_sin_formato" => $valor_total['valor_total'],
            );
            echo  json_encode($returnData);
        } else {
            echo "No actualizado ";
        }
    }
    public function actualizar_producto_pos()
    {

        $id_tabla_producto = $_POST['id_tabla_producto'];
        $cantidad = $_POST['cantidad'];
        $valor_unitario = $_POST['valor_unitario'];
        $valor_total = $cantidad * $valor_unitario;


        $data = [
            'cantidad_producto' => $cantidad,
            'valor_total' => $valor_total,
            'nota_producto' => $_REQUEST['nota']
        ];

        $model = model('productoPedidoPosModel');
        $actualizar = $model->set($data);
        $actualizar = $model->where('id', $id_tabla_producto);
        $actualizar = $model->update();

        if ($actualizar) {
            $pk_pedido_pos = model('productoPedidoPosModel')->select('pk_pedido_pos')->where('id', $id_tabla_producto)->first();

            $valor_total = model('productoPedidoPosModel')->selectSum('valor_total')->where('pk_pedido_pos', $pk_pedido_pos['pk_pedido_pos'])->find();

            $actualizar_pedido = [
                'valor_total' => $valor_total[0]['valor_total'],
            ];
            $modelo = model('pedidoPosModel');
            $actualizacion = $modelo->set($actualizar_pedido);
            $actualizacion = $modelo->where('id', $pk_pedido_pos['pk_pedido_pos']);
            $actualizacion = $modelo->update();

            $productos_pedido_pos = model('productoPedidoPosModel')->producto_pedido_pos($pk_pedido_pos['pk_pedido_pos']);
            $productos_del_pedido = view('productos_pedido_pos/productos_pedido', [
                "productos" => $productos_pedido_pos
            ]);

            $valor_total = model('pedidoPosModel')->select('valor_total')->where('id', $pk_pedido_pos['pk_pedido_pos'])->first();

            $returnData = array(
                "resultado" => 1,
                "productos" => $productos_del_pedido,
                "valor_total" => number_format($valor_total['valor_total'], 0, ",", "."),
                "valor_total_sin_formato" => $valor_total['valor_total'],
            );
            echo  json_encode($returnData);
        } else {
            echo "No actualizado ";
        }
    }


    public function actualizar_precio_pedido()
    {
        $numero_pedido = $_POST['numero_pedido'];
        $id_tabla_producto = $_POST['id_tabla_producto'];
        $cantidad = $_POST['cantidad'];
        $valor_unitario = $_POST['valor_unitario'];

        $valor_total = $cantidad * $valor_unitario;

        $actualizar_producto_pedido = [
            'valor_total' => $valor_total,
            'valor_unitario' => $valor_unitario,
            'cantidad_producto' => $cantidad
        ];
        $modelo = model('productoPedidoModel');
        $actualizacion = $modelo->set($actualizar_producto_pedido);
        $actualizacion = $modelo->where('id', $id_tabla_producto);
        $actualizacion = $modelo->update();

        if ($actualizacion) {
            $valor_total = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
            $cantidad_productos = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido)->find();
            $fk_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
            $actualizar_mesa = [
                'valor_pedido' => $valor_total[0]['valor_total']
            ];
            $model = model('mesasModel');
            $actualizar = $model->set($actualizar_mesa);
            $actualizar = $model->where('id', $fk_mesa['fk_mesa']);
            $actualizar = $model->update();

            $actualizar_pedido = [
                'valor_total' => $valor_total[0]['valor_total'],
                'cantidad_de_productos' => $cantidad_productos[0]['cantidad_producto']
            ];
            $modelo = model('pedidoModel');
            $actualizacion = $modelo->set($actualizar_pedido);
            $actualizacion = $modelo->where('id', $numero_pedido);
            $actualizacion = $modelo->update();

            $productos_del_pedido_para_facturar = model('productoPedidoModel')->productos_del_pedido_para_facturar($numero_pedido);
            $productos = view('facturar_pedido/facturar_pedido_pedido_tbody', [
                'productos' => $productos_del_pedido_para_facturar
            ]);

            $valor_total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();

            $returnData = array(
                "resultado" => 1,
                "productos" => $productos,
                "valor_total" => number_format($valor_total['valor_total'], 0, ",", "."),
                "valor_total_sin_formato" => $valor_total['valor_total'],
            );
            echo  json_encode($returnData);
        } else {
            echo "No se pudo actualizar ";
        }
    }

    public function eliminar_producto_pedido()
    {
        $id_tabla_producto = $_POST['id_tabla_producto'];

        $producto = model('productoPedidoModel')->editar_y_eliminar_producto_pedido($id_tabla_producto);
        $returnData = array(
            "resultado" => 1,
            "codigo_interno" => $producto[0]['codigointernoproducto'],
            "descripcion" => $producto[0]['nombreproducto'],
            "id_tabla_producto_pedido" => $id_tabla_producto
        );
        echo  json_encode($returnData);
    }

    public function borrar_producto()
    {
        $id_tabla_producto = $_POST['id_tabla_producto'];
        $numero_pedid = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_tabla_producto)->first();
        $numero_pedido = $numero_pedid['numero_de_pedido'];
        $borrar_producto_pedido = model('productoPedidoModel')->where('id', $id_tabla_producto);
        $borrar_producto_pedido->delete();

        if ($borrar_producto_pedido) {
            $valor_total = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
            $cantidad_productos = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido)->find();
            $fk_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
            $actualizar_mesa = [
                'valor_pedido' => $valor_total[0]['valor_total']
            ];
            $model = model('mesasModel');
            $actualizar = $model->set($actualizar_mesa);
            $actualizar = $model->where('id', $fk_mesa['fk_mesa']);
            $actualizar = $model->update();

            $actualizar_pedido = [
                'valor_total' => $valor_total[0]['valor_total'],
                'cantidad_de_productos' => $cantidad_productos[0]['cantidad_producto']
            ];
            $modelo = model('pedidoModel');
            $actualizacion = $modelo->set($actualizar_pedido);
            $actualizacion = $modelo->where('id', $numero_pedido);
            $actualizacion = $modelo->update();

            $productos_del_pedido_para_facturar = model('productoPedidoModel')->productos_del_pedido_para_facturar($numero_pedido);
            $productos = view('facturar_pedido/facturar_pedido_pedido_tbody', [
                'productos' => $productos_del_pedido_para_facturar
            ]);

            $valor_total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();

            $returnData = array(
                "resultado" => 1,
                "productos" => $productos,
                "valor_total" => number_format($valor_total['valor_total'], 0, ",", "."),
                "valor_total_sin_formato" => $valor_total['valor_total'],
            );
            echo  json_encode($returnData);
        }
    }

    public function actualizar_registro_factura_directa()
    {
        $id_tabla_producto = $_POST['id_tabla_producto'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];
        $precio_limpio = str_replace(".", "", $precio);

        $data = [
            'cantidad_producto' => $cantidad,
            'valor_unitario' => $precio_limpio,
            'valor_total' => $precio_limpio * $cantidad
        ];

        $model = model('productoPedidoPosModel');
        $actualizar = $model->set($data);
        $actualizar = $model->where('id', $id_tabla_producto);
        $actualizar = $model->update();

        if ($actualizar) {
            $numero_pedido = model('productoPedidoPosModel')->select('pk_pedido_pos')->where('id', $id_tabla_producto)->first();
            $valor_total = model('productoPedidoPosModel')->selectSum('valor_total')->where('pk_pedido_pos', $numero_pedido['pk_pedido_pos'])->find();

            $data = [
                'valor_total' => $valor_total[0]['valor_total']
            ];

            $model = model('pedidoPosModel');
            $actualizacion = $model->set($data);
            $actualizacion = $model->where('id', $numero_pedido['pk_pedido_pos']);
            $actualizacion = $model->update();

            if ($actualizacion) {

                $pk_pedido_pos = model('productoPedidoPosModel')->select('pk_pedido_pos')->where('id', $id_tabla_producto)->first();


                $productos_pedido_pos = model('productoPedidoPosModel')->producto_pedido_pos($pk_pedido_pos['pk_pedido_pos']);
                $productos_del_pedido = view('productos_pedido_pos/productos_pedido', [
                    "productos" => $productos_pedido_pos
                ]);

                $valor_total = model('pedidoPosModel')->select('valor_total')->where('id', $pk_pedido_pos['pk_pedido_pos'])->first();

                $codigointernoproducto = model('productoPedidoPosModel')->select('codigointernoproducto')->where('id', $id_tabla_producto)->first();
                $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigointernoproducto['codigointernoproducto'])->first();

                $returnData = array(
                    "resultado" => 1,
                    "productos" => $productos_del_pedido,
                    "valor_total" => number_format($valor_total['valor_total'], 0, ",", "."),
                    "valor_total_sin_formato" => $valor_total['valor_total'],
                    "nombre_producto" => $nombre_producto['nombreproducto']
                );
                echo  json_encode($returnData);
            }
        }
    }

    function ingresar_compra(){

        return view('compras/entrada');
    }
}
