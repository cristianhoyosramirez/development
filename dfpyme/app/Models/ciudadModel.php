<?php

namespace App\Models;

use CodeIgniter\Model;

class ciudadModel extends Model
{
    protected $table      = 'ciudad';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'idciudad',
        'iddepartamento',
        'numero',
        'nombreciudad',
        'code',
        'code_postal'
    ];
}
