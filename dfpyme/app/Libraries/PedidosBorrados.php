<?php

namespace App\Libraries;

class PedidosBorrados
{
    /**
     * Calcular imuestos
     * @param   $cod(igo_interno
     * 
     */
    public function get_pedidos_borrados($fecha_inicial, $fecha_final)
    {

        $sql_count = "SELECT
        count(id) as total from pedidos_borrados where fecha_eliminacion between '$fecha_inicial'  and '$fecha_final' ";
 
        $sql_data = "select * from pedidos_borrados where fecha_eliminacion between '$fecha_inicial'  and '$fecha_final' ";

        
    }
}
