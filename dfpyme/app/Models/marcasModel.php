<?php

namespace App\Models;

use CodeIgniter\Model;

class marcasModel extends Model
{
    protected $table      = 'marca';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['nombremarca'];

    public function marcas($valor)
    {
        $datos = $this->db->query("
        SELECT
             idmarca ,
            nombremarca 
        FROM
            marca
        WHERE
            nombremarca ILIKE '%$valor%';
         ");
        return $datos->getResultArray();
    }
}
