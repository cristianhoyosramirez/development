<?php

namespace App\Models;

use CodeIgniter\Model;

class consecutivosModel extends Model
{
    protected $table      = 'consecutivos';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['idconsecutivos','conceptoconsecutivo','numeroconsecutivo','serie'];

    public function update_serie($incremento)
    {
        $datos = $this->db->query("
        UPDATE
        consecutivos
    SET
        numeroconsecutivo = '$incremento'
    WHERE
        idconsecutivos = 14
        ");
       // return $datos->getResultArray();
    }

    public function actualizar_consecutivos($incremento)
    {
        $datos = $this->db->query("
        UPDATE
        consecutivos
    SET
        numeroconsecutivo = '$incremento'
    WHERE
        idconsecutivos = 11
        ");
       // return $datos->getResultArray();
    }
   
}