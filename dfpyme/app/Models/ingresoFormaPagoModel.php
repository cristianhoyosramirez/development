<?php

namespace App\Models;

use CodeIgniter\Model;

class ingresoFormaPagoModel extends Model
{
    protected $table      = 'ingreso_forma_pago';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['idingreso','idformapago','valor'];
   
}