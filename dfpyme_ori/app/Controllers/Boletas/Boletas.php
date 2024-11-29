<?php

namespace App\Controllers\Boletas;

use App\Controllers\BaseController;
use App\Libraries\data_table;
use App\Libraries\tipo_consulta;
use App\Libraries\Propina;


require APPPATH . "Controllers/phpqrcode/qrlib.php";

use QRcode;



class Boletas extends BaseController
{
    public $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }


    public function boletas()
    {

        $localidad = model('localidadModel')->where('estado', true)->findAll();
        return view('boletas/boletas', [
            'localidad' => $localidad
        ]);
    }


    function set_boletas()
    {



        if (!$this->validate([
            'clientes_factura_pos' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'localidad' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],

        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nombre_localidad = model('localidadModel')->select('nombre')->where('id', $_POST['localidad'])->first();



        $data = [

            'nitcliente' => $_POST['id_cliente_factura_pos'],
            'fecha_generacion' => date('Y-m-d'),
            'hora_generacion' => date("H:i:s"),
            'estado' => 'Generada',
            'fecha_ingreso' => date('Y-m-d'),
            'hora_ingreso' => date("H:i:s"),
            'observaciones' => '',
            'localidad' => $nombre_localidad['nombre']
        ];


        $insert = model('BoletasModel')->insert($data);


        //$ultimoID = model('BoletasModel')->insertID();

        $ultimoID = (string) model('BoletasModel')->insertID();

        $qrtext = $ultimoID;

        $path = 'images/';
        $qrcode = $path .  1 . ".png";
        $qrimage = time() . ".png";

        $model = model('boletasModel');
        $numero_factura = $model->set('nombre_qr', $qrtext . ".png");
        $numero_factura = $model->where('id', $ultimoID);
        $numero_factura = $model->update();


        if ($_POST['localidad'] != "General") {
            /* $borrar_localidad = model('localidadModel')->where('id', $_POST['localidad']);
            $borrar_localidad->delete(); */

            $num_fact = model('localidadModel');
            $numero_factura = $num_fact->set('estado', 'f');
            $numero_factura = $num_fact->where('id', $_POST['localidad']);
            $numero_factura = $num_fact->update();
        }



        QRcode::png($qrtext, $qrcode, 'H', 10, 10);



        return view('boletas/listado');
    }


    function consultar_boleta()
    {
        return view('boletas/consultar_codigo');
    }


    function cliente()
    {


        $data = [
            'nitcliente' => $this->request->getPost('cedula'),
            'idregimen' => 2,
            'nombrescliente' => $this->request->getPost('nombre'),
            'telefonocliente' => $this->request->getPost('telefono'),
            'celularcliente' => $this->request->getPost('telefono'),
            'emailcliente' => "",
            'idciudad' => 317,
            'direccioncliente' => "",
            'estadocliente' => true,
            'idtipo_cliente' => 1,
            'punto' => 0,
            'id_clasificacion' => 1
        ];


        $insert = model('clientesModel')->insert($data);


        $returnData = array(
            "resultado" => 1, //Falta plata 
            "nit_cliente" => $this->request->getPost('cedula'),
            "nombre_cliente" => $this->request->getPost('nombre')
        );
        echo  json_encode($returnData);
    }


    function actualizar_producto_porcentaje()
    {
        $id_producto = $this->request->getPost('id_producto_pedido');

        $id_usuario = $this->request->getPost('id_usuario');


        $porcentaje_producto = $this->request->getPost('valor');
        $codigo_interno = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_producto)->first();

        $valor_unitario = model('productoModel')->select('valorventaproducto')->where('codigointernoproducto',  $codigo_interno['codigointernoproducto'])->first();
        $cantidad = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_producto)->first();

        // Calcula el valor total usando la fórmula
        $valor_total = $valor_unitario['valorventaproducto'] * (1 - ($porcentaje_producto / 100));
        $total =  $valor_total * $cantidad['cantidad_producto'];

        $model = model('productoPedidoModel');
        $actualizar = $model->set('valor_unitario', $total);
        $actualizar = $model->set('valor_total', $total * $cantidad['cantidad_producto']);
        $actualizar = $model->where('id', $id_producto);
        $actualizar = $model->update();


        $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_producto)->first();

        $total_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();

        $model = model('pedidoModel');
        $actualizar = $model->set('valor_total', $total_pedido[0]['valor_total']);
        $actualizar = $model->where('id',  $numero_pedido['numero_de_pedido']);
        $actualizar = $model->update();

        $returnData = array(
            "resultado" => 1, //Falta plata 
            "total" => number_format($total, 0, ',', '.')
        );
        echo  json_encode($returnData);
    }

    function editar_precio_producto()
    {
        $id_producto = $this->request->getPost('id_producto_pedido');
        $precio  = $this->request->getPost('valor');

        if ($precio >= 0) {


            $cantidad = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_producto)->first();

            $total_producto = $precio * $cantidad['cantidad_producto'];

            $model = model('productoPedidoModel');
            $actualizar = $model->set('valor_unitario', $precio);
            $actualizar = $model->set('valor_total', $total_producto);
            $actualizar = $model->where('id', $id_producto);
            $actualizar = $model->update();

            $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_producto)->first();

            $total_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();

            $model = model('pedidoModel');
            $actualizar = $model->set('valor_total', $total_pedido[0]['valor_total']);
            $actualizar = $model->where('id',  $numero_pedido['numero_de_pedido']);
            $actualizar = $model->update();

            $returnData = array(
                "resultado" => 1, //Falta plata 
                "precio_producto" => number_format($precio, 0, ',', '.'),
                "total_pedido" => number_format($total_pedido[0]['valor_total'], 0, ',', '.'),
                "id" => $id_producto,
                "total_producto" => number_format($total_producto, 0, ',', '.')
            );
            echo  json_encode($returnData);
        }
    }


    function lista_precios()
    {
        $id_producto = $this->request->getPost('id_producto_pedido');
        $codigo_interno = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_producto)->first();

        $valor_venta = model('productoModel')->select('valorventaproducto')->where('codigointernoproducto', $codigo_interno['codigointernoproducto'])->first();
        $descto_mayor = model('productoModel')->select('descto_mayor')->where('codigointernoproducto', $codigo_interno['codigointernoproducto'])->first();

        $temp_precio_2 = ($descto_mayor['descto_mayor'] * $valor_venta['valorventaproducto']) / 100;
        $precio_2 = $valor_venta['valorventaproducto'] - $temp_precio_2;

        $precio_3 = model('productoModel')->select('precio_3')->where('codigointernoproducto', $codigo_interno['codigointernoproducto'])->first();

        $returnData = array(
            "resultado" => 1, //Falta plata 
            "precio_1" => "$ " . number_format($valor_venta['valorventaproducto'], 0, ',', '.'),
            "precio_2" => "$ " . number_format($precio_2, 0, ',', '.'),
            "precio_3" => "$ " . number_format($precio_3['precio_3'], 0, ',', '.'),
        );
        echo  json_encode($returnData);
    }

    function cortesia()
    {
        $id_producto = $this->request->getPost('id_producto_pedido');
        $codigo_interno = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_producto)->first();

        $returnData = array(
            "resultado" => 1, //Falta plata 

        );
        echo  json_encode($returnData);
    }

    function cerrar_modal()
    {
        $id_mesa = $this->request->getPost('id_mesa');

        $numero_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();

        $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['id']);
        $total_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido['id'])->first();


        $returnData = array(
            "resultado" => 1,

            "productos" => view('pedidos/productos_pedido', [
                "productos" => $productos_pedido,
            ]),
            "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),


        );
        echo  json_encode($returnData);
    }


    function descontar_dinero()
    {
        $id_producto = $this->request->getPost('id_producto_pedido');
        $valor_descontar = $this->request->getPost('valor');
        $codigo_interno = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_producto)->first();
        $valor_unitario = model('productoModel')->select('valorventaproducto')->where('codigointernoproducto',  $codigo_interno['codigointernoproducto'])->first();
        $cantidad = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_producto)->first();

        if ($valor_descontar > 0 && $valor_descontar <= $valor_unitario['valorventaproducto']) {
            $nuevo_precio = $valor_unitario['valorventaproducto'] - $valor_descontar;

            $model = model('productoPedidoModel');
            $actualizar = $model->set('valor_unitario', $nuevo_precio);
            $actualizar = $model->set('valor_total', $nuevo_precio * $cantidad['cantidad_producto']);
            $actualizar = $model->where('id',  $id_producto);
            $actualizar = $model->update();


            $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_producto)->first();

            $total = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();


            $model = model('pedidoModel');
            $pedido = $model->set('valor_total', $total[0]['valor_total']);
            $pedido = $model->where('id', $numero_pedido['numero_de_pedido']);
            $pedido = $model->update();


            $returnData = array(
                "resultado" => 1, //Falta plata 
                "precio_producto" => number_format($nuevo_precio, 0, ',', '.')
            );
            echo  json_encode($returnData);
        }
    }


    function nombre_producto()
    {
        $id_producto = $this->request->getPost('id_producto_pedido');
        $codigo_interno = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_producto)->first();
        $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto',  $codigo_interno['codigointernoproducto'])->first();
        $returnData = array(
            "resultado" => 1, //Falta plata 
            "nombre_producto" => "¿Esta seguro de generar cortesia para el producto: " . $nombre_producto['nombreproducto'] . "?"
        );
        echo  json_encode($returnData);
    }


    function generar_cortesia()
    {

        $id_producto = $this->request->getPost('id_producto_pedido');
        $codigo_interno = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_producto)->first();
        $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto',  $codigo_interno['codigointernoproducto'])->first();

        $model = model('productoPedidoModel');
        $actualizar = $model->set('valor_unitario', 0);
        $actualizar = $model->set('valor_total', 0);
        $actualizar = $model->where('id',  $id_producto);
        $actualizar = $model->update();

        $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_producto)->first();

        $total = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();


        $model = model('pedidoModel');
        $pedido = $model->set('valor_total', $total[0]['valor_total']);
        $pedido = $model->where('id', $numero_pedido['numero_de_pedido']);
        $pedido = $model->update();

        $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);
        $total_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido['numero_de_pedido'])->first();


        $returnData = array(
            "resultado" => 1, //Falta plata 
            "nombre_producto" => "¿Esta seguro de generar cortesia para el producto: " . $nombre_producto['nombreproducto'] . "?",
            "productos" => view('pedidos/productos_pedido', [
                "productos" => $productos_pedido,
            ]),
            "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),
        );
        echo  json_encode($returnData);
    }

    function asignar_p1()
    {

        $valor = $this->request->getPost('valor');
        //$valor = 50000;
        $id_producto = $this->request->getPost('id_producto_pedido');
        //$id_producto = 509;
        $codigo_interno = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_producto)->first();

        $valor_venta = model('productoModel')->select('valorventaproducto')->where('codigointernoproducto', $codigo_interno['codigointernoproducto'])->first();
        $descto_mayor = model('productoModel')->select('descto_mayor')->where('codigointernoproducto', $codigo_interno['codigointernoproducto'])->first();
        $cantidad = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_producto)->first();
        $precio_3 = model('productoModel')->select('precio_3')->where('codigointernoproducto', $codigo_interno['codigointernoproducto'])->first();


        $temp_precio_2 = ($descto_mayor['descto_mayor'] * $valor_venta['valorventaproducto']) / 100;
        $precio_2 = $valor_venta['valorventaproducto'] - $temp_precio_2;
        $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_producto)->first();
        $model = model('productoPedidoModel');
        if ($valor == 1) {

            $actualizar = $model->set('valor_unitario', $valor_venta['valorventaproducto']);
            $actualizar = $model->set('valor_total', $valor_venta['valorventaproducto'] * $cantidad['cantidad_producto']);
            $actualizar = $model->where('id',  $id_producto);
            $actualizar = $model->update();
        }
        if ($valor == 2) {

            $actualizar = $model->set('valor_unitario', $precio_2);
            $actualizar = $model->set('valor_total', $precio_2 * $cantidad['cantidad_producto']);
            $actualizar = $model->where('id',  $id_producto);
            $actualizar = $model->update();
        }
        if ($valor == 3) {

            $actualizar = $model->set('valor_unitario', $precio_3['precio_3']);
            $actualizar = $model->set('valor_total', $precio_3['precio_3'] * $cantidad['cantidad_producto']);
            $actualizar = $model->where('id',  $id_producto);
            $actualizar = $model->update();
        }

        $total_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();
        $model = model('pedidoModel');
        $actualizar = $model->set('valor_total', $total_pedido[0]['valor_total']);
        $actualizar = $model->where('id',  $numero_pedido['numero_de_pedido']);
        $actualizar = $model->update();


        $valor_unitario = model('productoPedidoModel')->select('valor_unitario')->where('id', $id_producto)->first();
        $valor_total = model('productoPedidoModel')->select('valor_total')->where('id', $id_producto)->first();
        $total_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido['numero_de_pedido'])->first();


        $tem_id = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_producto)->first();
        $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $tem_id['numero_de_pedido'])->first();


        $configuracion_propina = model('configuracionPedidoModel')->select('calculo_propina')->first();



        /*   $temp_propina = new Propina();
        $propina = $temp_propina->calcularPropina($id_mesa['fk_mesa']);
        $sub_total = $total_pedido['valor_total']; */

        if ($configuracion_propina['calculo_propina'] == 't') {

            $temp_propina = new Propina();
            $propina = $temp_propina->calcularPropina($id_mesa['fk_mesa']);
            $sub_total = $total_pedido['valor_total'];

            $model = model('pedidoModel');
            $configuracion = $model->set('propina', $propina['propina']);
            $actualizar = $model->where('id', $numero_pedido['id']);
            $configuracion = $model->update();

            $propina_final = $propina['propina'];
        }

        if ($configuracion_propina['calculo_propina'] == 'f') {

            $sub_total = $total_pedido['valor_total'];
            $propina_final = 0;
        }


        $returnData = array(
            "resultado" => 1,
            'valor_unitario' => "$ " . number_format($valor_unitario['valor_unitario'], 0, ",", "."),
            'valor_total' => "$ " . number_format($valor_total['valor_total'], 0, ",", "."),
            'total_pedido' => "$ " . number_format($total_pedido['valor_total'], 0, ",", "."),
            'sub_total' => "$ " . number_format($sub_total, 0, ",", "."),
            'id' => $id_producto,
            'propina' =>  "$ " . number_format($propina_final, 0, ",", ".")

        );
        echo  json_encode($returnData);
    }

    function municipios()
    {

        $id_departamento = $this->request->getPost('valorSelect1');
        // $id_departamento = strval($id_departamento);


        $code_depto = model('departamentoModel')->select('code')->where('iddepartamento', $id_departamento)->first();


        /* 
        if ($code_depto['code'] === '08') {
            $codigo_departamento = $code_depto['code'];
        } else if (in_array($code_depto['code'], array('5', '8'))) {
            $codigo_departamento = '0' . $code_depto['code'];
        } else {
            $codigo_departamento = $code_depto['code'];
        } */

        $codigo_departamento = $code_depto['code'];

        $municipios = model('municipiosModel')->where('code_depto', $codigo_departamento)->orderBy('nombre', 'asc')->findAll();

        $ciudad = model('ciudadModel')->where('iddepartamento', $id_departamento)->orderBy('nombreciudad', 'asc')->findAll();

        $returnData = array(
            "resultado" => 1, //Falta plata
            'municipios' => view('municipios/municipios', [
                'municipios' => $municipios
            ]),
            'ciudad' => view('municipios/ciudad', [
                'ciudad' => $ciudad
            ])

        );
        echo  json_encode($returnData);
    }

    function ciudad()
    {
        $code_departamento = $this->request->getPost('valorSelect1');

        // Supongamos que tienes un modelo que obtiene las opciones en función del valor seleccionado.
        $municipios = model('municipiosModel')->where('iddepartamento', $code_departamento)->findAll();

        $data = array();

        foreach ($municipios as $opcion) {
            $data[] = array(
                'value' => $opcion['idciudad'], // Reemplaza 'id' con el campo adecuado de tu base de datos.
                'text' => $opcion['nombreciudad'], // Reemplaza 'nombre' con el campo adecuado de tu base de datos.
            );
        }

        return $this->response->setJSON($data);
    }


    function cancelar_descuentos()
    {
        $id_producto = $this->request->getPost('id_producto_pedido');
        $codigo_interno = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_producto)->first();

        $valor_venta = model('productoModel')->select('valorventaproducto')->where('codigointernoproducto', $codigo_interno['codigointernoproducto'])->first();
        $cantidad = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_producto)->first();
        $model = model('productoPedidoModel');
        $actualizar = $model->set('valor_unitario', $valor_venta['valorventaproducto']);
        $actualizar = $model->set('valor_total', $valor_venta['valorventaproducto'] * $cantidad['cantidad_producto']);
        $actualizar = $model->where('id',  $id_producto);
        $actualizar = $model->update();

        $returnData = array(
            "resultado" => 1, //Falta plata 

        );
        echo  json_encode($returnData);
    }

    function valor()
    {

        $id_mesa = $this->request->getPost('id_mesa');

        $propina = model('pedidoModel')->select('propina')->where('fk_mesa', $id_mesa)->first();
        $total = model('pedidoModel')->select('valor_total')->where('fk_mesa', $id_mesa)->first();


        $returnData = array(
            "resultado" => 1, //Falta plata 
            "propina" => number_format($propina['propina'], 0, ",", "."),
            "sub_total" => number_format($total['valor_total'], 0, ",", "."),
            "total" => number_format($total['valor_total'] + $propina['propina'], 0, ",", ".")
        );
        echo  json_encode($returnData);
    }

    function editar_cantidades()
    {
        //$id_tabla_producto= 2425;
        $id_tabla_producto = $this->request->getPost('id');

        $codigo_interno_producto = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_tabla_producto)->first();
        $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_tabla_producto)->first();
        $valor_unitario = model('productoPedidoModel')->select('valor_unitario')->where('id', $id_tabla_producto)->first();
        $valor_total = model('productoPedidoModel')->select('valor_total')->where('id', $id_tabla_producto)->first();
        $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigointernoproducto'])->first();

        $returnData = array(
            "resultado" => 1, //Falta plata 
            "producto_cantidad" => view('pedidos/editar_cantidades', [
                'cantidad' => $cantidad_producto['cantidad_producto'],
                'nombre_producto' => $nombre_producto['nombreproducto'],
                'valor_unitario' => "$ " . number_format($valor_unitario['valor_unitario'], 0, ",", "."),
                'valor_total' => "$ " . number_format($valor_total['valor_total'], 0, ",", "."),
                'id_tabla_producto' => $id_tabla_producto
            ])
        );
        echo  json_encode($returnData);
    }




    function actualizar_cantidades()
    {
        $id_tabla_producto = $this->request->getPost('id_producto');
        $cantidad_actualizar = $this->request->getPost('cantidad_producto');
        $id_usuario = $this->request->getPost('id_usuario');
        $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_tabla_producto)->first();
        $tipo_usuario = model('usuariosModel')->select('idtipo')->where('idusuario_sistema', $id_usuario)->first();

        $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_tabla_producto)->first();
        $cantidad_impresos = model('productoPedidoModel')->select('numero_productos_impresos_en_comanda')->where('id', $id_tabla_producto)->first();
        $valor_unitario  = model('productoPedidoModel')->select('valor_unitario')->where('id', $id_tabla_producto)->first();

        $model_pedido = model('pedidoModel');

        if ($cantidad_actualizar > $cantidad_producto['cantidad_producto']) {


            $model = model('productoPedidoModel');


            $cantidades = [
                'valor_total' => $cantidad_actualizar * $valor_unitario['valor_unitario'],
                'cantidad_producto' => $cantidad_actualizar
            ];

            $actualizar = model('productoPedidoModel')->actualizacion_cantidad_producto($id_tabla_producto, $cantidades);


            $total_pedido = $model->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->find();


            $actualizacion = $model_pedido->set('valor_total', $total_pedido[0]['valor_total']);

            $actualizacion = $model_pedido->where('id', $numero_pedido['numero_de_pedido']);
            $actualizacion = $model_pedido->update();

            $resultado = 1;
        }

        if ($cantidad_actualizar < $cantidad_producto['cantidad_producto']) {



            if ($tipo_usuario['idtipo'] == 1 || $tipo_usuario['idtipo'] == 0) {
                $cantidades = [
                    'valor_total' => $cantidad_actualizar * $valor_unitario['valor_unitario'],
                    'cantidad_producto' => $cantidad_actualizar
                ];

                $actualizar = model('productoPedidoModel')->actualizacion_cantidad_producto($id_tabla_producto, $cantidades);
                $model = model('productoPedidoModel');
                $total_pedido = $model->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->find();

                $model_pedido = model('pedidoModel');
                $actualizacion = $model_pedido->set('valor_total', $total_pedido[0]['valor_total']);

                $actualizacion = $model_pedido->where('id', $numero_pedido['numero_de_pedido']);
                $actualizacion = $model_pedido->update();

                $resultado = 1;
            }

            if ($tipo_usuario['idtipo'] == 2) {

                if ($cantidad_actualizar > $cantidad_impresos['numero_productos_impresos_en_comanda']) {

                    $cantidades = [
                        'valor_total' => $cantidad_actualizar * $valor_unitario['valor_unitario'],
                        'cantidad_producto' => $cantidad_actualizar
                    ];

                    $actualizar = model('productoPedidoModel')->actualizacion_cantidad_producto($id_tabla_producto, $cantidades);
                    $model = model('productoPedidoModel');
                    $total_pedido = $model->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->find();

                    $model_pedido = model('pedidoModel');
                    $actualizacion = $model_pedido->set('valor_total', $total_pedido[0]['valor_total']);

                    $actualizacion = $model_pedido->where('id', $numero_pedido['numero_de_pedido']);
                    $actualizacion = $model_pedido->update();

                    $resultado = 1;
                }
                if ($cantidad_actualizar < $cantidad_impresos['numero_productos_impresos_en_comanda']) {
                    $resultado = 0;
                }
            }
        }

        if ($cantidad_impresos['numero_productos_impresos_en_comanda'] == $cantidad_producto['cantidad_producto']) {
            $resultado = 0;
        }

        if ($resultado == 1) {
            $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);
            $total_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido['numero_de_pedido'])->first();
            $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido['numero_de_pedido'])->first();
            $estado_mesa = model('mesasModel')->select('estado')->where('id', $id_mesa['fk_mesa'])->first();
            $cantidad_de_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido['numero_de_pedido'])->first();
            $productos_del_pedido = view('pedidos/productos_pedido', [
                "productos" => $productos_pedido,
                "pedido" => $numero_pedido['numero_de_pedido']
            ]);



            $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);

            $returnData = array(
                "resultado" => 1,  // Se actulizo el registro 
                //"productos_pedido" => $productos_del_pedido,
                "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),
                "cantidad_de_pruductos" => $cantidad_de_productos['cantidad_de_productos'],
                "numero_pedido" => $numero_pedido['numero_de_pedido'],
                "estado_mesa" => $estado_mesa['estado'],
                "productos_pedido" => view('pedidos/productos_pedido', [
                    "productos" => $productos_pedido,
                ]),
            );
            echo  json_encode($returnData);
        }
        if ($resultado == 0) {
            $returnData = array(
                "resultado" => 0,  // Se actulizo el registro 

            );
            echo  json_encode($returnData);
        }
    }

    function consultar_ventas()
    {


        $estado = model('estadoModel')->consultar_ventas();

        //$estado = model('estadoModel')->findAll();

        $consulta = "select * from documento_electronico";

        $documentos = model('pagosModel')->get_ventas_credito($consulta);
        return view('ventas/ventas', [
            'estado' => $estado,
            'documentos' => $documentos
        ]);
    }




    public function documento()
    {
        $valor_buscado = $_GET['search']['value'];
        $fecha_inicial = $this->request->getGet('fecha_inicial');
        //$fecha_inicial = '2024-04-03';
        $fecha_final = $this->request->getGet('fecha_final');
        //$fecha_final = '2024-04-03';;

        $sql_count = '';
        $sql_data = '';

        $table_map = [
            0 => 'id',
            1 => 'fecha',
            2 => 'nit_cliente',
            3 => 'nombrescliente',
            4 => 'documento',
            5 => 'total_documento',

        ];

        $sql_count = "SELECT
                     COUNT(id) AS total
                    FROM
                        pagos 
                WHERE
                        fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND id_estado = 8 ";

        $sql_data = "SELECT
                    id,
                    fecha,
                    documento,
                    total_documento,
                    id_factura,
                    id_estado,
                    nit_cliente,
                    id_estado,
                    id_factura,
                    saldo
                FROM
                    pagos 
                where
                    fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND id_estado = 8";



        $condition = "";

        if (!empty($valor_buscado)) {
            $condition .= " AND cliente.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR descripcionestado ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR factura_venta.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR numerofactura_venta ILIKE '%" . $valor_buscado . "%'";
        }

        $sql_count .= $condition;
        $sql_data .= $condition;

        $total_count = $this->db->query($sql_count)->getRow();

        $sql_data .= " ORDER BY " . $table_map[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " " . "LIMIT " . $_GET['length'] . " OFFSET " . $_GET['start'];

        $datos = $this->db->query($sql_data)->getResultArray();

        $data = [];
        $accion = new data_table();
        foreach ($datos as $detalle) {
            $sub_array = array();

            $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $detalle['nit_cliente'])->first();
            $sub_array[] = $detalle['fecha'];
            $sub_array[] = $detalle['nit_cliente'];
            $sub_array[] =  $nombre_cliente['nombrescliente'];

            //$sub_array[] = $detalle['documento'];


            if ($detalle['id_estado'] == 8) {
                $documento = model('facturaElectronicaModel')->select('numero')->where('id', $detalle['id_factura'])->first();
                $sub_array[] = $documento['numero'];
                //var_dump($documento);
            } else if ($detalle['id_estado'] != 8) {
                $sub_array[] = $detalle['documento'];
            }


            $sub_array[] =  number_format($detalle['total_documento'], 0, ",", ".");
            $sub_array[] = $detalle['saldo'];
            $tipo_documento = model('estadoModel')->select('descripcionestado')->where('idestado', $detalle['id_estado'])->first();

            $sub_array[] = $tipo_documento['descripcionestado'];


            $acciones = $accion->row_data_table($detalle['id_estado'], $detalle['id_factura']);

            $sub_array[] = $acciones;


            $data[] = $sub_array;
        }

        /*       $json_data = [
            'draw' => intval($this->request->getGEt(index: 'draw')),
            'recordsTotal' => $total_count->total,
            'recordsFiltered' => $total_count->total,
            'data' => $data,
        ];

        echo  json_encode($json_data); */





        $total_venta = model('pagosModel')->total_venta_fecha_estado($fecha_inicial, $fecha_final, 8);

        //$c_x_c = model('pagosModel')->c_x_c($fecha_inicial, $fecha_final);
        //$abonos = model('pagosModel')->abonos($fecha_inicial, $fecha_final);

        $temp_c_x_c = model('pagosModel')->c_x_c($fecha_inicial, $fecha_final);
        $temp_abonos = model('pagosModel')->abonos($fecha_inicial, $fecha_final);

        if (!empty($temp_abonos)) {
            $abonos = $temp_abonos[0]['pagos_recibidos'];
        } else if (empty($temp_abonos)) {
            $abonos = 0;
        }


        if (!empty($temp_c_x_c)) {
            $c_x_c = $temp_c_x_c[0]['total_por_cobrar'];
        } else if (empty($temp_abonos)) {
            $c_x_c = 0;
        }


        $json_data = [
            'draw' => intval($this->request->getGEt(index: 'draw')),
            'recordsTotal' => $total_count->total,
            'recordsFiltered' => $total_count->total,
            'data' => $data,
            'total' => "$ " . number_format($total_venta[0]['total'], 0, ",", "."),
            'cuentas_por_cobrar' => "$ " . number_format($c_x_c, 0, ",", "."),
            'abonos' => "$ " . number_format($abonos, 0, ",", "."),
            'abonos_sin_punto' => $abonos,
            'saldo_pendiente' => "$ " . number_format($c_x_c - $abonos, 0, ",", "."),
            'saldo_pendiente_sin_punto' => ($c_x_c - $abonos),

        ];

        echo  json_encode($json_data);
    }

    function numero_documento()
    {
        $numero_documento = $this->request->getPost('numero_factura');

        $factura = model('pagosModel')->get_documento($numero_documento);


        if (!empty($factura)) {
            $returnData = array(
                "resultado" => 1,  // Se actulizo el registro 


            );
            echo  json_encode($returnData);
        }
        if (empty($factura)) {
            $returnData = array(
                "resultado" => 0,  // Se actulizo el registro 

            );
            echo  json_encode($returnData);
        }
    }

    function get_cliente()
    {

        echo "Hola mundo ";
    }

    function borrar_propina()
    {

        $id_mesa = $this->request->getPost('id_mesa');


        $pedido = model('pedidoModel')->set('propina', 0)->where('fk_mesa', $id_mesa)->update();
        $valor_pedido = model('pedidoModel')->select('valor_total')->where('fk_mesa', $id_mesa)->first();

        if ($pedido) {
            $returnData = array(
                "resultado" => 1,  // Se actulizo el registro 
                "total" => number_format($valor_pedido['valor_total'], 0, ",", "."),
                "total_sin_formato" => $valor_pedido['valor_total']

            );
            echo  json_encode($returnData);
        }
    }


    public function tipo_documento()
    {
        $valor_buscado = $_GET['search']['value'];
        $id_apertura = model('aperturaModel')->selectMax('id')->findAll();
        $apertura = $id_apertura[0]['id'];



        $sql_count = '';
        $sql_data = '';

        $table_map = [
            0 => 'id',
            1 => 'fecha',
            2 => 'nit_cliente',
            3 => 'nombrescliente',
            4 => 'documento',
            5 => 'total_documento',

        ];

        $sql_count = "SELECT 
                            COUNT(pagos.id) AS total
                    FROM
                    pagos 
                     inner join cliente on cliente.nitcliente=nit_cliente
                    where id_apertura=$apertura";

        $sql_data = "SELECT
                    pagos.id as id,
                    fecha,
                    documento,
                    valor as total_documento,
                    id_factura,
                    id_estado,
                    nit_cliente,
                    id_estado,
                    id_factura,
                    saldo
                FROM
                    pagos 
                    inner join cliente on cliente.nitcliente=nit_cliente
                    where id_apertura=$apertura
                    
                    ";

        $condition = "";

        if (!empty($valor_buscado)) {
            $condition .= " AND cliente.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR documento ILIKE '%" . $valor_buscado . "%'";
        }

        $sql_count .= $condition;
        $sql_data .= $condition;

        $total_count = $this->db->query($sql_count)->getRow();

        $sql_data .= " ORDER BY " . $table_map[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " " . "LIMIT " . $_GET['length'] . " OFFSET " . $_GET['start'];

        $datos = $this->db->query($sql_data)->getResultArray();
        $data = [];

        $accion = new data_table();



        foreach ($datos as $detalle) {
            $sub_array = array();

            $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $detalle['nit_cliente'])->first();
            $sub_array[] = $detalle['fecha'];
            $sub_array[] = $detalle['nit_cliente'];
            $sub_array[] =  $nombre_cliente['nombrescliente'];

            if ($detalle['id_estado'] == 8) {
                $documento = model('facturaElectronicaModel')->select('numero')->where('id', $detalle['id_factura'])->first();
                $numero_documento = $documento['numero'];
            }

            if ($detalle['id_estado'] != 8) {
                $numero_documento = $detalle['documento'];
            }
            $sub_array[] = $numero_documento;
            $sub_array[] = "$ " . number_format($detalle['total_documento'], 0, ",", ".");
            $sub_array[] = "$ " . number_format($detalle['saldo'], 0, ",", ".");
            $tipo_documento = model('estadoModel')->select('descripcionestado')->where('idestado', $detalle['id_estado'])->first();

            $sub_array[] = $tipo_documento['descripcionestado'];

            $acciones = $accion->row_data_table($detalle['id_estado'], $detalle['id_factura']);

            $sub_array[] = $acciones;


            $data[] = $sub_array;
        }



        $temp_abonos = model('pagosModel')->abonos_generales();
        $saldo = model('pagosModel')->selectSum('saldo')->findAll();

        if (empty($temp_abonos)) {
            $abonos = 0;
        }
        if (!empty($temp_abonos)) {
            //$abonos = $temp_abonos[0]['pagos_recibidos'];
            $abonos = 0;
        }
        $total_ventas = model('pagosModel')->total_venta($id_apertura[0]['id']);

        $dian_aceptado = model('facturaElectronicaModel')->dian_ceptado();
        $dian_no_enviado = model('facturaElectronicaModel')->dian_no_enviado($id_apertura[0]['id']);
        $dian_rechazado = model('facturaElectronicaModel')->dian_rechazado();
        $dian_error = model('facturaElectronicaModel')->dian_error();

        $json_data = [
            'draw' => intval($this->request->getGEt(index: 'draw')),
            'recordsTotal' => $total_count->total,
            'recordsFiltered' => $total_count->total,
            'data' => $data,
            'total' => "$ " . number_format($total_ventas[0]['total'], 0, ",", "."),
            'titulo' => "Total ventas ",
            'abonos' => "$ " . number_format($abonos, 0, ",", "."),
            'abonos_sin_punto' => $abonos,
            'saldo_pendiente_por_cobrar' => "$ " . number_format($saldo[0]['saldo'], 0, ",", "."),
            'saldo_pendiente_por_cobrar_sin_punto' => $saldo[0]['saldo'],
            'dian_aceptado' => $dian_aceptado[0]['dian_aceptado'],
            'dian_no_enviado' => $dian_no_enviado[0]['dian_no_enviado'],
            'dian_rechazado' => $dian_rechazado[0]['dian_rechazado'],
            'dian_error' => $dian_error[0]['dian_error'],
        ];

        echo  json_encode($json_data);
    }

    function actualizar_propina()
    {
        $valor_propina = $this->request->getPost('valor_propina');
        $valor_propina = str_replace('.', '', $valor_propina);

        if ($valor_propina == "") {
            $propina = 0;
        } else if ($valor_propina != "") {
            $propina = $valor_propina;
        }

        $id_mesa = $this->request->getPost('id_mesa');


        $model = model('pedidoModel');
        $actualizar = $model->set('propina', $propina);
        $actualizar = $model->where('fk_mesa', $id_mesa);
        $actualizar = $model->update();

        if ($actualizar) {

            $returnData = array(
                "resultado" => 1,  // Se actulizo el registro 

            );
            echo  json_encode($returnData);
        }
    }




    function consultar_de_tipo_documento()
    {

        //$valor_buscado = $_GET['search']['value'];
        //$fecha_inicial = '2024-03-21';
        $fecha_inicial = $this->request->getGet('fecha_inicial');
        //$fecha_final = '2024-03-21';
        $fecha_final = $this->request->getGet('fecha_final');
        $tipo_documento = $this->request->getGet('tipo_documento');
        //$tipo_documento = 5;


        $acciones = new tipo_consulta();
        $acci = $acciones->consulta($fecha_inicial, $fecha_final, $tipo_documento);

        $table_map = [
            0 => 'id',
            1 => 'fecha',
            2 => 'nit_cliente',
            3 => 'nombrescliente',
            4 => 'documento',
            5 => 'total_documento',

        ];

        $sql_count = $acci['sql_count'];
        $sql_data = $acci['sql_data'];




        $condition = "";

        if (!empty($valor_buscado)) {
            $condition .= " AND cliente.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR descripcionestado ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR factura_venta.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR numerofactura_venta ILIKE '%" . $valor_buscado . "%'";
        }

        $sql_count .= $condition;
        $sql_data .= $condition;

        $total_count = $this->db->query($sql_count)->getRow();

        $sql_data .= " ORDER BY " . $table_map[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " " . "LIMIT " . $_GET['length'] . " OFFSET " . $_GET['start'];




        $datos = $this->db->query($sql_data)->getResultArray();
        $data = [];


        $accion = new data_table();
        foreach ($datos as $detalle) {
            $sub_array = array();

            $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $detalle['nit_cliente'])->first();
            $sub_array[] = $detalle['fecha'];
            $sub_array[] = $detalle['nit_cliente'];
            $sub_array[] =  $nombre_cliente['nombrescliente'];
            $sub_array[] = $detalle['documento'];
            $sub_array[] =  number_format($detalle['total_documento'], 0, ",", ".");
            $sub_array[] =  number_format($detalle['saldo'], 0, ",", ".");
            $documento = model('estadoModel')->select('descripcionestado')->where('idestado', $detalle['id_estado'])->first();

            $sub_array[] = $documento['descripcionestado'];
            $acciones = $accion->row_data_table($detalle['id_estado'], $detalle['id_factura']);

            $sub_array[] = $acciones;


            $data[] = $sub_array;
        }


        if ($tipo_documento == 5) {
            $total_venta = model('pagosModel')->total_venta_fecha($fecha_inicial, $fecha_final);
        }

        if ($tipo_documento != 5) {

            $total_venta = model('pagosModel')->total_venta_fecha_estado($fecha_inicial, $fecha_final, $tipo_documento);
        }

        $temp_c_x_c = model('pagosModel')->c_x_c($fecha_inicial, $fecha_final);
        $temp_abonos = model('pagosModel')->abonos($fecha_inicial, $fecha_final);

        if (!empty($temp_abonos)) {
            $abonos = $temp_abonos[0]['pagos_recibidos'];
        } else if (empty($temp_abonos)) {
            $abonos = 0;
        }


        if (!empty($temp_c_x_c)) {
            $c_x_c = $temp_c_x_c[0]['total_por_cobrar'];
        } else if (empty($temp_abonos)) {
            $c_x_c = 0;
        }

        $json_data = [
            'draw' => intval($this->request->getGEt(index: 'draw')),
            'recordsTotal' => $total_count->total,
            'recordsFiltered' => $total_count->total,
            'data' => $data,
            'total' => "$ " . number_format($total_venta[0]['total'], 0, ",", "."),
            'cuentas_por_cobrar' => "$ " . number_format($c_x_c, 0, ",", "."),
            'abonos' => "$ " . number_format($abonos, 0, ",", "."),
            'saldo_pendiente' => "$ " . number_format($c_x_c - $abonos, 0, ",", "."),
        ];

        echo  json_encode($json_data);
    }
    function consultar_cliente()
    {

        //$valor_buscado = $_GET['search']['value'];
        //$fecha_inicial = '2024-01-01';
        $fecha_inicial = $this->request->getGet('fecha_inicial');
        //$fecha_final = '2024-03-04';
        $fecha_final = $this->request->getGet('fecha_final');
        $tipo_documento = $this->request->getGet('tipo_documento');
        $nit_cliente = $this->request->getGet('nit_cliente');
        //$tipo_documento = 5;

        $acciones = new tipo_consulta();
        $acci = $acciones->consulta_cliente($fecha_inicial, $fecha_final, $tipo_documento, $nit_cliente);

        $table_map = [
            0 => 'id',
            1 => 'fecha',
            2 => 'nit_cliente',
            3 => 'nombrescliente',
            4 => 'documento',
            5 => 'total_documento',

        ];

        $sql_count = $acci['sql_count'];
        $sql_data = $acci['sql_data'];




        $condition = "";

        if (!empty($valor_buscado)) {
            $condition .= " AND cliente.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR descripcionestado ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR factura_venta.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR numerofactura_venta ILIKE '%" . $valor_buscado . "%'";
        }

        $sql_count .= $condition;
        $sql_data .= $condition;

        $total_count = $this->db->query($sql_count)->getRow();

        $sql_data .= " ORDER BY " . $table_map[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " " . "LIMIT " . $_GET['length'] . " OFFSET " . $_GET['start'];




        $datos = $this->db->query($sql_data)->getResultArray();
        $data = [];



        $accion = new data_table();
        foreach ($datos as $detalle) {
            $sub_array = array();

            $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $detalle['nit_cliente'])->first();
            $sub_array[] = $detalle['fecha'];
            $sub_array[] = $detalle['nit_cliente'];
            $sub_array[] =  $nombre_cliente['nombrescliente'];

            if ($detalle['id_estado'] == 8) {
                $documento = model('facturaElectronicaModel')->select('numero')->where('id', $detalle['id_factura'])->first();
                $sub_array[] = $documento['numero'];
            } else if ($detalle['id_estado'] != 8) {
                $sub_array[] = $detalle['documento'];
            }
            $sub_array[] =  number_format($detalle['total_documento'], 0, ",", ".");
            $sub_array[] =  number_format($detalle['saldo'], 0, ",", ".");
            $tipo_documento = model('estadoModel')->select('descripcionestado')->where('idestado', $detalle['id_estado'])->first();

            $sub_array[] = $tipo_documento['descripcionestado'];
            $acciones = $accion->row_data_table($detalle['id_estado'], $detalle['id_factura']);

            $sub_array[] = $acciones;


            $data[] = $sub_array;
        }

        $json_data = [
            'draw' => intval($this->request->getGEt(index: 'draw')),
            'recordsTotal' => $total_count->total,
            'recordsFiltered' => $total_count->total,
            'data' => $data,
        ];

        echo  json_encode($json_data);
    }

    function get_mesas_pedido()
    {
        $mesas = model('pedidoModel')->select('fk_mesa')->find();


        $returnData = array(
            "resultado" => 1,
            "mesas" => $mesas  // Se actulizo el registro 

        );
        echo  json_encode($returnData);
    }

    function venta_multiple()
    {

        $venta_multiple = model('configuracionPedidoModel')->select('requiere_mesa')->first();
        return view('configuracion/venta_multiple', [
            'venta_multiple' => $venta_multiple['requiere_mesa']
        ]);
    }

    function actualizar_venta_multiple()
    {
        $valor = $this->request->getPost('valor');

        $actualizar = model('configuracionPedidoModel')->set('requiere_mesa', $valor)->update();


        if ($actualizar) {
            $returnData = array(
                "resultado" => 1, //Falta plata  
            );
            echo  json_encode($returnData);
        }
    }

    function validar_venta_directa()
    {

        $id_mesa = model('mesasModel')->select('id')->where('estado', 1)->first();
        $pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa['id'])->first();
        $valor_pedido = model('pedidoModel')->select('valor_total')->where('fk_mesa', $id_mesa['id'])->first();
        $propina = model('pedidoModel')->select('propina')->where('fk_mesa', $id_mesa['id'])->first();
        $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa['id'])->first();



        if (!empty($pedido)) {
            $productos_pedido = model('productoPedidoModel')->producto_pedido($pedido['id']);

            $returnData = array(
                "resultado" => 1,
                "productos" => view('pedidos/productos_pedido', [
                    "productos" => $productos_pedido
                ]),
                'id_mesa' => $id_mesa['id'],
                'sub_total' => number_format($valor_pedido['valor_total'] - $propina['propina'], 0, ",", "."),
                'total' => "$ " . number_format($valor_pedido['valor_total'] + $propina['propina'], 0, ",", "."),
                'propina' => "$ " . number_format($propina['propina'], 0, ",", "."),
                'nombre_mesa' => $nombre_mesa['nombre'],
                "estado" => 1
            );

            echo  json_encode($returnData);
        }
    }

    function editar_precio()
    {
        $id_producto = $this->request->getPost('id');
        $precio  = $this->request->getPost('precio');
        $cantidad  = $this->request->getPost('cantidad');

        if ($precio >= 0) {


            //$cantidad = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_producto)->first();

            $total_producto = $precio * $cantidad;

            $model = model('productoPedidoModel');
            $actualizar = $model->set('valor_unitario', $precio);
            $actualizar = $model->set('cantidad_producto', $cantidad);
            $actualizar = $model->set('valor_total', $total_producto);
            $actualizar = $model->where('id', $id_producto);
            $actualizar = $model->update();

            $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_producto)->first();

            $total_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();

            $model = model('pedidoModel');
            $actualizar = $model->set('valor_total', $total_pedido[0]['valor_total']);
            $actualizar = $model->where('id',  $numero_pedido['numero_de_pedido']);
            $actualizar = $model->update();



            $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);

            $returnData = array(
                "resultado" => 1, //Falta plata 
                "precio_producto" => number_format($precio, 0, ',', '.'),
                "total_pedido" => number_format($total_pedido[0]['valor_total'], 0, ',', '.'),
                "id" => $id_producto,
                "total_producto" => number_format($total_producto, 0, ',', '.'),
                "productos_pedido" => view('pedidos/productos_pedido', [
                    "productos" => $productos_pedido,
                ]),
            );
            echo  json_encode($returnData);
        }
    }

    function eliminar_f_e()
    {
        $id = $this->request->getPost('id');

        $borrar = model('pagosModel')->borrar_f_e($id);

        if ($borrar) {
            $borrar_documento = model('facturaElectronicaModel')->where('id', $id)->delete();
        }

        $returnData = array(
            "resultado" => 1, //Falta plata 
        );
        echo  json_encode($returnData);
    }

    function validar_pass()
    {
        $pass = $this->request->getPost('password');

        $pin = model('configuracionPedidoModel')->select('eliminar_factura_electronica')->first();

        if ($pass === $pin['eliminar_factura_electronica']) {
            $returnData = array(
                "resultado" => 1, //Falta plata 
            );
            echo  json_encode($returnData);
        }
        if ($pass != $pin['eliminar_factura_electronica']) {
            $returnData = array(
                "resultado" => 0, //Falta plata 
            );
            echo  json_encode($returnData);
        }
    }

    function borrar_propina_parcial()
    {
        $id_mesa = $this->request->getPost('id_mesa');

        $numero_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();

        $total = model('partirFacturaModel')->propina_partida($numero_pedido['id']);

        $returnData = array(
            "resultado" => 1, //Falta plata 
            "total" => number_format($total[0]['valor_total'], 0, ',', '.'),
            "total_pedido" => $total[0]['valor_total'],
        );
        echo  json_encode($returnData);
    }

    public function consultar_entradas()
    {

        $valor_buscado = $_GET['search']['value'];

        /**
         * Tipo de busqueda
         * Al cargar la tabla se hace un busqueda de todas las compras 
         * hay otro criterio que es entre fechas , por fechas y proveedor y solo proveedor 
         */

        $busqueda = $this->request->getGet('buscar_por');

        if ($busqueda == "general") {
            $sql = new tipo_consulta();
            $temp_sql = $sql->getAllCompras();
        }

        //$sql_count = '';
        //$sql_data = '';

        $sql_count = $temp_sql['sql_count'];
        $sql_data = $temp_sql['sql_data'];


        $table_map = [
            0 => 'id',
            1 => 'fecha_ingreso',
            2 => 'nitproveedor',
            3 => 'usuario',
            4 => 'nombre_proveedor',

        ];


        $condition = "";

        if (!empty($valor_buscado)) {
            $condition .= " AND cliente.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR documento ILIKE '%" . $valor_buscado . "%'";
        }

        $sql_count .= $condition;
        $sql_data .= $condition;

        $total_count = $this->db->query($sql_count)->getRow();

        $sql_data .= " ORDER BY " . $table_map[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " " . "LIMIT " . $_GET['length'] . " OFFSET " . $_GET['start'];

        $datos = $this->db->query($sql_data)->getResultArray();




        foreach ($datos as $detalle) {
            $sub_array = array();

            $total = model('ComprasModel')->total_compra($detalle['id']);

            $sub_array[] =  $detalle['fecha_ingreso'];
            $sub_array[] =  $detalle['nombre_proveedor'];
            $sub_array[] =  $detalle['nitproveedor'];
            $sub_array[] =  number_format($total[0]['total_compra'], 0, ',', '.');
            $sub_array[] =  $detalle['usuario'];
            $sub_array[] =  $sub_array[] = '
                <a  class="btn btn-outline-success btn-icon " title="Imprimir compra " onclick="imprimir_compra(' . $detalle['id'] . ')" >
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg>
                </a>

              <a  class="btn bg-outline-muted-lt btn-icon " title="Ver productos" onclick="detalle_compra(' . $detalle['id'] . ')"  ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
               <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
               </a>
               ';

            $data[] = $sub_array;
        }

        $json_data = [
            'draw' => intval($this->request->getGEt(index: 'draw')),
            'recordsTotal' => $total_count->total,
            'recordsFiltered' => $total_count->total,
            'data' => $data,

        ];

        echo  json_encode($json_data);
    }
}
