<?php

namespace App\Models;

use CodeIgniter\Model;

class MovimientoInsumosModel extends Model
{
    protected $table      = 'movimiento_insumos';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'fecha',
        'hora',
        'id_producto',
        'inventario_anterior',
        'inventario_actual',
        'id_doc',
        'tipo_doc',
        'id_pro_prin'
    ];

    public function producto($id_doc, $id_producto)
    {
        $datos = $this->db->query("
       SELECT 
            movimiento_insumos.fecha, 
            movimiento_insumos.hora, 
            inventario_anterior, 
            inventario_actual, 
            nombreproducto,
            numero,
            id_pro_prin,
            documento_electronico.hora
        FROM 
        movimiento_insumos
        inner join documento_electronico on documento_electronico.id = movimiento_insumos.id_doc
        INNER JOIN 
        producto 
        ON 
        producto.id = movimiento_insumos.id_producto
        WHERE 
        id_doc = $id_doc
        AND id_producto = $id_producto;

        ");
        return $datos->getResultArray();
    }
    public function idUsuario($idDoc)
    {
        $datos = $this->db->query("
            select nombresusuario_sistema from pagos
            inner join usuario_sistema on usuario_sistema.idusuario_sistema = pagos.id_usuario_facturacion
            where id_factura = $idDoc and id_estado = 8;

        ");
        return $datos->getResultArray();
    }
}
