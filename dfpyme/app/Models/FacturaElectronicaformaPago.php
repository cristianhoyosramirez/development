<?php

namespace App\Models;

use CodeIgniter\Model;

class FacturaElectronicaformaPago extends Model
{
    protected $table      = 'documento_electronico_payment';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['id_de','id_user','id_caja','code_payment','fecha','hora','valor','pago'];
   
}