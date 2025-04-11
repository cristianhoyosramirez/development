<?php

namespace App\Models;

use CodeIgniter\Model;

class subCategoriaModel extends Model
{
    protected $table      = 'sub_categoria';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['nombre','id_categoria'];

    public function get_productos($id_subcategoria)
    {
        $datos = $this->db->query("
        SELECT
        id,
        codigointernoproducto,
        nombreproducto,
        valorventaproducto,
        estadoproducto,
        codigocategoria,
        id_tipo_inventario
    FROM
        producto
    WHERE
    id_subcategoria = '$id_subcategoria' AND estadoproducto = 'true' AND id_tipo_inventario = '1'  AND estadoproducto = 'true' OR id_tipo_inventario = '3'
    ORDER BY
        nombreproducto ASC
        ");
        return $datos->getResultArray();
    }
    public function get_productos_sub_categoria($id_subcategoria)
    {
        $datos = $this->db->query("
        SELECT
        id,
        codigointernoproducto,
        nombreproducto,
        valorventaproducto,
        estadoproducto,
        codigocategoria,
        id_tipo_inventario
    FROM
        producto
    WHERE
    id_subcategoria = '$id_subcategoria' AND estadoproducto = 'true'   
    ORDER BY
        nombreproducto ASC
        ");
        return $datos->getResultArray();
    }
}
