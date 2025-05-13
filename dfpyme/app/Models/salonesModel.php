<?php

namespace App\Models;

use CodeIgniter\Model;

class salonesModel extends Model
{
    protected $table      = 'salones';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['id','nombre'];
   
}