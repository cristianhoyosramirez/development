<?php

namespace App\Libraries;

class Impuestos
{
    /**
     * Calcular imuestos
     * @param   $cod(igo_interno
     * 
     */
    public function calcular_impuestos($codigointerno, $valor_total)
    {


        $resultado = array();
        $base_ico = "";
        $impuesto_ico = "";
        $base_iva = "";
        $impuesto_iva = "";

        //echo $codigointerno."</br>";

        $tiene_ico = model('productoModel')->select('aplica_ico')->where('codigointernoproducto', $codigointerno)->first();
        $id_ico = model('productoModel')->select('id_ico_producto')->where('codigointernoproducto', $codigointerno)->first();
        $valor_ico = model('icoConsumoModel')->select('valor_ico')->where('id_ico', $id_ico['id_ico_producto'])->first();

        $id_iva = model('productoModel')->select('idiva')->where('codigointernoproducto', $codigointerno)->first();
        $valor_iva = model('ivaModel')->select('valoriva')->where('idiva ', $id_iva['idiva'])->first();


        if ($tiene_ico['aplica_ico'] == 't') {
            /* Impuesto ICO */
            $porcentaje_ico = ($valor_ico['valor_ico'] / 100) + 1;
            $base_ico = $valor_total / $porcentaje_ico;
            $impuesto_al_consumo = $valor_total - $base_ico;
            $id_iva = model('productoModel')->select('idiva')->where('codigointernoproducto', $codigointerno)->first();

            $data['base_ico'] = $base_ico;
            $data['ico'] = $impuesto_al_consumo;
            $data['base_iva'] = 0;
            $data['iva'] = 0;
            $data['id_ico'] = $id_ico['id_ico_producto'];
            $data['id_iva'] = $id_iva['idiva'];
            $data['aplica_ico'] = 't';
            $data['valor_ico'] = $valor_ico['valor_ico'];
            $data['valor_iva'] = 0;
            array_push($resultado, $data);
        }

        if ($tiene_ico['aplica_ico'] == 'f') {
            /* Impuesto iva */


            $porcentaje_iva = ($valor_iva['valoriva'] / 100) + 1;
            $base_iva = $valor_total / $porcentaje_iva;
            $iva = $valor_total - $base_iva;

            $data['base_ico'] = 0;
            $data['ico'] = 0;
            $data['base_iva'] = $base_iva;
            $data['iva'] = $iva;
            $data['id_ico'] = $id_ico['id_ico_producto'];
            $data['id_iva'] = $id_iva['idiva'];
            $data['aplica_ico'] = 'f';
            //$data['valor_ico'] = $valor_ico['valor_ico'];
            $data['valor_ico'] = 0;
            $data['valor_iva'] = $valor_iva['valoriva'];
            array_push($resultado, $data);

            /* echo "El producto es ".$codigointerno."</br>";
            echo "El valor total es  ".$valor_total."</br>";
            echo "La base del iva es  ".$base_iva."</br>";
            echo "el porcentaje del iva es   ".$porcentaje_iva."</br>"; */
        }

        return $resultado;
    }

function propina($porcentje_propina, $valor_pedido ){

}

}
