<?php

namespace App\Models;

use CodeIgniter\Model;

class rubrosModel extends Model
{
    protected $table      = 'rubro_cuenta_retiro';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['id_cuenta_retiro', 'nombre_rubro'];
}
