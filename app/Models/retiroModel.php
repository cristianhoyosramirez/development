<?php

namespace App\Models;

use CodeIgniter\Model;

class retiroModel extends Model
{
    protected $table      = 'retiro';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['fecha', 'hora', 'idcaja', 'idturno', 'idusuario', 'id_apertura', 'id_rubro_cuenta_retiro'];


    public function retiros($fecha_inicial, $fecha_final,$id_cuenta_retiro)
    {
        $datos = $this->db->query("
        SELECT *
        FROM   retiro_forma_pago
        WHERE  fecha BETWEEN '$fecha_inicial' AND '$fecha_final'  and id_cuenta_retiro=$id_cuenta_retiro
         ");
        return $datos->getResultArray();
    }
    public function retiros_cuentas($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT DISTINCT
            (id_cuenta_retiro),
            nombre_cuenta,
            sum(valor) as total 
        FROM
            retiro_forma_pago 
            inner join
            cuenta_retiro 
            on retiro_forma_pago.id_cuenta_retiro = cuenta_retiro.id 
        WHERE
            fecha BETWEEN '$fecha_inicial' AND '$fecha_final' 
        GROUP BY
            retiro_forma_pago.id_cuenta_retiro,
            cuenta_retiro.nombre_cuenta 
        order by
            id_cuenta_retiro asc
         ");
        return $datos->getResultArray();
    }

    public function total_retiros($fecha_inicial, $fecha_final){
        $datos = $this->db->query("
        select sum(valor) as total from retiro_forma_pago where fecha between '$fecha_inicial' and '$fecha_final'
         ");
        return $datos->getResultArray();

    }

    public function retiros_general($fecha_inicial, $fecha_final){
        $datos = $this->db->query("
        SELECT id
        FROM retiro
        WHERE fecha BETWEEN '$fecha_inicial' AND '$fecha_final'
         ");
        return $datos->getResultArray();

    }

}
