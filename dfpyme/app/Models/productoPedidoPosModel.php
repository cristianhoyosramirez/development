<?php

namespace App\Models;

use CodeIgniter\Model;

class productoPedidoPosModel extends Model
{
    protected $table      = 'producto_pedido_pos';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['codigointernoproducto', 'cantidad_producto', 'valor_unitario', 'valor_total', 'id_categoria', 'se_imprime_en_comanda', 'impreso_en_comanda', 'nota_producto', 'pk_pedido_pos'];

    public function producto_pedido($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
              id,
              cantidad_producto,
              producto.nombreproducto,
              producto_pedido.codigointernoproducto AS id_producto,
              valor_unitario,
              nota_producto,
              cantidad_entregada,
              valor_total,
              producto.codigointernoproducto,
              nota_producto
        FROM
              producto_pedido
        INNER JOIN producto ON producto_pedido_pos.codigointernoproducto = producto.codigointernoproducto
        WHERE
              numero_de_pedido = '$numero_pedido'
        ORDER BY
              id
        DESC
        ");
        return $datos->getResultArray();
    }

    public function producto_pedido_pos($pk_pedido_pos)
    {
        $datos = $this->db->query("
        SELECT
            producto_pedido_pos.id as id,
            cantidad_producto,
            nota_producto,
            valor_unitario,
            valor_total,
            producto.codigointernoproducto,
            producto.nombreproducto
        FROM
            producto_pedido_pos
        INNER JOIN producto ON producto_pedido_pos.codigointernoproducto = producto.codigointernoproducto
        WHERE
            pk_pedido_pos = '$pk_pedido_pos' order by id DESC
        ");
        return $datos->getResultArray();
    }

    public function id_categoria($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
             DISTINCT id_categoria
        FROM
             producto_pedido_pos where pk_pedido_pos='$numero_pedido' order by id_categoria asc
        ");
        return $datos->getResultArray();
    }
    public function pre_factura($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
        producto_pedido_pos.id,
             producto.nombreproducto,
             producto.valorventaproducto,
             valor_total,
             cantidad_producto,
             nota_producto,
             valor_unitario,
             producto_pedido_pos.codigointernoproducto
        FROM
             producto_pedido_pos
        INNER JOIN producto ON producto_pedido_pos.codigointernoproducto = producto.codigointernoproducto
        where pk_pedido_pos=$numero_pedido order by id asc;
        ");
        return $datos->getResultArray();
    }


    public function productos_pedido($numero_pedido, $id_categoria)
    {
        $datos = $this->db->query("
        SELECT
            id,
             producto.nombreproducto,
             producto.valorventaproducto,
             valor_total,
             cantidad_producto,
             nota_producto,
             valor_unitario,
             producto_pedido_pos.codigointernoproducto
        FROM
             producto_pedido_pos
        INNER JOIN producto ON producto_pedido_pos.codigointernoproducto = producto.codigointernoproducto
        where pk_pedido_pos='$numero_pedido' and id_categoria='$id_categoria' and se_imprime_en_comanda='true'  and impreso_en_comanda='false' order by id asc;
        ");
        return $datos->getResultArray();
    }
    public function productos_pedido_pos($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
            producto_pedido_pos.id,
            cantidad_producto,
            valor_unitario,
            valor_total,
            producto_pedido_pos.codigointernoproducto,
            producto.nombreproducto,
            nota_producto
        FROM
            producto_pedido_pos
        INNER JOIN producto ON producto_pedido_pos.codigointernoproducto = producto.codigointernoproducto
        WHERE
            pk_pedido_pos = '$numero_pedido'
        ");
        return $datos->getResultArray();
    }
    public function codigo_id($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
            id,
            codigointernoproducto
        FROM
            producto_pedido_pos
        WHERE
            pk_pedido_pos = $numero_pedido
        ");
        return $datos->getResultArray();
    }
    public function valor_total_producto($numero_pedido, $id_producto)
    {
        $datos = $this->db->query("
        SELECT
            valor_total
        FROM
            producto_pedido_pos
        WHERE
            pk_pedido_pos = $numero_pedido and id =$id_producto
        ");
        return $datos->getResultArray();
    }

    public function imprimir_productos_pedido_pos($numero_pedido, $id_categoria)
    {
        $datos = $this->db->query("
        SELECT
            producto_pedido_pos.id,
             producto.nombreproducto,
             producto.valorventaproducto,
             valor_total,
             cantidad_producto,
             nota_producto,
             valor_unitario,
             producto_pedido_pos.codigointernoproducto
        FROM
            producto_pedido_pos
        INNER JOIN producto ON producto_pedido_pos.codigointernoproducto = producto.codigointernoproducto
        where pk_pedido_pos='$numero_pedido'  and se_imprime_en_comanda='TRUE'  and impreso_en_comanda='FALSE' order by id asc;
        
        ");
        return $datos->getResultArray();
    }
}
