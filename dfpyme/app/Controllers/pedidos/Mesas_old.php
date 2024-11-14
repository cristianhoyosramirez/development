<?php

namespace App\Controllers\pedidos;

use App\Controllers\BaseController;

use App\Libraries\Impuestos;

class Mesas extends BaseController
{
    public function index()
    {
        $categorias = model('categoriasModel')->where('permitir_categoria', 'true')->orderBy('nombrecategoria', 'asc')->findAll();
        $salones = model('salonesModel')->findAll();
        $mesas = model('mesasModel')->orderBy('id', 'ASC')->findAll();
        $estado = model('estadoModel')->orderBy('idestado', 'ASC')->findAll();
        $cliente_dian = model('clientesModel')->where('nitcliente', '222222222222')->first();
        $bancos = model('BancoModel')->findAll();
        $requiere_mesero = model('configuracionPedidoModel')->select('mesero_pedido')->first();
        $meseros = model('usuariosModel')->where('idtipo', 2)->orderBy('nombresusuario_sistema', 'asc')->find();
        $meseros = model('usuariosModel')->where('estadousuario_sistema', true)->orderBy('nombresusuario_sistema', 'asc')->find();


        return view('pedidos/mesas', [
            'categorias' => $categorias,
            'salones' => $salones,
            'mesas' => $mesas,
            'estado' => $estado,
            'nit_cliente' => $cliente_dian['nitcliente'],
            'nombre_cliente' => '222222222222 CONSUMIDOR FINAL',
            'bancos' => $bancos,
            'requiere_mesero' => $requiere_mesero['mesero_pedido'],
            'meseros' => $meseros
        ]);
    }


    public function productos_categoria()
    {

        $subcategorias = model('configuracionPedidoModel')->select('sub_categoria')->first();
        $id_categoria = $_POST['id_categoria'];
        //  $id_categoria = 2;

        $tipo_pedido = $_POST['tipo_pedido'];
        //$tipo_pedido = "movil";
        $categorias = model('categoriasModel')->where('permitir_categoria', 'true')->orderBy('nombrecategoria', 'asc')->findAll();


        /*  if ($subcategorias['sub_categoria'] == 'f') {
            //$id_categoria = 2;
            $productos = model('productoModel')->tipoInventario($id_categoria);

            if ($tipo_pedido == "movil") {
                $items = view('pedido/productos', [
                    'productos' => $productos,
                ]);
            }
            if ($tipo_pedido == "computador") {
                $items = view('pedidos/productos_categoria', [
                    'productos' => $productos,
                ]);
            }

            $returnData = array(
                "resultado" => 1,
                "productos" => $items,
                "lista_categoria" => view('pedidos/lista_categoria', [
                    'categorias' => $categorias,
                    'id_categoria' => $id_categoria
                ]),
            );
            echo  json_encode($returnData);
        } */
        // if ($subcategorias['sub_categoria'] == 't') {

        $id_subcategorias = model('productoCategoriaModel')->id_categorias($id_categoria);



        if (!empty($id_subcategorias)) {

            if ($tipo_pedido == "computador") {
                $items = view('pedidos/productos_subcategoria', [
                    'id_sub_categoria' => $id_subcategorias

                ]);
                $returnData = array(
                    "resultado" => 1,
                    "productos" => $items,
                    /*  "lista_categoria" => view('pedidos/lista_categoria', [
                        //'categorias' => $categorias,
                        'id_categoria' => $id_categoria
                    ]), */
                );
                echo  json_encode($returnData);
            }

            if ($tipo_pedido == "movil") {

                $items = view('pedidos/productos_subcategoria_movil', [
                    'id_sub_categoria' => $id_subcategorias

                ]);
                $returnData = array(
                    "resultado" => 1,
                    "productos" => $items,
                    "lista_categoria" => view('pedidos/lista_categoria', [
                        'categorias' => $categorias,
                        'id_categoria' => $id_categoria
                    ]),
                );
                echo  json_encode($returnData);
            }
        }
        if (empty($id_subcategorias)) {

            $productos = model('productoModel')->tipoInventario($id_categoria);


            if ($tipo_pedido == "movil") {

                $items = view('pedido/productos', [
                    'productos' => $productos,
                ]);


                $returnData = array(
                    "resultado" => 1,
                    "productos" => $items,
                    "lista_categoria" => view('pedidos/lista_categoria', [
                        'categorias' => $categorias,
                        'id_categoria' => $id_categoria
                    ]),
                );
                echo  json_encode($returnData);
            }
            if ($tipo_pedido == "computador") {

                $items = view('pedidos/productos_categoria', [
                    'productos' => $productos,
                ]);

                $returnData = array(
                    "resultado" => 1,
                    "productos" => $items,
                    "lista_categoria" => view('pedidos/lista_categoria', [
                        'categorias' => $categorias,
                        'id_categoria' => $id_categoria
                    ]),
                );
                echo  json_encode($returnData);
            }
        }
        // }
    }


