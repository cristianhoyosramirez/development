<?php

namespace App\Controllers\producto;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\Request;

class productoController extends BaseController
{
    public $db;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    function index()
    {

        $valor_buscado = $_POST['search']['value'];
        //$valor_buscado = "BU";

        $table_map = [
            0 => 'nombrecategoria',
            1 => 'codigointernoproducto',
            2 => 'nombreproducto',
            3 => 'nombrecategoria',
            4 => 'valorventaproducto',
        ];

        $sql_count = "SELECT count(codigointernoproducto) as total 
         from producto 
         INNER JOIN categoria ON producto.codigocategoria=categoria.codigocategoria WHERE estadoproducto='true'";

        $sql_data = "SELECT 
        nombrecategoria,
        nombreproducto,
        codigointernoproducto,
        valorventaproducto
        FROM producto
        INNER JOIN categoria ON producto.codigocategoria=categoria.codigocategoria WHERE estadoproducto='true'
        ";
        $condition = "";



        if (!empty($valor_buscado)) {

            /* $condition .= " AND nombreproducto ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR nombrecategoria ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR codigointernoproducto ILIKE '%" . $valor_buscado . "%'"; */
            $condition .= " AND (nombreproducto ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR nombrecategoria ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR codigointernoproducto ILIKE '%" . $valor_buscado . "%')";
        }

        $sql_count = $sql_count . $condition;
        $sql_data = $sql_data . $condition;

        //$total_count = $this->db->query($sql_count)->getRow();
        $total_count = $this->db->query($sql_count)->getRow();
        //echo $total_count->total;
        //dd($total_count);

        $sql_data .= " ORDER BY " . $table_map[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " " . "LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];

        $datos = $this->db->query($sql_data)->getResultArray();
        //$data = $this->db->query($sql_data)->getResult();

        if (!empty($datos)) {
            foreach ($datos as $detalle) {
                $cant = model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto', $detalle['codigointernoproducto'])->first();
                if (empty($cant)) {
                    $cantidad = 0;
                }
                if (!empty($cant)) {
                    $cantidad = $cant['cantidad_inventario'];
                }
                $sub_array = array();
                $sub_array[] = $detalle['nombrecategoria'];
                $sub_array[] = $detalle['codigointernoproducto'];
                $sub_array[] = $detalle['nombreproducto'];
                //$sub_array[] = $cantidad['cantidad_inventario'];
                $sub_array[] = $cantidad;
                $sub_array[] = "$" . number_format($detalle['valorventaproducto'], 0, ",", ".");

                $sub_array[] = '<a  class="btn btn-success "  onclick="editar_producto(' . $detalle['codigointernoproducto'] . ')"  >Editar</a> <a  class="btn btn-danger "  onclick="eliminar_producto(' . $detalle['codigointernoproducto'] . ')"  >Eliminar</a>  ';
                $data[] = $sub_array;
            }

            $json_data = [
                'draw' => intval($this->request->getPost(index: 'draw')),
                'recordsTotal' => $total_count->total,
                'recordsFiltered' => $total_count->total,
                'data' => $data,

            ];
            echo  json_encode($json_data);
        }
        if (empty($datos)) {
            $json_data = [
                'draw' => intval($this->request->getPost(index: 'draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => 0,

            ];
            echo  json_encode($json_data);
        }
    }

    function lista_de_productos()
    {
        $iva = model('ivaModel')->orderBy('idiva', 'asc')->find();
        $ico = model('icoConsumoModel')->orderBy('id_ico', 'desc')->findAll();
        $categorias = model('categoriasModel')->orderBy('id', 'asc')->findAll();
        $marcas = model('marcasModel')->orderBy('idmarca', 'asc')->findAll();
        $impuesto_saludable = model('impuestoSaludableModel')->findAll();

        $sub_categorias = model('SubCategoriaModel')->find();

        return view('producto/listado', [
            'iva' => $iva,
            'ico' => $ico,
            'categorias' => $categorias,
            'marcas' => $marcas,
            'impuesto_saludable' => $impuesto_saludable,
            'sub_categorias' => $sub_categorias
        ]);
    }



    public function productoPedido()
    {
        $returnData = array();
        $valor = $this->request->getVar('term');
        //$valor = 'a';

        $resultado = model('productoModel')->autoCompletePro($valor);

        if (!empty($resultado)) {
            foreach ($resultado as $row) {
                $cantidad_producto = model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto', $row['codigointernoproducto'])->first();


                if (empty($cantidad_producto)) {
                    $inventario = [
                        'codigointernoproducto' => $row['codigointernoproducto'],
                        'idvalor_unidad_medida' => 3,
                        'idcolor' => 0,
                        'cantidad_inventario' => 0
                    ];

                    $insert = model('inventarioModel')->insert($inventario);

                    $cantidad = 0;
                }

                if (!empty($cantidad_producto)) {
                    $cantidad = $cantidad_producto['cantidad_inventario'];
                }

                $codigo_pantalla = model('configuracionPedidoModel')->select('codigo_pantalla')->first();

                //$data['value'] =  $row['codigointernoproducto'] . " " . "/" . " " . $row['nombreproducto'];
                if ($codigo_pantalla['codigo_pantalla'] == 't') {
                    $data['value'] =  $row['codigointernoproducto'] . " " . "/" . " " . $row['nombreproducto'] . "  " . "$ " . number_format($row['valorventaproducto'], 0, ",", ".");
                }
                if ($codigo_pantalla['codigo_pantalla'] == 'f') {
                    $data['value'] =  $row['nombreproducto'] . "  " . "$ " . number_format($row['valorventaproducto'], 0, ",", ".");
                }
                $data['id_producto'] = $row['codigointernoproducto'];
                $data['nombre_producto'] = $row['nombreproducto'];
                $data['valor_venta'] = $row['valorventaproducto'];
                //$data['cantidad'] = $cantidad_producto['cantidad_inventario'];
                $data['cantidad'] = $cantidad;
                // $data=['cantidad']=$cantidad_producto['cantidad_inventario'];
                array_push($returnData, $data);
            }
            echo json_encode($returnData);
        } else {
            $data['value'] = "No hay resultados";
            array_push($returnData, $data);
            echo json_encode($returnData);
        }
    }
    public function pedido_pos()
    {
        $returnData = array();
        $valor = $this->request->getVar('term');

        $resultado = model('productoModel')->producto_pedido_pos($valor);

        if (!empty($resultado)) {
            foreach ($resultado as $row) {
                $data['value'] =  $row['codigointernoproducto'] . " " . "/" . " " . $row['nombreproducto'];
                $data['id_producto'] = $row['codigointernoproducto'];
                $data['valor_venta_producto'] = number_format($row['valorventaproducto'], 0, ',', '.');

                array_push($returnData, $data);
            }
            echo json_encode($returnData);
        } else {
            $data['value'] = "No hay resultados";
            array_push($returnData, $data);
            echo json_encode($returnData);
        }
    }

    public function agregar_producto_al_pedido()
    {
        $id_producto = $_POST['codigointernoproducto'];
        $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $id_producto)->first();
        $valor_venta = model('productoModel')->select('valorventaproducto')->where('codigointernoproducto', $id_producto)->first();

        if ($nombre_producto and $valor_venta) {

            $returnData = array(
                "codigointernoproducto" => $id_producto,
                "nombre_producto" => $nombre_producto['nombreproducto'],
                "valor_venta" => $valor_venta['valorventaproducto'],
                "valor_venta_con_formato" => "$" . number_format($valor_venta['valorventaproducto'], 0, ',', '.'),
                "resultado" => 1
            );
            echo  json_encode($returnData);
        } else if (empty($nombre_producto and $valor_venta)) {
            $returnData = array(
                "resultado" => 0
            );
        }
    }

    public function insertar_productos_tabla_pedido()
    {


        /**
         * Valores del formulacio autocompletar_producto 
         * peticion ajax agregar_producto_pedido
         */

        $cantidad = $_POST['validar_cantidad'];
        $numero_pedido = $_POST['numero_pedido'];
        $id_mesa = $_POST['id_mesa'];
        $id_usuario = $_POST['id_usuario'];
        $nota_producto = $_POST['nota_producto'];
        $valor_unitario = $_POST['valor_unitario'];
        $valor_total = $valor_unitario * $cantidad;
        $codigointernoproducto = $_POST['codigointernoproducto'];
        $codigo_interno_producto = (string)$codigointernoproducto;



        /*    $cantidad = 1;
        $numero_pedido = '';
        $id_mesa = 54;
        $id_usuario = 6;
        $nota_producto = "pr pr";
        $valor_unitario = 41000;
        $codigointernoproducto = 6730;
        $codigo_interno_producto = (string)$codigointernoproducto; 
        $valor_total = $valor_unitario * $cantidad; */

        $num_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();


        if ($cantidad == 0 || $cantidad < 0 || $cantidad == "") {
            $returnData = array(
                "resultado" => 0
            );
            echo  json_encode($returnData);
        } else {

            if ($numero_pedido == "") {

                $data = [
                    'fk_mesa' => $id_mesa,
                    'fk_usuario' => $id_usuario,
                    'valor_total' => $valor_total,
                    'cantidad_de_productos' => $cantidad,
                ];



                $insert = model('pedidoModel')->insert($data);
                //echo "Desde el controlador"; exit();
                /*   $datos_mesa = [
                    'estado' => 1,
                    'valor_pedido' => $valor_total,
                    'fk_usuario' => $id_usuario
                ];

                $model = model('mesasModel');
                $actualizar = $model->set($datos_mesa);
                $actualizar = $model->where('id', $id_mesa);
                $actualizar = $model->update(); */

                if ($insert) {
                    $ultimo_id_pedido = model('pedidoModel')->insertID;
                    $se_imprime_en_comanda = model('productoModel')->select('se_imprime')->where('codigointernoproducto', $codigo_interno_producto)->first();
                    $codigo_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $codigo_interno_producto)->first();
                    $producto_pedido = [
                        'numero_de_pedido' => $ultimo_id_pedido,
                        'cantidad_producto' => $cantidad,
                        'nota_producto' => $nota_producto,
                        'valor_unitario' => $valor_unitario,
                        'impresion_en_comanda' => false,
                        'cantidad_entregada' => 0,
                        'valor_total' => $valor_total,
                        'se_imprime_en_comanda' => $se_imprime_en_comanda['se_imprime'],
                        'codigo_categoria' => $codigo_categoria['codigocategoria'],
                        'codigointernoproducto' => $codigo_interno_producto,
                        'numero_productos_impresos_en_comanda' => 0
                    ];



                    $insertar = model('productoPedidoModel')->insert($producto_pedido);

                    if ($insertar) {
                        $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa)->first();

                        $productos_pedido = model('productoPedidoModel')->producto_pedido($ultimo_id_pedido);

                        $total_pedido = model('pedidoModel')->select('valor_total')->where('id', $ultimo_id_pedido)->first();
                        $cantidad_de_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $ultimo_id_pedido)->first();

                        $productos_del_pedido = view('productos_pedido/productos_pedido', [
                            "productos" => $productos_pedido,
                            "pedido" => $ultimo_id_pedido
                        ]);
                        $returnData = array(
                            "resultado" => 1,
                            "id_mesa" => $id_mesa,
                            "numero_pedido" => $ultimo_id_pedido,
                            "productos_pedido" => $productos_del_pedido,
                            "nombre_mesa" => $nombre_mesa['nombre'],
                            "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),
                            "cantidad_de_pruductos" => $cantidad_de_productos['cantidad_de_productos']

                        );
                        echo  json_encode($returnData);
                    }
                }
            } else {

                $configuracion_pedido = model('configuracionPedidoModel')->select('agregar_item')->first();

                if ($configuracion_pedido['agregar_item'] == 0) {

                    $existe_producto = model('productoPedidoModel')->cantidad_producto($num_pedido['id'], $codigo_interno_producto);

                    if (empty($existe_producto)) {
                        $se_imprime_en_comanda = model('productoModel')->select('se_imprime')->where('codigointernoproducto', $codigo_interno_producto)->first();
                        $codigo_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $codigo_interno_producto)->first();


                        if ($num_pedido['id'] == $numero_pedido) {

                            $producto_pedido = [
                                'numero_de_pedido' => $numero_pedido,
                                'cantidad_producto' => $cantidad,
                                'nota_producto' => $nota_producto,
                                'valor_unitario' => $valor_unitario,
                                'impresion_en_comanda' => false,
                                'cantidad_entregada' => 0,
                                'valor_total' => $valor_total,
                                'se_imprime_en_comanda' =>  $se_imprime_en_comanda['se_imprime'],
                                'codigo_categoria' =>   $codigo_categoria['codigocategoria'],
                                'codigointernoproducto' => $codigo_interno_producto,
                                'numero_productos_impresos_en_comanda' => 0
                            ];
                            $insertar = model('productoPedidoModel')->insert($producto_pedido);
                            if (!empty($nota_producto)) {
                                $id_producto_pedido = model('productoPedidoModel')->insertID;
                                $nota_pedido = [
                                    'nota' => $nota_producto,
                                    'pedido' => $numero_pedido,
                                    'id_tabla_producto' => $id_producto_pedido

                                ];

                                $insert = model('notaPedidoModel')->insert($nota_pedido);
                            }

                            $cantidad_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido)->first();
                            $cant_productos = $cantidad_productos['cantidad_de_productos'] + $cantidad;

                            $valor_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();
                            $val_pedido = $valor_pedido['valor_total'] + $valor_total;
                            $pedido = [
                                'valor_total' => $val_pedido,
                                'cantidad_de_productos' => $cant_productos,
                            ];

                            $model = model('pedidoModel');
                            $actualizar = $model->set($pedido);
                            $actualizar = $model->where('id', $numero_pedido);
                            $actualizar = $model->update();



                            if ($insertar && $actualizar) {

                                $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido);
                                $productos_del_pedido = view('productos_pedido/productos_pedido', [
                                    "productos" => $productos_pedido,
                                    "pedido" => $numero_pedido
                                ]);
                                $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa)->first();
                                $total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();
                                $cantidad_de_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido)->first();
                                $nota_pedido = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido)->first();
                                $returnData = array(
                                    "resultado" => 2,  // la mesa ya tiene productos
                                    "id_mesa" => $id_mesa,
                                    "numero_pedido" => $numero_pedido,
                                    "productos_pedido" => $productos_del_pedido,
                                    "nombre_mesa" => $nombre_mesa['nombre'],
                                    "total" => "$" . number_format($total['valor_total'], 0, ',', '.'),
                                    "cantidad_de_productos" => $cantidad_de_productos['cantidad_de_productos'],
                                    "nota_pedido" => $nota_pedido['nota_pedido']
                                );
                                echo  json_encode($returnData);
                            }
                        } else if ($num_pedido['id'] !== $numero_pedido) {
                            // echo "Se debe de refrescar la pagina por que no coincide el pedido con la mesa  ";
                            $returnData = array(
                                "resultado" => 3,  // la mesa ya tiene productos
                            );
                            echo  json_encode($returnData);
                        }
                    } else  if (!empty($existe_producto)) {

                        $num_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();
                        $cantidad_producto = model('productoPedidoModel')->cantidad_producto($num_pedido['id'], $codigo_interno_producto);
                        $valor_total_producto = model('productoPedidoModel')->select('valor_total')->where('numero_de_pedido', $num_pedido['id'])->first();
                        $valor_total_producto = model('productoPedidoModel')->select('valor_total')->where('codigointernoproducto', $codigo_interno_producto)->first();

                        $actualizar_cantidad_producto = model('productoPedidoModel')->actualizar_cantidad_producto($num_pedido['id'], $codigo_interno_producto, $cantidad_producto[0]['cantidad_producto'] + $cantidad, $nota_producto, $valor_total + $valor_total_producto['valor_total']);

                        $valor_pedido = model('pedidoModel')->select('valor_total')->where('id', $num_pedido['id'])->first();

                        $data = [
                            'valor_total' => $valor_pedido['valor_total'] + $valor_total
                        ];


                        if ($actualizar_cantidad_producto) {
                            $valor_total_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
                            $cantidades_totales = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido)->find();
                            $data_pedido = [
                                'valor_total' => $valor_total_pedido[0]['valor_total'],
                                'cantidad_de_productos' => $cantidades_totales[0]['cantidad_producto']
                            ];

                            $model = model('pedidoModel');
                            $actualizar = $model->set($data_pedido);
                            $actualizar = $model->where('id', $numero_pedido);
                            $actualizar = $model->update();

                            $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido);
                            $productos_del_pedido = view('productos_pedido/productos_pedido', [
                                "productos" => $productos_pedido,
                                "pedido" => $numero_pedido
                            ]);

                            $total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();
                            $cantidad_de_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido)->first();

                            $returnData = array(
                                "resultado" => 2,  // la mesa ya tiene productos
                                "productos_pedido" => $productos_del_pedido,
                                "total" => "$" . number_format($total['valor_total'], 0, ',', '.'),
                                "cantidad_de_productos" => $cantidad_de_productos['cantidad_de_productos'],
                                "numero_pedido" => $num_pedido['id'],
                                "id_mesa" => $id_mesa,
                                "valor_total" => $total['valor_total']
                            );
                            echo  json_encode($returnData);
                        }
                    }
                } else if ($configuracion_pedido['agregar_item'] == 1) {
                    $se_imprime_en_comanda = model('productoModel')->select('se_imprime')->where('codigointernoproducto', $codigo_interno_producto)->first();
                    $codigo_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $codigo_interno_producto)->first();
                    if ($num_pedido['id'] == $numero_pedido) {

                        $producto_pedido = [
                            'numero_de_pedido' => $numero_pedido,
                            'cantidad_producto' => $cantidad,
                            'nota_producto' => $nota_producto,
                            'valor_unitario' => $valor_unitario,
                            'impresion_en_comanda' => false,
                            'cantidad_entregada' => 0,
                            'valor_total' => $valor_total,
                            'se_imprime_en_comanda' =>  $se_imprime_en_comanda['se_imprime'],
                            'codigo_categoria' =>   $codigo_categoria['codigocategoria'],
                            'codigointernoproducto' => $codigo_interno_producto,
                            'numero_productos_impresos_en_comanda' => 0
                        ];
                        $insertar = model('productoPedidoModel')->insert($producto_pedido);


                        $cantidad_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido)->first();
                        $cant_productos = $cantidad_productos['cantidad_de_productos'] + $cantidad;

                        $valor_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();
                        $val_pedido = $valor_pedido['valor_total'] + $valor_total;
                        $pedido = [
                            'valor_total' => $val_pedido,
                            'cantidad_de_productos' => $cant_productos,
                        ];

                        $model = model('pedidoModel');
                        $actualizar = $model->set($pedido);
                        $actualizar = $model->where('id', $numero_pedido);
                        $actualizar = $model->update();



                        if ($insertar && $actualizar) {

                            $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido);
                            $productos_del_pedido = view('productos_pedido/productos_pedido', [
                                "productos" => $productos_pedido,
                                "pedido" => $numero_pedido
                            ]);
                            $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa)->first();
                            $total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();
                            $cantidad_de_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido)->first();
                            $nota_pedido = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido)->first();
                            $returnData = array(
                                "resultado" => 2,  // la mesa ya tiene productos
                                "id_mesa" => $id_mesa,
                                "numero_pedido" => $numero_pedido,
                                "productos_pedido" => $productos_del_pedido,
                                "nombre_mesa" => $nombre_mesa['nombre'],
                                "total" => "$" . number_format($total['valor_total'], 0, ',', '.'),
                                "cantidad_de_productos" => $cantidad_de_productos['cantidad_de_productos'],
                                "nota_pedido" => $nota_pedido['nota_pedido']
                            );
                            echo  json_encode($returnData);
                        }
                    } else if ($num_pedido['id'] !== $numero_pedido) {
                        // echo "Se debe de refrescar la pagina por que no coincide el pedido con la mesa  ";
                        $returnData = array(
                            "resultado" => 3,  // la mesa ya tiene productos
                        );
                        echo  json_encode($returnData);
                    }
                }
            }
        }
    }

    public function buscar_productos_id_categoria()
    {
        $id_categoria = $_POST['id_categoria'];
        $productos = model('productoModel')->tipoInventario($id_categoria);

        echo view('productos_pedido/productos_por_id_categoria', [
            'productos' => $productos
        ]);
    }

    public function agregar_productos_x_categoria()
    {
        $id_producto = $_POST['id_producto'];

        $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $id_producto)->first();
        $valor_venta = model('productoModel')->select('valorventaproducto')->where('codigointernoproducto', $id_producto)->first();

        $returnData = array(
            "id_producto" => $id_producto,
            "nombre_producto" =>  $nombre_producto['nombreproducto'],
            "valor_venta" =>  "$" . number_format($valor_venta['valorventaproducto'], 0, ',', '.'),
            "valor_venta_sin_formato" =>  $valor_venta['valorventaproducto'],
            "resultado" => 1
        );
        echo  json_encode($returnData);
    }

    public function productos_del_pedido_para_facturar()
    {
        $numero_pedido = $_POST['numero_de_pedido'];

        $productos_del_pedido_para_facturar = model('productoPedidoModel')->productos_del_pedido_para_facturar($numero_pedido);
        $total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();

        $productos = view('facturar_pedido/facturar_pedido_pedido_tbody', [
            'productos' => $productos_del_pedido_para_facturar
        ]);

        $returnData = array(
            "productos" => $productos,
            "resultado" => 1,
            "total" => $total['valor_total'],
            "total_con_formato" => number_format($total['valor_total'], 0, ',', '.')
        );
        echo  json_encode($returnData);
    }

    public function detalle_pedido()
    {
        $numero_pedido = $_POST['numero_pedido'];
        $pedido = model('pedidoModel')->pedido($numero_pedido);
        $productos = model('productoPedidoModel')->detalle_pedido($numero_pedido);

        $detalle_pedido = view('pedido/detalle_pedido', [
            'usuario' => $pedido[0]['nombresusuario_sistema'],
            'valor_total' => $pedido[0]['valor_total'],
            'cantidad_de_pructos' => $pedido[0]['cantidad_de_productos'],
            'fecha_pedido' => $pedido[0]['fecha_creacion'],
            'mesa' => $pedido[0]['nombre'],
            'pedido' => $numero_pedido,
            'productos' => $productos,
            'nota_pedido' => $pedido[0]['nota_pedido'],
            'fecha_pedido' => $pedido[0]['fecha_creacion']
        ]);

        $returnData = array(
            "detalle_pedido" => $detalle_pedido,
            "resultado" => 1
        );
        echo  json_encode($returnData);
    }


    public function editar_cantidades_de_pedido()
    {
        //$id_usuario = 20;
        $id_usuario = $_POST['id_usuario'];
        // $id_tabla_producto_pedido = 1600;        

        $id_tabla_producto_pedido = $_POST['id_tabla_producto_pedido'];


        //$impresion_en_comanda = model('productoPedidoModel')->select('impresion_en_comanda')->where('id', $id_tabla_producto_pedido)->first();
        $tipo_usuario = model('usuariosModel')->select('idtipo')->where('idusuario_sistema', $id_usuario)->first();

        //$notas = model('notaPedidoModel')->where('id_tabla_producto', $id_tabla_producto_pedido)->findAll();
        $producto = model('productoPedidoModel')->editar_producto_pedido($id_tabla_producto_pedido);

        $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_tabla_producto_pedido)->first();
        $cantidades_impresas = model('productoPedidoModel')->select('numero_productos_impresos_en_comanda')->where('id', $id_tabla_producto_pedido)->first();


        if ($cantidad_producto['cantidad_producto'] == $cantidades_impresas['numero_productos_impresos_en_comanda'] && $tipo_usuario['idtipo'] == 0) {
            $returnData = array(
                "resultado" => 1,
                "codigo_interno" => $producto[0]['codigointernoproducto'],
                "descripcion" => $producto[0]['nombreproducto'],
                "valor_unitario" => $producto[0]['valor_unitario'],
                "valor_unitario_formato" => "$" . number_format($producto[0]['valor_unitario'], 0, ',', '.'),
                "cantidad" => $producto[0]['cantidad_producto'],
                "total" => $producto[0]['valor_total'],
                "notas" => $producto[0]['nota_producto'],
                "id_tabla_producto_pedido" => $id_tabla_producto_pedido,

            );
            echo  json_encode($returnData);
        }

        if ($cantidad_producto['cantidad_producto'] > $cantidades_impresas['numero_productos_impresos_en_comanda']) {
            $returnData = array(
                "resultado" => 1,
                "codigo_interno" => $producto[0]['codigointernoproducto'],
                "descripcion" => $producto[0]['nombreproducto'],
                "valor_unitario" => $producto[0]['valor_unitario'],
                "valor_unitario_formato" => "$" . number_format($producto[0]['valor_unitario'], 0, ',', '.'),
                "cantidad" => $producto[0]['cantidad_producto'],
                "total" => $producto[0]['valor_total'],
                "notas" => $producto[0]['nota_producto'],
                "id_tabla_producto_pedido" => $id_tabla_producto_pedido,

            );
            echo  json_encode($returnData);
        }
        if ($cantidad_producto['cantidad_producto'] == $cantidades_impresas['numero_productos_impresos_en_comanda'] && $tipo_usuario['idtipo'] == 1) {
            $returnData = array(
                "resultado" => 0,
                "id_tabla_producto" => $id_tabla_producto_pedido
            );
            echo  json_encode($returnData);
        }
    }
    public function buscar_por_codigo_de_barras()
    {
        $codigo_de_barras = $_POST['codigo_de_barras'];
        $lista_precios = $_REQUEST['lista_precios'];

        if ($lista_precios == 0) {

            $buscar_producto_por_codigo_de_barras = model('productoModel')->buscar_producto_por_codigo_de_barras($codigo_de_barras);

            if (!empty($buscar_producto_por_codigo_de_barras)) {
                $returnData = array(
                    "resultado" => 1,
                    "codigo_interno_producto" => $buscar_producto_por_codigo_de_barras[0]['codigointernoproducto'],
                    "nombre_producto" => $buscar_producto_por_codigo_de_barras[0]['nombreproducto'],
                    "valor_venta_producto" => number_format($buscar_producto_por_codigo_de_barras[0]['valorventaproducto'], 0, ",", "."),
                );
                echo  json_encode($returnData);
            } else if (empty($buscar_producto_por_codigo_de_barras)) {
                $returnData = array(
                    "resultado" => 0,
                );
                echo  json_encode($returnData);
            }
        } else if ($lista_precios == 1) {
            /*   $buscar_producto_por_codigo_de_barras = model('productoModel')->buscar_producto_por_codigo_de_barras_al_por_mayor($codigo_de_barras);

            if (!empty($buscar_producto_por_codigo_de_barras)) {
                $returnData = array(
                    "resultado" => 1,
                    "codigointernoproducto" => $buscar_producto_por_codigo_de_barras[0]['codigointernoproducto'],
                    "nombre_producto" => $buscar_producto_por_codigo_de_barras[0]['nombreproducto'],
                    "valor_venta_producto" => number_format($buscar_producto_por_codigo_de_barras[0]['valorventaproducto'], 0, ",", "."),
                );
                echo  json_encode($returnData);
            } else if (empty($buscar_producto_por_codigo_de_barras)) {
                $returnData = array(
                    "resultado" => 0,
                );
                echo  json_encode($returnData);
            } */

            $buscar_producto_por_codigo_de_barras = model('productoModel')->buscar_producto_por_codigo_de_barras($codigo_de_barras);



            $lista_de_precios = model('productoModel')->listado_de_precios($codigo_de_barras);



            $returnData = array(
                "resultado" => 1,
                'lista_precios' => view('producto/lista_precios_pedido', [
                    'lista_precios' => $lista_de_precios
                ]),
                'nombre_producto' => $buscar_producto_por_codigo_de_barras[0]['nombreproducto'],
                'codigo_interno_producto' => $buscar_producto_por_codigo_de_barras[0]['codigointernoproducto']
            );
            echo  json_encode($returnData);
        }
    }

    public function cargar_producto_al_pedido()
    {
        $codigointernoproducto = $_POST['codigo_interno_producto'];
        $cantidad = $_POST['cantidad'];
        $usuario_facturacion = $_POST['usuario_facturacion'];

        $lista_precios = $_REQUEST['lista_precios'];
        $select_lista_precios = $_REQUEST['select_lista_precios'];

        //exit($usuario_facturacion);
        /*
        0. Precio al detal 
        1.Precio al por mayor 
        */

        /*  $codigointernoproducto = 8;
        $cantidad = 1;
        $usuario_facturacion = 8;
        $lista_precios = 0;
        $select_lista_precios = 1; */
        $codigo_interno_producto = (string)$codigointernoproducto;
        $temp_valor_venta = model('productoModel')->select('valorventaproducto')->where('codigointernoproducto', $codigo_interno_producto)->first();
        if ($lista_precios == 0) {

            $valor_venta = $temp_valor_venta['valorventaproducto'];
        } else if ($lista_precios == 1) {

            $porcentaje_descuento = model('productoModel')->select('descto_mayor')->where('codigointernoproducto', $codigo_interno_producto)->first();
            $temp = ($temp_valor_venta['valorventaproducto'] * $porcentaje_descuento['descto_mayor']) / 100;
            $valor_venta = $temp_valor_venta['valorventaproducto'] - $temp;
            // $valor_venta = $temp;
        }


        $nota_producto = $_POST['nota_producto'];

        $tiene_pedido_usuario = model('pedidoPosModel')->select('fk_usuario')->where('fk_usuario', $usuario_facturacion)->first();
        // exit('lista de precios ');

        if (empty($tiene_pedido_usuario)) {
            $data = [
                'fk_usuario' => $usuario_facturacion,
                'valor_total' => $valor_venta * $cantidad,
            ];
            $insert = model('pedidoPosModel')->insert($data);

            if ($insert) {
                $pk_pedido_pos = model('pedidoPosModel')->insertID;
                $se_imprime_en_comanda = model('productoModel')->select('se_imprime')->where('codigointernoproducto', $codigo_interno_producto)->first();
                $codigo_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $codigo_interno_producto)->first();

                $producto_pedido_pos = [
                    'codigointernoproducto' => $codigo_interno_producto,
                    'cantidad_producto' => $cantidad,
                    'valor_unitario' => $valor_venta,
                    'valor_total' => $valor_venta * $cantidad,
                    'id_categoria' => $codigo_categoria['codigocategoria'],
                    'se_imprime_en_comanda' => $se_imprime_en_comanda['se_imprime'],
                    'impreso_en_comanda' => false,
                    'nota_producto' => $nota_producto,
                    'pk_pedido_pos' => $pk_pedido_pos
                ];
                $insertar = model('productoPedidoPosModel')->insert($producto_pedido_pos);
                if ($insertar) {
                    $total = model('pedidoPosModel')->select('valor_total')->where('id', $pk_pedido_pos)->first();
                    $productos_pedido_pos = model('productoPedidoPosModel')->producto_pedido_pos($pk_pedido_pos);
                    $productos_del_pedido = view('productos_pedido_pos/productos_pedido', [
                        "productos" => $productos_pedido_pos
                    ]);
                    $returnData = array(
                        "resultado" => 1,
                        "productos" => $productos_del_pedido,
                        "total" => number_format($total['valor_total'], 0, ",", "."),
                        "select_lista_precios" => $select_lista_precios
                    );
                    echo  json_encode($returnData);
                }
            }
        }

        if (!empty($tiene_pedido_usuario)) {


            $pk_pedido_pos = model('pedidoPosModel')->select('id')->where('fk_usuario', $usuario_facturacion)->first();
            $se_imprime_en_comanda = model('productoModel')->select('se_imprime')->where('codigointernoproducto', $codigo_interno_producto)->first();
            $codigo_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $codigo_interno_producto)->first();


            $producto_pedido_pos = [
                'codigointernoproducto' => $codigo_interno_producto,
                'cantidad_producto' => $cantidad,
                'valor_unitario' => $valor_venta,
                'valor_total' => $valor_venta * $cantidad,
                'id_categoria' => $codigo_categoria['codigocategoria'],
                'se_imprime_en_comanda' => $se_imprime_en_comanda['se_imprime'],
                'impreso_en_comanda' => false,
                'nota_producto' => $nota_producto,
                'pk_pedido_pos' => $pk_pedido_pos['id']
            ];
            $insertar = model('productoPedidoPosModel')->insert($producto_pedido_pos);
            if ($insertar) {

                $valor_total = model('pedidoPosModel')->select('valor_total')->where('id', $pk_pedido_pos['id'])->first();
                $valor_producto = $valor_venta * $cantidad;

                $total = $valor_total['valor_total'] + $valor_producto;

                $data = [
                    'valor_total' => $total,

                ];

                $model = model('pedidoPosModel');
                $actualizar = $model->set($data);
                $actualizar = $model->where('id', $pk_pedido_pos['id']);
                $actualizar = $model->update();

                $total = model('pedidoPosModel')->select('valor_total')->where('id', $pk_pedido_pos['id'])->first();
                $productos_pedido_pos = model('productoPedidoPosModel')->producto_pedido_pos($pk_pedido_pos['id']);
                $productos_del_pedido = view('productos_pedido_pos/productos_pedido', [
                    "productos" => $productos_pedido_pos
                ]);

                $returnData = array(
                    "resultado" => 2,
                    "productos" => $productos_del_pedido,
                    "total" => number_format($total['valor_total'], 0, ",", "."),
                    "select_lista_precios" => $select_lista_precios
                );
                echo  json_encode($returnData);
            }
        }
    }
    public function entregar_producto()
    {
        $id_producto_pedido = $_POST['id_producto_pedido'];

        $cantidad_entregada = model('productoPedidoModel')->select('cantidad_entregada')->where('id', $id_producto_pedido)->first();
        $cantidad_solicitada = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_producto_pedido)->first();

        if ($cantidad_entregada['cantidad_entregada'] < $cantidad_solicitada['cantidad_producto']) {
            $returnData = array(
                "resultado" => 1,
                "cantidad_solicitada" => $cantidad_solicitada['cantidad_producto'],
                "cantidad_entregada" => $cantidad_entregada['cantidad_entregada'],
                "id_producto_pedido" => $id_producto_pedido
            );
            echo  json_encode($returnData);
        }

        if ($cantidad_entregada['cantidad_entregada'] == $cantidad_solicitada['cantidad_producto']) {
            $returnData = array(
                "resultado" => 2,
            );
            echo  json_encode($returnData);
        }
    }

    public function actualizar_entregar_producto()
    {

        $id_producto_pedido = $_POST['id_producto_pedido'];
        $cantidad = $_POST['cantidad'];
        $cantidad_solicitada = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_producto_pedido)->first();
        $cantidad_entregada = model('productoPedidoModel')->select('cantidad_entregada')->where('id', $id_producto_pedido)->first();

        if (($cantidad_entregada['cantidad_entregada'] + $cantidad) <= $cantidad_solicitada['cantidad_producto']) {


            $actualizar_cantidad = $cantidad + $cantidad_entregada['cantidad_entregada'];

            $data = [
                'cantidad_entregada' => $actualizar_cantidad,

            ];

            $model = model('productoPedidoModel');
            $actualizar = $model->set($data);
            $actualizar = $model->where('id', $id_producto_pedido);
            $actualizar = $model->update();

            $numero_de_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_producto_pedido)->first();

            $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_de_pedido['numero_de_pedido']);

            $productos_del_pedido = view('productos_pedido/productos_pedido', [
                "productos" => $productos_pedido,
                "pedido" => $numero_de_pedido['numero_de_pedido']
            ]);

            $codigo_interno_producto = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_producto_pedido)->first();
            $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigointernoproducto'])->first();

            $returnData = array(
                "resultado" => 1,
                "productos" => $productos_del_pedido,
                "pedido" => $numero_de_pedido['numero_de_pedido'],
                "mensaje" => "SE HA ENTREGADO " . $cantidad . " DE " . $nombre_producto['nombreproducto'],
            );
            echo  json_encode($returnData);
        }

        if (($cantidad_entregada['cantidad_entregada'] + $cantidad) > $cantidad_solicitada['cantidad_producto']) {
            $returnData = array(
                "resultado" => 2,
            );
            echo  json_encode($returnData);
        }
    }

    public function usuario_pedido()
    {

        $id_usuario = $_POST['id_usuario'];

        $tiene_pedido_usuario = model('pedidoPosModel')->select('fk_usuario')->where('fk_usuario', $id_usuario)->first();

        if (!empty($tiene_pedido_usuario)) {

            $observacion_general = model('pedidoPosModel')->select('nota_general')->where('fk_usuario', $id_usuario)->first();
            $returnData = array(
                "resultado" => 1,
                "observacion_general" => $observacion_general['nota_general']
            );
            echo  json_encode($returnData);
        } else {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        }
    }

    public function agregar_observacion_general()
    {
        $id_usuario = $_POST['id_usuario'];
        $observacion_general = $_POST['observacion_general'];

        $data = [
            'nota_general' =>  $observacion_general,
        ];

        $model = model('pedidoPosModel');
        $actualizar = $model->set($data);
        $actualizar = $model->where('fk_usuario', $id_usuario);
        $actualizar = $model->update();

        if ($actualizar) {
            $returnData = array(
                "resultado" => 1,
                "observaciones_general" => $observacion_general
            );
            echo  json_encode($returnData);
        }
    }

    public function facturar_pedido()
    {

        $apertura = model('aperturaRegistroModel')->select('idcaja')->first();
        if (!empty($apertura['idcaja'])) {
            $fk_mesa = $_POST['id_mesa_facturacion'];

            $numero_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $fk_mesa)->first();

            if (!empty($numero_pedido)) {
                $estado = model('estadoModel')->findAll();
                $regimen = model('regimenModel')->select('*')->find();
                $productos_del_pedido_para_facturar = model('productoPedidoModel')->productos_del_pedido_para_facturar($numero_pedido['id']);
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

                return view('pedido/facturar_pedido', [
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
            } else {
                $session = session();
                $session->setFlashdata('iconoMensaje', 'warning');
                return redirect()->to(base_url('salones/salones'))->with('mensaje', 'Mesa no tiene pedido para facturar ');
            }
        } elseif (empty($apertura['idcaja'])) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'error');
            return redirect()->to(base_url('salones/salones'))->with('mensaje', 'No hay apertura de caja ');
        }
    }

    public function insertar_producto_desde_categoria()
    {
        $codigo_interno_producto = $_POST['codigo_interno_producto'];
        $cantidad = $_POST['cantidad'];
        $numero_pedido = $_POST['numero_pedido'];
        $id_usuario = $_POST['id_usuario'];
        $id_mesa = $_POST['id_mesa'];
        $valor_total = $_POST['valor_total'];
        $nota_producto = $_POST['nota_producto'];
        $valor_unitario = $_POST['valor_unitario'];

        if ($cantidad == 0 || $cantidad < 0 || $cantidad == "") {
            $returnData = array(
                "resultado" => 0
            );
            echo  json_encode($returnData);
        } else {

            if ($numero_pedido == "") {
                $data = [
                    'fk_mesa' => $id_mesa,
                    'fk_usuario' => $id_usuario,
                    'valor_total' => $valor_total,
                    'cantidad_de_productos' => $cantidad,
                ];

                $insert = model('pedidoModel')->insert($data);

                $datos_mesa = [
                    'estado' => 1,
                    'valor_pedido' => $valor_total,
                    'fk_usuario' => $id_usuario
                ];

                $model = model('mesasModel');
                $actualizar = $model->set($datos_mesa);
                $actualizar = $model->where('id', $id_mesa);
                $actualizar = $model->update();

                if ($insert) {
                    $ultimo_id_pedido = model('pedidoModel')->insertID;
                    $se_imprime_en_comanda = model('productoModel')->select('se_imprime')->where('codigointernoproducto', $codigo_interno_producto)->first();
                    $codigo_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $codigo_interno_producto)->first();
                    $producto_pedido = [
                        'numero_de_pedido' => $ultimo_id_pedido,
                        'cantidad_producto' => $cantidad,
                        'nota_producto' => $nota_producto,
                        'valor_unitario' => $valor_unitario,
                        'impresion_en_comanda' => false,
                        'cantidad_entregada' => 0,
                        'valor_total' => $valor_total,
                        'se_imprime_en_comanda' => $se_imprime_en_comanda['se_imprime'],
                        'codigo_categoria' => $codigo_categoria['codigocategoria'],
                        'codigointernoproducto' => $codigo_interno_producto

                    ];

                    $insertar = model('productoPedidoModel')->insert($producto_pedido);

                    if ($insertar) {
                        $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa)->first();
                        $productos_pedido = model('productoPedidoModel')->producto_pedido($ultimo_id_pedido);
                        $total_pedido = model('pedidoModel')->select('valor_total')->where('id', $ultimo_id_pedido)->first();
                        $cantidad_de_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $ultimo_id_pedido)->first();
                        $productos_del_pedido = view('productos_pedido/productos_pedido', [
                            "productos" => $productos_pedido
                        ]);
                        $returnData = array(
                            "resultado" => 1,
                            "id_mesa" => $id_mesa,
                            "numero_pedido" => $ultimo_id_pedido,
                            "productos_pedido" => $productos_del_pedido,
                            "nombre_mesa" => $nombre_mesa['nombre'],
                            "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),
                            "cantidad_de_pruductos" => $cantidad_de_productos['cantidad_de_productos']

                        );
                        echo  json_encode($returnData);
                    }
                }
            } else {

                $se_imprime_en_comanda = model('productoModel')->select('se_imprime')->where('codigointernoproducto', $codigo_interno_producto)->first();
                $codigo_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $codigo_interno_producto)->first();
                $num_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();

                if ($num_pedido['id'] == $numero_pedido) {

                    $producto_pedido = [
                        'numero_de_pedido' => $numero_pedido,
                        'cantidad_producto' => $cantidad,
                        'nota_producto' => $nota_producto,
                        'valor_unitario' => $valor_unitario,
                        'impresion_en_comanda' => false,
                        'cantidad_entregada' => 0,
                        'valor_total' => $valor_total,
                        'se_imprime_en_comanda' =>  $se_imprime_en_comanda['se_imprime'],
                        'codigo_categoria' =>   $codigo_categoria['codigocategoria'],
                        'codigointernoproducto' => $codigo_interno_producto
                    ];
                    $insertar = model('productoPedidoModel')->insert($producto_pedido);

                    $cantidad_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido)->first();
                    $cant_productos = $cantidad_productos['cantidad_de_productos'] + $cantidad;

                    $valor_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();
                    $val_pedido = $valor_pedido['valor_total'] + $valor_total;
                    $pedido = [
                        'valor_total' => $val_pedido,
                        'cantidad_de_productos' => $cant_productos,
                    ];

                    $model = model('pedidoModel');
                    $actualizar = $model->set($pedido);
                    $actualizar = $model->where('id', $numero_pedido);
                    $actualizar = $model->update();

                    $mesa = [
                        'valor_pedido' => $val_pedido,
                    ];
                    $fk_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
                    $model = model('mesasModel');
                    $actualizar_mesa = $model->set($mesa);
                    $actualizar_mesa = $model->where('id', $fk_mesa['fk_mesa']);
                    $actualizar_mesa = $model->update();

                    if ($insertar && $actualizar && $actualizar_mesa) {

                        $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido);
                        $productos_del_pedido = view('productos_pedido/productos_pedido', [
                            "productos" => $productos_pedido
                        ]);
                        $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa)->first();
                        $total = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();
                        $cantidad_de_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido)->first();
                        $returnData = array(
                            "resultado" => 2,  // la mesa ya tiene productos
                            "id_mesa" => $id_mesa,
                            "numero_pedido" => $numero_pedido,
                            "productos_pedido" => $productos_del_pedido,
                            "nombre_mesa" => $nombre_mesa['nombre'],
                            "total" => "$" . number_format($total['valor_total'], 0, ',', '.'),
                            "cantidad_de_productos" => $cantidad_de_productos['cantidad_de_productos']
                        );
                        echo  json_encode($returnData);
                    }
                } else if ($num_pedido['id'] !== $numero_pedido) {
                    // echo "Se debe de refrescar la pagina por que no coincide el pedido con la mesa  ";
                    $returnData = array(
                        "resultado" => 3,  // la mesa ya tiene productos
                    );
                    echo  json_encode($returnData);
                }
            }
        }
    }

    public function actualizar_cantidades_de_pedido()
    {
        $cantidad = $_POST['cantidad'];
        $id_tabla_producto = $_POST['id_tabla_producto'];
        $notas = $_POST['notas'];
        if ($cantidad == 0 || $cantidad == "" || $cantidad < 0) {
            $returnData = array(
                "resultado" => 0,  // la mesa ya tiene productos
            );
            echo  json_encode($returnData);
        } else {


            $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_tabla_producto)->first();
            $fk_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido['numero_de_pedido'])->first();

            $valor_unitario = model('productoPedidoModel')->select('valor_unitario')->where('id', $id_tabla_producto)->first();
            $valor_total = $valor_unitario['valor_unitario'] * $cantidad;

            $data = [
                'cantidad_producto' => $cantidad,
                'valor_total' => $valor_total,
                'nota_producto' => $notas

            ];
            $model = model('productoPedidoModel');
            $actualizar = $model->set($data);
            $actualizar = $model->where('id', $id_tabla_producto);
            $actualizar = $model->update();
            if ($actualizar) {
                $valor_total_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->find();
                $cantidad_productos = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->find();



                $actualizar_total_pedido = [
                    'valor_total' => $valor_total_pedido[0]['valor_total'],
                    'cantidad_de_productos' => $cantidad_productos[0]['cantidad_producto']
                ];
                $model = model('pedidoModel');
                $actualizar = $model->set($actualizar_total_pedido);
                $actualizar = $model->where('id', $numero_pedido['numero_de_pedido']);
                $actualizar = $model->update();

                $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);
                $total_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido['numero_de_pedido'])->first();
                $cantidad_de_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido['numero_de_pedido'])->first();
                $productos_del_pedido = view('productos_pedido/productos_pedido', [
                    "productos" => $productos_pedido,
                    "pedido" => $numero_pedido['numero_de_pedido']
                ]);

                $returnData = array(
                    "resultado" => 1,  // Se actulizo el registro 
                    "productos" => $productos_del_pedido,
                    "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),
                    "cantidad_de_pruductos" => $cantidad_de_productos['cantidad_de_productos']
                );
                echo  json_encode($returnData);
            } else {
            }
        }
    }

    public function eliminar_producto()
    {
        //$id_tabla_producto = 1590;
        $id_tabla_producto = $_POST['id_tabla_producto'];
        $codigo_interno_producto = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_tabla_producto)->first();

        $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigointernoproducto'])->first();

        $id_usuario = $_POST['id_usuario'];
        //$id_usuario = 8;
        $producto = model('productoPedidoModel')->editar_producto_pedido($id_tabla_producto); //Validacion de registro existente 
        // $tiene_permiso = model('tipoPermisoModel')->puede_borrar_de_pedido($id_usuario); // que el usuario tenga asignado el permiso con id 93
        $tiene_permiso = model('usuariosModel')->select('idtipo')->where('idusuario_sistema', $id_usuario)->first();
        $impresion_en_comanda = model('productoPedidoModel')->select('impresion_en_comanda')->where('id', $id_tabla_producto)->first();

        if ($impresion_en_comanda['impresion_en_comanda'] == 'f') {
            $returnData = array(
                "resultado" => 1,  // Usuario no cuenta con permiso  
                "nombre_producto" => $nombre_producto['nombreproducto'],
                "id_tabla_proiducto" => $id_tabla_producto
            );
            echo  json_encode($returnData);
        }

        if ($tiene_permiso['idtipo'] == 0 and $impresion_en_comanda['impresion_en_comanda'] == 't') { // El usuario es administrador y puede eliminar 
            $returnData = array(
                "resultado" => 1,
                "nombre_producto" => $nombre_producto['nombreproducto'],
                "id_tabla_proiducto" => $id_tabla_producto
            );
            echo  json_encode($returnData);
        }
        if ($tiene_permiso['idtipo'] == 1 and $impresion_en_comanda['impresion_en_comanda'] == 't') { // El usuario es general y no  puede eliminar despues de ser impreso en comanda
            $returnData = array(
                "resultado" => 0,
                "id_tabla_producto" => $id_tabla_producto
            );
            echo  json_encode($returnData);
        }
    }



    public function eliminacion_de_producto()
    {
        $id_tabla_producto = $_POST['id_tabla_producto'];
        $id_usuario = $_POST['id_usuario'];

        //$puede_borrar_de_pedido_y_editar_despues_de_impreso_comanda = model('tipoPermisoModel')->puede_borrar_de_pedido_y_editar_despues_de_impreso_comanda($id_usuario);
        $tipo_usuario = model('usuariosModel')->select('idtipo')->where('idusuario_sistema', $id_usuario)->first();
        if ($tipo_usuario['idtipo'] == 0) {
            $item = model('productoPedidoModel')->where('id', $id_tabla_producto)->first();

            $producto = [
                'codigointernoproducto' => $item['codigointernoproducto'],
                'cantidad' => $item['cantidad_producto'],
                'fecha_eliminacion' => date('Y-m-d'),
                'hora_eliminacion' => date('H:i:s'),
                'usuario_eliminacion' => $id_usuario,
                'pedido' => $item['numero_de_pedido']
            ];

            $insert = model('productosBorradosModel')->insert($producto);

            $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_tabla_producto)->first();
            $borrar_producto_pedido = model('productoPedidoModel')->where('id', $id_tabla_producto);
            $borrar_producto_pedido->delete();

            if ($borrar_producto_pedido) {

                $fk_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido['numero_de_pedido'])->first();
                $valor_total_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->find();
                $cantidad_productos = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->find();

                $actualizar_total_pedido_mesa = [
                    'valor_pedido' => $valor_total_pedido[0]['valor_total']
                ];
                $model = model('mesasModel');
                $actualizar = $model->set($actualizar_total_pedido_mesa);
                $actualizar = $model->where('id', $fk_mesa['fk_mesa']);
                $actualizar = $model->update();

                $actualizar_total_pedido = [
                    'valor_total' => $valor_total_pedido[0]['valor_total'],
                    'cantidad_de_productos' => $cantidad_productos[0]['cantidad_producto']
                ];
                $model = model('pedidoModel');
                $actualizar = $model->set($actualizar_total_pedido);
                $actualizar = $model->where('id', $numero_pedido['numero_de_pedido']);
                $actualizar = $model->update();

                $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);
                $total_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido['numero_de_pedido'])->first();
                $cantidad_de_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido['numero_de_pedido'])->first();
                $productos_del_pedido = view('productos_pedido/productos_pedido', [
                    "productos" => $productos_pedido,
                    "pedido" => $numero_pedido['numero_de_pedido']
                ]);

                $returnData = array(
                    "resultado" => 1,  // Se actulizo el registro 
                    "productos" => $productos_del_pedido,
                    "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),
                    "cantidad_de_pruductos" => $cantidad_de_productos['cantidad_de_productos']
                );
                echo  json_encode($returnData);
            }
        } else if ($tipo_usuario['idtipo'] == 1) {
            $returnData = array(
                "resultado" => 0,  // Usuario no tiene permiso de eliminacion 
                "id_tabla_producto" => $id_tabla_producto
            );
            echo  json_encode($returnData);
        }
    }

    public function cargar_item_al_pedido()
    {
        $codigointernoproducto = $_POST['codigo_interno_producto'];

        $cantidad = $_POST['cantidad'];
        $numero_pedido = $_POST['numero_pedido'];

        $lista_precios = 0;
        $codigo_interno_producto = (string)$codigointernoproducto;
        $valor_uni = model('productoModel')->select('valorventaproducto')->where('codigointernoproducto', $codigo_interno_producto)->first();


        /*   $codigointernoproducto = 8;
        $cantidad = 1;
        $numero_pedido = 14572;
        $lista_precios=0;
        $codigo_interno_producto = (string)$codigointernoproducto; */




        if ($lista_precios == 0) {

            $valor_unitario = $valor_uni['valorventaproducto'];
            $valor_total = $valor_unitario * $cantidad;
        }
        if ($lista_precios == 1) {
            $porcentaje_descuento = model('productoModel')->select('descto_mayor')->where('codigointernoproducto', $codigo_interno_producto)->first();

            $temp = ($valor_uni['valorventaproducto'] * $porcentaje_descuento['descto_mayor']) / 100;
            $valor_unitario = $valor_uni['valorventaproducto'] - $temp;
            $valor_total = $valor_unitario * $cantidad;
        }


        $se_imprime_en_comanda = model('productoModel')->select('se_imprime')->where('codigointernoproducto', $codigo_interno_producto)->first();
        $codigo_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $codigo_interno_producto)->first();

        $producto_pedido = [
            'numero_de_pedido' => $numero_pedido,
            'cantidad_producto' => $cantidad,
            'valor_unitario' => $valor_unitario,
            'impresion_en_comanda' => false,
            'cantidad_entregada' => 0,
            'valor_total' => $valor_total,
            'se_imprime_en_comanda' =>  $se_imprime_en_comanda['se_imprime'],
            'codigo_categoria' =>   $codigo_categoria['codigocategoria'],
            'codigointernoproducto' => $codigo_interno_producto
        ];

        $insertar = model('productoPedidoModel')->insert($producto_pedido);

        if ($insertar) {

            $fk_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
            $valor_total_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido)->find();
            $cantidad_productos = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido)->find();



            $actualizar_total_pedido = [
                'valor_total' => $valor_total_pedido[0]['valor_total'],
                'cantidad_de_productos' => $cantidad_productos[0]['cantidad_producto']
            ];
            $model = model('pedidoModel');
            $actualizar = $model->set($actualizar_total_pedido);
            $actualizar = $model->where('id', $numero_pedido);
            $actualizar = $model->update();

            //$productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido);
            $productos_del_pedido_para_facturar = model('productoPedidoModel')->productos_del_pedido_para_facturar($numero_pedido);

            $total_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();
            $cantidad_de_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido)->first();
            $productos_del_pedido = view('facturar_pedido/facturar_pedido_pedido_tbody', [
                "productos" => $productos_del_pedido_para_facturar
            ]);

            $returnData = array(
                "resultado" => 1,  // Se actulizo el registro 
                "productos" => $productos_del_pedido,
                "total_pedido_sin_punto" => $total_pedido['valor_total'],
                "total_pedido" =>  number_format($total_pedido['valor_total'], 0, ',', '.'),
                "cantidad_de_pruductos" => $cantidad_de_productos['cantidad_de_productos']
            );
            echo  json_encode($returnData);
        }
    }
    public function editar_con_pin()
    {
        $pin = $_POST['pin'];
        $id_tabla_producto_pedido = $_POST['id_tabla_producto_pedido'];

        $id_usuario = model('usuariosModel')->select('idusuario_sistema')->where('pinusuario_sistema', $pin)->first();

        $tiene_permiso = model('tipoPermisoModel')->select('idusuario_sistema')->where('idusuario_sistema', $id_usuario['idusuario_sistema'])->first();
        if (!empty($tiene_permiso['idusuario_sistema'])) {
            $producto = model('productoPedidoModel')->editar_producto_pedido($id_tabla_producto_pedido);

            $returnData = array(
                "resultado" => 1,
                "codigo_interno" => $producto[0]['codigointernoproducto'],
                "descripcion" => $producto[0]['nombreproducto'],
                "valor_unitario" => $producto[0]['valor_unitario'],
                "valor_unitario_formato" => "$" . number_format($producto[0]['valor_unitario'], 0, ',', '.'),
                "cantidad" => $producto[0]['cantidad_producto'],
                "total" => $producto[0]['valor_total'],
                "notas" => $producto[0]['nota_producto'],
                "id_tabla_producto_pedido" => $id_tabla_producto_pedido
            );
            echo  json_encode($returnData);
        } else {
            $returnData = array(
                "resultado" => 0,  // Se actulizo el registro 

            );
            echo  json_encode($returnData);
        }
    }

    public function eliminar_con_pin_pad()
    {
        $pin = strval($_REQUEST['pin']);

        //$pin = 8888;

        $id_tabla_producto = $_REQUEST['id_tabla_producto'];
        //$id_tabla_producto = 84285;

        //$id_usuario = model('usuariosModel')->select('idusuario_sistema')->where('pinusuario_sistema', $pin)->first();
        $id_usuario = model('usuariosModel')->usuario_pin($pin);
        //$id_usuario = 6;

        if (!empty($id_usuario)) {

            //$tiene_permiso = model('tipoPermisoModel')->puede_borrar_de_pedido_y_editar_despues_de_impreso_comanda($id_usuario['idusuario_sistema']);
            // $tiene_permiso = model('tipoPermisoModel')->puede_borrar_de_pedido_y_editar_despues_de_impreso_comanda($id_usuario['idusuario_sistema']);
            $tiene_permiso = model('usuariosModel')->select('idtipo')->where('idusuario_sistema', $id_usuario[0]['idusuario_sistema'])->first();



            if ($tiene_permiso['idtipo'] == 1) {
                $returnData = array(
                    "resultado" => 0,  // No tiene permiso para la eliminacion 
                );
                echo  json_encode($returnData);
            } else if ($tiene_permiso['idtipo'] == 0) {

                $item = model('productoPedidoModel')->where('id', $id_tabla_producto)->first();

                //dd($item);

                $producto = [
                    'codigointernoproducto' => $item['codigointernoproducto'],
                    'cantidad' => $item['cantidad_producto'],
                    'fecha_eliminacion' => date('Y-m-d'),
                    'hora_eliminacion' => date('H:i:s'),
                    'usuario_eliminacion' => $id_usuario,
                    'pedido' => $item['numero_de_pedido']
                ];




                $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_tabla_producto)->first();
                $borrar_producto_pedido = model('productoPedidoModel')->where('id', $id_tabla_producto);
                $borrar_producto_pedido->delete();

                if ($borrar_producto_pedido) {


                    $fk_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido['numero_de_pedido'])->first();
                    $valor_total_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->find();
                    $cantidad_productos = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->find();



                    $actualizar_total_pedido = [
                        'valor_total' => $valor_total_pedido[0]['valor_total'],
                        'cantidad_de_productos' => $cantidad_productos[0]['cantidad_producto']
                    ];
                    $model = model('pedidoModel');
                    $actualizar = $model->set($actualizar_total_pedido);
                    $actualizar = $model->where('id', $numero_pedido['numero_de_pedido']);
                    $actualizar = $model->update();

                    $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);
                    $total_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido['numero_de_pedido'])->first();
                    $cantidad_de_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido['numero_de_pedido'])->first();
                    $productos_del_pedido = view('productos_pedido/productos_pedido', [
                        "productos" => $productos_pedido,
                        "pedido" => $numero_pedido['numero_de_pedido']
                    ]);

                    $returnData = array(
                        "resultado" => 1,  // Se actulizo el registro 
                        "productos" => $productos_del_pedido,
                        "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),
                        "cantidad_de_pruductos" => $cantidad_de_productos['cantidad_de_productos']
                    );
                    echo  json_encode($returnData);
                }
            }
        } else if (empty($id_usuario)) {
            $returnData = array(
                "resultado" => 2,  // Pin no válido  
            );
            echo  json_encode($returnData);
        }
    }

    public function editar_con_pin_pad()
    {
        $pin = $_REQUEST['pin'];
        $id_tabla_producto = $_REQUEST['id_tabla_producto'];

        $pin_valido = model('usuariosModel')->where('pinusuario_sistema', $pin)->first();
        if (empty($pin_valido)) {
            $returnData = array(
                "resultado" => 0,  // Pin no válido  
            );
            echo  json_encode($returnData);
        } else if (!empty($pin_valido)) {
            $producto = model('productoPedidoModel')->editar_producto_pedido($id_tabla_producto);

            $returnData = array(
                "resultado" => 1,
                "codigo_interno" => $producto[0]['codigointernoproducto'],
                "descripcion" => $producto[0]['nombreproducto'],
                "valor_unitario" => $producto[0]['valor_unitario'],
                "valor_unitario_formato" => "$" . number_format($producto[0]['valor_unitario'], 0, ',', '.'),
                "cantidad" => $producto[0]['cantidad_producto'],
                "total" => $producto[0]['valor_total'],
                "notas" => $producto[0]['nota_producto'],
                "id_tabla_producto_pedido" => $id_tabla_producto
            );
            echo  json_encode($returnData);
        }
    }

    function actualizar_factura_venta()
    {
        $temp = 0;
        $facturas = model('productoModel')->actualizar_factura_venta();

        foreach ($facturas as $detalle) {
            $temp2 = $temp + 1;
            $data = [
                'numerofactura_venta' => 'PB-' . $temp2

            ];
            $temp = $temp2;
            $model = model('facturaVentaModel');
            $actualizar = $model->set($data);
            $actualizar = $model->where('id', $detalle['id']);
            $actualizar = $model->update();

            $producto_factura_venta = [
                'numerofactura_venta' => ''
            ];

            $modelo = model('facturaVentaModel');
            $actualizar = $modelo->set($data);
            $actualizar = $modelo->where('id', $detalle['id']);
            $actualizar = $modelo->update();
        }
    }

    function actualizacion_cantidades()
    {
        $cantidad = $this->request->getPost('cantidad');
        $id_usuario = $this->request->getPost('id_usuario');
        $id_tabla_producto = $this->request->getPost('id_tabla_producto');
        $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_tabla_producto)->first();
        $tipo_usuario = model('usuariosModel')->select('idtipo')->where('idusuario_sistema', $id_usuario)->first();


        if ($cantidad > $cantidad_producto['cantidad_producto']) {
            $valor_unitario = model('productoPedidoModel')->select('valor_unitario')->where('id', $id_tabla_producto)->first();

            $data = [
                'cantidad_producto' => $cantidad,
                'valor_total' => $valor_unitario['valor_unitario'] * $cantidad

            ];

            $model = model('productoPedidoModel');
            $actualizar = $model->set($data);
            $actualizar = $model->where('id', $id_tabla_producto);
            $actualizar = $model->update();

            $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_tabla_producto)->first();
            $valor_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();

            $data_pedido = [
                'valor_total' => $valor_pedido[0]['valor_total']

            ];

            $model = model('pedidoModel');
            $actualizar = $model->set($data_pedido);
            $actualizar = $model->where('id', $numero_pedido);
            $actualizar = $model->update();

            $data_mesa = [
                'valor_pedido' => $valor_pedido[0]['valor_total']

            ];

            $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();

            $model = model('mesasModel');
            $actualizar = $model->set($data_mesa);
            $actualizar = $model->where('id', $id_mesa['fk_mesa']);
            $actualizar = $model->update();

            $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);

            $returnData = array(
                "resultado" => 1,
                "productos" => view('productos_pedido/productos_pedido', [
                    "productos" => $productos_pedido,
                    "pedido" => $numero_pedido['numero_de_pedido']
                ]),
                "total_pedido" => "$" . number_format($valor_pedido[0]['valor_total'], 0, ",", ".")
            );
            echo  json_encode($returnData);
        }

        if ($cantidad < $cantidad_producto['cantidad_producto'] and $tipo_usuario['idtipo'] == 0) {
            $valor_unitario = model('productoPedidoModel')->select('valor_unitario')->where('id', $id_tabla_producto)->first();

            $data = [
                'cantidad_producto' => $cantidad,
                'valor_total' => $valor_unitario['valor_unitario'] * $cantidad

            ];

            $model = model('productoPedidoModel');
            $actualizar = $model->set($data);
            $actualizar = $model->where('id', $id_tabla_producto);
            $actualizar = $model->update();

            $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_tabla_producto)->first();
            $valor_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();

            $data_pedido = [
                'valor_total' => $valor_pedido[0]['valor_total']

            ];

            $model = model('pedidoModel');
            $actualizar = $model->set($data_pedido);
            $actualizar = $model->where('id', $numero_pedido);
            $actualizar = $model->update();

            $data_mesa = [
                'valor_pedido' => $valor_pedido[0]['valor_total']

            ];

            $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();

            $model = model('mesasModel');
            $actualizar = $model->set($data_mesa);
            $actualizar = $model->where('id', $id_mesa['fk_mesa']);
            $actualizar = $model->update();

            $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);

            $returnData = array(
                "resultado" => 1,
                "productos" => view('productos_pedido/productos_pedido', [
                    "productos" => $productos_pedido,
                    "pedido" => $numero_pedido['numero_de_pedido']
                ]),
                "total_pedido" => "$" . number_format($valor_pedido[0]['valor_total'], 0, ",", ".")
            );
            echo  json_encode($returnData);
        }

        if ($cantidad < $cantidad_producto['cantidad_producto'] and $tipo_usuario['idtipo'] == 1) {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        }
    }
}
