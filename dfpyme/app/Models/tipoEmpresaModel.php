<?php

namespace App\Models;

use CodeIgniter\Model;

class tipoEmpresaModel extends Model
{
    protected $table      = 'tipo_empresa';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [ 'nombre'];
}
