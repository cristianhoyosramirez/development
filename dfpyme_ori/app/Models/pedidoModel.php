<?php

namespace App\Models;

use CodeIgniter\Model;

class pedidoModel extends Model
{
    protected $table      = 'pedido';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['fk_mesa', 'fk_usuario', 'valor_total', 'nota_pedido', 'cantidad_de_productos', 'fecha', 'numero_factura', 'base_iva', 'impuesto_iva', 'base_ico', 
    'impuesto_ico','consulta_valor_pedido','propina','propina_parcial'];

    protected $useTimestamps = true;
    // protected $createdField  = 'created_at';
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_actualizacion';
    protected $deletedField  = 'deleted_at';

    public function todosLospedidos()
    {
        $datos = $this->db->query("
        SELECT
        pedido.id,
        fecha_creacion,
        mesas.nombre,
        valor_total,
        nota_pedido
    FROM
        pedido
    INNER JOIN mesas ON pedido.fk_mesa = mesas.id
        ");
        return $datos->getResultArray();
    }
    public function pedido($pedido)
    {
        $datos = $this->db->query("
        SELECT
            usuario_sistema.nombresusuario_sistema,
            valor_total,
            nota_pedido,
            cantidad_de_productos,
            fecha_creacion,
            mesas.nombre
        FROM
            pedido
    INNER JOIN usuario_sistema ON usuario_sistema.idusuario_sistema = pedido.fk_usuario
    INNER JOIN mesas ON mesas.id = pedido.fk_mesa
    WHERE
        pedido.id = '$pedido'
        ");
        return $datos->getResultArray();
    }
    public function partir_factura($id)
    {
        $datos = $this->db->query("
        SELECT
            cantidad_producto,
            valor_unitario,
            valor_total,
            producto_pedido.codigointernoproducto,
            producto.nombreproducto
        FROM
            producto_pedido
        INNER JOIN producto ON producto_pedido.codigointernoproducto = producto.codigointernoproducto
        WHERE
        producto_pedido.id = $id
        ");
        return $datos->getResultArray();
    }

    public function pedido_mesa($id)
    {
        $datos = $this->db->query("
        SELECT
            valor_total,
            id,
            propina,
            usuario_sistema.nombresusuario_sistema,
            nota_pedido,
            fecha_creacion
        FROM
            pedido
        INNER JOIN usuario_sistema ON usuario_sistema.idusuario_sistema = pedido.fk_usuario
        WHERE
            fk_mesa = $id
        ");
        return $datos->getResultArray();
    }
    public function update_mesa()
    {
        $datos = $this->db->query("
        SELECT
            fk_mesa,
            mesas.nombre,
            pedido.fk_usuario,
            usuario_sistema.nombresusuario_sistema,
            valor_total,
            propina
            
        FROM
            pedido
        INNER JOIN mesas ON mesas.id = pedido.fk_mesa
        INNER JOIN usuario_sistema ON usuario_sistema.idusuario_sistema = pedido.fk_usuario
        ");
        return $datos->getResultArray();
    }
}
