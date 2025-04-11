<?php

namespace App\Models;

use CodeIgniter\Model;

class fiscalModel extends Model
{
    protected $table      = 'fiscal';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'fecha', 'base_0', 'base_ico', 'ico', 'caja', 'registro_inicial',
        'registro_final', 'total_registros', 'consecutivo','total'
    ];
}
