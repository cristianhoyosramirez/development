<?php

namespace App\Models;

use CodeIgniter\Model;

class aperturaRegistroModel extends Model
{
    protected $table      = 'apertura_registro';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['idcaja', 'numero'];
}
