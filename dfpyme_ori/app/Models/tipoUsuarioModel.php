<?php

namespace App\Models;

use CodeIgniter\Model;

class tipoUsuarioModel extends Model
{
    protected $table      = 'tipo';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['descripciontipo'];
}
