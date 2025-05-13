<?php

namespace App\Models;

use CodeIgniter\Model;

class pagosModel extends Model
{
    protected $table      = 'pagos';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'fecha',
        'hora',
        'documento',
        'valor',
        'propina',
        'total_documento',
        'efectivo',
        'transferencia',
        'total_pago',
        'id_usuario_facturacion',
        'id_mesero',
        'id_estado',
        'id_apertura',
        'recibido_efectivo',
        'recibido_transferencia',
        'cambio',
        'id_factura',
        'saldo',
        'nit_cliente',
        'id_pedido',
        'id_mesa'
    ];

    public function set_ventas_pos($id_apertura)
    {
        $datos = $this->db->query("
    SELECT
    SUM(valor) as valor
    FROM
        pagos
    WHERE
        id_apertura = $id_apertura AND id_estado = 1
    ");
        return $datos->getResultArray();
    }

    public function set_ventas_electronicas($id_apertura)
    {
        $datos = $this->db->query("
        SELECT
        SUM(valor) as valor
        FROM
            pagos
        WHERE
            id_apertura = $id_apertura AND id_estado = 8
    ");
        return $datos->getResultArray();
    }

    function get_id($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
    SELECT
        id_factura
    FROM
        pagos
    WHERE
        fecha BETWEEN '$fecha_inicial' AND '$fecha_final' 
 ");
        return $datos->getResultArray();
    }
    function get_base_iva($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT
        SUM(total - iva) AS base_iva
    FROM
        kardex
    WHERE
        fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND iva > 0 AND ico = 0
 ");
        return $datos->getResultArray();
    }
    function get_base_ico($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT
        SUM(total - ico) AS base_ico
    FROM
        kardex
    WHERE
        fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND ico > 0 AND iva = 0
 ");
        return $datos->getResultArray();
    }
    function get_id_pos($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
    SELECT
        id_factura
    FROM
        pagos
    WHERE
        fecha BETWEEN '$fecha_inicial' AND '$fecha_final' and id_estado=1
 ");
        return $datos->getResultArray();
    }






    function get_id_electronicas($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
    SELECT
        id_factura
    FROM
        pagos
    WHERE
        fecha BETWEEN '$fecha_inicial' AND '$fecha_final' and id_estado=8
 ");
        return $datos->getResultArray();
    }

    function get_costo_total($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT
        SUM(costo*cantidad) AS total_costo
        FROM
         kardex
        WHERE
            fecha BETWEEN '$fecha_inicial' AND '$fecha_final'
 ");
        return $datos->getResultArray();
    }

    function get_ico_total($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT
        SUM(ico) AS total_ico
    FROM
        kardex
    WHERE
        fecha BETWEEN '$fecha_inicial' AND '$fecha_final'
 ");
        return $datos->getResultArray();
    }

    function get_iva_total($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT
        SUM(iva) AS total_iva
    FROM
        kardex
    WHERE
        fecha BETWEEN '$fecha_inicial' AND '$fecha_final'
 ");
        return $datos->getResultArray();
    }
    function get_venta_total($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT
        SUM(valor) AS total_venta
    FROM
        pagos
    WHERE
        fecha BETWEEN '$fecha_inicial' AND '$fecha_final'
 ");
        return $datos->getResultArray();
    }
    function get_min_id($id_apertura)
    {

        $datos = $this->db->query("
        select min(id) as id from pagos where id_apertura=$id_apertura and id_estado =1
 ");
        return $datos->getResultArray();
    }
    function get_min_id_electronico($id_apertura)
    {

        $datos = $this->db->query("
        SELECT MIN(id) AS id 
FROM documento_electronico 
WHERE id_status = 2 
  AND transaccion_id IS NOT NULL 
  AND transaccion_id != '' AND id_apertura=$id_apertura;
 ");
        return $datos->getResultArray();
    }

    function get_max_id_electronico($id_apertura)
    {

        $datos = $this->db->query("
  SELECT MAX(id) AS id 
FROM 
    documento_electronico
WHERE 
    id_status = 2
    AND transaccion_id IS NOT NULL
    AND transaccion_id != ''
    AND id_apertura = $id_apertura;
;
 ");
        return $datos->getResultArray();
    }


    function get_max_id($id_apertura)
    {

        $datos = $this->db->query("
        select max(id) as id from pagos where id_apertura=$id_apertura and id_estado =1
 ");
        return $datos->getResultArray();
    }
    function get_total_registros($id_apertura)
    {

        $datos = $this->db->query("
        select count(id) as total_registros from pagos where id_apertura=$id_apertura and id_estado =1
 ");
        return $datos->getResultArray();
    }
    function get_total_registros_electronicos($id_inicial, $id_final)
    {

        $datos = $this->db->query("
        SELECT count(id) AS id 
FROM 
    documento_electronico
WHERE 
    id_status = 2
    AND transaccion_id IS NOT NULL
    AND transaccion_id != ''
    AND id BETWEEN $id_inicial AND $id_final 
 ");
        return $datos->getResultArray();
    }
    function get_ventas_mesero($fecha, $id_mesero)
    {

        $datos = $this->db->query("
        select sum(total_documento) as total_venta from pagos where fecha between '$fecha' and '$fecha' and id_mesero = $id_mesero
 ");
        return $datos->getResultArray();
    }
    function get_total_ventas_mesero($fecha, $id_mesero)
    {

        $datos = $this->db->query("
        select count(id) as numero_ventas from pagos where fecha between '$fecha' and '$fecha' and id_mesero = $id_mesero
 ");
        return $datos->getResultArray();
    }
    function get_id_mesero($fecha)
    {

        $datos = $this->db->query("
        select DISTINCT(id_mesero) from pagos where fecha between '$fecha' and '$fecha'
 ");
        return $datos->getResultArray();
    }
    function get_ventas_credito($consulta)
    {

        $datos = $this->db->query("
       $consulta
        ");
        return $datos->getResultArray();
    }
    function get_documento($documento)
    {

        $datos = $this->db->query("
        select * from pagos where documento = '$documento'
        ");
        return $datos->getResultArray();
    }
    function get_saldo($id_estado)
    {

        $datos = $this->db->query("
            select sum (saldo) as saldo  from pagos where saldo > 0 and id_estado=$id_estado
        ");
        return $datos->getResultArray();
    }


    public function fiscal_iva($id_inicial, $id_final)
    {
        $datos = $this->db->query("
          SELECT DISTINCT ( valor_iva )
        FROM   kardex
        WHERE  id_factura between $id_inicial and $id_final 
        AND aplica_ico = 'false' and id_estado = 8 
        ");
        return $datos->getResultArray();
    }
    public function total_venta($id_apertura)
    {
        $datos = $this->db->query("
        select sum(valor) as total from pagos where id_apertura= $id_apertura
        ");
        return $datos->getResultArray();
    }
    public function total_venta_fecha($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
            select sum(total_documento) as total  from pagos where fecha between '$fecha_inicial' and '$fecha_final'
        ");
        return $datos->getResultArray();
    }
    public function total_venta_fecha_estado($fecha_inicial, $fecha_final, $id_estado)
    {

        $datos = $this->db->query("
            select sum(total_documento) as total  from pagos where fecha between '$fecha_inicial' and '$fecha_final' and id_estado=$id_estado
        ");
        return $datos->getResultArray();
    }
    public function total_venta_costo($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT 
        sum(valor) AS total
        FROM
        pagos where fecha between '$fecha_inicial' and '$fecha_final'
        ");
        return $datos->getResultArray();
    }
    public function c_x_c($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("SELECT
                                         SUM(total_documento) AS total_por_cobrar
                                    FROM
                                        pagos
                                    WHERE
                                        fecha BETWEEN '$fecha_inicial' AND '$fecha_final' and id_estado = 2 
        ");
        return $datos->getResultArray();
    }
    public function c_x_c_generales()
    {

        $datos = $this->db->query("SELECT
                                         SUM(total_documento) AS total_por_cobrar
                                    FROM
                                        pagos
                                    WHERE
                                         id_estado = 2 
        ");
        return $datos->getResultArray();
    }
    public function abonos($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("SELECT
                                    (total_documento - saldo) AS pagos_recibidos
                                FROM
                                    pagos
                                WHERE
                                    id_estado = 2 AND fecha BETWEEN '$fecha_inicial' AND '$fecha_final' ");
        return $datos->getResultArray();
    }
    public function abonos_generales()
    {

        $datos = $this->db->query("SELECT
                                    (total_documento - saldo) AS pagos_recibidos
                                FROM
                                    pagos where id_estado=2 
                                 ");
        return $datos->getResultArray();
    }

    public function ventas_contado($id_apertura)
    {

        $datos = $this->db->query("SELECT
        SUM(total_documento) AS total
    FROM
        pagos
    WHERE
        id_apertura = $id_apertura AND id_estado = 1
                                 ");
        return $datos->getResultArray();
    }
    public function pago_transferencia($id_factura)
    {

        $datos = $this->db->query("
        SELECT
        recibido_transferencia
    FROM
        pagos
    WHERE
        id = $id_factura AND recibido_transferencia > 0
                                 ");
        return $datos->getResultArray();
    }
    public function pago_efectivo($id_factura)
    {

        $datos = $this->db->query("
        SELECT
        *
    FROM
        pagos
    WHERE
        id_factura = $id_factura
                                 ");
        return $datos->getResultArray();
    }
    public function borrar_remisiones($id_apertura)
    {
        try {
            $this->db->transStart(); // Iniciar transacción

            $this->db->query("
                DELETE FROM pagos WHERE id_apertura = $id_apertura AND id_estado = 7; 
                DELETE FROM kardex WHERE id_apertura = $id_apertura AND id_estado = 7; 
            ");

            $this->db->transComplete(); // Finalizar transacción

            if ($this->db->transStatus() === false) {
                // La transacción falló, por lo que se revierte
                $this->db->transRollback();
                return false;
            } else {
                // La transacción se completó con éxito
                return true;
            }
        } catch (\Exception $e) {
            // Captura cualquier excepción
            return false;
        }
    }
    public function borrar_f_e($id_factura)
    {
        try {
            $this->db->transStart(); // Iniciar transacción

            $this->db->query("
                delete from pagos where id_factura = $id_factura and id_estado = 8;
                delete from kardex where id_factura = $id_factura and id_estado = 8;
            ");

            $this->db->transComplete(); // Finalizar transacción

            if ($this->db->transStatus() === false) {
                // La transacción falló, por lo que se revierte
                $this->db->transRollback();
                return false;
            } else {
                // La transacción se completó con éxito
                return true;
            }
        } catch (\Exception $e) {
            // Captura cualquier excepción
            return false;
        }
    }

    public function total_costo($idInicial, $IdFinal)
    {

        $datos = $this->db->query("
        SELECT
        SUM(costo) AS costo
    FROM
        kardex
    WHERE
        fecha between '$idInicial' and '$IdFinal'");
        return $datos->getResultArray();
    }

    public function pagos($id)
    {

        $datos = $this->db->query("
        select efectivo , transferencia from pagos where id = $id");
        return $datos->getResultArray();
    }
    public function costo($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT sum(costo) AS costo
        FROM kardex
        WHERE fecha BETWEEN '$fecha_inicial' AND '$fecha_final'");
        return $datos->getResultArray();
    }
    public function get_total_ventas_electronicas($id_apertura)
    {

        $datos = $this->db->query("
        SELECT SUM (total) AS total_electronicas
        FROM   documento_electronico
        WHERE  id_apertura = $id_apertura
        AND id_status = 2 ");
        return $datos->getResultArray();
    }
    public function get_total_ventas($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
        SELECT 
        SUM(valor) AS valor
        FROM 
            pagos
        WHERE 
            fecha BETWEEN '$fecha_inicial' AND '$fecha_final';
        ");
        return $datos->getResultArray();
    }
    public function getSaldo($id_factura)
    {

        $datos = $this->db->query("
            select saldo from pagos where id_estado = 8 and id_factura = $id_factura ");
        return $datos->getResultArray();
    }
    public function fechas_impuestos($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
            
            SELECT DISTINCT fecha FROM pagos where fecha between '$fecha_inicial' and '$fecha_final' order by fecha asc;
            
            ");
        return $datos->getResultArray();
    }
    public function fechas_inc($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
            
            SELECT DISTINCT valor_ico FROM kardex where fecha between '$fecha_inicial' and '$fecha_final' ;
            
            ");
        return $datos->getResultArray();
    }
    public function fechas_iva($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
            
            SELECT DISTINCT valor_iva FROM kardex where fecha between '$fecha_inicial' and '$fecha_final';
            
            ");
        return $datos->getResultArray();
    }
    public function total_formas_pago($apertura)
    {
        $datos = $this->db->query("
            
            SELECT 
  distinct (medio_pago)
FROM 
    documento_electronico
WHERE 
    id_apertura = $apertura 

            
            ");
        return $datos->getResultArray();
    }
    public function getDocumentosCosto($fechaInicial, $fechaFinal)
    {
        $datos = $this->db->query("
            
                SELECT
                    id,
                    fecha,
                    documento,
                    valor as total_documento,
                    id_factura,
                    id_estado,
                    nit_cliente,
                    id_estado,
                    id_factura
                FROM
                    pagos where fecha BETWEEN '$fechaInicial' and '$fechaFinal'
            
            ");
        return $datos->getResultArray();
    }
    public function getTotalVenta($fechaInicial, $fechaFinal)
    {
        $datos = $this->db->query("
            
                SELECT
                   sum(valor) as total
                FROM
                    pagos where fecha BETWEEN '$fechaInicial' and '$fechaFinal'
            
            ");
        return $datos->getResultArray();
    }
    public function getUsuarioVenta($fechaInicial, $fechaFinal)
    {
        $datos = $this->db->query("
            
                SELECT DISTINCT
                    (idusuario)
                FROM
                    kardex
                WHERE
                    fecha BETWEEN '$fechaInicial' AND '$fechaFinal'
            
            ");
        return $datos->getResultArray();
    }
}
