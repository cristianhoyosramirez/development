<?php

namespace App\Models;

use CodeIgniter\Model;

class BoletasModel extends Model
{
    protected $table      = 'boletas';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
    'nitcliente',
    'fecha_generacion',
    'hora_generacion',
    'estado',
    'fecha_ingreso',
    'hora_ingreso',
    'observaciones',
    'nombre_qr',
    'localidad'
 ];
}
