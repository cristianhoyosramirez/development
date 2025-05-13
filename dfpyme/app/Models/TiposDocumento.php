<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposDocumento extends Model
{
    protected $table      = 'documento_identidad';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['codigo','descripcion'];
   
}