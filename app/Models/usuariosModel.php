<?php

namespace App\Models;

use CodeIgniter\Model;

class usuariosModel extends Model
{
    protected $table      = 'usuario_sistema';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'idtipo',
        'cedulausuario_sistema',
        'nombresusuario_sistema',
        'usuariousuario_sistema',
        'contraseniausuario_sistema',
        'estadousuario_sistema',
        'telefonousuario_sistema',
        'direccion_sistema',
        'pinusuario_sistema',
        'estadousuario_sistema',
        'nombresusuario_sistema'
    ];

    public function id_usuario($pin_eliminacion)
    {
        $datos = $this->db->query("
        SELECT
        idusuario_sistema
    FROM
        usuario_sistema
    WHERE
        pinusuario_sistema = '$pin_eliminacion';
        ");
        return $datos->getResultArray();
    }
    public function usuario_permiso_eliminacion($id_usuario)
    {
        $datos = $this->db->query("
        SELECT
        idusuario_sistema
    FROM
        tipo_permiso
    WHERE
        idpermiso = 90 AND idusuario_sistema = '$id_usuario';
        ");
        return $datos->getResultArray();
    }
    public function usuario_pin($pin)
    {
        $datos = $this->db->query("
        SELECT idusuario_sistema
        FROM   usuario_sistema
        WHERE  pinusuario_sistema = '$pin' 
        ");
        return $datos->getResultArray();
    }

    public function usuario_valido($pin)
    {
        $datos = $this->db->query("
        SELECT *
            FROM usuario_sistema
            WHERE pinusuario_sistema= '$pin'
            AND estadousuario_sistema='true'
        ");
        return $datos->getResultArray();
    }
    public function get_usuarios()
    {
        $datos = $this->db->query("
        SELECT
            idusuario_sistema,
            nombresusuario_sistema
        FROM
            usuario_sistema
        WHERE
            idtipo = 2 AND estadousuario_sistema = 'true'
        ");
        return $datos->getResultArray();
    }
    public function get_listado_usuarios()
    {
        $datos = $this->db->query("
        SELECT
            nombresusuario_sistema,
            usuariousuario_sistema,
            idusuario_sistema,
            tipo.descripciontipo,
            pinusuario_sistema
        FROM
            usuario_sistema
        INNER JOIN tipo ON tipo.idtipo = usuario_sistema.idtipo WHERE estadousuario_sistema='true'
        ");
        return $datos->getResultArray();
    }
    public function nombre_usuario($id)
    {
        $datos = $this->db->query("
            select nombresusuario_sistema from usuario_sistema where idusuario_sistema=$id
        ");
        return $datos->getResultArray();
    }
}
