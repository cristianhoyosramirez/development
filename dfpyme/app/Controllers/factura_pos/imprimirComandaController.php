<?php

namespace App\Controllers\factura_pos;

require APPPATH . "Controllers/mike42/autoload.php";

use App\Controllers\BaseController;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class imprimirComandaController extends BaseController
{
    public function index()
    {
        return view('home/home');
    }

    public function imprimir_comanda()
    {

        $id_usuario = $_POST['id_usuario'];

        $transaccion_usuario = model('pedidoPosModel')->select('fk_usuario')->where('fk_usuario', $id_usuario)->first();

        if (empty($transaccion_usuario['fk_usuario'])) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('salones/salones'))->with('mensaje', 'No hay comanda para imprimir ');
        } else  if (!empty($transaccion_usuario['fk_usuario'])) {
            /**
             * Comprobacion de que en la columna se_imprime de la tabla producto_pedido hayan valores en false 
             */
            // $imprimir_comanda = model('productoPedidoPosModel')->select('impresion_en_comanda')->where('numero_de_pedido', $numero_pedido)->find();

            /*  foreach ($imprimir_comanda as $comprobar) {
            if ($comprobar == true) {
                $session = session();
                $session->setFlashdata('iconoMensaje', 'warning');
                return redirect()->to(base_url('salones/salones'))->with('mensaje', 'No hay productos para imprimir');
            } else { */

            $pk_pedido_pos = model('pedidoPosModel')->select('id')->where('fk_usuario', $id_usuario)->first();
            $codigo_categoria = model('productoPedidoPosModel')->id_categoria($pk_pedido_pos['id']);

            foreach ($codigo_categoria as $valor) {
                $nombre_categoria = model('categoriasModel')->select('nombrecategoria')->where('codigocategoria', $valor['id_categoria'])->first();
                $impresora = model('categoriasModel')->select('impresora')->where('codigocategoria', $valor['id_categoria'])->first();
                $this->generar_comanda($pk_pedido_pos['id'], $nombre_categoria['nombrecategoria'], $impresora['impresora'], $valor['id_categoria']);
            }
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('salones/salones'))->with('mensaje', 'Impresión de comanda exitoso');
            //}
            //}
        }
    }


    public function generar_comanda($numero_pedido, $nombre_categoria, $id_impresora, $id_categoria)
    {

        $items = model('productoPedidoPosModel')->productos_pedido($numero_pedido, $id_categoria);

        if (!empty($items)) {
            $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora)->first();

            $id_usuario = model('pedidoPosModel')->select('fk_usuario')->where('id', $numero_pedido)->first();
            $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario)->first();

            $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 1);
            $printer->text("**" . $nombre_categoria . "**" . "\n");



            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $printer->text("Mesero: " . $nombre_usuario['nombresusuario_sistema'] . "\n");

            $printer->text("Fecha :" . "   " . date('d/m/Y ') . "\n");
            $printer->text("Hora  :" . "   " . date('h:i:s a', time()) . "\n");



            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("----------------------------------------------- \n");
            $printer->text("CÓDIGO   PRODUCTO      CANTIDAD     NOTAS  \n");
            $printer->text("----------------------------------------------- \n");


            $items = model('productoPedidoPosModel')->productos_pedido($numero_pedido, $id_categoria);

            foreach ($items as $productos) {
                $data = [
                    'impreso_en_comanda' => true,
                ];

                $actualizar = model('productoPedidoPosModel')->set($data);
                $actualizar = model('productoPedidoPosModel')->where('pk_pedido_pos', $numero_pedido);
                $actualizar = model('productoPedidoPosModel')->update();

                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(2, 2);
                $printer->text( $productos['nombreproducto'] . "\n");
                $printer->text("Cant. " . $productos['cantidad_producto'] . "      " . "$" . number_format($productos['valorventaproducto'], 0, ',', '.') . "                   " . "$" . number_format($productos['cantidad_producto'] * $productos['valorventaproducto'], 0, ',', '.') . "\n");
                if (!empty($productos['nota_producto'])) {
                    $printer->setJustification(Printer::JUSTIFY_CENTER);
                    $printer->text("NOTAS:\n");
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text($productos['nota_producto'] . "\n");
                }
                $printer->text("_______________________________________________ \n");
                $printer->text("\n");
            }

            $observaciones_genereles = model('pedidoPosModel')->select('nota_general')->where('id', $numero_pedido)->first();
            if (!empty($observaciones_genereles['nota_general'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(1, 2);
                $printer->text("OBSERVACIONES GENERALES\n");
                $printer->text("\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(1, 1);
                $printer->text($observaciones_genereles['nota_general'] . "\n");
            }
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

        } else {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'warning');
            return redirect()->to(base_url('salones/salones'))->with('mensaje', 'No hay productos para imprimir');
        }
    }

    public function re_imprimir_comanda()
    {

        $numero_pedido = $_POST['numero_de_pedido_reimprimir_comanda'];

        $imprimir_comanda = model('productoPedidoModel')->select('impresion_en_comanda')->where('numero_de_pedido', $numero_pedido)->find();

        $codigo_categoria = model('productoPedidoModel')->id_categoria($numero_pedido);

        foreach ($codigo_categoria as $valor) {
            $nombre_categoria = model('categoriasModel')->select('nombrecategoria')->where('codigocategoria', $valor['codigo_categoria'])->first();
            $impresora = model('categoriasModel')->select('impresora')->where('codigocategoria', $valor['codigo_categoria'])->first();
            $this->generar_reimpresion_comanda($numero_pedido, $nombre_categoria['nombrecategoria'], $impresora['impresora'], $valor['codigo_categoria']);
        }
        $session = session();
        $session->setFlashdata('iconoMensaje', 'success');
        return redirect()->to(base_url('salones/salones'))->with('mensaje', 'Impresión de comanda exitoso');
    }

    public function generar_reimpresion_comanda($numero_pedido, $nombre_categoria, $id_impresora, $id_categoria)
    {

        $items = model('productoPedidoModel')->reimprimir_productos_pedido($numero_pedido, $id_categoria);

        if (!empty($items)) {
            $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora)->first();


            $id_usuario = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();
            $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
            $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa['fk_mesa'])->first();
            $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario)->first();


            $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text("REIMPRESION" . "\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 1);
            $printer->text("**" . $nombre_categoria . "**" . "\n");



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


            // $items = model('productoPedidoModel')->productos_pedido($numero_pedido, $id_categoria);
            $items = model('productoPedidoModel')->reimprimir_comanda($numero_pedido, $id_categoria);
            foreach ($items as $productos) {

                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(1, 1);
                $printer->text("Cod." . $productos['codigointernoproducto'] . "      " . $productos['nombreproducto'] . "\n");
                $printer->text("Cant. " . $productos['cantidad_producto'] . "      " . "$" . number_format($productos['valorventaproducto'], 0, ',', '.') . "                   " . "$" . number_format($productos['cantidad_producto'] * $productos['valorventaproducto'], 0, ',', '.') . "\n");
                if (!empty($productos['nota_producto'])) {
                    $printer->setJustification(Printer::JUSTIFY_CENTER);
                    $printer->text("NOTAS:\n");
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text($productos['nota_producto'] . "\n");
                }
                $printer->text("_______________________________________________ \n");
                $printer->text("\n");
            }

            $observaciones_genereles = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido)->first();
            if (!empty($observaciones_genereles['nota_pedido'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(1, 2);
                $printer->text("OBSERVACIONES GENERALES\n");
                $printer->text("\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(1, 1);
                $printer->text($observaciones_genereles['nota_pedido'] . "\n");
            }
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

        } else {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'warning');
            return redirect()->to(base_url('salones/salones'))->with('mensaje', 'No hay productos para imprimir');
        }
    }
}
