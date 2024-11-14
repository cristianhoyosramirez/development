<?php

namespace App\Models;

use CodeIgniter\Model;

class devolucionModel extends Model
{
    protected $table      = 'devolucion_venta';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['numero', 'numerofactura', 'nitcliente', 'fecha', 'idusuario', 'idcaja', 'idturno', 'hora', 'id_apertura', 'fecha_y_hora_devolucion'];

    public function id($fecha_devolucion)
    {
        $datos = $this->db->query("
        select  min(id) as minimo, max(id) as maximo from devolucion_venta where fecha='$fecha_devolucion' 
        ");
        return $datos->getResultArray();
    }
    public function iva($id_minimo, $id_maximo)
    {
        $datos = $this->db->query("
        select DISTINCT 
                iva
        from detalle_devolucion_venta where id_devolucion_venta BETWEEN '$id_minimo' and $id_maximo and iva!=0
        ");
        return $datos->getResultArray();
    }
    public function ico($id_minimo, $id_maximo)
    {
        $datos = $this->db->query("
        select DISTINCT 
                ico
        from detalle_devolucion_venta where id_devolucion_venta BETWEEN '$id_minimo' and $id_maximo and ico!=0
        ");
        return $datos->getResultArray();
    }
    public function iva_devolucion($tarifa_iva, $id_minimo, $id_maximo)
    {
        $datos = $this->db->query("
        SELECT
            SUM(valor*cantidad) AS base
            
        FROM
            detalle_devolucion_venta
        WHERE
            id_devolucion_venta BETWEEN $id_minimo AND $id_maximo AND iva = $tarifa_iva 
        ");
        return $datos->getResultArray();
    }
    public function ico_devolucion($tarifa_ico, $id_minimo, $id_maximo)
    {
        $datos = $this->db->query("
        SELECT
            SUM(valor*cantidad) AS base  
        FROM
            detalle_devolucion_venta
        WHERE
            id_devolucion_venta BETWEEN $id_minimo AND $id_maximo AND ico = $tarifa_ico
        ");
        return $datos->getResultArray();
    }

    public function min_id($hora_inicial, $fecha_inicial)
    {
        $datos = $this->db->query("
        select min (id) as id
        from devolucion_venta
        where hora BETWEEN '$hora_inicial' AND '23:59:59' and fecha='$fecha_inicial';
        ");
        return $datos->getResultArray();
    }


    public function max_id($hora_final, $fecha_final)
    {
        $datos = $this->db->query("
        select max (id) as id
        from devolucion_venta 
        where hora BETWEEN '$hora_final' AND '23:59:59' and fecha='$fecha_final';
        ");
        return $datos->getResultArray();
    }


    public function resutado_suma_entre_fechas($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT
            SUM(valor_total_producto) AS total,
            SUM(cantidad) AS cantidad,
            (valor_total_producto / cantidad) AS valor_unitario,
            producto.nombreproducto,
            codigo
        FROM
            detalle_devolucion_venta
        INNER JOIN producto ON detalle_devolucion_venta.codigo = producto.codigointernoproducto
        WHERE
            fecha_venta BETWEEN '$fecha_inicial' AND '$fecha_final'
        GROUP BY
            detalle_devolucion_venta.cantidad,
            detalle_devolucion_venta.valor_total_producto,
            detalle_devolucion_venta.codigo,
            producto.nombreproducto
        ");
        return $datos->getResultArray();
    }
    public function total($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT
           SUM(valor_total_producto) AS total
        FROM
            detalle_devolucion_venta
        WHERE
            fecha_venta BETWEEN '$fecha_inicial' AND '$fecha_final'
        ");
        return $datos->getResultArray();
    }

    public function resutado_suma_entre_fecha_y_hora_final($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT
            SUM(valor_total_producto) AS total,
            SUM(cantidad) AS cantidad,
            (valor_total_producto / cantidad) AS valor_unitario,
            producto.nombreproducto,
            codigo
        FROM
            detalle_devolucion_venta
        INNER JOIN producto ON detalle_devolucion_venta.codigo = producto.codigointernoproducto
        WHERE
            fecha_y_hora_venta BETWEEN '$fecha_inicial' AND '$fecha_final'
        GROUP BY
            detalle_devolucion_venta.cantidad,
            detalle_devolucion_venta.valor_total_producto,
            detalle_devolucion_venta.codigo,
            producto.nombreproducto          
        ");
        return $datos->getResultArray();
    }

    public function total_con_hora_final($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT
           SUM(valor_total_producto) AS total
        FROM
            detalle_devolucion_venta
        WHERE
            fecha_y_hora_venta BETWEEN '$fecha_inicial' AND '$fecha_final'
        ");
        return $datos->getResultArray();
    }
    public function resutado_suma_entre_fecha_con_hora_final($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT
            SUM(valor_total_producto) AS total,
            SUM(cantidad) AS cantidad,
            (valor_total_producto / cantidad) AS valor_unitario,
            producto.nombreproducto,
            codigo
        FROM
            detalle_devolucion_venta
        INNER JOIN producto ON detalle_devolucion_venta.codigo = producto.codigointernoproducto
        WHERE
            fecha_y_hora_venta BETWEEN '$fecha_inicial' AND '$fecha_final'
        GROUP BY
            detalle_devolucion_venta.cantidad,
            detalle_devolucion_venta.valor_total_producto,
            detalle_devolucion_venta.codigo,
            producto.nombreproducto          
        ");
        return $datos->getResultArray();
    }

    public function total_con_hora_final_y_final($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT
           SUM(valor_total_producto) AS total
        FROM
            detalle_devolucion_venta
        WHERE
            fecha_y_hora_venta BETWEEN '$fecha_inicial' AND '$fecha_final'
        ");
        return $datos->getResultArray();
    }
    public function devoluciones($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT
            id,fecha,hora
        FROM
            devolucion_venta
        WHERE fecha_y_hora_devolucion BETWEEN '$fecha_inicial' AND '$fecha_final'
        ");
        return $datos->getResultArray();
    }
    public function devoluciones_total($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT  sum(valor_total_producto) as total_devoluciones
      
        FROM detalle_devolucion_venta WHERE fecha_y_hora_venta BETWEEN '$fecha_inicial' AND '$fecha_final';

        ");
        return $datos->getResultArray();
    }
    public function devoluciones_total_cierre($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT  sum(valor_total_producto) as total_devoluciones
      
        FROM detalle_devolucion_venta WHERE fecha_y_hora_venta BETWEEN '$fecha_inicial' AND '$fecha_final';

        ");
        return $datos->getResultArray();
    }

    public function sumar_devoluciones($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT  sum(valor_total_producto) as total_devoluciones
      
        FROM detalle_devolucion_venta WHERE fecha_y_hora_venta BETWEEN '$fecha_inicial' AND '$fecha_final';

        ");
        return $datos->getResultArray();
    }

    public function tarifa_iva($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        select distinct(iva) 
        from detalle_devolucion_venta 
        where fecha_y_hora_venta between '$fecha_inicial' and '$fecha_final'
        ");
        return $datos->getResultArray();
    }


    public function devolucion_iva($tarifa_iva, $fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT
            SUM(valor*cantidad) AS base
            
        FROM
            detalle_devolucion_venta
        WHERE
        fecha_y_hora_venta BETWEEN '$fecha_inicial' AND '$fecha_final' AND iva = $tarifa_iva 
        ");
        return $datos->getResultArray();
    }

    public function tarifa_ico($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        select distinct(ico) 
        from detalle_devolucion_venta 
        where fecha_y_hora_venta between '$fecha_inicial' and '$fecha_final'
        ");
        return $datos->getResultArray();
    }
    
    public function devolucion_ico($tarifa_ico, $fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT
            SUM(valor*cantidad) AS base
            
        FROM
            detalle_devolucion_venta
        WHERE
        fecha_y_hora_venta BETWEEN '$fecha_inicial' AND '$fecha_final' AND ico = $tarifa_ico 
        ");
        return $datos->getResultArray();
    }
}
