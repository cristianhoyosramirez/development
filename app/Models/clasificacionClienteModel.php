<?php

namespace App\Models;

use CodeIgniter\Model;

class clasificacionClienteModel extends Model
{
    protected $table      = 'clasificacion_cliente';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['descripcion'];
   
}