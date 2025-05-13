<?php

namespace App\Models;

use CodeIgniter\Model;

class itemFacturaElectronicaModel extends Model
{
    protected $table      = 'item_documento_electronico';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_de',
        'numero',
        'codigo',
        'descripcion',
        'unidad_medida',
        'cantidad',
        'costo',
        'iva',
        'ic',
        'icn',
        'precio_unitario',
        'neto',
        'total',
        'code_standar',
        'item_standar',
        'inventario_anterior',
        'inventario_actual'
    ];

    function set_item_factura($id_factura, $codigo_interno, $nombre_producto, $cantidad, $costo, $iva, $ico, $precio_unitario, $total)
    {
        $inventario=model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto',$codigo_interno)->first();

    
        $data = [
            'id_de' => $id_factura,
            'numero' => 1,
            'codigo' => $codigo_interno,
            'descripcion' => $nombre_producto,
            'unidad_medida' => 94,
            'cantidad' => $cantidad,
            'costo' => $costo,
            'iva' => $iva,
            'ic' => 0,
            'icn' => $ico,
            'precio_unitario' => round($precio_unitario,1), // valor antes de impuestos 
            'neto' => $total,
            'total' => $total*$cantidad,
            'code_standar' => $codigo_interno,
            'item_standar' => '999',
            'inventario_anterior'=>$inventario['cantidad_inventario'],
            'inventario_actual'=>$inventario['cantidad_inventario']-$cantidad

        ];

        $item = $this->db->table('item_documento_electronico');
        $item->insert($data);
        return $this->db->insertID();
    }

    public function getProductos()
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
    public function getProducto($idInicial,$idFinal)
    { 
        $datos = $this->db->query("
           SELECT
            codigo,
            descripcion,
            cantidad,
            costo,
            iva,
            ic,
            icn,
            precio_unitario ,
            item_documento_electronico.neto,
            item_documento_electronico.total,
            documento_electronico.numero,
            fecha,
            nombreproducto,
            documento_electronico.numero 
        FROM
            item_documento_electronico
        INNER JOIN documento_electronico ON documento_electronico.id = item_documento_electronico.id_de
        INNER JOIN producto ON producto.codigointernoproducto = item_documento_electronico.codigo
        WHERE
            item_documento_electronico.id_de BETWEEN $idInicial AND $idFinal;
         ");
        return $datos->getResultArray();
    }
}
