<?php

namespace App\Controllers\factura_pos;

use App\Controllers\BaseController;

require APPPATH . "Controllers/mike42/autoload.php";

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class facturacionSinImpuestosController extends BaseController
{
    public function index()
    {
        return view('home/home');
    }

    public function imprimir_factura()
    {
        //$id_factura = 63823;
        $id_factura = $_POST['id_de_factura'];

        $movimientos_transaccion = model('facturaformaPagoModel')->forma_pago_transaccion($id_factura);


        $estado_factura = model('facturaVentaModel')->select('idestado')->where('id', $id_factura)->first();
        $numero_factura = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura)->first();
        $fecha_factura_venta = model('facturaVentaModel')->select('fecha_factura_venta')->where('id', $id_factura)->first();
        $hora_factura_venta = model('facturaVentaModel')->select('horafactura_venta')->where('id', $id_factura)->first();
        $id_usuario = model('facturaVentaModel')->select('idusuario_sistema')->where('id', $id_factura)->first();
        $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario['idusuario_sistema'])->first();
        $datos_empresa = model('empresaModel')->datosEmpresa();

        $nit_cliente = model('facturaVentaModel')->select('nitcliente')->where('id', $id_factura)->first();
        $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $nit_cliente['nitcliente'])->first();

        // $nombre_impresora = 'FACTURACION';
        $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();
        if (empty($id_impresora)) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'error');
            return redirect()->to(base_url('administracion_impresora/impresion_factura'))->with('mensaje', 'No hay impresora asignada para facturar');
        } else {

            $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();
            $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 1);
            $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
            //$printer->text($datos_empresa[0]['representantelegalempresa'] . "\n");
            $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
            $printer->text($datos_empresa[0]['direccionempresa'] . "\n");
            $printer->text($datos_empresa[0]['telefonoempresa'] . "\n");
            $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
            $printer->text("\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            if ($estado_factura['idestado'] == 1) {
                $printer->text("FACTURA DE VENTA:" . $numero_factura['numerofactura_venta'] . "\n");
            }
            if ($estado_factura['idestado'] == 7) {
                //$printer->text("TIPO DE VENTA:" . $numero_factura['numerofactura_venta'] . "\n");
                $printer->text("TIPO DE VENTA: REMISION CONTADO " . "\n");
            }
            $printer->text("FECHA:" . " " . $fecha_factura_venta['fecha_factura_venta'] . "  " . $hora_factura_venta['horafactura_venta'] . "\n");
            $printer->text("CAJA 1:" . "   " . "CAJERO: " . $nombre_usuario['nombresusuario_sistema'] . "\n");

            $printer->text("---------------------------------------------" . "\n");
            $printer->text("CLIENTE :" . " " . $nombre_cliente['nombrescliente'] . "\n");
            $printer->text("NIT     :" . " " . $nit_cliente['nitcliente'] . "\n");
            $printer->text("---------------------------------------------" . "\n");
            $printer->text("CODIGO    DESCRIPCION   VALOR UNITARIO    TOTAL" . "\n");
            $printer->text("---------------------------------------------" . "\n");
            $items = model('productoFacturaVentaModel')->getProductosFacturaVentaModel($id_factura);



            foreach ($items as $detalle) {
                $valor_venta = $detalle['total'] / $detalle['cantidadproducto_factura_venta'];
                $printer->setJustification(Printer::JUSTIFY_LEFT);

                /* $printer->text($detalle['codigointernoproducto']);
            $printer->text($detalle['nombreproducto']."\n"); */

                // $printer->text($detalle['cantidadproducto_factura_venta'] . " " . "$" . number_format($valor_venta, 0, ",", ".") . "                     " . "$" . number_format($detalle['total'], 0, ",", ".") . "\n");
                $printer->text("Cod." . $detalle['codigointernoproducto'] . "      " . $detalle['nombreproducto'] . "\n");
                $printer->text("Cant. " . $detalle['cantidadproducto_factura_venta'] . "      " . "$" . number_format($valor_venta, 0, ',', '.') . "                   " . "$" . number_format($detalle['total'], 0, ',', '.') . "\n");
            }

            $printer->text("---------------------------------------------" . "\n");
            $total = model('productoFacturaVentaModel')->selectSum('total')->where('id_factura', $id_factura)->find();
            $descuento=model('facturaVentaModel')->select('descuento')->where('id',$id_factura)->first();
            $propina=model('facturaVentaModel')->select('propina')->where('id',$id_factura)->first();
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("DESCUENTO :" . "$" . number_format($descuento['descuento'], 0, ",", ".") . "\n");
            $printer->text("PROPINA :" . "$" . number_format($propina['propina'], 0, ",", ".") . "\n");
            $printer->setTextSize(2, 2);
            $printer->setJustification(Printer::JUSTIFY_RIGHT);

            $printer->text("TOTAL :" . "$" . number_format((($total[0]['total']-$descuento['descuento']))+$propina['propina'], 0, ",", ".") . "\n");
            $efectivo = model('facturaFormaPagoModel')->select('valor_pago')->where('id_factura', $id_factura)->find();

            $printer->setTextSize(1, 1);
            //$printer->text("EFECTIVO :" . "$" . number_format($efectivo[0]['valor_pago'], 0, ",", ".") . "\n");

            $efectivo = model('facturaFormaPagoModel')->selectSum('valor_pago')->where('id_factura', $id_factura)->find();

            $id_forma_pago = model('facturaFormaPagoModel')->id_forma_pago($id_factura);
            $temp_cambio = 0;
            foreach ($id_forma_pago as $forma_pago) {
                $id_forma_pago = model('facturaFormaPagoModel')->id_forma_pago($id_factura);
                $nombre_forma_pago = model('facturaFormaPagoModel')->nombre_forma_pago($forma_pago['idforma_pago']);
                $valor_forma_pago = model('facturaFormaPagoModel')->valor_forma_pago($forma_pago['idforma_pago'], $id_factura);

                $printer->text($nombre_forma_pago[0]['nombreforma_pago'] . "$" . number_format($valor_forma_pago[0]['valor_pago'], 0, ",", ".") . "\n");
            }


            $printer->text("CAMBIO :" . "$" . number_format($efectivo[0]['valor_pago'] - (($total[0]['total']-$descuento['descuento'])+$propina['propina']), 0, ",", ".") . "\n");

            //$printer->text("CAMBIO :" . "$" . number_format($efectivo[0]['valor_pago'] - $total[0]['total'], 0, ",", ".") . "\n");
            $printer->text("-----------------------------------------------" . "\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $fk_usuario_mesero = model('facturaVentaModel')->select('fk_usuario_mesero')->where('id', $id_factura)->first();

            $nombreusuario_sistema = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $fk_usuario_mesero['fk_usuario_mesero'])->first();

            $printer->text("ATENDIDO POR:" . $nombreusuario_sistema['nombresusuario_sistema'] . "\n");
            $fk_mesa = model('facturaVentaModel')->select('fk_mesa')->where('id', $id_factura)->first();
            if (!empty($fk_mesa['fk_mesa'])) {
                $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $fk_mesa['fk_mesa'])->first();
                $printer->text("MESA:" . $nombre_mesa['nombre'] . "\n");
                $id_salon = model('mesasModel')->select('fk_salon')->where('id', $fk_mesa['fk_mesa'])->first();
                $nombre_salon = model('salonesModel')->select('nombre')->where('id', $id_salon['fk_salon'])->first();
                $numero_pedido = model('facturaVentaModel')->select('numero_pedido')->where('id', $id_factura)->first();
                $printer->text("Numero de pedido " . $numero_pedido['numero_pedido'] . "\n");
                $printer->text("TIPO DE VENTA:" . $nombre_salon['nombre'] . "\n");
            }
            if (empty($fk_mesa['fk_mesa']) || $fk_mesa['fk_mesa'] == 0) {
                $printer->text("TIPO DE VENTA: VENTAS DE MOSTRADOR" . "\n");
            }

            $observaciones_genereles = model('facturaVentaModel')->select('observaciones_generales')->where('id', $id_factura)->first();
            if (!empty($observaciones_genereles['observaciones_generales'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(1, 2);
                $printer->text("OBSERVACIONES GENERALES\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(1, 1);
                $printer->text($observaciones_genereles['observaciones_generales'] . "\n");
                $printer->text("-----------------------------------------------" . "\n");
            }
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("GRACIAS POR SU VISITA " . "\n");

            $printer->feed(1);
            $printer->cut();
            //$printer->pulse();
            //$printer->close();
            // return redirect()->to(base_url('pedido/pedidos_para_facturar'))->with('mensaje', 'Impresión de factura correcto');

            //$movimientos_transaccion = model('facturaformaPagoModel')->select('valorfactura_forma_pago')->where('id_factura', $id_factura)->findAll();
            //$movimientos_transaccion = model('facturaformaPagoModel')->select('valorfactura_forma_pago')->where('idforma_pago', 4)->findAll();

            if (!empty($movimientos_transaccion)) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(1, 1);
                $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
                //$printer->text($datos_empresa[0]['representantelegalempresa'] . "\n");
                $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
                $printer->text($datos_empresa[0]['direccionempresa'] . "\n");
                $printer->text($datos_empresa[0]['telefonoempresa'] . "\n");
                $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
                $printer->text("\n");

                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(1, 1);
                $printer->text("FACTURA DE VENTA:" . $numero_factura['numerofactura_venta'] . "\n");
                $printer->text("FECHA:" . " " . $fecha_factura_venta['fecha_factura_venta'] . "  " . $hora_factura_venta['horafactura_venta'] . "\n");
                $printer->text("FECHA:" . " " . $fecha_factura_venta['fecha_factura_venta'] . "  " . $hora_factura_venta['horafactura_venta'] . "\n");
                $printer->text("CAJA 1:" . "   " . "CAJERO: " . $nombre_usuario['nombresusuario_sistema'] . "\n\n");
                $printer->setTextSize(2, 1);
                $printer->text("TOTAL :" . "$" . number_format($total[0]['total'], 0, ",", ".") . "\n");
                if (!empty($movimientos_efectivo[0]['valorfactura_forma_pago'])) {
                    $printer->text("EFECTIVO :" . "$" . number_format($movimientos_efectivo[0]['valorfactura_forma_pago'], 0, ",", ".") . "\n");
                }
                $printer->text("TRANSACCION :" . "$" . number_format($movimientos_transaccion[0]['valorfactura_forma_pago'], 0, ",", ".") . "\n\n\n");
                $printer->setTextSize(1, 1);
                $printer->text("Nombre : \n\n");
                $printer->text("Identificación: \n\n");
                $printer->text("Teléfono :\n\n");


                $printer->feed(1);
                $printer->cut();
                $printer->pulse();
                $printer->close();
            } else if (empty($movimientos_transaccion)) {
                $printer->pulse();
                $printer->close();
            }
            return redirect()->to(base_url('pedido/pedidos_para_facturar'))->with('mensaje', 'Impresión de factura correcto');
        }
    }
    public function imprimir_factura_directa()
    {
        //$id_factura = 70045;
        $id_factura = $_POST['id_de_factura'];


        $numero_factura = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura)->first();

        if ($numero_factura['numerofactura_venta']) {
            $estado_factura = model('facturaVentaModel')->select('idestado')->where('id', $id_factura)->first();
            $fecha_factura_venta = model('facturaVentaModel')->select('fecha_factura_venta')->where('id', $id_factura)->first();
            $hora_factura_venta = model('facturaVentaModel')->select('horafactura_venta')->where('id', $id_factura)->first();
            $id_usuario = model('facturaVentaModel')->select('idusuario_sistema')->where('id', $id_factura)->first();
            $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario['idusuario_sistema'])->first();
            $datos_empresa = model('empresaModel')->datosEmpresa();

            $nit_cliente = model('facturaVentaModel')->select('nitcliente')->where('id', $id_factura)->first();
            $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $nit_cliente['nitcliente'])->first();

            $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();


            $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();
            $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 1);
            $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
            //$printer->text($datos_empresa[0]['representantelegalempresa'] . "\n");
            $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
            $printer->text($datos_empresa[0]['direccionempresa'] . "\n");
            $printer->text($datos_empresa[0]['telefonoempresa'] . "\n");
            $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
            $printer->text("\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            if ($estado_factura['idestado'] == 1) {
                $printer->text("FACTURA DE VENTA:" . $numero_factura['numerofactura_venta'] . "\n");
            }
            if ($estado_factura['idestado'] == 7) {
                $printer->text("TIPO DE VENTA: REMISIÓN DE CONTADO " . "\n");
            }
            $printer->text("FECHA:" . " " . $fecha_factura_venta['fecha_factura_venta'] . "  " . $hora_factura_venta['horafactura_venta'] . "\n");
            $printer->text("CAJA 1:" . "   " . "CAJERO: " . $nombre_usuario['nombresusuario_sistema'] . "\n");

            $printer->text("---------------------------------------------" . "\n");
            $printer->text("CLIENTE :" . " " . $nombre_cliente['nombrescliente'] . "\n");
            $printer->text("NIT     :" . " " . $nit_cliente['nitcliente'] . "\n");
            $printer->text("---------------------------------------------" . "\n");
            $printer->text("CODIGO    DESCRIPCION   VALOR UNITARIO    TOTAL" . "\n");
            $printer->text("---------------------------------------------" . "\n");
            $items = model('productoFacturaVentaModel')->getProductosFacturaVentaModel($id_factura);



            foreach ($items as $detalle) {
                $valor_venta = $detalle['total'] / $detalle['cantidadproducto_factura_venta'];
                $printer->setJustification(Printer::JUSTIFY_LEFT);


                $printer->text("Cod." . $detalle['codigointernoproducto'] . "      " . $detalle['nombreproducto'] . "\n");
                $printer->text("Cant. " . $detalle['cantidadproducto_factura_venta'] . "      " . "$" . number_format($valor_venta, 0, ',', '.') . "                   " . "$" . number_format($detalle['total'], 0, ',', '.') . "\n");
            }

            $printer->text("---------------------------------------------" . "\n");
            $total = model('productoFacturaVentaModel')->selectSum('total')->where('id_factura', $id_factura)->find();
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->setTextSize(1, 1);
            $printer->text("SUB TOTAL :" . "$" . number_format($total[0]['total'], 0, ",", ".") . "\n");
            $descuento = model('facturaVentaModel')->select('descuento')->where('id', $id_factura)->first();
            $propina = model('facturaVentaModel')->select('propina')->where('id', $id_factura)->first();
            $printer->text("Descuento :" . "$" . number_format($descuento['descuento'], 0, ",", ".") . "\n");
            $printer->text("Propina :" . "$" . number_format($propina['propina'], 0, ",", ".") . "\n");
            $efectivo = model('facturaFormaPagoModel')->select('valor_pago')->where('id_factura', $id_factura)->find();

            $printer->setTextSize(2, 2);

            $printer->text("TOTAL :" . "$" . number_format(($total[0]['total']-$descuento['descuento'])+$propina['propina'], 0, ",", ".") . "\n");

            $printer->setTextSize(1, 1);


            $efectivo = model('facturaFormaPagoModel')->selectSum('valor_pago')->where('id_factura', $id_factura)->find();

            $id_forma_pago = model('facturaFormaPagoModel')->id_forma_pago($id_factura);
            $temp_cambio = 0;
            foreach ($id_forma_pago as $forma_pago) {
                $id_forma_pago = model('facturaFormaPagoModel')->id_forma_pago($id_factura);
                $nombre_forma_pago = model('facturaFormaPagoModel')->nombre_forma_pago($forma_pago['idforma_pago']);
                $valor_forma_pago = model('facturaFormaPagoModel')->valor_forma_pago($forma_pago['idforma_pago'], $id_factura);

                $printer->text($nombre_forma_pago[0]['nombreforma_pago'] . "$" . number_format($valor_forma_pago[0]['valor_pago'], 0, ",", ".") . "\n");
            }


            $printer->text("CAMBIO :" . "$" . number_format($efectivo[0]['valor_pago']-(($total[0]['total']-$descuento['descuento'])+$propina['propina']) , 0, ",", ".") . "\n");


            $printer->text("-----------------------------------------------" . "\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $fk_usuario_mesero = model('facturaVentaModel')->select('fk_usuario_mesero')->where('id', $id_factura)->first();

            $nombreusuario_sistema = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $fk_usuario_mesero['fk_usuario_mesero'])->first();

            $printer->text("ATENDIDO POR:" . $nombreusuario_sistema['nombresusuario_sistema'] . "\n");
            $fk_mesa = model('facturaVentaModel')->select('fk_mesa')->where('id', $id_factura)->first();
            if (!empty($fk_mesa['fk_mesa'])) {
                $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $fk_mesa['fk_mesa'])->first();
                $printer->text("MESA:" . $nombre_mesa['nombre'] . "\n");
                $id_salon = model('mesasModel')->select('fk_salon')->where('id', $fk_mesa['fk_mesa'])->first();
                $nombre_salon = model('salonesModel')->select('nombre')->where('id', $id_salon['fk_salon'])->first();
                $numero_pedido = model('facturaVentaModel')->select('numero_pedido')->where('id', $id_factura)->first();
                $printer->text("Numero de pedido " . $numero_pedido['numero_pedido'] . "\n");
                $printer->text("TIPO DE VENTA:" . $nombre_salon['nombre'] . "\n");
            }
            if (empty($fk_mesa['fk_mesa']) || $fk_mesa['fk_mesa'] == 0) {
                $printer->text("TIPO DE VENTA: VENTAS DE MOSTRADOR" . "\n");
            }

            $observaciones_genereles = model('facturaVentaModel')->select('observaciones_generales')->where('id', $id_factura)->first();
            if (!empty($observaciones_genereles['observaciones_generales'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(1, 2);
                $printer->text("OBSERVACIONES GENERALES\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(1, 1);
                $printer->text($observaciones_genereles['observaciones_generales'] . "\n");
                $printer->text("-----------------------------------------------" . "\n");
            }
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("GRACIAS POR SU VISITA " . "\n");

            $printer->feed(1);
            $printer->cut();
            // $printer->pulse();
            //$printer->close();

            $movimientos_efectivo = model('facturaformaPagoModel')->forma_pago_efectivo($id_factura);

            $movimientos_transaccion = model('facturaformaPagoModel')->forma_pago_transaccion($id_factura);

            
            if (!empty($movimientos_transaccion)) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(1, 1);
                $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
                //$printer->text($datos_empresa[0]['representantelegalempresa'] . "\n");
                $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
                $printer->text($datos_empresa[0]['direccionempresa'] . "\n");
                $printer->text($datos_empresa[0]['telefonoempresa'] . "\n");
                $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
                $printer->text("\n");

                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(1, 1);
                $printer->text("FACTURA DE VENTA:" . $numero_factura['numerofactura_venta'] . "\n");
                $printer->text("FECHA:" . " " . $fecha_factura_venta['fecha_factura_venta'] . "  " . $hora_factura_venta['horafactura_venta'] . "\n");
                $printer->text("FECHA:" . " " . $fecha_factura_venta['fecha_factura_venta'] . "  " . $hora_factura_venta['horafactura_venta'] . "\n");
                $printer->text("CAJA 1:" . "   " . "CAJERO: " . $nombre_usuario['nombresusuario_sistema'] . "\n\n");
                $printer->setTextSize(2, 1);
                $printer->text("TOTAL :" . "$" . number_format($total[0]['total'], 0, ",", ".") . "\n");
                if (!empty($movimientos_efectivo[0]['valorfactura_forma_pago'])) {
                    $printer->text("EFECTIVO :" . "$" . number_format($movimientos_efectivo[0]['valorfactura_forma_pago'], 0, ",", ".") . "\n");
                }
                $printer->text("TRANSACCION :" . "$" . number_format($movimientos_transaccion[0]['valorfactura_forma_pago'], 0, ",", ".") . "\n\n\n");
                $printer->setTextSize(1, 1);
                $printer->text("Nombre : \n\n");
                $printer->text("Identificación: \n\n");
                $printer->text("Teléfono :\n\n");


                $printer->feed(1);
                $printer->cut();
                $printer->pulse();
                $printer->close();
            } else if (empty($movimientos_transaccion)) {
                $printer->pulse();
                $printer->close();
            }


            $returnData = array(
                "resultado" => 1, //Falta plata  
                "tabla" => view('factura_pos/tabla_reset_factura')
            );
            echo  json_encode($returnData);
        }
    }
    public function imprimir_factura_partir_factura()
    {
        $id_factura = $_POST['id_de_factura_partida'];

        $numero_factura = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura)->first();
        $fecha_factura_venta = model('facturaVentaModel')->select('fecha_factura_venta')->where('id', $id_factura)->first();
        $hora_factura_venta = model('facturaVentaModel')->select('horafactura_venta')->where('id', $id_factura)->first();
        $id_usuario = model('facturaVentaModel')->select('idusuario_sistema')->where('id', $id_factura)->first();
        $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario['idusuario_sistema'])->first();
        $datos_empresa = model('empresaModel')->datosEmpresa();

        $nit_cliente = model('facturaVentaModel')->select('nitcliente')->where('id', $id_factura)->first();
        $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $nit_cliente['nitcliente'])->first();

        $nombre_impresora = 'FACTURACION';
        $connector = new WindowsPrintConnector($nombre_impresora);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        //$printer->text($datos_empresa[0]['representantelegalempresa'] . "\n");
        $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
        $printer->text($datos_empresa[0]['direccionempresa'] . "\n");
        $printer->text($datos_empresa[0]['telefonoempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
        $printer->text("\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setTextSize(1, 1);
        $printer->text("FACTURA DE VENTA:" . $numero_factura['numerofactura_venta'] . "\n");
        $printer->text("FECHA:" . " " . $fecha_factura_venta['fecha_factura_venta'] . "  " . $hora_factura_venta['horafactura_venta'] . "\n");
        $printer->text("CAJA 1:" . "   " . "CAJERO: " . $nombre_usuario['nombresusuario_sistema'] . "\n");

        $printer->text("---------------------------------------------" . "\n");
        $printer->text("CLIENTE :" . " " . $nombre_cliente['nombrescliente'] . "\n");
        $printer->text("NIT     :" . " " . $nit_cliente['nitcliente'] . "\n");
        $printer->text("---------------------------------------------" . "\n");
        $printer->text("CODIGO    DESCRIPCION   VALOR UNITARIO    TOTAL" . "\n");
        $printer->text("---------------------------------------------" . "\n");
        $items = model('productoFacturaVentaModel')->getProductosFacturaVentaModel($id_factura);



        foreach ($items as $detalle) {
            $valor_venta = $detalle['total'] / $detalle['cantidadproducto_factura_venta'];
            $printer->setJustification(Printer::JUSTIFY_LEFT);


            $printer->text("Cod." . $detalle['codigointernoproducto'] . "      " . $detalle['nombreproducto'] . "\n");
            $printer->text("Cant. " . $detalle['cantidadproducto_factura_venta'] . "      " . "$" . number_format($valor_venta, 0, ',', '.') . "                   " . "$" . number_format($detalle['total'], 0, ',', '.') . "\n");
        }

        $printer->text("---------------------------------------------" . "\n");
        $total = model('facturaFormaPagoModel')->selectSum('valorfactura_forma_pago')->where('id_factura', $id_factura)->find();
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("TOTAL :" . "$" . number_format($total[0]['valorfactura_forma_pago'], 0, ",", ".") . "\n");
        $efectivo = model('facturaFormaPagoModel')->selectSum('valor_pago')->where('id_factura', $id_factura)->find();
        $id_forma_pago = model('facturaFormaPagoModel')->id_forma_pago($id_factura);
        $temp_cambio = 0;
        foreach ($id_forma_pago as $forma_pago) {
            $id_forma_pago = model('facturaFormaPagoModel')->id_forma_pago($id_factura);
            $nombre_forma_pago = model('facturaFormaPagoModel')->nombre_forma_pago($forma_pago['idforma_pago']);
            $valor_forma_pago = model('facturaFormaPagoModel')->valor_forma_pago($forma_pago['idforma_pago'], $id_factura);

            $printer->text($nombre_forma_pago[0]['nombreforma_pago'] . "$" . number_format($valor_forma_pago[0]['valor_pago'], 0, ",", ".") . "\n");
        }

        $printer->text("CAMBIO :" . "$" . number_format($efectivo[0]['valor_pago'] - $total[0]['valorfactura_forma_pago'], 0, ",", ".") . "\n");


        // $printer->text("EFECTIVO :" . "$" . number_format($efectivo[0]['valor_pago'], 0, ",", ".") . "\n");
        //$printer->text("CAMBIO :" . "$" . number_format($efectivo[0]['valor_pago'] - $total[0]['total'], 0, ",", ".") . "\n");
        $printer->text("-----------------------------------------------" . "\n");

        $regimen_empresa = model('empresaModel')->select('idregimen')->first();

        if ($regimen_empresa['idregimen'] == 1) {

            $tarifa_iva = model('productoFacturaVentaModel')->tarifa_iva($id_factura);

            if (!empty($tarifa_iva)) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(1, 1);
                $printer->text("**DISCRIMINACION TARIFAS DE IVA** \n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("TARIFA    COMPRA       BASE/IMP         IVA" . "\n");
                foreach ($tarifa_iva as $iva) {
                    $datos_iva = model('productoFacturaVentaModel')->base_iva($iva['valor_iva'], $id_factura);
                    if (!empty($datos_iva)) {
                        $printer->text($iva['valor_iva'] . "%" . "          " . "$" . number_format($datos_iva[0]['compra'], 0, ",", ".") . "   " . "$" . number_format($datos_iva[0]['base'] * $datos_iva[0]['cantidadproducto_factura_venta'], 0, ",", ".") . "    " . "$" . number_format($datos_iva[0]['compra'] - ($datos_iva[0]['base'] * $datos_iva[0]['cantidadproducto_factura_venta']), 0, ",", ".") . "\n");
                    }
                }
            }

            //$tarifa_ico = model('productoFacturaVentaModel')->tarifa_ico($id_factura);
            $tarifa_ico = model('productoFacturaVentaModel')->tarifa_ico($id_factura);

            if (!empty($tarifa_ico)) {
                $printer->text("\n");
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(1, 1);
                $printer->text("**DISCRIMINACION TARIFAS DE IPO CONSUMO** \n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("TARIFA    COMPRA     BASE/IMP        IPO CONSUMO" . "\n");
                /*   foreach ($tarifa_ico as $ico) {

                    //  $printer->text($iva['valor_iva']."%". "\n");
                    $datos_ico = model('productoFacturaVentaModel')->base_ico($ico['valor_ico'], $id_factura);
                    if (!empty($datos_ico)) {
                        $printer->text($ico['valor_ico'] . "%" . "          " . "$" . number_format($datos_ico[0]['compra'], 0, ",", ".") . "   " . "$" . number_format($datos_ico[0]['base'] * $datos_ico[0]['cantidadproducto_factura_venta'], 0, ",", ".") . "    " . "$" . number_format($datos_ico[0]['compra'] - ($datos_ico[0]['base'] * $datos_ico[0]['cantidadproducto_factura_venta']), 0, ",", ".") . "\n");
                    }
                } */
                foreach ($tarifa_ico as $ico) {
                    //  $printer->text($iva['valor_iva']."%". "\n");
                    $total_compra = model('productoFacturaVentaModel')->total_compra($ico['valor_ico'], $id_factura);
                    $base_ico = model('productoFacturaVentaModel')->base_ico($ico['valor_ico'], $id_factura);
                    $printer->text($ico['valor_ico'] . "%" . "          " . "$" . number_format($total_compra[0]['compra'], 0, ",", ".") . "   " . "$" . number_format($base_ico[0]['base'], 0, ",", ".") . "    " . "$" . number_format($total_compra[0]['compra'] - ($base_ico[0]['base']), 0, ",", ".") . "\n");
                }
            }
        }

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setTextSize(1, 1);
        $fk_usuario_mesero = model('facturaVentaModel')->select('fk_usuario_mesero')->where('id', $id_factura)->first();
        $nombreusuario_sistema = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $fk_usuario_mesero['fk_usuario_mesero'])->first();
        $printer->text("ATENDIDO POR:" . $nombreusuario_sistema['nombresusuario_sistema'] . "\n");
        $fk_mesa = model('facturaVentaModel')->select('fk_mesa')->where('id', $id_factura)->first();
        $numero_pedido = model('facturaVentaModel')->select('numero_pedido')->where('id', $id_factura)->first();



        if (!empty($fk_mesa['fk_mesa'])) {
            $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $fk_mesa['fk_mesa'])->first();
            $printer->text("MESA:" . $nombre_mesa['nombre'] . "\n");
            $id_salon = model('mesasModel')->select('fk_salon')->where('id', $fk_mesa['fk_mesa'])->first();
            $nombre_salon = model('salonesModel')->select('nombre')->where('id', $id_salon['fk_salon'])->first();
            $printer->text("NÚMERO DE PEDIDO:"  . $numero_pedido['numero_pedido'] . "\n");
            $printer->text("TIPO DE VENTA:" . $nombre_salon['nombre'] . "\n");
        }

        if (empty($fk_mesa['fk_mesa'])) {
            $printer->text("NÚMERO DE PEDIDO:"  . $numero_pedido['numero_pedido'] . "\n");
            $printer->text("TIPO DE VENTA: VENTAS DE MOSTRADOR" . "\n");
        }

        $observaciones_genereles = model('facturaVentaModel')->select('observaciones_generales')->where('id', $id_factura)->first();
        if (!empty($observaciones_genereles['observaciones_generales'])) {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 2);
            $printer->text("OBSERVACIONES GENERALES\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $printer->text($observaciones_genereles['observaciones_generales'] . "\n");
            $printer->text("-----------------------------------------------" . "\n");
        }
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("GRACIAS POR SU VISITA " . "\n");

        $printer->feed(1);
        $printer->cut();
        $printer->pulse();
        $printer->close();

        $numero_pedido = $_POST['id_pedido_factura_partida'];

        $estado = model('estadoModel')->findAll();
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
    }

    public function cerrar_venta_partir_factura()
    {
        $numero_pedido = $_POST['cerrar_venta_partir_factura'];
        $estado = model('estadoModel')->findAll();
        $regimen = model('regimenModel')->select('*')->find();
        $productos_del_pedido_para_facturar = model('productoPedidoModel')->productos_del_pedido_para_facturar($numero_pedido);
        $total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();

        $totalizado = $total['valor_total'];

        $tipo_cliente = model('tipoClienteModel')->select('*')->find();
        $clasificacion_cliente = model('clasificacionClienteModel')->select('*')->find();
        $departamento = model('departamentoModel')->select('*')->where('idpais', 49)->find();
        $id_cliente_general = model('clientesModel')->select('id')->where('nitcliente', '22222222')->first();
        $id_regimen_no_responsable_iva = model('regimenModel')->select('idregimen')->where('nombreregimen', 'NO RESPONSABLE DE IVA')->first();
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
    }

    public function reset_factura()
    {
        $usuario = $_REQUEST['usuario'];
        $pk_pedio_pos = model('pedidoPosModel')->select('id')->where('fk_usuario', $usuario)->first();

        $borrar_pedido = model('pedidoPosModel')->where('fk_usuario', $usuario);
        $borrar_pedido->delete();
        if ($borrar_pedido) {

            $borrar_producto_pedido = model('productoPedidoPosModel')->where('pk_pedido_pos', $pk_pedio_pos['id']);
            $borrar_producto_pedido->delete();
            $productos = view('factura_pos/tabla_reset_factura');
            if ($borrar_producto_pedido) {
                $returnData = array(
                    "resultado" => 1,
                    "productos" => $productos
                );
                echo  json_encode($returnData);
            }
        }
    }
}
