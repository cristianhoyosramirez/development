<?php

namespace App\Libraries;

class Inventario
{
    /**
     * Calcular imuestos
     * @param   $cod(igo_interno
     * 
     */
    public function actualizar_inventario($codigointerno, $id_tipo_inventario, $cantidad, $documento, $id_doc)
    {

        $cantidad_inventario = model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto', $codigointerno)->first();
        $id_pro = model('productoModel')->select('id')->where('codigointernoproducto', $codigointerno)->first();
        $inventario_final = $cantidad_inventario['cantidad_inventario'] - $cantidad;

        if ($id_tipo_inventario == 1) {


            $data = [
                'cantidad_inventario' => $inventario_final,

            ];
            $model = model('inventarioModel');
            $actualizar = $model->set($data);
            $actualizar = $model->where('codigointernoproducto', $codigointerno);
            $actualizar = $model->update();
        } elseif ($id_tipo_inventario == 3) {


            /*   $data = [
                'cantidad_inventario' => $inventario_final,

            ];
            $model = model('inventarioModel');
            $actualizar = $model->set($data);
            $actualizar = $model->where('codigointernoproducto', $codigointerno);
            $actualizar = $model->update(); */


            $producto_fabricado = model('productoFabricadoModel')->select('*')->where('prod_fabricado', $codigointerno)->find();

            

            foreach ($producto_fabricado as $detall) {


                $id_producto = model('productoModel')->select('id')->where('codigointernoproducto', $detall['prod_proceso'])->first();
                $cantidad_inventario = model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto', $detall['prod_proceso'])->first();
                $descontar_de_inventario = $cantidad * $detall['cantidad'];

              
               /*  $movimiento = [
                    'fecha' => date('Y-m-d'),
                    'hora' => date("H:i:s"),
                    'id_producto' => $id_producto['id'],
                    'inventario_anterior' => $cantidad_inventario['cantidad_inventario'],
                    'inventario_actual' => $cantidad_inventario['cantidad_inventario'] - $descontar_de_inventario,
                    'id_doc' => $id_doc,
                    'tipo_doc' => $documento,
                    'id_pro_prin' => $id_pro['id']
                ];

                $insertar = model('MovimientoInsumosModel')->insert($movimiento); */

                $data = [
                    //'cantidad_inventario' => $cantidad_inventario['cantidad_inventario'] - $descontar_de_inventario,
                    'cantidad_inventario' => 1,

                ];

                $model = model('inventarioModel');
                $actualizar = $model->set($data);
                $actualizar = $model->where('codigointernoproducto', $detall['prod_proceso']);
                $actualizar = $model->update();
            }
        }
    }
}
