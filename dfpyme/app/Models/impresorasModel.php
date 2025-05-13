<?php

namespace App\Models;

use CodeIgniter\Model;

class impresorasModel extends Model
{
    protected $table      = 'impresora';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['nombre'];
   
}