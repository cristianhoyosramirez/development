<?php

namespace App\Models;

use CodeIgniter\Model;

class borrar_productosModel extends Model
{
    protected $table      = 'productos_borrados';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['codigointernoproducto', 'cantidad','fecha_eliminacion','hora_eliminacion','usuario_eliminacion','pedido'];
}
