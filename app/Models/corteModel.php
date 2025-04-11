<?php

namespace App\Models;

use CodeIgniter\Model;

class corteModel extends Model
{
    protected $table      = 'corte';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['numero','fecha'];
    

}