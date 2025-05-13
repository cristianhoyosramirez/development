<?php

namespace App\Models;

use CodeIgniter\Model;

class configuracionAtributosProductoModel extends Model
{
    protected $table      = 'producto_atributos';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['id_producto', 'id_atributo', 'numero_componentes'];

    public function atributosProducto($idProducto)
    {
        $datos = $this->db->query("
        SELECT atributos.nombre,atributos.id
FROM   producto_atributos
       INNER JOIN atributos
               ON atributos.id = producto_atributos.id_atributo
WHERE  id_producto = $idProducto
        ");
        return $datos->getResultArray();
    }

    public function existeAtributosProducto($idProducto, $idAtributo)
    {
        $datos = $this->db->query("
            SELECT
                *
            FROM
                producto_atributos
            WHERE
                id_atributo = $idAtributo AND id_producto = $idProducto
        ");
        return $datos->getResultArray();
    }

    public function getAtributos($idProducto)
    {
        $datos = $this->db->query("
           SELECT DISTINCT
                (id_atributo)
            FROM
                producto_atributos
            WHERE
                id_producto = $idProducto
        ");
        return $datos->getResultArray();
    }
    public function getNumeroComponentes($idProducto, $idAtributo)
    {
        $datos = $this->db->query("
        SELECT
            numero_componentes
        FROM
            producto_atributos
        WHERE
            id_producto = $idProducto AND id_atributo = $idAtributo
        ");
        return $datos->getResultArray();
    }
    public function getId($idProducto, $idAtributo)
    {
        $datos = $this->db->query("
        SELECT
            id
        FROM
            producto_atributos
        WHERE
            id_producto = $idProducto AND id_atributo = $idAtributo
        ");
        return $datos->getResultArray();
    }

    public function getIdComponentes($id_tabla_producto, $id_atributo)
    {
        $datos = $this->db->query("
        SELECT componentes.nombre, atributos_producto.id 
        FROM atributos_producto 
        INNER JOIN componentes ON componentes.id = atributos_producto.id_componente 
        WHERE atributos_producto.id_tabla_producto = $id_tabla_producto 
        AND atributos_producto.id_atributo = $id_atributo;
        ");
        return $datos->getResultArray();
    }
    public function geterIdComponentes($id_tabla_producto, $id_componente)
    {
        $datos = $this->db->query("
        SELECT componentes.nombre, atributos_producto.id 
        FROM atributos_producto 
        INNER JOIN componentes ON componentes.id = atributos_producto.id_componente 
        WHERE atributos_producto.id_tabla_producto = $id_tabla_producto 
        AND atributos_producto.id_componente = $id_componente;
        ");
        return $datos->getResultArray();
    }
}
