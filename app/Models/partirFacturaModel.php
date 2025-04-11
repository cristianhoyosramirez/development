<?php

namespace App\Models;

use CodeIgniter\Model;

class partirFacturaModel extends Model
{
    protected $table      = 'partir_factura';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['numero_de_pedido', 'cantidad_producto', 'valor_unitario', 'valor_total', 'codigointernoproducto', 'id_tabla_producto_pedido', 'fk_usuario', 'nombreproducto', 'id_tabla_producto'];

    public function productos($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
            partir_factura.id,
            numero_de_pedido,
            cantidad_producto,
            valor_unitario,
            valor_total,
            partir_factura.codigointernoproducto,
            producto.nombreproducto,
            id_tabla_producto
        FROM
            partir_factura
        INNER JOIN producto ON partir_factura.codigointernoproducto = producto.codigointernoproducto
        WHERE
            numero_de_pedido =$numero_pedido
            ORDER BY partir_factura.id ASC
        ");
        return $datos->getResultArray();
    }
    public function get_total($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT sum(valor_total) AS valor_total
        FROM partir_factura
        WHERE cantidad_producto > 0
          AND numero_de_pedido=$numero_pedido
        ");
        return $datos->getResultArray();
    }
    public function get_productos_pago_parcial($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
            *
        FROM
            partir_factura
        WHERE
            cantidad_producto > 0 AND numero_de_pedido=$numero_pedido
        ");
        return $datos->getResultArray();
    }

    public function get_productos($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT
            *
        FROM
            partir_factura
        WHERE
            numero_de_pedido = $numero_pedido AND cantidad_producto > 0
        ");
        return $datos->getResultArray();
    }
    public function propina_partida($numero_pedido)
    {
        $datos = $this->db->query("
        SELECT Sum(valor_total) as valor_total
        FROM   partir_factura
        WHERE  numero_de_pedido = $numero_pedido
        AND cantidad_producto > 0 
        ");
        return $datos->getResultArray();
    }
}
