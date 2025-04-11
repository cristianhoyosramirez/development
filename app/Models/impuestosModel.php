<?php

namespace App\Models;

use CodeIgniter\Model;

class impuestosModel extends Model
{
    protected $table      = 'impuestos';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['codigo','nombre','descripcion','impuesto','porcentual','estado'];
   
}