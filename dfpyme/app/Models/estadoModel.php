<?php

namespace App\Models;

use CodeIgniter\Model;

class estadoModel extends Model
{
    protected $table      = 'estado';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['descripcionestado', 'estado', 'orden','consulta'];

    public function estados()
    {
        $datos = $this->db->query("
        SELECT
            *
         FROM
            estado
        WHERE
            estado = TRUE
        ORDER BY
            orden ASC

       ");
        return $datos->getResultArray();
    }
    public function consultar_ventas()
    {
        $datos = $this->db->query("
       SELECT *
        FROM estado
        WHERE  consulta = 'true'

       ");
        return $datos->getResultArray();
    }
}
