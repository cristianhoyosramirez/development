<?php

namespace App\Models;

use CodeIgniter\Model;

class credencialesWebServerModel extends Model
{
    protected $table      = 'credencials_web_service';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['auth_token'];
}
