<?php

namespace App\Libraries;

class estado_factura
{
    public function getUuid($id_fact)
    {

        $uuid = model('facturaElectronicaModel')->select('transaccion_id')->where('id', $id_fact)->first();

        if ($uuid) {
            return $uuid['transaccion_id'];
        }

        
    }
}
