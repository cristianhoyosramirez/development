<?php

namespace App\Models;

use CodeIgniter\Model;

class atributosDeProductoModel extends Model
{
    protected $table      = 'atributos_producto';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['id_componente', 'id_tabla_producto', 'id_atributo', 'id_producto'];

    public function getNumeroComponentes($id_atributo, $id_producto)
    {
        $datos = $this->db->query("
            SELECT numero_componentes
            FROM   producto_atributos
            WHERE  id_atributo = $id_atributo
                AND id_producto = $id_producto 
         ");
        return $datos->getResultArray();
    }
    public function countNumeroComponentes($id_producto, $id_tabla_producto, $id_atributo)
    {
        $datos = $this->db->query("
           SELECT Count(id_componente) as numero_componentes
           FROM   atributos_producto
           WHERE  id_producto = $id_producto
          and id_tabla_producto= $id_tabla_producto
          and id_atributo = $id_atributo
         ");
        return $datos->getResultArray();
    }
    public function getAtributos($id_tabla_producto)
    {
        $datos = $this->db->query("

            select distinct (id_atributo) from atributos_producto where id_tabla_producto=$id_tabla_producto
            
         ");
        return $datos->getResultArray();
    }
    public function getAtributosPedido($id_tabla_producto)
    {
        $datos = $this->db->query("
            SELECT DISTINCT
                (id_atributo),
                nombre
            FROM
                atributos_producto
            INNER JOIN atributos ON atributos_producto.id_atributo = atributos.id
            WHERE
                id_tabla_producto = $id_tabla_producto
         ");
        return $datos->getResultArray();
    }
    public function getComponentes($id_tabla_producto,$id_atributo)
    {
        $datos = $this->db->query("
            SELECT componentes.nombre  
            FROM atributos_producto  
            INNER JOIN componentes ON componentes.id = atributos_producto.id_componente  
            WHERE atributos_producto.id_tabla_producto = $id_tabla_producto 
            AND atributos_producto.id_atributo = $id_atributo  
            ORDER BY componentes.nombre ASC;
         ");
        return $datos->getResultArray();
    }
}
