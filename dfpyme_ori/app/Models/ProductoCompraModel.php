<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoCompraModel extends Model
{
    protected $table      = 'producto_factura_proveedor_temp';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [

        'idfactura',
        'codigo_producto',
        'idmedida',
        'idcolor',
        'idlote',
        'cantidad',
        'valor',
        'iva',
        'descuento',
        'descuento_2',
        'id_usuario',
    ];

    public function productos_compra($id_usuario)
    {
        $datos = $this->db->query("
 SELECT 
    producto.nombreproducto, 
    producto_factura_proveedor_temp.cantidad, 
    producto_factura_proveedor_temp.valor AS valor_unitario, 
    producto_factura_proveedor_temp.codigo_producto AS codigointernoproducto
FROM 
    producto_factura_proveedor_temp 
INNER JOIN 
    producto 
ON 
    producto.codigointernoproducto = producto_factura_proveedor_temp.codigo_producto
WHERE 
    producto_factura_proveedor_temp.id_usuario = $id_usuario;

        ");
        return $datos->getResultArray();
    }

    public function total_compra($id_usuario)
    {
        $datos = $this->db->query("
  SELECT 
    sum(cantidad * valor) as total_factura
FROM 
    producto_factura_proveedor_temp 
WHERE 
    producto_factura_proveedor_temp.id_usuario = $id_usuario;

        ");
        return $datos->getResultArray();
    }


}
