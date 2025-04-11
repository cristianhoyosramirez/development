<?php

namespace App\Models;

use CodeIgniter\Model;

class EntradasSalidasModel extends Model
{
    protected $table      = 'entradas_salidas';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['id_documento', 'id_concepto_kardex', 'id_operacion', 'fecha', 'inventario_anterior', 'tabla'];

    public function datos()
    {
        $datos = $this->db->query("
        SELECT 
    producto.nombreproducto,
    entradas_salidas_manuales.cantidad,
    entradas_salidas_manuales.saldo_anterior,
    entradas_salidas_manuales.nuevo_saldo,
    tipo_documento.nombre,
    usuario_sistema.nombresusuario_sistema, 
    fecha 
FROM 
    entradas_salidas_manuales
INNER JOIN 
    producto 
    ON producto.id = entradas_salidas_manuales.id
INNER JOIN 
    tipo_documento 
    ON tipo_documento.id = entradas_salidas_manuales.tipo_movimiento
INNER JOIN 
    usuario_sistema 
    ON usuario_sistema.idusuario_sistema = entradas_salidas_manuales.id_usuario;


       ");
        return $datos->getResultArray();
    }



    public function Entradas_salidas($id,$id_producto)
    {
        $datos = $this->db->query("
        
            SELECT 
                usuario_sistema.nombresusuario_sistema AS usuario, 
                concepto_kardex.nombre AS concepto_kardex, 
                producto.nombreproducto AS nombreproducto,
                inventario_anterior,
                inventario_actual,
                cantidad,
                fecha,
                entradas_salidas_manuales.id,
                nota,
                hora
            FROM 
                entradas_salidas_manuales
            INNER JOIN 
                usuario_sistema ON usuario_sistema.idusuario_sistema = entradas_salidas_manuales.id_usuario 
            INNER JOIN 
                concepto_kardex ON concepto_kardex.id = entradas_salidas_manuales.id_concepto
            INNER JOIN 
                producto ON producto.id = entradas_salidas_manuales.id_producto
            WHERE 
                entradas_salidas_manuales.id = $id and id_producto=$id_producto;
        



       ");
        return $datos->getResultArray();
    }

    public function getDatosVentas($codigo, $id)
    {
        $datos = $this->db->query("
        
        SELECT 
                item_documento_electronico.cantidad, 
                producto.nombreproducto, 
                item_documento_electronico.inventario_anterior, 
                item_documento_electronico.inventario_actual, 
                documento_electronico.fecha, 
                item_documento_electronico.cantidad, 
                documento_electronico.numero, 
                documento_electronico.nota,
                documento_electronico.hora 
        FROM 
            item_documento_electronico 
        INNER JOIN 
            producto ON producto.codigointernoproducto = item_documento_electronico.codigo 
        INNER JOIN 
            documento_electronico ON documento_electronico.id = item_documento_electronico.id_de 
        WHERE 
            item_documento_electronico.id_de = $id 
            AND item_documento_electronico.codigo = '$codigo';

       ");
        return $datos->getResultArray();
    }

    public function getMovimientos($operacion, $fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        
       SELECT * 
        FROM entradas_salidas 
        WHERE id_operacion = $operacion 
        AND fecha BETWEEN '$fecha_inicial' AND '$fecha_final';
    

       ");
        return $datos->getResultArray();
    }
    public function getMovimientosAll($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        
       SELECT * 
        FROM entradas_salidas 
        WHERE fecha BETWEEN '$fecha_inicial' AND '$fecha_final';
    

       ");
        return $datos->getResultArray();
    }
}
