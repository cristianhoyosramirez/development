<?php

namespace App\Models;

use CodeIgniter\Model;

class icoConsumoModel extends Model
{
    protected $table      = 'ico_consumo';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['id_ico','valor_ico'];
   
}