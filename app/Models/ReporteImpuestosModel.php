<?php

namespace App\Models;

use CodeIgniter\Model;

class ReporteImpuestosModel extends Model
{
    protected $table      = 'reporte_impuestos';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [

        'base_inc_0',
        'inc_0',
        'base_iva_0',
        'iva_0',
        'base_iva_5',
        'iva_5',
        'base_iva_19',
        'iva_19',
        'fecha',
        'total_inc',
        'total_iva',
        'total_venta',
        'dia_proceso',
        'base_inc_8',
        'inc_8',

    ];

    public function actualizar_tabla($data, $fecha, $dia)
    {
        // Accede directamente a la tabla usando el método update()
        return $this->db->table('reporte_impuestos')
            ->where('fecha', $fecha)
            ->where('dia_proceso', $dia)
            ->update($data); // Actualiza los datos
    }

    public function getValores()
    {
        $datos = $this->db->query("
        SELECT *  
        FROM reporte_impuestos  
        ORDER BY dia_proceso ASC ;
        ");
        return $datos->getResultArray();
    }
    public function getTotal($fechaInicial, $fechaFinal)
    {
        $datos = $this->db->query("
            SELECT
            SUM(total) AS total,
            SUM(ico) AS inc,
            SUM(iva) AS iva
            FROM
                kardex
            WHERE
            fecha BETWEEN '$fechaInicial' AND '$fechaFinal'
        ");
        return $datos->getResultArray();
    }

    public function getProductos($fechaInicial, $fechaFinal)
    {
        $datos = $this->db->query("
            SELECT
                nombreproducto,
                kardex.id,
                codigo,
                numerodocumento,
                fecha,
                hora,
                cantidad,
                valor,
                total,
                costo,
                ico,
                iva,
                valor_ico,
                valor_iva,
                descripcionestado,
                valor_unitario
            FROM
                kardex
            INNER JOIN estado ON kardex.id_estado = estado.idestado
            INNER JOIN producto ON producto.codigointernoproducto = kardex.codigo
            WHERE
                fecha BETWEEN '$fechaInicial' AND '$fechaFinal'
            ORDER BY
                fecha
            DESC
        ");
        return $datos->getResultArray();
    }
}
