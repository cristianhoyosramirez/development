<?php

namespace App\Models;

use CodeIgniter\Model;

class medioPagoModel extends Model
{
    protected $table      = 'medio_pago';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['codigo','nombre','estado'];
   
}