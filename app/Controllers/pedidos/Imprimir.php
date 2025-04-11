<?php

namespace App\Controllers\pedidos;

use App\Controllers\BaseController;

require APPPATH . "Controllers/mike42/autoload.php";

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use App\Libraries\impresion;
use \DateTime;
use \DateTimeZone;



class Imprimir extends BaseController
{
    public function index()
    {
        return view('home/home');
    }


    function imprimirComanda()
    {

        //$id_mesa = 1;
        $id_mesa = $this->request->getPost('id_mesa');
        $id_usuario = $this->request->getPost('id_usuario');

        //$id_usuario = 6;

        $tipo_usuario = model('usuariosModel')->select('idtipo')->where('idusuario_sistema', $id_usuario)->first();
        $pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();
        $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa)->first();

        $configuracion_comanda = model('configuracionPedidoModel')->select('partir_comanda')->first();

        $impresion_comanda = model('configuracionPedidoModel')->select('comanda')->first();  //Reimpresion

        $productos = array();

        /**
         * Pasos para la impresion de la comanda 
         * 1. Validacion de que la me sa tenga pedido
         * 2. Validacion de que productos falta por imprimir   ( $productos_pedido)
         */


        if (!empty($pedido)) {
            $codigo_categoria = model('productoPedidoModel')->id_categoria($pedido['id']);
            $productos_pedido = $items = model('productoPedidoModel')->productos_pedido($pedido['id']);



            if (!empty($productos_pedido)) {



                foreach ($productos_pedido as $keyProductos) {

                    $id_impresora = model('productoModel')->select('id_impresora')->where('codigointernoproducto', $keyProductos['codigointernoproducto'])->first();



                    $model = model('productoPedidoModel');

                    $model->set('id_impresora', $id_impresora['id_impresora'])
                        ->where('codigointernoproducto', $keyProductos['codigointernoproducto'])
                        ->where('numero_de_pedido', $pedido['id'])
                        ->update();

                    /*  if ($tipo_usuario['idtipo'] == 2 || $tipo_usuario['idtipo'] == 3) {
                            $productos = model('productoPedidoModel')->productos_impresora($id_impresora['impresora']);
                        }
                        if ($tipo_usuario['idtipo'] == 1 || $tipo_usuario['idtipo'] == 0) {
                            $productos = model('productoPedidoModel')->GetProductosimpresora($id_impresora['impresora'],$pedido['id']);
                        } */
                }

                //$this->generar_comanda($productos, $pedido['id'], $nombre_mesa['nombre'], $keyCategoria['codigo_categoria'], 'Comanda');
                $impresoras = model('impresorasModel')->findAll();


                foreach ($impresoras as $keyImpresoras) {

                    $productos = model('productoPedidoModel')->productos_impresora($keyImpresoras['id']);

                    if (!empty($productos)) {
                        $this->generar_comanda_sin_partir($productos, $pedido['id'], $nombre_mesa['nombre'], $keyImpresoras['id']);
                    }
                }

                $returnData = array(
                    "resultado" => 1
                );
                echo  json_encode($returnData);
            }

            if (empty($productos_pedido)) {



                if ($impresion_comanda['comanda'] === "t") { // Todo debe imprimirse en la misma impresora 



                    if ($tipo_usuario['idtipo'] == 1 || $tipo_usuario['idtipo'] == 0) {

                        if ($configuracion_comanda['partir_comanda'] == 't') {

                            foreach ($codigo_categoria as $valor) {


                                //$items = model('productoPedidoModel')->reimprimir_comanda($pedido['id'], $valor['codigo_categoria']);

                                foreach ($items as $detalle) {
                                    $data['id'] = $detalle['id'];
                                    $data['nombreproducto'] = $detalle['nombreproducto'];
                                    $data['valor_venta'] = $detalle['valorventaproducto'];
                                    $data['valor_total'] = $detalle['valor_total'];
                                    $data['cantidad'] = $detalle['cantidad_producto'];
                                    $data['nota_producto'] = $detalle['nota_producto'];
                                    $data['valor_unitario'] = $detalle['valor_unitario'];
                                    $data['codigo_interno'] = $detalle['codigointernoproducto'];
                                    $data['impresos'] = $detalle['numero_productos_impresos_en_comanda'];
                                    array_push($productos, $data);
                                }

                                $this->generar_comanda($productos, $pedido['id'], $nombre_mesa['nombre'], $valor['codigo_categoria'], 'Comanda');
                                $productos = array();
                            }
                        }
                        if ($configuracion_comanda['partir_comanda'] == 'f') {


                            /*   $items = model('productoPedidoModel')->reimprimir_comanda_todo($pedido['id']);

                            foreach ($items as $detalle) {
                                $data['id'] = $detalle['id'];
                                $data['nombreproducto'] = $detalle['nombreproducto'];
                                $data['valor_venta'] = $detalle['valorventaproducto'];
                                $data['valor_total'] = $detalle['valor_total'];
                                $data['cantidad'] = $detalle['cantidad_producto'];
                                $data['nota_producto'] = $detalle['nota_producto'];
                                $data['valor_unitario'] = $detalle['valor_unitario'];
                                $data['codigo_interno'] = $detalle['codigointernoproducto'];
                                $data['impresos'] = $detalle['numero_productos_impresos_en_comanda'];
                                array_push($productos, $data);
                            }
                            $this->generar_comanda($productos, $pedido['id'], $nombre_mesa['nombre'], '1');
                            $productos = array(); */

                            /*   $impresoras = model('impresorasModel')->findAll();


                            foreach ($impresoras as $keyImpresoras) {

                                $productos = model('productoPedidoModel')->productos_impresora($keyImpresoras['id']);

                                if (!empty($productos)) {
                                    //$this->generar_comanda_sin_partir($productos, $pedido['id'], $nombre_mesa['nombre'], $keyImpresoras['id']);
                                }
                            } */
                            /*  $impresoras = model('impresorasModel')->findAll();



                            foreach ($impresoras as $keyImpresoras) {

                                $productos = model('productoPedidoModel')->productosReImpresion($keyImpresoras['id']);

                                if (!empty($productos)) {
                                    $this->generar_comanda_sin_partir($productos, $pedido['id'], $nombre_mesa['nombre'], $keyImpresoras['id']);
                                }
                            } */
                            /*   foreach ($codigo_categoria as $valor) {


                                $items = model('productoPedidoModel')->reimprimir_comanda($pedido['id'], $valor['codigo_categoria']);

                                foreach ($items as $detalle) {
                                    $data['id'] = $detalle['id'];
                                    $data['nombreproducto'] = $detalle['nombreproducto'];
                                    $data['valor_venta'] = $detalle['valorventaproducto'];
                                    $data['valor_total'] = $detalle['valor_total'];
                                    $data['cantidad'] = $detalle['cantidad_producto'];
                                    $data['nota_producto'] = $detalle['nota_producto'];
                                    $data['valor_unitario'] = $detalle['valor_unitario'];
                                    $data['codigo_interno'] = $detalle['codigointernoproducto'];
                                    $data['impresos'] = $detalle['numero_productos_impresos_en_comanda'];
                                    array_push($productos, $data);
                                }
                                $this->generar_comanda($productos, $pedido['id'], $nombre_mesa['nombre'], $valor['codigo_categoria']);
                                $productos = array();
                            } */
                        }

                        $categoria = model('productoPedidoModel')->getCategorias($pedido['id']);


                        foreach ($categoria as $keyCategoria) {
                            $id_impresora = model('categoriasModel')->select('impresora')->where('codigocategoria', $keyCategoria['codigo_categoria'])->first();

                            $model = model('productoPedidoModel');

                            $model->set('id_impresora', $id_impresora['impresora'])
                                ->where('codigo_categoria', $keyCategoria['codigo_categoria'])
                                ->where('numero_de_pedido', $pedido['id'])
                                ->update();


                            $productos = model('productoPedidoModel')->productosReImpresion($id_impresora['impresora'], $pedido['id']);
                        }
                        //$this->generar_comanda($productos, $pedido['id'], $nombre_mesa['nombre'], $keyCategoria['codigo_categoria'], 'Reimpresion comanda');
                        //$impresoras = model('impresorasModel')->findAll();





                        $returnData = array(
                            "resultado" => 1
                        );
                        echo  json_encode($returnData);
                    } else if ($tipo_usuario['idtipo'] == 2) {
                        $returnData = array(
                            "resultado" => 0
                        );
                        echo  json_encode($returnData);
                    }
                }




                if ($impresion_comanda['comanda'] === "f") {
                    $returnData = array(
                        "resultado" => 0
                    );
                    echo  json_encode($returnData);
                }
            }
        }
    }


    function generar_comanda($productos, $numero_pedido, $nombre_mesa, $codigo_categoria, $tipoImpresion)

    {

        $nombre_categoria = model('categoriasModel')->select('nombrecategoria')->where('codigocategoria', $codigo_categoria)->first();

        // $impresora = model('categoriasModel')->select('impresora')->where('codigocategoria', $codigo_categoria)->first();
        $id_impresora = model('categoriasModel')->select('impresora')->where('codigocategoria', $codigo_categoria)->first();
        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora)->first();
        $id_usuario = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();
        $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario['fk_usuario'])->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);


        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text("**" .  $tipoImpresion . "**" . "\n\n");
        $printer->text("**" .  $nombre_categoria['nombrecategoria'] . "**" . "\n\n");



        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setTextSize(1, 2);
        $printer->text("Pedido: " . $numero_pedido . "       Mesa: " . $nombre_mesa . "\n\n");
        $printer->setTextSize(1, 1);
        $printer->text("Mesero: " . $nombre_usuario['nombresusuario_sistema'] . "\n");

        $printer->text("Fecha :" . "   " . date('d/m/Y ') . "Hora  :" . "   " . date('h:i:s a', time()) . "\n\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);

        foreach ($productos as $productos) {


            $cantidad_productos_impresos = model('productoPedidoModel')->select('numero_productos_impresos_en_comanda')->where('id', $productos['id'])->first();
            $cantidad_productos = model('productoPedidoModel')->select('cantidad_producto')->where('id', $productos['id'])->first();



            $data = [
                'numero_productos_impresos_en_comanda' => $cantidad_productos_impresos['numero_productos_impresos_en_comanda'] + ($cantidad_productos['cantidad_producto'] - $cantidad_productos_impresos['numero_productos_impresos_en_comanda']),
            ];

            $actualizar = model('productoPedidoModel')->set($data);
            $actualizar = model('productoPedidoModel')->where('id', $productos['id']);
            $actualizar = model('productoPedidoModel')->update();
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(2, 1);
            $printer->text($productos['nombreproducto'] . "\n");


            if (($cantidad_productos['cantidad_producto'] - $cantidad_productos_impresos['numero_productos_impresos_en_comanda']) > 0) {
                $printer->text("Cant. " . $cantidad_productos['cantidad_producto'] - $cantidad_productos_impresos['numero_productos_impresos_en_comanda'] . "\n");
            }
            if (($cantidad_productos['cantidad_producto'] == $cantidad_productos_impresos['numero_productos_impresos_en_comanda'])) {
                $printer->text("Cant. " . $cantidad_productos['cantidad_producto'] . "\n");
            }
            if (!empty($productos['nota_producto'])) {
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("NOTAS:\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text($productos['nota_producto'] . "\n");
            }
            $printer->text("-----------------------\n");
            $printer->text("\n");
        }
        $observaciones_genereles = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido)->first();
        if (!empty($observaciones_genereles['nota_pedido'])) {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 1);
            $printer->text("OBSERVACIONES GENERALES \n");
            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(2, 1);
            $printer->text($observaciones_genereles['nota_pedido'] . "\n");
            /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
            */
            //$printer->cut();

            /*
            Por medio de la impresora mandamos un pulso.
            Esto es útil cuando la tenemos conectada
            por ejemplo a un cajón
            */
            //$printer->pulse();
            //$printer->close();
            # $printer = new Printer($connector);

            //$milibreria = new Ejemplolibreria();
            //$data = $milibreria->getRegistros();
        }
        $printer->cut();

        $printer->close();
    }
    function generar_comanda_sin_partir($productos, $numero_pedido, $nombre_mesa, $id_impresora)

    {



        // $impresora = model('categoriasModel')->select('impresora')->where('codigocategoria', $codigo_categoria)->first();

        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora)->first();
        $id_usuario = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();
        $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario['fk_usuario'])->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);


        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);




        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setTextSize(1, 2);
        $printer->text("Pedido: " . $numero_pedido . "       Mesa: " . $nombre_mesa . "\n\n");
        $printer->setTextSize(1, 1);
        $printer->text("Mesero: " . $nombre_usuario['nombresusuario_sistema'] . "\n");

        $printer->text("Fecha :" . "   " . date('d/m/Y ') . "Hora  :" . "   " . date('h:i:s a', time()) . "\n\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);

        foreach ($productos as $productos) {


            $cantidad_productos_impresos = model('productoPedidoModel')->select('numero_productos_impresos_en_comanda')->where('id', $productos['id'])->first();
            $cantidad_productos = model('productoPedidoModel')->select('cantidad_producto')->where('id', $productos['id'])->first();



            $data = [
                'numero_productos_impresos_en_comanda' => $cantidad_productos_impresos['numero_productos_impresos_en_comanda'] + ($cantidad_productos['cantidad_producto'] - $cantidad_productos_impresos['numero_productos_impresos_en_comanda']),
            ];

            $actualizar = model('productoPedidoModel')->set($data);
            $actualizar = model('productoPedidoModel')->where('id', $productos['id']);
            $actualizar = model('productoPedidoModel')->update();
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(2, 1);
            $printer->text($productos['nombreproducto'] . "\n");


            if (($cantidad_productos['cantidad_producto'] - $cantidad_productos_impresos['numero_productos_impresos_en_comanda']) > 0) {
                $printer->text("Cant. " . $cantidad_productos['cantidad_producto'] - $cantidad_productos_impresos['numero_productos_impresos_en_comanda'] . "\n");
            }
            if (($cantidad_productos['cantidad_producto'] == $cantidad_productos_impresos['numero_productos_impresos_en_comanda'])) {
                $printer->text("Cant. " . $cantidad_productos['cantidad_producto'] . "\n");
            }

            $atributos = model('atributosDeProductoModel')->getAtributosPedido($productos['id']);
            if (!empty($atributos)) {
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("\n");
                foreach ($atributos as $keyAtributos) {
                    $componentes = model('atributosDeProductoModel')->getComponentes($productos['id'], $keyAtributos['id_atributo']);

                    // Almacenar los nombres de los componentes en un array
                    $componentesNombres = [];

                    foreach ($componentes as $keyComponentes) {
                        $componentesNombres[] = $keyComponentes['nombre'];
                    }

                    // Construir la línea con el formato requerido
                    $printer->setTextSize(1, 1);
                    $printer->text("* " . $keyAtributos['nombre'] . " (" . implode("- ", $componentesNombres) . ")\n");

                }
            }

            $printer->text("\n");



            if (!empty($productos['nota_producto'])) {
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("NOTAS:\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setEmphasis(true);
                $printer->text($productos['nota_producto'] . "\n");
                $printer->setEmphasis(false);
            }
            $printer->text("----------------------------------------------\n");
            $printer->text("\n");
        }
        $observaciones_genereles = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido)->first();
        if (!empty($observaciones_genereles['nota_pedido'])) {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 1);
            $printer->text("*** NOTA GENERAL *** \n");
            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(2, 1);
            $printer->text($observaciones_genereles['nota_pedido'] . "\n");
            /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
            */
            //$printer->cut();

            /*
            Por medio de la impresora mandamos un pulso.
            Esto es útil cuando la tenemos conectada
            por ejemplo a un cajón
            */
            //$printer->pulse();
            //$printer->close();
            # $printer = new Printer($connector);

            //$milibreria = new Ejemplolibreria();
            //$data = $milibreria->getRegistros();
        }
        $printer->cut();

        $printer->close();
    }

    public function imprimir_prefactura()
    {

        $id_mesa = $this->request->getPost('id_mesa');
        //$id_mesa = 418;
        $propina = $this->request->getPost('propina');
        $pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();
        $numero_pedido = $pedido['id'];

        $id_usuario = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();
        $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
        $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa['fk_mesa'])->first();
        $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario['fk_usuario'])->first();
        $id_mesero = model('pedidoModel')->select('fk_usuario')->where('id', $id_mesa['fk_mesa'])->first();

        //$nombre_mesero = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_mesero['fk_usuario'])->first();

        $id_impresora = model('cajaModel')->select('id_impresora')->first();
        if (!empty($id_impresora)) {
            $nombre_de_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();
            $connector = new WindowsPrintConnector($nombre_de_impresora['nombre']);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text("Orden de pedido " . "\n");



            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $printer->text("Pedido N°   " . $numero_pedido . "\n");
            $printer->text("Mesa   N°   " . $nombre_mesa['nombre'] . "\n");
            if (!empty($nombre_mesero)) {
                $printer->text("Mesero:     " .    $nombre_mesero['nombresusuario_sistema'] . "\n");
            }
            if (empty($nombre_mesero)) {
                $printer->text("Mesero:     " .    $nombre_usuario['nombresusuario_sistema'] . "\n");
            }


            $printer->text("Fecha :  " . "   " . date('d/m/Y ') . "\n");
            $printer->text("Hora  :  " .  "   " .     date('h:i:s a', time()) . "\n");
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
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(2, 1);
            $printer->setTextSize(1, 1);
            $printer->text("SUB TOTAL  :" . "$" . number_format($total['valor_total'], 0, ",", ".") . "\n");
            $printer->text("PROPINA    :" . "$" . number_format($propina, 0, ",", ".") . "\n");
            $printer->text("---------------------------------------------" . "\n");

            $printer->setTextSize(2, 2);
            $printer->text("TOTAL      :" . "$" . number_format($total['valor_total'] + $propina, 0, ",", ".") . "\n");
            $printer->setTextSize(1, 1);
            $printer->text("---------------------------------------------" . "\n");

            $printer->text("Este establecimiento sugiere un aporte de servicio voluntario del 10% del valor de la cuenta. Usted puede aceptarlo, rechazarla o modificarlo según su valoración del servicio. 
Este monto se distribuye al 100% entre el personal de servicio según el reglamento interno.
" . "\n");

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


    public function imprimir_factura()
    {

        //$id_factura = 35;

        $id_factura = $_POST['numero_de_factura'];

        $imp = new impresion();
        $impresion = $imp->imprimir_factura($id_factura);

        $imprime_boucher = model('cajaModel')->select('imp_comprobante_transferencia')->where('numerocaja', 1)->first();



        if ($imprime_boucher['imp_comprobante_transferencia'] == 1) {
            $movimientos_transaccion = model('pagosModel')->pago_transferencia($id_factura);
            $movimientos_efectivo = model('pagosModel')->pago_efectivo($id_factura);


            if (!empty($movimientos_transaccion)) {

                $imprimir = $imp->imprimir_comprobnate_transferencia($id_factura, $movimientos_transaccion[0]['recibido_transferencia'], $movimientos_efectivo[0]['recibido_efectivo'], $movimientos_efectivo[0]['total_pago']);
            }
        }
    }


    function imprimir_movimiento_caja()
    {


        $id_apertura = $this->request->getPost('id_apertura');

        $imp = new impresion();

        $impresion = $imp->imprimir_cuadre_caja($id_apertura);
    }

    function lista_electronicas()
    {

        $facturas_electronicas = model('facturaElectronicaModel')->orderBy('id', 'desc')->findAll();



        return view('duplicado_de_factura/factura_electronica', [
            'facturas' => $facturas_electronicas
        ]);
    }

    function imprimir_factura_electronica()
    {
        $imp = new impresion();


        $id_factura = $this->request->getPost('id_factura');
        //$id_factura = 2;

        $id_resolucion = model('facturaElectronicaModel')->select('id_resolucion')->where('id', $id_factura)->first();

        $datos_resolucion = model('resolucionElectronicaModel')->where('id', $id_resolucion['id_resolucion'])->first();

        if (!empty($datos_resolucion)) {

            $impresion = $imp->impresion_factura_electronica($id_factura);
        }
        if (empty($datos_resolucion)) {

            $impresion = $imp->imprimir_factura_electronica($id_factura);
        }
    }

    function impresion_factura_electronica()
    {

        $imp = new impresion();

        $id_factura = $this->request->getPost('id_factura');
        //$id_factura = 32;

        $id_resolucion = model('facturaElectronicaModel')->select('id_resolucion')->where('id', $id_factura)->first();

        $datos_resolucion = model('resolucionElectronicaModel')->where('id', $id_resolucion['id_resolucion'])->first();

        if (!empty($datos_resolucion)) {

            $impresion = $imp->impresion_factura_electronica($id_factura);
        }
        if (empty($datos_resolucion)) {


            $impresion = $imp->imprimir_factura_electronica($id_factura);
        }
    }

    function detalle_f_e()
    {
        $id_factura = $this->request->getPost('id_factura');
        $items = model('itemFacturaElectronicaModel')->where('id_de', $id_factura)->findAll();

        $total = model('facturaElectronicaModel')->select('total')->where('id', $id_factura)->first();
        $numero = model('facturaElectronicaModel')->select('numero')->where('id', $id_factura)->first();
        $nit_cliente = model('facturaElectronicaModel')->select('nit_cliente')->where('id', $id_factura)->first();
        $nit_cliente = model('facturaElectronicaModel')->select('nit_cliente')->where('id', $id_factura)->first();
        $fecha = model('facturaElectronicaModel')->select('fecha')->where('id', $id_factura)->first();
        $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();


        $returnData = array(
            "resultado" => 1,
            "f_e" => view('consultas/detalle_factura_electronica', [
                'productos' => $items,
                'numero' => $numero['numero'],
                'nombre' => $nombre_cliente['nombrescliente'],
                'fecha' => $fecha['fecha']

            ]),
            "total" => "Total $ " . number_format($total['total'], 0, ',', '.')
        );
        echo  json_encode($returnData);
    }




    function reporte_ventas()
    {
        //var_dump($this->request->getPost()); exit();
        $id_apertura = $this->request->getPost('id_apertura');
        //$id_apertura = 12;
        $id_cierre = $this->request->getPost('id_cierre');

        /*  $imp = new impresion();
        $impresion = $imp->imp_reporte_ventas($id_cierre); */


        if (!empty($id_apertura)) {
            $imp = new impresion();
            $impresion = $imp->imp_reporte_ventas($id_apertura);
        }
        if (!empty($id_cierre)) {
            $apertura = model('cierreModel')->select('idapertura')->where('id', $id_cierre)->first();
            $imp = new impresion();
            $impresion = $imp->imp_reporte_ventas($apertura['idapertura']);
        }
    }

    function reporte_ventasSinCantidades()
    {
        //var_dump($this->request->getPost()); exit();
        $id_apertura = $this->request->getPost('id_apertura');
        //$id_apertura = 12;
        $id_cierre = $this->request->getPost('id_cierre');

        /*  $imp = new impresion();
        $impresion = $imp->imp_reporte_ventas($id_cierre); */


        if (!empty($id_apertura)) {
            $imp = new impresion();
            $impresion = $imp->imp_reporte_ventasSinCantidades($id_apertura);
        }
        if (!empty($id_cierre)) {
            $apertura = model('cierreModel')->select('idapertura')->where('id', $id_cierre)->first();
            $imp = new impresion();
            $impresion = $imp->imp_reporte_ventasSinCantidades($apertura['idapertura']);
        }
    }



    function imprimir_fiscal()
    {

        $id_apertura = $this->request->getPost('id_apertura');
        $id_usuario = $this->request->getPost('id_usuario');
        $nombreUsuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario)->first();
        //$id_apertura = 38;

        $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();
        $datos_empresa = model('empresaModel')->datosEmpresa();

        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();



        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);





        $fecha_y_hora_cierre = "";
        $ventas_credito = "";

        /**
         * Datos empresa 
         */

        $datos_empresa = model('empresaModel')->find();
        $id_regimen = $datos_empresa[0]['idregimen'];
        $regimen = model('regimenModel')->select('descripcion')->where('idregimen', $id_regimen)->first();
        $nombre_ciudad = model('ciudadModel')->select('nombreciudad')->where('idciudad', $datos_empresa[0]['idciudad'])->first();
        $nombre_departamento = model('departamentoModel')->select('nombredepartamento')->where('iddepartamento', $datos_empresa[0]['iddepartamento'])->first();

        /**
         * Datos de hora y aperura de la caja que se esta consultando 
         * 1. Si la caja no esta cerrada se le asigna la fecha y hora actual  
         */
        $fecha_y_hora_apertura = model('aperturaModel')->select('fecha_y_hora_apertura')->where('id', $id_apertura)->first();
        $fecha_cierre = model('cierreModel')->select('fecha_y_hora_cierre')->where('idapertura', $id_apertura)->first();

        if (empty($fecha_cierre['fecha_y_hora_cierre'])) {
            $fecha_y_hora_cierre = date('Y-m-d H:i:s');
        } else if (!empty($fecha_cierre['fecha_y_hora_cierre'])) {
            $fecha_y_hora_cierre = $fecha_cierre['fecha_y_hora_cierre'];
        }
        /**
         * Registro inicial es la primer factura que se realiza de esa aperturta 
         * Registro final es la primer factura que se realiza de esa aperturta  
         */

        $id_inicial = model('pagosModel')->get_min_id_electronico($id_apertura);
        $id_final = model('pagosModel')->get_max_id_electronico($id_apertura);

        if (!empty($id_inicial[0]['id']) and !empty($id_final[0]['id'])) {
            //$total_registros = model('pagosModel')->get_total_registros_electronicos($id_apertura);
            $total_registros = model('pagosModel')->get_total_registros_electronicos($id_inicial[0]['id'], $id_final[0]['id']);
            $reg_inicial = model('facturaElectronicaModel')->select('numero')->where('id', $id_inicial[0]['id'])->first();
            $fecha_hora_inicial = model('facturaElectronicaModel')->select('fecha,hora')->where('id', $id_inicial[0]['id'])->first();


            $reg_final = model('facturaElectronicaModel')->select('numero')->where('id', $id_final[0]['id'])->first();
            $fecha_hora_final = model('facturaElectronicaModel')->select('fecha,hora')->where('id', $id_final[0]['id'])->first();

            /**
             * Discriminación de las bases tributarias tanto iva como impuesto al consumo 
             */

            $iva = model('pagosModel')->fiscal_iva($id_inicial[0]['id'], $id_final[0]['id']);
            $array_iva = array();

            $facturas = model('facturaElectronicaModel')->getFacturasTrasmitidas($id_inicial[0]['id'], $id_final[0]['id']);
            $array_iva = []; // Arreglo para almacenar los resultados finales.

            if (!empty($iva)) {  //Tarifas de IVA 

                foreach ($iva as $detalle) { // Inicializar acumuladores para cada tarifa de IVA.

                    $tarifa_iva = $detalle['valor_iva'];
                    $total_base = 0;
                    $total_iva = 0;
                    $total_venta = 0;

                    foreach ($facturas as $keyFacturas) {  //Recorrrer los ID's de las facturas y que han sido trasmitidas 
                        // Obtener los datos de IVA por factura y tarifa.
                        $iva_factura = model('kardexModel')->get_iva_electronico($keyFacturas['id'], $tarifa_iva);   //Obtengo el IVA de la factura 
                        $total_factura = model('kardexModel')->total_iva_electronico($keyFacturas['id'], $tarifa_iva); //Obtengo el total de la factura 

                        if (!empty($iva_factura) && !empty($total_factura)) {
                            // Acumular valores por tarifa.
                            $total_base += $total_factura[0]['total'] - $iva_factura[0]['iva'];
                            $total_iva += $iva_factura[0]['iva'];
                            $total_venta += $total_factura[0]['total'];
                        }
                    }

                    // Guardar los resultados en el arreglo final.
                    $data_iva = [
                        'tarifa_iva' => $tarifa_iva,
                        'base' => $total_base,
                        'total_iva' => $total_iva,
                        'valor_venta' => $total_venta,
                    ];
                    array_push($array_iva, $data_iva);
                }
            } else {
                $data_iva['tarifa_iva'] =  0;
                $data_iva['base'] = 0;
                $data_iva['total_iva'] = 0;
                $data_iva['valor_venta'] = 0;
                array_push($array_iva, $data_iva);
            }

            $ico = model('kardexModel')->fiscal_ico($id_inicial[0]['id'], $id_final[0]['id']);
            //  $array_ico = array();

            $facturas_inc = model('facturaElectronicaModel')->getFacturasTrasmitidas($id_inicial[0]['id'], $id_final[0]['id']);
            $array_ico = [];

            if (!empty($ico)) {
                foreach ($ico as $detalle) {

                    $tarifa_inc = $detalle['valor_ico'];
                    $total_base_inc = 0;
                    $total_inc = 0;
                    $total_venta_inc = 0;

                    foreach ($facturas as $keyFacturas) {  //Recorrrer los ID's de las facturas y que han sido trasmitidas 
                        // Obtener los datos de IVA por factura y tarifa.
                        $inc_factura = model('kardexModel')->get_inc_electronico($keyFacturas['id'], $tarifa_inc);   //Obtengo el IVA de la factura 
                        $total_factura_inc = model('kardexModel')->total_inc_electronico($keyFacturas['id'], $tarifa_inc); //Obtengo el total de la factura 

                        if (!empty($inc_factura) && !empty($total_factura_inc)) {
                            // Acumular valores por tarifa.
                            $total_base_inc += $total_factura_inc[0]['total'] - $inc_factura[0]['total'];
                            $total_inc += $inc_factura[0]['total'];
                            $total_venta_inc += $total_factura_inc[0]['total'];
                        }
                    }
                    // Guardar los resultados en el arreglo final.
                    $data_ico = [
                        'tarifa_ico' => $tarifa_inc,
                        'base' => $total_base_inc,
                        'total_ico' => $total_inc,
                        'valor_venta' => $total_venta_inc,
                    ];
                    array_push($array_ico, $data_ico);
                }
            } else {
                $data_ico['tarifa_ico'] =  0;
                $data_ico['base'] = 0;
                $data_ico['total_ico'] = 0;
                $data_ico['valor_venta'] = 0;
                array_push($array_ico, $data_ico);
            }


            /**
             * Total de ventas crédito y de contado 
             */

            $vantas_contado = model('kardexModel')->ventas_contado_electronicas($id_inicial[0]['id'], $id_final[0]['id']);
            $venta_credito = model('facturaVentaModel')->venta_credito($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

            if (empty($venta_credito[0]['total_ventas_credito'])) {
                $ventas_credito = 0;
            } else if (!empty($venta_credito[0]['total_ventas_credito'])) {
                $ventas_credito = $venta_credito[0]['total_ventas_credito'];
            }

            /**
             *Devoluciones 
             */
            //$iva_devolucion = model('devolucionModel')->tarifa_iva($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

            $iva_devolucion = model('devolucionModel')->tarifa_iva($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

            $array_devoluciones_iva = array();
            // if (!empty($iva_devolucion)) {

            foreach ($iva_devolucion as $detalle) {

                $aplica_ico = model('productoModel')->select('aplica_ico')->where('codigointernoproducto', $detalle['codigo'])->first();

                if ($aplica_ico['aplica_ico'] == 't') {
                    $iva_devolucion = model('devolucionModel')->devolucion_iva($detalle['iva'], $fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre, $detalle['codigo']);

                    $temp_porcentaje = $detalle['iva'] / 100;
                    $sub_total = $iva_devolucion[0]['base'] * $temp_porcentaje;
                    $total = $iva_devolucion[0]['base'] + $sub_total;
                    $impuesto = $total - $iva_devolucion[0]['base'];

                    $data_devo_iva['tarifa'] = $detalle['iva'];
                    $data_devo_iva['base'] =  $iva_devolucion[0]['base'];
                    $data_devo_iva['impuesto'] = $impuesto;
                    $data_devo_iva['total'] = $total;
                    array_push($array_devoluciones_iva, $data_devo_iva);
                }
            }


            $ico_devolucion = model('devolucionModel')->tarifa_ico($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);

            $array_devoluciones_ico = array();
            if (!empty($ico_devolucion)) {

                foreach ($ico_devolucion as $detalle) {

                    $ico_devolucion = model('devolucionModel')->devolucion_ico($detalle['ico'], $fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_cierre);
                    $temp_porcentaje = $detalle['ico'] / 100;
                    $sub_total = $ico_devolucion[0]['base'] * $temp_porcentaje;
                    $total = $ico_devolucion[0]['base'] + $sub_total;
                    $impuesto = $total - $ico_devolucion[0]['base'];

                    $data_devo_ico['tarifa'] = $detalle['ico'];
                    $data_devo_ico['base'] =  $ico_devolucion[0]['base'];
                    $data_devo_ico['impuesto'] = $impuesto;
                    $data_devo_ico['total'] = $total;
                    array_push($array_devoluciones_ico, $data_devo_ico);
                }
            } else if (empty($ico_devolucion)) {

                $data_devo_ico['base'] =  0;
                $data_devo_ico['tarifa'] = 0;
                $data_devo_ico['impuesto'] = 0;
                $data_devo_ico['total'] = 0;
                array_push($array_devoluciones_ico, $data_devo_ico);
            }


            $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
            $consecutivo_fiscal = model('consecutivoInformeModel')->select('numero')->where('id_apertura', $id_apertura)->first();

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 1);

            $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
            $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
            $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $nombre_ciudad['nombreciudad'] . " " . $nombre_departamento['nombredepartamento'] . "\n");
            $printer->text($regimen['descripcion'] . "\n");
            $printer->text("\n");


            $hora_inicial = DateTime::createFromFormat('H:i:s', $fecha_hora_inicial['hora'])->format('g:i A');
            $hora_final = DateTime::createFromFormat('H:i:s', $fecha_hora_final['hora'])->format('g:i A');


            $printer->text("***INFORME FISCAL DE VENTAS ***\n\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Informe N°       : " . $consecutivo_fiscal['numero']  . "\n");
            $printer->text("Fecha            : " . $fecha_apertura['fecha'] . "\n");
            $printer->text("Registro inicial : " . $reg_inicial['numero'] . " "  . $fecha_hora_inicial['fecha'] . " " . $hora_inicial . "\n");
            $printer->text("Registro final   : " . $reg_final['numero'] . " " . $fecha_hora_final['fecha'] . " " . $hora_final . "\n");
            $printer->text("Total registros  : " . $total_registros[0]['id'] . "\n");
            $printer->text("Fecha generacion : " . date('Y-m-d H:i:s') . "\n");
            $printer->text("Usuario          : " . $nombreUsuario['nombresusuario_sistema'] . "\n\n");

            // Título de la tabla
            $printer->setEmphasis(true);
            $printer->text("-----------------------------------------------\n");
            $printer->text("Tarifa    Base grabable   Valor IVA   Val total\n");
            $printer->text("-----------------------------------------------\n");
            $printer->setEmphasis(false);

            // Añadir los datos de la tabla
            foreach ($array_iva as $detalle) {
                //$printer->text($detalle['tarifa_iva']%  "   "  .$detalle['total_iva']."  ".$detalle['valor_venta'] );
                $printer->text(
                    $detalle['tarifa_iva'] . "%         " .
                        "$" . number_format($detalle['total_iva'], 0, ",", ".") . "            " .
                        0 . "          " .
                        "$" . number_format($detalle['valor_venta'], 0, ",", ".") . "\n"
                );
            }


            $printer->text("\n");
            $printer->text("-----------------------------------------------\n");
            $printer->text("Tarifa    Base grabable   Val INC   Val total\n");
            $printer->text("-----------------------------------------------\n");
            foreach ($array_ico as $detalle) {
                //$printer->text($detalle['tarifa_iva']%  "   "  .$detalle['total_iva']."  ".$detalle['valor_venta'] );
                $printer->text($detalle['tarifa_ico'] . "%        " . number_format($detalle['base'], 0, ",", ".") . "     " . number_format($detalle['total_ico'], 0, ",", ".") . "       " . number_format($detalle['valor_venta'], 0, ",", ".") . "\n");
            }

            $pago = model('pagosModel')->total_formas_pago($id_apertura);
            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("-----------------------------------------------\n");
            $printer->text("\n");
            $printer->text("**FORMAS DE PAGO** \n\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);

            foreach ($pago as $keyPago) {
                $nombre_comercial = model('medioPagoModel')->getNombre($keyPago['medio_pago']);
                $total = model('medioPagoModel')->getTotal($keyPago['medio_pago'], $id_apertura);

                // Define el ancho máximo para las columnas
                $columna_nombre = 35; // Ancho para el nombre
                $columna_total = 10;  // Ancho para el total (para valores numéricos)

                // Limita y ajusta el texto del nombre
                $nombre = str_pad(substr($nombre_comercial[0]['nombre_comercial'], 0, $columna_nombre), $columna_nombre);

                // Formatea el monto para alinearlo a la derecha
                $monto = str_pad(number_format($total[0]['total'], 0, ",", "."), $columna_total, " ", STR_PAD_LEFT);

                // Escribe en la impresora
                $printer->text($nombre . $monto . "\n");
            }

            $totalFormasPago = model('medioPagoModel')->getTotalFormas($id_apertura);
            $printer->text("TOTAL FORMAS DE PAGO              " . "$ " . number_format($totalFormasPago[0]['total'], 0, ",", ".") . "\n");


            $printer->feed(1);
            $printer->cut();

            $printer->close();

            $returnData = array(
                "resultado" => 1
            );
            echo  json_encode($returnData);
        }
    }




    function imprimir_inventario()
    {

        $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();
        $datos_empresa = model('empresaModel')->datosEmpresa();

        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
        $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento'] . "\n");
        $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
        $printer->text("\n");

        $inventario = model('inventarioModel')->impresion_invetario();
        $printer->text(str_pad("Cód", 4) . "    " . str_pad("Producto", 30) . str_pad("Cantidad", 10) . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);

        foreach ($inventario as $detalle) {
            // Limita el nombre del producto a 20 caracteres y ajusta si es más corto
            $codigo = str_pad($detalle['codigointernoproducto'], 4);  // Espacio de 4 caracteres para el código
            $producto = str_pad(substr($detalle['nombreproducto'], 0, 30), 30);  // Limita el nombre a 20 caracteres
            $cantidad = str_pad($detalle['cantidad_inventario'], 10);  // Espacio de 10 caracteres para la cantidad

            // Imprime el texto alineado
            $printer->text($codigo . "   " . $producto . "    " . $cantidad);
            $printer->text("____________________________________________\n");
        }



        $printer->setJustification(Printer::JUSTIFY_CENTER);

        $printer->text("\n");
        $printer->text("IMPRESO POR DFPYME \n");


        $printer->feed(1);
        $printer->cut();

        $printer->close();

        $returnData = array(
            "resultado" => 1
        );
        echo  json_encode($returnData);
    }

    function imprimir_inventario_sin_cantidades()
    {

        $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();
        $datos_empresa = model('empresaModel')->datosEmpresa();

        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
        $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento'] . "\n");
        $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
        $printer->text("\n");

        $inventario = model('inventarioModel')->impresion_invetario();
        $printer->text(str_pad("Cód", 4) . "    " . str_pad("Producto", 30)  . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);

        foreach ($inventario as $detalle) {
            // Limita el nombre del producto a 20 caracteres y ajusta si es más corto
            $codigo = str_pad($detalle['codigointernoproducto'], 4);  // Espacio de 4 caracteres para el código
            $producto = str_pad($detalle['nombreproducto'], 30);  // Limita el nombre a 20 caracteres

            $printer->text("  _________________________________________\n  ");
            // Imprime el texto alineado
            $printer->text($codigo . "   " . $producto . "  \n  ");
        }



        $printer->setJustification(Printer::JUSTIFY_CENTER);

        $printer->text("\n");
        $printer->text("IMPRESO POR DFPYME \n");


        $printer->feed(1);
        $printer->cut();

        $printer->close();

        $returnData = array(
            "resultado" => 1
        );
        echo  json_encode($returnData);
    }

    function imprimir_categoria_con_cantidades()
    {

        $codigo_categoria = $this->request->getPost('categorias');
        //$codigo_categoria = 6;

        $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();
        $datos_empresa = model('empresaModel')->datosEmpresa();

        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
        $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento'] . "\n");
        $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
        $printer->text("\n");

        $nombre_categoria = model('categoriasModel')->nombre_categorias($codigo_categoria);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("**CATEGORIA: " . $nombre_categoria[0]['nombrecategoria'] . "**\n\n");

        $inventario = model('inventarioModel')->impresion_invetario_categorias($codigo_categoria);
        $printer->text(str_pad("Cód", 4) . "    " . str_pad("Producto", 30)  . str_pad("Cant", 10) . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);




        foreach ($inventario as $detalle) {
            // Limita el nombre del producto a 20 caracteres y ajusta si es más corto
            $codigo = str_pad($detalle['codigointernoproducto'], 4);  // Espacio de 4 caracteres para el código
            $producto = str_pad($detalle['nombreproducto'], 30);  // Limita el nombre a 20 caracteres
            $cantidad = str_pad($detalle['cantidad_inventario'], 10);  // Limita el nombre a 20 caracteres

            $printer->text("  _________________________________________\n  ");
            // Imprime el texto alineado
            $printer->text($codigo . "   " . $producto . "   " . $cantidad . "  \n  ");
        }



        $printer->setJustification(Printer::JUSTIFY_CENTER);

        $printer->text("\n");
        $printer->text("IMPRESO POR DFPYME \n");


        $printer->feed(1);
        $printer->cut();

        $printer->close();

        $returnData = array(
            "resultado" => 1
        );
        echo  json_encode($returnData);
    }
    function imprimir_categoria_sin_cantidades()
    {

        $codigo_categoria = $this->request->getPost('categorias');
        //$codigo_categoria = 6;

        $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();
        $datos_empresa = model('empresaModel')->datosEmpresa();

        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
        $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento'] . "\n");
        $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
        $printer->text("\n");

        $nombre_categoria = model('categoriasModel')->nombre_categorias($codigo_categoria);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("**CATEGORIA: " . $nombre_categoria[0]['nombrecategoria'] . "**\n\n");

        $inventario = model('inventarioModel')->impresion_invetario_categorias($codigo_categoria);
        $printer->text(str_pad("Cód", 4) . "    " . str_pad("Producto", 30)  . "\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);




        foreach ($inventario as $detalle) {
            // Limita el nombre del producto a 20 caracteres y ajusta si es más corto
            $codigo = str_pad($detalle['codigointernoproducto'], 4);  // Espacio de 4 caracteres para el código
            $producto = str_pad($detalle['nombreproducto'], 30);  // Limita el nombre a 20 caracteres
            $cantidad = str_pad($detalle['cantidad_inventario'], 10);  // Limita el nombre a 20 caracteres

            $printer->text("  _________________________________________\n  ");
            // Imprime el texto alineado
            $printer->text($codigo . "   " . $producto . "  \n  ");
        }



        $printer->setJustification(Printer::JUSTIFY_CENTER);

        $printer->text("\n");
        $printer->text("IMPRESO POR DFPYME \n");


        $printer->feed(1);
        $printer->cut();

        $printer->close();

        $returnData = array(
            "resultado" => 1
        );
        echo  json_encode($returnData);
    }
}
