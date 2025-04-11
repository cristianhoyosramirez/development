<?php

namespace App\Models;

use CodeIgniter\Model;

class consecutivoInformeModel extends Model
{
    protected $table      = 'consecutivo_informe';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['fecha','idcaja', 'numero','id_apertura'];
   
}