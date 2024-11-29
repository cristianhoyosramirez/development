<?php

namespace App\Controllers\consultas_y_reportes;

require APPPATH . "Controllers/mike42/autoload.php";

use App\Controllers\BaseController;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use App\Libraries\impresion;

class duplicadoFacturaController extends BaseController
{
    public function index()
    {
        return view('home/home');
    }

    public function duplicado_factura()
    {
        return view('duplicado_de_factura/duplicado_factura');
    }

    public function facturas_por_rango_de_fechas()
    {



        if (!$this->validate([
            'fecha_inicial' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'No hay fecha definida',
                ]
            ],
            'fecha_final' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'No hay fecha definida',
                ]
            ],

        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fecha_inicial = $_POST['fecha_inicial'];
        $fecha_final = $_POST['fecha_final'];
        $id_usuario = $_POST['id_usuario'];
        $facturas_rango_de_fechas = model('facturaVentaModel')->facturas_por_rango_de_fechas($fecha_inicial, $fecha_final);



        foreach ($facturas_rango_de_fechas as $detalle) {
            $valor_factura = model('productoFacturaVentaModel')->selectSum('total')->where('id_factura', $detalle['id'])->find();
            $data = [
                'nit_cliente' => $detalle['nitcliente'],
                'valor_factura' => $valor_factura[0]['total'],
                'numero_factura' => $detalle['numerofactura_venta'],
                'fecha_factura' => $detalle['fecha_factura_venta'],
                'id_factura' => $detalle['id'],
                'id_usuario' => $id_usuario,
                'horafactura_venta' => $detalle['horafactura_venta'],
                'fk_mesa' => $detalle['fk_mesa'],
                'numero_pedido' => $detalle['numero_pedido']
            ];
            $insert = model('duplicadoFacturaModel')->insert($data);
        }
        $facturas_por_rango_de_fechas = model('duplicadoFacturaModel')->orderBy('id', 'asc')->find();


        $borrar = model('duplicadoFacturaModel')->where('id_usuario', $id_usuario);
        $borrar->delete();

        return view('duplicado_de_factura/facturas_por_rango_de_fecha', [
            "facturas" => $facturas_por_rango_de_fechas
        ]);
    }

    public function detalle_factura()
    {
        $id_factura = $_POST['id_factura'];
        $items = model('productoFacturaVentaModel')->getProductosFacturaVentaModel($id_factura);

        $datos_factura = model('facturaVentaModel')->encabezado_facturas_venta($id_factura);
        //$total_factura = model('facturaVentaModel')->select('valor_factura')->where('id', $id_factura)->first();
        $total_factura = model('kardexModel')->selectSum('total')->where('id_factura', $id_factura)->first();
        $productos = view('duplicado_de_factura/productos_factura_duplicado', [
            'productos' => $items,
            'fecha_factura' => $datos_factura[0]['fecha_factura_venta'],
            'numero_factura' => $datos_factura[0]['numerofactura_venta'],
            'nit_cliente' => $datos_factura[0]['nitcliente'],
            'hora_factura' => $datos_factura[0]['horafactura_venta'],
            'total_factura' => $total_factura['total']
        ]);

        $returnData = array(
            "resultado" => 1, //Hay numero de pedido
            "productos" => $productos


        );
        echo  json_encode($returnData);
    }

  
    public function imprimir_duplicado_factura()
    {
        //$id_factura = $_POST['id_de_factura'];
        $id_factura = 48;
        $imp = new impresion();
        $impresion = $imp->imprimir_factura($id_factura);
    }
}
