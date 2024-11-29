<?php

namespace App\Models;

use CodeIgniter\Model;

class movimientosCajaGeneralModel extends Model
{
    protected $table      = 'movimientos_caja_general';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['fecha_apertura', 'valor_apertura', 'fecha_cierre', 'valor_cierre', 'hora_apertura', 'hora_cierre'];

    public function efectivo($fecha_apertura, $fecha_actual)
    {
        $datos = $this->db->query("
        SELECT sum(valorfactura_forma_pago) as efectivo
        FROM factura_forma_pago
        WHERE fecha_y_hora_forma_pago BETWEEN '$fecha_apertura' AND '$fecha_actual' and idforma_pago=1
        ");
        return $datos->getResultArray();
    }
    public function transaccion($fecha_apertura, $fecha_actual)
    {
        $datos = $this->db->query("
        SELECT sum(valorfactura_forma_pago) as transaccion
        FROM factura_forma_pago
        WHERE fecha_y_hora_forma_pago BETWEEN '$fecha_apertura' AND '$fecha_actual' and idforma_pago=4
        ");
        return $datos->getResultArray();
    }

    public function total_ingresos($fecha_apertura, $fecha_actual)
    {
        $datos = $this->db->query("
        SELECT sum(valorfactura_forma_pago) as total
        FROM factura_forma_pago
        WHERE fecha_y_hora_forma_pago BETWEEN '$fecha_apertura' AND '$fecha_actual'
        ");
        return $datos->getResultArray();
    }

    public function efectivo_cierre($fecha_apertura, $fecha_actual)
    {
        $datos = $this->db->query("
        SELECT sum(valorfactura_forma_pago) as efectivo
        FROM factura_forma_pago
        WHERE fechafactura_forma_pago BETWEEN '$fecha_apertura' AND '$fecha_actual' and idforma_pago=1
        ");
        return $datos->getResultArray();
    }
    public function transaccion_cierre($fecha_apertura, $fecha_actual)
    {
        $datos = $this->db->query("
        SELECT sum(valorfactura_forma_pago) as transaccion
        FROM factura_forma_pago
        WHERE fechafactura_forma_pago BETWEEN '$fecha_apertura' AND '$fecha_actual' and idforma_pago=4
        ");
        return $datos->getResultArray();
    }
    public function total_ingresos_cierre($fecha_apertura, $fecha_actual)
    {
        $datos = $this->db->query("
        SELECT sum(valorfactura_forma_pago) as total
        FROM factura_forma_pago
        WHERE fecha_y_hora_forma_pago BETWEEN '$fecha_apertura' AND '$fecha_actual'
        ");
        return $datos->getResultArray();
    }

    public function consultar_total_ingresos_cierre($fecha_apertura, $fecha_cierre)
    {
        $datos = $this->db->query("
        SELECT 
        fechafactura_forma_pago,
        numerofactura_venta,
            valorfactura_forma_pago,
            nombreforma_pago
        FROM factura_forma_pago
        INNER JOIN forma_pago ON forma_pago.idforma_pago=factura_forma_pago.idforma_pago
        WHERE fechafactura_forma_pago BETWEEN '$fecha_apertura' AND '$fecha_cierre'
        ");
        return $datos->getResultArray();
    }
    public function total_cierre($fecha_apertura, $fecha_cierre)
    {
        $datos = $this->db->query("
        SELECT 
            sum(valorfactura_forma_pago) as total
            
        FROM factura_forma_pago
       
        WHERE fechafactura_forma_pago BETWEEN '$fecha_apertura' AND '$fecha_cierre'
        ");
        return $datos->getResultArray();
    }
    public function imprimir_mov_efectivo($fecha_apertura, $fecha_actual)
    {
        $datos = $this->db->query("
        SELECT sum(valorfactura_forma_pago) as efectivo
        FROM factura_forma_pago
        WHERE fechafactura_forma_pago BETWEEN '$fecha_apertura' AND '$fecha_actual' and idforma_pago=1
        ");
        return $datos->getResultArray();
    }
    public function imprimir_mov_transaccion($fecha_apertura, $fecha_actual)
    {
        $datos = $this->db->query("
        SELECT sum(valorfactura_forma_pago) as transaccion
        FROM factura_forma_pago
        WHERE fechafactura_forma_pago BETWEEN '$fecha_apertura' AND '$fecha_actual' and idforma_pago=4
        ");
        return $datos->getResultArray();
    }
}
