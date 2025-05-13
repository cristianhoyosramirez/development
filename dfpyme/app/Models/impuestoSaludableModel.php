<?php

namespace App\Models;

use CodeIgniter\Model;

class impuestoSaludableModel extends Model
{
    protected $table      = 'impuesto_saludable';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['nombre'];
}
