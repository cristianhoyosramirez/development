<?php

namespace App\Controllers\login;

use App\Controllers\BaseController;

class loginController extends BaseController
{
    public function index()
    {
        return view('home/home');
    }

    public function login()
    {

        if (!$this->validate([
            'pin' => [
                'rules' => 'required|is_not_unique[usuario_sistema.pinusuario_sistema]|max_length[4]',
                'errors' => [
                    'required' => 'El pin es requerido',
                    'is_not_unique' => 'Pin inexistente',
                    'max_length' => 'Longitud máxima de 4 digitos'

                ]
            ],

        ])) {
            return redirect()->to(base_url('/'))->withInput()->with('errors', $this->validator->getErrors());
        }


        $pin = $this->request->getVar('pin');
        $usuario = model('usuariosModel')->usuario_valido($pin);
        #$usuario = model('usuariosModel')->select('*')->where('pinusuario_sistema', $pin)->first();


        if (!empty($usuario)) {
            $tipo_permiso = model('tipoPermisoModel')->select('*')->where('idusuario_sistema', $usuario[0]['idusuario_sistema'])->find();



            if ($usuario) {
                $datosSesion = [
                    'id_usuario' => $usuario[0]['idusuario_sistema'],
                    'usuario' => $usuario[0]['usuariousuario_sistema'],
                    'nombre_usuario' => $usuario[0]['nombresusuario_sistema'],
                    'logged_in' => TRUE,
                    'tipo' => $usuario[0]['idtipo'],
                    'tipo_permiso' => $tipo_permiso
                ];
                $sesion = session();
                $sesion->set($datosSesion);
                if ($usuario[0]['idtipo'] == 0 or $usuario[0]['idtipo'] == 1 or $usuario[0]['idtipo'] == 3) {
                    return redirect()->to(base_url('pedidos/mesas'));
                }

                if ($usuario[0]['idtipo'] == 2) {
                    return redirect()->to(base_url('pedidos/gestion_pedidos'));
                }
            } else {
                $datosSesion = [
                    'id_usuario' => $usuario['idusuario_sistema'],
                    'usuario' => $usuario['usuariousuario_sistema'],
                    'nombre_usuario' => $usuario['nombresusuario_sistema'],
                    'logged_in' => FALSE,
                    'tipo' => $usuario['idtipo']
                ];
                $sesion = session();
                $sesion->set($datosSesion);
                return redirect()->to(base_url('/'))->withInput()->with('errors', $this->validator->getErrors());
            }
        } else if (empty($usuario)) {

            $session = session();
            $session->setFlashdata('iconoMensaje', 'Error');
            return redirect()->to(base_url())->with('mensaje', 'Usuario inactivo o no existe');
        }
    }
    public function closeSesion()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url() . '/');
    }

    public function recetas()
    {
        $recetas = model('productoModel')->select('codigointernoproducto,id,nombreproducto,precio_costo,valorventaproducto')->where('id_tipo_inventario', 3)->orderBy('nombreproducto', 'asc')->findAll();
        $insumos = model('productoModel')->select('codigointernoproducto,id,nombreproducto,precio_costo,valorventaproducto')->where('id_tipo_inventario', 4)->orderBy('nombreproducto', 'asc')->findAll();
        $openModal = model('configuracionPedidoModel')->select('recetasmodal')->first();

        return view('producto/recetas', [
            'recetas' => $recetas,
            'insumos' => $insumos,
            'modal' => $openModal['recetasmodal']
        ]);
    }

    public function Searchrecetas()
    {
        $returnData = array();
        $valor = $this->request->getVar('valorBusqueda');

        $resultado = model('productoModel')->GetRecetas($valor);

        if (!empty($resultado)) {
            return $this->response->setJSON([
                'response' => 'success',

                'productos' =>  view('recetas/listaRecetas', [
                    'productos' => $resultado
                ]),

            ]);
        } else if (empty($resultado)) {

            return $this->response->setJSON([
                'response' => 'empty',
            ]);
        }
    }

    function SearchInsumosRecetas()
    {

        $json = $this->request->getJSON();
        $codigo = $json->codigointernoproducto;
        //$codigo = '201';

        $productos = model('productoFabricadoModel')->GetInsumos($codigo);
        $costo = model('productoFabricadoModel')->GetCosto($codigo);
        $receta = model('productoModel')->GetReceta($codigo);
        $precioVenta = model('productoModel')->GetValVenta($codigo);

        if (!empty($productos)) {
            return $this->response->setJSON([
                'response' => 'success',

                'productos' =>  view('recetas/insumos', [
                    'productos' => $productos
                ]),
                'receta' => '<span class="text-primary">Componentes receta:</span> <span class="text-orange">' . htmlspecialchars($receta[0]['nombreproducto'], ENT_QUOTES, 'UTF-8') . '</span>',

                'costo' => $costo[0]['costo'],
                'precioVenta' => number_format($precioVenta[0]['valorventaproducto'], 0, ',', '.'),
                'rentabilidad' => number_format($precioVenta[0]['valorventaproducto'] - $costo[0]['costo'], 0, ',', '.'),
                'codigo' => $codigo,
                'nombreReceta' => $receta[0]['nombreproducto']


            ]);
        } else if (empty($productos)) {
            return $this->response->setJSON([
                'response' => 'fail',
                'receta' => '<span class="text-primary">Componentes receta:</span> <span class="text-orange">' . htmlspecialchars($receta[0]['nombreproducto'], ENT_QUOTES, 'UTF-8') . '</span>',
                'costo' => number_format($costo[0]['costo'], 0, ',', '.'),
                'precioVenta' => number_format($precioVenta[0]['valorventaproducto'], 0, ',', '.'),
                'rentabilidad' => number_format($precioVenta[0]['valorventaproducto'] - $costo[0]['costo'], 0, ',', '.'),
                'codigo' => $codigo,
                'nombreReceta' => $receta[0]['nombreproducto']
            ]);
        }
    }

    function deleteInsumo()
    {

        $json = $this->request->getJSON();
        $id = $json->id;

        $receta = model('productoFabricadoModel')->select('prod_fabricado')->where('id', $id)->first();
        $codigoReceta = $receta['prod_fabricado'];

        $borrar = model('productoFabricadoModel')->where('id', $id)->delete();

        if ($borrar) {

            $costo = model('productoFabricadoModel')->GetCosto($codigoReceta);
            $precioVenta = model('productoModel')->GetValVenta($codigoReceta);

            return $this->response->setJSON([
                'response' => 'success',
                'id' => $id,
                'rentabilidad' => number_format(
                    ($precioVenta[0]['valorventaproducto'] ?? 0) - ($costo[0]['costo'] ?? 0),
                    0,
                    ',',
                    '.'
                ),

                'costo' => number_format($costo[0]['costo'], 0, ',', '.'),
                'precio_venta' => number_format($precioVenta[0]['valorventaproducto'], 0, ',', '.')

            ]);
        }
    }

    function SearchInsumos()
    {
        $json = $this->request->getJSON();
        $valor = $json->valorBusqueda;

        $insumos = model('productoModel')->GetInsumos($valor);

        if (!empty($insumos)) {
            return $this->response->setJSON([
                'response' => 'success',

                'productos' =>  view('recetas/listaInsumos', [
                    'productos' => $insumos
                ]),

            ]);
        }
        if (empty($insumos)) {
            return $this->response->setJSON([
                'response' => 'empty',

                'productos' =>  view('recetas/listaInsumos', [
                    'productos' => $insumos
                ]),

            ]);
        }
    }

    function addInsumo()
    {
        $json = $this->request->getJSON();
        $codigoReceta = $json->codigoReceta;
        //$codigoReceta = 107;
        $codigoInsumo = $json->codigoInsumo;
        // $codigoInsumo = 14;
        $cantidad = $json->cantidad;
        //$cantidad = 4;

        //$existeInsumo = model('productoFabricadoModel')->select('prod_proceso')->where('prod_proceso', $codigoInsumo)->first();
        $existeInsumo = model('productoFabricadoModel')->existeInsumo($codigoReceta, $codigoInsumo);

        //dd($existeInsumo);

        if (!empty($existeInsumo)) {

            return $this->response->setJSON([
                'response' => 'exist',

            ]);
        }
        if (empty($existeInsumo)) {

            $data = [
                'prod_fabricado' => $codigoReceta,
                'prod_proceso' => $codigoInsumo,
                'cantidad' => $cantidad
            ];

            $insert = model('productoFabricadoModel')->insert($data);

            if ($insert) {

                $insumos = model('productoFabricadoModel')->GetInsumos($codigoReceta);
                $costo = model('productoFabricadoModel')->GetCosto($codigoReceta);
                $precioVenta = model('productoModel')->GetValVenta($codigoReceta);

                return $this->response->setJSON([
                    'response' => 'success',
                    'insumos' =>  view('recetas/insumos', [
                        'productos' => $insumos
                    ]),
                    'rentabilidad' => number_format(
                        ($precioVenta[0]['valorventaproducto'] ?? 0) - ($costo[0]['costo'] ?? 0),
                        0,
                        ',',
                        '.'
                    ),

                    'costo' => number_format($costo[0]['costo'], 0, ',', '.'),
                    'precio_venta' => number_format($precioVenta[0]['valorventaproducto'], 0, ',', '.')
                ]);
            }
        }
    }

    function updateCantidadInsumo()
    {

        $json = $this->request->getJSON();
        $id = $json->id;
        //$id = 39;
        $valor = $json->valor;
        //$valor = 22;

        $actualizar = model('productoFabricadoModel')->set('cantidad', $valor)->where('id', $id)->update();
        $prod_proceso = model('productoFabricadoModel')->select('prod_proceso')->where('id', $id)->first();

        $prod_fabricado = model('productoFabricadoModel')->select('prod_fabricado')->where('id', $id)->first();
        $codigoReceta = $prod_fabricado['prod_fabricado'];
        $costo = model('productoFabricadoModel')->GetCosto($codigoReceta);

        $costoUnitario = model('productoModel')->GetCostoUnitario($prod_proceso['prod_proceso']);

        $precioVenta = model('productoModel')->GetValVenta($codigoReceta);

        return $this->response->setJSON([
            'response' => 'success',

            'rentabilidad' => number_format(
                ($precioVenta[0]['valorventaproducto'] ?? 0) - ($costo[0]['costo'] ?? 0),
                0,
                ',',
                '.'
            ),

            'costo' => $costo[0]['costo'],
            'precio_venta' => number_format($precioVenta[0]['valorventaproducto'], 0, ',', '.'),
            //'costoTotal' => ($costoUnitario[0]['precio_costo'] * $valor),
            //'costoTotal' => round($costoUnitario[0]['precio_costo'] * $valor, 2),
            'costoTotal' => number_format($costoUnitario[0]['precio_costo'] * $valor, 2, '.', ','),

            'id' => $id
        ]);
    }

    function updateMedida()
    {

        $json = $this->request->getJSON();
        $idUnidad = $json->idUnidad;
        $codigProducto = $json->codigoInternoProducto;

        $update = model('productoMedidaModel')->set('idvalor_unidad_medida', $idUnidad)->where('codigointernoproducto', $codigProducto)->update();

        if ($update) {
            return $this->response->setJSON([
                'response' => 'success',
            ]);
        }
    }

    function allRecetas()
    {
        $resultado = model('productoModel')->select('codigointernoproducto,id,nombreproducto,precio_costo,valorventaproducto')->where('id_tipo_inventario', 3)->orderBy('nombreproducto', 'asc')->findAll();
    
        return $this->response->setJSON([
            'response' => 'success',
            'productos' =>  view('recetas/listaRecetas', [
                    'productos' => $resultado
                ]),

        ]);

    }

    function AllInsumos()
    {
    
        

        $insumos = model('productoModel')->GetaLLInsumos();

        if (!empty($insumos)) {
            return $this->response->setJSON([
                'response' => 'success',

                'productos' =>  view('recetas/listaInsumos', [
                    'productos' => $insumos
                ]),

            ]);
        }
        if (empty($insumos)) {
            return $this->response->setJSON([
                'response' => 'empty',

                'productos' =>  view('recetas/listaInsumos', [
                    'productos' => $insumos
                ]),

            ]);
        }
    }
}
