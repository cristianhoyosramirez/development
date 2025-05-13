<?php

namespace App\Models;

use CodeIgniter\Model;

class formaPagoModel extends Model
{
    protected $table      = 'forma_pago';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['nombreforma_pago','aplica','ruta'];

    public function getFacturas($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
            SELECT
    efectivo,
    transferencia,
    id,
    id_factura,
    id_estado,
    fecha,
    hora,
    total_documento,
    descripcionestado,
    CASE WHEN id_estado = 8 THEN(
    SELECT
        numero
    FROM
        documento_electronico
    WHERE
        documento_electronico.id = pagos.id_factura
    LIMIT 1
) ELSE(
   SELECT numerofactura_venta FROM factura_venta WHERE pagos.id_factura = factura_venta.id LIMIT 1
)
END AS numero
FROM
    pagos
INNER JOIN estado ON estado.idestado = pagos.id_estado
WHERE
    fecha BETWEEN '$fecha_inicial' AND '$fecha_final'
ORDER BY
    fecha
asc;

         ");
        return $datos->getResultArray();
    }
}