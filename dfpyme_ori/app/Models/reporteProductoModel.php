<?php

namespace App\Models;

use CodeIgniter\Model;

class reporteProductoModel extends Model
{
    protected $table      = 'reporte_ventas_producto_categorias';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['cantidad', 'nombre_producto','precio_venta','valor_total','id_categoria','codigo_interno_producto','valor_unitario'];

    public function categorias()
    {
        $datos = $this->db->query("
            select distinct(id_categoria)  from reporte_ventas_producto_categorias 
         ");
        return $datos->getResultArray();
    }

}
