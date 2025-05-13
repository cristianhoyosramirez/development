<?php

namespace App\Models;

use CodeIgniter\Model;

class municipiosModel extends Model
{
    protected $table      = 'municipio';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'code_depto',
        'code',
        'depto',
        'nombre'
    ];
}
