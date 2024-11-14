<?php

namespace App\Models;

use CodeIgniter\Model;

class productoFacturaVentaModel extends Model
{
    protected $table = 'producto_factura_venta';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'numerofactura_venta',
        'codigointernoproducto',
        'cantidadproducto_factura_venta',
        'valorunitarioproducto_factura_venta',
        'idmedida',
        'idcolor',
        'valor_descuento',
        'valor_recargo',
        'valor_iva',
        'retorno',
        'valor',
        'costo',
        'id_factura',
        'valor_venta_real',
        'impoconsumo',
        'total',
        'valor_ico',
        'aplica_ico',
        'impuesto_al_consumo',
        'iva',
        'id_iva',
        'aplica_ico',
        'valor_total_producto',
        'fecha_y_hora_venta',
        'fecha_venta',
        'id_categoria',
        'id_impuesto_saludable',
        'valor_impuesto_saludable'

    ];

    public function getProductosFacturaVentaModel($id_factura)
    {
        $datos = $this->db->query("
        SELECT
            producto.codigointernoproducto,
            producto.nombreproducto,
            cantidadproducto_factura_venta,
            valor_venta_real,
            total
        FROM
            public.producto_factura_venta
        INNER JOIN producto ON producto_factura_venta.codigointernoproducto = producto.codigointernoproducto where id_factura='$id_factura'
        ");
        return $datos->getResultArray();
    }

    public function get_ico_iva($id_factura)
    {
        $datos = $this->db->query("
        SELECT  cantidadproducto_factura_venta, valor_iva, valor_venta_real, valor_ico
        FROM producto_factura_venta where id_factura='$id_factura';
        ");
        return $datos->getResultArray();
    }
    public function base_iva($valor_iva, $id_factura)
    {
        $datos = $this->db->query("
        SELECT
            SUM(total) AS compra,
            SUM(valor_venta_real) AS base,
            cantidadproducto_factura_venta
        FROM
            producto_factura_venta
        WHERE
            valor_iva = '$valor_iva' AND id_factura = '$id_factura' and aplica_ico='false'
        GROUP BY
            cantidadproducto_factura_venta;
        ");
        return $datos->getResultArray();
    }
    public function tarifa_iva($id_factura)
    {
        $datos = $this->db->query("
        SELECT DISTINCT
            valor_iva
         FROM
            producto_factura_venta
        WHERE
        id_factura = '$id_factura' and aplica_ico='false';
        ");
        return $datos->getResultArray();
    }
    public function tarifa_iva_kardex($id_factura, $id_estado)
    {
        $datos = $this->db->query("
        SELECT DISTINCT
            valor_iva
         FROM
            producto_factura_venta
        WHERE
        id_factura = '$id_factura' and aplica_ico='false';
        ");
        return $datos->getResultArray();
    }

    public function tarifa_ico($id_factura)
    {
        $datos = $this->db->query("
        SELECT DISTINCT
            valor_ico
         FROM
            producto_factura_venta
        WHERE
        id_factura = '$id_factura' and aplica_ico='true';
        ");
        return $datos->getResultArray();
    }

    public function total_compra($valor_ico, $id_factura)
    {
        $datos = $this->db->query("
        SELECT
            SUM(total) AS compra
        FROM
            producto_factura_venta
        WHERE
            valor_ico = '$valor_ico' AND id_factura = '$id_factura' and aplica_ico='true'
        
        ");
        return $datos->getResultArray();
    }
    public function base_ico($valor_ico, $id_factura)
    {
        $datos = $this->db->query("
        SELECT
             SUM(impuesto_al_consumo*cantidadproducto_factura_venta) AS base
             
        FROM
            producto_factura_venta
        WHERE
            valor_ico = '$valor_ico' AND id_factura = '$id_factura' and aplica_ico='true'
        ");
        return $datos->getResultArray();
    }
    public function impuestos($id_factura)
    {
        $datos = $this->db->query("
        SELECT    
        cantidadproducto_factura_venta, 
           iva,impuesto_al_consumo,valor_venta_real
        FROM producto_factura_venta where id_factura='$id_factura';
        ");
        return $datos->getResultArray();
    }
    public function iva($id_inicial, $id_final)
    {
        $datos = $this->db->query("
        SELECT DISTINCT
                        (valor_iva)
        FROM
            producto_factura_venta
        WHERE
            id_factura BETWEEN '$id_inicial' AND '$id_final' and aplica_ico='false' ;
        ");
        return $datos->getResultArray();
    }
    public function ico($id_inicial, $id_final)
    {
        $datos = $this->db->query("
        SELECT DISTINCT
                        (valor_ico)
        FROM
            producto_factura_venta
        WHERE
        id_factura BETWEEN '$id_inicial' AND '$id_final' and aplica_ico='true' and valor_ico!=0;
        ");
        return $datos->getResultArray();
    }
    public function valor_iva($porcentaje_iva, $id_inicial, $id_final)
    {
        //echo "El porcentaje es $porcentaje_iva el registro inicial es $registro_inicial y el registro final es $registro_final" . "</br>";

        $datos = $this->db->query("
        SELECT 
        valor_iva as tarifa_iva,
        sum(
            valor_venta_real*
            cantidadproducto_factura_venta) as base,
            sum(iva*cantidadproducto_factura_venta) as total_iva,
            sum(total) as total
        FROM
            producto_factura_venta
        WHERE
        id_factura BETWEEN '$id_inicial' AND '$id_final' AND aplica_ico = 'false'  AND valor_iva = $porcentaje_iva group by valor_iva
        ");
        return $datos->getResultArray();
    }
    public function valor_ico(
        $porcentaje_ico,
        $id_inicial,
        $id_final
    ) {
        //echo "El porcentaje es $porcentaje_iva el registro inicial es $registro_inicial y el registro final es $registro_final" . "</br>";

        $datos = $this->db->query("
        SELECT 
        valor_ico as tarifa_ico,
        sum(
            valor_venta_real*
            cantidadproducto_factura_venta) as base,
            sum(impuesto_al_consumo*cantidadproducto_factura_venta) as total_ico,
            sum(total) as total
        FROM
            producto_factura_venta
        WHERE
        id_factura BETWEEN '$id_inicial' AND '$id_final' AND aplica_ico = 'true' AND valor_ico != 0 AND valor_ico = $porcentaje_ico group by valor_ico
        ");
        return $datos->getResultArray();
    }
    public function datos_producto(
        $fecha_inicial,

        $fecha_final,

    ) {
        //echo "El porcentaje es $porcentaje_iva el registro inicial es $registro_inicial y el registro final es $registro_final" . "</br>";

        $datos = $this->db->query("
        SELECT *
        FROM producto_factura_venta
        WHERE  fecha_venta BETWEEN '$fecha_inicial' AND '$fecha_final' 
        ");
        return $datos->getResultArray();
    }
    public function datos_consulta_producto($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT
            factura_venta.numerofactura_venta,
            fecha_y_hora_venta,
            nombreproducto,
            (
                total / cantidadproducto_factura_venta
            ) AS precio_unitario,
            factura_venta.nitcliente,
            cantidadproducto_factura_venta,
            total,
            factura_venta.id,
            fecha_venta
        FROM
            producto_factura_venta
        INNER JOIN producto ON producto_factura_venta.codigointernoproducto = producto.codigointernoproducto
        INNER JOIN factura_venta ON producto_factura_venta.id_factura = factura_venta.id
        WHERE
            fecha_venta BETWEEN '$fecha_inicial' AND '$fecha_final'
            order by id desc
        ");
        return $datos->getResultArray();
    }

    public function datos_producto_sumados($min_id, $max_id)
    {
        //echo "El porcentaje es $porcentaje_iva el registro inicial es $registro_inicial y el registro final es $registro_final" . "</br>";

        $datos = $this->db->query("
        SELECT
            cantidadproducto_factura_venta,
           
            producto.nombreproducto,
            producto_factura_venta.codigointernoproducto,
            valor_total_producto
        FROM
            producto_factura_venta
        INNER JOIN producto ON producto_factura_venta.codigointernoproducto = producto.codigointernoproducto
        WHERE
            idproducto_factura_venta BETWEEN $min_id AND $max_id
        ");
        return $datos->getResultArray();
    }

    public function datos_de_producto($fecha_inicial, $fecha_final, $id_categoria)
    {
        $datos = $this->db->query("
        SELECT DISTINCT
            valor_total_producto,
            codigointernoproducto,
            id_categoria
        FROM
            producto_factura_venta
        WHERE
            fecha_venta BETWEEN '$fecha_inicial ' AND '$fecha_final' and id_categoria=$id_categoria;
        ");
        return $datos->getResultArray();
    }

    public function resutado_suma_entre_fechas($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
            SELECT DISTINCT 
                valor_total_producto,
                codigointernoproducto
            FROM   producto_factura_venta
            WHERE  fecha_venta BETWEEN '$fecha_inicial' AND '$fecha_final' 
        ");
        return $datos->getResultArray();
    }
    public function reporte_suma_cantidades($fecha_inicial, $fecha_final, $valor_unitario, $codigointernoproducto)
    {
        $datos = $this->db->query("
        SELECT SUM(total) as valor_total,
                SUM(cantidadproducto_factura_venta) as cantidad,
                valor_total_producto
        FROM   producto_factura_venta
        WHERE  fecha_venta BETWEEN '$fecha_inicial' AND '$fecha_final'
               AND valor_total_producto = $valor_unitario
               AND codigointernoproducto = '$codigointernoproducto' 
        GROUP BY producto_factura_venta.valor_total_producto
        ");
        return $datos->getResultArray();
    }
    public function reporte_suma_cantidades_con_horas($fecha_inicial, $fecha_final, $valor_unitario, $codigointernoproducto)
    {
        $datos = $this->db->query("
        SELECT SUM(total) as valor_total,
                SUM(cantidadproducto_factura_venta) as cantidad,
                valor_total_producto
        FROM   producto_factura_venta
        WHERE  fecha_y_hora_venta BETWEEN '$fecha_inicial' AND '$fecha_final'
               AND valor_total_producto = $valor_unitario
               AND codigointernoproducto = '$codigointernoproducto' 
        GROUP BY producto_factura_venta.valor_total_producto
        ");
        return $datos->getResultArray();
    }

    public function reporte_cantidades($fecha_inicial, $fecha_final, $valor_total_producto, $codigointernoproducto)
    {
        $datos = $this->db->query("
        SELECT
            SUM(total)as total,
            SUM(cantidadproducto_factura_venta) as total_cantidades,
            valor_total_producto,
            nombreproducto,id_categoria
        FROM
            producto_factura_venta
        INNER JOIN producto ON producto_factura_venta.codigointernoproducto = producto.codigointernoproducto
        WHERE
        fecha_venta BETWEEN '$fecha_inicial ' AND '$fecha_final' AND producto_factura_venta.codigointernoproducto = '$codigointernoproducto' AND valor_total_producto =$valor_total_producto
        GROUP BY
            producto_factura_venta.valor_total_producto,
            producto.nombreproducto,
            producto_factura_venta.id_categoria,producto_factura_venta.codigointernoproducto
        ORDER BY producto_factura_venta.codigointernoproducto
        ");
        return $datos->getResultArray();
    }

    public function resutado_entre_fechas($fecha_inicial, $fecha_final)
    {
        //echo "El porcentaje es $porcentaje_iva el registro inicial es $registro_inicial y el registro final es $registro_final" . "</br>";

        $datos = $this->db->query("
        SELECT
            idproducto_factura_venta
        FROM
         producto_factura_venta
        WHERE
            fecha_venta BETWEEN '$fecha_inicial' AND '$fecha_final';
        ");
        return $datos->getResultArray();
    }

    public function resutado_entre_fechas_sin_hora_inicial($fecha_inicial, $fecha_final)
    {
        //echo "El porcentaje es $porcentaje_iva el registro inicial es $registro_inicial y el registro final es $registro_final" . "</br>";

        $datos = $this->db->query("
        SELECT
            idproducto_factura_venta
        FROM
            producto_factura_venta
        WHERE
            fecha_y_hora_venta BETWEEN '$fecha_inicial' AND '$fecha_final';
        ");
        return $datos->getResultArray();
    }
    public function resutado_entre_fechas_y_horas($fecha_inicial, $fecha_final)
    {
        //echo "El porcentaje es $porcentaje_iva el registro inicial es $registro_inicial y el registro final es $registro_final" . "</br>";

        $datos = $this->db->query("
        SELECT
            idproducto_factura_venta
        FROM
            producto_factura_venta
        WHERE
            fecha_y_hora_venta BETWEEN '$fecha_inicial' AND '$fecha_final';;
        ");
        return $datos->getResultArray();
    }
    public function total($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT
            SUM(total) AS total
        FROM
            producto_factura_venta
        WHERE
            fecha_venta BETWEEN '$fecha_inicial' AND '$fecha_final'
        ");
        return $datos->getResultArray();
    }


    public function consulta_entre_fechas_sin_hora_inicial($fecha_inicial, $fecha_final)
    {
        //echo "El porcentaje es $porcentaje_iva el registro inicial es $registro_inicial y el registro final es $registro_final" . "</br>";

        $datos = $this->db->query("
        SELECT DISTINCT
        valor_total_producto,
        codigointernoproducto
        FROM
            producto_factura_venta
        WHERE
            fecha_y_hora_venta BETWEEN '$fecha_inicial ' AND '$fecha_final';
        ");
        return $datos->getResultArray();
    }

    public function total_entre_fechas_sin_hora_inicial($fecha_inicial, $fecha_final)
    {
        //echo "El porcentaje es $porcentaje_iva el registro inicial es $registro_inicial y el registro final es $registro_final" . "</br>";

        $datos = $this->db->query("
        SELECT
            SUM(total) AS total
        FROM
            producto_factura_venta
            
        WHERE
            fecha_y_hora_venta BETWEEN '$fecha_inicial' AND '$fecha_final'
        ");
        return $datos->getResultArray();
    }

    public function consulta_entre_fechas_con_hora_inicial_y_final($fecha_inicial, $fecha_final)
    {
        //echo "El porcentaje es $porcentaje_iva el registro inicial es $registro_inicial y el registro final es $registro_final" . "</br>";

        $datos = $this->db->query("
        SELECT DISTINCT
            valor_total_producto,
            codigointernoproducto
        FROM
            producto_factura_venta
        WHERE
            fecha_y_hora_venta BETWEEN '$fecha_inicial ' AND '$fecha_final';
        ");
        return $datos->getResultArray();
    }

    public function total_entre_fechas_con_hora_inicial_y_final($fecha_inicial, $fecha_final)
    {
        //echo "El porcentaje es $porcentaje_iva el registro inicial es $registro_inicial y el registro final es $registro_final" . "</br>";

        $datos = $this->db->query("
        SELECT
            SUM(total) AS total
        FROM
            producto_factura_venta
            
        WHERE
            fecha_y_hora_venta BETWEEN '$fecha_inicial' AND '$fecha_final'
        ");
        return $datos->getResultArray();
    }

    public function dato_cantidades($fecha_inicial, $fecha_final, $codigointernoproducto)
    {
        //echo "El porcentaje es $porcentaje_iva el registro inicial es $registro_inicial y el registro final es $registro_final" . "</br>";

        $datos = $this->db->query("
        SELECT
        cantidadproducto_factura_venta
    FROM
        producto_factura_venta
    
    WHERE
    fecha_venta BETWEEN '$fecha_inicial' AND '$fecha_final' and codigointernoproducto = '$codigointernoproducto' 
        ");
        return $datos->getResultArray();
    }

    public function total_datos_consulta_producto($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT Sum (total) as total
        FROM   producto_factura_venta
        WHERE  fecha_venta BETWEEN '$fecha_inicial' AND '$fecha_final' 
        ");
        return $datos->getResultArray();
    }
    public function categorias($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT DISTINCT id_categoria
        FROM   producto_factura_venta
        WHERE  fecha_venta BETWEEN '$fecha_inicial' AND '$fecha_final' order by id_categoria; 
        ");
        return $datos->getResultArray();
    }
    public function kardex_categorias($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT DISTINCT id_categoria
        FROM   kardex
        WHERE  fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND id_categoria IS NOT NULL order by id_categoria; 
        ");
        return $datos->getResultArray();
    }
    public function categorias_con_horas($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT DISTINCT id_categoria
        FROM   producto_factura_venta
        WHERE  fecha_y_hora_venta BETWEEN '$fecha_inicial' AND '$fecha_final' order by id_categoria; 
        ");
        return $datos->getResultArray();
    }
    public function get_datos_producto($id_categoria, $fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT 
            Sum(cantidadproducto_factura_venta) as cantidad,
            producto_factura_venta.codigointernoproducto,
            nombreproducto,
            valor_total_producto AS valor_unitario,
            total                AS valor_total,
            idproducto_factura_venta
        FROM   producto_factura_venta
        INNER JOIN producto
        ON
            producto_factura_venta.codigointernoproducto = producto.codigointernoproducto
        WHERE  fecha_venta BETWEEN '$fecha_inicial' AND '$fecha_final'
        AND id_categoria = $id_categoria
        GROUP  BY producto_factura_venta.total,
            producto_factura_venta.codigointernoproducto,
            producto.nombreproducto,
            producto_factura_venta.valor_total_producto,
            producto_factura_venta.idproducto_factura_venta 
        ");
        return $datos->getResultArray();
    }


    public function validar_categoria($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT id_categoria, codigointernoproducto,idproducto_factura_venta
        FROM   producto_factura_venta
        WHERE  fecha_venta BETWEEN '$fecha_inicial' AND '$fecha_final' order by id_categoria; 
        ");
        return $datos->getResultArray();
    }

    public function fiscal_iva($id_apertura)
    {
        $datos = $this->db->query("
        SELECT DISTINCT ( valor_iva )
        FROM   kardex
        WHERE  id_apertura=$id_apertura
        AND aplica_ico = 'false' 
        ");
        return $datos->getResultArray();
    }
  /*   public function fiscal_iva($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT DISTINCT ( valor_iva )
        FROM   producto_factura_venta
        WHERE  fecha_y_hora_venta BETWEEN '$fecha_inicial' AND '$fecha_final'
        AND aplica_ico = 'false' 
        ");
        return $datos->getResultArray();
    } */
    public function datos_iva($fecha_inicial, $fecha_final, $valor_iva)
    {
        $datos = $this->db->query("
        SELECT 
        valor_iva as tarifa_iva,
        sum(
            valor_venta_real*
            cantidadproducto_factura_venta) as base,
            sum(iva*cantidadproducto_factura_venta) as total_iva,
            sum(total) as total
        FROM
            producto_factura_venta
        WHERE
        fecha_y_hora_venta between '$fecha_inicial' and '$fecha_final' AND aplica_ico = 'false'  AND valor_iva = $valor_iva group by valor_iva   
        ");
        return $datos->getResultArray();
    }

    public function fiscal_ico($id_apertura)
    {
        $datos = $this->db->query("
        SELECT DISTINCT ( valor_ico )
        FROM   kardex
        WHERE  id_apertura=$id_apertura
        AND aplica_ico = 'true' 
        ");
        return $datos->getResultArray();
    }
   /*  public function fiscal_ico($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT DISTINCT ( valor_ico )
        FROM   producto_factura_venta
        WHERE  fecha_y_hora_venta BETWEEN '$fecha_inicial' AND '$fecha_final'
        AND aplica_ico = 'true' 
        ");
        return $datos->getResultArray();
    } */

    public function datos_ico($fecha_inicial, $fecha_final, $valor_ico)
    {
        $datos = $this->db->query("
        SELECT 
        valor_ico as tarifa_ico,
        sum(
            valor_venta_real*
            cantidadproducto_factura_venta) as base,
            sum(impuesto_al_consumo*cantidadproducto_factura_venta) as total_ico,
            sum(total) as total
        FROM
            producto_factura_venta
        WHERE
        fecha_y_hora_venta between '$fecha_inicial' and '$fecha_final' AND aplica_ico = 'true'  AND valor_ico = $valor_ico group by valor_ico   
        ");
        return $datos->getResultArray();
    }


    public function datos_producto_factura($id_min, $id_max)
    {

        $datos = $this->db->query("
            
        select codigointernoproducto,valor,cantidadproducto_factura_venta,total,id_factura,idproducto_factura_venta
        from producto_factura_venta where id_factura between $id_min and $id_max
        ");
        return $datos->getResultArray();
    }
    public function buscar_producto_factura($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        select  codigointernoproducto,valor,cantidadproducto_factura_venta,total,id_factura,idproducto_factura_venta
        from producto_factura_venta where fecha_y_hora_venta between $fecha_inicial and $fecha_final
        ");
        return $datos->getResultArray();
    }
    public function datos_producto_factura_cantidad($codigo_interno_producto, $valor, $id_inicial, $id_final)
    {

        $datos = $this->db->query("
        select 
        sum (valor)as total, sum (cantidadproducto_factura_venta) as cantidad 
        from producto_factura_venta where codigointernoproducto='$codigo_interno_producto' and valor='$valor' and id_factura between $id_inicial and $id_final
        ");
        return $datos->getResultArray();
    }

    public function total_venta($id_inicial, $id_final)
    {

        $datos = $this->db->query("
        select sum (valor)as total  from producto_factura_venta where id_factura between $id_inicial and $id_final
        ");
        return $datos->getResultArray();
    }
    public function total_ventas($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        select sum (valor)as total  from producto_factura_venta where fecha_y_hora_venta between $fecha_inicial and $fecha_final
        ");
        return $datos->getResultArray();
    }
    public function get_total_venta($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        select sum (total) as total_venta from producto_factura_venta where fecha_y_hora_venta between '$fecha_inicial' and '$fecha_final'

        ");
        return $datos->getResultArray();
    }
    public function total_registros($id_inicial, $id_final)
    {

        $datos = $this->db->query("
        select count (id)as total  from factura_venta where id between $id_inicial and $id_final
        ");
        return $datos->getResultArray();
    }
    public function total_registro($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        select count (id)as total  from factura_venta where fecha_y_hora_venta between $fecha_inicial and $fecha_final
        ");
        return $datos->getResultArray();
    }
    public function get_impuesto_saluidable($id_factura)
    {

        $datos = $this->db->query("
        SELECT
            SUM(valor_impuesto_saludable) * cantidadproducto_factura_venta AS total_impuesto_saludable
        FROM
            producto_factura_venta
        WHERE
            id_factura = $id_factura
        GROUP BY
            cantidadproducto_factura_venta

        ");
        return $datos->getResultArray();
    }
}
