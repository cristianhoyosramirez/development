<?php

namespace App\Models;

use CodeIgniter\Model;

class responsabilidadFiscalModel extends Model
{
    protected $table      = 'responsabilidad_fiscal';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['codigo','descripcion','estado'];
   
}