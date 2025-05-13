<?php

namespace App\Models;

use CodeIgniter\Model;

class retiroFormaPagoModel extends Model
{
    protected $table      = 'retiro_forma_pago';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['idretiro', 'idpago', 'valor', 'concepto', 'id_cuenta_retiro', 'fecha', 'fecha_y_hora_retiro_forma_pago','id_apertura'];

    public function retiros_general($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT sum(valor) AS total
        FROM retiro_forma_pago
        WHERE fecha BETWEEN '$fecha_inicial' AND '$fecha_final'
         ");
        return $datos->getResultArray();
    }
    public function total_retiros($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT Sum(valor) as total_retiros
        FROM   retiro_forma_pago
        WHERE  fecha_y_hora_retiro_forma_pago BETWEEN '$fecha_inicial' AND '$fecha_final'
         ");
        return $datos->getResultArray();
    }
    public function retiros($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT valor,
            concepto,
            fecha,
            id
        FROM   retiro_forma_pago
        WHERE  fecha_y_hora_retiro_forma_pago BETWEEN
        '$fecha_inicial' AND '$fecha_final' 
         ");
        return $datos->getResultArray();
    }
    public function retiros_forma_pago($id_retiro)
    {
        $datos = $this->db->query("
        SELECT valor,
            concepto
        FROM retiro_forma_pago
        WHERE idretiro=$id_retiro 
         ");
        return $datos->getResultArray();
    }
}
