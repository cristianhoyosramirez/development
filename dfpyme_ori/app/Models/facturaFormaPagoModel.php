<?php

namespace App\Models;

use CodeIgniter\Model;

class facturaFormaPagoModel extends Model
{
    protected $table      = 'factura_forma_pago';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'numerofactura_venta', 'idusuario', 'idcaja', 'idforma_pago', 'fechafactura_forma_pago', 'hora', 'valorfactura_forma_pago', 'idturno', 'valor_pago', 'id_factura',
        'fecha_y_hora_forma_pago'
    ];
    public function id_forma_pago($id_factura)
    {
        $datos = $this->db->query("
    SELECT DISTINCT
        idforma_pago
     FROM
        factura_forma_pago
    WHERE
    id_factura = $id_factura
    ");
        return $datos->getResultArray();
    }
    public function nombre_forma_pago($id_forma_pago)
    {
        $datos = $this->db->query("
        SELECT
        nombreforma_pago
    FROM
        forma_pago
    WHERE
        idforma_pago = $id_forma_pago
    ");
        return $datos->getResultArray();
    }
    public function valor_forma_pago($id_forma_pago, $id_factura)
    {
        $datos = $this->db->query("
        SELECT
        valor_pago
    FROM
        factura_forma_pago
    WHERE
        idforma_pago = $id_forma_pago AND id_factura = $id_factura
    ");
        return $datos->getResultArray();
    }
    public function ingresos_efectivo($fecha_apertura, $fecha_cierre)
    {
        $datos = $this->db->query("
        SELECT
            SUM(valorfactura_forma_pago) as ingresos_efectivo
        FROM factura_forma_pago
        WHERE
            fecha_y_hora_forma_pago BETWEEN '$fecha_apertura' AND '$fecha_cierre' AND idforma_pago = 1
    ");
        return $datos->getResultArray();
    }
    public function ingresos_transaccion($fecha_apertura, $fecha_cierre)
    {
        $datos = $this->db->query("
        SELECT
            SUM(valorfactura_forma_pago) as ingresos_transaccion
        FROM factura_forma_pago
        WHERE
            fecha_y_hora_forma_pago BETWEEN '$fecha_apertura' AND '$fecha_cierre' AND idforma_pago = 4
    ");
        return $datos->getResultArray();
    }
    public function total_ingresos($fecha_apertura, $fecha_cierre)
    {
        $datos = $this->db->query("
        SELECT
            SUM(valorfactura_forma_pago) as total_ingresos
        FROM factura_forma_pago
        WHERE
            fecha_y_hora_forma_pago BETWEEN '$fecha_apertura' AND '$fecha_cierre'
    ");
        return $datos->getResultArray();
    }
    public function forma_pago($id_factura)
    {
        $datos = $this->db->query("
        SELECT
            nombreforma_pago,
            valorfactura_forma_pago
        FROM
            factura_forma_pago
        INNER JOIN forma_pago ON factura_forma_pago.idforma_pago = forma_pago.idforma_pago
        WHERE
            id_factura = $id_factura
    ");
        return $datos->getResultArray();
    }

    public function  movimientos_efectivo($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT fechafactura_forma_pago,
            numerofactura_venta,
            hora,
            valorfactura_forma_pago
        FROM   factura_forma_pago
        WHERE  fecha_y_hora_forma_pago BETWEEN
        '$fecha_inicial' AND '$fecha_final' and idforma_pago=1
    ");
        return $datos->getResultArray();
    }
    public function  total_efectivo($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT Sum(valorfactura_forma_pago) as total
        FROM   factura_forma_pago
        WHERE  fecha_y_hora_forma_pago BETWEEN
       '$fecha_inicial' AND '$fecha_final'
       AND idforma_pago = 1 
    ");
        return $datos->getResultArray();
    }
    public function  movimientos_transaccion($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT fechafactura_forma_pago,
            numerofactura_venta,
            hora,
            valorfactura_forma_pago
        FROM   factura_forma_pago
        WHERE  fecha_y_hora_forma_pago BETWEEN
        '$fecha_inicial' AND '$fecha_final' and idforma_pago=4
    ");
        return $datos->getResultArray();
    }
    public function  total_transaccion($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT Sum(valorfactura_forma_pago) as total
        FROM   factura_forma_pago
        WHERE  fecha_y_hora_forma_pago BETWEEN
       '$fecha_inicial' AND '$fecha_final'
       AND idforma_pago = 4 
    ");
        return $datos->getResultArray();
    }
    public function  forma_pago_efectivo($id_factura)
    {
        $datos = $this->db->query("
        select valorfactura_forma_pago from factura_forma_pago where   idforma_pago=1 and id_factura=$id_factura
    ");
        return $datos->getResultArray();
    }
    public function  forma_pago_transaccion($id_factura)
    {
        $datos = $this->db->query("
        select valorfactura_forma_pago from factura_forma_pago where   idforma_pago=4 and id_factura=$id_factura
    ");
        return $datos->getResultArray();
    }

    public function  valor_pago_transaccion($id_factura)
    {
        $datos = $this->db->query("
        select valor_pago from factura_forma_pago where   idforma_pago=4 and id_factura=$id_factura
    ");
        return $datos->getResultArray();
    }
    public function  valor_pago_efectivo($id_factura)
    {
        $datos = $this->db->query("
        select valor_pago from factura_forma_pago where   idforma_pago=1 and id_factura=$id_factura
    ");
        return $datos->getResultArray();
    }


    function factura_forma_pago($numero_factura, $id_usuario, $id_forma_pago, $fecha, $hora, $valor_pago, $efectivo, $id_factura, $fecha_y_hora,$propina)
    {
        $data = [

            'numerofactura_venta' =>  $numero_factura,
            'idusuario' => $id_usuario,
            'idcaja' => 1,
            'idforma_pago' => $id_forma_pago,
            'fechafactura_forma_pago' => $fecha,
            'hora' => $hora,
            'valorfactura_forma_pago' => $valor_pago,
            'idturno' => 1,
            'valor_pago' => $efectivo,
            'id_factura' => $id_factura,
            'fecha_y_hora_forma_pago' => $fecha_y_hora
        ];

        $factura_forma_pago = $this->db->table('factura_forma_pago');
        $factura_forma_pago->insert($data);


        return $this->db->insertID();
    }
}
