<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPrecio3ToProductos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('producto', [
            'precio_3' => [
                'type'       => 'INT',
                'constraint' => 11,   // Tamaño del entero (puedes ajustarlo según lo necesites)
                'default'    => 0,    // Valor por defecto 0
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('producto', 'precio_3');
    }
}
