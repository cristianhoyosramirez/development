<?php

namespace App\Models;

use CodeIgniter\Model;

class KardexConceptoModel extends Model
{
    protected $table      = 'concepto_kardex';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['idoperacion','nombre'];

   
}