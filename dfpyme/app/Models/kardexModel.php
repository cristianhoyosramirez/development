<?php

namespace App\Models;

use CodeIgniter\Model;

class kardexModel extends Model
{
    protected $table      = 'kardex';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'idcompra',
        'codigo',
        'idusuario',
        'idconcepto',
        'numerodocumento',
        'fecha',
        'hora',
        'cantidad',
        'valor',
        'total',
        'fecha_y_hora_factura_venta',
        'id_categoria',
        'id_apertura',
        'valor_unitario',
        'id_factura',
        'costo',
        'ico',
        'iva',
        'id_estado',
        'valor_ico',
        'valor_iva',
        'aplica_ico',
        'id_pedido',
        'saldo_anterior',
        'nuevo_saldo',
        //'id_usuario'
    ];

    public function get_productos($id_apertura)
    {
        $datos = $this->db->query("
        SELECT DISTINCT
        valor_unitario,
        codigo,id_categoria
    FROM
        kardex
    WHERE
        id_apertura = $id_apertura
        
        ");
        return $datos->getResultArray();
    }
    public function get_iva_fiscales($id_apertura, $valor_iva)
    {
        $datos = $this->db->query("
        SELECT
        SUM(iva) as iva
        FROM
        kardex
        WHERE
        id_apertura = $id_apertura AND valor_iva = $valor_iva AND id_estado = 1
        
        ");
        return $datos->getResultArray();
    }
    public function get_iva_electronico($id_factura, $valor_iva)
    {
        $datos = $this->db->query("
        SELECT
        SUM(iva) as iva
        FROM
        kardex
        WHERE
        id_factura = $id_factura AND valor_iva = $valor_iva AND id_estado = 8
        
        ");
        return $datos->getResultArray();
    }
  /*   public function get_iva_electronico($id_apertura, $valor_iva)
    {
        $datos = $this->db->query("
        SELECT
        SUM(iva) as iva
        FROM
        kardex
        WHERE
        id_apertura = $id_apertura AND valor_iva = $valor_iva AND id_estado = 8
        
        ");
        return $datos->getResultArray();
    } */
    public function get_total($id_apertura, $valor_unitario, $codigo)
    {
        $datos = $this->db->query("
        SELECT
            SUM(total) AS total, sum(cantidad) as cantidad
        FROM
            kardex
        WHERE
            id_apertura = $id_apertura AND codigo = '$codigo' AND valor_unitario = $valor_unitario
        ");
        return $datos->getResultArray();
    }
    public function get_categorias($id_apertura)
    {
        $datos = $this->db->query("
        SELECT DISTINCT
            id_categoria
        FROM
            kardex
        WHERE
        id_apertura = '$id_apertura'
        ");
        return $datos->getResultArray();
    }
    public function get_total_categoria($id_categoria)
    {
        $datos = $this->db->query("
        SELECT
            SUM(valor_total) AS total
        FROM
            reporte_ventas_producto_categorias
        WHERE
            id_categoria=$id_categoria
        ");
        return $datos->getResultArray();
    }
    public function get_factutras_pos($id_factura)
    {
        $datos = $this->db->query("
        SELECT * FROM kardex WHERE id_factura = $id_factura;
        ");
        return $datos->getResultArray();
    }
    public function get_costo($id_factura)
    {
        $datos = $this->db->query("
        select sum (costo * cantidad) as costo from kardex where id_factura=$id_factura
        ");
        return $datos->getResultArray();
    }
    public function get_ico($id_factura)
    {
        $datos = $this->db->query("
        select sum (ico) as ico from kardex where id_factura=$id_factura
        ");
        return $datos->getResultArray();
    }
    public function get_iva($id_factura)
    {
        $datos = $this->db->query("
        select sum (iva) as iva from kardex where id_factura=$id_factura
        ");
        return $datos->getResultArray();
    }
    public function get_inc($id_apertura)
    {
        $datos = $this->db->query("
        select sum(ico) as total  from kardex where id_apertura=$id_apertura and id_estado=1
        ");
        return $datos->getResultArray();
    }
    public function get_inc_electronico($id_factura,$valor_inc)
    {
        $datos = $this->db->query("
        select sum(ico) as total  from kardex where id_factura=$id_factura and valor_ico=$valor_inc and id_estado=8
        ");
        return $datos->getResultArray();
    }
    public function total_inc($id_apertura)
    {
        $datos = $this->db->query("
        select sum(total) as total  from kardex where id_apertura=$id_apertura and id_estado=1 and aplica_ico=true
        ");
        return $datos->getResultArray();
    }
    public function total_inc_electronico($id_factura,$valor_inc)
    {
        $datos = $this->db->query("
        select sum(total) as total  from kardex where id_factura=$id_factura and id_estado=8 and valor_ico=$valor_inc and aplica_ico=true
        ");
        return $datos->getResultArray();
    }

    public function ventas_contado_electronicas($id_inicial , $id_final)
    {
        $datos = $this->db->query("
        SELECT 
        SUM(neto) AS total_ventas
        FROM 
            documento_electronico
        WHERE 
            id_status = 2 
            AND transaccion_id IS NOT NULL 
            AND transaccion_id != ''
            AND id BETWEEN $id_inicial  AND $id_final ;

        ");
        return $datos->getResultArray();
    }
    public function ventas_contado($id_apertura)
    {
        $datos = $this->db->query("
        select sum(total) as total  from kardex where id_apertura=$id_apertura and id_estado=1
        ");
        return $datos->getResultArray();
    }

    public function get_iva_fiscal($id_apertura, $valor_iva)
    {
        $datos = $this->db->query("
            select sum(total) as total from kardex where id_apertura = $id_apertura and id_estado = 1 and valor_iva = $valor_iva 
        ");
        return $datos->getResultArray();
    }
    public function total_iva_electronico($id_factura, $valor_iva)
    {
        $datos = $this->db->query("
            select sum(total) as total from kardex where id_factura = $id_factura and id_estado = 8 and valor_iva = $valor_iva 
        ");
        return $datos->getResultArray();
    }
    
    public function get_inc_calc($id_factura)
    {
        $datos = $this->db->query("
        SELECT DISTINCT ( valor_ico )
        FROM   kardex
        WHERE  id_factura =$id_factura
               AND aplica_ico = true and id_estado=8
        ");
        return $datos->getResultArray();
    }
    public function get_total_inc($id_factura)
    {
        $datos = $this->db->query("
        SELECT sum ( ico ) as total_inc
        FROM   kardex
        WHERE  id_factura =$id_factura
               AND aplica_ico = true and id_estado=8
        ");
        return $datos->getResultArray();
    }
    public function get_iva_calc($id_factura)
    {
        $datos = $this->db->query("
        select distinct (valor_iva ) from kardex where id_factura = $id_factura and aplica_ico= false 
        ");
        return $datos->getResultArray();
    }
    public function get_total_iva($id_factura)
    {
        $datos = $this->db->query("
        select sum (iva ) as total_iva from kardex where id_factura = $id_factura and aplica_ico= false and id_estado=8
        ");
        return $datos->getResultArray();
    }


    public function get_tarifa_ico($id_factura, $valor_ico)
    {
        $datos = $this->db->query("
        select sum (ico) as inc from kardex where id_factura = $id_factura and valor_ico = $valor_ico
        ");
        return $datos->getResultArray();
    }
    public function get_tarifa_iva($id_factura, $valor_iva)
    {
        $datos = $this->db->query("
        select sum (iva) as iva from kardex where id_factura = $id_factura and valor_iva = $valor_iva and id_estado = 8 
        ");
        return $datos->getResultArray();
    }

    public function get_base_iva($id_factura, $valor_iva)
    {
        $datos = $this->db->query("
        select sum (total) as total_iva from kardex where id_factura = $id_factura and valor_iva = $valor_iva and id_estado = 8 
        ");
        return $datos->getResultArray();
    }

    public function get_productos_factura($id_factura)
    {
        $datos = $this->db->query("
        SELECT producto.nombreproducto AS descripcion,
        cantidad,
        codigo,
        valor_unitario AS neto,
        valor_ico,
        valor_iva,
        kardex.aplica_ico,
        total
 FROM kardex
 INNER JOIN producto ON producto.codigointernoproducto = kardex.codigo
 WHERE id_factura= $id_factura and id_estado= 8
        ");
        return $datos->getResultArray();
    }


    public function fiscal_ico($id_inicial, $id_final)
    {
        $datos = $this->db->query("
        SELECT DISTINCT ( valor_ico )
        FROM   kardex
        WHERE  id_factura between $id_inicial and $id_final 
        AND aplica_ico = 'true' and id_estado = 8 
        ");
        return $datos->getResultArray();
    }


    public function temp_categoria($id_apertura)
    {
        $datos = $this->db->query("
            select distinct(id_categoria) from kardex where id_apertura = $id_apertura
        ");
        return $datos->getResultArray();
    }
    public function temp_categoria_productos($id_categoria, $id_apertura)
    {
        $datos = $this->db->query("
        SELECT kardex.codigo, SUM(kardex.cantidad) AS cantidad, producto.nombreproducto 
        FROM kardex 
        INNER JOIN producto ON kardex.codigo = producto.codigointernoproducto 
        WHERE id_categoria = '$id_categoria' AND id_apertura = $id_apertura 
        GROUP BY kardex.codigo, producto.nombreproducto
        ORDER BY producto.nombreproducto ASC;
        ");
        return $datos->getResultArray();
    }
    public function valor_producto($codigo_interno, $id_apertura)
    {
        $datos = $this->db->query("
        SELECT sum(total) AS total_producto
        FROM kardex
        WHERE codigo='$codigo_interno'
        AND id_apertura =$id_apertura;
        ");
        return $datos->getResultArray();
    }
    public function valor_total_categoria($categoria, $id_apertura)
    {
        $datos = $this->db->query("
        SELECT sum(total) AS total_categoria
        FROM kardex
        WHERE id_categoria='$categoria'
        AND id_apertura =$id_apertura;
        ");
        return $datos->getResultArray();
    }

    public function total_venta_iva_5($id_apertura)  //Total de la venta con impuestos 
    {
        $datos = $this->db->query("
        SELECT SUM (total) as total
        FROM kardex
        WHERE valor_iva= 5
          AND id_apertura = $id_apertura
        ");
        return $datos->getResultArray();
    }
    public function total_venta_iva_5_costo($fecha_inicial, $fecha_final)  //Total de la venta con impuestos 
    {
        $datos = $this->db->query("
        SELECT SUM (total) as total
        FROM kardex
        WHERE valor_iva= 5
        and 
        fecha between '$fecha_inicial' and '$fecha_final'
        ");
        return $datos->getResultArray();
    }
    public function total_venta_iva_19_costo($fecha_inicial, $fecha_final)  //Total de la venta con impuestos 
    {
        $datos = $this->db->query("
        SELECT SUM (total) as total
        FROM kardex
        WHERE valor_iva= 19
        and 
        fecha between '$fecha_inicial' and '$fecha_final'
        ");
        return $datos->getResultArray();
    }
    public function total_venta_inc_costo($fecha_inicial, $fecha_final)  //Total de la venta con impuestos 
    {
        $datos = $this->db->query("
        SELECT SUM (total) as total
        FROM kardex
        WHERE valor_ico= 8
        and 
        fecha between '$fecha_inicial' and '$fecha_final'
        ");
        return $datos->getResultArray();
    }
    public function total_venta_inc($id_apertura, $inc)  //Total de la venta con impuestos 
    {
        $datos = $this->db->query("
        select sum (total )as total from kardex where valor_ico= $inc and id_apertura =  $id_apertura and aplica_ico='true'
        ");
        return $datos->getResultArray();
    }
    public function venta_inc($id_apertura)  //Total de la venta con impuestos 
    {
        $datos = $this->db->query("
        select sum (ico )as inc from kardex where valor_ico= 8 and id_apertura =  $id_apertura and aplica_ico='true'
        ");
        return $datos->getResultArray();
    }
    public function venta_iva_5($id_apertura)  // Total del valor del iva 5 % 
    {
        $datos = $this->db->query("
        SELECT SUM (iva) as iva
        FROM kardex
        WHERE valor_iva= 5
          AND id_apertura = $id_apertura
        ");
        return $datos->getResultArray();
    }
    public function venta_iva_5_costo($fecha_inicial, $fecha_final)  // Total del valor del iva 5 % 
    {
        $datos = $this->db->query("
        SELECT SUM (iva) as iva
        FROM kardex
        WHERE valor_iva= 5
        and 
        fecha between '$fecha_inicial' and '$fecha_final'
        ");
        return $datos->getResultArray();
    }
    public function venta_iva_19_costo($fecha_inicial, $fecha_final)  // Total del valor del iva 5 % 
    {
        $datos = $this->db->query("
        SELECT SUM (iva) as iva
        FROM kardex
        WHERE valor_iva= 19
        and 
        fecha between '$fecha_inicial' and '$fecha_final'
        ");
        return $datos->getResultArray();
    }
    public function venta_inc_costo($fecha_inicial, $fecha_final)  // Total del valor del iva 5 % 
    {
        $datos = $this->db->query("
        SELECT SUM (ico) as inc
        FROM kardex
        WHERE valor_ico= 8
        and 
        fecha between '$fecha_inicial' and '$fecha_final'
        ");
        return $datos->getResultArray();
    }

    public function resultado_suma_entre_fechas($fecha_inicial, $fecha_final)  // Total del valor del iva 5 % 
    {
        $datos = $this->db->query("
        SELECT DISTINCT 
                
                codigo,
                valor
            FROM   kardex
            WHERE  fecha BETWEEN '$fecha_inicial' AND '$fecha_final' and idcompra=0 and idconcepto=10
        ");
        return $datos->getResultArray();
    }

    public function reporte_suma_cantidades($fecha_inicial, $fecha_final, $valor_unitario, $codigointernoproducto)
    {
        $datos = $this->db->query("
        SELECT 
    SUM(total) AS valor_total,
    SUM(cantidad) AS cantidad,
    total
FROM 
    kardex
WHERE 
    fecha BETWEEN '$fecha_inicial' AND '$fecha_final'
    AND total = $valor_unitario
    AND codigo = '$codigointernoproducto' 
GROUP BY 
    total;

        ");
        return $datos->getResultArray();
    }
 
    public function reporte_suma_cant($fecha_inicial, $fecha_final, $valor_unitario, $codigointernoproducto)
    {
        $datos = $this->db->query("
     SELECT 
    
    SUM(cantidad) AS cantidad,
    SUM(total) AS valor_total
FROM 
    kardex
WHERE 
    fecha BETWEEN '$fecha_inicial' AND '$fecha_final'
    AND valor = $valor_unitario
    AND codigo = '$codigointernoproducto';

        ");
        return $datos->getResultArray();
    }
    public function get_iva_reportes($apertura)
    {
        $datos = $this->db->query("
        SELECT DISTINCT valor_iva AS iva
        FROM   kardex
        WHERE  aplica_ico = 'false'
        AND id_apertura = $apertura
        ");
        return $datos->getResultArray();
    }
    public function get_iva_reportes_fecha($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        
        SELECT DISTINCT valor_iva AS iva
        FROM   kardex
        WHERE  aplica_ico = 'false'
        AND fecha between '$fecha_inicial' and '$fecha_final'
        ");
        return $datos->getResultArray();
    }
    public function get_ico_reportes_fecha($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        
        SELECT DISTINCT valor_ico AS inc
        FROM   kardex
        WHERE  aplica_ico = 'true'
        AND fecha between '$fecha_inicial' and '$fecha_final'
        ");
        return $datos->getResultArray();
    }
    public function get_inc_reportes($apertura)
    {
        $datos = $this->db->query("
        SELECT DISTINCT valor_ico AS inc
        FROM   kardex
        WHERE  aplica_ico = 'true'
        AND id_apertura = $apertura
        ");
        return $datos->getResultArray();
    }
    public function get_producto($codigo)
    {
        $datos = $this->db->query("
        SELECT id
        FROM kardex
        WHERE codigo = '$codigo'
        ");
        return $datos->getResultArray();
    }
    public function get_total_factura($id_factura)
    {
        $datos = $this->db->query("
        select total_documento as  valor from pagos where id_factura= $id_factura and id_estado = 8
        ");
        return $datos->getResultArray();
    }
    public function get_recibido_transferencia($id_factura)
    {
        $datos = $this->db->query("
        select recibido_transferencia from pagos where id_factura= $id_factura and id_estado = 8
        ");
        return $datos->getResultArray();
    }
    public function get_recibido_efectivo($id_factura)
    {
        $datos = $this->db->query("
        select recibido_efectivo from pagos where id_factura= $id_factura and id_estado = 8
        ");
        return $datos->getResultArray();
    }
    public function cambio($id_factura)
    {
        $datos = $this->db->query("
        select cambio from pagos where id_factura= $id_factura and id_estado = 8
        ");
        return $datos->getResultArray();
    }
    public function items($id_factura)
    {
        $datos = $this->db->query("
        select count(id) as id  from kardex where id_factura= $id_factura and id_estado = 8
        ");
        return $datos->getResultArray();
    }
    public function iva_producto($id_factura, $id_estado)
    {
        $datos = $this->db->query("
            select distinct(valor_iva) as porcentaje_iva  from kardex where id_factura = $id_factura and id_estado = $id_estado
        ");
        return $datos->getResultArray();
    }


    public function total_iva($id_factura, $id_estado)
    {
        $datos = $this->db->query("
           select sum(iva) as iva from kardex where id_factura = $id_factura and id_estado = $id_estado
        ");
        return $datos->getResultArray();
    }

    public function get_inc_pos($id_factura, $id_estado)
    {
        $datos = $this->db->query("
          SELECT
          SUM(ico) AS inc
          FROM
                kardex
          WHERE
                id_factura = $id_factura AND id_estado = $id_estado and aplica_ico='true'
        ");
        return $datos->getResultArray();
    }
    public function get_iva_pos($id_factura, $id_estado)
    {
        $datos = $this->db->query("
          SELECT
          SUM(iva) AS iva
          FROM
                kardex
          WHERE
                id_factura = $id_factura AND id_estado = $id_estado and aplica_ico='false'
        ");
        return $datos->getResultArray();
    }


    public function total_iva_producto($fecha_inicial, $fecha_final, $iva)
    {
        $datos = $this->db->query("
          select sum(iva) as iva   from kardex where fecha between '$fecha_inicial'  and '$fecha_final' and valor_iva = $iva and aplica_ico='false'
        ");
        return $datos->getResultArray();
    }
    public function total_iva_producto_apertura($id_apertura, $iva)
    {
        $datos = $this->db->query("
          select sum(iva) as iva   from kardex where id_apertura=$id_apertura and valor_iva = $iva and aplica_ico='false'
        ");
        return $datos->getResultArray();
    }

    public function total_iva_producto_pos($id_factura, $estado)
    {
        $datos = $this->db->query("
          select sum(iva) as iva   from kardex where id_factura=$id_factura and id_estado=$estado
        ");
        return $datos->getResultArray();
    }

    public function total_iva_fecha($fecha_inicial, $fecha_final, $iva)
    {
        $datos = $this->db->query("
          select sum(total) as total   from kardex where fecha between '$fecha_inicial'  and '$fecha_final' and valor_iva = $iva and aplica_ico='false';
        ");
        return $datos->getResultArray();
    }
    public function total_iva_fecha_apertura($apertura, $iva)
    {
        $datos = $this->db->query("
          select sum(total) as total   from kardex where id_apertura=$apertura and valor_iva = $iva and aplica_ico='false';
        ");
        return $datos->getResultArray();
    }
    public function total_iva_apertura($id_apertura, $iva)
    {
        $datos = $this->db->query("
          select sum(total) as total   from kardex where id_apertura=$id_apertura and valor_iva = $iva and aplica_ico='false';
        ");
        return $datos->getResultArray();
    }

    public function total_inc_producto($fecha_inicial, $fecha_final, $inc)
    {
        $datos = $this->db->query("
          select sum(ico) as inc   from kardex where fecha between '$fecha_inicial'  and '$fecha_final' and valor_ico = $inc and aplica_ico='true'
        ");
        return $datos->getResultArray();
    }
    public function total_inc_producto_apertura($apertura, $inc)
    {
        $datos = $this->db->query("
          select sum(ico) as inc   from kardex where id_apertura=$apertura and valor_ico = $inc and aplica_ico='true'
        ");
        return $datos->getResultArray();
    }

    public function total_inc_fecha($fecha_inicial, $fecha_final, $inc)
    {
        $datos = $this->db->query("
          select sum(total) as total   from kardex where fecha between '$fecha_inicial'  and '$fecha_final' and valor_ico = $inc and aplica_ico='true';
        ");
        return $datos->getResultArray();
    }
    public function total_inc_fecha_apertura($apertura, $inc)
    {
        $datos = $this->db->query("
          select sum(total) as total   from kardex where id_apertura=$apertura and valor_ico = $inc;
        ");
        return $datos->getResultArray();
    }
    public function total_inc_apertura($id_apertura, $inc)
    {
        $datos = $this->db->query("
          select sum(total) as total   from kardex where id_apertura=$id_apertura and valor_ico = $inc and aplica_ico='true';
        ");
        return $datos->getResultArray();
    }

    public function get_distinct_iva($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
          select distinct (valor_iva) as tarifa_iva from kardex where id between $fecha_inicial and $fecha_final and aplica_ico='false';
        ");
        return $datos->getResultArray();
    }
    public function get_distinct_inc($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
          select distinct (valor_ico) as tarifa_inc from kardex where id between $fecha_inicial and $fecha_final and aplica_ico='true';
        ");
        return $datos->getResultArray();
    }

    public function get_valor_inc($fecha_inicial,$tarifa)
    {
        $datos = $this->db->query("
          select sum (ico) as inc from kardex where fecha = '$fecha_inicial' and valor_ico = $tarifa and aplica_ico='true';
        ");
        return $datos->getResultArray();
    }

    public function get_total_inc_fecha($fecha)
    {
        $datos = $this->db->query("
          select sum (ico) as inc from kardex where fecha ='$fecha' and aplica_ico='true';
        ");
        return $datos->getResultArray();
    }

    public function get_total_iva_fecha($fecha)
    {
        $datos = $this->db->query("
          select sum (iva) as iva from kardex where fecha ='$fecha' and aplica_ico='false';
        ");
        return $datos->getResultArray();
    }

    public function get_total_fecha($fecha)
    {
        $datos = $this->db->query("
          select sum (total) as total from kardex where fecha ='$fecha';
        ");
        return $datos->getResultArray();
    }


    public function get_base_inc($fecha_inicial,$tarifa)
    {
        $datos = $this->db->query("
          select sum (total) as total from kardex where fecha = '$fecha_inicial' and valor_ico = $tarifa and aplica_ico='true';
        ");
        return $datos->getResultArray();
    }

    public function get_valor_iva($fecha_inicial,$tarifa)
    {
        $datos = $this->db->query("
          select sum (iva) as iva from kardex where fecha = '$fecha_inicial' and valor_iva = $tarifa and aplica_ico='false';
        ");
        return $datos->getResultArray();
    }
    public function get_tot_iva($fecha_inicial,$tarifa)
    {
        $datos = $this->db->query("
          select sum (total) as total_iva from kardex where fecha = '$fecha_inicial' and valor_iva = $tarifa and aplica_ico='false';
        ");
        return $datos->getResultArray();
    }

    public function get_iva_reporte($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
            select sum (iva) as iva from kardex where fecha BETWEEN '$fecha_inicial' and '$fecha_final' and aplica_ico='false'
        ");
        return $datos->getResultArray();
    }
    public function get_ico_reporte($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
            select sum (ico) as ico from kardex where fecha BETWEEN '$fecha_inicial' and '$fecha_final' and aplica_ico='true'
        ");
        return $datos->getResultArray();
    }

    public function getCosto($id_factura, $id_estado)
    {
        $datos = $this->db->query("
           SELECT
    SUM(costo) AS costo
FROM
    kardex
WHERE
    id_factura = $id_factura AND id_estado = $id_estado;

        ");
        return $datos->getResultArray();
    }
    public function getIva($id_factura, $id_estado)
    {
        $datos = $this->db->query("
           SELECT
    SUM(iva) AS iva
FROM
    kardex
WHERE
    id_factura = $id_factura AND id_estado = $id_estado;

        ");
        return $datos->getResultArray();
    }
    public function getInc($id_factura, $id_estado)
    {
        $datos = $this->db->query("
           SELECT
    SUM(ico) AS ico
FROM
    kardex
WHERE
    id_factura = $id_factura AND id_estado = $id_estado;

        ");
        return $datos->getResultArray();
    }

    public function getTotal($fechaInicial, $fechaFinal,$usuario)
    {
        $datos = $this->db->query("
       SELECT
        SUM(total) as total
        FROM
            kardex
        WHERE
            fecha BETWEEN '$fechaInicial' AND '$fechaFinal' AND idusuario = $usuario;

        ");
        return $datos->getResultArray();
    }

}
