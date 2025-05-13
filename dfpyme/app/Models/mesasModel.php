<?php

namespace App\Models;

use CodeIgniter\Model;

class mesasModel extends Model
{
    protected $table      = 'mesas';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['fk_salon', 'nombre', 'estado', 'valor_pedido', 'fk_usuario', 'id_mesero'];

    public function mesas()
    {
        $datos = $this->db->query("
        SELECT
            mesas.nombre,
            valor_total,
            usuario_sistema.nombresusuario_sistema AS usuario,
            nota_pedido,fecha_creacion as fecha,
            fk_mesa as id_mesa
        FROM
            pedido
        INNER JOIN mesas ON mesas.id = pedido.fk_mesa
        INNER JOIN usuario_sistema ON usuario_sistema.idusuario_sistema = pedido.fk_usuario;
        ");
        return $datos->getResultArray();
    }

    public function salonMesas()
    {
        $datos = $this->db->query("
        SELECT
            mesas.id AS id,
            salones.nombre AS nombre_salon,
            mesas.nombre AS nombre_mesas
        FROM
            mesas
        INNER JOIN salones ON mesas.fk_salon = salones.id
        ORDER BY
            id ASC;
        ");
        return $datos->getResultArray();
    }
    public function nombre_mesa_salon($id_salon, $id_mesa)
    {
        $datos = $this->db->query("
        SELECT
            fk_salon,
            nombre
        FROM
            mesas
        WHERE
            fk_salon = '$id_salon' AND nombre = '$id_mesa'
        ");
        return $datos->getResultArray();
    }

    public function mesas_salon($fk_salon)
    {
        $datos = $this->db->query("
        SELECT
              id,
              nombre,
              estado,
              valor_pedido,
              usuario_sistema.nombresusuario_sistema
        FROM
              mesas
        INNER JOIN usuario_sistema ON mesas.fk_usuario = usuario_sistema.idusuario_sistema
        WHERE
            fk_salon = '$fk_salon'
        ORDER BY
            id ASC;
        ");
        return $datos->getResultArray();
    }
    public function todas_las_mesas()
    {
        $datos = $this->db->query("
            select * from mesas where nombre!='SISTEMA'
        ");
        return $datos->getResultArray();
    }
    public function buscar_mesa($valor)
    {
        $datos = $this->db->query("
        SELECT
        *
    FROM
        mesas
    WHERE
        nombre ILIKE '%$valor%';
        ");
        return $datos->getResultArray();
    }
    public function buscar_meseros($valor)
    {
        $datos = $this->db->query("
        SELECT
        idusuario_sistema,nombresusuario_sistema , valor_total,mesas.nombre,fk_mesa, mesas.nombre as nombre_mesa,propina
    FROM
        pedido
    inner join usuario_sistema on usuario_sistema.idusuario_sistema = pedido.fk_usuario
    inner join mesas on mesas.id=  pedido.fk_mesa
    WHERE nombresusuario_sistema ILIKE '%$valor%';
    
    
        ");
        return $datos->getResultArray();
    }
}
