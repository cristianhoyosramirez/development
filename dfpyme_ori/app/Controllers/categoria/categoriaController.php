<?php

namespace App\Controllers\categoria;

use App\Controllers\BaseController;

class categoriaController extends BaseController
{
    public function index()
    {

        $categorias = model('categoriasModel')->select('codigocategoria');
        $categorias = model('categoriasModel')->select('permitir_categoria');
        $categorias = model('categoriasModel')->select('impresora');
        $categorias = model('categoriasModel')->select('subcategoria');
        $categorias = model('categoriasModel')->select('nombrecategoria')->orderBy('codigocategoria', 'asc')->find();
        $impresoras = model('impresorasModel')->select('*')->find();

        return view('categoria/categoria', [
            'categorias' => $categorias,
            'impresoras' => $impresoras
        ]);
    }

    public function actualizar()
    {
        $id_categoria = $_POST['codigo_categoria'];
        $id_impresora = $_POST['id_impresora'];
        $estado_categoria = $_POST['estado_categoria'];
        $data = [
            'permitir_categoria' => $estado_categoria,
            'impresora' => $id_impresora
        ];

        $model = model('categoriasModel');
        $actualizar = $model->set($data);
        $actualizar = $model->where('codigocategoria', $id_categoria);
        $actualizar = $model->update();


        if ($actualizar) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('categoria/index'))->with('mensaje', 'Actualizacion correcta');
        }
    }

    function crear()
    {
        $impresoras = model('impresorasModel')->findAll();
        return view('categoria/nueva_categoria', [
            'impresoras' => $impresoras
        ]);
    }

    function guardar()
    {

        if (!$this->validate([
            'nombre_categoria' => [
                'rules' => 'required|is_unique[categoria.nombrecategoria]',
                'errors' => [
                    'required' => 'Dato necesario',
                    'is_unique' => 'Nombre ya existe'

                ]
            ],
        ])) {
            $errors = $this->validator->getErrors();
            echo json_encode([
                'code' => 0,
                'error' => $errors
            ]);
        } else {

            $consecutivos_categoria = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 5)->first();

            $data = [
                'codigocategoria' => $consecutivos_categoria['numeroconsecutivo'],
                'nombrecategoria' => $this->request->getPost('nombre_categoria'),
                'descripcioncategoria' => '',
                'estadocategoria' => 'true',
                'permitir_categoria' => 'true',
                'impresora' => $this->request->getPost('impresora_categoria')

            ];

            $insert = model('categoriasModel')->insert($data);
            if ($insert) {
                $consecutivo = [
                    'numeroconsecutivo' => $consecutivos_categoria['numeroconsecutivo'] + 1,

                ];

                $model = model('consecutivosModel');
                $consecutivos = $model->set($consecutivo);
                $consecutivos = $model->where('idconsecutivos', 5);
                $consecutivos = $model->update();

                $categorias = model('categoriasModel')->findAll();

                echo json_encode([
                    'code' => 1,
                    'msg' => 'Usuario creado',
                    'categorias' => view('categoria/select_categorias', [
                        'codigo_categoria' => $consecutivos_categoria['numeroconsecutivo'],
                        'categorias' => $categorias
                    ])
                ]);
            }
        }
    }





    function actualizar_estado_categoria()
    {
        $data = [
            'permitir_categoria' => $this->request->getPost('opcion')
        ];

        $model = model('categoriasModel');
        $categorias = $model->set($data);
        $categorias = $model->where('codigocategoria', $this->request->getPost('id_categoria'));
        $categorias = $model->update();

        if ($categorias) {

            $categorias = model('categoriasModel')->select('codigocategoria');
            $categorias = model('categoriasModel')->select('permitir_categoria');
            $categorias = model('categoriasModel')->select('impresora');
            $categorias = model('categoriasModel')->select('nombrecategoria')->orderBy('codigocategoria', 'asc')->find();
            $impresoras = model('impresorasModel')->select('*')->find();


            $returnData = array(
                "resultado" => 0, //No hay pedido 
                "categorias" => view('categoria/tabla_categorias', [
                    'categorias' => $categorias,
                    'impresoras' => $impresoras
                ])
            );
            echo  json_encode($returnData);
        }
    }
    function actualizar_impresora()
    {
        $data = [
            'impresora' => $this->request->getPost('opcion')
        ];

        $codigo_categoria = $this->request->getPost('id_categoria');
        //$codigo_categoria = '1';

        $model = model('categoriasModel');
        $categorias = $model->set($data);
        $categorias = $model->where('codigocategoria', $codigo_categoria);
        $categorias = $model->update();
        if ($categorias) {

            $categorias = model('categoriasModel')->select('codigocategoria');
            $categorias = model('categoriasModel')->select('permitir_categoria');
            $categorias = model('categoriasModel')->select('impresora');
            $categorias = model('categoriasModel')->select('subcategoria');
            $categorias = model('categoriasModel')->select('nombrecategoria')->orderBy('codigocategoria', 'asc')->find();
            $impresoras = model('impresorasModel')->select('*')->find();


            $returnData = array(
                "resultado" => 0, //No hay pedido 
                "categorias" => view('categoria/tabla_categorias', [
                    'categorias' => $categorias,
                    'impresoras' => $impresoras
                ])
            );
            echo  json_encode($returnData);
        }
    }


    function actualizar_nombre()
    {
        $nombre_categoria = $this->request->getPost('nombre_categoria');
        $codigo_categoria = $this->request->getPost('codigo_categoria');
        $data = [
            'nombrecategoria' => $nombre_categoria
        ];

        $model = model('categoriasModel');
        $categorias = $model->set($data);
        $categorias = $model->where('codigocategoria', $codigo_categoria);
        $categorias = $model->update();


        $sub_categoria = model('subCategoriaModel')->where('id_categoria', $codigo_categoria)->first();

        if ($categorias) {
            $categorias = model('categoriasModel')->select('codigocategoria');
            $categorias = model('categoriasModel')->select('permitir_categoria');
            $categorias = model('categoriasModel')->select('impresora');
            $categorias = model('categoriasModel')->select('subcategoria');
            $categorias = model('categoriasModel')->select('nombrecategoria')->orderBy('codigocategoria', 'asc')->find();
            $impresoras = model('impresorasModel')->select('*')->find();
            $returnData = array(
                "resultado" => 0, //No hay pedido 
                "categorias" => view('categoria/tabla_categorias', [
                    'categorias' => $categorias,
                    'impresoras' => $impresoras
                ])
            );
            echo  json_encode($returnData);
        }
    }

    function get_todas_las_marcas_producto()
    {
        $marcas = model('marcasModel')->findAll();

        return view('marcas/listado', [
            'marcas' => $marcas
        ]);
    }

    function crear_marcas()
    {

        $data = [
            'nombremarca' => $_REQUEST['marca']
        ];

        $insert = model('marcasModel')->insert($data);

        if ($insert) {
            $returnData = array(
                "resultado" => 1, //No hay pedido 
                "marcas" => view('marcas/tbody_marcas', [
                    'marcas' => model('marcasModel')->findAll(),
                ])
            );
            echo  json_encode($returnData);
        }
    }

    function editar_marca()
    {
        $id_marca = $this->request->getPost('id');
        $nombre_marca = model('marcasModel')->select('nombremarca')->where('idmarca', $id_marca)->first();
        if (!empty($nombre_marca)) {
            return view('marcas/datosIniciales', [
                'nombre_marca' => $nombre_marca['nombremarca'],
                'id_marca' => $id_marca
            ]);
        }
    }

    function actualizar_marca()
    {
        $id_marca = $this->request->getPost('id');
        $nombre_marca = $this->request->getPost('nombre');

        $data = [
            'nombremarca' => $nombre_marca
        ];

        $marca = model('marcasModel');
        $marcas = $marca->set($data);
        $marcas = $marca->where('idmarca', $id_marca);
        $marcas = $marca->update();

        $session = session();
        $session->setFlashdata('iconoMensaje', 'success');
        return redirect()->to(base_url('categoria/marcas'))->with('mensaje', 'Actualizacion correcta  ');
    }

    function sub_categoria()
    {
        $data = [
            'nombre' => $this->request->getPost('nombre_categoria'),
            'id_categoria' => $this->request->getPost('categoria')
        ];

        $insert = model('subCategoriaModel')->insert($data);


        $model = model('categoriasModel');
        $categoria = $model->set('subcategoria', 'true');
        $categoria = $model->where('id', $this->request->getPost('categoria'));
        $categoria = $model->update();



        $session = session();
        $session->setFlashdata('iconoMensaje', 'success');
        return redirect()->to(base_url('configuracion/crear_sub_categoria'))->with('mensaje', 'Subcategoria creada  ');
    }


    function actualizar_sub_categoria()
    {

        //echo $this->request->getPost('opcion'); exit();
        $data = [
            'subcategoria' => $this->request->getPost('opcion')
        ];

        $model = model('categoriasModel');
        $categorias = $model->set($data);
        $categorias = $model->where('codigocategoria', $this->request->getPost('id_categoria'));
        $categorias = $model->update();

        if ($categorias) {

            $categorias = model('categoriasModel')->select('codigocategoria');
            $categorias = model('categoriasModel')->select('permitir_categoria');
            $categorias = model('categoriasModel')->select('impresora');
            $categorias = model('categoriasModel')->select('subcategoria');
            $categorias = model('categoriasModel')->select('nombrecategoria')->orderBy('codigocategoria', 'asc')->find();
            $impresoras = model('impresorasModel')->select('*')->find();


            $returnData = array(
                "resultado" => 0, //No hay pedido 
                "categorias" => view('categoria/tabla_categorias', [
                    'categorias' => $categorias,
                    'impresoras' => $impresoras
                ])
            );
            echo  json_encode($returnData);
        }
    }

    function consulta_sub_categoria()
    {
        $id_categoria = $this->request->getPost('id_categoria');
        //$id_categoria = 4;

        $sub_categoria = model('categoriasModel')->select('subcategoria')->where('id', $id_categoria)->first();

        if ($sub_categoria['subcategoria'] == 't') {

            $sub_categorias = model('subCategoriaModel')->where('id_categoria', $id_categoria)->findAll();

            $returnData = array(
                "resultado" => 1, //No hay pedido 
                "sub_categoria" => $sub_categoria['subcategoria'],
                "datos" => view('categoria/sub_categoria', [
                    'sub_categorias' => $sub_categorias
                ])
            );
            echo  json_encode($returnData);
        }


        if ($sub_categoria['subcategoria'] == 'f') {


            $returnData = array(
                "resultado" => 1, //No hay pedido 
                "sub_categoria" => $sub_categoria['subcategoria'],
            );
            echo  json_encode($returnData);
        }
    }
}
