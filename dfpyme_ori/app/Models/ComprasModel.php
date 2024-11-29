<?php

namespace App\Models;

use CodeIgniter\Model;

class ComprasModel extends Model
{
    protected $table      = 'producto_factura_proveedor';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'numeroconsecutivofactura_proveedor',
        'codigointernoproducto',
        'idvalor_unidad_medida',
        'idcolor',
        'idlote',
        'cantidadproducto_factura_proveedor',
        'valoringresoproducto_factura_proveedor',
        'ivaproducto_factura_proveedor',
        'descuento',
        'impoconsumo',
        'inventario_anterior',
        'inventario_actual'
    ];

    public function insertar($data)
    {
        $datos = $this->db->table('producto_factura_proveedor_temp');
        $datos->insert($data);

        return $this->db->insertID();
    }

    public function productos($id_usuario)
    {
        $datos = $this->db->query("
         SELECT
            producto_factura_proveedor_temp.id,
            codigo_producto as codigointernoproducto,
            cantidad,
            valor,
            producto.nombreproducto
        FROM
            producto_factura_proveedor_temp
        INNER JOIN producto ON producto.codigointernoproducto = producto_factura_proveedor_temp.codigo_producto where id_usuario=$id_usuario
        order by id desc
         ");
        return $datos->getResultArray();
    }
    public function borrado($id_usuario)
    {
        $datos = $this->db->query("
            delete from producto_factura_proveedor_temp where id_usuario=$id_usuario
         ");
        //return $datos->getResultArray();
    }
    public function total_compra_proveedor($id_compra)
    {
        $datos = $this->db->query("
SELECT 
    SUM(cantidadproducto_factura_proveedor * valoringresoproducto_factura_proveedor) AS total_compra 
FROM 
    producto_factura_proveedor 
WHERE 
    numeroconsecutivofactura_proveedor = $id_compra;

         ");
        return $datos->getResultArray();
    }

    public function eliminar($data)
    {
        $delete = $this->db->table('producto_factura_proveedor_temp');
        $delete->where($data);
        return $delete->delete();
    }

    public function numero_articulos($id_usuario)
    {
        $articulos = $this->db->query("SELECT sum(cantidad) as total_productos FROM producto_factura_proveedor_temp where id_usuario=$id_usuario");
        return $articulos->getResultArray();
    }
    public function total($id_usuario)
    {
        $articulos = $this->db->query("SELECT sum(cantidad*valor) as total FROM producto_factura_proveedor_temp where id_usuario=$id_usuario");
        return $articulos->getResultArray();
    }

    public function actualizar_producto($data, $id)
    {
        // Accede directamente a la tabla usando el método update()
        return $this->db->table('producto_factura_proveedor_temp')
            ->where('id', $id)
            ->update($data); // Actualiza los datos
    }

    public function cantidad($id)
    {
        $cantidad = $this->db->query("SELECT cantidad   FROM producto_factura_proveedor_temp where id=$id");
        return $cantidad->getResultArray();
    }
    public function valor_unitario($id)
    {
        $cantidad = $this->db->query("SELECT valor   FROM producto_factura_proveedor_temp where id=$id");
        return $cantidad->getResultArray();
    }

    public function buscar_por_codigo($codigo)
    {
        $cantidad = $this->db->query("SELECT
                                        codigointernoproducto,
                                        nombreproducto,
                                        id_tipo_inventario
                                    FROM
                                        producto
                                    WHERE
                                        codigointernoproducto = '$codigo' OR codigobarrasproducto = '$codigo'");
        return $cantidad->getResultArray();
    }

    public function eliminar_productos($data)
    {
        $delete = $this->db->table('producto_factura_proveedor_temp');
        $delete->where($data);
        return $delete->delete();
    }

    public function total_compra($id_factura)
    {
        $compra = $this->db->query("SELECT sum(cantidadproducto_factura_proveedor*valoringresoproducto_factura_proveedor) as total_compra FROM producto_factura_proveedor where numeroconsecutivofactura_proveedor=$id_factura");
        return $compra->getResultArray();
    }


    public function productos_compra($id_entrada)
    {
        $datos = $this->db->query("
       SELECT 
                                        cantidadproducto_factura_proveedor AS cantidad, 
                                        valoringresoproducto_factura_proveedor AS valor_unitario,
                                        nombreproducto,
                                         producto_factura_proveedor.codigointernoproducto
                                    FROM 
                                        producto_factura_proveedor
                                    INNER JOIN 
                                        producto 
                                    ON 
                                        producto.codigointernoproducto = producto_factura_proveedor.codigointernoproducto
                                        WHERE numeroconsecutivofactura_proveedor=$id_entrada
         ");
        return $datos->getResultArray();
    }



    public function total_productos_compra($id_entrada)
    {
        $compra = $this->db->query("
                                   SELECT 
                                        sum(cantidadproducto_factura_proveedor * valoringresoproducto_factura_proveedor) as total_factura
                                       
                                    FROM 
                                        producto_factura_proveedor
                                        WHERE numeroconsecutivofactura_proveedor=$id_entrada

        ");
        return $compra->getResultArray();
    }
    public function codigo_proveedor($id_entrada)
    {
        $compra = $this->db->query("
                                   SELECT 
                                        codigointernoproveedor
                                    FROM 
                                        factura_proveedor
                                        WHERE numeroconsecutivofactura_proveedor=$id_entrada

        ");
        return $compra->getResultArray();
    }
    public function fecha_compra($id_entrada)
    {
        $compra = $this->db->query("
                                   SELECT 
                                        fecha_factura
                                    FROM 
                                        factura_proveedor
                                        WHERE numeroconsecutivofactura_proveedor=$id_entrada

        ");
        return $compra->getResultArray();
    }
    public function cantidad_producto_compra($id)
    {
        $compra = $this->db->query("
                                   SELECT 
                                        cantidad,valor,id_usuario
                                    FROM 
                                        producto_factura_proveedor_temp
                                        WHERE id=$id
        ");
        return $compra->getResultArray();
    }


    public function getProductosCompra($id, $codigo)
    {
        $compra = $this->db->query("
                   SELECT
    producto_factura_proveedor.cantidadproducto_factura_proveedor,
    producto.nombreproducto,
    producto_factura_proveedor.inventario_anterior,
    producto_factura_proveedor.inventario_actual,
    producto_factura_proveedor.cantidadproducto_factura_proveedor AS cantidad_movimiento,
    factura_proveedor.hora
FROM
    producto_factura_proveedor
INNER JOIN 
    producto 
ON 
    producto.codigointernoproducto = producto_factura_proveedor.codigointernoproducto
INNER JOIN 
    factura_proveedor 
ON 
    factura_proveedor.numeroconsecutivofactura_proveedor = producto_factura_proveedor.numeroconsecutivofactura_proveedor
WHERE
    producto_factura_proveedor.numeroconsecutivofactura_proveedor = $id 
    AND producto_factura_proveedor.codigointernoproducto = '$codigo';

        ");
        return $compra->getResultArray();
    }
}
