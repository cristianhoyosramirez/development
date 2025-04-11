<?php

namespace App\Models;

use CodeIgniter\Model;

class medioPagoModel extends Model
{
    protected $table      = 'medio_pago';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['codigo', 'nombre', 'estado', 'nombre_comercial'];

    public function getNombre($codigo)
    {
        $datos = $this->db->query("
        SELECT nombre_comercial 
        FROM medio_pago 
        WHERE codigo = '$codigo';

        ");
        return $datos->getResultArray();
    }

    public function getTotal($codigo,$id_apertura)
    {
        $datos = $this->db->query("
        SELECT SUM(total) AS total 
        FROM documento_electronico 
        WHERE medio_pago = '$codigo' AND id_apertura = $id_apertura AND id_status = 2 AND transaccion_id <> '';

        ");
        return $datos->getResultArray();
    }
    public function getTotalExcel($codigo,$id_apertura)
    {
        $datos = $this->db->query("
        SELECT SUM(total) AS total 
        FROM documento_electronico 
        WHERE medio_pago = '$codigo' AND id_apertura = $id_apertura AND id_status = 2 ;

        ");
        return $datos->getResultArray();
    }
    public function getTotalReal($codigo,$id_apertura)
    {
        $datos = $this->db->query("
        SELECT SUM(total) AS total 
        FROM documento_electronico 
        WHERE medio_pago = '$codigo' AND id_apertura = $id_apertura AND id_status = 2 ;

        ");
        return $datos->getResultArray();
    }
    public function getTotalFormas($id_apertura)
    {
        $datos = $this->db->query("
        SELECT SUM(total) AS total
FROM documento_electronico
WHERE id_apertura = $id_apertura 
  AND id_status = 2 
  AND transaccion_id IS NOT NULL 
  AND transaccion_id <> '';
;

        ");
        return $datos->getResultArray();
    }
}
