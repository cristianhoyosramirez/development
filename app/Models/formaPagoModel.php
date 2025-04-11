<?php

namespace App\Models;

use CodeIgniter\Model;

class formaPagoModel extends Model
{
    protected $table      = 'forma_pago';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['nombreforma_pago','aplica','ruta'];
   
}