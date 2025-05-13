<?php

namespace App\Models;

use CodeIgniter\Model;

class detallesTributariosModel extends Model
{
    protected $table      = 'details_tributary_client';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['nit_cliente','codigo','nombre','descripcion'];
   
}