<?php

namespace App\Models;

use CodeIgniter\Model;

class componentesAtributosProductoModel extends Model
{
    protected $table      = 'componentes';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['nombre','id_atributo'];
   
}