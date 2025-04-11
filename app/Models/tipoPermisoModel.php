<?php

namespace App\Models;

use CodeIgniter\Model;

class tipoPermisoModel extends Model
{
    protected $table      = 'tipo_permiso';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['idpermiso', 'idusuario_sistema'];

    public function puede_borrar_de_pedido($id_usuario)
    {
        $datos = $this->db->query("
        SELECT idtipo_permiso
        FROM tipo_permiso where idpermiso=91 and idusuario_sistema='$id_usuario'
        ");
        return $datos->getResultArray();
    }

    public function puede_borrar_de_pedido_y_editar($id_usuario)
    {
        $datos = $this->db->query("
        SELECT idtipo_permiso
        FROM tipo_permiso where idpermiso=93 and idusuario_sistema='$id_usuario'
        ");
        return $datos->getResultArray();
    }

    public function puede_borrar_de_pedido_y_editar_despues_de_impreso_comanda($id_usuario)
    {
        $datos = $this->db->query("
        SELECT idtipo_permiso
        FROM tipo_permiso where idpermiso=95 and idusuario_sistema='$id_usuario'
        ");
        return $datos->getResultArray();
    }

    public function tipo_permiso($id_usuario)
    {
        $datos = $this->db->query("
        SELECT idusuario_sistema
        FROM   tipo_permiso
        WHERE  idpermiso = 92
               AND idusuario_sistema = $id_usuario
        ");
        return $datos->getResultArray();
    }
}
