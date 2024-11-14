<?php

namespace App\Controllers\pruebaImpresion;


require APPPATH . "Controllers/pruebaImpresion/autoload.php";

use App\Controllers\BaseController;
use Config\Services;



use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;



class imprimirController extends BaseController
{


    public function index()
    {

        $nombre_impresora = "pruebas2";


        $connector = new WindowsPrintConnector($nombre_impresora);
        $printer = new Printer($connector);
        // $printer->text("Hola");
        /*   $printer->feed(5);
        $printer->setTextSize(2, 2);
        $printer->text("Ticket con PHP");
        $printer->setTextSize(2, 1);
        $printer->feed();
        $printer->text("Hola mundo\n\nParzibyte.me\n\nNo olvides suscribirte"); */
        /*
        Hacemos que el papel salga. Es como
        dejar muchos saltos de línea sin escribir nada
        */
        $nombre_empresa = model('empresaModel')->select('nombrecomercialempresa')->find();
        $nit = model('empresaModel')->select('nitempresa')->find();
        $direccion = model('empresaModel')->select('direccionempresa')->find();
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text($nombre_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text("NIT:" . "  " . $nit[0]['nitempresa'] . "\n");
        $printer->text("DIRECCIÓN:" . "  " . $direccion[0]['direccionempresa'] . "\n");
        $printer->text("NO RESPOSABLE DE IVA \n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("FACTURA DE VENTA N° 70AE4378 \n");
        $printer->text("FECHA \n");
        $printer->text("CAJA \n");
        $printer->text("----------------------------------------------- \n");
        $printer->text("CLIENTE: CLIENTE GENERAL" . "\n");
        $printer->text("NIT: 22222222" . "\n");
        $printer->text("----------------------------------------------- \n");
        $printer->text("CÓDIGO   PRODUCTO      CANT     VENTA     TOTAL \n");

        $items = model('productoFacturaVentaModel')->getProductosFacturaVentaModel(1550);
        foreach ($items as $detalle) {
            $valor_venta = $detalle['total'] / $detalle['cantidadproducto_factura_venta'];
            $printer->text($detalle['codigointernoproducto'] . "      " . $detalle['nombreproducto'] . "\n");
            $printer->text($detalle['cantidadproducto_factura_venta'] ."        ". number_format($valor_venta, 0, ",", ".") ."              "."$" . number_format($detalle['total'], 0, ",", ".").   "\n");
        }
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("TOTAL \n");
        $printer->text("EFECTIVO \n");
        $printer->text("CAMBIO \n");
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("GRACIAS POR SER NUESTROS CLIENTES \n");
        $printer->feed(10);

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
        $printer->pulse();
        $printer->close();
        # $printer = new Printer($connector);

        //$milibreria = new Ejemplolibreria();
        //$data = $milibreria->getRegistros();
    }
}
