<?php

namespace App\Models;

use CodeIgniter\Model;

class aperturaModel extends Model
{
    protected $table      = 'apertura ';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['fecha', 'hora', 'idcaja', 'idturno', 'idusuario', 'valor', 'fecha_y_hora_apertura'];



    public function movimiento_caja($numero_caja,)
    {
        $datos = $this->db->query("
        SELECT
            apertura.id as id_apertura,
            apertura.fecha AS fecha_apertura,
            apertura.hora AS hora_apertura,
            usuario_sistema.nombresusuario_sistema AS usuario_apertura,
            apertura.valor AS valor_apertura,
            usuario_sistema.nombresusuario_sistema AS usuario_cierre,
            fecha_y_hora_apertura
        FROM
            apertura
        INNER JOIN usuario_sistema ON apertura.idusuario = usuario_sistema.idusuario_sistema
        WHERE
        idcaja=$numero_caja
        GROUP BY
            apertura.fecha,
            apertura.hora,
            apertura.idusuario,
            apertura.valor,
            apertura.id,
            usuario_sistema.nombresusuario_sistema
        ORDER BY
            apertura.id desc
         ");
        return $datos->getResultArray();
    }
    public function movimiento_caja_cierrre($id_apertura)
    {
        $datos = $this->db->query("
        SELECT
            cierre.fecha as fecha_cierre,
            cierre.hora AS hora_cierre,
        SUM(cierre_forma_pago.valor) AS valor_cierre,
            usuario_sistema.nombresusuario_sistema AS usuario_cierre,
            cierre.id as id_cierre,
            fecha_y_hora_cierre
        FROM
            cierre
        INNER JOIN cierre_forma_pago ON cierre.id = cierre_forma_pago.idcierre
        INNER JOIN usuario_sistema ON cierre.idusuario = usuario_sistema.idusuario_sistema
        WHERE
            cierre.idapertura = '$id_apertura'
        GROUP BY
            cierre.hora,
            usuario_sistema.nombresusuario_sistema,
            usuario_sistema.nombresusuario_sistema,
            cierre.fecha,
            cierre.id 
         ");
        return $datos->getResultArray();
    }

    public function aperturas($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT fecha,
        hora,
        valor,id
        FROM   apertura
        WHERE  fecha BETWEEN '$fecha_inicial' AND '$fecha_final' 
         ");
        return $datos->getResultArray();
    }

    public function apertura()
    {
        $datos = $this->db->query("
        SELECT
            id,
            fecha,
            hora
        FROM
            apertura
        ORDER BY
            id
        DESC
        
         ");
        return $datos->getResultArray();
    }
}
