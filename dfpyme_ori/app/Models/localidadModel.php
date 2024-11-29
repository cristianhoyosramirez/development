<?php

namespace App\Models;

use CodeIgniter\Model;

class localidadModel extends Model
{
    protected $table      = 'localidad';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['nombre','estado'];
   
}