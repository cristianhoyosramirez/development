<?php

namespace App\Models;

use CodeIgniter\Model;

class cuentaRetiroModel extends Model
{
    protected $table      = 'cuenta_retiro';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['nombre_cuenta'];

    public function get_cuentas_rubros()
    {
        $datos = $this->db->query("
        SELECT 
            rubro_cuenta_retiro.id,
            nombre_cuenta,
            nombre_rubro
        FROM   rubro_cuenta_retiro
        inner join cuenta_retiro
                ON rubro_cuenta_retiro.id_cuenta_retiro = cuenta_retiro.id 
        ");
        return $datos->getResultArray();
    }
}
