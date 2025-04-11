<?php

namespace App\Models;

use CodeIgniter\Model;

class CodigoPostalModel extends Model
{
    protected $table      = 'code_postal';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['departamento','c_postal','ciudad'];
   
}