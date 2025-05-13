<?php

namespace App\Controllers\consultas_y_reportes;

require APPPATH . "Controllers/mike42/autoload.php";

use App\Controllers\BaseController;

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

use \DateTime;
use \DateTimeZone;


class AbonosController extends BaseController
{
    public function saldo_factura()
    {

        $id_factura = $this->request->getPost('id_factura');
        //$tipo_factura = model('pagosModel')->select('id_estado')->where('id', $id_factura)->first();
        $saldo = model('pagosModel')->select('saldo')->where('id_factura', $id_factura)->first();
        $valor = model('pagosModel')->select('valor')->where('id_factura', $id_factura)->first();
        $documento = model('pagosModel')->select('documento')->where('id_factura', $id_factura)->first();

        $returnData = array(
            "saldo" => number_format($saldo['saldo'], 0, ",", "."),
            "valor_factura" => number_format($valor['valor'], 0, ",", "."),
            "numero_factura" => $documento['documento'],
            "id_factura" => $id_factura,
            "resultado" => 1,
        );
        echo  json_encode($returnData);
    }

    function actualizar_saldo()
    {

        $efectivo = $this->request->getPost('efectivo');
        $transaccion = $this->request->getPost('transaccion');
        $id_factura = $this->request->getPost('id_factura');
        $abono = $this->request->getPost('abono');
        // $saldo = $this->request->getPost('saldo');
        $saldo = model('pagosModel')->select('saldo')->where('id_factura', $id_factura)->first();

        $id_usuario = $this->request->getPost('id_usuario');


        $resultado = $efectivo + $transaccion;

        $saldo_actualizado = $saldo['saldo'] - $abono;

        $data = [
            'saldo' => $saldo_actualizado,
        ];
        $model = model('pagosModel');
        $actualizar = $model->set($data);
        $actualizar = $model->where('id_factura', $id_factura);
        $actualizar = $model->update();


        // $numero_factura = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura)->first();
        $numero_factura = model('pagosModel')->select('documento')->where('id_factura', $id_factura)->first();

        if ($actualizar) {

            $fecha = DateTime::createFromFormat('U.u', microtime(TRUE));
            $fecha->setTimeZone(new DateTimeZone('America/Bogota'));
            $fecha_y_hora = $fecha->format('Y-m-d H:i:s.u');

            $factura_forma_pago_efectivo = [

                'numerofactura_venta' => $numero_factura['documento'],
                'idusuario' => $id_usuario,
                'idcaja' => 1,
                'idforma_pago' => 1,
                'fechafactura_forma_pago' => date('Y-m-d'),
                'hora' => date("H:i:s"),
                'valorfactura_forma_pago' => $efectivo,
                'idturno' => 1,
                'valor_pago' => $efectivo,
                'id_factura' => $id_factura,
                'fecha_y_hora_forma_pago' => $fecha_y_hora
            ];

            $factura_forma_pago_transaccion = [
                'numerofactura_venta' => $numero_factura['documento'],
                'idusuario' => $id_usuario,
                'idcaja' => 1,
                'idforma_pago' => 4,
                'fechafactura_forma_pago' => date('Y-m-d'),
                'hora' => date("H:i:s"),
                'valorfactura_forma_pago' => $transaccion,
                'idturno' => 1,
                'valor_pago' => $transaccion,
                'id_factura' => $id_factura,
                'fecha_y_hora_forma_pago' => $fecha_y_hora
            ];



            if ($efectivo > 0) {

                $insert_efectivo = model('facturaFormaPagoModel')->insert($factura_forma_pago_efectivo);

                $consecutivo_ingreso = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 32)->first();

                $nit_cliente = model('facturaVentaModel')->select('nitcliente')->where('id', $id_factura)->first();

                $ingreso = [
                    'numero' => $consecutivo_ingreso['numeroconsecutivo'],
                    'concepto' => 'ABONO A FACTURA NUMERO ' . $numero_factura['documento'],
                    'tipo' => 1,
                    'id_relacion' => 0,
                    'fecha' => date('Y-m-d'),
                    'valor' => $abono,
                    'estado' => 'TRUE',
                    'saldo' => $saldo_actualizado,
                    'nitcliente' => $nit_cliente['nitcliente'],
                    'idcaja' => 1,
                    'idusuario' => $id_usuario

                ];

                $insert_ingreso = model('ingresoModel')->insert($ingreso);
                $id_ingreso = model('ingresoModel')->where('idusuario', $id_usuario)->insertID;
                $ingreso_efectivo = [
                    'idingreso' => $id_ingreso,
                    'idformapago' => 1,
                    'valor' => $efectivo,
                ];


                $insertar_forma_pago = model('ingresoFormaPagoModel')->insert($ingreso_efectivo);

                $data = [
                    'numeroconsecutivo' => $consecutivo_ingreso['numeroconsecutivo'] + 1,
                ];
                $model = model('consecutivosModel');
                $actualizar = $model->set($data);
                $actualizar = $model->where('idconsecutivos', 32);
                $actualizar = $model->update();


                $returnData = array(

                    "resultado" => 1,
                    "id_ingreso" => $id_ingreso
                );
                echo  json_encode($returnData);
            }

            if ($transaccion > 0) {

                $insert_transaccion = model('facturaFormaPagoModel')->insert($factura_forma_pago_transaccion);

                $returnData = array(

                    "resultado" => 1
                );
                echo  json_encode($returnData);
            }


            if ($transaccion > 0 and $efectivo > 0) {
                $insert_efectivo = model('facturaFormaPagoModel')->insert($factura_forma_pago_efectivo);
                $insert_transaccion = model('facturaFormaPagoModel')->insert($factura_forma_pago_transaccion);
                $returnData = array(

                    "resultado" => 1
                );
                echo  json_encode($returnData);
            }
        }
    }


    function imprimir_ingreso()
    {
        $id_ingreso = $_REQUEST['id_ingreso'];
        $datos_empresa = model('empresaModel')->datosEmpresa();
        $connector = new WindowsPrintConnector('FACTURACION');
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
        $printer->text("FECHA:" . date('Y-m-d') . "\n");
        $id_usuario = model('ingresoModel')->select('idusuario')->where('id', $id_ingreso)->first();
        $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario)->first();
        $printer->text("CAJERO(A):" . $nombre_usuario['nombresusuario_sistema'] . "\n");
        $numero_ingreso = model('ingresoModel')->select('numero')->where('id', $id_ingreso)->first();
        $printer->text("COMPROBANTE DE INGRESO N°" . $numero_ingreso['numero'] . "\n");
        $printer->text("--------------------------------------------\n");
        $printer->text("\n");
        $nit_tercero = model('ingresoModel')->select('nitcliente')->where('id', $id_ingreso)->first();
        $nombre_tercero = model('clientesModel')->select('nombrescliente')->where('nitcliente', $nit_tercero['nitcliente'])->first();
        $printer->text("RECIBIDO DE :" . $nombre_tercero['nombrescliente'] . "\n");
        $printer->text("NIT O CC :" . number_format($nit_tercero['nitcliente'], 0, ",", ".") . "\n");

        $printer->text("\n");
        $printer->text("\n");
        $concepto_ingreso = model('ingresoModel')->select('concepto')->where('id', $id_ingreso)->first();
        $printer->text($concepto_ingreso['concepto'] . "\n");


        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $total = model('ingresoModel')->select('valor')->where('id', $id_ingreso)->first();
        $printer->text("TOTAL ABONO :     " . "$" . number_format($total['valor'], 0, ",", ".") . "\n");
        $efectivo = model('ingresoFormaPagoModel')->select('valor')->where('idingreso', $id_ingreso)->first();
        $printer->text("PAGO CON :  " . "$" . number_format($efectivo['valor'], 0, ",", ".") . "\n");
        $saldo = model('ingresoModel')->select('saldo')->where('id', $id_ingreso)->first();

        $printer->text("SALDO CARTERA:     " . "$" . number_format($saldo['saldo'], 0, ",", ".") . "\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("FIRMA      ----------------------------------- \n\n");
        $printer->text("C.C O NIT: ----------------------------------- \n\n");
        $printer->text("FECHA:     ----------------------------------- \n\n");

        $printer->feed(1);
        $printer->cut();

        $printer->close();
    }


    function excel_mov()
    {

        $movimientos = model('TempMovModel')->findAll();

        //dd($movimientos);


        $file_name = 'Movimiento producto.xlsx';

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();


        // Establecer el estilo de las celdas del encabezado
        $headerStyle = [
            'font' => [
                'bold' => true,           // Negrita
                'size' => 12,             // Tamaño de fuente
                'color' => ['argb' => 'FFFFFF'], // Color de fuente blanco
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Alineación centrada
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,   // Alineación centrada vertical
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // Relleno sólido
                'startColor' => ['argb' => '4F81BD'], // Color de fondo (puedes cambiarlo)
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Bordes finos
                    'color' => ['argb' => '000000'], // Color de borde negro
                ],
            ],
        ];

        // Aplicar estilo a las celdas A1:J1
        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);

        $sheet->setCellValue('A1', 'Fecha');

        $sheet->setCellValue('B1', 'Hora');

        $sheet->setCellValue('C1', 'Movimiento');

        $sheet->setCellValue('D1', 'Producto');

        $sheet->setCellValue('E1', 'Cantidad inicial ');
        $sheet->setCellValue('F1', 'Cantidad movimiento ');
        $sheet->setCellValue('G1', 'Cantidad final ');
        $sheet->setCellValue('H1', 'Documento ');
        $sheet->setCellValue('I1', 'Usuario ');
        $sheet->setCellValue('J1', 'Notal ');

        $count = 2;


        foreach ($movimientos as $row) {
            $sheet->setCellValue('A' . $count, $row['fecha']);

            $sheet->setCellValue('B' . $count, $row['hora']);

            $sheet->setCellValue('C' . $count, $row['movimiento']);

            $sheet->setCellValue('D' . $count, $row['producto']);
            $sheet->setCellValue('E' . $count, $row['cantidad_inicial']);
            $sheet->setCellValue('F' . $count, $row['cantidad_movi']);
            $sheet->setCellValue('G' . $count, $row['cantidad_final']);
            $sheet->setCellValue('H' . $count, $row['documento']);
            $sheet->setCellValue('I' . $count, $row['usuario']);
            $sheet->setCellValue('J' . $count, $row['nota']);

            $count++;
        }

        $writer = new Xlsx($spreadsheet);

        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");

        header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');

        header('Expires: 0');

        header('Cache-Control: must-revalidate');

        header('Pragma: public');

        header('Content-Length:' . filesize($file_name));

        flush();

        readfile($file_name);

        exit;
    }

    function impuestos()
    {
        return view('impuestos/reporte_impuestos');
    }

    function reporte_impuestos()
    {

        // $movimientos = model('ReporteImpuestosModel')->findAll();
        $movimientos = model('ReporteImpuestosModel')->getValores();

        $fecha_inicial = model('ReporteImpuestosModel')->selectMin('fecha')->first();
        $fecha_final = model('ReporteImpuestosModel')->selectMax('fecha')->first();

        $ids = model('facturaElectronicaModel')->id_inicial_final($fecha_inicial['fecha'], $fecha_final['fecha']);


        $primer_factura = model('facturaElectronicaModel')->select('numero')->where('id', $ids[0]['id_minimo'])->first();
        $ultima_factura = model('facturaElectronicaModel')->select('numero')->where('id', $ids[0]['id_maximo'])->first();

        $total_IVA = model('kardexModel')->get_iva_reporte($fecha_inicial['fecha'], $fecha_final['fecha']);
        $total_INC = model('kardexModel')->get_ico_reporte($fecha_inicial['fecha'], $fecha_final['fecha']);


        $datos_empresa = model('empresaModel')->datosEmpresa();


        $file_name = 'Reporte impuestos .xlsx';

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();


        // Establecer el estilo de las celdas del encabezado
        $headerStyle = [
            'font' => [
                'bold' => true,           // Negrita
                'size' => 12,             // Tamaño de fuente
                'color' => ['argb' => 'FFFFFF'], // Color de fuente blanco
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Alineación centrada
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,   // Alineación centrada vertical
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // Relleno sólido
                'startColor' => ['argb' => '000000'], // Cambiado a negro
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Bordes finos
                    'color' => ['argb' => '000000'], // Color de borde negro
                ],
            ],
        ];

        $sheet->getStyle('A5')->applyFromArray([
            'font' => [
                'bold' => true, // Texto en negrita
                'size' => 14,  // Tamaño de fuente
                'color' => ['rgb' => '6297D5'], // Color azul pastel
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER, // Alineación horizontal centrada
                'vertical' => Alignment::VERTICAL_CENTER,     // Alineación vertical centrada
            ],
        ]);







        $sheet->setCellValue('A1', $datos_empresa[0]['nombrejuridicoempresa']);

        // Combina las celdas de A1 a J1
        $sheet->mergeCells('A1:O1');

        // Centra el texto en el rango combinado
        $sheet->getStyle('A1:O1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:O1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->setCellValue('A2', $datos_empresa[0]['nombrecomercialempresa']);

        // Combina las celdas de A1 a J1
        $sheet->mergeCells('A2:O2');

        // Centra el texto en el rango combinado
        $sheet->getStyle('A2:O2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:O2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $sheet->setCellValue('A3', 'NIT: ' . $datos_empresa[0]['nitempresa']);
        $sheet->mergeCells('A3:O3');

        // Centra el texto en el rango combinado
        $sheet->getStyle('A3:O3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:O3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $sheet->setCellValue(
            'A4',
            'Dirección: ' . $datos_empresa[0]['direccionempresa'] . " " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento']
        );

        $sheet->mergeCells('A4:O4');

        // Centra el texto en el rango combinado
        $sheet->getStyle('A4:O4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:O4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->setCellValue('A5', 'REPORTE DE IMPUESTOS  ');
        $sheet->mergeCells('A5:O5');


        $sheet->setCellValue('A6', "Fecha inicial");
        $sheet->setCellValue('B6', $fecha_inicial['fecha']);

        $sheet->setCellValue('D6', "Fecha final");
        $sheet->setCellValue('E6', $fecha_final['fecha']);


        $sheet->setCellValue('A7', "Factura inicial");
        $sheet->setCellValue('B7', $primer_factura['numero']);

        $sheet->setCellValue('D7', "Factura final");
        $sheet->setCellValue('E7', $ultima_factura['numero']);

        // Aplicar estilo a las celdas A1:J1
        $sheet->getStyle('A10:O10')->applyFromArray($headerStyle);
        $sheet->setCellValue('A10', 'Dia proceso ');
        $sheet->setCellValue('B10', 'Fecha ');
        $sheet->setCellValue('C10', 'Base INC 0');
        $sheet->setCellValue('D10', 'INC 0');
        $sheet->setCellValue('E10', 'Base INC 8  ');
        $sheet->setCellValue('F10', 'INC 8');
        $sheet->setCellValue('G10', 'Base IVA 0 ');
        $sheet->setCellValue('H10', 'IVA 0  ');
        $sheet->setCellValue('I10', 'Base IVA 5 ');
        $sheet->setCellValue('J10', 'IVA 5  ');
        $sheet->setCellValue('k10', 'Base IVA 19 ');
        $sheet->setCellValue('L10', 'IVA 19  ');
        $sheet->setCellValue('M10', 'Total INC  ');
        $sheet->setCellValue('N10', 'Total IVA  ');
        $sheet->setCellValue('O10', 'Total venta  ');

        $count = 11;


        foreach ($movimientos as $row) {
            $sheet->setCellValue('A' . $count, $row['dia_proceso']);
            $sheet->setCellValue('B' . $count, $row['fecha']);
            //$sheet->setCellValue('C' . $count, $row['base_inc_0']);
            $sheet->setCellValueExplicit('C' . $count, $row['base_inc_0'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D' . $count, $row['inc_0'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E' . $count, $row['base_inc_8'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('F' . $count, $row['inc_8'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('G' . $count, $row['base_iva_0'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('H' . $count, $row['iva_0'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('I' . $count, $row['base_iva_5'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('J' . $count, $row['iva_5'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('K' . $count, $row['base_iva_19'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('L' . $count, $row['iva_19'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('M' . $count, $row['total_inc'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('N' . $count, $row['total_iva'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            // $sheet->setCellValue('O' . $count, number_format($row['total_venta'], 0, ",", "."));
            // $sheet->setCellValue('O' . $count, 30.000);
            $sheet->setCellValueExplicit('O' . $count, $row['total_venta'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            $count++;
        }


        $sheet->getStyle('N' . $count . ':O' . ($count + 2))->applyFromArray([
            'font' => [
                'bold' => true, // Negrita para resaltar el texto
                'color' => ['rgb' => '000000'], // Texto negro oscuro
                'size' => 16, // Tamaño de fuente
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Centrado horizontal
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,     // Centrado vertical
            ],
        ]);

        $total_venta = model('facturaElectronicaModel')->total_venta($fecha_inicial['fecha'], $fecha_final['fecha']);
        $sheet->setCellValue('N' . $count, 'Total Ventas'); // Título en la columna N
        $sheet->setCellValueExplicit('O' . $count, number_format($total_venta[0]['total_venta'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        // Agregamos "Total IVA"
        $sheet->setCellValue('N' . ($count + 1), 'Total IVA'); // Título en la columna N (siguiente fila)
        $sheet->setCellValueExplicit('O' . ($count + 1), number_format($total_IVA[0]['iva'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        // Agregamos "Total ICO"
        $sheet->setCellValue('N' . ($count + 2), 'Total INC'); // Título en la columna N (siguiente fila)
        $sheet->setCellValueExplicit('O' . ($count + 2), number_format($total_INC[0]['ico'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        $writer = new Xlsx($spreadsheet);

        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");

        header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');

        header('Expires: 0');

        header('Cache-Control: must-revalidate');

        header('Pragma: public');

        header('Content-Length:' . filesize($file_name));

        flush();

        readfile($file_name);

        exit;
    }

    function cruce_inventario()
    {

        $conteo_manual = model('inventarioModel')->cruce_inventario();
        $inventario = model('inventarioModel')->inventario();
        $productos = model('productoModel')->ProductoInventario();
    
        return view('inventarios/cruceInventarios', [
            'conteo_manual' => $conteo_manual,
            'inventario_sistema' => $inventario,
            'productos' => $productos
        ]);
    }

    function reporte_cruce_inventarios()
    {


        $inventario = model('inventarioModel')->findAll();
        $cruce = model('inventarioModel')->cruce_inventario();

        $datos_empresa = model('empresaModel')->datosEmpresa();
        $file_name = 'Cruce de inventarios  .xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Establecer el estilo de las celdas del encabezado
        $headerStyle = [
            'font' => [
                'bold' => true,           // Negrita
                'size' => 12,             // Tamaño de fuente
                'color' => ['argb' => 'FFFFFF'], // Color de fuente blanco
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Alineación centrada
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,   // Alineación centrada vertical
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // Relleno sólido
                'startColor' => ['argb' => '000000'], // Cambiado a negro
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Bordes finos
                    'color' => ['argb' => '000000'], // Color de borde negro
                ],
            ],
        ];

        $sheet->getStyle('A5')->applyFromArray([
            'font' => [
                'bold' => true, // Texto en negrita
                'size' => 14,  // Tamaño de fuente
                'color' => ['rgb' => '6297D5'], // Color azul pastel
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER, // Alineación horizontal centrada
                'vertical' => Alignment::VERTICAL_CENTER,     // Alineación vertical centrada
            ],
        ]);

        $sheet->setCellValue('A1', $datos_empresa[0]['nombrejuridicoempresa']);


        /*  $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); */

        $sheet->mergeCells('A1:G1');

        // Centrar el texto horizontal y verticalmente
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Eliminar bordes en el rango
        $sheet->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);


        $sheet->setCellValue('A2', $datos_empresa[0]['nombrecomercialempresa']);
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2:G2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:G2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $sheet->setCellValue('A3', 'NIT: ' . $datos_empresa[0]['nitempresa']);
        $sheet->mergeCells('A3:G3');
        $sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:G3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $sheet->setCellValue(
            'A4',
            'Dirección: ' . $datos_empresa[0]['direccionempresa'] . " " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento']
        );
        $sheet->mergeCells('A4:G4');
        $sheet->getStyle('A4:G4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:G4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->setCellValue('A5', 'CRUCE DE INVENTARIOS ');
        $sheet->mergeCells('A5:G5');



        $sheet->getStyle('A7:G7')->applyFromArray($headerStyle);
        $sheet->setCellValue('A7', 'Código ');
        $sheet->setCellValue('B7', 'Producto');
        $sheet->setCellValue('C7', 'Cantidad conteo');
        $sheet->setCellValue('D7', 'Cantidad sistema ');
        $sheet->setCellValue('E7', 'Diferencia inventario  ');
        $sheet->setCellValue('F7', 'Valor costo ');
        $sheet->setCellValue('G7', 'Valor venta ');

        $count = 8;

        foreach ($inventario as $row) {

            $producto = model('inventarioModel')->conteo_manual($row['codigointernoproducto']);

            /*  $sheet->setCellValue('A' . $count, $row['codigointernoproducto']);
            $nombreProducto=model('productoModel')->select('nombreproducto')->where('codigointernoproducto',$row['codigointernoproducto'])->first();
            $sheet->setCellValue('B' . $count, $nombreProducto['nombreproducto']);
            $sheet->setCellValueExplicit('C' . $count, $row['cantidad_inventario_fisico'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D' . $count, $row['cantidad_inventario'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E' . $count, $row['diferencia'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('F' . $count, $row['valor_costo'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('G' . $count, $row['valor_venta'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $count++; */

            if (!empty($producto)) {

                $sheet->setCellValue('A' . $count, $producto[0]['codigointernoproducto']);
                $sheet->setCellValue('B' . $count, $producto[0]['nombreproducto']);
                $sheet->setCellValueExplicit('C' . $count, $producto[0]['cantidad_inventario_fisico'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('D' . $count, $producto[0]['cantidad_inventario'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('E' . $count, $producto[0]['diferencia'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('F' . $count, number_format($producto[0]['valor_costo'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('G' . $count, number_format($producto[0]['valor_venta'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $count++;
            }
        }

        //$total_venta=model('inventarioModel')->selectSum()

        /* $sheet->setCellValue('N' . ($count + 1), 'Total IVA'); // Título en la columna N (siguiente fila)
        $sheet->setCellValueExplicit('O' . ($count + 1), number_format($total_IVA[0]['iva'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        // Agregamos "Total ICO"
        $sheet->setCellValue('N' . ($count + 2), 'Total INC'); // Título en la columna N (siguiente fila)
        $sheet->setCellValueExplicit('O' . ($count + 2), number_format($total_INC[0]['ico'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); */

        $writer = new Xlsx($spreadsheet);

        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");

        header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');

        header('Expires: 0');

        header('Cache-Control: must-revalidate');

        header('Pragma: public');

        header('Content-Length:' . filesize($file_name));

        flush();

        readfile($file_name);

        exit;
    }
    function reporte_sobrantes()
    {

        //$cruce = model('inventarioModel')->sobrantes();

        $inventario = model('inventarioModel')->findAll();
        $cruce = model('inventarioModel')->cruce_inventario();

        $datos_empresa = model('empresaModel')->datosEmpresa();
        $file_name = 'Productos sobrantes.xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Establecer el estilo de las celdas del encabezado
        $headerStyle = [
            'font' => [
                'bold' => true,           // Negrita
                'size' => 12,             // Tamaño de fuente
                'color' => ['argb' => 'FFFFFF'], // Color de fuente blanco
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Alineación centrada
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,   // Alineación centrada vertical
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // Relleno sólido
                'startColor' => ['argb' => '000000'], // Cambiado a negro
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Bordes finos
                    'color' => ['argb' => '000000'], // Color de borde negro
                ],
            ],
        ];

        $sheet->getStyle('A5')->applyFromArray([
            'font' => [
                'bold' => true, // Texto en negrita
                'size' => 14,  // Tamaño de fuente
                'color' => ['rgb' => '6297D5'], // Color azul pastel
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER, // Alineación horizontal centrada
                'vertical' => Alignment::VERTICAL_CENTER,     // Alineación vertical centrada
            ],
        ]);

        $sheet->setCellValue('A1', $datos_empresa[0]['nombrejuridicoempresa']);


        /*  $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); */

        $sheet->mergeCells('A1:G1');

        // Centrar el texto horizontal y verticalmente
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Eliminar bordes en el rango
        $sheet->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);


        $sheet->setCellValue('A2', $datos_empresa[0]['nombrecomercialempresa']);
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2:G2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:G2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $sheet->setCellValue('A3', 'NIT: ' . $datos_empresa[0]['nitempresa']);
        $sheet->mergeCells('A3:G3');
        $sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:G3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $sheet->setCellValue(
            'A4',
            'Dirección: ' . $datos_empresa[0]['direccionempresa'] . " " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento']
        );
        $sheet->mergeCells('A4:G4');
        $sheet->getStyle('A4:G4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:G4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->setCellValue('A5', 'CRUCE DE INVENTARIOS ');
        $sheet->mergeCells('A5:G5');


        $sheet->setCellValue('A6', 'PRODUCTOS SOBRANTES');
        $sheet->mergeCells('A6:G6');



        $sheet->getStyle('A7:G7')->applyFromArray($headerStyle);
        $sheet->setCellValue('A7', 'Código ');
        $sheet->setCellValue('B7', 'Producto');
        $sheet->setCellValue('C7', 'Cantidad conteo');
        $sheet->setCellValue('D7', 'Cantidad sistema ');
        $sheet->setCellValue('E7', 'Diferencia inventario  ');
        $sheet->setCellValue('F7', 'Valor costo ');
        $sheet->setCellValue('G7', 'Valor venta ');

        $count = 8;

        foreach ($inventario as $row) {

            $producto = model('inventarioModel')->conteo_manual($row['codigointernoproducto']);

            if (!empty($producto)) {
                if ($producto[0]['diferencia'] > 0) {
                    $sheet->setCellValue('A' . $count, $producto[0]['codigointernoproducto']);
                    $sheet->setCellValue('B' . $count, $producto[0]['nombreproducto']);
                    $sheet->setCellValueExplicit('C' . $count, $producto[0]['cantidad_inventario_fisico'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('D' . $count, $producto[0]['cantidad_inventario'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('E' . $count, $producto[0]['diferencia'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('F' . $count, number_format($producto[0]['valor_costo'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('G' . $count, number_format($producto[0]['valor_venta'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $count++;
                }
            }
        }
        //$total_venta=model('inventarioModel')->selectSum()

        /* $sheet->setCellValue('N' . ($count + 1), 'Total IVA'); // Título en la columna N (siguiente fila)
        $sheet->setCellValueExplicit('O' . ($count + 1), number_format($total_IVA[0]['iva'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        // Agregamos "Total ICO"
        $sheet->setCellValue('N' . ($count + 2), 'Total INC'); // Título en la columna N (siguiente fila)
        $sheet->setCellValueExplicit('O' . ($count + 2), number_format($total_INC[0]['ico'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); */

        $writer = new Xlsx($spreadsheet);

        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");

        header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');

        header('Expires: 0');

        header('Cache-Control: must-revalidate');

        header('Pragma: public');

        header('Content-Length:' . filesize($file_name));

        flush();

        readfile($file_name);

        exit;
    }
    function reporte_faltantes()
    {

        //$cruce = model('inventarioModel')->sobrantes();

        $inventario = model('inventarioModel')->findAll();
        $cruce = model('inventarioModel')->cruce_inventario();

        $datos_empresa = model('empresaModel')->datosEmpresa();
        $file_name = 'Productos faltantes.xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Establecer el estilo de las celdas del encabezado
        $headerStyle = [
            'font' => [
                'bold' => true,           // Negrita
                'size' => 12,             // Tamaño de fuente
                'color' => ['argb' => 'FFFFFF'], // Color de fuente blanco
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Alineación centrada
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,   // Alineación centrada vertical
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // Relleno sólido
                'startColor' => ['argb' => '000000'], // Cambiado a negro
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Bordes finos
                    'color' => ['argb' => '000000'], // Color de borde negro
                ],
            ],
        ];

        $sheet->getStyle('A5')->applyFromArray([
            'font' => [
                'bold' => true, // Texto en negrita
                'size' => 14,  // Tamaño de fuente
                'color' => ['rgb' => '6297D5'], // Color azul pastel
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER, // Alineación horizontal centrada
                'vertical' => Alignment::VERTICAL_CENTER,     // Alineación vertical centrada
            ],
        ]);

        $sheet->setCellValue('A1', $datos_empresa[0]['nombrejuridicoempresa']);


        /*  $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); */

        $sheet->mergeCells('A1:G1');

        // Centrar el texto horizontal y verticalmente
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Eliminar bordes en el rango
        $sheet->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);


        $sheet->setCellValue('A2', $datos_empresa[0]['nombrecomercialempresa']);
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2:G2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:G2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $sheet->setCellValue('A3', 'NIT: ' . $datos_empresa[0]['nitempresa']);
        $sheet->mergeCells('A3:G3');
        $sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:G3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $sheet->setCellValue(
            'A4',
            'Dirección: ' . $datos_empresa[0]['direccionempresa'] . " " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento']
        );
        $sheet->mergeCells('A4:G4');
        $sheet->getStyle('A4:G4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:G4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->setCellValue('A5', 'CRUCE DE INVENTARIOS ');
        $sheet->mergeCells('A5:G5');


        $sheet->setCellValue('A6', 'PRODUCTOS SOBRANTES');
        $sheet->mergeCells('A6:G6');



        $sheet->getStyle('A7:G7')->applyFromArray($headerStyle);
        $sheet->setCellValue('A7', 'Código ');
        $sheet->setCellValue('B7', 'Producto');
        $sheet->setCellValue('C7', 'Cantidad conteo');
        $sheet->setCellValue('D7', 'Cantidad sistema ');
        $sheet->setCellValue('E7', 'Diferencia inventario  ');
        $sheet->setCellValue('F7', 'Valor costo ');
        $sheet->setCellValue('G7', 'Valor venta ');

        $count = 8;

        foreach ($inventario as $row) {

            $producto = model('inventarioModel')->conteo_manual($row['codigointernoproducto']);

            if (!empty($producto)) {
                if ($producto[0]['diferencia'] < 0) {
                    $sheet->setCellValue('A' . $count, $producto[0]['codigointernoproducto']);
                    $sheet->setCellValue('B' . $count, $producto[0]['nombreproducto']);
                    $sheet->setCellValueExplicit('C' . $count, $producto[0]['cantidad_inventario_fisico'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('D' . $count, $producto[0]['cantidad_inventario'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('E' . $count, $producto[0]['diferencia'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('F' . $count, number_format($producto[0]['valor_costo'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('G' . $count, number_format($producto[0]['valor_venta'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $count++;
                }
            }
        }
        //$total_venta=model('inventarioModel')->selectSum()

        /* $sheet->setCellValue('N' . ($count + 1), 'Total IVA'); // Título en la columna N (siguiente fila)
        $sheet->setCellValueExplicit('O' . ($count + 1), number_format($total_IVA[0]['iva'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        // Agregamos "Total ICO"
        $sheet->setCellValue('N' . ($count + 2), 'Total INC'); // Título en la columna N (siguiente fila)
        $sheet->setCellValueExplicit('O' . ($count + 2), number_format($total_INC[0]['ico'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); */

        $writer = new Xlsx($spreadsheet);

        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");

        header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');

        header('Expires: 0');

        header('Cache-Control: must-revalidate');

        header('Pragma: public');

        header('Content-Length:' . filesize($file_name));

        flush();

        readfile($file_name);

        exit;
    }

    function productos_inventario()
    {
        $productos = model('productoModel')->getInventario();

        return $this->response->setJSON([
            'success' => true,
            'productos' => view('ventas/productos', [
                'productos' => $productos
            ])
        ]);
    }


    function Inventario()
    {
        $inventario = model('inventarioModel')->findAll();
        $cruce = model('inventarioModel')->cruce_inventario();
        $datos_empresa = model('empresaModel')->datosEmpresa();
        $file_name = 'Inventario.xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Establecer el estilo de las celdas del encabezado
        $headerStyle = [
            'font' => [
                'bold' => true,           // Negrita
                'size' => 12,             // Tamaño de fuente
                'color' => ['argb' => 'FFFFFF'], // Color de fuente blanco
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Alineación centrada
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,   // Alineación centrada vertical
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // Relleno sólido
                'startColor' => ['argb' => '000000'], // Cambiado a negro
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Bordes finos
                    'color' => ['argb' => '000000'], // Color de borde negro
                ],
            ],
        ];

        $sheet->setCellValue('A1', $datos_empresa[0]['nombrejuridicoempresa']);
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);

        // Agregar datos de la empresa y encabezados de inventario
        $sheet->setCellValue('A2', $datos_empresa[0]['nombrecomercialempresa']);
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2:G2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:G2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('A3', 'NIT: ' . $datos_empresa[0]['nitempresa']);
        $sheet->mergeCells('A3:G3');
        $sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:G3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('A4', 'Dirección: ' . $datos_empresa[0]['direccionempresa'] . " " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento']);
        $sheet->mergeCells('A4:G4');
        $sheet->getStyle('A4:G4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:G4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Título del reporte
        $sheet->setCellValue('A5', 'INVENTARIOS GENERAL');
        $sheet->mergeCells('A5:G5');
        $sheet->getStyle('A7:F7')->applyFromArray($headerStyle);

        // Encabezados de columnas
        $sheet->setCellValue('A7', 'Categoria ');
        $sheet->setCellValue('B7', 'Código');
        $sheet->setCellValue('C7', 'Producto');
        $sheet->setCellValue('D7', 'Cantidad  ');
        $sheet->setCellValue('E7', 'Costo unidad ');
        $sheet->setCellValue('F7', 'Costo total ');

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Poner los datos del inventario
        $productos = model('productoModel')->ExcelInventario();
        // dd($productos);
        $count = 8;
        foreach ($productos as $item) {
            $sheet->setCellValue('A' . $count, $item['nombrecategoria']);
            $sheet->setCellValue('B' . $count, $item['codigointernoproducto']);
            $sheet->setCellValue('C' . $count, $item['nombreproducto']);
            $sheet->setCellValue('D' . $count, $item['cantidad_inventario']);
            $sheet->setCellValue('E' . $count, $item['costo_unitario']); // Diferencia
            $sheet->setCellValue('F' . $count, $item['costo_producto']);
            $count++;
        }

        // Ajustar el ancho de las columnas automáticamente

        $costoTotal = model('productoModel')->TotalInv();

        $unidades = model('inventarioModel')
            ->selectSum('cantidad_inventario')
            ->where('cantidad_inventario >', 0)
            ->findAll();

        $sheet->setCellValue('E' . $count, 'Costo Total:');
        $sheet->setCellValue('F' . $count, $costoTotal[0]['costo_total']); // Imprime el costo total
        $count++;

        $sheet->setCellValue('E' . $count, 'Total Unidades:');
        $sheet->setCellValue('F' . $count, $unidades[0]['cantidad_inventario']); // Imprime el total de unidades
        $count++;

        // Ajustar el ancho de las columnas automáticamente
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Guardar y forzar la descarga del archivo
        $writer = new Xlsx($spreadsheet);
        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length:' . filesize($file_name));

        flush();
        readfile($file_name);
        exit;
    }

    function closeModal(){

        $conteo_manual = model('inventarioModel')->cruce_inventario();
        $inventario = model('inventarioModel')->inventario();
        $productos = model('productoModel')->ProductoInventario();

        return $this->response->setJSON([
            'success' => true,
            'productos'=>view('ventas/ingresoInventario',[
                'conteo_manual' => $conteo_manual,
            'inventario_sistema' => $inventario,
            'productos' => $productos
            ])
        ]);
        
    }
}
