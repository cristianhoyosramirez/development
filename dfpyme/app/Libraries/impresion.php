<?php

namespace App\Libraries;

require APPPATH . "Controllers/mike42/autoload.php";

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use \DateTime;
use \DateTimeZone;

class impresion
{
    /**
     * Calcular imuestos
     * @param   $cod(igo_interno
     * 
     */
    function imprimir_cuadre_caja($id_apertura)
    {


        $id_apertura = $id_apertura;
        //$id_apertura = 19;

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

        $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
        $hora_apertura = model('aperturaModel')->select('hora')->where('id', $id_apertura)->first();

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("**CUADRE DE CAJA** \n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Fecha apertura:  " . $fecha_apertura['fecha'] . " " . date("g:i a", strtotime($hora_apertura['hora'])) . "\n");


        $cierre = model('cierreModel')->select('fecha')->where('idapertura', $id_apertura)->first();

        if (!empty($cierre)) {
            $hora = model('cierreModel')->select('hora')->where('idapertura', $id_apertura)->first();
            $printer->text("Fecha cierre:    " . $cierre['fecha'] . " " . date("g:i a", strtotime($hora['hora'])) . "\n");
        }
        if (empty($cierre)) {
            $printer->text("Fecha cierre:    Sin cierre " . "\n");
        }




        $printer->text("Caja N° : 1 \n");

        $printer->text("\n");

        $valor_apertura = model('aperturaModel')->select('valor')->where('id', $id_apertura)->first();

        $ventas_pos = model('pagosModel')->set_ventas_pos($id_apertura);

        $ventas_electronicas = model('pagosModel')->set_ventas_electronicas($id_apertura);
        $propinas = model('pagosModel')->selectSum('propina')->where('id_apertura', $id_apertura)->findAll();
        $printer->text("Ventas POS: " . "             $ " . number_format($ventas_pos[0]['valor'], 0, ",", ".") . "\n");
        $printer->text("Ventas electrónicas: "  .  "    $ " . number_format($ventas_electronicas[0]['valor'], 0, ",", ".") . "\n");
        $printer->text("Propinas: "  .  "               $ " . number_format($propinas[0]['propina'], 0, ",", ".") . "\n");

        $printer->text("\n");

        $printer->text("-----------------------------------------------\n ");
        $printer->text("INGRESOS \n ");
        $printer->text("-----------------------------------------------\n ");
        $printer->text("\n");



        $ingresos_efectivo = model('pagosModel')->selectSum('efectivo')->where('id_apertura', $id_apertura)->findAll();
        $efectivo = $ingresos_efectivo[0]['efectivo'];
        $ingresos_transaccion = model('pagosModel')->selectSum('transferencia')->where('id_apertura', $id_apertura)->findAll();
        $propinas = model('pagosModel')->selectSum('propina')->where('id_apertura', $id_apertura)->findAll();

        //dd($efectivo);
        $printer->text("Valor apertura: " . "        $ " . number_format($valor_apertura['valor'], 0, ",", ".") . "\n");
        $printer->text("Ingresos efectivo:      " . "$ " . number_format($ingresos_efectivo[0]['efectivo'], 0, ",", ".") . "\n");
        $printer->text("Ingresos transacción: " . "  $ " . number_format($ingresos_transaccion[0]['transferencia'], 0, ",", ".") . "\n");
        //$total_ingresos = model('facturaFormaPagoModel')->total_ingresos($fecha_y_hora_apertura['fecha_y_hora_apertura'], $fecha_y_hora_actual);
        $printer->text("Total ingresos          " . "$ " . number_format(($ingresos_efectivo[0]['efectivo']  + $valor_apertura['valor'] + $ingresos_transaccion[0]['transferencia']), 0, ",", ".") . "\n");


        $printer->text("\n");

        $printer->text("------------------------------------------------\n ");
        $printer->text("RETIROS \n");
        $printer->text("------------------------------------------------\n ");
        $retiros = model('retiroModel')->select('*')->where('id_apertura', $id_apertura)->findAll();

        $printer->text("\n");
        foreach ($retiros as $detalle) {
            $printer->text("FECHA: " . $detalle['fecha'] . " " . date("g:i a", strtotime($detalle['hora'])) . "\n");
            $concepto = model('retiroFormaPagoModel')->select('concepto')->where('idretiro', $detalle['id'])->first();

            //echo $concepto['concepto']. "</br>";
            //var_dump($concepto);
            //dd($concepto);

            //$printer->text($concepto['concepto']. "\n");

            //$concepto = "empacado crem helado";

            $concepto = trim($concepto['concepto'] ?? ''); // Elimina espacios en blanco
            $concepto = htmlspecialchars($concepto, ENT_QUOTES, 'UTF-8');


            $printer->text($concepto . "\n");



            /* $valor = model('retiroFormaPagoModel')->select('valor')->where('idretiro', $detalle['id'])->first();
            $printer->text("VALOR:" . "$" . number_format($valor['valor'], 0, ",", ".") . "\n"); */

            $valor = model('retiroFormaPagoModel')->select('valor')->where('idretiro', $detalle['id'])->first();

            // Verifica que el valor existe y es numérico
            if (isset($valor['valor'])) {
                // Elimina cualquier carácter extraño que no sea número, coma o punto
                $monto_limpio = preg_replace('/[^0-9,.-]/', '', $valor['valor']);

                // Convertir a número flotante para evitar errores
                $monto_numerico = floatval(str_replace(',', '.', $monto_limpio));

                // Formatear número
                $monto = number_format($monto_numerico, 0, ",", ".");
            } else {
                $monto = "No disponible";
            }

            $printer->text("VALOR: $" . $monto . "\n");


            $printer->text("\n");
        }



        $temp_retiros = 0;
        $total_retiros = 0;
        /* foreach ($retiros as $detalle) {
            $valor = model('retiroFormaPagoModel')->select('valor')->where('idretiro', $detalle['id'])->first();
           
            $temp_retiros = $temp_retiros + $valor['valor'];
           
            $total_retiros = $temp_retiros;
        } */


        foreach ($retiros as $detalle) {
            $valor = model('retiroFormaPagoModel')->select('valor')->where('idretiro', $detalle['id'])->first();

            // Verificar que el valor existe y es numérico antes de sumarlo
            $monto = isset($valor['valor']) && is_numeric($valor['valor']) ? floatval($valor['valor']) : 0;

            $temp_retiros += $monto; // Sumar correctamente
        }


        //$printer->text("Total retiros: " . "         $ " . number_format($total_retiros, 0, ",", ".") . "\n");
        $printer->text("Total retiros: " . "         $ " . number_format($temp_retiros, 0, ",", ".") . "\n");

        $printer->text("\n");

        $printer->text("------------------------------------------------\n ");
        $printer->text("DEVOLUCIONES \n");
        $printer->text("------------------------------------------------\n ");
        $printer->text("\n");

        $devolucion_venta = model('devolucionModel')->where('id_apertura', $id_apertura)->findAll();


        $temp_devoluciones = 0;
        $total_devoluciones = 0;

        foreach ($devolucion_venta as $detalle) {

            $printer->text("FECHA:" . $detalle['fecha'] . " " . date("g:i a", strtotime($detalle['hora'])) . "\n");
            $codigo_interno_producto = model('detalleDevolucionVentaModel')->select('codigo')->where('id_devolucion_venta', $detalle['id'])->first();
            $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigo'])->first();
            $printer->text("PRODUCTO: " . $nombre_producto['nombreproducto'] . "\n");
            $valor = model('devolucionVentaEfectivoModel')->select('valor')->where('iddevolucion', $detalle['id'])->first();
            $printer->text("VALOR " . "$" . number_format($valor['valor'], 0, ",", ".") . "\n");
            $printer->text("\n");


            $temp_devoluciones = $temp_devoluciones + $valor['valor'];
            $total_devoluciones = $temp_devoluciones;
        }

        $printer->text("Total devoluciones:" . "     $ " . number_format($total_devoluciones, 0, ",", ".") . "\n");

        $printer->text("\n");
        $printer->text("------------------------------------------------\n");
        $printer->text("Ingresos-(Retiros-Devoluciones) \n");
        $printer->text("------------------------------------------------\n");

        $printer->text("Ingresos a caja " . "        $ " . number_format($ingresos_efectivo[0]['efectivo'] + $valor_apertura['valor'], 0, ",", ".") . "\n");

        //$printer->text("(-) Total retiros: " . "     $ " . number_format($total_retiros, 0, ",", ".") . "\n");
        $printer->text("(-) Total retiros: " . "     $ " . number_format($temp_retiros, 0, ",", ".") . "\n");
        $printer->text("(-) Total devoluciones:" . " $ " . number_format($total_devoluciones, 0, ",", ".") . "\n");

        $temp = $ingresos_efectivo[0]['efectivo'] + $valor_apertura['valor'];
        //$total_caja = $total_retiros + $total_devoluciones;
        $total_caja = $temp_retiros + $total_devoluciones;
        $total_en_caja = $temp - $total_caja;

        $printer->text("(=) Efectivo en caja:   $ " . number_format($total_en_caja, 0, ",", ".") . "\n");

        $printer->text("\n");
        $printer->text("-----------------------------------------------\n");
        $printer->text("Cierre de caja \n");
        $printer->text("-----------------------------------------------\n");
        //$printer->text("Efectivo en caja   " . "     $ " . number_format($total_en_caja, 0, ",", ".") . " \n");


        $id_cierre = model('cierreModel')->select('id')->where('idapertura', $id_apertura)->first();

        if (!empty($id_cierre)) {
            $valor_cierre_efectivo_usuario = model('cierreFormaPagoModel')->valor_cierre_efectivo_usuario($id_cierre['id']);
        }



        if (empty($valor_cierre_efectivo_usuario)) {
            $cierre_usuario = 0;
        }
        if (!empty($valor_cierre_efectivo_usuario)) {
            $cierre_usuario =  $valor_cierre_efectivo_usuario[0]['valor'];
        }

        $printer->text("\n");

        if (empty($ingresos_transaccion)) {
            $transaccion = 0;
        }

        if (!empty($ingresos_transaccion)) {
            $transaccion = $ingresos_transaccion[0]['transferencia'];
        }

        if (!empty($id_cierre)) {
            $valor_cierre_transaccion_usuari = model('cierreFormaPagoModel')->valor_cierre_transaccion_usuario($id_cierre['id']);
        }


        if (empty($valor_cierre_transaccion_usuari)) {
            $valor_cierre_transaccion_usuario = 0;
        }
        if (!empty($valor_cierre_transaccion_usuari)) {
            $valor_cierre_transaccion_usuario = $valor_cierre_transaccion_usuari[0]['valor'];
        }

        $printer->text("Efectivo caja: " . "              $ " . number_format($total_en_caja, 0, ",", ".") . "\n");
        $printer->text("Cierre efectivo  " . "            $ " .  number_format($cierre_usuario, 0, ",", ".") .  "\n");
        $printer->text("Diferencia efectivo  " . "        $ " . number_format(($cierre_usuario) - $total_en_caja, 0, ",", ".") . "\n\n");

        $printer->text("Transacciones caja: " . "         $ " . number_format($transaccion, 0, ",", ".") . "\n");
        $printer->text("Cierre transacciones  " . "       $ " .  number_format($valor_cierre_transaccion_usuario, 0, ",", ".") .  "\n");


        /*  if ($total_en_caja > $cierre_usuario) {
        $printer->text("Diferencia efectivo  " . "   $ " . number_format(($total_en_caja) - $cierre_usuario, 0, ",", ".") . "\n");
        } */
        /*   if ($total_en_caja < $cierre_usuario) {
        $printer->text("Diferencia efectivo  " . "   $ " . number_format(($cierre_usuario) - $total_en_caja , 0, ",", ".") . "\n");
        } */


        $printer->text("Diferencia transaccion  " .      "     $ " . number_format($valor_cierre_transaccion_usuario -  $transaccion, 0, ",", ".") . "\n");

        $printer->text("\n");

        /*   if ($total_en_caja > $cierre_usuario) {
            $printer->text("TOTAL DIFERENCIAS  " . "     $ " . number_format((($total_en_caja) - $cierre_usuario) + ($transaccion - $valor_cierre_transaccion_usuario), 0, ",", ".") . "\n");
        }
        if ($total_en_caja < $cierre_usuario) {
            $printer->text("TOTAL DIFERENCIAS  " . "     $ " . number_format((($cierre_usuario ) - $total_en_caja ) + ($transaccion - $valor_cierre_transaccion_usuario), 0, ",", ".") . "\n");
        } */
        $printer->text("TOTAL DIFERENCIAS  " . "          $ " . number_format((($cierre_usuario) - $total_en_caja) + ($valor_cierre_transaccion_usuario  - $transaccion), 0, ",", ".") . "\n");
        $printer->text("\n");

        $printer->feed(1);
        $printer->cut();

        $printer->close();
    }


