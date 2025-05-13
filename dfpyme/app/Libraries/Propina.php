<?php

namespace App\Libraries;

class Propina
{
    /**
     * Calcular imuestos
     * @param   $cod(igo_interno
     * 
     */
    public function calcularPropina($id_mesa)
    {
        // Obtener el valor total del pedido
        $valor_pedido = model('pedidoModel')->select('valor_total')->where('fk_mesa', $id_mesa)->first();

        // Obtener la configuración de la propina
        $tipo_propina = model('configuracionPedidoModel')->select('propina')->first();
        $temp_porcentaje_propina = model('configuracionPedidoModel')->select('valor_defecto_propina')->first();

        // Calcular el porcentaje de propina
        $porcentaje_propina = $temp_porcentaje_propina['valor_defecto_propina'] / 100;


        
        if ($tipo_propina['propina'] == 1) {  // Calcular la propina con redondeo 
            $temp_propina = $valor_pedido['valor_total'] * $porcentaje_propina;
            // Redondear la propina al valor más cercano a mil
            //$propina = round($temp_propina ) ;
            $propina = ceil($temp_propina / 100) * 100;
        } else {   // Calcular la propina sin  redondeo 
            $temp_propina = $valor_pedido['valor_total'] * $porcentaje_propina;
            $propina =$temp_propina ;
        }

        $model = model('pedidoModel');
        //$actualizar = $model->set('valor_total', $valor_pedido['valor_total']+$propina);
        $actualizar = $model->set('propina', $propina);
        $actualizar = $model->where('fk_mesa', $id_mesa);
        $actualizar = $model->update();

        // Retornar un arreglo con la propina y el valor del pedido
        return [
            'propina' => $propina,
            'valor_pedido' => $valor_pedido['valor_total']
        ];
    }
}
