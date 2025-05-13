<?php

namespace App\Models;

use CodeIgniter\Model;

class precuentaModel extends Model
{
    protected $table      = 'pre_cuenta';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['id_impresora'];
   
}