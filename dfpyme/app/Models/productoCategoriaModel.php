<?php

namespace App\Models;

use CodeIgniter\Model;

class productoCategoriaModel extends Model
{
    protected $table      = 'producto_catego_sub';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['id_categoria','id_sub_categoria'];

  /*   public function id_categorias($codigo_categoria)
    {
        $datos = $this->db->query("
        SELECT DISTINCT id_sub_categoria
        FROM producto_catego_sub
        WHERE id_categoria='$codigo_categoria' 
        ");
        return $datos->getResultArray();
    } */
     public function id_categorias($codigo_categoria)
    {
        $datos = $this->db->query("
        SELECT id,
                nombre
        FROM   sub_categoria
        WHERE  id_categoria = '$codigo_categoria' 
        ");
        return $datos->getResultArray();
    } 

    public function sub_categorias($codigo_categoria, $id_sub_categoria)
    {
        $datos = $this->db->query("
        SELECT *
        FROM producto_catego_sub
        WHERE id_categoria = '$codigo_categoria'
          AND id_sub_categoria= $id_sub_categoria
        ");
        return $datos->getResultArray();
    }
   
}