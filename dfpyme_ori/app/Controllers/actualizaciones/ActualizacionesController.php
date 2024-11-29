<?php

namespace App\Controllers\actualizaciones;

use App\Controllers\BaseController;

class ActualizacionesController extends BaseController
{
    public function index()
    {

        // return view('home/home');
    }

    function Bd()
    {

        /**
         * Actualiazacion del campo 
         */

        $impuestos = model('configuracionPedidoModel')->set('impuesto', 'true')->update();

        /**
         * Agregar a la tabla configuracion pedido una columna para agregar la url 
         */
        $update_bd = model('ActualizacionesModel')->add_colum_url();

        /**
         * Agregar a la tabla configuracion pedido una columna para la altura de las mesas 
         * 11 de Octubre de 2024  
         */
        $update_bd = model('ActualizacionesModel')->add_colum_altura();
        $update_altura = model('configuracionPedidoModel')->set('altura', 30)->update();

        /**
         * Agregar a la tabla configuracion pedido una columna para visualizar el codigo en pantalla o no 
         * Este campo me permite ver en pantalla al momento de llamar los productos si concateno el codigo con el nombre del producto 
         * 12 de Octubre de 2024 
         */
        $alter_codigo = model('ActualizacionesModel')->add_colum_codigo();
        $update_codigo = model('configuracionPedidoModel')->set('codigo_pantalla', false)->update();


        /**
         * Fecha de creación 2024-10-13 
         * Adicion de la  columna nombre_comercial  a la tabla medio_pago para mstrar un nombre de medio de pago entendible  
         */
        $nombre_comercial = model('ActualizacionesModel')->add_colum_nombre_comercial();

        /**
         * Fecha de creación 2024-10-13 
         * Despues de crear dicha columna la actualizamos con los nombres correctos 
         */
        $actualizar_nombre_comercial = model('ActualizacionesModel')->update_nombre_comercial();


        /**
         * Fecha de creación 2024-10-13 
         * agregar otro campo a la tabla forma pago el cual es ruta donde se almacena la ruta para ubicar el icono 
         */

         $add_column_ruta=model('ActualizacionesModel')->add_column_ruta();



        $session = session();
        $session->setFlashdata('iconoMensaje', 'success');
        return redirect()->to(base_url('pedidos/mesas'))->with('mensaje', 'Actualización a base de datos éxitosa ');
    }
}
