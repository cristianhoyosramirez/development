<?php

namespace App\Models;

use CodeIgniter\Model;

class productoFabricadoModel extends Model
{
    protected $table      = 'producto_fabricado';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['prod_fabricado','prod_proceso', 'cantidad'];
   
}