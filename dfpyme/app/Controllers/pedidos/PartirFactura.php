<?php

namespace App\Controllers\pedidos;

use App\Controllers\BaseController;
use App\Libraries\Impuestos;

class PartirFactura extends BaseController
{
    public function index()
    {
        return view('home/home');
    }


    function partir_factura()
    {
        $id_tabla_partir_factura = $_REQUEST['id'];
        $cantidad = $_REQUEST['cantidad'];  //Se refiere al pago parcial 
        $cantidad_producto = $_REQUEST['cantidad_producto'];
        $id_mesa = $_REQUEST['id_mesa'];
        $numero_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();

        $valor_unitario = model('productoPedidoModel')->select('valor_unitario')->where('id', $id_tabla_partir_factura)->first();
        $codigo_interno = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_tabla_partir_factura)->first();
        $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno['codigointernoproducto'])->first();

        if ($cantidad_producto >= $cantidad) {
            $data = [
                'numero_de_pedido' => $numero_pedido['id'],
                'cantidad_producto' => $cantidad,
                'valor_unitario' => $valor_unitario['valor_unitario'],
                'valor_total' => $valor_unitario['valor_unitario'] * $cantidad,
                'codigointernoproducto' => $codigo_interno['codigointernoproducto'],
                'nombre_producto' => $nombre_producto['nombreproducto'],
                'id_tabla_producto' => $id_tabla_partir_factura

            ];

            $insertar = model('partirFacturaModel')->insert($data);

            if ($insertar) {

                $numero_pedido = $numero_pedido['id'];
                $producto_partir_factura = model('partirFacturaModel')->productos($numero_pedido);



                $returnData = array(
                    "resultado" => 1,

                );
                echo  json_encode($returnData);
            }
        } else {
            $returnData = array(
                "resultado" => 0,

            );
            echo  json_encode($returnData);
        }
    }

    function valor()
    {

        $id_usuario = $this->request->getPost('id_usuario');
        //$id_usuario = 6; 

        $rol = model('usuariosModel')->select('idtipo')->where('idusuario_sistema', $id_usuario)->first();
        if ($rol['idtipo'] == 1 or $rol['idtipo'] == 0) {
            $tiene_apertura = model('aperturaRegistroModel')->select('id')->first();

            $estado_licencia = model('configuracionPedidoModel')->select('estado_licencia')->first();

            if (!empty($tiene_apertura['id'])) {
                $impuestos = new Impuestos();


                $id_mesa = $this->request->getPost('id_mesa');
                //$id_mesa = 1;
                $base = 0;
                $tributos = 0;
                $factura_electronica = "";

                $valor_pedido = model('pedidoModel')->select('valor_total')->where('fk_mesa', $id_mesa)->first();
                $numero_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();

                $productos = model('productoPedidoModel')->where('numero_de_pedido', $numero_pedido['id'])->find();
                $totalBase = 0;
                foreach ($productos as $detalle) {

                    $calculo = $impuestos->calcular_impuestos($detalle['codigointernoproducto'], $detalle['valor_total'], $detalle['valor_unitario'], $detalle['cantidad_producto']);

                    foreach ($calculo as $valor) {

                        $totalBase += $valor['base_ico'] + $valor['base_iva'];
                    }
                }
                $maxVenta = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 48)->first();




                $requiere_factura_electronica = "";
                if ($totalBase >= $maxVenta['numeroconsecutivo']) {
                    $factura_electronica = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>El valor del documento supera el monto permitido para FACTURA POS, seleccione FACTURACIÓN ELECTRÓNICA </strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
                    $requiere_factura_electronica = "si";
                }
                if ($totalBase < $maxVenta['numeroconsecutivo']) {
                    $factura_electronica = "";
                    $requiere_factura_electronica = "no";
                }

                $returnData = array(
                    "resultado" => 1,
                    "total" => "Total: $" . number_format($valor_pedido['valor_total'], 0, ",", "."),
                    "sub_total" => "Sub total: $" . number_format($valor_pedido['valor_total'], 0, ",", "."),
                    "valor_total" => $valor_pedido['valor_total'],
                    "factura_electronica" => $factura_electronica,
                    "requiere_factura_electronica" => $requiere_factura_electronica,
                    "estado_licencia" => $estado_licencia['estado_licencia']
                );
                echo  json_encode($returnData);
            } else if (empty($tiene_apertura['id'])) {
                $returnData = array(
                    "resultado" => 0,
                );
                echo  json_encode($returnData);
            }
        } else if ($rol['idtipo'] != 1) {

            $returnData = array(
                "resultado" => 2,
            );
            echo  json_encode($returnData);
        }
    }

    function actualizar_cantidad_pago_parcial()
    {

        $cantidad_tabla_partir_factura = $this->request->getPost('cantidad');  // Cantidad actual en la tabla partir factura 

        $cantidad = $cantidad_tabla_partir_factura + 1;


        $id_tabla_partir_factura = $this->request->getPost('id_tabla_partir_factura');
        // $id_tabla_producto = 29;


        $numero_pedido = model('partirFacturaModel')->select('numero_de_pedido')->where('id', $id_tabla_partir_factura)->first();
        $id_producto = model('partirFacturaModel')->select('id_tabla_producto')->where('id', $id_tabla_partir_factura)->first();
        $id_producto = model('partirFacturaModel')->select('id_tabla_producto')->where('id', $id_tabla_partir_factura)->first();
        $cantidad_producto_pedido = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_producto['id_tabla_producto'])->first();



        if ($cantidad <= $cantidad_producto_pedido['cantidad_producto']) {

            $valor_unitario = model('partirFacturaModel')->select('valor_unitario')->where('id', $id_tabla_partir_factura)->first();

            $data = [
                'cantidad_producto' => $cantidad,
                'valor_total' => $cantidad * $valor_unitario['valor_unitario']

            ];

            $model = model('partirFacturaModel');
            $actualizar_cantidad = $model->set($data);
            $actualizar_cantidad = $model->where('id', $id_tabla_partir_factura);
            $actualizar_cantidad = $model->update();


            $productos = model('partirFacturaModel')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->orderBy('id', 'ASC')->findAll();
            $total = model('partirFacturaModel')->get_total($numero_pedido['numero_de_pedido']);

            $returnData = array(
                "resultado" => 1,
                "productos" => view('pedidos/productos_pedido_parcial', [
                    "productos" => $productos,
                ]),
                "total" => "Total $" . number_format($total[0]['valor_total'], 0, ',', '.')
            );
            echo  json_encode($returnData);
        }
    }

    function restar_partir_factura()
    {

        $cantidad_tabla_producto_partir = $this->request->getPost('cantidad');  // Cantidad actual en la tabla partir factura


        $cantidad = $cantidad_tabla_producto_partir - 1;


        $id_tabla_producto = $this->request->getPost('id_tabla_producto');

        $cantidad_producto = model('partirFacturaModel')->select('cantidad_producto')->where('id', $id_tabla_producto)->first();  // Cantidad actual en la tabla producto pedidio 

        $numero_pedido = model('partirFacturaModel')->select('numero_de_pedido')->where('id', $id_tabla_producto)->first();



        if ($cantidad < $cantidad_producto['cantidad_producto'] and $cantidad >= 0) {


            $valor_unitario = model('partirFacturaModel')->select('valor_unitario')->where('id', $id_tabla_producto)->first();

            $data = [
                'cantidad_producto' => $cantidad,
                'valor_total' => $cantidad * $valor_unitario['valor_unitario']

            ];

            $model = model('partirFacturaModel');
            $actualizar_cantidad = $model->set($data);
            $actualizar_cantidad = $model->where('id', $id_tabla_producto);
            $actualizar_cantidad = $model->update();


            $productos = model('partirFacturaModel')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->orderBy('id', 'asc')->findAll();
            $total = model('partirFacturaModel')->get_total($numero_pedido['numero_de_pedido']);



            $returnData = array(
                "resultado" => 1,
                "productos" => view('pedidos/productos_pedido_parcial', [
                    "productos" => $productos,
                ]),
                "total" => "Total $" . number_format($total[0]['valor_total'], 0, ',', '.')
            );
            echo  json_encode($returnData);
        }
    }

    function cancelar_pago_parcial()
    {

        $id_mesa = $this->request->getPost('id_mesa');
        $numero_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();

        /*   $model = model('partirFacturaModel');
        $borrar = $model->truncate(); */
/* 
        $model = model('partirFacturaModel');
        $borrar = $model->where('numero_de_pedido', $numero_pedido['id']);
        $borrar = $model->delete(); */

        $truncate = model('partirFacturaModel')->truncate();

        
            $returnData = array(
                "resultado" => 1,
            );
            echo  json_encode($returnData);
    
    }

    function valor_pago_parcial()
    {
        $impuestos = new Impuestos();

        $id_mesa = $this->request->getPost('id_mesa');
        //$id_mesa = 99;
        $base = 0;
        $tributos = 0;
        $factura_electronica = "";
        $requiere_factura_electronica = "";


        $numero_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();

        $productos = model('partirFacturaModel')->get_productos_pago_parcial($numero_pedido['id']);

        $totalBase = 0;
        foreach ($productos as $detalle) {

            $calculo = $impuestos->calcular_impuestos($detalle['codigointernoproducto'], $detalle['valor_total'], $detalle['valor_unitario'], $detalle['cantidad_producto']);

            foreach ($calculo as $valor) {

                $totalBase += $valor['base_ico'] + $valor['base_iva'];
            }
        }
        $maxVenta = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 48)->first();

        if ($totalBase >= $maxVenta['numeroconsecutivo']) {
            $factura_electronica = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>El valor del documento supera el monto permitido para FACTURA POS, seleccione FACTURACIÓN ELECTRÓNICA </strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
            $requiere_factura_electronica = "si";
        }
        if ($totalBase < $maxVenta['numeroconsecutivo']) {
            $factura_electronica = "";
            $requiere_factura_electronica = "no";
        }


        $total = model('partirFacturaModel')->get_total($numero_pedido['id']);
        $estado_licencia = model('configuracionPedidoModel')->select('estado_licencia')->first();
        $returnData = array(
            "resultado" => 1,

            "valor_total" => $total[0]['valor_total'],
            "factura_electronica" => $factura_electronica,
            "total" => "$" . number_format($total[0]['valor_total'], 0, ",", "."),
            "factura_electronica" => $factura_electronica,
            "requiere_factura_electronica" => $requiere_factura_electronica,
            "estado_licencia" => $estado_licencia['estado_licencia']
        );
        echo  json_encode($returnData);
    }
}
