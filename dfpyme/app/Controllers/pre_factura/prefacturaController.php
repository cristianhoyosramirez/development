<?php

namespace App\Controllers\pre_factura;

require APPPATH . "Controllers/mike42/autoload.php";

use App\Controllers\BaseController;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class prefacturaController extends BaseController
{
    public function index()
    {
        return view('home/home');
    }
    public function impresora()
    {

        $impresoras = model('impresorasModel')->select('*')->find();
        $id_impresora = model('precuentaModel')->select('id_impresora')->first();

        if (!empty($id_impresora)) {

            return view('pre_factura/pre_factura', [
                'impresoras' => $impresoras,
                'id_impresora' => $id_impresora['id_impresora']
            ]);
        } else {
            return view('pre_factura/asignar_impresora', [
                'impresoras' => $impresoras,
                'resultado' => 0
            ]);
        }
    }

    public function imprimir()
    {

        $numero_pedido = $_POST['numero_de_pedido_pre_factura'];
        $id_usuario = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();
        $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
        $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa['fk_mesa'])->first();
        $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario)->first();

        $id_impresora = model('precuentaModel')->select('id_impresora')->first();
        if (!empty($id_impresora)) {
            $nombre_de_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();
            $connector = new WindowsPrintConnector($nombre_de_impresora['nombre']);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text("PREFACTURA" . "\n");



            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $printer->text("Pedido N°" . $numero_pedido . "\n");
            $printer->text("Mesa N°" . $nombre_mesa['nombre'] . "\n");
            $printer->text("Mesero: " . $nombre_usuario['nombresusuario_sistema'] . "\n");

            $printer->text("Fecha :" . "   " . date('d/m/Y ') . "\n");
            $printer->text("Hora  :" . "   " . date('h:i:s a', time()) . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("----------------------------------------------- \n");
            $printer->text("CÓDIGO   PRODUCTO      CANTIDAD     NOTAS  \n");
            $printer->text("----------------------------------------------- \n");


            $items = model('productoPedidoModel')->pre_factura($numero_pedido);


            foreach ($items as $productos) {
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(1, 1);
                $printer->text("Cod." . $productos['codigointernoproducto'] . "      " . $productos['nombreproducto'] . "\n");
                $printer->text("Cant. " . $productos['cantidad_producto'] . "      " . "$" . number_format($productos['valor_unitario'], 0, ',', '.') . "                   " . "$" . number_format($productos['valor_total'], 0, ',', '.') . "\n");
                if (!empty($productos['nota_producto'])) {
                    $printer->setJustification(Printer::JUSTIFY_CENTER);
                    $printer->text("NOTAS:\n");
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text($productos['nota_producto'] . "\n");
                }
                $printer->text("_______________________________________________ \n");
                $printer->text("\n");
            }

            $observacion_general = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido)->first();
            if (!empty($observacion_general['nota_pedido'])) {
                $printer->setTextSize(2, 1);
                $printer->text("OBSERVACION GENERAL\n");
                $printer->text($observacion_general['nota_pedido'] . "\n");
            }

            $total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->setTextSize(2, 2);
            $printer->text("TOTAL :" . "$" . number_format($total['valor_total'], 0, ",", ".") . "\n");
            $printer->setTextSize(1, 1);
            $printer->text("---------------------------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("GRACIAS POR SU VISITA " . "\n");

            /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
            */
            $printer->cut();

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
            return redirect()->to(base_url('pedido/pedidos_para_facturar'))->with('mensaje', 'Prefactura correcta');
        } else if (empty($id_impresora)) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'error');
            return redirect()->to(base_url('pre_factura/impresora'))->with('mensaje', 'No hay impresora asignada  para imprimir la prefactura');
        }
    }

    public function imprimir_desde_pedido()
    {

        $numero_pedido = $_POST['numero_de_pedido_pre_factura'];

        $id_usuario = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();
        $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
        $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa['fk_mesa'])->first();
        $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario)->first();

        $id_impresora = model('precuentaModel')->select('id_impresora')->first();
        if (!empty($id_impresora)) {
            $nombre_de_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();
            $connector = new WindowsPrintConnector($nombre_de_impresora['nombre']);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text("PREFACTURA" . "\n");



            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $printer->text("Pedido N°" . $numero_pedido . "\n");
            $printer->text("Mesa N°" . $nombre_mesa['nombre'] . "\n");
            $printer->text("Mesero: " . $nombre_usuario['nombresusuario_sistema'] . "\n");

            $printer->text("Fecha :" . "   " . date('d/m/Y ') . "\n");
            $printer->text("Hora  :" . "   " . date('h:i:s a', time()) . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("----------------------------------------------- \n");
            $printer->text("CÓDIGO   PRODUCTO      CANTIDAD     NOTAS  \n");
            $printer->text("----------------------------------------------- \n");


            $items = model('productoPedidoModel')->pre_factura($numero_pedido);


            foreach ($items as $productos) {
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(1, 1);
                $printer->text("Cod." . $productos['codigointernoproducto'] . "      " . $productos['nombreproducto'] . "\n");
                $printer->text("Cant. " . $productos['cantidad_producto'] . "      " . "$" . number_format($productos['valor_unitario'], 0, ',', '.') . "                   " . "$" . number_format($productos['valor_total'], 0, ',', '.') . "\n");
                if (!empty($productos['nota_producto'])) {
                    $printer->setJustification(Printer::JUSTIFY_CENTER);
                    $printer->text("NOTAS:\n");
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text($productos['nota_producto'] . "\n");
                }
                $printer->text("_______________________________________________ \n");
                $printer->text("\n");
            }

            $observacion_general = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido)->first();
            if (!empty($observacion_general['nota_pedido'])) {
                $printer->setTextSize(2, 1);
                $printer->text("OBSERVACION GENERAL\n");
                $printer->text($observacion_general['nota_pedido'] . "\n");
            }

            $total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->setTextSize(2, 2);
            $printer->text("TOTAL :" . "$" . number_format($total['valor_total'], 0, ",", ".") . "\n");
            $printer->setTextSize(1, 1);
            $printer->text("---------------------------------------------" . "\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("GRACIAS POR SU VISITA " . "\n");

            /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
            */
            $printer->cut();

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
            $returnData = array(
                "resultado" => 1
            );
            echo  json_encode($returnData);
        } else if (empty($id_impresora)) {
            $returnData = array(
                "resultado" => 0
            );
            echo  json_encode($returnData);
        }
    }

    public function asignar_impresora()
    {
        $id_impresora = $_POST['id_impresora'];




        $id_de_impresora = model('precuentaModel')->select('id_impresora')->first();

        if (!empty($id_de_impresora['id_impresora'])) {


            $data = [
                'id_impresora' => $id_impresora,

            ];
            $model = model('precuentaModel');
            $pre_cuenta = $model->set($data);
            $pre_cuenta = $model->where('id', 1);
            $pre_cuenta = $model->update();


            if ($pre_cuenta) {
                $session = session();
                $session->setFlashdata('iconoMensaje', 'success');
                return redirect()->to(base_url('pre_factura/impresora'))->with('mensaje', 'Impresora asignada');
            } else {
                $session = session();
                $session->setFlashdata('iconoMensaje', 'error');
                return redirect()->to(base_url('pre_factura/impresora'))->with('mensaje', 'Error al asignar la impresora');
            }
        }
        if (empty($id_de_impresora['id_impresora'])) {
            $data = [
                'id_impresora' => $id_impresora
            ];

            $insertado = model('precuentaModel')->insert($data);
            if ($insertado) {
                $session = session();
                $session->setFlashdata('iconoMensaje', 'success');
                return redirect()->to(base_url('pre_factura/impresora'))->with('mensaje', 'Impresora asignada');
            }
        }
    }
    public function imprimir_prefactura()
    {
        $id_mesa = $_POST['id_mesa'];

        $numer_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();
        $numero_pedido = $numer_pedido['id'];
        $id_usuario = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();
        $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
        $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa['fk_mesa'])->first();
        $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario)->first();

        //$id_impresora = model('precuentaModel')->select('id_impresora')->first();
        if (!empty($id_mesa)) {
            // $nombre_de_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();
            $connector = new WindowsPrintConnector('FACTURACION');
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text("PREFACTURA" . "\n");



            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $printer->text("Pedido N°" . $numero_pedido . "\n");
            $printer->text("Mesa N°" . $nombre_mesa['nombre'] . "\n");
            $printer->text("Mesero: " . $nombre_usuario['nombresusuario_sistema'] . "\n");

            $printer->text("Fecha :" . "   " . date('d/m/Y ') . "\n");
            $printer->text("Hora  :" . "   " . date('h:i:s a', time()) . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("----------------------------------------------- \n");
            $printer->text("CÓDIGO   PRODUCTO      CANTIDAD     NOTAS  \n");
            $printer->text("----------------------------------------------- \n");


            $items = model('productoPedidoModel')->pre_factura($numero_pedido);


            foreach ($items as $productos) {
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(1, 1);
                $printer->text("Cod." . $productos['codigointernoproducto'] . "      " . $productos['nombreproducto'] . "\n");
                $printer->text("Cant. " . $productos['cantidad_producto'] . "      " . "$" . number_format($productos['valor_unitario'], 0, ',', '.') . "                   " . "$" . number_format($productos['valor_total'], 0, ',', '.') . "\n");
                if (!empty($productos['nota_producto'])) {
                    $printer->setJustification(Printer::JUSTIFY_CENTER);
                    $printer->text("NOTAS:\n");
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text($productos['nota_producto'] . "\n");
                }
                $printer->text("_______________________________________________ \n");
                $printer->text("\n");
            }

            $observacion_general = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido)->first();
            if (!empty($observacion_general['nota_pedido'])) {
                $printer->setTextSize(2, 1);
                $printer->text("OBSERVACION GENERAL\n");
                $printer->text($observacion_general['nota_pedido'] . "\n");
            }

            $total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->setTextSize(2, 2);
            $printer->text("TOTAL :" . "$" . number_format($total['valor_total'], 0, ",", ".") . "\n");
            $printer->setTextSize(1, 1);
            $printer->text("------------------------------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("GRACIAS POR SU VISITA " . "\n");

            /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
            */
            $printer->cut();

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
            $returnData = array(
                "resultado" => 1
            );
            echo  json_encode($returnData);
        } else if (empty($id_impresora)) {
            $returnData = array(
                "resultado" => 0
            );
            echo  json_encode($returnData);
        }
    }

    /* public function buscar_por_codigo()
    {
       

        if (!empty($buscar_producto_por_codigo_de_barras)) {
            $returnData = array(
                "resultado" => 1,
                "codigo_interno_producto" => $buscar_producto_por_codigo_de_barras[0]['codigointernoproducto'],
                "nombre_producto" => $buscar_producto_por_codigo_de_barras[0]['nombreproducto'],
                "valor_venta_producto" => number_format($buscar_producto_por_codigo_de_barras[0]['valorventaproducto'], 0, ",", "."),
            );
            echo  json_encode($returnData);
        } 

        
    }*/

    function buscar_por_codigo()
    {
        /**
         * Datos recibidos por ajax desde la vista de mesas 
         */
        //$id_mesa = 1;

        $codigo_de_barras = $_POST['codigo'];

        $buscar_producto_por_codigo_de_barras = model('productoModel')->buscar_producto_por_codigo_de_barras($codigo_de_barras);

        if (!empty($buscar_producto_por_codigo_de_barras)) {

            if (empty($this->request->getPost('id_mesa'))) {
                $temp_id_mesa = model('mesasModel')->select('id')->where('estado', 1)->first();
                $id_mesa = $temp_id_mesa['id'];
            }

            if (!empty($this->request->getPost('id_mesa'))) {

                $id_mesa = $this->request->getPost('id_mesa');
            }

            $id_mesero = $this->request->getPost('mesero');

            //$id_usuario = "";

            if (!empty($id_mesero)) {
                $id_usuario = $this->request->getPost('mesero');
            }

            if (empty($id_mesero)) {
                $id_usuario = $this->request->getPost('id_usuario');
            }


            $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa)->first();
            $estado_mesa = model('mesasModel')->select('estado')->where('id', $id_mesa)->first();

            //$id_usuario = 6;


            //$id_usuario = 15;
            //$id_producto = 2;
            //$id_producto = '207';
            //$id_producto = '10';
            $id_producto = (string) $buscar_producto_por_codigo_de_barras[0]['codigointernoproducto'];

            /**
             * Datos del producto
             */

            $se_imprime_en_comanda = model('productoModel')->select('se_imprime')->where('codigointernoproducto', $id_producto)->first();

            $codigo_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $id_producto)->first();
            $codigo_interno_producto = model('productoModel')->select('codigointernoproducto')->where('codigointernoproducto', $id_producto)->first();
            $valor_unitario = model('productoModel')->select('valorventaproducto')->where('codigointernoproducto', $id_producto)->first();

            $tiene_pedido = model('pedidoModel')->pedido_mesa($id_mesa);
            $numero_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();
            $estado_mesa = model('mesasModel')->select('estado')->where('id', $id_mesa)->first();


            if (empty($tiene_pedido)) {

                /**
                 * Insercion en la tabla de pedido
                 */


                $data = [
                    'fk_mesa' => $id_mesa,
                    'fk_usuario' => $id_usuario,
                    'valor_total' => $valor_unitario['valorventaproducto'],
                    'cantidad_de_productos' => 1,

                ];
                $insert = model('pedidoModel')->insert($data);

                /**
                 * Insertar en la tabla producto pedido 
                 */

                $ultimo_id_pedido = model('pedidoModel')->insertID;
                $producto_pedido = [
                    'numero_de_pedido' => $ultimo_id_pedido,
                    'cantidad_producto' => 1,
                    'nota_producto' => '',
                    'valor_unitario' => $valor_unitario['valorventaproducto'],
                    'impresion_en_comanda' => false,
                    'cantidad_entregada' => 0,
                    'valor_total' => $valor_unitario['valorventaproducto'],
                    'se_imprime_en_comanda' => $se_imprime_en_comanda['se_imprime'],
                    'codigo_categoria' => $codigo_categoria['codigocategoria'],
                    'codigointernoproducto' => $codigo_interno_producto['codigointernoproducto'],
                    'numero_productos_impresos_en_comanda' => 0,
                    'estado' => $estado_mesa['estado']
                ];


                $insertar = model('productoPedidoModel')->insertar(
                    $ultimo_id_pedido,
                    $valor_unitario['valorventaproducto'],
                    $se_imprime_en_comanda['se_imprime'],
                    $codigo_categoria['codigocategoria'],
                    $codigo_interno_producto['codigointernoproducto'],
                    1
                );


                $productos_pedido = model('productoPedidoModel')->producto_pedido($ultimo_id_pedido);
                $total_pedido = model('pedidoModel')->select('valor_total')->where('id', $ultimo_id_pedido)->first();
                $cantidad_de_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $ultimo_id_pedido)->first();

                // $ultimo_id_producto = model('productoPedidoModel')->insertID;
                $ultimo_id_producto = model('productoPedidoModel')->selectMax('id')->find();



                $returnData = array(
                    "resultado" => 1,
                    "id_mesa" => $id_mesa,
                    "numero_pedido" => $ultimo_id_pedido,
                    "productos_pedido" => view('pedidos/productos_pedido', [
                        "productos" => $productos_pedido,
                    ]),
                    "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),
                    "cantidad_de_pruductos" => $cantidad_de_productos['cantidad_de_productos'],
                    "id" => $ultimo_id_producto[0]['id'],
                    "estado" => $estado_mesa['estado'],
                    'nombre_mesa' => $nombre_mesa['nombre'],
                    'estado' => $estado_mesa['estado']

                );
                echo  json_encode($returnData);
            } else  if (!empty($tiene_pedido)) {
                $configuracion_pedido = model('configuracionPedidoModel')->select('agregar_item')->first();


                if ($configuracion_pedido['agregar_item'] == 0) {   // Actualiza el producto 


                    $existe_producto = model('productoPedidoModel')->cantidad_producto($numero_pedido['id'], $codigo_interno_producto['codigointernoproducto']);

                    if (empty($existe_producto)) {

                        $insertar = model('productoPedidoModel')->insertar(
                            $numero_pedido['id'],
                            $valor_unitario['valorventaproducto'],
                            $se_imprime_en_comanda['se_imprime'],
                            $codigo_categoria['codigocategoria'],
                            $codigo_interno_producto['codigointernoproducto']
                        );

                        $cantidad_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido['id'])->first();
                        $cant_productos = $cantidad_productos['cantidad_de_productos'] + 1;

                        $valor_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido['id'])->first();

                        $val_pedido = $valor_pedido['valor_total'] + $valor_unitario['valorventaproducto'];
                        $pedido = [
                            'valor_total' => $val_pedido,
                            'cantidad_de_productos' => $cant_productos,
                        ];

                        $model = model('pedidoModel');
                        $actualizar = $model->set($pedido);
                        $actualizar = $model->where('id', $numero_pedido['id']);
                        $actualizar = $model->update();

                        $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['id']);
                        $productos_del_pedido = view('productos_pedido/productos_pedido', [
                            "productos" => $productos_pedido,
                            "pedido" => $numero_pedido
                        ]);

                        $productos_del_pedido = view('pedidos/productos_pedido', [
                            "productos" => $productos_pedido,
                        ]);

                        $total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();
                        $cantidad_de_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido)->first();
                        $nota_pedido = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido)->first();
                        $ultimo_id_producto = model('productoPedidoModel')->insertID;
                        $returnData = array(
                            "resultado" => 1,
                            "id_mesa" => $id_mesa,
                            "numero_pedido" => $numero_pedido['id'],
                            "productos_pedido" => $productos_del_pedido,
                            "total_pedido" =>  "$" . number_format($total['valor_total'], 0, ',', '.'),
                            "cantidad_de_pruductos" => $cantidad_de_productos['cantidad_de_productos'],
                            "id" => $ultimo_id_producto,
                            'nombre_mesa' => $nombre_mesa['nombre'],
                            'estado' => $estado_mesa['estado']



                        );
                        echo  json_encode($returnData);
                    } else  if (!empty($existe_producto)) {

                        $cantidad_producto = model('productoPedidoModel')->cantidad_producto($numero_pedido['id'], $codigo_interno_producto['codigointernoproducto']);
                        $valor_total_producto = model('productoPedidoModel')->select('valor_total')->where('numero_de_pedido', $numero_pedido['id'])->first();
                        $actualizar_cantidad_producto = model('productoPedidoModel')->actualizar_cantidad_producto($numero_pedido['id'], $codigo_interno_producto['codigointernoproducto'], $cantidad_producto[0]['cantidad_producto'] + 1, '', 1000 + $valor_total_producto['valor_total']);

                        $valor_total_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['id'])->find();
                        $cantidades_totales = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido['id'])->find();

                        $data_pedido = [
                            'valor_total' => $valor_total_pedido[0]['valor_total'],
                            'cantidad_de_productos' => $cantidades_totales[0]['cantidad_producto']
                        ];

                        $model = model('pedidoModel');
                        $actualizar = $model->set($data_pedido);
                        $actualizar = $model->where('id', $numero_pedido['id']);
                        $actualizar = $model->update();


                        $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['id']);

                        $total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido['id'])->first();
                        $cantidad_de_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido['id'])->first();
                        $ultimo_id_producto = model('productoPedidoModel')->insertID;

                        $returnData = array(
                            "resultado" => 1,  // la mesa ya tiene productos
                            "productos_pedido" => view('pedidos/productos_pedido', [
                                "productos" => $productos_pedido,
                                "pedido" => $numero_pedido['id']
                            ]),
                            "total" => "$" . number_format($total['valor_total'], 0, ',', '.'),
                            "cantidad_de_productos" => $cantidad_de_productos['cantidad_de_productos'],
                            "numero_pedido" => $numero_pedido['id'],
                            "id_mesa" => $id_mesa,
                            "valor_total" => $total['valor_total'],
                            "id" => $ultimo_id_producto,
                            'nombre_mesa' => $nombre_mesa['nombre'],
                            'estado' => $estado_mesa['estado']
                        );
                        echo  json_encode($returnData);
                    }
                } else if ($configuracion_pedido['agregar_item'] == 1) {

                    $producto_pedido = [
                        'numero_de_pedido' => $numero_pedido,
                        'cantidad_producto' => 1,
                        'nota_producto' => '',
                        'valor_unitario' => $valor_unitario,
                        'impresion_en_comanda' => false,
                        'cantidad_entregada' => 0,
                        'valor_total' => $valor_unitario,
                        'se_imprime_en_comanda' =>  $se_imprime_en_comanda['se_imprime'],
                        'codigo_categoria' =>   $codigo_categoria['codigocategoria'],
                        'codigointernoproducto' => $codigo_interno_producto,
                        'numero_productos_impresos_en_comanda' => 0,
                        'estado' => $estado_mesa['estado']
                    ];
                    $insertar = model('productoPedidoModel')->insert($producto_pedido);


                    $cantidad_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido['id'])->first();

                    $cant_productos = $cantidad_productos['cantidad_de_productos'] + 1;

                    $valor_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido['id'])->first();
                    $val_pedido = $valor_pedido['valor_total'] + $valor_unitario['valorventaproducto'];
                    $pedido = [
                        'valor_total' => $val_pedido,
                        'cantidad_de_productos' => $cant_productos,
                    ];

                    $model = model('pedidoModel');
                    $actualizar = $model->set($pedido);
                    $actualizar = $model->where('id', $numero_pedido['id']);
                    $actualizar = $model->update();


                    $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['id']);
                    $total_pedido = $model->select('valor_total')->where('id', $numero_pedido['id'])->first();


                    $ultimo_id_producto = model('productoPedidoModel')->insertID;

                    $returnData = array(
                        "resultado" => 1,
                        "id_mesa" => $id_mesa,
                        "numero_pedido" => $numero_pedido['id'],
                        "productos_pedido" => view('pedidos/productos_pedido', [
                            "productos" => $productos_pedido,
                        ]),
                        "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),
                        //"cantidad_de_pruductos" => $cantidad_de_productos['cantidad_de_productos']
                        "id" => $ultimo_id_producto,
                        'nombre_mesa' => $nombre_mesa['nombre'],
                        'estado' => $estado_mesa['estado']
                    );
                    echo  json_encode($returnData);
                }
            }
        }if(empty($buscar_producto_por_codigo_de_barras)){
            $returnData = array(
                "resultado" => 0,
         

            );
            echo  json_encode($returnData);
        }
    }
}
