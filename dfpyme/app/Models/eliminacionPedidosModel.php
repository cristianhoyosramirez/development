<?php

namespace App\Models;

use CodeIgniter\Model;

class eliminacionPedidosModel extends Model
{
    protected $table      = 'pedidos_borrados';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['numero_pedido', 'valor_pedido', 'fecha_eliminacion', 'hora_eliminacion', 'fecha_creacion','usuario_eliminacion','id_mesero'];


    public function get_total_eliminados($fecha_inicial,$fecha_final)
    {
        $datos = $this->db->query("
        SELECT Sum(valor_pedido) AS total
        FROM   pedidos_borrados
        WHERE  fecha_eliminacion BETWEEN '$fecha_inicial' AND '$fecha_final' 

        ");
        return $datos->getResultArray();
    }

}
