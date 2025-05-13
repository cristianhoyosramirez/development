<?php

namespace App\Models;

use CodeIgniter\Model;

class cierreModel extends Model
{
    protected $table      = 'cierre';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['idapertura','fecha', 'hora','idcaja','idturno','idusuario','fecha_y_hora_cierre'];
    
    public function cierre_efectivo($id_cierre)
    {
        $datos = $this->db->query("
        SELECT valor
        FROM   cierre_forma_pago
        WHERE  idcierre = $id_cierre
               AND idpago = 1 ;
         ");
        return $datos->getResultArray();
    }
}