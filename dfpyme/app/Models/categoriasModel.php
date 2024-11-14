<?php

namespace App\Models;

use CodeIgniter\Model;

class categoriasModel extends Model
{
    protected $table      = 'categoria';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['codigocategoria', 'nombrecategoria', 'descripcioncategoria', 'estadocategoria', 'permitir_categoria', 'impresora','subcategoria'];


    public function categorias($valor)
    {
        $datos = $this->db->query("
        SELECT
            codigocategoria,
            nombrecategoria
        FROM
            categoria
        WHERE
            nombrecategoria ILIKE '%$valor%';
         ");
        return $datos->getResultArray();
    }
    public function sub_categorias()
    {
        $datos = $this->db->query("
            select distinct(id_categoria) from sub_categoria ;
         ");
        return $datos->getResultArray();
    }
    public function nombre_categorias($codigo_categoria)
    {
        $datos = $this->db->query("
            select nombrecategoria from categoria where codigocategoria='$codigo_categoria';
         ");
        return $datos->getResultArray();
    }
}
