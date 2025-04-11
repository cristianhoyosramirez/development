<?php

namespace App\Models;

use CodeIgniter\Model;

class detallesRutModel extends Model
{
    protected $table      = 'details_rut_client';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'nit_cliente',
        'codigo',
        'descripcion'

    ];
}
