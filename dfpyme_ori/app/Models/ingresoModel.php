<?php

namespace App\Models;

use CodeIgniter\Model;

class ingresoModel extends Model
{
    protected $table      = 'ingreso';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['numero','concepto','tipo','id_relacion','fecha','valor','estado','saldo','nitcliente','idcaja','idusuario'];
   
}