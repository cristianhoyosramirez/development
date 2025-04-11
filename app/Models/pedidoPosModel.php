<?php

namespace App\Models;

use CodeIgniter\Model;

class pedidoPosModel extends Model
{
    protected $table      = 'pedido_pos';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['fk_usuario','valor_total', 'nota_general','numero_factura','base_iva','impuesto_iva','base_ico','impuesto_ico'];
   
}