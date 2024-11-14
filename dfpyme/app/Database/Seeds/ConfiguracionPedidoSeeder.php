<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ConfiguracionPedidoSeeder extends Seeder
{
    public function run()
    {
        //
        $data = [
            'eliminar_factura_electronica' => 6830,
        ];

        // Asumiendo que quieres actualizar una fila específica
        $this->db->table('configuracion_pedido')->update($data, ['id' => 1]); // Cambia el ID según corresponda
    }
}
