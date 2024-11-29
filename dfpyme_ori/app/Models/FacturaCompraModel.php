<?php

namespace App\Models;

use CodeIgniter\Model;

class FacturaCompraModel extends Model
{
    protected $table      = 'factura_proveedor';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'codigointernoproveedor',
        'idestado',
        'idcaja',
        'idusuario_sistema',
        'numerofactura_proveedor',
        'limitepagofactura_proveedor',
        'fechaingresofactura_proveedor',
        'estadofactura_proveedor',
        'descuento',
        'cancel',
        'esfactura',
        'valor_ajuste',
        'fecha_factura',
        'nota',
        'hora'
    ];

    public function getUsuario($id)
    {
        $compra = $this->db->query("
                                  SELECT
    nombresusuario_sistema
FROM
    factura_proveedor
INNER JOIN usuario_sistema ON usuario_sistema.idusuario_sistema = factura_proveedor.idusuario_sistema
WHERE
    numeroconsecutivofactura_proveedor = $id
        ");
        return $compra->getResultArray();
    }
}
