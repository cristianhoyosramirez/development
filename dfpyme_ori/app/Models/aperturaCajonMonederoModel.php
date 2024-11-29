<?php

namespace App\Models;

use CodeIgniter\Model;

class aperturaCajonMonederoModel extends Model
{
    protected $table      = 'apertura_cajon_monedero';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['fk_impresora'];
   
}