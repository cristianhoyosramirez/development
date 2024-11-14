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
}
