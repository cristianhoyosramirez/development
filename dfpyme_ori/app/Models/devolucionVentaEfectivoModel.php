<?php

namespace App\Models;

use CodeIgniter\Model;

class devolucionVentaEfectivoModel extends Model
{
    protected $table      = 'devolucion_venta_efectivo';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['iddevolucion','valor'];
   
}