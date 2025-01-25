<?php

namespace App\Models;

use CodeIgniter\Model;

class categoriasModel extends Model
{
    protected $table      = 'categoria';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['codigocategoria', 'nombrecategoria', 'descripcioncategoria', 'estadocategoria', 'permitir_categoria', 'impresora', 'subcategoria'];


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
    public function GetImpuesto($id)
    {
        $datos = $this->db->query("
           SELECT 
    ico_consumo.valor_ico 
FROM 
    producto 
INNER JOIN 
    ico_consumo 
ON 
    ico_consumo.id_ico = producto.id_ico_producto 
WHERE 
    producto.id = $id;

         ");
        return $datos->getResultArray();
    }
    public function GetIva($id)
    {
        $datos = $this->db->query("
           SELECT 
    iva.valoriva 
FROM 
    producto 
INNER JOIN 
    iva 
    ON producto.idiva = iva.idiva 
WHERE 
    producto.id = $id;


         ");
        return $datos->getResultArray();
    }
    public function Recetas($codigo)
    {
        $datos = $this->db->query("
            select 
            nombreproducto,cantidad,precio_costo as costo_unidad,precio_costo*cantidad costo_total
            from producto_fabricado
            inner join producto on producto_fabricado.prod_proceso=producto.codigointernoproducto where prod_fabricado='$codigo'
         ");
        return $datos->getResultArray();
    }
}
