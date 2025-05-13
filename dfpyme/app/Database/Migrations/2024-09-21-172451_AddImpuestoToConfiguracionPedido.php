<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImpuestoToConfiguracionPedido extends Migration
{
    public function up()
    {
        $fields = [
            'impuesto' => [
                'type' => 'BOOLEAN',
                'default' => true, // Optional: set a default value
                'null' => false,
            ],
        ];

        $this->forge->addColumn('configuracion_pedido', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('configuracion_pedido', 'impuesto');
    }
}

