<?php

namespace App\Models;

use CodeIgniter\Model;

class inventarioModel extends Model
{
    protected $table      = 'inventario';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['codigointernoproducto', 'idvalor_unidad_medida', 'idcolor', 'cantidad_inventario'];




    public function producto($valor)
    {
        $datos = $this->db->query("
        SELECT
        *
    FROM
        producto
    WHERE
        nombreproducto ILIKE '%$valor%' and estadoproducto = 'true' order by nombreproducto asc;
         ");
        return $datos->getResultArray();
    }

    public function get_categoria()
    {
        $datos = $this->db->query("
            SELECT DISTINCT codigocategoria FROM producto;
         ");
        return $datos->getResultArray();
    }

    public function impresion_invetario()
    {
        $datos = $this->db->query("
            SELECT inventario.codigointernoproducto,
                cantidad_inventario,
                nombreproducto
            FROM   inventario
            INNER JOIN producto
               ON producto.codigointernoproducto =
                  inventario.codigointernoproducto 
                ORDER BY 
    nombreproducto ASC 
         ");
        return $datos->getResultArray();
    }
    public function impresion_invetario_categorias($categoria)
    {
        $datos = $this->db->query("
            SELECT inventario.codigointernoproducto,
                cantidad_inventario,
                nombreproducto
            FROM   inventario
            INNER JOIN producto
               ON producto.codigointernoproducto =
                  inventario.codigointernoproducto 
                  where codigocategoria='$categoria'
                ORDER BY 
    nombreproducto ASC 
         ");
        return $datos->getResultArray();
    }

    public function cruce_inventario()
    {
        $datos = $this->db->query("
SELECT 
    cantidad_inventario_fisico,
    inventario_fisico.codigointernoproducto,
    nombreproducto,
    (cantidad_inventario_fisico - cantidad_inventario) AS diferencia,
    cantidad_inventario,
    (cantidad_inventario_fisico - cantidad_inventario) * precio_costo AS valor_costo,
    (cantidad_inventario_fisico - cantidad_inventario) * valorventaproducto AS valor_venta
FROM 
    inventario_fisico
INNER JOIN 
    producto 
    ON producto.codigointernoproducto = inventario_fisico.codigointernoproducto
INNER JOIN 
    inventario 
    ON inventario.codigointernoproducto = inventario_fisico.codigointernoproducto
WHERE 
    corte_inventario_fisico = 'false' 
    AND (cantidad_inventario_fisico - cantidad_inventario) != 0
ORDER BY 
    nombreproducto DESC;
;


         ");
        return $datos->getResultArray();
    }
}
