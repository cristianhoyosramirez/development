<?php

namespace App\Models;

use CodeIgniter\Model;

class tipoClienteModel extends Model
{
    protected $table      = 'tipo_cliente';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['descripcion'];
   
}