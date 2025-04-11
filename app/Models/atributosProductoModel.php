<?php

namespace App\Models;

use CodeIgniter\Model;

class atributosProductoModel extends Model
{
    protected $table      = 'atributos';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['nombre'];

    public function atributos($valor)
    {
        $datos = $this->db->query("
        SELECT   *
        FROM     atributos
        WHERE    nombre ilike '%$valor%'
        ORDER BY nombre ASC;
         ");
        return $datos->getResultArray();
    }
   
}