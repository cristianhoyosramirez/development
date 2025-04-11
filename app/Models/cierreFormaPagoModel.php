<?php

namespace App\Models;

use CodeIgniter\Model;

class cierreFormaPagoModel extends Model
{
    protected $table      = 'cierre_forma_pago';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['idcierre', 'idpago', 'valor'];


    public function cierre_efectivo($id_cierre)
    {
        $datos = $this->db->query("
        SELECT
            valor
        FROM
            cierre_forma_pago
        WHERE
            idcierre = $id_cierre AND idpago = 1
         ");
        return $datos->getResultArray();
    }
    public function cierre_transaccion($id_cierre)
    {
        $datos = $this->db->query("
        SELECT
            valor
        FROM
            cierre_forma_pago
        WHERE
            idcierre = $id_cierre AND idpago = 4
         ");
        return $datos->getResultArray();
    }
    public function fecha_cierre($id_cierre)
    {
        $datos = $this->db->query("
        SELECT
            fecha
        FROM
            cierre
        WHERE
            id = $id_cierre
         ");
        return $datos->getResultArray();
    }
    public function hora_cierre($id_cierre)
    {
        $datos = $this->db->query("
        SELECT
            hora
        FROM
            cierre
        WHERE
            id = $id_cierre
         ");
        return $datos->getResultArray();
    }
    public function formas_pago_cierre($id_cierre)
    {
        $datos = $this->db->query("
        select valor,
            forma_pago.nombreforma_pago
        from cierre_forma_pago 
        inner join forma_pago on cierre_forma_pago.idpago=forma_pago.idforma_pago where idcierre=$id_cierre
         ");
        return $datos->getResultArray();
    }
    public function valor_cierre_efectivo_usuario($id_cierre)
    {
        $datos = $this->db->query("
        select valor
        from cierre_forma_pago 
        where idcierre=$id_cierre and idpago=1
         ");
        return $datos->getResultArray();
    }
    public function valor_cierre_transaccion_usuario($id_cierre)
    {
        $datos = $this->db->query("
        select valor
        from cierre_forma_pago 
        where idcierre=$id_cierre and idpago=4
         ");
        return $datos->getResultArray();
    }
    public function actualizar_transaccion($id_cierre,$valor)
    {
        $datos = $this->db->query("
        UPDATE cierre_forma_pago
        SET  valor='$valor'
        WHERE idcierre='$id_cierre' and idpago=4;
         ");
        //return $datos->getResultArray();
    }
}
