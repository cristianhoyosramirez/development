<?php

namespace App\Models;

use CodeIgniter\Model;

class EntradasSalidasManualesModel extends Model
{
    protected $table      = 'entradas_salidas_manuales';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['id_producto','cantidad','nota','id_usuario','fecha','hora', 'id_concepto','inventario_anterior','inventario_actual'];

   
}