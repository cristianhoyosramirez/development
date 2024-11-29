<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEliminarFacturaElectronicaToConfiguracionPedido extends Migration
{
    public function up()
    {
        //
         $this->forge->addColumn('configuracion_pedido', [
            'eliminar_factura_electronica' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true, // Si quieres permitir valores nulos
            ],
        ]);
    }

    public function down()
    {
        //
    }
}
