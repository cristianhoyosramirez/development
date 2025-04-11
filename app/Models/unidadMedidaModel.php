<?php

namespace App\Models;

use CodeIgniter\Model;

class unidadMedidaModel extends Model
{
    protected $table      = 'valor_unidad_medida';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'descripcionvalor_unidad_medida',
        'codigo',
        'state'
    ];

    
    
}
