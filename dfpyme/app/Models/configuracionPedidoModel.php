<?php

namespace App\Models;

use CodeIgniter\Model;

class configuracionPedidoModel extends Model
{
    protected $table      = 'configuracion_pedido';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'agregar_item', 'propina', 'mesero_pedido',
        'valor_defecto_propina', 'sub_categoria', 'borrar_remisiones', 'partir_comanda', 'producto_favoroitos', 'requiere_mesa',
        'encabezado_factura', 'pie_factura','eliminar_factura_electronica','impuesto','comanda','calculo_propina','url','altura',
        'codigo_pantalla',
        'notaPedido'
    ];
}