    function agregar_producto()
    {
        /**
         * Datos recibidos por ajax desde la vista de mesas 
         */
        //$id_mesa = 16;
        $id_mesa = $this->request->getPost('id_mesa');
        $id_mesero = $this->request->getPost('mesero');

        //$id_usuario = "";

        if (!empty($id_mesero)) {
            $id_usuario = $this->request->getPost('mesero');
        }

        if (empty($id_mesero)) {
            $id_usuario = $this->request->getPost('id_usuario');
        }



        //$id_usuario = 15;
        //$id_producto = 2;
        //$id_producto = '207';
        // $id_producto = $this->request->getPost('id_producto');
        $id_producto = (string) $this->request->getPost('id_producto');

        /**
         * Datos del producto
         */

        $se_imprime_en_comanda = model('productoModel')->select('se_imprime')->where('codigointernoproducto', $id_producto)->first();

        $codigo_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $id_producto)->first();
        $codigo_interno_producto = model('productoModel')->select('codigointernoproducto')->where('codigointernoproducto', $id_producto)->first();
        $valor_unitario = model('productoModel')->select('valorventaproducto')->where('codigointernoproducto', $id_producto)->first();

        $tiene_pedido = model('pedidoModel')->pedido_mesa($id_mesa);
        $numero_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();


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
                'numero_productos_impresos_en_comanda' => 0
            ];
            $insertar = model('productoPedidoModel')->insertar(
                $ultimo_id_pedido,
                $valor_unitario['valorventaproducto'],
                $se_imprime_en_comanda['se_imprime'],
                $codigo_categoria['codigocategoria'],
                $codigo_interno_producto['codigointernoproducto']
            );


            $productos_pedido = model('productoPedidoModel')->producto_pedido($ultimo_id_pedido);
            $total_pedido = model('pedidoModel')->select('valor_total')->where('id', $ultimo_id_pedido)->first();
            $cantidad_de_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $ultimo_id_pedido)->first();


            $temp_producto_pedido = [
                'numero_de_pedido' => $ultimo_id_pedido,
                'cantidad_producto' => 1,
                'nota_producto' => '',
                'valor_unitario' => $valor_unitario['valorventaproducto'],
                'valor_total' => $valor_unitario['valorventaproducto'],
                'se_imprime_en_comanda' => $se_imprime_en_comanda['se_imprime'],
                'codigo_categoria' => $codigo_categoria['codigocategoria'],
                'codigointernoproducto' => $codigo_interno_producto['codigointernoproducto'],
            ];


            $insertar = model('tempProductoPedidoModel')->insert($temp_producto_pedido);




            $returnData = array(
                "resultado" => 1,
                "id_mesa" => $id_mesa,
                "numero_pedido" => $ultimo_id_pedido,
                "productos_pedido" => view('pedidos/productos_pedido', [
                    "productos" => $productos_pedido,
                ]),
                "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),
                "cantidad_de_pruductos" => $cantidad_de_productos['cantidad_de_productos']

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


                    $temp_producto_pedido = [
                        'numero_de_pedido' =>  $numero_pedido['id'],
                        'cantidad_producto' => 1,
                        'nota_producto' => '',
                        'valor_unitario' => $valor_unitario['valorventaproducto'],
                        'valor_total' => $valor_unitario['valorventaproducto'],
                        'se_imprime_en_comanda' => $se_imprime_en_comanda['se_imprime'],
                        'codigo_categoria' => $codigo_categoria['codigocategoria'],
                        'codigointernoproducto' => $codigo_interno_producto['codigointernoproducto'],
                    ];
        
        
                    $insertar = model('tempProductoPedidoModel')->insert($temp_producto_pedido);


                    $returnData = array(
                        "resultado" => 1,
                        "id_mesa" => $id_mesa,
                        "numero_pedido" => $numero_pedido['id'],
                        "productos_pedido" => $productos_del_pedido,
                        "total_pedido" =>  "$" . number_format($total['valor_total'], 0, ',', '.'),
                        "cantidad_de_pruductos" => $cantidad_de_productos['cantidad_de_productos']

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


                    $temp_producto_pedido = [
                        'numero_de_pedido' =>  $numero_pedido['id'],
                        'cantidad_producto' => 1,
                        'nota_producto' => '',
                        'valor_unitario' => $valor_unitario['valorventaproducto'],
                        'valor_total' => $valor_unitario['valorventaproducto'],
                        'se_imprime_en_comanda' => $se_imprime_en_comanda['se_imprime'],
                        'codigo_categoria' => $codigo_categoria['codigocategoria'],
                        'codigointernoproducto' => $codigo_interno_producto['codigointernoproducto'],
                    ];
        
        
                    $insertar = model('tempProductoPedidoModel')->insert($temp_producto_pedido);


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
                        "valor_total" => $total['valor_total']
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
                    'numero_productos_impresos_en_comanda' => 0
                ];
                $insertar = model('productoPedidoModel')->insert($producto_pedido);

                $temp_producto_pedido = [
                    'numero_de_pedido' =>  $numero_pedido['id'],
                    'cantidad_producto' => 1,
                    'nota_producto' => '',
                    'valor_unitario' => $valor_unitario['valorventaproducto'],
                    'valor_total' => $valor_unitario['valorventaproducto'],
                    'se_imprime_en_comanda' => $se_imprime_en_comanda['se_imprime'],
                    'codigo_categoria' => $codigo_categoria['codigocategoria'],
                    'codigointernoproducto' => $codigo_interno_producto['codigointernoproducto'],
                ];
    
    
                $insertar = model('tempProductoPedidoModel')->insert($temp_producto_pedido);


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


                $returnData = array(
                    "resultado" => 1,
                    "id_mesa" => $id_mesa,
                    "numero_pedido" => $numero_pedido['id'],
                    "productos_pedido" => view('pedidos/productos_pedido', [
                        "productos" => $productos_pedido,
                    ]),
                    "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),
                    //"cantidad_de_pruductos" => $cantidad_de_productos['cantidad_de_productos']

                );
                echo  json_encode($returnData);
            }
        }
    }

    function pedido()
    {
        //$id_mesa = 424;
        $id_mesa = $this->request->getPost('id_mesa');

        $numero_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa)->first();
        $total_pedido = model('pedidoModel')->select('valor_total')->where('fk_mesa', $id_mesa)->first();
        $nota_pedido = model('pedidoModel')->select('nota_pedido')->where('fk_mesa', $id_mesa)->first();
        $propina = model('pedidoModel')->select('propina')->where('fk_mesa', $id_mesa)->first();
        $id_mesero = model('pedidoModel')->select('fk_usuario')->where('id', $id_mesa)->first();

        if (!empty($id_mesero['id_mesero'])) {
            $nombre_mesero = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_mesero['id_mesero'])->first();
        }
        if (empty($id_mesero['id_mesero'])) {

            $id_meser = model('pedidoModel')->select('fk_usuario')->where('fk_mesa', $id_mesa)->first();
            $nombre_mesero = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_meser['fk_usuario'])->first();
        }

        $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['id']);
        $returnData = array(
            "resultado" => 1,
            "id_mesa" => $id_mesa,
            "numero_pedido" => $numero_pedido['id'],
            "productos_pedido" => view('pedidos/productos_pedido', [
                "productos" => $productos_pedido,
            ]),
            "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),
            "propina" =>   number_format($propina['propina'], 0, ',', '.'),
            //"cantidad_de_pruductos" => $cantidad_de_productos['cantidad_de_productos']
            "nota_pedido" => $nota_pedido['nota_pedido'],
            "total_propina" => number_format($propina['propina'] + $total_pedido['valor_total'], 0, ',', '.'),
            "nombre_mesero" => $nombre_mesero['nombresusuario_sistema']

        );
        echo  json_encode($returnData);
    }

    function nota()
    {
        $id_mesa = $this->request->getPost('id_mesa');
        $nota = $this->request->getPost('data');

        $nota = [
            'nota_pedido' => $nota
        ];

        $model = model('pedidoModel');
        $actualizar = $model->set($nota);
        $actualizar = $model->where('fk_mesa', $id_mesa);
        $actualizar = $model->update();
    }

    function get_mesas_tiempo_real()
    {
        $mesas = model('mesasModel')->orderBy('id', 'ASC')->findAll();

        $returnData = array(
            "resultado" => 1,

            "mesas" => view('pedidos/todas_las_mesas_lista', [
                "mesas" => $mesas,
            ]),
        );
        echo  json_encode($returnData);
    }


    function mesas_salon()
    {
        $id_salon = $this->request->getPost('id_salon');
        $categorias = model('categoriasModel')->where('permitir_categoria', 'true')->orderBy('nombrecategoria', 'asc')->findAll();

        $mesas = model('mesasModel')->where('fk_salon', $id_salon)->orderBy('id', 'ASC')->findAll();
        $returnData = array(
            "resultado" => 1,

            "mesas" => view('pedidos/todas_las_mesas_lista', [
                "mesas" => $mesas,
            ]),
            "categorias" => view('pedidos/categorias', [
                'categorias' => $categorias
            ])
        );
        echo  json_encode($returnData);
    }

    function get_mesas()
    {
        $mesas = model('mesasModel')->orderBy('id', 'ASC')->findAll();
        $returnData = array(
            "resultado" => 1,

            "mesas" => view('pedidos/todas_las_mesas_lista', [
                "mesas" => $mesas,
            ]),
        );
        echo  json_encode($returnData);
    }

    function agregar_nota()
    {
        $nota = $this->request->getPost('nota');
        $id_producto = $this->request->getPost('id_producto');

        $nota = [
            'nota_producto' => $nota,

        ];

        $model = model('productoPedidoModel');
        $actualizar = $model->set($nota);
        $actualizar = $model->where('id', $id_producto);
        $actualizar = $model->update();

        if ($actualizar) {
            $pedido = $model->select('numero_de_pedido')->where('id', $id_producto)->first();

            $productos_pedido = model('productoPedidoModel')->producto_pedido($pedido['numero_de_pedido']);

            $returnData = array(
                "resultado" => 1,
                "productos_pedido" => view('pedidos/productos_pedido', [
                    "productos" => $productos_pedido,
                ]),
            );
            echo  json_encode($returnData);
        }
    }

    function consultar_nota()
    {
        $id_producto = $this->request->getPost('id_producto');
        $nota = model('productoPedidoModel')->select('nota_producto')->where('id', $id_producto)->first();
        $producto = model('productoPedidoModel')->set_producto_pedido($id_producto);



        $returnData = array(
            "resultado" => 1,
            "nota" => $nota['nota_producto'],
            "producto" => view('consultas/producto', [
                'producto' => $producto
            ]),
            'valor_total' => number_format($producto[0]['valor_unitario'], 0, ',', '.')
        );
        echo  json_encode($returnData);
    }

    function eliminar_producto()
    {


        $id_tabla_producto = $_POST['id_tabla_producto'];
        $id_usuario = $_POST['id_usuario'];


        $tipo_usuario = model('usuariosModel')->select('idtipo')->where('idusuario_sistema', $id_usuario)->first();
        $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_tabla_producto)->first();


        $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_tabla_producto)->first();
        $codigo_interno_producto = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_tabla_producto)->first();
        $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigointernoproducto'])->first();
        $cantidad_impresos = model('productoPedidoModel')->select('numero_productos_impresos_en_comanda')->where('id', $id_tabla_producto)->first();
        $item = model('productoPedidoModel')->where('id', $id_tabla_producto)->first();


        if ($cantidad_impresos['numero_productos_impresos_en_comanda'] < $cantidad_producto['cantidad_producto']) {

            $cantidad_eliminar = $cantidad_producto['cantidad_producto'] - $cantidad_impresos['numero_productos_impresos_en_comanda'];
            $nueva_cantidad = $cantidad_producto['cantidad_producto'] - $cantidad_eliminar;


            if ($nueva_cantidad == 0) {
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

                    $producto = [
                        'codigointernoproducto' => $item['codigointernoproducto'],
                        'cantidad' => $cantidad_eliminar,
                        'fecha_eliminacion' => date('Y-m-d'),
                        'hora_eliminacion' => date('H:i:s'),
                        'usuario_eliminacion' => $id_usuario,
                        'pedido' => $item['numero_de_pedido']
                    ];

                    $insert = model('productosBorradosModel')->insert($producto);


                    $productos_del_pedido = view('pedidos/productos_pedido', [
                        "productos" => $productos_pedido,
                        "pedido" => $numero_pedido['numero_de_pedido']
                    ]);

                    $returnData = array(
                        "resultado" => 1,  // Se actulizo el registro 
                        "productos" => $productos_del_pedido,
                        "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),
                        "cantidad_de_pruductos" => $cantidad_de_productos['cantidad_de_productos'],
                        "mensaje" => "Se han eliminado " . $cantidad_eliminar . " " . $nombre_producto['nombreproducto']
                    );
                    echo  json_encode($returnData);
                }
            } else if ($nueva_cantidad > 0) {

                $producto = [
                    'codigointernoproducto' => $item['codigointernoproducto'],
                    'cantidad' => $cantidad_eliminar,
                    'fecha_eliminacion' => date('Y-m-d'),
                    'hora_eliminacion' => date('H:i:s'),
                    'usuario_eliminacion' => $id_usuario,
                    'pedido' => $item['numero_de_pedido']
                ];

                $insert = model('productosBorradosModel')->insert($producto);


                $valor_unitario = model('productoPedidoModel')->select('valor_unitario')->where('id', $id_tabla_producto)->first();

                $producto_pedido = [
                    'valor_total' => $valor_unitario['valor_unitario'] * $cantidad_producto['cantidad_producto'],
                    'cantidad_producto' => $nueva_cantidad
                ];
                $model = model('productoPedidoModel');
                $actualizar = $model->set($producto_pedido);
                $actualizar = $model->where('id', $id_tabla_producto);
                $actualizar = $model->update();


                $total_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->first();

                $model = model('pedidoModel');
                $actualizar = $model->set('valor_total', $total_pedido['valor_total']);
                $actualizar = $model->where('id', $numero_pedido['numero_de_pedido']);
                $actualizar = $model->update();


                $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);
                $total_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido['numero_de_pedido'])->first();
                $cantidad_de_productos = model('pedidoModel')->select('cantidad_de_productos')->where('id', $numero_pedido['numero_de_pedido'])->first();
                $productos_del_pedido = view('pedidos/productos_pedido', [
                    "productos" => $productos_pedido,
                    "pedido" => $numero_pedido['numero_de_pedido']
                ]);

                $returnData = array(
                    "resultado" => 1,  // Se actulizo el registro 
                    "productos" => $productos_del_pedido,
                    "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),
                    "cantidad_de_pruductos" => $cantidad_de_productos['cantidad_de_productos'],
                    "mensaje" => "Se han eliminado " . $cantidad_eliminar . " " . $nombre_producto['nombreproducto']
                );
                echo  json_encode($returnData);
            }
        }


        if ($cantidad_impresos['numero_productos_impresos_en_comanda'] == $cantidad_producto['cantidad_producto']) {

            if ($tipo_usuario['idtipo'] == 0) {
                if ($tipo_usuario['idtipo'] == 0 or $tipo_usuario['idtipo'] == 1) {
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
                        $productos_del_pedido = view('pedidos/productos_pedido', [
                            "productos" => $productos_pedido,
                            "pedido" => $numero_pedido['numero_de_pedido']
                        ]);

                        $returnData = array(
                            "resultado" => 1,  // Se actulizo el registro 
                            "productos" => $productos_del_pedido,
                            "total_pedido" =>  "$" . number_format($total_pedido['valor_total'], 0, ',', '.'),
                            "cantidad_de_pruductos" => $cantidad_de_productos['cantidad_de_productos'],
                            "mensaje" => "Eliminación correcta"
                        );
                        echo  json_encode($returnData);
                    }
                }
            }


            if ($tipo_usuario['idtipo'] == 2) {

                $returnData = array(
                    "resultado" => 0,  // Se actulizo el registro 

                );
                echo  json_encode($returnData);
            }
        }
    }


    function actualizar_cantidades()
    {
        $id_tabla_producto = $this->request->getPost('id_tabla');

        $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_tabla_producto)->first();
        $valor_unitario_producto = model('productoPedidoModel')->select('valor_unitario')->where('id', $id_tabla_producto)->first();

        if ($cantidad_producto) {
            $data = [
                'cantidad_producto' => $cantidad_producto['cantidad_producto'] + 1,
                'valor_total' => $valor_unitario_producto['valor_unitario'] * ($cantidad_producto['cantidad_producto'] + 1)

            ];

            $model = model('productoPedidoModel');
            $actualizar_cantidad = $model->set($data);
            $actualizar_cantidad = $model->where('id', $id_tabla_producto);
            $actualizar_cantidad = $model->update();





            if ($actualizar_cantidad) {
                $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_tabla_producto)->first();
                $codigo_interno_producto = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_tabla_producto)->first();
                $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigointernoproducto'])->first();
                $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_tabla_producto)->first();
                $valor_total = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();
                $cantidad_productos = model('productoPedidoModel')->selectSum('cantidad_producto')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();

                $data = [
                    'valor_total' => $valor_total[0]['valor_total'],
                    'cantidad_de_productos' => $cantidad_productos[0]['cantidad_producto']
                ];

                $model = model('pedidoModel');
                $actualizar_cantidad = $model->set($data);
                $actualizar_cantidad = $model->where('id', $numero_pedido['numero_de_pedido']);
                $actualizar_cantidad = $model->update();

                $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);

                $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido['numero_de_pedido'])->first();
                $valor_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido['numero_de_pedido'])->first();



                $returnData = array(
                    "resultado" => 1,
                    "cantidad" => $cantidad_producto['cantidad_producto'],
                    "id" => $id_tabla_producto,
                    "nombre_producto" => $nombre_producto['nombreproducto'],
                    "productos" => view('pedidos/productos_pedido', [
                        "productos" => $productos_pedido,
                        "pedido" => $numero_pedido['numero_de_pedido']
                    ]),
                    "cantidad_de_productos" => $cantidad_productos[0]['cantidad_producto'],
                    "total" => number_format($valor_pedido['valor_total'], 0, ",", ".")
                );
                echo  json_encode($returnData);
            }
        }
    }

    public function eliminacion_de_pedido()
    {

        $numer_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $this->request->getPost('id_mesa'))->first();
        $numero_pedido = $numer_pedido['id'];

        $id_usuario = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();



        $id_de_usuario = $this->request->getPost('id_usuario');
        $permiso_eliminar = model('usuariosModel')->select('idtipo')->where('idusuario_sistema', $id_de_usuario)->first();

        // if (!empty($permiso_eliminar['idusuario_sistema'])) {

        $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();


        $valor_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();

        $fecha_creacion = model('pedidoModel')->select('fecha_creacion')->where('id', $numero_pedido)->first();
        $items = model('productoPedidoModel')->productos_pedido($numero_pedido);

        if ($permiso_eliminar['idtipo'] == 1 || $permiso_eliminar['idtipo'] == 0) {
            $pedido_borrado = [
                'numero_pedido' => $numero_pedido,
                'valor_pedido' => $valor_pedido['valor_total'],
                'fecha_eliminacion' =>  date("Y-m-d"),
                'hora_eliminacion' => date('H:i:s'),
                'fecha_creacion' => $fecha_creacion['fecha_creacion'],
                'usuario_eliminacion' => $id_de_usuario,
                'id_mesero' => $id_usuario['fk_usuario']
            ];

            $insert = model('eliminacionPedidosModel')->insert($pedido_borrado);

            foreach ($items as $detalle) {
                $producto = [
                    'codigointernoproducto' => $detalle['codigointernoproducto'],
                    'cantidad' => $detalle['cantidad_producto'],
                    'fecha_eliminacion' => date('Y-m-d'),
                    'hora_eliminacion' => date('H:i:s'),
                    'usuario_eliminacion' => $id_de_usuario,
                    'pedido' => $numero_pedido,
                    'id_mesero' => $id_usuario['fk_usuario']
                ];

                $insert = model('productosBorradosModel')->insert($producto);
            }



            $model = model('productoPedidoModel');
            $borrarPedido = $model->where('numero_de_pedido', $numero_pedido);
            $borrarPedido = $model->delete();

            $model = model('pedidoModel');
            $borrar = $model->where('id', $numero_pedido);
            $borrar = $model->delete();
            $mesas = model('mesasModel')->orderBy('id', 'ASC')->findAll();

            if ($borrarPedido && $borrar) {

                $returnData = array(
                    "resultado" => 1,
                    "mesas" => view('pedidos/todas_las_mesas_lista', [
                        "mesas" => $mesas,
                    ]),
                );
                echo  json_encode($returnData);
            } else {
                $returnData = array(
                    "resultado" => 0,
                );
                echo  json_encode($returnData);
            }
            //} 
            /* else {
            echo 0;
        } */
        }
        if ($permiso_eliminar['idtipo'] == 2) {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        }
    }

    function restar_producto()
    {
        $id_tabla_producto = $this->request->getPost('id_tabla');
        $id_usuario = $this->request->getPost('id_usuario');

        //$impresion_comanda = model('productoPedidoModel')->select('impresion_en_comanda')->where('id', $id_tabla_producto)->first();
        $tipo_usuario = model('usuariosModel')->select('idtipo')->where('idusuario_sistema', $id_usuario)->first();
        $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_tabla_producto)->first();
        $cantidades_impresas = model('productoPedidoModel')->select('numero_productos_impresos_en_comanda')->where('id', $id_tabla_producto)->first();

        if ($cantidad_producto['cantidad_producto'] > $cantidades_impresas['numero_productos_impresos_en_comanda']) {

            if ($cantidad_producto['cantidad_producto'] - 1 > 0) {
                $data = [
                    'cantidad_producto' => $cantidad_producto['cantidad_producto'] - 1,

                ];

                $model = model('productoPedidoModel');
                $actualizar_cantidad = $model->set($data);
                $actualizar_cantidad = $model->where('id', $id_tabla_producto);
                $actualizar_cantidad = $model->update();
                if ($actualizar_cantidad) {

                    $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_tabla_producto)->first();
                    $codigo_interno_producto = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_tabla_producto)->first();
                    $valor_unitario_producto = model('productoPedidoModel')->select('valor_unitario')->where('id', $id_tabla_producto)->first();
                    $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigointernoproducto'])->first();
                    $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_tabla_producto)->first();

                    $valor_total_producto = [
                        'valor_total' => $cantidad_producto['cantidad_producto'] * $valor_unitario_producto['valor_unitario']
                    ];

                    $model = model('productoPedidoModel');
                    $actualizar_cantidad = $model->set($valor_total_producto);
                    $actualizar_cantidad = $model->where('id', $id_tabla_producto);
                    $actualizar_cantidad = $model->update();

                    $producto_borrado = [
                        'codigointernoproducto' => $codigo_interno_producto['codigointernoproducto'],
                        'cantidad' => 1,
                        'fecha_eliminacion' => date('Y-m-d'),
                        'hora_eliminacion' => date('H:i:s'),
                        'pedido' => $numero_pedido['numero_de_pedido']

                    ];

                    $insert = model('borrar_productosModel')->insert($producto_borrado);

                    $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);

                    $valor_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();

                    $valor_total_pedido = [
                        'valor_total' => $valor_pedido[0]['valor_total'],

                    ];

                    $model = model('pedidoModel');
                    $actualizar_cantidad = $model->set($valor_total_pedido);
                    $actualizar_cantidad = $model->where('id', $numero_pedido['numero_de_pedido']);
                    $actualizar_cantidad = $model->update();



                    $returnData = array(
                        "resultado" => 1,
                        "cantidad" => $cantidad_producto['cantidad_producto'],
                        "id" => $id_tabla_producto,
                        "nombre_producto" => $nombre_producto['nombreproducto'],
                        "productos" => view('pedidos/productos_pedido', [
                            "productos" => $productos_pedido,
                            "pedido" => $numero_pedido['numero_de_pedido'],
                        ]),
                        "total" => number_format($valor_pedido[0]['valor_total'], 0, ",", ".")
                    );
                    echo  json_encode($returnData);
                }
            }
        }

        if (($cantidad_producto['cantidad_producto'] == $cantidades_impresas['numero_productos_impresos_en_comanda']) and $tipo_usuario['idtipo'] == 1) {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        }

        if ($cantidad_producto['cantidad_producto'] < $cantidades_impresas['numero_productos_impresos_en_comanda'] and $tipo_usuario['idtipo'] == 0) {
            if ($cantidad_producto['cantidad_producto'] - 1 > 0) {
                $data = [
                    'cantidad_producto' => $cantidad_producto['cantidad_producto'] - 1,

                ];

                $model = model('productoPedidoModel');
                $actualizar_cantidad = $model->set($data);
                $actualizar_cantidad = $model->where('id', $id_tabla_producto);
                $actualizar_cantidad = $model->update();
                if ($actualizar_cantidad) {

                    $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_tabla_producto)->first();
                    $codigo_interno_producto = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_tabla_producto)->first();
                    $valor_unitario_producto = model('productoPedidoModel')->select('valor_unitario')->where('id', $id_tabla_producto)->first();
                    $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigointernoproducto'])->first();
                    $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_tabla_producto)->first();

                    $valor_total_producto = [
                        'valor_total' => $cantidad_producto['cantidad_producto'] * $valor_unitario_producto['valor_unitario']
                    ];

                    $model = model('productoPedidoModel');
                    $actualizar_cantidad = $model->set($valor_total_producto);
                    $actualizar_cantidad = $model->where('id', $id_tabla_producto);
                    $actualizar_cantidad = $model->update();

                    $producto_borrado = [
                        'codigointernoproducto' => $codigo_interno_producto['codigointernoproducto'],
                        'cantidad' => 1,
                        'fecha_eliminacion' => date('Y-m-d'),
                        'hora_eliminacion' => date('H:i:s'),
                        'pedido' => $numero_pedido['numero_de_pedido']

                    ];

                    $insert = model('borrar_productosModel')->insert($producto_borrado);

                    $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);

                    $valor_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();

                    $valor_total_pedido = [
                        'valor_total' => $valor_pedido[0]['valor_total'],

                    ];

                    $model = model('pedidoModel');
                    $actualizar_cantidad = $model->set($valor_total_pedido);
                    $actualizar_cantidad = $model->where('id', $numero_pedido['numero_de_pedido']);
                    $actualizar_cantidad = $model->update();




                    $returnData = array(
                        "resultado" => 1,
                        "cantidad" => $cantidad_producto['cantidad_producto'],
                        "id" => $id_tabla_producto,
                        "nombre_producto" => $nombre_producto['nombreproducto'],
                        "productos" => view('pedidos/productos_pedido', [
                            "productos" => $productos_pedido,
                            "pedido" => $numero_pedido['numero_de_pedido'],
                        ]),
                        "total" => number_format($valor_pedido[0]['valor_total'], 0, ",", ".")
                    );
                    echo  json_encode($returnData);
                }
            }
        }


        if ($cantidad_producto['cantidad_producto'] == $cantidades_impresas['numero_productos_impresos_en_comanda'] and $tipo_usuario['idtipo'] == 0) {
            if ($cantidad_producto['cantidad_producto'] - 1 > 0) {
                $data = [
                    'cantidad_producto' => $cantidad_producto['cantidad_producto'] - 1,

                ];

                $model = model('productoPedidoModel');
                $actualizar_cantidad = $model->set($data);
                $actualizar_cantidad = $model->where('id', $id_tabla_producto);
                $actualizar_cantidad = $model->update();
                if ($actualizar_cantidad) {

                    $cantidad_producto = model('productoPedidoModel')->select('cantidad_producto')->where('id', $id_tabla_producto)->first();
                    $codigo_interno_producto = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_tabla_producto)->first();
                    $valor_unitario_producto = model('productoPedidoModel')->select('valor_unitario')->where('id', $id_tabla_producto)->first();
                    $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_interno_producto['codigointernoproducto'])->first();
                    $numero_pedido = model('productoPedidoModel')->select('numero_de_pedido')->where('id', $id_tabla_producto)->first();

                    $valor_total_producto = [
                        'valor_total' => $cantidad_producto['cantidad_producto'] * $valor_unitario_producto['valor_unitario']
                    ];

                    $model = model('productoPedidoModel');
                    $actualizar_cantidad = $model->set($valor_total_producto);
                    $actualizar_cantidad = $model->where('id', $id_tabla_producto);
                    $actualizar_cantidad = $model->update();

                    $producto_borrado = [
                        'codigointernoproducto' => $codigo_interno_producto['codigointernoproducto'],
                        'cantidad' => 1,
                        'fecha_eliminacion' => date('Y-m-d'),
                        'hora_eliminacion' => date('H:i:s'),
                        'pedido' => $numero_pedido['numero_de_pedido']

                    ];

                    $insert = model('borrar_productosModel')->insert($producto_borrado);

                    $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['numero_de_pedido']);

                    $valor_pedido = model('productoPedidoModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['numero_de_pedido'])->findAll();

                    $valor_total_pedido = [
                        'valor_total' => $valor_pedido[0]['valor_total'],

                    ];

                    $model = model('pedidoModel');
                    $actualizar_cantidad = $model->set($valor_total_pedido);
                    $actualizar_cantidad = $model->where('id', $numero_pedido['numero_de_pedido']);
                    $actualizar_cantidad = $model->update();

                    $mesas = [
                        'valor_pedido' => $valor_pedido[0]['valor_total']
                    ];

                    $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido['numero_de_pedido'])->first();




                    $returnData = array(
                        "resultado" => 1,
                        "cantidad" => $cantidad_producto['cantidad_producto'],
                        "id" => $id_tabla_producto,
                        "nombre_producto" => $nombre_producto['nombreproducto'],
                        "productos" => view('pedidos/productos_pedido', [
                            "productos" => $productos_pedido,
                            "pedido" => $numero_pedido['numero_de_pedido'],
                        ]),
                        "total" => number_format($valor_pedido[0]['valor_total'], 0, ",", ".")
                    );
                    echo  json_encode($returnData);
                }
            }
        }

        if ($cantidad_producto['cantidad_producto'] < $cantidades_impresas['numero_productos_impresos_en_comanda'] and $tipo_usuario['idtipo'] == 1) {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        }
    }


    function productos_pedido()
    {

        $model = model('partirFacturaModel')->truncate();

        $apertura_registro = model('aperturaRegistroModel')->first();

        if (!empty($apertura_registro)) {

            $numero_pedido = model('pedidoModel')->select('id')->where('fk_mesa', $this->request->getPost('id_mesa'))->first();



            $productos_pedido = model('productoPedidoModel')->producto_pedido($numero_pedido['id']);

            foreach ($productos_pedido as $detalle) {
                $data = [
                    'numero_de_pedido' => $numero_pedido['id'],
                    'cantidad_producto' => 0,
                    'valor_unitario' => $detalle['valor_unitario'],
                    'valor_total' => $detalle['valor_total'],
                    'codigointernoproducto' => $detalle['codigointernoproducto'],
                    'nombreproducto' => $detalle['nombreproducto'],
                    'id_tabla_producto' => $detalle['id']
                ];
                $insert = model('partirFacturaModel')->insert($data);
            }

            $producto_partir_factura = model('partirFacturaModel')->productos($numero_pedido['id']);
            //$total = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['id'])->findAll();
            //$total = model('partirFacturaModel')->selectSum('valor_total')->where('numero_de_pedido', $numero_pedido['id'])->findAll();

            $returnData = array(
                "resultado" => 1,
                "productos" => view('pedidos/productos_pedido_parcial', [
                    "productos" => $producto_partir_factura,
                ]),
                "total" => "Total $ 0"
            );
            echo  json_encode($returnData);
        } else if (empty($apertura_registro)) {
            $returnData = array(
                "resultado" => 0,

            );
            echo  json_encode($returnData);
        }
    }


    function reporte_propinas()
    {

        //$id_apertura = 36;
        $id_apertura = $_REQUEST['id_apertura'];

        $meseros  = model('facturaPropinaModel')->get_meseros($id_apertura);



        $total_propinas = model('FacturaPropinaModel')->selectSum('valor_propina')->where('id_apertura', $id_apertura)->findAll();

        $returnData = array(
            "resultado" => 1,
            "propinas" => view('pedidos/propinas', [
                "meseros" => $meseros,
                "id_apertura" => $id_apertura,
                "total_propinas" => $total_propinas[0]['valor_propina']
            ]),
            "total_propinas" => "Total: $ " . number_format($total_propinas[0]['valor_propina'], 0, ",", ".")

        );
        echo  json_encode($returnData);
    }

    function todas_las_mesas()
    {

        $mesas = model('mesasModel')->orderBy('id', 'asc')->findAll();

        $returnData = array(
            "resultado" => 1,
            "mesas" => view('pedidos/lista_mesas', [
                "mesas" => $mesas
            ])

        );
        echo  json_encode($returnData);
    }

    function buscar_mesas()
    {


        $mesas = model('mesasModel')->buscar_mesa($this->request->getPost('valor'));

        $returnData = array(
            "resultado" => 1,
            "mesas" => view('pedidos/lista_mesas', [
                "mesas" => $mesas
            ])

        );
        echo  json_encode($returnData);
    }

    function buscar_mesero()
    {




        $meseros = model('mesasModel')->buscar_meseros($this->request->getPost('valor'));

        if (!empty($this->request->getPost('valor'))) {
            $returnData = array(
                "resultado" => 1,
                "meseros" => view('pedidos/lista_meseros', [
                    "meseros" => $meseros
                ])

            );
            echo  json_encode($returnData);
        }


        if (empty($this->request->getPost('valor'))) {

            $mesas = model('mesasModel')->buscar_mesa($this->request->getPost('valor'));
            $returnData = array(
                "resultado" => 1,
                "meseros" => view('pedidos/lista_mesas', [
                    "mesas" => $mesas
                ])

            );
            echo  json_encode($returnData);
        }
    }


    function crear_mesero()
    {
        $nombre = $this->request->getPost('nombre');
        //$nombre = 'Chucho';
        $id_mesa = $this->request->getPost('id_mesa');
        //$id_mesa = 54;

        $nombre_mesero = model('usuariosModel')->where('nombresusuario_sistema', $nombre)->first();

        $data = [
            'idtipo' => 2,
            'cedulausuario_sistema' => 123456,
            'nombresusuario_sistema' => $nombre,
            'usuariousuario_sistema' => $nombre,
            'contraseniausuario_sistema' => $nombre,
            'estadousuario_sistema' => true,
            'telefonousuario_sistema' => "",
            'direccion_sistema' => "",
            'pinusuario_sistema' => "",
        ];

        if (empty($nombre_mesero['nombresusuario_sistema'])) {
            $insert = model('usuariosModel')->insert($data);

            // Obtener el último ID insertado
            $ultimo_id = model('usuariosModel')->insertID();
            $meseros = model('usuariosModel')->where('idtipo', 2)->orderBy('nombresusuario_sistema', 'asc')->find();
            $meseros = model('usuariosModel')->where('estadousuario_sistema', true)->orderBy('nombresusuario_sistema', 'asc')->find();


            $returnData = array(
                "resultado" => 1,
                "nombre" => $nombre,
                "meseros" => view('mesa/meseros', [
                    'meseros' => $meseros
                ])

            );
            echo  json_encode($returnData);
        }

        if (!empty($nombre_mesero['nombresusuario_sistema'])) {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        }
    }
}
