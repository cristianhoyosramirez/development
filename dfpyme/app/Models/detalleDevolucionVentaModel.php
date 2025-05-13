<?php

namespace App\Models;

use CodeIgniter\Model;

class detalleDevolucionVentaModel extends Model
{
    protected $table      = 'detalle_devolucion_venta';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['id_devolucion_venta', 'codigo', 'idmedida', 'idcolor', 'valor', 'descuento', 'iva', 'cantidad', 'impoconsumo', 'ico', 'valor_total_producto', 'fecha_y_hora_venta', 'fecha_venta', 'id_apertura'];

    public function detalle_devolucion($id_apertura)
    {
        $datos = $this->db->query("
        SELECT
            producto.nombreproducto,
            valor_total_producto / cantidad AS valor_unitario,
            valor_total_producto,
            codigo,
            cantidad
        FROM
            detalle_devolucion_venta
        INNER JOIN producto ON detalle_devolucion_venta.codigo = producto.codigointernoproducto
        WHERE
        id_apertura = $id_apertura
        ");
        return $datos->getResultArray();
    }

    public function getDevoluciones($id_apertura)
    {
        $datos = $this->db->query("
       select * from detalle_devolucion_venta where id_apertura= $id_apertura
        ");
        return $datos->getResultArray();
    }
}
