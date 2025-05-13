<?php

namespace App\Controllers\pedidos;

use App\Controllers\BaseController;

class TomaPedidosController extends BaseController
{
    public function index()
    {
        $categorias = model('categoriasModel')->where('permitir_categoria', 'true')->orderBy('orden', 'asc')->findAll();
        $salones = model('salonesModel')->findAll();
        $mesas = model('mesasModel')->where('estado',0)->orderBy('id', 'ASC')->findAll();
        $estado = model('estadoModel')->orderBy('idestado', 'ASC')->findAll();
        $cliente_dian = model('clientesModel')->where('nitcliente', '222222222222')->first();
        $bancos = model('BancoModel')->findAll();
        $requiere_mesero = model('configuracionPedidoModel')->select('mesero_pedido')->first();
        $meseros = model('usuariosModel')->where('idtipo', 2)->orderBy('nombresusuario_sistema', 'asc')->find();
        $meseros = model('usuariosModel')->where('estadousuario_sistema', true)->orderBy('nombresusuario_sistema', 'asc')->find();
        
        return view('toma_pedidos/pedidos',[
            'categorias' => $categorias,
            'salones' => $salones,
            'mesas' => $mesas,
            'estado' => $estado,
            'nit_cliente' => $cliente_dian['nitcliente'],
            'nombre_cliente' => '222222222222 CUANTIAS MENORES',
            'bancos' => $bancos,
            'requiere_mesero' => $requiere_mesero['mesero_pedido'],
            'meseros' => $meseros
        ]);
    }

    public function actualizarNota(){

        $json = $this->request->getJSON();
        $id = $json->id;
        $nota = $json->nota;

        $model = model('productoPedidoModel');
        $model->set('nota_producto', $nota);
        $model->where('id', $id);

        if ($model->update()) {
            return json_encode(['status' => 'success']);
        } else {
            return json_encode(['status' => 'error']);
        }

    }
}
