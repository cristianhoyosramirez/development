<?php

namespace App\Controllers\producto;

use App\Controllers\BaseController;

class operacionesProductoController extends BaseController
{

    public function index()
    {

        $productos = model('productoModel')->productos();
        return view('producto/listado');
    }



    public function crear()
    {

        return view('producto/crear', [
            'id' => $this->request->getPost('id'),
            'nombre_producto' => $this->request->getPost('nombre_producto')
        ]);
    }

    public function imagen()
    {
        $id_producto = $_REQUEST['id_producto_imagen'];
        $img = $this->request->getFile('producto_imagen');

        $ruta_imagen = base_url() . '/images/productos/'  . $id_producto . '.jpg';
        $nombre_imagen = $id_producto . '.jpg';

        $file_headers = @get_headers($ruta_imagen);
        if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {

            $img->move('images/productos', $id_producto . '.jpg');
            $productos = model('productoModel')->producto();
            //dd($productos);
            return view('producto/listado', [
                'productos' => $productos
            ]);
        } else {

            echo "encontrado" . "</br>";
        }
    }
    function lista_precios()
    {
        echo $id_producto = $_REQUEST['id_producto'];
    }

    function get_codigo_interno()
    {
        $codigo_interno_producto = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 2)->first();
        if ($codigo_interno_producto) {
            $returnData = array(
                "codigo_interno_producto" =>  $codigo_interno_producto['numeroconsecutivo'],
                "resultado" => 1
            );
            echo  json_encode($returnData);
        }
    }


    function categorias()
    {
        if (!isset($_POST['palabraClave'])) {
            $categorias = model('categoriasModel')->orderBy('codigocategoria', 'desc')->find();
        } else {
            //$buscar = $_POST['palabraClave'];
            $categorias = model('categoriasModel')->categorias($_POST['palabraClave']);
        }
        $response = [];
        foreach ($categorias as $detalle) {
            $response[] = [
                'id' => $detalle['codigocategoria'],
                'text' => $detalle['nombrecategoria'],
            ];
        }
        echo json_encode($response);
    }


    function marcas()
    {
        if (!isset($_POST['palabraClave'])) {
            $marcas = model('MarcasModel')->orderBy('idmarca', 'desc')->find();
        } else {
            //$buscar = $_POST['palabraClave'];
            $marcas = model('marcasModel')->marcas($_POST['palabraClave']);
        }
        $response = [];
        foreach ($marcas as $detalle) {
            $response[] = [
                'id' => $detalle['idmarca'],
                'text' => $detalle['nombremarca'],
            ];
        }
        echo json_encode($response);
    }
    function iva()
    {


        if (!isset($_POST['palabraClave'])) {
            $iva = model('ivaModel')->orderBy('idiva', 'desc')->find();
        } else {
            if (empty($_POST['palabraClave'])) {
                $iva = model('ivaModel')->orderBy('idiva', 'desc')->find();
            } else {
                $iva = model('ivaModel')->orderBy('idiva', 'desc')->find();
            }
        }
        $response = [];
        foreach ($iva as $detalle) {
            $response[] = [
                'id' => $detalle['idiva'],
                'text' => $detalle['valoriva'] . "%",
            ];
        }
        echo json_encode($response);
    }

    function ico()
    {
        $ico = model('icoConsumoModel')->select('*')->orderBy('id_ico', 'desc')->find();

        if (!isset($_POST['palabraClave'])) {
            if (empty($_POST['palabraClave'])) {
                $ico = model('icoConsumoModel')->select('*')->orderBy('id_ico', 'desc')->find();
            } else {
                $ico = model('icoConsumoModel')->select('*')->orderBy('id_ico', 'desc')->find();
            }
        } else {
            //$buscar = $_POST['palabraClave'];
            $ico = model('icoConsumoModel')->select('*')->orderBy('id_ico', 'desc')->find();
        }
        $response = [];
        foreach ($ico as $detalle) {
            $response[] = [
                'id' => $detalle['id_ico'],
                'text' => $detalle['valor_ico'] . "%",
            ];
        }
        echo json_encode($response);
    }




    function creacion_producto()
    {
        $this->request->getPost('impresion_en_comanda');
        if (
            !$this->validate([
                'crear_producto_codigo_interno' => [
                    'rules' => 'required|is_unique[producto.codigointernoproducto]',
                    'errors' => [
                        'required' => 'Dato necesario',
                        'is_unique' => 'Código interno ya existe',
                    ],
                ],
                'crear_producto_nombre' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Dato necesario',
                    ],
                ],
                'categoria_producto' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Dato necesario',
                    ],
                ],
                'valor_costo_producto' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Dato necesario',
                    ],
                ],
                'valor_venta_producto' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Dato necesario',
                    ],
                ],
                'marca_producto' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Dato necesarios',
                    ],
                ],
               /*  'informacion_tributaria' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Dato necesario',
                    ],
                ], */
            ])
        ) {
            $errors = $this->validator->getErrors();
            echo json_encode(['code' => 0, 'error' => $errors]);
        } else {

            $impresion_comanda = "";

            if (empty($this->request->getPost('impresion_en_comanda'))) {
                $impresion_comanda = 'false';
            } else if (!empty($this->request->getPost('impresion_en_comanda'))) {
                $impresion_comanda = 'true';
            }

            if (empty($this->request->getPost('permitir_descuento'))) {
                $aplica_descuento = 'false';
            } else if (!empty($this->request->getPost('permitir_descuento'))) {
                $aplica_descuento = 'true';
            }

            //$valor_venta_producto = str_replace('.', '', $this->request->getPost('valor_venta_producto'));

            $valor_venta = str_replace('.', '', $this->request->getPost('valor_venta_producto'));

            if ($valor_venta == 0) {
                $val_venta_producto = 1;
                $valor_venta_producto = 0;
                $precio_2=0;
            }
            if ($valor_venta > 0) {
                $valor_venta_producto = $valor_venta;
                $pre_2 = (str_replace('.', '', $this->request->getPost('precio_2')) * 100) / $valor_venta_producto;
                $precio_2 = 100 - $pre_2;
            }
    

           // exit($valor_venta_producto);


            $precio_costo = str_replace('.', '', $this->request->getPost('valor_costo_producto'));

            $aplica_ico = '';

            $temp_precio_2 = $this->request->getPost('precio_2');


            //$pre_2 = (str_replace('.', '', $this->request->getPost('precio_2')) * 100) / $valor_venta_producto;
           //$precio_2 = 100 - $pre_2;


            $valorImpuestoSaludable = $this->request->getPost('valor_impuesto_saludable');

            // Quitar los puntos al impuesto saludable
            $valorImpuestoSaludable = str_replace('.', '', $valorImpuestoSaludable);

            // Si el impuesto saludable está vacío, asignarle un cero
            if (empty($valorImpuestoSaludable)) {
                $valorImpuestoSaludable = 0;
            }

            //echo $this->request->getPost('sub_categoria'); exit();

            if (empty($this->request->getPost('sub_categoria'))) {
                $id_categoria = 0;
            }
            if (!empty($this->request->getPost('sub_categoria'))) {
                $id_categoria = $this->request->getPost('sub_categoria');
            }



            if ($this->request->getPost('informacion_tributaria') == 1) {

                $data = [
                    'codigointernoproducto' => $this->request->getPost('crear_producto_codigo_interno'),
                    'codigobarrasproducto' => $this->request->getPost('crear_producto_codigo_de_barras'),
                    'referenciaproducto' => '',
                    'nombreproducto' => $this->request->getPost('crear_producto_nombre'),
                    'descripcionproducto' => '',
                    'codigocategoria' => $this->request->getPost('categoria_producto'),
                    'idmarca' => $this->request->getPost('marca_producto'),
                    'utilidadporcentualproducto' => 1,
                    'valorventaproducto' => $valor_venta_producto,
                    'aplicaprecioporcentaje' => false,
                    //'idiva' => $this->request->getPost('valor_iva'),
                    'idiva' => 1,
                    'unidadventaproducto' => 1,
                    'cantidadminimaproducto' => 0,
                    'cantidadmaximaproducto' => 0,
                    'estadoproducto' => true,
                    'aplicatalla' => false,
                    'aplicacolor' => false,
                    'cantidad_decimal' => false,
                    'precio_costo' => $precio_costo,
                    'descto_mayor' => $precio_2,
                    'descto_distribuidor' => 0,
                    'idiva_temp' => 1,
                    'utilidad_2' => 0,
                    'utilidad_3' => 0,
                    'codigo_2' => '',
                    'codigo_3' => '',
                    'codigo_4' => '',
                    'codigo_5' => '',
                    'codigo_6' => '',
                    'codigo_7' => '',
                    'descto_3' => 0,
                    'inicial' => true,
                    'impoconsumo' => 0,
                    'id_tipo_inventario' => $this->request->getPost('tipoProducto'),  
                    'id_ico_producto' => $this->request->getPost('valor_ico'),
                    'aplica_ico' => true,
                    'se_imprime' => $impresion_comanda,
                    'aplica_descuento' => $aplica_descuento,
                    'id_impuesto_saludable' => $this->request->getPost('impuesto_saludable'),
                    'valor_impuesto_saludable' => 0,
                    //'id_subcategoria'=>$this->request->getPost('sub_categoria')
                    'id_subcategoria' => $id_categoria,
                    'favorito' => $this->request->getPost('favorito'),
                    'precio_3' => str_replace('.', '',$this->request->getPost('precio_3'))
                ];

              
                $insert = model('productoModel')->insert($data);



                if ($insert) {

                    $consecutivo_producto = model('consecutivosModel')->select('numeroconsecutivo')->where(' idconsecutivos', 2)->first();

                    $codigo_producto = [
                        'numeroconsecutivo' => $consecutivo_producto['numeroconsecutivo'] + 1
                    ];

                    $model = model('consecutivosModel');
                    $actualizar = $model->set($codigo_producto);
                    $actualizar = $model->where(' idconsecutivos', 2);
                    $actualizar = $model->update();

                    $producto_medida = [
                        'codigointernoproducto' => $this->request->getPost('crear_producto_codigo_interno'),
                        'idvalor_unidad_medida' => 3

                    ];

                    $insertar = model('productoMedidaModel')->insert($producto_medida);

                    $inventario = [
                        'codigointernoproducto' => $this->request->getPost('crear_producto_codigo_interno'),
                        'idvalor_unidad_medida' => 3,
                        'idcolor' => 0,
                        'cantidad_inventario' => 0

                    ];

                    $inventario = model('inventarioModel')->insert($inventario);


                    $favorito = view('configuracion/configuracion_favoritos');
                    $select_info_tri = view('configuracion/select_info_tri');
                    $tipo_impuesto = view('configuracion/tipo_impuesto');
                    $categorias = view('configuracion/categorias');

                    echo json_encode(
                        [
                            'code' => 1,
                            'msg' => 'Usuario creado',
                            'favorito' => $favorito,
                            'select_info_tri' => $select_info_tri,
                            'tipo_impuesto' => $tipo_impuesto,
                            'categorias' =>  $categorias,
                        ],

                    );

                    //echo json_encode(['code' => 1, 'msg' => 'Usuario creado']);
                } else {
                    echo json_encode(['code' => 0, 'msg' => 'No se pudo crear ']);
                }
            }
            if ($this->request->getPost('informacion_tributaria') == 2) {

                $data = [
                    'codigointernoproducto' => $this->request->getPost('crear_producto_codigo_interno'),
                    'codigobarrasproducto' => $this->request->getPost('crear_producto_codigo_de_barras'),
                    'referenciaproducto' => '',
                    'nombreproducto' => $this->request->getPost('crear_producto_nombre'),
                    'descripcionproducto' => '',
                    'codigocategoria' => $this->request->getPost('categoria_producto'),
                    'idmarca' => $this->request->getPost('marca_producto'),
                    'utilidadporcentualproducto' => 1,
                    'valorventaproducto' => $valor_venta_producto,
                    'aplicaprecioporcentaje' => false,
                    'idiva' => $this->request->getPost('valor_iva'),
                    'unidadventaproducto' => 1,
                    'cantidadminimaproducto' => 0,
                    'cantidadmaximaproducto' => 0,
                    'estadoproducto' => true,
                    'aplicatalla' => false,
                    'aplicacolor' => false,
                    'cantidad_decimal' => false,
                    'precio_costo' => $precio_costo,
                    'descto_mayor' => $precio_2,
                    'descto_distribuidor' => 0,
                    'idiva_temp' => 1,
                    'utilidad_2' => 0,
                    'utilidad_3' => 0,
                    'codigo_2' => '',
                    'codigo_3' => '',
                    'codigo_4' => '',
                    'codigo_5' => '',
                    'codigo_6' => '',
                    'codigo_7' => '',
                    'descto_3' => 0,
                    'inicial' => true,
                    'impoconsumo' => 0,
                    'id_tipo_inventario' => $this->request->getPost('tipoProducto'),
                    'id_ico_producto' => 2,
                    'aplica_ico' => false,
                    'se_imprime' => $impresion_comanda,
                    'aplica_descuento' => $aplica_descuento,
                    'id_impuesto_saludable' => $this->request->getPost('impuesto_saludable'),
                    'valor_impuesto_saludable' => $valorImpuestoSaludable,
                    //'id_subcategoria' => $this->request->getPost('sub_categoria')
                    //'id_subcategoria' => 0,
                    'id_subcategoria' => $id_categoria,
                    'favorito' => $this->request->getPost('favorito'),
                    'precio_3' => str_replace('.', '',$this->request->getPost('precio_3'))

                ];

                $insert = model('productoModel')->insert($data);

                if ($insert) {
                    $consecutivo_producto = model('consecutivosModel')->select('numeroconsecutivo')->where(' idconsecutivos', 2)->first();

                    $codigo_producto = [
                        'numeroconsecutivo' => $consecutivo_producto['numeroconsecutivo'] + 1
                    ];

                    $model = model('consecutivosModel');
                    $actualizar = $model->set($codigo_producto);
                    $actualizar = $model->where(' idconsecutivos', 2);
                    $actualizar = $model->update();

                    $producto_medida = [
                        'codigointernoproducto' => $this->request->getPost('crear_producto_codigo_interno'),
                        'idvalor_unidad_medida' => 3

                    ];

                    $insertar = model('productoMedidaModel')->insert($producto_medida);

                    $inventario = [
                        'codigointernoproducto' => $this->request->getPost('crear_producto_codigo_interno'),
                        'idvalor_unidad_medida' => 3,
                        'idcolor' => 0,
                        'cantidad_inventario' => 0

                    ];

                    $inventario = model('inventarioModel')->insert($inventario);

                    $favorito = view('configuracion/configuracion_favoritos');
                    $select_info_tri = view('configuracion/select_info_tri');
                    $tipo_impuesto = view('configuracion/tipo_impuesto');
                    $categorias = view('configuracion/categorias');

                    echo json_encode(
                        [
                            'code' => 1,
                            'msg' => 'Usuario creado',
                            'favorito' => $favorito,
                            'select_info_tri' => $select_info_tri,
                            'tipo_impuesto' => $tipo_impuesto,
                            'categorias' =>  $categorias,
                        ],

                    );
                } else {
                    echo json_encode(['code' => 0, 'msg' => 'No se pudo crear ']);
                }
            }

            if (empty($this->request->getPost('informacion_tributaria'))) {

                $data = [
                    'codigointernoproducto' => $this->request->getPost('crear_producto_codigo_interno'),
                    'codigobarrasproducto' => $this->request->getPost('crear_producto_codigo_de_barras'),
                    'referenciaproducto' => '',
                    'nombreproducto' => $this->request->getPost('crear_producto_nombre'),
                    'descripcionproducto' => '',
                    'codigocategoria' => $this->request->getPost('categoria_producto'),
                    'idmarca' => $this->request->getPost('marca_producto'),
                    'utilidadporcentualproducto' => 1,
                    'valorventaproducto' => $valor_venta_producto,
                    'aplicaprecioporcentaje' => false,
                    'idiva' => 1,
                    'unidadventaproducto' => 1,
                    'cantidadminimaproducto' => 0,
                    'cantidadmaximaproducto' => 0,
                    'estadoproducto' => true,
                    'aplicatalla' => false,
                    'aplicacolor' => false,
                    'cantidad_decimal' => false,
                    'precio_costo' => $precio_costo,
                    'descto_mayor' => $precio_2,
                    'descto_distribuidor' => 0,
                    'idiva_temp' => 1,
                    'utilidad_2' => 0,
                    'utilidad_3' => 0,
                    'codigo_2' => '',
                    'codigo_3' => '',
                    'codigo_4' => '',
                    'codigo_5' => '',
                    'codigo_6' => '',
                    'codigo_7' => '',
                    'descto_3' => 0,
                    'inicial' => true,
                    'impoconsumo' => 0,
                    'id_tipo_inventario' => $this->request->getPost('tipoProducto'),
                    'id_ico_producto' => 2,
                    'aplica_ico' => false,
                    'se_imprime' => $impresion_comanda,
                    'aplica_descuento' => $aplica_descuento,
                    'id_impuesto_saludable' => $this->request->getPost('impuesto_saludable'),
                    'valor_impuesto_saludable' => $valorImpuestoSaludable,
                    //'id_subcategoria' => $this->request->getPost('sub_categoria')
                    //'id_subcategoria' => 0,
                    'id_subcategoria' => $id_categoria,
                    'favorito' => $this->request->getPost('favorito'),
                    'precio_3' => str_replace('.', '',$this->request->getPost('precio_3'))

                ];

                $insert = model('productoModel')->insert($data);

                if ($insert) {
                    $consecutivo_producto = model('consecutivosModel')->select('numeroconsecutivo')->where(' idconsecutivos', 2)->first();

                    $codigo_producto = [
                        'numeroconsecutivo' => $consecutivo_producto['numeroconsecutivo'] + 1
                    ];

                    $model = model('consecutivosModel');
                    $actualizar = $model->set($codigo_producto);
                    $actualizar = $model->where(' idconsecutivos', 2);
                    $actualizar = $model->update();

                    $producto_medida = [
                        'codigointernoproducto' => $this->request->getPost('crear_producto_codigo_interno'),
                        'idvalor_unidad_medida' => 3

                    ];

                    $insertar = model('productoMedidaModel')->insert($producto_medida);

                    $inventario = [
                        'codigointernoproducto' => $this->request->getPost('crear_producto_codigo_interno'),
                        'idvalor_unidad_medida' => 3,
                        'idcolor' => 0,
                        'cantidad_inventario' => 0

                    ];

                    $inventario = model('inventarioModel')->insert($inventario);

                    $favorito = view('configuracion/configuracion_favoritos');
                    $select_info_tri = view('configuracion/select_info_tri');
                    $tipo_impuesto = view('configuracion/tipo_impuesto');
                    $categorias = view('configuracion/categorias');

                    echo json_encode(
                        [
                            'code' => 1,
                            'msg' => 'Usuario creado',
                            'favorito' => $favorito,
                            'select_info_tri' => $select_info_tri,
                            'tipo_impuesto' => $tipo_impuesto,
                            'categorias' =>  $categorias,
                        ],

                    );
                } else {
                    echo json_encode(['code' => 0, 'msg' => 'No se pudo crear ']);
                }
            }

            if (!empty($this->request->getPost('sub_categoria'))) {

                $productos_cat_sub = model('productoCategoriaModel')->sub_categorias($this->request->getPost('categoria_producto'), $this->request->getPost('sub_categoria'));

                if (empty($productos_cat_sub)) {
                    $data_producto = [
                        'id_categoria' => $this->request->getPost('categoria_producto'),
                        'id_sub_categoria' => $this->request->getPost('sub_categoria')
                    ];

                    $insercion = model('productoCategoriaModel')->insert($data_producto);
                }
            }
        }
    }


    function editar_precios()
    {
        $id_producto = $this->request->getPost('id_producto');
        //$id_producto = '6';

        $iva = model('ivaModel')->orderBy('idiva', 'DESC')->findAll();
        $ico = model('icoConsumoModel')->orderBy('id_ico', 'DESC')->findAll();
        $valor_costo = model('productoModel')->select('precio_costo')->where('codigointernoproducto', $id_producto)->first();
        $valor_venta = model('productoModel')->select('valorventaproducto')->where('codigointernoproducto', $id_producto)->first();
        $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $id_producto)->first();
        $categorias = model('categoriasModel')->findAll();
        $marcas = model('marcasModel')->findAll();
        $imprimie_en_comanda = model('productoModel')->select('se_imprime')->where('codigointernoproducto', $id_producto)->first();
        $permite_descuento = model('productoModel')->select('aplica_descuento')->where('codigointernoproducto', $id_producto)->first();
        $aplica_ico = model('productoModel')->select('aplica_ico')->where('codigointernoproducto', $id_producto)->first();
        $id_iva = model('productoModel')->select('idiva')->where('codigointernoproducto', $id_producto)->first();
        $id_ico = model('productoModel')->select('id_ico_producto')->where('codigointernoproducto', $id_producto)->first();
        $id_categoria = model('productoModel')->select('codigocategoria')->where('codigointernoproducto', $id_producto)->first();
        $descto_mayor = model('productoModel')->select('descto_mayor')->where('codigointernoproducto', $id_producto)->first();
        $temp_precio_2 = ($descto_mayor['descto_mayor'] * $valor_venta['valorventaproducto']) / 100;
        $precio_2 = $valor_venta['valorventaproducto'] - $temp_precio_2;
        $impuesto_saludable = model('impuestoSaludableModel')->findAll();
        $valor_impuesto_saludable = model('productoModel')->select('valor_impuesto_saludable')->where('codigointernoproducto', $id_producto)->first();
        $codigo_barras = model('productoModel')->select('codigobarrasproducto')->where('codigointernoproducto', $id_producto)->first();
        $precio_3 = model('productoModel')->select('precio_3')->where('codigointernoproducto', $id_producto)->first();

        //$sub_categoria = model('categoriasModel')->select('subcategoria')->where('codigocategoria', $id_categoria['codigocategoria'])->first();
        $sub_categoria = model('productoModel')->select('id_subcategoria')->where('codigointernoproducto', $id_producto)->first();

        $sub_categorias = model('subCategoriaModel')->findAll();


        $returnData = array(
            "resultado" => 1,
            "edicion_producto" => view('producto/editar_producto', [
                'iva' => $iva,
                'ico' => $ico,
                'valor_costo' => $valor_costo['precio_costo'],
                'valor_venta' => $valor_venta['valorventaproducto'],
                'codigo_interno_producto' => $id_producto,
                'nombre_producto' => $nombre_producto['nombreproducto'],
                'categorias' => $categorias,
                'marcas' => $marcas,
                'impresion_en_comanda' => $imprimie_en_comanda['se_imprime'],
                'permite_descuento' => $permite_descuento['aplica_descuento'],
                'aplica_ico' => $aplica_ico['aplica_ico'],
                'id_iva' => $id_iva['idiva'],
                'id_ico' => $id_ico['id_ico_producto'],
                'id_categoria' => $id_categoria['codigocategoria'],
                'precio_2' => number_format($precio_2, 0, ",", "."),
                'impuesto_saludable' => $impuesto_saludable,
                'valor_impuesto_saludable' => number_format($valor_impuesto_saludable['valor_impuesto_saludable'], 0, ",", "."),
                'codigo_barras' => $codigo_barras['codigobarrasproducto'],
                //'sub_categoria' => $sub_categoria['subcategoria'],
                'sub_categoria' => $sub_categoria['id_subcategoria'],
                'sub_categorias' => $sub_categorias,
                'precio_3'=>number_format($precio_3['precio_3'], 0, ",", "."),

            ])
        );
        echo  json_encode($returnData);
    }


    function actualizar_precio_producto()
    {


        $this->request->getPost('impresion_en_comanda');
        if (
            !$this->validate([

                'crear_producto_nombre' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Dato necesario',
                    ],
                ],

                'edicion_de_valor_costo_producto' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Dato necesario',
                    ],
                ],
                'editar_marca_producto' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Dato necesarios',
                    ],
                ],
              /*   'informacion_tributaria' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Dato necesario',
                    ],
                ], */
                'editar_valor_venta_producto' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Dato necesario',
                    ],
                ],
            ])
        ) {
            $errors = $this->validator->getErrors();
            echo json_encode(['code' => 0, 'error' => $errors]);
        } else {

            $valor_venta_producto = str_replace('.', '', $this->request->getPost('editar_valor_venta_producto'));
            $codigo_interno_producto = str_replace('.', '', $this->request->getPost('codigo_interno_producto_editar'));
            $precio_costo = str_replace('.', '', $this->request->getPost('edicion_de_valor_costo_producto'));

            $imprimir_comanda = "";


            if (empty($this->request->getPost('editar_impresion_en_comanda'))) {
                $imprimir_comanda = 'false';
                //$impresion_comanda = 'false';
            }
            if (!empty($this->request->getPost('editar_impresion_en_comanda'))) {
                $imprimir_comanda = 'true';
            }

            $permite_descuento = "";
            if (empty($this->request->getPost('editar_descuento'))) {
                $permite_descuento = 'false';
            }
            if (!empty($this->request->getPost('editar_descuento'))) {
                $permite_descuento = 'true';
            }

            $aplica_ico = "";

            if ($this->request->getPost('informacion_tributaria') == 1) {  //Tiene INC
                $aplica_ico = "t";
                $id_iva = 1;
                $id_ico=$this->request->getPost('valor_ico');
            }
            if ($this->request->getPost('informacion_tributaria') == 2) { // Tiene IVA 
                $aplica_ico = "f";
                $id_iva = $this->request->getPost('valor_iva');
                $id_ico= 1;
            }
            if (empty($this->request->getPost('informacion_tributaria') )) {
                $aplica_ico = "f";
                $id_iva = 1;
                $id_ico= 1;
            }

            $temp_precio_2 = $this->request->getPost('precio_2');

            $pre_2 = (str_replace('.', '', $this->request->getPost('precio_2')) * 100) / $valor_venta_producto;
            $precio_2 = 100 - $pre_2;





            $actualizar_precio = [
                'codigobarrasproducto' => $this->request->getPost('crear_producto_codigo_de_barras'),
                'nombreproducto' => $this->request->getPost('crear_producto_nombre'),
                'codigocategoria' => $this->request->getPost('edicion_de_categoria_producto'),
                'idmarca' => $this->request->getPost('editar_marca_producto'),
                'idiva' => $id_iva,
                'valorventaproducto' =>  str_replace('.', '', $this->request->getPost('editar_valor_venta_producto')),
                'precio_costo' =>  str_replace('.', '', $this->request->getPost('edicion_de_valor_costo_producto')),
                'descto_mayor' => $precio_2,
                'se_imprime' => $imprimir_comanda,
                //'se_imprime' => $imprimir_comanda,
                'id_ico_producto' => $id_ico,
                'aplica_ico' => $aplica_ico,
                'aplica_descuento' => $permite_descuento,
                'valor_impuesto_saludable' => $this->request->getPost('edicion_de_valor_costo_producto'),
                'id_subcategoria' => $this->request->getPost('sub_categoria'),
                'favorito' => $this->request->getPost('favorito_editar'),
                'precio_3' =>  str_replace('.', '', $this->request->getPost('editar_precio_3')),
            ];

            $model = model('productoModel');
            $actualizar = $model->set($actualizar_precio);
            $actualizar = $model->where('codigointernoproducto', $codigo_interno_producto);
            $actualizar = $model->update();
            if ($actualizar) {
                echo json_encode(['code' => 1, 'msg' => 'Usuario creado']);
            }
        }
    }

    function eliminar_producto_inventario()
    {

        $tiene_movimientos = model('productoFacturaVentaModel')->where('codigointernoproducto', $this->request->getPost('id_producto'));


        $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $this->request->getPost('id_producto'))->first();

        if (empty($tiene_movimientos)) {
            echo json_encode(['resultado' => 0]);
        } else if (!empty($tiene_movimientos)) {

            echo json_encode(
                [
                    'resultado' => 1,
                    'nombre_producto' => $nombre_producto['nombreproducto'],
                    'codigo_interno_producto' => $this->request->getPost('id_producto')

                ]
            );
        }
    }


    function borrar_producto_inventario()
    {
        $codigo_interno_producto = $this->request->getPost('codigo_interno_producto');
        // $codigo_interno_producto = '3'; 
        //$tiene_movimientos = model('kardexModel')->select('id')->where('codigo', $this->request->getPost('codigo_interno_producto'))->first();
        $tiene_movimientos = model('kardexModel')->get_producto($codigo_interno_producto);



        if (!empty($tiene_movimientos)) {

            $data = [
                'estadoproducto' => false,

            ];
            $model = model('productoModel');
            $actualizar = $model->set($data);
            $actualizar = $model->where('codigointernoproducto', $codigo_interno_producto);
            $actualizar = $model->update();

            echo json_encode(
                [
                    'resultado' => 1,

                ]
            );
        }

        if (empty($tiene_movimientos)) {
            $borrar_producto = model('productoModel')->where('codigointernoproducto', $codigo_interno_producto);
            $borrar_producto->delete();

            if ($borrar_producto) {
                echo json_encode(
                    [
                        'resultado' => 1,
                    ]
                );
            }
        }
    }

    public function eliminacion_de_pedido_desde_pedido()
    {
        //$numero_pedido = 36;
        $numero_pedido = $_POST['numero_pedido'];

        //$id_usuario = 6;
        $id_usuario = $_POST['usuario_eliminacion'];

        /**
         * Consultar si el usuario tiene el permiso 92 que corresponde a que puede borrar el pedido 
         */
        $permiso_eliminar = model('usuariosModel')->select('idtipo')->where('idusuario_sistema', $id_usuario)->first();


        if ($permiso_eliminar['idtipo'] == 0) {

            $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();

            $datos_pedido = model('pedidoModel')->select('*')->where('id', $numero_pedido)->first();

            $data = [
                'numero_pedido' => $numero_pedido,
                'valor_pedido' => $datos_pedido['valor_total'],
                'fecha_eliminacion' => date('Y-m-d'),
                'hora_eliminacion' => date("h:i:s"),
                'fecha_creacion' => $datos_pedido['fecha_creacion'],
                'usuario_eliminacion' => $id_usuario
            ];

            $datos_pedido = model('eliminacionPedidosModel')->insert($data);


            $model = model('productoPedidoModel');
            $borrarPedido = $model->where('numero_de_pedido', $numero_pedido);
            $borrarPedido = $model->delete();

            $model = model('pedidoModel');
            $borrar = $model->where('id', $numero_pedido);
            $borrar = $model->delete();

            if ($borrarPedido && $borrar) {
                $data = [
                    'estado' => 0,
                    'valor_pedido' => 0,
                    'fk_usuario' => $id_usuario
                ];

                $model = model('mesasModel');
                $numero_factura = $model->set($data);
                $numero_factura = $model->where('id', $id_mesa['fk_mesa']);
                $numero_factura = $model->update();
                $returnData = array(
                    "resultado" => 1
                );
                echo  json_encode($returnData);
            } else {
                echo 0;
            }
        } else if ($permiso_eliminar['idtipo'] == 1) {
            $returnData = array(
                "resultado" => 0
            );
            echo  json_encode($returnData);
        }
    }


    function autorizacion_pin()
    {
        $id_tabla_producto = $this->request->getPost('id_tabla_producto');

        //$id_tabla_producto = 84270;
        $pin = $this->request->getPost('pin');
        //$pin = '8888';
        $tipo_usuario = model('usuariosModel')->select('idtipo')->where('pinusuario_sistema', $pin)->first();


        if ($tipo_usuario['idtipo'] == 0) {
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
                "id_tabla_producto_pedido" => $id_tabla_producto,

            );
            echo  json_encode($returnData);
        }
        if ($tipo_usuario['idtipo'] == 1) {
            $returnData = array(
                "resultado" => 0,


            );
            echo  json_encode($returnData);
        }
    }


    function eliminar_pedido_usuario()
    {
        $pin = $this->request->getPost('pin');

        $numero_pedido = $this->request->getPost('numero_pedido');


        $permiso_eliminar = model('usuariosModel')->select('idtipo')->where('pinusuario_sistema', $pin)->first();
        $id_usuario = model('usuariosModel')->select('idusuario_sistema')->where('pinusuario_sistema', $pin)->first();

        if ($permiso_eliminar['idtipo'] == 0) {

            $valor_pedido = model('pedidoModel')->select('valor_total')->where('id', $numero_pedido)->first();

            $fecha_creacion = model('pedidoModel')->select('fecha_creacion')->where('id', $numero_pedido)->first();
            $pedido_borrado = [
                'numero_pedido' => $numero_pedido,
                'valor_pedido' => $valor_pedido['valor_total'],
                'fecha_eliminacion' =>  date("Y-m-d"),
                'hora_eliminacion' => date('H:i:s'),
                'fecha_creacion' => $fecha_creacion['fecha_creacion'],
                'usuario_eliminacion' => $id_usuario['idusuario_sistema'],
                //'usuario_elimininacion' => $id_usuario['idusuario_sistema']
            ];

            $insert = model('eliminacionPedidosModel')->insert($pedido_borrado);

            $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();

            $data = [
                'estado' => 0,
                'valor_pedido' => "0",
                'fk_usuario' => $id_usuario['idusuario_sistema'],
            ];
            $model = model('mesasModel');
            $actualizar = $model->set($data);
            $actualizar = $model->where('id', $id_mesa['fk_mesa']);
            $actualizar = $model->update();

            $borrar_producto_pedido = model('productoPedidoModel')->where('numero_de_pedido', $numero_pedido);
            $borrar_producto_pedido->delete();

            $borrar_pedido = model('pedidoModel')->where('id', $numero_pedido);
            $borrar_pedido->delete();

            $returnData = array(
                "resultado" => 1,
            );
            echo  json_encode($returnData);
        }
    }

    public function entrada_salida()
    {
        $returnData = array();
        $valor = $this->request->getVar('term');
        //$valor = 'a';

        $resultado = model('productoModel')->inventario($valor);

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


                $data['value'] =  $row['codigointernoproducto'] . " " . "/" . " " . $row['nombreproducto'];
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

    public function InvSalida()
    {
        $returnData = array();
        $valor = $this->request->getVar('term');
        //$valor = 'a';

        $resultado = model('productoModel')->GetInventarioSalida($valor);

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


                $data['value'] =  $row['codigointernoproducto'] . " " . "/" . " " . $row['nombreproducto'];
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
}
