<?php

namespace App\Models;

use CodeIgniter\Model;

class duplicadoFacturaModel extends Model
{
    protected $table      = 'duplicado_factura';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['nit_cliente', 'valor_factura', 'numero_factura', 'fecha_factura','id_factura', 'id_usuario','horafactura_venta','fk_mesa','numero_pedido'];
}
