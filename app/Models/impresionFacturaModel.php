<?php

namespace App\Models;

use CodeIgniter\Model;

class impresionFacturaModel extends Model
{
    protected $table      = 'impresion_factura';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['id_impresora'];
   
}