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

    public function inventario()
    {
        $datos = $this->db->query("
       SELECT inventario.codigointernoproducto,
            cantidad_inventario,
            nombreproducto,valorventaproducto,precio_costo
        FROM   inventario
        INNER JOIN producto
               ON producto.codigointernoproducto =
                  inventario.codigointernoproducto
        ORDER  BY nombreproducto ASC 
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
    public function conteo_manual($codigoProducto)
    {
        $datos = $this->db->query("
SELECT 
    cantidad_inventario_fisico,
    inventario_fisico.codigointernoproducto,
    nombreproducto,
    producto.id,
    (cantidad_inventario_fisico - 
        CASE 
            WHEN cantidad_inventario < 0 THEN -cantidad_inventario 
            ELSE cantidad_inventario 
        END
    ) AS diferencia,
    cantidad_inventario,
    (cantidad_inventario_fisico - 
        CASE 
            WHEN cantidad_inventario < 0 THEN -cantidad_inventario 
            ELSE cantidad_inventario 
        END
    ) * precio_costo AS valor_costo,
    (cantidad_inventario_fisico - 
        CASE 
            WHEN cantidad_inventario < 0 THEN -cantidad_inventario 
            ELSE cantidad_inventario 
        END
    ) * valorventaproducto AS valor_venta
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
    AND inventario_fisico.codigointernoproducto = '$codigoProducto'
ORDER BY 
    nombreproducto DESC;
;


         ");
        return $datos->getResultArray();
    }
    public function sobrantes()
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
    AND (cantidad_inventario_fisico - cantidad_inventario) > 0
ORDER BY 
    nombreproducto DESC;
;


         ");
        return $datos->getResultArray();
    }
    public function cruce_faltantes()
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
    AND (cantidad_inventario_fisico - cantidad_inventario) < 0
ORDER BY 
    nombreproducto DESC;
;


         ");
        return $datos->getResultArray();
    }

    public function getProducto($codigoProducto)
    {
        $datos = $this->db->query("

SELECT 
    
    nombreproducto,precio_costo,valorventaproducto,inventario.cantidad_inventario
    
FROM 
inventario
    INNER JOIN producto on producto.codigointernoproducto = inventario.codigointernoproducto
    where inventario.codigointernoproducto='$codigoProducto';

         ");
        return $datos->getResultArray();
    }
    public function getInventarioFisico()
    {
        $datos = $this->db->query("
        SELECT
            *
        FROM 
        inventario_fisico
            where corte_inventario_fisico='false';
         ");
        return $datos->getResultArray();
    }
    public function getFechaCorte()
    {
        $datos = $this->db->query("
        select max(numero_corte) as corte ,max(fecha_inventario_fisico) as fecha from inventario_fisico
         ");
        return $datos->getResultArray();
    }

    public function updateCorte()
    {
        // Ejecuta la consulta de actualización
        $this->db->query("
        UPDATE inventario_fisico
        SET corte_inventario_fisico = 'true'
        WHERE corte_inventario_fisico = 'false';
    ");

        // Verifica si se actualizaron filas
        if ($this->db->affectedRows() > 0) {
            return true; // La actualización fue exitosa
        } else {
            return false; // No se actualizó ninguna fila
        }
    }
}
