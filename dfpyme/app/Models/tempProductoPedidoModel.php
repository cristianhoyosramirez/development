<?php

namespace App\Models;

use CodeIgniter\Model;

class tempProductoPedidoModel extends Model
{
    protected $table      = 'temp_producto_pedido';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'numero_de_pedido',
        'cantidad_producto',
        'nota_producto',
        'valor_unitario',
        'valor_total',
        'se_imprime_en_comanda',
        'codigo_categoria',
        'codigointernoproducto'
    ];


    public function borrar_productos($pedido,  $codigo_categoria)
    {
        $datos = $this->db->query("
        delete from temp_producto_pedido where numero_de_pedido = $pedido and codigo_categoria= '$codigo_categoria'
    ");
    }

    public function productos_pedido($numero_pedido, $categoria)
    {
        $datos = $this->db->query("
        SELECT
            temp_producto_pedido.id as id,
            producto.nombreproducto,
            producto.valorventaproducto,
            valor_total,
            cantidad_producto,
            nota_producto,
            valor_unitario,
            temp_producto_pedido.codigointernoproducto
        FROM
             temp_producto_pedido
        INNER JOIN producto ON temp_producto_pedido.codigointernoproducto = producto.codigointernoproducto
        where numero_de_pedido='$numero_pedido'  and se_imprime_en_comanda='true' and codigo_categoria='$categoria' order by id asc;
        ");
        return $datos->getResultArray();
    }
}
