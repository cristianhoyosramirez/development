<?php

namespace App\Models;

use CodeIgniter\Model;

class dianModel extends Model
{
    protected $table      = 'dian';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = [ 'numeroresoluciondian', 'fechadian', 'rangoinicialdian', 'rangofinaldian', 
    'inicialestatica', 'finalestatica', 'texto_inicial', 'texto_final', 'id_modalidad', 
    'vigencia', 'id_caja', 'vigencia_mes','alerta_facturacion'];
   
}