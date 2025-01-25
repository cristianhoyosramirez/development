<?php

namespace App\Models;

use CodeIgniter\Model;

class facturaElectronicaModel extends Model
{
    protected $table      = 'documento_electronico';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'nit_cliente',
        'estado',
        'tipo',
        'tipo_factura',
        'tipo_operacion',
        'tipo_ambiente',
        'id_status',
        'numero',
        'fecha',
        'hora',
        'fecha_limite',
        'numero_items',
        'total',
        'neto',
        'moneda',
        'id_resolucion',
        'metodo_pago',
        'medio_pago',
        'fecha_pago',
        'version_ubl',
        'version_dian',
        'transaccion_id',
        'id_caja',
        'cancelled',
        'fecha_y_hora_factura_venta',
        'id_apertura',
        'propina',
        'id_apertura',
        'qrcode',
        'cufe',
        'pdf_url',
        'nota'
    ];

    public function insertar($datos)
    {
        $Nombres = $this->db->table('documento_electronico');
        $Nombres->insert($datos);

        return $this->db->insertID();
    }

    public function  dian_ceptado()
    {
        $datos = $this->db->query("
        SELECT
        COUNT(id) AS dian_aceptado
        FROM
                documento_electronico
        WHERE
         id_status = 2
    ");
        return $datos->getResultArray();
    }
    public function  dian_no_enviado()
    {
        $datos = $this->db->query("
        SELECT
        COUNT(id) AS dian_no_enviado
        FROM
                documento_electronico
        WHERE
         id_status = 1
    ");
        return $datos->getResultArray();
    }
    public function  dian_rechazado()
    {
        $datos = $this->db->query("
        SELECT
        COUNT(id) AS dian_rechazado
        FROM
                documento_electronico
        WHERE
         id_status = 3
    ");
        return $datos->getResultArray();
    }
    public function  dian_error()
    {
        $datos = $this->db->query("
        SELECT
        COUNT(id) AS dian_error
        FROM
                documento_electronico
        WHERE
         id_status = 4
    ");
        return $datos->getResultArray();
    }
    public function  dian_estado_error($id_estado)
    {
        $datos = $this->db->query("
        SELECT fecha,
            nit_cliente,
            numero,
            neto,
            cliente.nombrescliente
        FROM documento_electronico
        INNER JOIN cliente ON documento_electronico.nit_cliente = cliente.nitcliente
        WHERE id_status= $id_estado
    ");
        return $datos->getResultArray();
    }

    public function  inicial_final($id_inicial, $id_final)
    {
        $datos = $this->db->query("
     SELECT 
    (SELECT numero 
     FROM documento_electronico 
     WHERE id BETWEEN $id_inicial AND $id_final
     AND numero LIKE 'FESP%' 
     ORDER BY CAST(SUBSTRING(numero, 5, LENGTH(numero) - 4) AS INTEGER) ASC 
     LIMIT 1) AS primer_registro,
     
    (SELECT numero 
     FROM documento_electronico 
     WHERE id BETWEEN $id_inicial AND $id_final
     AND numero LIKE 'FESP%' 
     ORDER BY CAST(SUBSTRING(numero, 5, LENGTH(numero) - 4) AS INTEGER) DESC 
     LIMIT 1) AS ultimo_registro;

    ");
        return $datos->getResultArray();
    }
    public function  id_inicial_final($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT MIN(id) AS id_minimo, MAX(id) AS id_maximo
        FROM documento_electronico
            WHERE fecha BETWEEN '$fecha_inicial' AND '$fecha_final';
    ");
        return $datos->getResultArray();
    }
    public function  total_venta($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        select sum (neto) as total_venta from documento_electronico where fecha between '$fecha_inicial' and '$fecha_final';
    ");
        return $datos->getResultArray();
    }
    public function  getFacturasTrasmitidas($id_inicial, $id_final)
    {
        $datos = $this->db->query("
        SELECT id 
        FROM documento_electronico 
        WHERE id_status = 2 
        AND transaccion_id IS NOT NULL 
        AND transaccion_id != ''
        AND id BETWEEN $id_inicial AND $id_final order by id asc;
    ");
        return $datos->getResultArray();
    }
}
