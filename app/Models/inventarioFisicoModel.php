<?php

namespace App\Models;

use CodeIgniter\Model;

class inventarioFisicoModel extends Model
{
    protected $table      = 'inventario_fisico';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_inventario_fisico',
        'fecha_inventario_fisico',
        'codigointernoproducto',
        'idvalor_unidad_medida',
        'idcolor',
        'cantidad_inventario_fisico',
        'corte_inventario_fisico',
        'numero_corte',
        'costo'
    ];

    public function existeProducto($codigo)
    {
        $datos = $this->db->query("
        SELECT   
    *
FROM 
    inventario_fisico
WHERE 
    codigointernoproducto = '$codigo' and corte_inventario_fisico='false'
         ");
        return $datos->getResultArray();
    }
}
