<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnFavorito extends Migration
{
    public function up()
    {
        $this->forge->addColumn('producto', [
            'favorito' => [
                'type' => 'BOOLEAN',
                'default' => false, // Optional: set a default value
                'null' => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('producto', 'favorito');
    }
}
