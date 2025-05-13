<?php

namespace App\Models;

use CodeIgniter\Model;

class reporteProductoModel extends Model
{
    protected $table      = 'reporte_ventas_producto_categorias';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['cantidad', 'nombre_producto','precio_venta','valor_total','id_categoria','codigo_interno_producto','valor_unitario'];

    public function categorias()
    {
        $datos = $this->db->query("
            select distinct(id_categoria)  from reporte_ventas_producto_categorias 
         ");
        return $datos->getResultArray();
    }
    public function getCategorias($inicial, $final )
    {
        $datos = $this->db->query("
            SELECT DISTINCT
                (id_categoria),
                categoria.nombrecategoria 
            FROM
                kardex
            INNER JOIN categoria ON categoria.codigocategoria = kardex.id_categoria
            WHERE
                fecha_y_hora_factura_venta BETWEEN '$inicial' AND '$final'
         ");
        return $datos->getResultArray();
    }
    public function getProductosCategorias($inicial, $final,$codigoCategoria )
    {
        $datos = $this->db->query("
            SELECT
                fecha,
                hora,
                cantidad,
                valor,
                total,
                nombreproducto,
                codigo,
                valor_unitario
            FROM
                kardex
            INNER JOIN producto ON producto.codigointernoproducto = kardex.codigo
            WHERE
                fecha_y_hora_factura_venta BETWEEN '$inicial' AND '$final' AND id_categoria = '$codigoCategoria'
         ");
        return $datos->getResultArray();
    }

}
