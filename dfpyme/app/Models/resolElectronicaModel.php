<?php

namespace App\Models;

use CodeIgniter\Model;

class resolElectronicaModel extends Model
{
    protected $table      = 'resolucion_electronica';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'numero',
        'date_begin',
        'vigency',
        'date_end',
        'prefijo',
        'number_begin',
        'number_end',
        'consecutive',
        'flexible',
        'alerta'

    ];
}
