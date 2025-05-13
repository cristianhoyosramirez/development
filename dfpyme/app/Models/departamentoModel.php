<?php

namespace App\Models;

use CodeIgniter\Model;

class departamentoModel extends Model
{
    protected $table      = 'departamento';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = [ 'nombredepartamento', 'idpais','code'];
   
}