    function imprimir_factura_electronica($id_factura)  // Cuando es prefactura 
    {

        $id_factura = $id_factura;
        //$id_factura = 24;
        $id_impresora = model('cajaModel')->select('id_impresora')->first();
        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();
        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);

        $id_estado = model('facturaElectronicaModel')->select('id_status')->where('id', $id_factura)->first();
        $numero = model('facturaElectronicaModel')->select('numero')->where('id', $id_factura)->first();




        if ($id_estado['id_status'] == 1) {
            $estado = "PENDIENTE";
        }
        if ($id_estado['id_status'] == 2) {
            $estado = "FIRMADO";
        }

        $nit_cliente = model('facturaElectronicaModel')->select('nit_cliente')->where('id', $id_factura)->first();
        $nombres_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();
        $direccion = model('clientesModel')->select('direccioncliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();
        $telefono = model('clientesModel')->select('telefonocliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();
        $email = model('clientesModel')->select('emailcliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();


        $datos_empresa = model('empresaModel')->datosEmpresa();
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
        $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento'] . "\n");
        $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");

        $id_regimen = model('empresaModel')->select('idregimen')->first();
        $regimen = model('regimenModel')->select('descripcion')->where('idregimen', $id_regimen['idregimen'])->first();

        $printer->text($regimen['descripcion']);
        /*  $printer->text($datos_empresa[0]['nombreregimen'] . "\n"); */
        $printer->text("\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("ORDEN DE PEDIDO  " . $numero['numero'] . "\n");
        $printer->text("TIPO DE VENTA:   PREFACTURA DE VENTA  \n");
        $printer->text("FECHA:           " . date('Y-m-d') . "\n");
        $printer->text("CAJA:            1"  . "\n");
        $printer->text("CAJERO:          Usuario administrador"  . "\n");
        $printer->text("\n");
        $items = model('itemFacturaElectronicaModel')->where('id_de', $id_factura)->findAll();


        $printer->text("---------------------------------------------" . "\n");
        $printer->text("CLIENTE:         " . $nombres_cliente['nombrescliente'] . "\n");
        $printer->text("NIT :            " . $nit_cliente['nit_cliente']  . "\n");
        $printer->text("DIRECCIÓN:       " . $direccion['direccioncliente']  . "\n");
        $printer->text("TELEFÓNO         " . $telefono['telefonocliente'] . "\n");
        $printer->text("EMAIL:           " . $email['emailcliente'] . "\n");


        $printer->text("---------------------------------------------" . "\n");
        $printer->text("CÓDIGO    DESCRIPCIÓN   VALOR UNITARIO    TOTAL" . "\n");
        $printer->text("---------------------------------------------" . "\n");




        foreach ($items as $productos) {

            $printer->setTextSize(1, 1);
            $printer->text("Cod." . $productos['codigo'] . "      " . $productos['descripcion'] . "\n");
            $printer->text("Cant. " . $productos['cantidad'] . "      " . "$" . number_format($productos['neto'], 0, ',', '.') . "                   " . "$" . number_format($productos['neto'] * $productos['cantidad'], 0, ',', '.') . "\n");
            if (!empty($productos['nota_producto'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("NOTAS:\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text($productos['nota_producto'] . "\n");
            }

            $printer->text("\n");
        }


        $inc = model('kardexModel')->get_total_inc($id_factura);
        $iva = model('kardexModel')->get_total_iva($id_factura);



        //$total =  model('pagosModel')->select('total_documento')->where('id_factura', $id_factura)->first();
        $total =  model('kardexModel')->get_total_factura($id_factura);

        //$total =  model('pagosModel')->select('total_documento')->where('id_factura', $id_factura)->first();
        $cambio =  model('pagosModel')->select('cambio')->where('id_factura', $id_factura)->first();
        $propina =  model('pagosModel')->select('propina')->where('id_factura', $id_factura)->first();


        $transferencia =  model('kardexModel')->get_recibido_transferencia($id_factura);
        $efectivo =  model('kardexModel')->get_recibido_efectivo($id_factura);

        $sub_total = ($total[0]['valor'] - ($inc[0]['total_inc'] + $iva[0]['total_iva'])) - $propina['propina'];




        $printer->text("_______________________________________________ \n");
        $printer->setJustification(Printer::JUSTIFY_RIGHT);

        /*   if ($id_regimen['idregimen'] == 1) {

            $printer->text("SUB TOTAL : "   .    "$ ".number_format($sub_total, 0, ",", ".") . "\n");
            $printer->text("INC       :"    .    "$ ".number_format($inc[0]['total_inc'], 0, ",", ".") . "\n");
            $printer->text("IVA       :"    .    "$ ".number_format($iva[0]['total_iva'], 0, ",", ".") . "\n");
            $printer->text("PROPINA   :"    .    "$ ".number_format($propina['propina'], 0, ",", ".") . "\n");
        } */
        $printer->text(str_pad("SUB TOTAL", 15) . ": " . str_pad("$ " . number_format($sub_total, 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");

        if ($id_regimen['idregimen'] == 1) {
            $printer->text(str_pad("INC", 15) . ": " . str_pad("$ " . number_format($inc[0]['total_inc'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("IVA", 15) . ": " . str_pad("$ " . number_format($iva[0]['total_iva'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
        }
        //$printer->text("\n");
        $printer->text(str_pad("PROPINA", 15) . ": " . str_pad("$ " . number_format($propina['propina'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
        $printer->text("\n");
        $printer->setTextSize(1, 2);
        $printer->setEmphasis(true); // Negrita (resaltado)
        $printer->text(str_pad(" TOTAL", 15) . ":" . "$ " . number_format($total[0]['valor'], 0, ",", ".") . "\n");
        $printer->setEmphasis(false); // Desactiva la negrita
        $printer->text("\n");
        $printer->setTextSize(1, 1);



        if ($efectivo[0]['recibido_efectivo'] > 0) {
            $printer->text(str_pad("PAGO EFECTIVO ", 15) . ": " . str_pad("$ " . number_format($efectivo[0]['recibido_efectivo'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
        }

        if ($transferencia[0]['recibido_transferencia'] > 0) {
            $printer->text(str_pad("PAGO TRANSFERENCIA", 15) . ": " . str_pad("$ " . number_format($transferencia[0]['recibido_transferencia'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
        }

        $printer->text(str_pad("CAMBIO", 15) . ": " . str_pad("$ " . number_format($cambio['cambio'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");

        $temp_encabezado = model('ConfiguracionPedidoModel')->select('encabezado_factura')->first();
        $encabezado = $temp_encabezado['encabezado_factura'];
        //$printer->text("$encabezado \n");

        $total = model('facturaElectronicaModel')->select('total')->where('id', $id_factura)->first();
        $inc_tarifa = model('kardexModel')->get_inc_calc($id_factura);

        $iva_tarifa = model('kardexModel')->get_iva_calc($id_factura);


        if ($id_regimen['idregimen'] == 1) {
            if (!empty($inc_tarifa)) {
                $printer->text("** DISCRIMINACIÓN DE TARIFAS DE INC **   \n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);

                $printer->text(str_pad("TARIFA", 10, " ") . str_pad("BASE", 15, " ") . str_pad(" INC ",  15, " ") .            "VENTA\n");
                foreach ($inc_tarifa as $detalle) {
                    $inc = model('kardexModel')->get_tarifa_ico($id_factura, $detalle['valor_ico']);
                    $tarifa = $inc_tarifa[0]['valor_ico'] . " %";
                    $base_inc = number_format(($total['total'] - $inc[0]['inc']), 0, ",", ".");
                    $inc = number_format(($total['total'] - ($total['total'] - $inc[0]['inc'])), 0, ",", ".");
                    $venta = number_format($total['total'], 0, ",", ".");

                    $printer->text(str_pad($tarifa, 10, " ") . str_pad($base_inc, 15, " ") . " " .   str_pad($inc, 15, " ") . "  " . $venta . "\n");
                }
            }

            if (!empty($iva_tarifa)) {

                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("** DISCRIMINACIÓN DE TARIFAS DE IVA **   \n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);


                $printer->text(str_pad("TARIFA", 10, " ") . str_pad("BASE", 10, " ") . str_pad("IVA", 10, " ") . "VENTA\n");


                foreach ($iva_tarifa as $detalle) {
                    $iva = model('kardexModel')->get_tarifa_iva($id_factura, $detalle['valor_iva']);  //Esto es el valor total del iva 
                    $venta_total_iva = model('kardexModel')->get_base_iva($id_factura, $detalle['valor_iva']); // Esto es el valor total de la venta 

                    $tarifa = $detalle['valor_iva'] . " %";
                    //$base_iva = number_format(($total['total'] - $iva[0]['iva']), 0, ",", ".");
                    $base_iva = number_format(($venta_total_iva[0]['total_iva'] - $iva[0]['iva']), 0, ",", ".");
                    $iva = number_format($iva[0]['iva'], 0, ",", ".");
                    //$venta = number_format($total['total'], 0, ",", ".");
                    $venta = number_format($venta_total_iva[0]['total_iva'], 0, ",", ".");

                    $printer->text(str_pad($tarifa, 10, " ") . str_pad("$" . $base_iva, 10, " ") . str_pad("$" . $iva, 10, " ") . "$" . $venta . "\n");
                }
            }
        }



        $printer->text("_______________________________________________ \n\n");
        $nota = model('facturaElectronicaModel')->select('nota')->where('id', $id_factura)->first();
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        if (!empty($nota['nota'])) {
            $printer->text("Nota:" . $nota['nota']);
        }
        $printer->text("\n");
        $printer->text("\n");
        $total = model('facturaElectronicaModel')->select('total')->where('id', $id_factura)->first();
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text("SOFTWARE DFPYME INTREDETE. \n");
        $printer->text("INTREDETE 901448365\n");
        $printer->text("Proveedor tecnológico\n");
        $printer->text("DATAICO SAS 901223648\n");
        $printer->text("\n");

        $printer->text("*GRACIAS POR SER NUESTROS CLIENTES* \n");

        $printer->feed(1);
        $printer->cut();

        $printer->close();

        $returnData = array(
            "resultado" => 1
        );
        echo  json_encode($returnData);
    }


    function impresion_factura_electronica($id_factura)  //Ya ha sido trasmitoda 
    {
        //$id_factura = 2026;

        $id_factura = $id_factura;
        $id_impresora = model('cajaModel')->select('id_impresora')->first();
        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();
        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);


        $id_estado = model('facturaElectronicaModel')->select('id_status')->where('id', $id_factura)->first();
        $numero = model('facturaElectronicaModel')->select('numero')->where('id', $id_factura)->first();

        $fecha = model('facturaElectronicaModel')->select('fecha')->where('id', $id_factura)->first();
        $hora = model('facturaElectronicaModel')->select('hora')->where('id', $id_factura)->first();


        if ($id_estado['id_status'] == 1) {
            $estado = "PENDIENTE";
        }
        if ($id_estado['id_status'] == 2) {
            $estado = "FIRMADO";
        }

        $nit_cliente = model('facturaElectronicaModel')->select('nit_cliente')->where('id', $id_factura)->first();
        $nombres_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();
        $direccion = model('clientesModel')->select('direccioncliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();
        $telefono = model('clientesModel')->select('telefonocliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();
        $email = model('clientesModel')->select('emailcliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();


        $datos_empresa = model('empresaModel')->datosEmpresa();
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT: " . $datos_empresa[0]['nitempresa'] . "\n");
        $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento'] . "\n");
        $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");

        $temp_descripcion = model('regimenModel')->select('descripcion')->where('idregimen', $datos_empresa[0]['idregimen'])->first();
        $descripcion = $temp_descripcion['descripcion'];

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text($descripcion);



        // Obtener el encabezado de la base de datos
        $temp_encabezado = model('ConfiguracionPedidoModel')->select('encabezado_factura')->first();
        $encabezado = $temp_encabezado['encabezado_factura'];
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("$encabezado \n");

        $printer->text("\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("FACTURA ELECTRÓNICA DE VENTA NÚMERO: " . $numero['numero'] . "\n");
        $printer->text("TIPO DE VENTA:   ELECTRÓNICA DE CONTADO \n");
        /* $printer->text("FECHA Y HORA DE GENERACIÓN: " . $fecha['fecha'] ." ".$hora['hora'] ."\n"); */
        $printer->text("FECHA GENERACIÓN: " . $fecha['fecha'] . "      HORA: " . $hora['hora'] . "\n");
        $printer->text("FECHA VALIDACIÓN: " . $fecha['fecha'] . "      HORA: " . $hora['hora'] . "\n");

        $printer->text("CAJA:            1"  . "\n");
        $printer->text("CAJERO:          Usuario administrador"  . "\n");
        $printer->text("\n");




        $items = model('kardexModel')->get_productos_factura($id_factura);

        $printer->text("---------------------------------------------" . "\n");
        $printer->text("CLIENTE:     " . $nombres_cliente['nombrescliente'] . "\n");
        $printer->text("NIT/CC:      " . $nit_cliente['nit_cliente']  . "\n");
        $printer->text("DIRECCIÓN:   " . $direccion['direccioncliente']  . "\n");
        $printer->text("TELEFÓNO    " . $telefono['telefonocliente'] . "\n");
        $printer->text("EMAIL:       " . $email['emailcliente'] . "\n");


        $printer->text("---------------------------------------------" . "\n");
        $printer->text("CÓDIGO  DESCRIPCIÓN  VALOR UNITARIO  TOTAL   IMP" . "\n");
        $printer->text("---------------------------------------------" . "\n");


        foreach ($items as $productos) {

            $printer->setTextSize(1, 1);
            $printer->text("Cod." . $productos['codigo'] . "      " . $productos['descripcion'] . "\n");

            if ($productos['aplica_ico'] == 't') {
                $impuesto = $productos['valor_ico'];
            }
            if ($productos['aplica_ico'] == 'f') {
                $impuesto = $productos['valor_iva'];
            }



            $printer->text("Cant. " . $productos['cantidad'] . "      " . "$" . number_format($productos['neto'], 0, ',', '.') . "               " . "$" . number_format($productos['total'], 0, ',', '.') . " " .  $impuesto . "%" . "\n");
            if (!empty($productos['nota_producto'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("NOTAS:\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text($productos['nota_producto'] . "\n");
            }

            $printer->text("\n");
        }

        $inc = model('kardexModel')->get_total_inc($id_factura);
        $iva = model('kardexModel')->get_total_iva($id_factura);

        $total =  model('kardexModel')->get_total_factura($id_factura);

        $transferencia =  model('kardexModel')->get_recibido_transferencia($id_factura);
        $efectivo =  model('kardexModel')->get_recibido_efectivo($id_factura);
        $propina =  model('pagosModel')->select('propina')->where('id_factura', $id_factura)->first();

        $sub_total = ($total[0]['valor'] - ($inc[0]['total_inc'] + $iva[0]['total_iva'])) - $propina['propina'];


        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("_______________________________________________ \n");
        $id_regimen = model('empresaModel')->select('idregimen')->first();
        $printer->text(str_pad("SUB TOTAL", 15) . ": " . str_pad("$ " . number_format($sub_total, 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");

        if ($id_regimen['idregimen'] == 1) {
            $printer->text(str_pad("INC", 15) . ": " . str_pad("$ " . number_format($inc[0]['total_inc'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("IVA", 15) . ": " . str_pad("$ " . number_format($iva[0]['total_iva'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
        }
        //$printer->text("\n");
        $printer->text(str_pad("PROPINA", 15) . ": " . str_pad("$ " . number_format($propina['propina'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
        $printer->text("\n");
        $printer->setTextSize(1, 2);
        $printer->setEmphasis(true); // Negrita (resaltado)
        $printer->text(str_pad(" TOTAL", 15) . ":" . "$ " . number_format($total[0]['valor'], 0, ",", ".") . "\n");
        $printer->setEmphasis(false); // Desactiva la negrita
        $printer->text("\n");
        $printer->setTextSize(1, 1);
        $printer->text("\n");
        $printer->setTextSize(1, 1);



        $cambio = model('kardexModel')->cambio($id_factura);
        if ($efectivo[0]['recibido_efectivo'] > 0) {
            $printer->text(str_pad("PAGO EFECTIVO ", 15) . ": " . str_pad("$ " . number_format($efectivo[0]['recibido_efectivo'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
        }

        if ($transferencia[0]['recibido_transferencia'] > 0) {
            $printer->text(str_pad("PAGO TRANSFERENCIA", 15) . ": " . str_pad("$ " . number_format($transferencia[0]['recibido_transferencia'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
        }

        $printer->text(str_pad("CAMBIO", 15) . ": " . str_pad("$ " . number_format($cambio[0]['cambio'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");



        $printer->text("_______________________________________________ \n\n");
        $total = model('facturaElectronicaModel')->select('total')->where('id', $id_factura)->first();

        $inc_tarifa = model('kardexModel')->get_inc_calc($id_factura);
        $iva_tarifa = model('kardexModel')->get_iva_calc($id_factura);



        $items = model('kardexModel')->items($id_factura);
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Numero de items: " . $items[0]['id'] .  "\n");
        $printer->text("_______________________________________________ \n");

        //if (!empty($inc_tarifa)) {

        $printer->setJustification(Printer::JUSTIFY_CENTER);

        if ($id_regimen['idregimen'] == 1) {
            if (!empty($inc_tarifa)) {
                $printer->text("** DISCRIMINACIÓN DE TARIFAS DE INC **   \n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);


                $printer->text(str_pad("TARIFA", 10, " ") . str_pad("BASE", 12, " ") . str_pad("INC", 12, " ") . "VENTA\n");



                foreach ($inc_tarifa as $detalle) {
                    $inc = model('kardexModel')->get_tarifa_ico($id_factura, $detalle['valor_ico']);
                    $tarifa = $inc_tarifa[0]['valor_ico'] . " %";
                    $base_inc = number_format(($total['total'] - $inc[0]['inc']), 0, ",", ".");
                    $inc = number_format(($inc[0]['inc']), 0, ",", ".");
                    $venta = number_format($total['total'], 0, ",", ".");

                    $printer->text(str_pad($tarifa, 10, " ") . "$" . str_pad($base_inc, 12, " ") . "$" . str_pad($inc, 12, " ") . "$" . $venta . "\n");
                }
            }



            if (!empty($iva_tarifa)) {

                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("** DISCRIMINACIÓN DE TARIFAS DE IVA **   \n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);

                $printer->text(str_pad("TARIFA", 10, " ") . str_pad("BASE", 10, " ") . str_pad("IVA", 10, " ") . "VENTA\n");

                foreach ($iva_tarifa as $detalle) {
                    $iva = model('kardexModel')->get_tarifa_iva($id_factura, $detalle['valor_iva']);  //Esto es el valor total del iva 
                    $venta_total_iva = model('kardexModel')->get_base_iva($id_factura, $detalle['valor_iva']); // Esto es el valor total de la venta 

                    $tarifa = $detalle['valor_iva'] . " %";
                    //$base_iva = number_format(($total['total'] - $iva[0]['iva']), 0, ",", ".");
                    $base_iva = number_format(($venta_total_iva[0]['total_iva'] - $iva[0]['iva']), 0, ",", ".");
                    $iva = number_format($iva[0]['iva'], 0, ",", ".");
                    //$venta = number_format($total['total'], 0, ",", ".");
                    $venta = number_format($venta_total_iva[0]['total_iva'], 0, ",", ".");

                    $printer->text(str_pad($tarifa, 10, " ") . str_pad("$" . $base_iva, 10, " ") . str_pad("$" . $iva, 10, " ") . "$" . $venta . "\n");
                }
            }
        }
        //}

        $id_resolucion = model('facturaElectronicaModel')->select('id_resolucion')->where('id', $id_factura)->first();

        //dd( $id_resolucion  )


        $datos_resolucion = model('resolucionElectronicaModel')->where('id', $id_resolucion['id_resolucion'])->first();

        $printer->text("\n");
        $printer->text("Resolución DIAN No " . $datos_resolucion['numero'] . " de " . $datos_resolucion['date_begin'] . "\n");
        $printer->text("del " . $datos_resolucion['number_begin'] . " al " . $datos_resolucion['number_end'] . " prefijo " . $datos_resolucion['prefijo'] . "\n");
        $printer->text("\n");

        $qr = model('facturaElectronicaModel')->select('qrcode')->where('id', $id_factura)->first();
        $cufe = model('facturaElectronicaModel')->select('cufe')->where('id', $id_factura)->first();
        //$printer->qrCode($qr['qrcode']);
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Representación gráfica de factura electrónica \n");

        $printer->text("número: " . $numero['numero'] . "\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->qrCode($qr['qrcode'], Printer::QR_ECLEVEL_L, 4);

        $printer->text("\n");

        $printer->text("CUFE: \n" . $cufe['cufe'] . "\n");

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text("_______________________________________________ ");
        $printer->text("\n");
        $temp_pie = model('ConfiguracionPedidoModel')->select('pie_factura')->first();
        $pie = $temp_pie['pie_factura'];
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("$pie \n");
        $printer->text("\n");

        $nota = model('facturaElectronicaModel')->select('nota')->where('id', $id_factura)->first();
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        if (!empty($nota['nota'])) {
            $printer->text("Nota:" . $nota['nota']);
        }
        $printer->text("\n");
        $printer->text("\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("SOFTWARE DFPYME \n");
        $printer->text("INTREDETE 901448365 \n");
        $printer->text("Proveedor tecnológico \n");
        $printer->text("DATAICO SAS  901223648\n\n");
        $printer->text("*GRACIAS POR SER NUESTROS CLIENTES*\n");

        $printer->feed(1);
        $printer->cut();

        $printer->close();

        $returnData = array(
            "resultado" => 1
        );
        echo  json_encode($returnData);
    }


    public function imprimir_factura($id_factura)
    {

        //$id_factura = 28;

        $id_factura = $id_factura;


        $numero_factura = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura)->first();

        $regimen = model('empresaModel')->select('idregimen')->first();

        if (!empty($numero_factura['numerofactura_venta'])) {

            $numero_factura['numerofactura_venta'];


            $fecha_factura_venta = model('facturaVentaModel')->select('fecha_factura_venta')->where('id', $id_factura)->first();
            $hora_factura_venta = model('facturaVentaModel')->select('horafactura_venta')->where('id', $id_factura)->first();
            $id_usuario = model('facturaVentaModel')->select('idusuario_sistema')->where('id', $id_factura)->first();
            $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario['idusuario_sistema'])->first();
            $datos_empresa = model('empresaModel')->datosEmpresa();

            $nit_cliente = model('facturaVentaModel')->select('nitcliente')->where('id', $id_factura)->first();
            $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $nit_cliente['nitcliente'])->first();

            $id_impresora = model('cajaModel')->select('id_impresora')->first();

            $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

            $connector = new WindowsPrintConnector($nombre_impresora['nombre']);

            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 2);
            $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
            $printer->setTextSize(1, 1);
            $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
            $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
            $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento'] . "\n");
            $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");
            $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
            $id_regimen = model('empresaModel')->select('idregimen')->first();
            $regimen = model('regimenModel')->select('descripcion')->where('idregimen', $id_regimen['idregimen'])->first();
            //$printer->text($regimen['descripcion']);
            //$printer->text(" Responsable de IVA – INC \n");
            $printer->text("\n");


            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $estado_factura = model('facturaVentaModel')->estado_factura($id_factura);
            if ($estado_factura[0]['idestado'] == 1 or $estado_factura[0]['idestado'] == 2) {
                $printer->text("FACTURA DE VENTA: " . $numero_factura['numerofactura_venta'] . "\n");
            }

            if ($estado_factura[0]['idestado'] == 7) {
                $printer->text("REMISION : " . $numero_factura['numerofactura_venta'] . "\n");
            }



            $printer->text("TIPO DE VENTA:" . $estado_factura[0]['descripcionestado'] . "\n");

            $printer->text("FECHA:" . " " . $fecha_factura_venta['fecha_factura_venta'] . "  " . date("g:i a", strtotime($hora_factura_venta['horafactura_venta'])) . "\n");
            if ($estado_factura[0]['idestado'] == 2) {
                $printer->text("FECHA LIMITE:" . $estado_factura[0]['fechalimitefactura_venta'] . "\n");
            }
            $printer->text("CAJA : 1" . "\n");
            $printer->text("CAJERO: " . $nombre_usuario['nombresusuario_sistema'] . "\n");

            $printer->text("---------------------------------------------" . "\n");
            $printer->text("CLIENTE :" . " " . $nombre_cliente['nombrescliente'] . "\n");
            $printer->text("NIT     :" . " " . number_format($nit_cliente['nitcliente'], 0, ",", ".") . "\n");
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



            $id_estado = model('facturaVentaModel')->select('idestado')->where('id', $id_factura)->first();


            $inc = model('kardexModel')->get_inc_pos($id_factura, $id_estado['idestado']);
            $iva = model('kardexModel')->get_iva_pos($id_factura, $id_estado['idestado']);


            $printer->text("---------------------------------------------" . "\n");
            $total = model('productoFacturaVentaModel')->selectSum('total')->where('id_factura', $id_factura)->find();

            $printer->setJustification(Printer::JUSTIFY_RIGHT);

            $impuesto_saludable = model('productoFacturaVentaModel')->get_impuesto_saluidable($id_factura);


            if ($id_regimen['idregimen'] == 1) {

                if ($estado_factura[0]['idestado'] == 1 or $estado_factura[0]['idestado'] == 2 or $estado_factura[0]['idestado'] == 6) {


                    //$printer->text("SUB TOTAL :" . "$" . number_format($total[0]['total']-($inc[0]['inc']), 0, ",", ".") . "\n");


                    if (!empty($iva[0]['iva'])) {
                        $printer->text("SUB TOTAL :" . "$" . number_format($total[0]['total'] - ($iva[0]['iva']), 0, ",", ".") . "\n");
                        $printer->text("IVA       :" . "$" . number_format($iva[0]['iva'], 0, ",", ".") . "\n");
                    }

                    if (!empty($inc[0]['inc'])) {
                        $printer->text("SUB TOTAL :" . "$" . number_format($total[0]['total'] - ($inc[0]['inc']), 0, ",", ".") . "\n");
                        $printer->text("IMPUESTO AL CONSUMO :" . "$" . number_format($inc[0]['inc'], 0, ",", ".") . "\n");
                    }
                }
            }




            $descuento = model('facturaVentaModel')->select('descuento')->where('id', $id_factura)->first();
            $printer->text("DESCUENTO :" . "$" . number_format($descuento['descuento'], 0, ",", ".") . "\n");

            $propina = model('facturaVentaModel')->select('propina')->where('id', $id_factura)->first();
            $printer->text("PROPINA :" . "$" . number_format($propina['propina'], 0, ",", ".") . "\n\n");
            $printer->setTextSize(2, 2);
            $printer->text("TOTAL :" . "$" . number_format(($total[0]['total'] - $descuento['descuento']) + $propina['propina'], 0, ",", ".") . "\n\n");

            $efectivo = model('facturaFormaPagoModel')->selectSum('valor_pago')->where('id_factura', $id_factura)->find();
            $printer->setTextSize(1, 1);
            $id_forma_pago = model('facturaFormaPagoModel')->id_forma_pago($id_factura);
            $temp_cambio = 0;
            foreach ($id_forma_pago as $forma_pago) {
                $id_forma_pago = model('facturaFormaPagoModel')->id_forma_pago($id_factura);
                $nombre_forma_pago = model('facturaFormaPagoModel')->nombre_forma_pago($forma_pago['idforma_pago']);
                $valor_forma_pago = model('facturaFormaPagoModel')->valor_forma_pago($forma_pago['idforma_pago'], $id_factura);

                if ($valor_forma_pago[0]['valor_pago'] > 0) {
                    $printer->text($nombre_forma_pago[0]['nombreforma_pago'] . ":  $" . number_format($valor_forma_pago[0]['valor_pago'], 0, ",", ".") . "\n");
                }
            }
            if ($estado_factura[0]['idestado'] == 6) {
                $printer->text("Efectivo : 0\n");
                $printer->text("Cambio   : 0\n");
            }
            if ($estado_factura[0]['idestado'] == 1 or $estado_factura[0]['idestado'] == 7) {
                $printer->text("CAMBIO: " . "$" . number_format($efectivo[0]['valor_pago'] - (($total[0]['total'] - $descuento['descuento']) + $propina['propina']), 0, ",", ".") . "\n");
                $printer->text("-----------------------------------------------" . "\n");
            }
            $regimen = model('empresaModel')->select('idregimen')->first();

            if ($regimen['idregimen'] == 1) {
                if ($estado_factura[0]['idestado'] == 1 or $estado_factura[0]['idestado'] == 2 or $estado_factura[0]['idestado'] == 6) {
                    //$tarifa_iva = model('productoFacturaVentaModel')->tarifa_iva($id_factura);
                    $tarifa_iva = model('kardexModel')->iva_producto($id_factura, $estado_factura[0]['idestado']);



                    //dd($inc);

                    if (!empty($iva[0]['iva'])) {
                        $printer->setJustification(Printer::JUSTIFY_CENTER);
                        $printer->setTextSize(1, 1);
                        $printer->text("**DISCRIMINACION TARIFAS DE IVA** \n");
                        $printer->setJustification(Printer::JUSTIFY_LEFT);
                        $printer->text("TARIFA    VENTA       BASE/IMP         IVA" . "\n");
                        foreach ($tarifa_iva as $iva) {
                            // $datos_iva = model('productoFacturaVentaModel')->base_iva($iva['valor_iva'], $id_factura);
                            $datos_iva = model('kardexModel')->total_iva_producto_pos($id_factura, $estado_factura[0]['idestado']);
                            if (!empty($datos_iva)) {
                                $printer->text($iva['porcentaje_iva'] . "%" . "       " . "$" . number_format($total[0]['total'], 0, ",", ".") . "   " . "$" . number_format($total[0]['total'] - $datos_iva[0]['iva'], 0, ",", ".") . "    " . "$" . number_format(($datos_iva[0]['iva']), 0, ",", ".") . "\n");
                            }
                        }
                    }

                    $tarifa_ico = model('productoFacturaVentaModel')->tarifa_ico($id_factura);



                    if (!empty($inc[0]['inc'])) {
                        $printer->text("\n");
                        $printer->setJustification(Printer::JUSTIFY_CENTER);
                        $printer->setTextSize(1, 1);
                        $printer->text("**DISCRIMINACION TARIFAS DE INC** \n");
                        $printer->setJustification(Printer::JUSTIFY_LEFT);
                        $printer->text("TARIFA   BASE/IMP        INC     TOTAL" . "\n");


                        foreach ($tarifa_ico as $ico) {
                            //  $printer->text($iva['valor_iva']."%". "\n");


                            $total_compra = model('productoFacturaVentaModel')->total_compra($ico['valor_ico'], $id_factura);

                            $base_ico = model('productoFacturaVentaModel')->base_ico($ico['valor_ico'], $id_factura);

                            if ($total_compra[0]['compra'] >= 100000) {
                                $printer->text($ico['valor_ico'] . "%      " .  "$" . number_format(($total_compra[0]['compra'] - ($base_ico[0]['base']) - $impuesto_saludable[0]['total_impuesto_saludable']), 0, ",", ".") . "        " . "$" . number_format($base_ico[0]['base'], 0, ",", ".") . "       $" . number_format($total_compra[0]['compra'], 0, ",", ".") . "\n");
                            }
                            if ($total_compra[0]['compra'] < 100000) {
                                $printer->text($ico['valor_ico'] . "%" . "       " . "$ " . number_format($total_compra[0]['compra'] - ($base_ico[0]['base']), 0, ",", ".") . "      " . "   $" . number_format($base_ico[0]['base'], 0, ",", ".") . "    $" . number_format($total_compra[0]['compra'], 0, ",", ".") . "\n");
                            }
                        }
                    }


                    // if ($estado_factura[0]['descripcionestado'] == 1 or $estado_factura[0]['descripcionestado'] == 2) {
                    $id_registro_dian = model('consecutivosModel')->select('numeroconsecutivo')->Where('idconsecutivos', 6)->first();


                    // $prefijo_factura = model('consecutivosModel')->select('numeroconsecutivo')->where('conceptoconsecutivo =', 'FacturaPrefijo')->first();
                    $prefijo_factura = model('dianModel')->select('inicialestatica')->where('iddian ', $id_registro_dian['numeroconsecutivo'])->first();

                    $id_resolucion_dian = model('consecutivosModel')->select('numeroconsecutivo')->where('conceptoconsecutivo =', 'IdRegistroDian')->first();

                    $factura_prefijo = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', $id_resolucion_dian['numeroconsecutivo'])->first();

                    $fecha_dian = model('dianModel')->select('fechadian')->where('iddian', $id_resolucion_dian['numeroconsecutivo'])->first();
                    $rango_inicial = model('dianModel')->select('rangoinicialdian')->where('iddian', $id_resolucion_dian['numeroconsecutivo'])->first();
                    $rango_final = model('dianModel')->select('rangofinaldian')->where('iddian', $id_resolucion_dian['numeroconsecutivo'])->first();
                    $texto_inicial = model('dianModel')->select('texto_inicial')->where('iddian', $id_resolucion_dian['numeroconsecutivo'])->first();
                    $texto_final = model('dianModel')->select('texto_final')->where('iddian', $id_resolucion_dian['numeroconsecutivo'])->first();

                    $numero_resolucion_dian = model('dianModel')->select('numeroresoluciondian')->where('iddian', $id_resolucion_dian['numeroconsecutivo'])->first();
                    $printer->text("\n");
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->setTextSize(1, 1);
                    //$printer->text($texto_inicial['texto_inicial'] . $numero_resolucion_dian['numeroresoluciondian'] . " de" . " " . $fecha_dian['fechadian'] . "\n");
                    //$printer->text($texto_final['texto_final'] . " Del " . $rango_inicial['rangoinicialdian'] . " al " . " "  . $rango_final['rangofinaldian'] . " " . "Prefijo " . $prefijo_factura['inicialestatica'] . "\n\n");
                }
            }

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $fk_usuario_mesero = model('facturaVentaModel')->select('fk_usuario_mesero')->where('id', $id_factura)->first();
            $nombreusuario_sistema = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $fk_usuario_mesero['fk_usuario_mesero'])->first();
            $printer->text("ATENDIDO POR:" . $nombreusuario_sistema['nombresusuario_sistema'] . "\n");

            $observaciones_genereles = model('facturaVentaModel')->select('observaciones_generales')->where('id', $id_factura)->first();
            $fk_mesa = model('facturaVentaModel')->select('fk_mesa')->where('id', $id_factura)->first();
            $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $fk_mesa['fk_mesa'])->first();
            if (!empty($nombre_mesa['nombre'])) {
                $printer->text("MESA:" . $nombre_mesa['nombre'] . "\n");
            }
            if (empty($nombre_mesa['nombre'])) {
                $printer->text("MESA: VENTAS DE MOSTRADOR" . "\n");
            }

            if (!empty($observaciones_genereles['observaciones_generales'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(1, 2);
                $printer->text("OBSERVACIONES GENERALES\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(1, 2);
                $printer->text($observaciones_genereles['observaciones_generales'] . "\n");
            }

            $printer->text("\n");
            $printer->text("RESPONSABLE: _____________ " . "\n\n");
            $printer->text("AUTORIZA:    _____________ " . "\n");

            $printer->text("-----------------------------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 1);
            $printer->text("IMPRESO POR SOFTWARE DFPYME INTREDETE" . "\n");
            $printer->text("NIT: 901448365-5" . "\n");

            $printer->text("-----------------------------------------------" . "\n");
            $printer->text("GRACIAS POR SU VISITA " . "\n");





            $printer->feed(1);
            $printer->cut();
            //$printer->pulse();
            $printer->close();

            $returnData = array(
                "resultado" => 1, //Falta plata 
                "tabla" => view('factura_pos/tabla_reset_factura')
            );
            echo  json_encode($returnData);
        }
    }

    function imprimir_comprobnate_transferencia($id_factura, $transferencia, $efectivo, $total)
    {

        $id_impresora = model('cajaModel')->select('id_impresora')->first();
        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);
        $id_factura = $id_factura;

        $numero_factura = model('pagosModel')->select('documento')->where('id', $id_factura)->first();
        $fecha_factura_venta = model('pagosModel')->select('fecha')->where('id', $id_factura)->first();
        $hora_factura = model('pagosModel')->select('hora')->where('id', $id_factura)->first();
        $total_factura = model('pagosModel')->select('total_documento')->where('id', $id_factura)->first();
        $id_usuario = model('pagosModel')->select('id_usuario_facturacion')->where('id', $id_factura)->first();
        $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario['id_usuario_facturacion'])->first();
        $temp_transferencia = model('pagosModel')->select('transferencia')->where('id', $id_factura)->first();
        $temp_efectivo = model('pagosModel')->select('recibido_efectivo')->where('id', $id_factura)->first();
        $cambio = model('pagosModel')->select('cambio')->where('id', $id_factura)->first();

        $dateTime = new DateTime($hora_factura['hora']);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $hora_am_pm = $dateTime->format('h:i A'); // 'h' para 12 horas y 'A' para AM/PM
        $datos_empresa = model('empresaModel')->datosEmpresa();
        $printer->text("SOPORTE TRANSFERENCIA\n");
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
        $printer->text("-------------------------------------------" . "\n");
        $printer->text("\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setTextSize(1, 1);
        $printer->text("FECHA:                " . "" . $fecha_factura_venta['fecha'] . "   " . $hora_factura['hora'] . "\n");
        $printer->text("CAJA:                 " . "1" . "\n");
        $printer->text("USUARIO:              " .  $nombre_usuario['nombresusuario_sistema'] . "\n\n");
        $printer->text("NÚMERO DOCUMENTO:     " . $numero_factura['documento'] . "\n");
        $printer->text("VALOR  DOCUMENTO:     " . number_format($total_factura['total_documento'], 0, ",", ".") . "\n");
        $printer->text("VALOR  EFECTIVO:      " . number_format($temp_efectivo['recibido_efectivo'], 0, ",", ".") . "\n");
        $printer->text("VALOR  TRANSFERENCIA: " . number_format($temp_transferencia['transferencia'], 0, ",", ".") . "\n");
        $printer->text("CAMBIO:               " . number_format($cambio['cambio'], 0, ",", ".") . "\n");

        //$temp_total = model('pagosModel')->select('total_documento')->where('id', $id_factura)->first();
        //$temp_efectivo = model('pagosModel')->select('recibido_efectivo')->where('id', $id_factura)->first();

        //$cambio = model('pagosModel')->select('cambio')->where('id', $id_factura)->first();


        /*  $printer->setTextSize(1, 2);
        $printer->text(
            str_pad("Pago transferencia :", 20) .
                str_pad("$" . number_format($temp_transferencia['transferencia'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n"
        ); */


        $printer->text("\n\n");
        $printer->setTextSize(1, 1);

        $printer->text("Nota:____________________________________" . "\n\n");




        $printer->text("\n");



        $printer->feed(1);
        $printer->cut();
        $printer->pulse();
        $printer->close();
    }

    /*   function imprimir_comprobnate_transferencia($id_factura, $transferencia, $efectivo, $total)
    {

        $id_impresora = model('cajaModel')->select('id_impresora')->first();
        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);

        $datos_empresa = model('empresaModel')->datosEmpresa();
        $id_impresora = model('cajaModel')->select('id_impresora')->first();

        $id_factura = $id_factura;

        $numero_factura = model('pagosModel')->select('documento')->where('id', $id_factura)->first();

        $fecha_factura_venta = model('pagosModel')->select('fecha')->where('id', $id_factura)->first();
        $hora_factura = model('pagosModel')->select('hora')->where('id', $id_factura)->first();
        $id_usuario = model('pagosModel')->select('id_usuario_facturacion')->where('id', $id_factura)->first();

        $dateTime = new DateTime($hora_factura['horas']);
        $hora_am_pm = $dateTime->format('h:i A'); // 'h' para 12 horas y 'A' para AM/PM

        $printer = new Printer($connector);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text("SOPORTE TRANSFERENCIA\n");
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        
        $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");

        $printer->text("\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setTextSize(1, 1);
        $printer->text("FACTURA DE VENTA:   " .  $numero_factura['documento'] . "\n");
        $printer->text("FECHA:             " . " " . $fecha_factura_venta['fecha'] . "   " . $hora_factura['hora'] . "\n\n");
        $temp_total = model('pagosModel')->select('total_documento')->where('id', $id_factura)->first();
        $temp_efectivo = model('pagosModel')->select('recibido_efectivo')->where('id', $id_factura)->first();
        $temp_transferencia = model('pagosModel')->select('transferencia')->where('id', $id_factura)->first();
        $cambio = model('pagosModel')->select('cambio')->where('id', $id_factura)->first();


        $printer->setTextSize(1, 2);
        $printer->text(
            str_pad("Pago transferencia :", 20) .
                str_pad("$" . number_format($temp_transferencia['transferencia'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n"
        );


        $printer->text("\n\n");
        $printer->setTextSize(1, 1);

        $printer->text("Nota:____________________________________" . "\n\n");
        


        $printer->feed(1);
        $printer->cut();
        $printer->pulse();
        $printer->close();
    }
 */


    function imprimir_comanda() {}

    function imp_reporte_ventas($id_apertura)
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



        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("**REPORTE DE VENTAS** \n\n");

        $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
        $hora_apertura = model('aperturaModel')->select('hora')->where('id', $id_apertura)->first();
        $fecha_cierre = model('cierreModel')->select('fecha')->where('idapertura', $id_apertura)->first();
        $hora_cierre = model('cierreModel')->select('hora')->where('idapertura', $id_apertura)->first();
        //$printer->text("Fecha de apertura:     " . $fecha_apertura['fecha'] . "   " . $hora_apertura['hora'] . "\n");

        if (!empty($fecha_cierre['fecha'])) {
            $printer->text("Fecha de cierre:       " .     $fecha_cierre['fecha'] . "   " . $hora_cierre['hora'] . "\n");
        }

        $printer->text("Fecha de impresion:    " . date('Y-m-d') . "\n");



        $categorias = model('kardexModel')->temp_categoria($id_apertura);


        $printer->setJustification(Printer::JUSTIFY_LEFT);



        foreach ($categorias as $detalle) {
            $nombre_categoria = model('categoriasModel')->select('nombrecategoria')->where('codigocategoria', $detalle['id_categoria'])->first();
            $categoria = $nombre_categoria['nombrecategoria'];
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("------------------------------------\n");
            $printer->text("CATEGORIA: " . $categoria . "\n");
            $printer->text("------------------------------------\n\n");
            $productos = model('kardexModel')->temp_categoria_productos($detalle['id_categoria'], $id_apertura);

            foreach ($productos as $valor) {
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                // Alinea la cantidad a la derecha con una longitud fija de 10 caracteres
                $cantidad_alineada = str_pad($valor['cantidad'], 7, ' ', STR_PAD_LEFT);
                $printer->text($cantidad_alineada . " ____ " . $valor['nombreproducto'] .   "\n");
                $valor_total = model('kardexModel')->valor_producto($valor['codigo'], $id_apertura);

                $printer->text("      Valor total     $"  . number_format($valor_total[0]['total_producto'], 0, ",", ".") .  "\n\n");
            }
            $valor_total_categoria = model('kardexModel')->valor_total_categoria($detalle['id_categoria'], $id_apertura);
            $printer->text("\n");
            $printer->text("      Gran total     $"  . number_format($valor_total_categoria[0]['total_categoria'], 0, ",", ".") .  "\n\n");
        }

        $total = model('kardexModel')->selectSum('total')->where('id_apertura', $id_apertura)->findAll();
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->setTextSize(1, 2);
        $printer->text(" TOTAL VENTAS     $"  . number_format($total[0]['total'], 0, ",", ".") .  "\n\n");

        $printer->text("\n");

        $printer->feed(1);
        $printer->cut();

        $printer->close();

        $returnData = array(
            "resultado" => 1
        );
        echo  json_encode($returnData);
    }
    function imp_reporte_ventasSinCantidades($id_apertura)
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



        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("**REPORTE DE VENTAS** \n\n");

        $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
        $hora_apertura = model('aperturaModel')->select('hora')->where('id', $id_apertura)->first();
        $fecha_cierre = model('cierreModel')->select('fecha')->where('idapertura', $id_apertura)->first();
        $hora_cierre = model('cierreModel')->select('hora')->where('idapertura', $id_apertura)->first();
        //$printer->text("Fecha de apertura:     " . $fecha_apertura['fecha'] . "   " . $hora_apertura['hora'] . "\n");

        if (!empty($fecha_cierre['fecha'])) {
            $printer->text("Fecha de cierre:       " .     $fecha_cierre['fecha'] . "   " . $hora_cierre['hora'] . "\n");
        }

        $printer->text("Fecha de impresion:    " . date('Y-m-d') . "\n");



        $categorias = model('kardexModel')->temp_categoria($id_apertura);


        $printer->setJustification(Printer::JUSTIFY_LEFT);



        foreach ($categorias as $detalle) {
            $nombre_categoria = model('categoriasModel')->select('nombrecategoria')->where('codigocategoria', $detalle['id_categoria'])->first();
            $categoria = $nombre_categoria['nombrecategoria'];
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("------------------------------------\n");
            $printer->text("CATEGORIA: " . $categoria . "\n");
            $printer->text("------------------------------------\n\n");
            $productos = model('kardexModel')->temp_categoria_productos($detalle['id_categoria'], $id_apertura);

            foreach ($productos as $valor) {
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                // Alinea la cantidad a la derecha con una longitud fija de 10 caracteres
                $cantidad_alineada = str_pad($valor['cantidad'], 7, ' ', STR_PAD_LEFT);
                $printer->text($cantidad_alineada . " ____ " . $valor['nombreproducto'] .   "\n");
                $valor_total = model('kardexModel')->valor_producto($valor['codigo'], $id_apertura);
            }
            $valor_total_categoria = model('kardexModel')->valor_total_categoria($detalle['id_categoria'], $id_apertura);
            $printer->text("\n");
        }

        $total = model('kardexModel')->selectSum('total')->where('id_apertura', $id_apertura)->findAll();
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->setTextSize(1, 2);


        $printer->text("\n");

        $printer->feed(1);
        $printer->cut();

        $printer->close();

        $returnData = array(
            "resultado" => 1
        );
        echo  json_encode($returnData);
    }
}
