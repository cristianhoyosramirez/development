<?php

namespace App\Models;

use CodeIgniter\Model;

class productoMedidaModel extends Model
{
    protected $table      = 'producto_medida';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['codigointernoproducto','idvalor_unidad_medida'];
   
}