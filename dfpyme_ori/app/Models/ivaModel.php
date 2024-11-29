<?php

namespace App\Models;

use CodeIgniter\Model;

class ivaModel extends Model
{
    protected $table      = 'iva';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['idiva','valoriva', 'conceptoiva'];

    public function iva($valor)
    {
        $datos = $this->db->query("
        SELECT
             valoriva
        FROM
            iva
        WHERE
            valoriva =19;
         ");
        return $datos->getResultArray();
    }
   
}