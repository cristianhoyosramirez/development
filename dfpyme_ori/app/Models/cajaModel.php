<?php

namespace App\Models;

use CodeIgniter\Model;

class cajaModel extends Model
{
    protected $table      = 'caja ';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['numerocaja', 'estado', 'consecutivo','requiere_lista_de_precios','imp_comprobante_transferencia','consecutivo_factura_electronica','id_impresora'];


    public function forma_pago_cierre($id_cierre)
    {
        $datos = $this->db->query("
        SELECT DISTINCT idpago
        FROM cierre_forma_pago where idcierre =$id_cierre; 
         ");
        return $datos->getResultArray();
    }
    public function nombre_forma_pago_cierre($id_cierre, $id_pago)
    {
        $datos = $this->db->query("
        SELECT
            nombreforma_pago, valor
        FROM
            cierre_forma_pago
        INNER JOIN forma_pago ON cierre_forma_pago.idpago = forma_pago.idforma_pago
        WHERE
            cierre_forma_pago.idcierre = $id_cierre
         ");
        return $datos->getResultArray();
    }
    public function forma_pago_retiro($id_cierre)
    {
        $id_cierre = $id_cierre['id'];
        $datos = $this->db->query("
        SELECT
            concepto,
            valor,
            retiro.fecha,
            retiro.hora
        FROM
            retiro_forma_pago
        INNER JOIN forma_pago ON retiro_forma_pago.idpago = forma_pago.idforma_pago
        INNER JOIN retiro ON retiro.id = retiro_forma_pago.idretiro
        WHERE
            retiro_forma_pago.idretiro = $id_cierre
         ");
        return $datos->getResultArray();
    }
}
