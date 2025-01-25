<?php

namespace App\Models;

use CodeIgniter\Model;

class tipoInventarioModel extends Model
{
    protected $table      = 'tipo_inventario';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['nombre','descripcion'];
   
}