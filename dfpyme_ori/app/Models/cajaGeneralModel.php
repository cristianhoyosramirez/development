<?php

namespace App\Models;

use CodeIgniter\Model;

class cajaGeneralModel extends Model
{
    protected $table      = 'movimientos_caja_general';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['fecha_apertura', 'valor_apertura', 'fecha_cierre', 'valor_cierre'];

    public function get_fechacierre_valorcierre($id)
    {
        $datos = $this->db->query("
        SELECT
            *
        FROM movimientos_caja_general
        WHERE id = $id; 
         ");
        return $datos->getResultArray();
    }
    public function siguiente_id()
    {
        $datos = $this->db->query("
        select nextval('movimientos_caja_general_id_seq') as siguiente_id; 
         ");
        return $datos->getResultArray();
    }

   
}
