<?php

namespace App\Models;

use CodeIgniter\Model;

class ProveedorModel extends Model
{
    protected $table      = 'proveedor';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'codigointernoproveedor',
        'nitproveedor',
        'idregimen',
        'razonsocialproveedor',
        'nombrecomercialproveedor',
        'descripcionproveedor',
        'direccionproveedor',
        'idciudad',
        'telefonoproveedor',
        'celularproveedor',
        'faxproveedor',
        'emailproveedor',
        'webproveedor',
        'estadoproveedor',
    ];

    public function autoComplete($valor)
    {

        $datos = $this->db->query("
                select codigointernoproveedor,nombrecomercialproveedor,nitproveedor from  proveedor 
                where nombrecomercialproveedor ilike '%$valor%' or nitproveedor ilike '%$valor%'
        ");
        return $datos->getResultArray();
    }
    
}
